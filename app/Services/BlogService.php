<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;

class BlogService
{
    /**
     * Get blogs with sorting and pagination
     */
    public function getBlogs(string $sortBy = 'latest', int $perPage = 25): LengthAwarePaginator
    {
        $query = Blog::query();

        match ($sortBy) {
            'popular' => $query->popular(),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    /**
     * Search blogs by title
     */
    public function searchBlogs(string $searchTerm, string $sortBy = 'latest', int $perPage = 25): LengthAwarePaginator
    {
        $query = Blog::search($searchTerm);

        match ($sortBy) {
            'popular' => $query->popular(),
            'oldest' => $query->oldest(),
            default => $query->latest(),
        };

        return $query->paginate($perPage);
    }

    /**
     * Get a single blog and increment views
     */
    public function getBlogWithViewIncrement(int $id): ?Blog
    {
        $blog = Blog::find($id);

        if ($blog) {
            $blog->incrementViews();
        }

        return $blog;
    }

    /**
     * Get a single blog without incrementing views
     */
    public function getBlog(int $id): ?Blog
    {
        return Blog::find($id);
    }

    /**
     * Create a new blog post
     */
    public function createBlog(array $data): Blog
    {
        return Blog::create([
            'email' => $data['email'],
            'title' => $data['title'],
            'body' => $data['body'],
            'show_email' => $data['show_email'] ?? true,
            'ip' => $this->getClientIp(),
            'views' => 0,
        ]);
    }

    /**
     * Update a blog post
     */
    public function updateBlog(Blog $blog, array $data): bool
    {
        return $blog->update([
            'title' => $data['title'],
            'body' => $data['body'],
            'show_email' => $data['show_email'] ?? $blog->show_email,
        ]);
    }

    /**
     * Delete a blog post
     */
    public function deleteBlog(Blog $blog): bool
    {
        return $blog->delete();
    }

    /**
     * Get client IP address
     */
    protected function getClientIp(): string
    {
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipList = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            return trim($ipList[0]);
        }

        return request()->ip() ?? '0.0.0.0';
    }

    /**
     * Get total blog count
     */
    public function getTotalCount(): int
    {
        return Blog::count();
    }

    /**
     * Get recent blogs
     */
    public function getRecentBlogs(int $limit = 5): Collection
    {
        return Blog::latest()->limit($limit)->get();
    }
}
