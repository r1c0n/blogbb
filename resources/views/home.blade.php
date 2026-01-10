@extends('layouts.app')

@section('title', $siteConfig['name'] ?? 'BlogBB')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="jumbotron-custom mb-4" style="background-image: url('{{ $siteConfig['image'] ?? '' }}');">
        <div class="container">
            <h1 class="display-4"><b>{{ $siteConfig['name'] ?? 'BlogBB' }}</b></h1>
            <p class="lead">{{ $siteConfig['description'] ?? 'A modern blog platform' }}</p>
            <hr class="my-4 bg-white">
            <a class="btn btn-primary btn-lg" href="{{ route('blogs.index') }}" role="button">
                <i class="fas fa-book-open"></i> Browse Posts
            </a>
            @auth('admin')
            <a class="btn btn-success btn-lg" href="{{ route('blogs.create') }}" role="button">
                <i class="fas fa-pen"></i> Write a Post
            </a>
            @endauth
        </div>
    </div>

    <!-- Recent Posts -->
    @if($recentBlogs->count() > 0)
    <div class="mt-5">
        <h2 class="mb-4">Recent Posts</h2>
        <div class="row">
            @foreach($recentBlogs as $blog)
            <div class="col-md-6 mb-3">
                <div class="card blog-card h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('blogs.show', $blog->id) }}" class="text-decoration-none">
                                {{ $blog->title }}
                            </a>
                        </h5>
                        <p class="card-text text-muted">
                            {{ $blog->getExcerpt(100) }}...
                        </p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-eye"></i> {{ $blog->views }} views
                            </small>
                            <small class="text-muted">
                                {{ $blog->created_at->diffForHumans() }}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-primary">
                View All Posts <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
