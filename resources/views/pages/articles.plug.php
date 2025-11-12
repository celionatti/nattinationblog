@extends('layouts.default')

@section('title', 'Articles Page ')

@push('styles')
<style>
    /* ========== ARTICLES PAGE STYLES ========== */
    .articles-hero {
        background-color: var(--bg-secondary);
        padding: 3rem 0;
        margin-bottom: 3rem;
        border-bottom: 1px solid var(--border-color);
    }

    .articles-hero h1 {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .articles-hero p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto;
    }

    .articles-filters {
        background-color: var(--bg-secondary);
        padding: 1.5rem 0;
        margin-bottom: 2rem;
        border-bottom: 1px solid var(--border-color);
    }

    .filter-buttons {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .filter-btn {
        background-color: var(--bg-primary);
        color: var(--text-primary);
        border: 1px solid var(--border-color);
        padding: 0.5rem 1.5rem;
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .filter-btn:hover,
    .filter-btn.active {
        background-color: var(--accent);
        color: white;
        border-color: var(--accent);
    }

    .articles-grid {
        margin-bottom: 4rem;
    }

    .article-card {
        background-color: var(--bg-secondary);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        height: 100%;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
        margin-bottom: 2rem;
    }

    .article-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px var(--card-shadow);
    }

    .article-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .article-card .card-body {
        padding: 1.5rem;
    }

    .article-card .category-badge {
        background-color: var(--accent);
        color: white;
        padding: 0.25rem 0.75rem;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        display: inline-block;
        margin-bottom: 0.75rem;
    }

    .article-card h3 {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .article-card p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .article-card .meta {
        color: var(--text-secondary);
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .load-more-container {
        text-align: center;
        margin-top: 2rem;
    }

    .btn-load-more {
        background-color: var(--accent);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: background-color 0.3s ease;
    }

    .btn-load-more:hover {
        background-color: var(--accent-hover);
    }
</style>
@endpush

@section('content')
<!-- ========== ARTICLES HERO SECTION ========== -->
<section class="articles-hero animate-on-scroll">
    <div class="container text-center">
        <h1>All Articles</h1>
        <p>Explore our collection of articles covering a wide range of topics from technology and politics to entertainment and lifestyle.</p>
    </div>
</section>

<!-- ========== ARTICLES FILTERS ========== -->
<section class="articles-filters animate-on-scroll">
    <div class="container">
        <div class="filter-buttons">
            <button class="filter-btn active" data-filter="all">All Articles</button>
            <button class="filter-btn" data-filter="technology">Technology</button>
            <button class="filter-btn" data-filter="politics">Politics</button>
            <button class="filter-btn" data-filter="entertainment">Entertainment</button>
            <button class="filter-btn" data-filter="sports">Sports</button>
            <button class="filter-btn" data-filter="lifestyle">Lifestyle</button>
        </div>
    </div>
</section>

<!-- ========== ARTICLES GRID ========== -->
<section class="articles-grid animate-on-scroll">
    <div class="container">
        <div class="row" id="articlesContainer">
            <!-- Article cards will be dynamically loaded here -->
            <div class="col-lg-4 col-md-6" data-category="technology">
                <div class="article-card" data-article-id="1" data-article-slug="title-1">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=200&fit=crop"
                        alt="Tech" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Technology</span>
                        <h3>Quantum Computing Makes Giant Leap Forward</h3>
                        <p>Researchers have achieved a breakthrough in quantum computing that could revolutionize how we process information and solve complex problems.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 5h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.2k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="politics">
                <div class="article-card" data-article-id="2" data-article-slug="title-2">
                    <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=400&h=200&fit=crop"
                        alt="Politics" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Politics</span>
                        <h3>Senate Passes Major Infrastructure Bill</h3>
                        <p>After months of negotiations, the Senate has approved a comprehensive infrastructure package that will fund projects across the country.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 1h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 5.2k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="entertainment">
                <div class="article-card" data-article-id="3" data-article-slug="title-3">
                    <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=400&h=200&fit=crop"
                        alt="Movie" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Entertainment</span>
                        <h3>Box Office: New Blockbuster Breaks Records</h3>
                        <p>The latest superhero film has shattered box office records in its opening weekend, becoming the highest-grossing film of the year.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 2h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 4.1k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="sports">
                <div class="article-card" data-article-id="4" data-article-slug="title-4">
                    <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=400&h=200&fit=crop"
                        alt="Sports" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Sports</span>
                        <h3>Champions League: Stunning Upset Shocks Fans</h3>
                        <p>Underdogs pull off a remarkable victory against the tournament favorites in a match that will be remembered for years to come.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 6h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.8k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="lifestyle">
                <div class="article-card" data-article-id="5" data-article-slug="title-5">
                    <img src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?w=400&h=200&fit=crop"
                        alt="Travel" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Lifestyle</span>
                        <h3>10 Hidden Gems You Must Visit in 2025</h3>
                        <p>Discover these breathtaking destinations that are still off the beaten path but offer unforgettable experiences for travelers.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 3h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 1.8k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="technology">
                <div class="article-card" data-article-id="6" data-article-slug="title-6">
                    <img src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?w=400&h=200&fit=crop"
                        alt="AI" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Technology</span>
                        <h3>AI Revolution: New Breakthrough Changes Everything</h3>
                        <p>Artificial intelligence researchers have developed a system that can understand and generate human-like text with unprecedented accuracy.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 4h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.5k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="politics">
                <div class="article-card">
                    <img src="https://images.unsplash.com/photo-1591258739299-5b65d5cbb235?w=400&h=200&fit=crop"
                        alt="Elections" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Politics</span>
                        <h3>Polls Show Tight Race in Upcoming Vote</h3>
                        <p>Latest polling data indicates a neck-and-neck competition between the leading candidates with just weeks remaining until election day.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 5h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.8k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="entertainment">
                <div class="article-card">
                    <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=400&h=200&fit=crop"
                        alt="Music" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Entertainment</span>
                        <h3>Grammy Winner Announces World Tour</h3>
                        <p>The multi-award winning artist has revealed plans for a global tour that will visit over 30 countries starting next spring.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 4h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.7k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6" data-category="sports">
                <div class="article-card">
                    <img src="https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=200&fit=crop"
                        alt="Basketball" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Sports</span>
                        <h3>NBA Finals: Underdog Team Takes Game 1</h3>
                        <p>In a surprising turn of events, the underdog team secured a decisive victory in the opening game of the championship series.</p>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 8h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.1k</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="load-more-container">
            <button class="btn btn-load-more" id="loadMoreBtn">Load More Articles</button>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // ========== ARTICLES PAGE FUNCTIONALITY ==========
    function initArticlesPage() {
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const articlesContainer = document.getElementById('articlesContainer');
        const articles = document.querySelectorAll('.article-card');
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        // Filter articles
        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                button.classList.add('active');

                const filter = button.getAttribute('data-filter');

                // Show/hide articles based on filter
                articles.forEach(article => {
                    const category = article.closest('.col-lg-4').getAttribute('data-category');

                    if (filter === 'all' || category === filter) {
                        article.closest('.col-lg-4').style.display = 'block';
                    } else {
                        article.closest('.col-lg-4').style.display = 'none';
                    }
                });
            });
        });

        // Load more functionality
        let articlesToShow = 6; // Initial number of articles to show
        const allArticles = document.querySelectorAll('.col-lg-4');

        // Initially hide articles beyond the initial count
        allArticles.forEach((article, index) => {
            if (index >= articlesToShow) {
                article.style.display = 'none';
            }
        });

        loadMoreBtn.addEventListener('click', () => {
            articlesToShow += 3; // Load 3 more articles

            // Show the additional articles
            allArticles.forEach((article, index) => {
                if (index < articlesToShow) {
                    article.style.display = 'block';
                }
            });

            // Hide the button if all articles are shown
            if (articlesToShow >= allArticles.length) {
                loadMoreBtn.style.display = 'none';
            }
        });
    }
</script>
@endpush