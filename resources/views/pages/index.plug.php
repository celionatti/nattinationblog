@extends('layouts.default')

@section('title', 'Welcome To ')

@section('content')
<Hero />

<!-- ========== TRENDING SECTION ========== -->
<section class="trending-section animate-on-scroll">
    <div class="container">
        <div class="section-header">
            <h2>Trending</h2>
            <div class="line"></div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <!-- PHP: Loop trending posts here -->
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?w=400&h=200&fit=crop"
                        alt="News" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">World</span>
                        <h3>Climate Summit Reaches Historic Agreement</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 1h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.5k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?w=400&h=200&fit=crop"
                        alt="Travel" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Travel</span>
                        <h3>10 Hidden Gems You Must Visit in 2025</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 3h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 1.8k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1512941937669-90a1b58e7e9c?w=400&h=200&fit=crop"
                        alt="Tech" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Technology</span>
                        <h3>Quantum Computing Makes Giant Leap Forward</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 5h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.2k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1490730141103-6cac27aaab94?w=400&h=200&fit=crop"
                        alt="Fashion" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Fashion</span>
                        <h3>Fashion Week 2025: Top Trends Revealed</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 8h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 1.5k</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== ENTERTAINMENT SECTION ========== -->
<section class="entertainment-section animate-on-scroll">
    <div class="container">
        <div class="section-header">
            <h2>Entertainment</h2>
            <div class="line"></div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <!-- PHP: Loop entertainment posts here -->
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1478720568477-152d9b164e26?w=400&h=200&fit=crop"
                        alt="Movie" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Movies</span>
                        <h3>Box Office: New Blockbuster Breaks Records</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 2h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 4.1k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1511379938547-c1f69419868d?w=400&h=200&fit=crop"
                        alt="Music" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Music</span>
                        <h3>Grammy Winner Announces World Tour</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 4h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.7k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1440404653325-ab127d49abc1?w=400&h=200&fit=crop"
                        alt="TV" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">TV Shows</span>
                        <h3>Most Anticipated Series of 2025 Unveiled</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 6h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.5k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1485846234645-a62644f84728?w=400&h=200&fit=crop"
                        alt="Celebrity" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Celebrity</span>
                        <h3>Hollywood Stars Unite for Charity Event</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 9h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 2.9k</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ========== POLITICS SECTION ========== -->
<section class="politics-section animate-on-scroll">
    <div class="container">
        <div class="section-header">
            <h2>Politics</h2>
            <div class="line"></div>
        </div>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <!-- PHP: Loop politics posts here -->
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1529107386315-e1a2ed48a620?w=400&h=200&fit=crop"
                        alt="Politics" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Politics</span>
                        <h3>Senate Passes Major Infrastructure Bill</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 1h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 5.2k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?w=400&h=200&fit=crop"
                        alt="Policy" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Policy</span>
                        <h3>New Healthcare Reform Announced Today</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 3h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 4.6k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1591258739299-5b65d5cbb235?w=400&h=200&fit=crop"
                        alt="Elections" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">Elections</span>
                        <h3>Polls Show Tight Race in Upcoming Vote</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 5h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 3.8k</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="content-card">
                    <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=400&h=200&fit=crop"
                        alt="International" loading="lazy">
                    <div class="card-body">
                        <span class="category-badge">International</span>
                        <h3>G20 Summit Addresses Global Challenges</h3>
                        <div class="meta">
                            <span><i class="bi bi-clock me-1"></i> 7h ago</span>
                            <span><i class="bi bi-eye me-1"></i> 4.3k</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection