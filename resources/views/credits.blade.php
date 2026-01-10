@extends('layouts.app')

@section('title', 'Credits - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-award"></i> BlogBB Credits</h3>
                </div>
                <div class="card-body">
                    <h4 class="mb-3">Blog software by BlogBB Team</h4>
                    <div class="mb-4">
                        <p class="mb-2"><strong>made</strong> - Creator of the original BlogBB.</p>
                        <p class="mb-2"><strong>Official B</strong> - Maintaining the project.</p>
                    </div>

                    <hr>

                    <div class="text-muted">
                        <p class="mb-0">
                            <i class="fas fa-code"></i> Powered by Laravel {{ app()->version() }}
                        </p>
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
