<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'title',
        'body',
        'ip',
        'views',
        'show_email',
    ];

    protected $casts = [
        'views' => 'integer',
        'show_email' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Increment the view count for the blog post
     */
    public function incrementViews(): void
    {
        $this->increment('views');
    }

    /**
     * Get excerpt of the blog body
     */
    public function getExcerpt(int $length = 60): string
    {
        return substr($this->body, 0, $length);
    }

    /**
     * Scope a query to order by latest
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Scope a query to order by oldest
     */
    public function scopeOldest($query)
    {
        return $query->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to order by most popular
     */
    public function scopePopular($query)
    {
        return $query->orderBy('views', 'desc');
    }

    /**
     * Scope a query to search by title
     */
    public function scopeSearch($query, string $searchTerm)
    {
        return $query->where('title', 'like', "%{$searchTerm}%");
    }
}
