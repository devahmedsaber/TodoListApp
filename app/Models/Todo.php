<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todo extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'image',
    ];

    /**
     * Scope a query to search todos.
     *
     * @param Builder $query
     * @param string|null $searchKey
     * @return Builder
     */
    public function scopeSearch(Builder $query, ?string $searchKey): Builder
    {
        if ($searchKey) {
            return $query->where(function ($q) use ($searchKey) {
                $q->where('title', 'like', "%{$searchKey}%")
                    ->orWhere('description', 'like', "%{$searchKey}%");
            });
        }

        return $query;
    }
}
