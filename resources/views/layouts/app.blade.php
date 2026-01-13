<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $siteConfig['name'] ?? 'BlogBB')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        .jumbotron-custom {
            background-size: cover;
            background-position: center;
            padding: 5rem 2rem;
            border-radius: 15px;
            color: white;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.7);
        }
        .blog-card {
            transition: transform 0.2s;
        }
        .blog-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .breadcrumb-item a {
            text-decoration: none;
        }
        .breadcrumb-item a:hover {
            text-decoration: underline;
        }
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1rem 0;
            }
            .navbar-nav .nav-item {
                padding: 0.5rem 0;
                border-bottom: 1px solid rgba(255,255,255,0.1);
            }
            .navbar-nav .nav-item:last-child {
                border-bottom: none;
            }
            .navbar-nav .nav-link {
                padding: 0.5rem 1rem;
                font-size: 1.1rem;
            }
            .navbar-nav .dropdown-menu {
                background-color: #343a40;
                border: none;
                padding-left: 1rem;
            }
            .navbar-nav .dropdown-item {
                color: rgba(255,255,255,0.8);
                padding: 0.5rem 1rem;
            }
            .navbar-nav .dropdown-item:hover {
                background-color: rgba(255,255,255,0.1);
                color: #fff;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top shadow">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-blog"></i> {{ $siteConfig['name'] ?? 'BlogBB' }}
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs.index') }}">Blog Posts</a>
                    </li>
                    @auth('admin')
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blogs.create') }}">
                            <i class="fas fa-plus"></i> New Post
                        </a>
                    </li>
                    @endauth
                    @auth('admin')
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                           data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-shield"></i> {{ Auth::guard('admin')->user()->username }}
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item" href="{{ route('admin.index') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a></li>
                            @if(Auth::guard('admin')->user()->canManageAdmins())
                            <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">
                                <i class="fas fa-users-cog"></i> Admin Users
                            </a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Announcement Banner -->
    @if(!empty($siteConfig['announcement']))
    <div class="alert alert-info alert-dismissible fade show mb-0" role="alert">
        <div class="container">
            {!! $siteConfig['announcement'] !!}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="container mt-3">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="container mt-3">
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    @endif

    <!-- Breadcrumbs -->
    @if(isset($breadcrumbs) && count($breadcrumbs) > 0)
    <div class="container">
        <nav aria-label="breadcrumb" class="pt-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i> Home</a></li>
                @foreach($breadcrumbs as $breadcrumb)
                    @if($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
    </div>
    @endif

    <!-- Main Content -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; {{ date('Y') }} {{ $siteConfig['name'] ?? 'BlogBB' }}. Built with Laravel.</p>
            <p class="mb-0 text-muted small">{{ $siteConfig['description'] ?? '' }}</p>
            <p class="mb-0 mt-2">
                <a href="{{ route('credits') }}" class="text-white-50 text-decoration-none">
                    <i class="fas fa-award"></i> Credits
                </a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
