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

use App\Models\Article;
use App\Models\Category;
use App\Models\ArticleRevision;
use Plugs\Upload\UploadedFile;
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
        } catch (\Exception $e) {
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
        } catch (\Exception $e) {
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
            // Start transaction
            Article::beginTransaction();

            // Get JSON data from request
            $data = $request->getParsedBody();

            // Validate required fields
            $errors = $this->validateArticleData($data, true);
            if (!empty($errors)) {
                Article::rollBack();
                return $this->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $errors
                ], 422);
            }

            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                Article::rollBack();
                return $this->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Generate unique slug
            $slug = $this->generateSlug($data['title']);

            // Create article
            $article = Article::create([
                'title' => $data['title'],
                'slug' => $slug,
                'content' => $data['content'],
                'excerpt' => $data['excerpt'] ?? $this->generateExcerpt($data['content']),
                'featured_image' => $data['featured_image'] ?? null,
                'seo_title' => $data['seo_title'] ?? substr($data['title'], 0, 60),
                'seo_description' => $data['seo_description'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s'),
                'author_id' => $userId,
            ]);

            if (!$article) {
                Article::rollBack();
                throw new \Exception('Failed to create article');
            }

            // Create initial revision
            $this->createRevision($article, $userId, 'Initial publication');

            // Attach categories if provided
            if (!empty($data['categories']) && is_array($data['categories'])) {
                $this->attachCategories($article->id, $data['categories']);
            }

            Article::commit();

            return $this->json([
                'success' => true,
                'message' => 'Article published successfully!',
                'article_id' => $article->id,
                'slug' => $article->slug,
                'redirect_url' => '/admin/articles'
            ], 201);

        } catch (\Exception $e) {
            Article::rollBack();
            return $this->json([
                'success' => false,
                'message' => 'Failed to create article: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save article as draft or create draft revision
     */
    public function saveDraft(Request $request): Response
    {
        try {
            Article::beginTransaction();

            $data = $request->getParsedBody();

            // Basic validation for drafts (only title required)
            if (empty($data['title'])) {
                Article::rollBack();
                return $this->json([
                    'success' => false,
                    'message' => 'Title is required',
                    'errors' => ['title' => 'Title field is required']
                ], 422);
            }

            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                Article::rollBack();
                return $this->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if this is an update to existing draft
            $articleId = $data['article_id'] ?? null;
            
            if ($articleId) {
                // Update existing article
                $article = Article::find($articleId);
                
                if (!$article) {
                    Article::rollBack();
                    return $this->json([
                        'success' => false,
                        'message' => 'Article not found'
                    ], 404);
                }

                // Update article
                $article->update([
                    'title' => $data['title'],
                    'content' => $data['content'] ?? '',
                    'excerpt' => $data['excerpt'] ?? '',
                    'featured_image' => $data['featured_image'] ?? $article->featured_image,
                    'seo_title' => $data['seo_title'] ?? substr($data['title'], 0, 60),
                    'seo_description' => $data['seo_description'] ?? null,
                    'seo_keywords' => $data['seo_keywords'] ?? null,
                ]);

                // Create revision
                $this->createRevision($article, $userId, 'Draft update');

            } else {
                // Create new draft article
                $slug = $this->generateSlug($data['title']);

                $article = Article::create([
                    'title' => $data['title'],
                    'slug' => $slug,
                    'content' => $data['content'] ?? '',
                    'excerpt' => $data['excerpt'] ?? '',
                    'featured_image' => $data['featured_image'] ?? null,
                    'seo_title' => $data['seo_title'] ?? substr($data['title'], 0, 60),
                    'seo_description' => $data['seo_description'] ?? null,
                    'seo_keywords' => $data['seo_keywords'] ?? null,
                    'status' => 'draft',
                    'author_id' => $userId,
                ]);

                if (!$article) {
                    Article::rollBack();
                    throw new \Exception('Failed to save draft');
                }

                // Create initial revision
                $this->createRevision($article, $userId, 'Initial draft');
            }

            // Update categories if provided
            if (!empty($data['categories']) && is_array($data['categories'])) {
                $this->syncCategories($article->id, $data['categories']);
            }

            Article::commit();

            return $this->json([
                'success' => true,
                'message' => 'Draft saved successfully!',
                'draft_id' => $article->id,
                'slug' => $article->slug
            ], 200);

        } catch (\Exception $e) {
            Article::rollBack();
            return $this->json([
                'success' => false,
                'message' => 'Failed to save draft: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Upload featured image
     */
    public function uploadFeaturedImage(Request $request): Response
    {
        try {
            // Get uploaded file using the helper method
            $file = $this->file($request, 'featured_image');

            if (!$file) {
                return $this->json([
                    'success' => false,
                    'message' => 'No file uploaded'
                ], 400);
            }

            // Validate and upload image using the Controller's upload helper
            $result = $this->upload($file, [
                'path' => 'public/uploads/articles',
                'allowed' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
                'maxSize' => 5242880 // 5MB
            ]);

            return $this->json([
                'success' => true,
                'message' => 'Image uploaded successfully',
                'file_path' => $result['url'],
                'file_info' => [
                    'name' => $result['name'],
                    'size' => $result['size'],
                    'type' => $result['type'],
                    'dimensions' => $result['dimensions'] ?? null
                ]
            ], 200);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Upload failed: ' . $e->getMessage()
            ], 500);
        }
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
        } catch (\Exception $e) {
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
     * Attach categories to article (for new articles)
     */
    private function attachCategories(int $articleId, array $categoryIds): void
    {
        if (empty($categoryIds)) {
            return;
        }

        // Insert into article_categories table
        foreach ($categoryIds as $categoryId) {
            // Skip if not numeric
            if (!is_numeric($categoryId)) {
                continue;
            }

            try {
                // Use raw query to insert
                $pdo = Article::getPdo();
                $stmt = $pdo->prepare(
                    "INSERT IGNORE INTO article_categories (article_id, category_id) VALUES (?, ?)"
                );
                $stmt->execute([$articleId, $categoryId]);
            } catch (\Exception $e) {
                error_log("Failed to attach category {$categoryId}: " . $e->getMessage());
            }
        }
    }

    /**
     * Sync categories for existing article (removes old, adds new)
     */
    private function syncCategories(int $articleId, array $categoryIds): void
    {
        try {
            $pdo = Article::getPdo();

            // Remove existing categories
            $stmt = $pdo->prepare("DELETE FROM article_categories WHERE article_id = ?");
            $stmt->execute([$articleId]);

            // Add new categories
            $this->attachCategories($articleId, $categoryIds);
        } catch (\Exception $e) {
            error_log("Failed to sync categories: " . $e->getMessage());
        }
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
        $userId = $_SESSION['user_id'] ?? null;
        
        return $userId ? (int) $userId : null;
    }
}