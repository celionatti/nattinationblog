@extends('layouts.admin')

@section('title', "Show Article Details")

@push('styles')
<style>
    /* ========== POST CONTENT ========== */
    .post-header {
        margin-bottom: 2rem;
    }

    .post-title {
        font-size: 2.5rem;
        font-weight: 700;
        line-height: 1.2;
        margin-bottom: 1rem;
    }

    .post-meta {
        display: flex;
        align-items: center;
        gap: 1.5rem;
        flex-wrap: wrap;
        margin-bottom: 1.5rem;
    }

    .post-author {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .author-name {
        font-weight: 600;
        font-size: 0.95rem;
    }

    .post-date {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .post-category {
        display: inline-block;
        padding: 0.4rem 0.8rem;
        background-color: var(--accent-light);
        color: var(--accent);
        border-radius: 6px;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .post-stats {
        display: flex;
        gap: 1.5rem;
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .post-stat {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .post-featured-image {
        width: 100%;
        height: 400px;
        border-radius: 12px;
        object-fit: cover;
        margin-bottom: 2rem;
    }

    .post-body {
        font-size: 1.1rem;
        line-height: 1.8;
        margin-bottom: 3rem;
    }

    .post-body p {
        margin-bottom: 1.5rem;
    }

    .post-body h2 {
        font-size: 1.8rem;
        font-weight: 700;
        margin: 2.5rem 0 1.5rem;
    }

    .post-body h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 2rem 0 1rem;
    }

    .post-body blockquote {
        border-left: 4px solid var(--accent);
        padding-left: 1.5rem;
        margin: 2rem 0;
        font-style: italic;
        color: var(--text-secondary);
    }

    .post-body img {
        max-width: 100%;
        border-radius: 8px;
        margin: 1.5rem 0;
    }

    .post-body ul,
    .post-body ol {
        margin: 1.5rem 0;
        padding-left: 1.5rem;
    }

    .post-body li {
        margin-bottom: 0.5rem;
    }

    .post-actions {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
    }
</style>
@endpush

@section('content')
<div class="post-header">
    <h1 class="post-title">{{ $article->title }}</h1>

    <div class="post-meta">
        <div class="post-author">
            @php
            $authorName = $article->author ? $article->author->name : 'Unknown';
            $initials = substr($authorName, 0, 2);
            @endphp
            <div class="author-avatar text-uppercase">{{ $initials }}</div>
            <div class="author-info">
                <div class="author-name">{{ $authorName }}</div>
                @if($article->status === "published")
                <div class="post-date">Published @diffForHumans($article->published_at)</div>
                @elseif($article->status === "archived")
                <div class="post-date">Archived @diffForHumans($article->created_at)</div>
                @else
                <div class="post-date">Drafted @diffForHumans($article->created_at)</div>
                @endif
            </div>
        </div>
        @php
        $categoryName = $article->category ? $article->category->name : 'Uncategorized';
        @endphp
        <span class="post-category">{{ $categoryName }}</span>

        <div class="post-stats">
            <div class="post-stat">
                <i class="bi bi-eye"></i>
                <span>{{ $article->view_count ?? 0 }} views</span>
            </div>
            <div class="post-stat">
                <i class="bi bi-chat"></i>
                <span>{{ $article->comment_count ?? 0 }} comments</span>
            </div>
            <div class="post-stat">
                <i class="bi bi-heart"></i>
                <span>{{ $article->like_count ?? 0 }} likes</span>
            </div>
        </div>
    </div>
</div>

<img src="{{ $article->featured_image }}"
    alt="Featured Image" class="post-featured-image shadow-sm">

<div class="post-actions">
    <button class="btn-custom btn-primary">
        <i class="bi bi-pencil"></i>
        Edit Post
    </button>
    <button class="btn-custom btn-secondary">
        <i class="bi bi-eye"></i>
        View Live
    </button>
    <button class="btn-custom btn-secondary">
        <i class="bi bi-share"></i>
        Share
    </button>
</div>

<div class="post-body">
    <?= html_entity_decode(nl2br($article->content)) ?>
</div>
@endsection