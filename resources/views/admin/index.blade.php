@extends('layouts.app')

@section('title', 'Admin Dashboard - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="fas fa-cog"></i> Admin Dashboard</h1>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-blog"></i> Total Blog Posts</h5>
                    <h2 class="mb-0">{{ $totalBlogs }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-ban"></i> Banned IPs</h5>
                    <h2 class="mb-0">{{ $totalBans }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-{{ $siteConfig['maintenance'] ? 'warning' : 'success' }} text-white">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-wrench"></i> Site Status</h5>
                    <h2 class="mb-0">{{ $siteConfig['maintenance'] ? 'Maintenance' : 'Online' }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('admin.settings') }}" class="btn btn-primary">
                            <i class="fas fa-cog"></i> Site Settings
                        </a>
                        <a href="{{ route('admin.bans') }}" class="btn btn-danger">
                            <i class="fas fa-ban"></i> Manage Bans
                        </a>
                        @if(Auth::guard('admin')->user()->canManageAdmins())
                        <a href="{{ route('admin.users.index') }}" class="btn btn-warning">
                            <i class="fas fa-users-cog"></i> Admin Users
                        </a>
                        @endif
                        <form action="{{ route('admin.maintenance.toggle') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-{{ $siteConfig['maintenance'] ? 'success' : 'warning' }}">
                                <i class="fas fa-wrench"></i>
                                {{ $siteConfig['maintenance'] ? 'Disable' : 'Enable' }} Maintenance
                            </button>
                        </form>
                        <a href="{{ route('blogs.index') }}" class="btn btn-info text-white">
                            <i class="fas fa-eye"></i> View Site
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Blog Posts -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Recent Blog Posts</h5>
        </div>
        <div class="card-body">
            @forelse($recentBlogs as $blog)
            <div class="d-flex justify-content-between align-items-center border-bottom py-2">
                <div>
                    <h6 class="mb-1">
                        <a href="{{ route('blogs.show', $blog->id) }}" target="_blank">
                            {{ $blog->title }}
                        </a>
                    </h6>
                    <small class="text-muted">
                        By {{ $blog->email }} | {{ $blog->views }} views |
                        {{ $blog->created_at->diffForHumans() }}
                    </small>
                </div>
                <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this blog post?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </form>
            </div>
            @empty
            <p class="text-muted mb-0">No blog posts yet.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
