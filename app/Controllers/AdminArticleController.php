<?php

declare(strict_types=1);

namespace App\Controllers;

/*
|--------------------------------------------------------------------------
| Admin Article Controller
|--------------------------------------------------------------------------
| This controller handles administrative functionalities for articles.
| Includes draft/revision system, category management, and image uploads.
*/

use Exception;
use App\Models\Article;
use App\Models\Category;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use App\Models\ArticleRevision;
use Plugs\Base\Controller\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminArticleController extends Controller
{
    /**
     * Display article management page
     */
    public function manage(): Response
    {
        try {
            // Get all articles with author info
            $articles = Article::with('author')
                ->latest('created_at')
                ->paginate(15);

            $data = [
                'articles' => $articles,
                'page_title' => 'Manage Articles'
            ];

            return $this->view('admin.articles.manage', $data);
        } catch (Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to load articles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display new article form
     */
    public function newArticle(Request $request): Response
    {
        try {
            // Get active categories from database
            $categories = Category::where('is_active', true)
                ->orderBy('sort_order', 'ASC')
                ->orderBy('name', 'ASC')
                ->get();

            $data = [
                'page_title' => 'Create New Article',
                'categories' => $categories
            ];

            return $this->view('admin.articles.create', $data);
        } catch (Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to load page: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create and publish article
     */
    public function createArticle(Request $request): Response
    {
        try {
            // Get form data
            $data = $request->getParsedBody();

            // Get current user ID
            $userId = $this->getCurrentUserId($request);

            if (!$userId) {
                // Redirect to login if not authenticated
                return $this->redirect('/admin/login');
            }

            // Determine if publishing or saving as draft
            $isPublishing = isset($data['publish']) ||
                (isset($data['action']) && $data['action'] === 'publish');

            // Validate article data
            $errors = $this->validateArticleData($data, $isPublishing);

            if (!empty($errors)) {
                // Store errors in session and redirect back
                $_SESSION['form_errors'] = $errors;
                $_SESSION['form_data'] = $data;
                return $this->redirect('/admin/articles/create');
            }

            // Start transaction
            Article::beginTransaction();

            // Handle featured image upload
            $featuredImagePath = null;
            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                try {
                    $featuredImagePath = $this->handleFeaturedImageUpload($_FILES['featured_image']);
                } catch (Exception $e) {
                    Article::rollBack();

                    $_SESSION['form_errors'] = ['featured_image' => $e->getMessage()];
                    $_SESSION['form_data'] = $data;
                    return $this->redirect('/admin/articles/create');
                }
            }

            // Generate slug from title
            $slug = $this->generateSlug($data['title']);

            // Auto-generate excerpt if not provided
            $excerpt = !empty($data['excerpt'])
                ? $data['excerpt']
                : $this->generateExcerpt($data['content']);

            // Prepare article data
            $articleData = [
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'excerpt' => $excerpt,
                'author_id' => $userId,
                'category_id' => $data['categories'] ?? null,
                'featured_image' => $featuredImagePath,
                'status' => $isPublishing ? 'published' : 'draft',
                'published_at' => $isPublishing ? date('Y-m-d H:i:s') : null,

                // SEO fields
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
            ];

            // Create article
            $article = Article::create($articleData);

            if (!$article) {
                throw new Exception('Failed to create article');
            }

            // Create initial revision
            $this->createRevision(
                $article,
                $userId,
                $isPublishing ? 'Initial publication' : 'Initial draft'
            );

            // Commit transaction
            Article::commit();

            // Clear form data from session
            unset($_SESSION['form_errors'], $_SESSION['form_data']);

            // Set success message
            $_SESSION['success_message'] = $isPublishing
                ? 'Article published successfully!'
                : 'Article saved as draft.';

            // Redirect to article edit page or list
            return $this->redirect('/admin/articles/' . $article->id . '/edit');

        } catch (Exception $e) {
            // Rollback transaction
            if (Article::transactionLevel() > 0) {
                Article::rollBack();
            }

            // Delete uploaded image if article creation failed
            if (isset($featuredImagePath) && file_exists($featuredImagePath)) {
                @unlink($featuredImagePath);
            }

            // Log error
            error_log('Article creation failed: ' . $e->getMessage());

            // Store error and redirect back
            $_SESSION['form_errors'] = ['general' => 'Failed to create article: ' . $e->getMessage()];
            $_SESSION['form_data'] = $data ?? [];

            return $this->redirect('/admin/articles/create');
        }
    }

    /**
     * Handle featured image upload
     */
    private function handleFeaturedImageUpload(array $fileData): string
    {
        // Define upload path
        $uploadPath = defined('BASE_PATH')
            ? BASE_PATH . '/uploads/articles/featured'
            : dirname(__DIR__, 2) . '/uploads/articles/featured';

        // Create uploader instance
        $uploader = new FileUploader($uploadPath);

        // Configure for images only
        $uploader->imagesOnly(5 * 1024 * 1024) // 5MB max
            ->setImageDimensions(
                maxWidth: 2000,
                maxHeight: 2000,
                minWidth: 400,
                minHeight: 300
            )
            ->generateUniqueName(true)
            ->organizeByDate(true)
            ->preventDuplicates(false)
            ->allowSvg(false); // Disable SVG for security

        // Create UploadedFile instance
        $file = new UploadedFile($fileData);

        // Get user identifier for rate limiting
        $userIdentifier = $_SESSION['auth_user_id'] ?? $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        // Upload file
        $result = $uploader->upload($file, null);

        // Return the relative path (for storage in database)
        return $result['relative_path'];
    }

    /**
     * Create article revision
     */
    private function createRevision(Article $article, int $userId, string $notes = ''): bool
    {
        try {
            // Get next revision number
            $lastRevision = ArticleRevision::where('article_id', $article->id)
                ->orderBy('revision_number', 'DESC')
                ->first();

            $revisionNumber = $lastRevision ? $lastRevision->revision_number + 1 : 1;

            // Create revision
            ArticleRevision::create([
                'article_id' => $article->id,
                'revision_number' => $revisionNumber,
                'title' => $article->title,
                'content' => $article->content,
                'excerpt' => $article->excerpt,
                'author_id' => $userId,
                'revision_notes' => $notes
            ]);

            return true;
        } catch (Exception $e) {
            // Log error but don't fail the main operation
            error_log("Failed to create revision: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Validate article data
     */
    private function validateArticleData(array $data, bool $isPublishing = false): array
    {
        $errors = [];

        // Title validation
        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($data['title']) > 255) {
            $errors['title'] = 'Title must not exceed 255 characters';
        }

        // Content validation (stricter for publishing)
        if ($isPublishing) {
            if (empty($data['content'])) {
                $errors['content'] = 'Content is required';
            } elseif (strlen(strip_tags($data['content'])) < 50) {
                $errors['content'] = 'Content must be at least 50 characters';
            }
        }

        // Excerpt validation
        if (!empty($data['excerpt']) && strlen($data['excerpt']) > 500) {
            $errors['excerpt'] = 'Excerpt must not exceed 500 characters';
        }

        // SEO title validation
        if (!empty($data['seo_title']) && strlen($data['seo_title']) > 60) {
            $errors['seo_title'] = 'SEO title should not exceed 60 characters';
        }

        // SEO description validation
        if (!empty($data['seo_description']) && strlen($data['seo_description']) > 160) {
            $errors['seo_description'] = 'SEO description should not exceed 160 characters';
        }

        // SEO keywords validation
        if (!empty($data['seo_keywords']) && strlen($data['seo_keywords']) > 255) {
            $errors['seo_keywords'] = 'SEO keywords should not exceed 255 characters';
        }

        // Category validation (optional but recommended for publishing)
        if ($isPublishing && empty($data['categories'])) {
            $errors['categories'] = 'Please select a category for your article';
        }

        return $errors;
    }

    /**
     * Generate URL-friendly slug from title
     */
    private function generateSlug(string $title): string
    {
        // Convert to lowercase
        $slug = strtolower(trim($title));

        // Replace spaces with hyphens
        $slug = preg_replace('/\s+/', '-', $slug);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9-]/', '', $slug);

        // Remove consecutive hyphens
        $slug = preg_replace('/-+/', '-', $slug);

        // Trim hyphens from ends
        $slug = trim($slug, '-');

        // Ensure we have something
        if (empty($slug)) {
            $slug = 'article-' . time();
        }

        // Ensure uniqueness
        $originalSlug = $slug;
        $counter = 1;

        while (Article::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Generate excerpt from content
     */
    private function generateExcerpt(string $content, int $length = 200): string
    {
        // Strip HTML tags
        $text = strip_tags($content);

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        // Trim to specified length
        if (strlen($text) > $length) {
            $text = substr($text, 0, $length);

            // Find last complete word
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false) {
                $text = substr($text, 0, $lastSpace);
            }

            $text .= '...';
        }

        return $text;
    }

    /**
     * Get current user ID from session
     */
    private function getCurrentUserId(Request $request): ?int
    {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Get user ID from session
        $userId = $_SESSION['auth_user_id'] ?? $_SESSION['auth_user_id'] ?? null;

        return $userId ? (int) $userId : null;
    }
}