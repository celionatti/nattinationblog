@extends('layouts.default')

@section('title', 'Articles Page ')

@section('content')
<!-- ========== BREADCRUMB ========== -->
<section class="breadcrumb-section">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/">Home</a></li>
                <li class="breadcrumb-item"><a href="#">World</a></li>
                <li class="breadcrumb-item active" aria-current="page">Article Title</li>
            </ol>
        </nav>
    </div>
</section>

<!-- ========== ARTICLE HEADER ========== -->
<article class="article-header animate-on-scroll">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <span class="category-badge">World News</span>
                <h1 class="article-title">Breaking: Major Development Reshapes Global Economy</h1>

                <div class="article-meta">
                    <div class="meta-item">
                        <img src="https://i.pravatar.cc/80?img=12" alt="Author" class="author-avatar">
                        <div class="author-info">
                            <span class="author-name">John Doe</span>
                            <span class="author-role">Senior Editor</span>
                        </div>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-calendar3"></i>
                        <span>November 5, 2025</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-clock"></i>
                        <span>8 min read</span>
                    </div>
                    <div class="meta-item">
                        <i class="bi bi-eye"></i>
                        <span>12.5K views</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</article>

<!-- ========== ARTICLE CONTENT ========== -->
<section class="article-section animate-on-scroll">
    <div class="container">
        <div class="row">
            <!-- Main Content -->
            <div class="col-lg-8">
                <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200&h=600&fit=crop"
                    alt="Featured article image"
                    class="featured-image"
                    loading="lazy">

                <div class="article-content">
                    <p>In a groundbreaking development that has sent shockwaves through financial markets worldwide, global economic leaders gathered today to announce unprecedented measures aimed at reshaping the international monetary system. The implications of these decisions will be felt for generations to come.</p>

                    <p>The announcement comes at a critical juncture, as economies around the world grapple with inflationary pressures, supply chain disruptions, and the lingering effects of recent global challenges. Experts suggest that this coordinated effort represents the most significant economic policy shift since the post-war era.</p>

                    <h3>Market Reactions</h3>

                    <p>Financial markets responded swiftly to the announcement, with major indices experiencing significant volatility in early trading. The S&P 500 initially surged by 2.3% before settling into more moderate gains, while European and Asian markets showed mixed reactions as investors digested the implications of the new policies.</p>

                    <p>Currency markets saw particularly dramatic movements, with the dollar strengthening against most major currencies. Gold prices initially spiked but later retreated as clarity emerged around the specifics of the proposed measures.</p>

                    <h3>Expert Analysis</h3>

                    <p>Leading economists have offered varying perspectives on the potential impact of these developments. Dr. Sarah Chen, Chief Economist at Global Financial Institute, emphasized that "while the short-term market reaction has been volatile, the long-term implications could fundamentally alter how we think about monetary policy coordination."</p>

                    <p>However, not all experts are convinced. Professor Michael Roberts of Harvard Business School cautioned that "implementing such sweeping changes across diverse economic systems presents enormous practical challenges. The devil will be in the details of execution."</p>

                    <h2>Looking Ahead</h2>

                    <p>As nations move forward with implementation, attention now turns to how these policies will affect everyday consumers and businesses. Early indications suggest that borrowing costs may stabilize, but the full impact won't be clear for several quarters.</p>

                    <p>The agreement also includes provisions for regular review and adjustment, acknowledging that flexibility will be crucial as the global economic landscape continues to evolve. Quarterly meetings of finance ministers and central bank governors have been scheduled to monitor progress and address emerging challenges.</p>

                    <p>For businesses and investors, the message is clear: adaptability and careful attention to policy developments will be more important than ever. As these historic changes unfold, the ability to respond quickly to new information will separate successful strategies from those caught flat-footed.</p>

                    <p>The coming months will be critical in determining whether this ambitious initiative can deliver on its promise of greater economic stability and prosperity. One thing is certain—the global economic landscape has been fundamentally altered, and the ripple effects will be felt far and wide.</p>
                </div>

                <!-- Social Share -->
                <div class="social-share">
                    <h3>Share this article</h3>
                    <div class="share-buttons">
                        <button class="share-btn twitter" onclick="shareOnTwitter()">
                            <i class="bi bi-twitter"></i> Twitter
                        </button>
                        <button class="share-btn facebook" onclick="shareOnFacebook()">
                            <i class="bi bi-facebook"></i> Facebook
                        </button>
                        <button class="share-btn linkedin" onclick="shareOnLinkedIn()">
                            <i class="bi bi-linkedin"></i> LinkedIn
                        </button>
                        <button class="share-btn copy" onclick="copyLink()">
                            <i class="bi bi-link-45deg"></i> Copy Link
                        </button>
                    </div>
                </div>

                <!-- Author Bio -->
                <div class="author-bio animate-on-scroll">
                    <img src="https://i.pravatar.cc/200?img=12" alt="John Doe" class="author-bio-avatar">
                    <div class="author-bio-content">
                        <h4>John Doe</h4>
                        <p class="author-bio-role">Senior Editor | Economic Affairs</p>
                        <p class="author-bio-text">John is an award-winning journalist with over 15 years of experience covering international economics and finance. He has reported from major financial centers around the world and holds a Master's degree in Economics from Oxford University.</p>
                    </div>
                </div>

                <!-- Related Articles -->
                <div class="related-articles animate-on-scroll">
                    <div class="section-header">
                        <h2>Related Articles</h2>
                        <div class="line"></div>
                    </div>
                    <div class="row g-4">
                        <!-- PHP: Loop related posts here -->
                        <div class="col-md-4">
                            <a href="#" class="related-card">
                                <img src="https://images.unsplash.com/photo-1611974789855-9c2a0a7236a3?w=400&h=200&fit=crop" alt="Related article" loading="lazy">
                                <div class="card-body">
                                    <span class="category-badge">Economy</span>
                                    <h3>Central Banks Signal New Policy Direction</h3>
                                    <div class="meta">
                                        <span><i class="bi bi-clock me-1"></i> 2 days ago</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="related-card">
                                <img src="https://images.unsplash.com/photo-1590283603385-17ffb3a7f29f?w=400&h=200&fit=crop" alt="Related article" loading="lazy">
                                <div class="card-body">
                                    <span class="category-badge">Markets</span>
                                    <h3>Stock Markets Rally on Economic News</h3>
                                    <div class="meta">
                                        <span><i class="bi bi-clock me-1"></i> 3 days ago</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4">
                            <a href="#" class="related-card">
                                <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?w=400&h=200&fit=crop" alt="Related article" loading="lazy">
                                <div class="card-body">
                                    <span class="category-badge">Analysis</span>
                                    <h3>What These Changes Mean for You</h3>
                                    <div class="meta">
                                        <span><i class="bi bi-clock me-1"></i> 4 days ago</span>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="comments-section animate-on-scroll">
                    <div class="section-header">
                        <h2>Comments <span class="comment-count">(8)</span></h2>
                        <div class="line"></div>
                    </div>

                    <!-- Comment Form -->
                    <div class="comment-form-card">
                        <h3>Leave a Comment</h3>
                        <form id="commentForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="Your Name *" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="Your Email *" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" rows="4" placeholder="Your Comment *" required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="saveInfo">
                                        <label class="form-check-label" for="saveInfo">
                                            Save my name and email for next time
                                        </label>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn-submit-comment">
                                        <i class="bi bi-chat-dots-fill me-2"></i> Post Comment
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Comments List -->
                    <div class="comments-list">
                        <h3 class="comments-title">All Comments (8)</h3>

                        <!-- PHP: Loop comments here -->
                        <!-- Comment 1 -->
                        <div class="comment">
                            <img src="https://i.pravatar.cc/80?img=32" alt="Commenter" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h4 class="comment-author">Sarah Mitchell</h4>
                                    <span class="comment-time"><i class="bi bi-clock me-1"></i> 2 hours ago</span>
                                </div>
                                <p class="comment-text">This is exactly the kind of in-depth analysis we need. The implications for emerging markets are particularly interesting. Great work!</p>
                                <div class="comment-actions">
                                    <button class="comment-action-btn" onclick="likeComment(this)">
                                        <i class="bi bi-hand-thumbs-up"></i> <span>24</span>
                                    </button>
                                    <button class="comment-action-btn reply-btn" onclick="toggleReplyForm(this)">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                </div>

                                <!-- Reply Form (Hidden by default) -->
                                <div class="reply-form" style="display: none;">
                                    <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                                    <div class="reply-form-actions">
                                        <button class="btn-cancel-reply" onclick="toggleReplyForm(this.closest('.comment-actions').querySelector('.reply-btn'))">Cancel</button>
                                        <button class="btn-post-reply">Post Reply</button>
                                    </div>
                                </div>

                                <!-- Nested Reply -->
                                <div class="comment-reply">
                                    <img src="https://i.pravatar.cc/80?img=12" alt="Author" class="comment-avatar">
                                    <div class="comment-content">
                                        <div class="comment-header">
                                            <h4 class="comment-author">John Doe <span class="author-badge">Author</span></h4>
                                            <span class="comment-time"><i class="bi bi-clock me-1"></i> 1 hour ago</span>
                                        </div>
                                        <p class="comment-text">Thank you Sarah! I'm glad you found it valuable. The emerging markets angle is definitely something we'll be following closely.</p>
                                        <div class="comment-actions">
                                            <button class="comment-action-btn" onclick="likeComment(this)">
                                                <i class="bi bi-hand-thumbs-up"></i> <span>12</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment 2 -->
                        <div class="comment">
                            <img src="https://i.pravatar.cc/80?img=45" alt="Commenter" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h4 class="comment-author">Michael Chen</h4>
                                    <span class="comment-time"><i class="bi bi-clock me-1"></i> 4 hours ago</span>
                                </div>
                                <p class="comment-text">I've been following this story for weeks. Your breakdown of the policy implications is spot on. Bookmarking this for future reference.</p>
                                <div class="comment-actions">
                                    <button class="comment-action-btn" onclick="likeComment(this)">
                                        <i class="bi bi-hand-thumbs-up"></i> <span>18</span>
                                    </button>
                                    <button class="comment-action-btn reply-btn" onclick="toggleReplyForm(this)">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                </div>

                                <div class="reply-form" style="display: none;">
                                    <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                                    <div class="reply-form-actions">
                                        <button class="btn-cancel-reply" onclick="toggleReplyForm(this.closest('.comment-actions').querySelector('.reply-btn'))">Cancel</button>
                                        <button class="btn-post-reply">Post Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment 3 -->
                        <div class="comment">
                            <img src="https://i.pravatar.cc/80?img=28" alt="Commenter" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h4 class="comment-author">Emily Rodriguez</h4>
                                    <span class="comment-time"><i class="bi bi-clock me-1"></i> 6 hours ago</span>
                                </div>
                                <p class="comment-text">Excellent article! Could you expand on how this might affect small businesses? That's an angle I'd love to see explored.</p>
                                <div class="comment-actions">
                                    <button class="comment-action-btn" onclick="likeComment(this)">
                                        <i class="bi bi-hand-thumbs-up"></i> <span>31</span>
                                    </button>
                                    <button class="comment-action-btn reply-btn" onclick="toggleReplyForm(this)">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                </div>

                                <div class="reply-form" style="display: none;">
                                    <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                                    <div class="reply-form-actions">
                                        <button class="btn-cancel-reply" onclick="toggleReplyForm(this.closest('.comment-actions').querySelector('.reply-btn'))">Cancel</button>
                                        <button class="btn-post-reply">Post Reply</button>
                                    </div>
                                </div>

                                <!-- Nested Replies -->
                                <div class="comment-reply">
                                    <img src="https://i.pravatar.cc/80?img=67" alt="Commenter" class="comment-avatar">
                                    <div class="comment-content">
                                        <div class="comment-header">
                                            <h4 class="comment-author">David Park</h4>
                                            <span class="comment-time"><i class="bi bi-clock me-1"></i> 5 hours ago</span>
                                        </div>
                                        <p class="comment-text">Great question Emily! I'd also like to know more about the SME impact.</p>
                                        <div class="comment-actions">
                                            <button class="comment-action-btn" onclick="likeComment(this)">
                                                <i class="bi bi-hand-thumbs-up"></i> <span>8</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment 4 -->
                        <div class="comment">
                            <img src="https://i.pravatar.cc/80?img=51" alt="Commenter" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h4 class="comment-author">Amanda Johnson</h4>
                                    <span class="comment-time"><i class="bi bi-clock me-1"></i> 8 hours ago</span>
                                </div>
                                <p class="comment-text">As an economist, I appreciate the thorough research behind this piece. Well done! 📊</p>
                                <div class="comment-actions">
                                    <button class="comment-action-btn" onclick="likeComment(this)">
                                        <i class="bi bi-hand-thumbs-up"></i> <span>42</span>
                                    </button>
                                    <button class="comment-action-btn reply-btn" onclick="toggleReplyForm(this)">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                </div>

                                <div class="reply-form" style="display: none;">
                                    <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                                    <div class="reply-form-actions">
                                        <button class="btn-cancel-reply" onclick="toggleReplyForm(this.closest('.comment-actions').querySelector('.reply-btn'))">Cancel</button>
                                        <button class="btn-post-reply">Post Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Comment 5 -->
                        <div class="comment">
                            <img src="https://i.pravatar.cc/80?img=39" alt="Commenter" class="comment-avatar">
                            <div class="comment-content">
                                <div class="comment-header">
                                    <h4 class="comment-author">Robert Taylor</h4>
                                    <span class="comment-time"><i class="bi bi-clock me-1"></i> 10 hours ago</span>
                                </div>
                                <p class="comment-text">Very informative! Sharing this with my team. This is going to be a hot topic in our next meeting.</p>
                                <div class="comment-actions">
                                    <button class="comment-action-btn" onclick="likeComment(this)">
                                        <i class="bi bi-hand-thumbs-up"></i> <span>15</span>
                                    </button>
                                    <button class="comment-action-btn reply-btn" onclick="toggleReplyForm(this)">
                                        <i class="bi bi-reply-fill"></i> Reply
                                    </button>
                                </div>

                                <div class="reply-form" style="display: none;">
                                    <textarea class="form-control" rows="2" placeholder="Write a reply..."></textarea>
                                    <div class="reply-form-actions">
                                        <button class="btn-cancel-reply" onclick="toggleReplyForm(this.closest('.comment-actions').querySelector('.reply-btn'))">Cancel</button>
                                        <button class="btn-post-reply">Post Reply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Load More Button -->
                        <div class="text-center mt-4">
                            <button class="btn-load-more" onclick="loadMoreComments()">
                                <i class="bi bi-arrow-down-circle me-2"></i> Load More Comments
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <div class="sidebar-sticky">
                    <!-- Popular Posts Widget -->
                    <div class="sidebar-widget animate-on-scroll">
                        <h4>Popular Posts</h4>
                        <!-- PHP: Loop popular posts here -->
                        <a href="#" class="sidebar-post">
                            <img src="https://images.unsplash.com/photo-1526628953301-3e589a6a8b74?w=160&h=160&fit=crop" alt="Popular post" loading="lazy">
                            <div class="sidebar-post-content">
                                <h5>AI Revolution Transforms Healthcare Industry</h5>
                                <div class="sidebar-post-meta">
                                    <i class="bi bi-eye me-1"></i> 15.2K views
                                </div>
                            </div>
                        </a>
                        <a href="#" class="sidebar-post">
                            <img src="https://images.unsplash.com/photo-1461896836934-ffe607ba8211?w=160&h=160&fit=crop" alt="Popular post" loading="lazy">
                            <div class="sidebar-post-content">
                                <h5>Champions League: Historic Comeback Victory</h5>
                                <div class="sidebar-post-meta">
                                    <i class="bi bi-eye me-1"></i> 12.8K views
                                </div>
                            </div>
                        </a>
                        <a href="#" class="sidebar-post">
                            <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?w=160&h=160&fit=crop" alt="Popular post" loading="lazy">
                            <div class="sidebar-post-content">
                                <h5>Climate Summit Reaches Breakthrough Deal</h5>
                                <div class="sidebar-post-meta">
                                    <i class="bi bi-eye me-1"></i> 11.5K views
                                </div>
                            </div>
                        </a>
                        <a href="#" class="sidebar-post">
                            <img src="https://images.unsplash.com/photo-1483058712412-4245e9b90334?w=160&h=160&fit=crop" alt="Popular post" loading="lazy">
                            <div class="sidebar-post-content">
                                <h5>Top Travel Destinations for 2025 Revealed</h5>
                                <div class="sidebar-post-meta">
                                    <i class="bi bi-eye me-1"></i> 9.7K views
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Tags Widget -->
                    <div class="sidebar-widget animate-on-scroll">
                        <h4>Popular Tags</h4>
                        <div class="tag-cloud">
                            <a href="#" class="tag">Economy</a>
                            <a href="#" class="tag">Finance</a>
                            <a href="#" class="tag">Markets</a>
                            <a href="#" class="tag">Business</a>
                            <a href="#" class="tag">Technology</a>
                            <a href="#" class="tag">Politics</a>
                            <a href="#" class="tag">World</a>
                            <a href="#" class="tag">Analysis</a>
                            <a href="#" class="tag">Breaking</a>
                            <a href="#" class="tag">Investment</a>
                        </div>
                    </div>

                    <!-- Newsletter Widget -->
                    <div class="sidebar-widget animate-on-scroll" style="background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%); color: white; border: none;">
                        <h4 style="color: white; border-color: rgba(255,255,255,0.3);">Newsletter</h4>
                        <p style="margin-bottom: 1rem; opacity: 0.95;">Get the latest news delivered directly to your inbox.</p>
                        <form onsubmit="event.preventDefault(); alert('Newsletter subscription feature coming soon!');">
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Your email address" required style="border-radius: 8px;">
                            </div>
                            <button type="submit" class="btn w-100" style="background: white; color: var(--accent); font-weight: 600; border-radius: 8px;">
                                Subscribe Now
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection