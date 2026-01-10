@extends('layouts.app')

@section('title', 'Search Results for ' . $searchTerm . ' - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 mb-4">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('blogs.create') }}" class="btn btn-primary w-100">
                        <i class="fas fa-plus"></i> NEW POST
                    </a>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9">
            <!-- Search Header -->
            <h2 class="mb-4">Results for "{{ $searchTerm }}"</h2>

            <!-- Header with Sort and Search -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div class="d-flex gap-2">
                    <!-- Sort Form -->
                    <form action="{{ route('blogs.search') }}" method="get" class="d-flex gap-2">
                        <select class="form-select form-select-sm" name="d" onchange="this.form.submit()">
                            <option value="l" {{ $currentSort == 'l' ? 'selected' : '' }}>Latest</option>
                            <option value="o" {{ $currentSort == 'o' ? 'selected' : '' }}>Oldest</option>
                            <option value="p" {{ $currentSort == 'p' ? 'selected' : '' }}>Popular</option>
                        </select>
                        <input type="hidden" name="q" value="{{ $searchTerm }}">
                    </form>

                    <!-- Search Form -->
                    <form action="{{ route('blogs.search') }}" method="get" class="d-flex">
                        <input type="text" name="q" class="form-control form-control-sm"
                               placeholder="Search..." value="{{ $searchTerm }}" required>
                    </form>
                </div>
            </div>

            <!-- Blog Posts -->
            @forelse($blogs as $blog)
            <div class="card mb-3 blog-card">
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('blogs.show', $blog->id) }}" class="text-decoration-none text-dark">
                            {{ $blog->title }}
                        </a>
                    </h5>
                    <p class="card-text text-secondary">
                        {{ $blog->getExcerpt(60) }}...
                        <a href="{{ route('blogs.show', $blog->id) }}" class="text-decoration-none">
                            <span class="text-secondary fw-bold">View more</span>
                        </a>
                    </p>
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-muted mb-0">
                            <span class="fw-bold">{{ $blog->views }} view(s)</span> -
                            <span class="fw-bold">{{ $blog->created_at->format('l, M. jS, Y - g:i:s A') }}</span>
                        </p>
                        <small class="text-muted">
                            <i class="far fa-eye"></i> #{{ $blog->id }}
                        </small>
                    </div>
                </div>
            </div>
            @empty
            <div class="alert alert-warning">
                <i class="fas fa-search"></i> No results found for "{{ $searchTerm }}". Try a different search term.
            </div>
            @endforelse

            <!-- Pagination -->
            <div class="mt-4">
                {{ $blogs->appends(['q' => $searchTerm, 'd' => $currentSort])->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
