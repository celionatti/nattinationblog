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
use App\Models\User;
use App\Models\Article;
use App\Models\Category;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use App\Models\ArticleRevision;
use Plugs\Base\Controller\Controller;
use Plugs\View\ErrorMessage;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AdminArticleController extends Controller
{
    private $uploader;

    public function onConstruct()
    {
        $this->uploader = new FileUploader();
        $this->uploader->usePublicFolder("uploads/articles");
        $this->uploader->imagesOnly(5 * 1024 * 1024)->setImageDimensions(maxWidth: 2000, maxHeight: 2000);
        $this->uploader->disableSecurityFiles();
    }
    /**
     * Display article management page
     */
    public function manage(Request $request): Response
    {
        try {
            // Get query parameters
            $queryParams = $request->getQueryParams();
            $statusFilter = $queryParams['status'] ?? 'all';
            $authorFilter = $queryParams['author'] ?? 'all';
            $dateFilter = $queryParams['date'] ?? 'all';
            $perPage = 15;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query - get all articles first
            $articles = Article::with(['author']);

            // Apply status filter
            if ($statusFilter !== 'all') {
                $articles = $articles->where('status', $statusFilter);
            }

            // Apply author filter
            if ($authorFilter !== 'all') {
                $articles = $articles->where('author_id', $authorFilter);
            }

            // Apply date filter
            if ($dateFilter !== 'all') {
                $now = date('Y-m-d H:i:s');
                switch ($dateFilter) {
                    case 'today':
                        $articles = $articles->where('created_at', '>=', date('Y-m-d 00:00:00'));
                        break;
                    case 'week':
                        $articles = $articles->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-1 week')));
                        break;
                    case 'month':
                        $articles = $articles->where('created_at', '>=', date('Y-m-d 00:00:00', strtotime('-1 month')));
                        break;
                }
            }

            // Apply sorting
            $articles = $articles->latest('created_at');

            // Create paginator
            $paginator = Paginator::fromQuery($articles, $perPage, $currentPage);

            // Get paginated articles
            $articles = $paginator->items();

            // Get categories and authors for filters
            $categories = Category::where('is_active', true)
                ->orderBy('name', 'ASC')
                ->get();

            $authors = User::all(); // Get all users for now

            $data = [
                'articles' => $articles,
                'categories' => $categories,
                'authors' => $authors,
                'paginator' => $paginator,
                'status_filter' => $statusFilter,
                'author_filter' => $authorFilter,
                'date_filter' => $dateFilter,
                'page_title' => 'Manage Articles'
            ];

            return $this->view('admin.articles.manage', $data);
        } catch (Exception $e) {
            FlashMessage::error('Failed to load articles: ' . $e->getMessage());
            return $this->view('admin.articles.manage', [
                'articles' => [],
                'categories' => [],
                'authors' => [],
                'paginator' => null,
                'status_filter' => 'all',
                'author_filter' => 'all',
                'date_filter' => 'all',
                'page_title' => 'Manage Articles'
            ]);
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

    public function store(Request $request)
    {
        try {
            $this->currentRequest = $request;

            $data = $request->getParsedBody();

            $isPublish = $data['action'] === 'publish' ? true : false;

            $errors = $this->validateArticleData($request, $isPublish);

            // If validation fails, redirect back with errors
            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID (you might need to adjust this based on your auth system)
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to create an article.');
                return $this->redirect('/admin/articles/new-article');
            }

            // Handle featured image upload
            $featuredImage = null;

            if(isset($_FILES['featured_image'])) {
                $file = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                try {
                    $featuredImage = $this->uploader->upload($file, null, (string)$userId);
                } catch(Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/articles/new-article");
                }
            }

            // Generate slug from title
            $slug = $this->generateSlug($data['title']);

            // Generate excerpt if not provided
            $excerpt = $body['excerpt'] ?? $this->generateExcerpt($data['content']);

            // Determine published_at date
            $publishedAt = null;
            if (isset($data['action']) && $data['action'] === 'publish') {
                $publishedAt = date('Y-m-d H:i:s');
                
                // If scheduled publishing is implemented later:
                // if (isset($body['publish_at']) && !empty($body['publish_at'])) {
                //     $publishedAt = date('Y-m-d H:i:s', strtotime($body['publish_at']));
                // }
            }

            // Prepare article data
            $articleData = [
                'title' => trim($data['title']),
                'slug' => $slug,
                'content' => $data['content'],
                'excerpt' => $excerpt,
                'featured_image' => $featuredImage['url'],
                'status' => $isPublish ? "published" : 'draft',
                'author_id' => $userId,
                'categories' => $data['categories'] ?? null,
                'seo_title' => $data['seo_title'] ?? null,
                'seo_description' => $data['seo_description'] ?? null,
                'seo_keywords' => $data['seo_keywords'] ?? null,
                'published_at' => $publishedAt,
            ];

            // Create the article
            $article = Article::create($articleData);
            
            if($article) {
                // Create initial revision
                $this->createRevision($article, $userId, 'Initial version');
                
                FlashMessage::success('Article created successfully!');
                
                // Redirect based on action
                if (isset($data['action']) && $data['action'] === 'draft') {
                    return $this->redirect("/admin/articles/edit/{$article->id}");
                }
                
                return $this->redirect('/admin/articles');
            } else {
                FlashMessage::error('Failed to create article. Please try again.');
                return $this->redirect('/admin/articles/new-article');
            }
        } catch (Exception $e) {
            FlashMessage::error('Error creating article: ' . $e->getMessage());
            return $this->redirect('/admin/articles/new-article');
        }
    }

    /**
     * Validate article data
     */
    private function validateArticleData($request, bool $isPublishing = false): ErrorMessage
    {
        $data = $request->getParsedBody();
        $errors = new ErrorMessage();

        // Title validation
        if (empty($data['title'])) {
            $errors->add('title', 'Title is required');
        } elseif (strlen($data['title']) > 255) {
            $errors->add('title', 'Title must not exceed 255 characters');
        }

        // Content validation (stricter for publishing)
        if ($isPublishing) {
            if (empty($data['content'])) {
                $errors->add('content', 'Content is required');
            } elseif (strlen(strip_tags($data['content'])) < 50) {
                $errors->add('content', 'Content must be at least 50 characters');
            }
        }

        // Excerpt validation
        if (!empty($data['excerpt']) && strlen($data['excerpt']) > 500) {
            $errors->add('excerpt', 'Excerpt must not exceed 500 characters');
        }

        // SEO title validation
        if (!empty($data['seo_title']) && strlen($data['seo_title']) > 60) {
            $errors->add('seo_title', 'SEO title should not exceed 60 characters');
        }

        // SEO description validation
        if (!empty($data['seo_description']) && strlen($data['seo_description']) > 160) {
            $errors->add('seo_description', 'SEO description should not exceed 160 characters');
        }

        // SEO keywords validation
        if (!empty($data['seo_keywords']) && strlen($data['seo_keywords']) > 255) {
            $errors->add('seo_keywords', 'SEO keywords should not exceed 255 characters');
        }

        // Category validation (optional but recommended for publishing)
        if ($isPublishing && empty($data['categories'])) {
            $errors->add('categories', 'Please select a category for your article');
        }

        return $errors;
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
            FlashMessage::error("Failed to create revision: " . $e->getMessage(), "Failed Process");
            return false;
        }
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
        $userId = $_SESSION['user_id'] ?? $_SESSION['auth_user_id'] ?? $_SESSION['admin_id'] ?? null;

        return $userId ? (int) $userId : null;
    }
}
