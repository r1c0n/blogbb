@extends('layouts.app')

@section('title', 'Site Settings - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <!-- Back Button -->
            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>

            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-cog"></i> Site Settings</h3>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>Validation Error:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form action="{{ route('admin.settings.update') }}" method="POST">
                        @csrf

                        <!-- Site Name -->
                        <div class="mb-3">
                            <label for="site_name" class="form-label">Site Name</label>
                            <input type="text" class="form-control @error('site_name') is-invalid @enderror"
                                   id="site_name" name="site_name"
                                   value="{{ old('site_name', $siteConfig['name']) }}" required>
                            @error('site_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site Description -->
                        <div class="mb-3">
                            <label for="site_description" class="form-label">Site Description</label>
                            <input type="text" class="form-control @error('site_description') is-invalid @enderror"
                                   id="site_description" name="site_description"
                                   value="{{ old('site_description', $siteConfig['description']) }}" required>
                            @error('site_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site URL -->
                        <div class="mb-3">
                            <label for="site_url" class="form-label">Site URL</label>
                            <input type="url" class="form-control @error('site_url') is-invalid @enderror"
                                   id="site_url" name="site_url"
                                   value="{{ old('site_url', $siteConfig['url']) }}" required>
                            <div class="form-text">Include trailing slash (e.g., https://example.com/)</div>
                            @error('site_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site Image -->
                        <div class="mb-3">
                            <label for="site_image" class="form-label">Hero Image URL</label>
                            <input type="url" class="form-control @error('site_image') is-invalid @enderror"
                                   id="site_image" name="site_image"
                                   value="{{ old('site_image', $siteConfig['image']) }}">
                            <div class="form-text">URL to the hero banner image</div>
                            @error('site_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Site Announcement -->
                        <div class="mb-3">
                            <label for="site_announcement" class="form-label">Site Announcement</label>
                            <textarea class="form-control @error('site_announcement') is-invalid @enderror"
                                      id="site_announcement" name="site_announcement" rows="3">{{ old('site_announcement', $siteConfig['announcement']) }}</textarea>
                            <div class="form-text">Displayed as a banner at the top of the site. Leave empty to hide. HTML allowed.</div>
                            @error('site_announcement')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Settings
                            </button>
                            <a href="{{ route('admin.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
