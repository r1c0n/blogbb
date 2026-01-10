@extends('layouts.app')

@section('title', 'Maintenance Mode - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row justify-content-center" style="min-height: 60vh;">
        <div class="col-md-6 d-flex align-items-center">
            <div class="text-center w-100">
                <i class="fas fa-wrench fa-5x text-warning mb-4"></i>
                <h1 class="display-4 mb-4">Under Maintenance</h1>
                <p class="lead">
                    We're currently performing scheduled maintenance.
                    We'll be back online shortly.
                </p>
                <p class="text-muted">
                    Thank you for your patience!
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
