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
use Plugs\View\ErrorMessage;
use Plugs\Utils\FlashMessage;
use Plugs\Paginator\Paginator;
use Plugs\Upload\FileUploader;
use Plugs\Upload\UploadedFile;
use App\Models\ArticleRevision;
use App\Models\ArticleCategories;
use Plugs\Base\Controller\Controller;
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
            $perPage = 10;
            $currentPage = (int)($queryParams['page'] ?? 1);

            // Build query with eager loading
            $articles = Article::with(['author', 'categories']);

            // Apply status filter
            if ($statusFilter !== 'all') {
                $articles = $articles->where('status', $statusFilter);
            }

            // Apply author filter
            if ($authorFilter !== 'all') {
                $articles = $articles->where('author_id', (int)$authorFilter);
            }

            // Apply date filter
            if ($dateFilter !== 'all') {
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

            // Apply sorting - latest first
            $articles = $articles->orderBy('created_at', 'DESC');

            // Create paginator
            $paginator = Paginator::fromQuery($articles, $perPage, $currentPage);

            // Get paginated articles
            $articles = $paginator->items();

            // Get categories and authors for filters

            $authors = User::all();

            $data = [
                'articles' => $articles,
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

            $isPublish = ($data['action'] ?? '') === 'publish';

            $errors = $this->validateArticleData($request, $isPublish);

            // If validation fails, redirect back with errors
            if ($errors->any()) {
                return $this->back($errors);
            }

            // Get current user ID
            $userId = $this->getCurrentUserId($request);
            if (!$userId) {
                FlashMessage::error('You must be logged in to create an article.');
                return $this->redirect('/admin/articles/new-article');
            }

            // Handle featured image upload
            $featuredImage = null;

            if (isset($_FILES['featured_image']) && $_FILES['featured_image']['error'] === UPLOAD_ERR_OK) {
                $file = UploadedFile::createFromFilesArray($_FILES['featured_image']);
                try {
                    $uploadResult = $this->uploader->upload($file, null, (string)$userId);
                    $featuredImage = $uploadResult['url'];
                } catch (Exception $e) {
                    FlashMessage::error("Upload failed: " . $e->getMessage());
                    return $this->redirect("/admin/articles/new-article");
                }
            }

            // Generate slug from title
            $slug = $this->generateSlug($data['title']);

            // Generate excerpt if not provided or empty
            $excerpt = $this->getExcerpt($data);

            // Generate SEO fields if not provided
            $seoTitle = !empty(trim($data['seo_title'] ?? ''))
                ? trim($data['seo_title'])
                : $this->generateSeoTitle($data['title']);

            $seoDescription = !empty(trim($data['seo_description'] ?? ''))
                ? trim($data['seo_description'])
                : $this->generateSeoDescription($data['content'], $data['title']);

            $seoKeywords = !empty(trim($data['seo_keywords'] ?? ''))
                ? trim($data['seo_keywords'])
                : $this->generateSeoKeywords($data['content'], $data['title']);

            // Determine published_at date
            $publishedAt = null;
            if ($isPublish) {
                $publishedAt = date('Y-m-d H:i:s');
            }

            // Prepare article data
            $articleData = [
                'title' => trim($data['title']),
                'slug' => $slug,
                'content' => $data['content'],
                'excerpt' => $excerpt,
                'categories' => $data['categories'],
                'featured_image' => $featuredImage,
                'status' => $isPublish ? "published" : 'draft',
                'author_id' => $userId,
                'seo_title' => $seoTitle,
                'seo_description' => $seoDescription,
                'seo_keywords' => $seoKeywords,
                'published_at' => $publishedAt,
            ];

            // Create the article
            $article = Article::create($articleData);

            if ($article) {
                // Create article categories linking
                $this->createArticleCategories($article, $data['categories']);

                FlashMessage::success('Article created successfully!');

                // Redirect based on action
                if ($data['action'] === 'draft') {
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

    private function createArticleCategories(Article $article, $category): bool
    {
        try {

            ArticleCategories::create([
                'article_id' => $article->id,
                'category_id' => $category
            ]);

            return true;
        } catch (Exception $e) {
            error_log("Failed to create article categories: " . $e->getMessage());
            FlashMessage::error("Failed to create article categories: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get excerpt from data - handles both empty strings and null values
     */
    private function getExcerpt(array $data): string
    {
        // Check if excerpt exists and is not empty (after trimming)
        $excerpt = trim($data['excerpt'] ?? '');

        // If excerpt is provided and not empty, use it
        if (!empty($excerpt)) {
            return $excerpt;
        }

        // Otherwise generate from content
        return $this->generateExcerpt($data['content'] ?? '');
    }

    /**
     * Generate SEO Title from article title
     */
    private function generateSeoTitle(string $title, int $maxLength = 60): string
    {
        // Clean and format the title
        $seoTitle = trim($title);

        // Remove extra spaces
        $seoTitle = preg_replace('/\s+/', ' ', $seoTitle);

        // Ensure it doesn't exceed max length
        if (strlen($seoTitle) > $maxLength) {
            $seoTitle = substr($seoTitle, 0, $maxLength);

            // Don't cut in the middle of a word if possible
            $lastSpace = strrpos($seoTitle, ' ');
            if ($lastSpace !== false && $lastSpace > $maxLength - 20) {
                $seoTitle = substr($seoTitle, 0, $lastSpace);
            }

            // Remove trailing punctuation and add ellipsis if needed
            $seoTitle = rtrim($seoTitle, ".,!?-");
            if (strlen($seoTitle) < strlen($title)) {
                $seoTitle .= '...';
            }
        }

        return $seoTitle;
    }

    /**
     * Generate SEO Description from content
     */
    private function generateSeoDescription(string $content, string $title = '', int $maxLength = 160): string
    {
        // Strip HTML tags and decode HTML entities
        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        // If content is too short, use title as fallback
        if (strlen($text) < 50 && !empty($title)) {
            $text = $title . ' - ' . $text;
        }

        // Trim to specified length
        if (strlen($text) > $maxLength) {
            $text = substr($text, 0, $maxLength);

            // Find last complete word
            $lastSpace = strrpos($text, ' ');
            if ($lastSpace !== false && $lastSpace > $maxLength - 30) {
                $text = substr($text, 0, $lastSpace);
            }

            // Remove trailing punctuation
            $text = rtrim($text, ".,!?-");
            $text .= '...';
        }

        return $text;
    }

    /**
     * Generate SEO Keywords from content and title
     */
    private function generateSeoKeywords(string $content, string $title = '', int $maxKeywords = 10): string
    {
        // Combine title and content for keyword extraction
        $text = $title . ' ' . $content;

        // Strip HTML tags and decode HTML entities
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Convert to lowercase
        $text = strtolower($text);

        // Remove special characters but keep spaces and hyphens
        $text = preg_replace('/[^\p{L}\p{N}\s-]/u', ' ', $text);

        // Split into words
        $words = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

        // Define common stop words to exclude
        $stopWords = [
            'the',
            'a',
            'an',
            'and',
            'or',
            'but',
            'in',
            'on',
            'at',
            'to',
            'for',
            'of',
            'with',
            'by',
            'from',
            'up',
            'about',
            'into',
            'through',
            'during',
            'before',
            'after',
            'above',
            'below',
            'between',
            'among',
            'is',
            'are',
            'was',
            'were',
            'be',
            'been',
            'being',
            'have',
            'has',
            'had',
            'do',
            'does',
            'did',
            'will',
            'would',
            'could',
            'should',
            'may',
            'might',
            'must',
            'can',
            'this',
            'that',
            'these',
            'those',
            'i',
            'you',
            'he',
            'she',
            'it',
            'we',
            'they',
            'me',
            'him',
            'her',
            'us',
            'them',
            'my',
            'your',
            'his',
            'its',
            'our',
            'their',
            'what',
            'which',
            'who',
            'whom',
            'whose',
            'when',
            'where',
            'why',
            'how',
            'all',
            'any',
            'both',
            'each',
            'few',
            'more',
            'most',
            'other',
            'some',
            'such',
            'no',
            'nor',
            'not',
            'only',
            'own',
            'same',
            'so',
            'than',
            'too',
            'very'
        ];

        // Count word frequency
        $wordCounts = [];
        foreach ($words as $word) {
            // Skip short words and stop words
            if (strlen($word) <= 2 || in_array($word, $stopWords)) {
                continue;
            }

            if (isset($wordCounts[$word])) {
                $wordCounts[$word]++;
            } else {
                $wordCounts[$word] = 1;
            }
        }

        // Sort by frequency (descending)
        arsort($wordCounts);

        // Take top keywords
        $keywords = array_slice(array_keys($wordCounts), 0, $maxKeywords);

        // Limit total characters to 255
        $keywordString = implode(', ', $keywords);
        if (strlen($keywordString) > 255) {
            $keywordString = substr($keywordString, 0, 255);
            $lastComma = strrpos($keywordString, ',');
            if ($lastComma !== false) {
                $keywordString = substr($keywordString, 0, $lastComma);
            }
        }

        return $keywordString;
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
        // Handle empty content
        if (empty(trim($content))) {
            return '';
        }

        // Strip HTML tags and decode HTML entities
        $text = strip_tags($content);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', trim($text));

        // If text is already shorter than length, return as is
        if (strlen($text) <= $length) {
            return $text;
        }

        // Trim to specified length
        $text = substr($text, 0, $length);

        // Find last complete word
        $lastSpace = strrpos($text, ' ');
        if ($lastSpace !== false) {
            $text = substr($text, 0, $lastSpace);
        }

        // Remove trailing punctuation and add ellipsis
        $text = rtrim($text, ".,!?-");
        $text .= '...';

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
