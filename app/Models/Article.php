<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'article_favorite');
    }

    protected static function booted()
    {
        static::creating(function (self $article) {
            $article->slug = Str::slug($article->title);
        });
    }
}
