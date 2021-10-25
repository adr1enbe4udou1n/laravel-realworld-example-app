<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
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
 * @method static Builder|Article byAuthor($author)
 * @method static Builder|Article byFavorited($author)
 * @method static Builder|Article byTag($author)
 * @method static Builder|Article followedAuthor(\Illuminate\Foundation\Auth\User $user)
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'title',
        'description',
        'body',
        'slug',
    ];

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

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeByAuthor(Builder $query, $author)
    {
        return $query->where('votes', '>', 100);
    }

    public function scopeByFavorited(Builder $query, $author)
    {
        return $query->where('votes', '>', 100);
    }

    public function scopeByTag(Builder $query, $author)
    {
        return $query->where('votes', '>', 100);
    }

    public function scopeFollowedAuthor($query, User $user)
    {
        return $query->where('votes', '>', 100);
    }

    protected static function booted()
    {
        static::creating(function (self $article) {
            if (! $article->slug) {
                $article->slug = Str::slug($article->title);
            }
        });
    }
}
