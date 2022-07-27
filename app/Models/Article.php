<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['category', 'author'];

    // option to Route binding
    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function scopeFilter(Builder $query, array $filters)
    {
        $query->when($filters['search'] ?? false, fn($query, $search) =>
        $query->where(fn($query) =>
            $query
            ->where('title', 'like', '%' . $search . '%')
            ->orWhere('body', 'like', '%' . $search . '%'))
        );

//        $query->when($filters['category'] ?? false, fn(Builder $query, $category) =>
//        $query
//            ->whereExists(fn($query) =>
//            $query
//                ->from('categories')->whereColumn('categories.id', 'articles.category_id')
//                ->where('categories.name', $category)
//            )
//        );

        $query->when($filters['category'] ?? false, fn(Builder $query, $category) =>
        $query
            ->whereHas('category', fn($query) =>
            $query->where('slug', $category))
        );

        $query->when($filters['author'] ?? false, fn(Builder $query, $author) =>
        $query
            ->whereHas('author', fn($query) =>
            $query->where('username', $author))
        );


//        $query->when($filters['category'] ?? false, fn(Builder $query, $category) =>
//        $query
//            ->join('categories', 'category_id', '=', 'categories.id')
//            ->where('categories.name', '=', $category)
//        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
