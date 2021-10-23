<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * App\Models\Article.
 *
 * @property int                                                            $id
 * @property int                                                            $author_id
 * @property string                                                         $title
 * @property string                                                         $slug
 * @property string                                                         $description
 * @property string                                                         $body
 * @property null|\Illuminate\Support\Carbon                                $created_at
 * @property null|\Illuminate\Support\Carbon                                $updated_at
 * @property \App\Models\User                                               $author
 * @property \App\Models\Comment[]|\Illuminate\Database\Eloquent\Collection $comments
 * @property null|int                                                       $comments_count
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection    $favoritedBy
 * @property null|int                                                       $favorited_by_count
 * @property \App\Models\Tag[]|\Illuminate\Database\Eloquent\Collection     $tags
 * @property null|int                                                       $tags_count
 *
 * @method static \Database\Factories\ArticleFactory factory(...$parameters)
 * @method static \Illuminate\Database\Eloquent\Builder|Article newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Article query()
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereBody($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Article whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
