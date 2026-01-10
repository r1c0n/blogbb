@extends('layouts.app')

@section('title', $blog->title . ' - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Back Button -->
            <a href="{{ route('blogs.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Blogs
            </a>

            <!-- Blog Post -->
            <div class="card">
                <div class="card-body">
                    <h1 class="card-title">{{ $blog->title }}</h1>

                    <div class="text-muted mb-3">
                        <p class="mb-1">
                            <i class="far fa-calendar"></i>
                            {{ $blog->created_at->format('l, M. jS, Y - g:i:s A [T]') }}
                        </p>
                        <p class="mb-1">
                            <i class="far fa-eye"></i>
                            {{ $blog->views }} view(s)
                        </p>
                        @if($blog->show_email)
                        <p class="mb-0">
                            <i class="far fa-envelope"></i>
                            <strong>By: {{ $blog->email }}</strong>
                        </p>
                        @endif
                    </div>

                    <hr>

                    <div class="blog-content">
                        {!! nl2br(e($blog->body)) !!}
                    </div>
                </div>
            </div>

            <!-- Share Section -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Share this post</h5>
                    <div class="d-flex gap-2">
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($blog->title) }}&url={{ urlencode(route('blogs.show', $blog->id)) }}"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fab fa-twitter"></i> Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blogs.show', $blog->id)) }}"
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fab fa-facebook"></i> Facebook
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .blog-content {
        font-size: 1.1rem;
        line-height: 1.8;
        color: #333;
    }
</style>
@endpush
