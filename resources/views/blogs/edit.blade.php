@extends('layouts.app')

@section('title', 'Edit Post - ' . ($siteConfig['name'] ?? 'BlogBB'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Back to Post
            </a>

            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0"><i class="fas fa-edit"></i> Edit Blog Post</h3>
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

                    <form action="{{ route('blogs.update', $blog->id) }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror"
                                   id="title" name="title" placeholder="Enter blog title"
                                   value="{{ old('title', $blog->title) }}" required>
                            <div class="form-text">MIN: 3 - MAX: 100</div>
                            @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Body -->
                        <div class="mb-3">
                            <label for="body" class="form-label">Body</label>
                            <textarea class="form-control @error('body') is-invalid @enderror"
                                      id="body" name="body" rows="12"
                                      placeholder="Enter blog content" required>{{ old('body', $blog->body) }}</textarea>
                            <div class="form-text">MIN: 10 - MAX: 60000 characters</div>
                            @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Show Email -->
                        <div class="mb-3">
                            <label for="show_email" class="form-label">Email Visibility</label>
                            <select class="form-select" id="show_email" name="show_email">
                                <option value="1" {{ old('show_email', $blog->show_email) == '1' ? 'selected' : '' }}>Show email on post</option>
                                <option value="0" {{ old('show_email', $blog->show_email) == '0' ? 'selected' : '' }}>Hide email on post</option>
                            </select>
                            <div class="form-text">Choose whether to display your admin email publicly on this post</div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save"></i> Update Post
                            </button>
                            <a href="{{ route('blogs.show', $blog->id) }}" class="btn btn-outline-secondary">
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
