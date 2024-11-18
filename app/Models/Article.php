<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

/**
 * App\Models\Article.
 *
 * @property int $id
 * @property int $author_id
 * @property string $title
 * @property string $slug
 * @property string $description
 * @property string $body
 * @property null|\Illuminate\Support\Carbon $created_at
 * @property null|\Illuminate\Support\Carbon $updated_at
 * @property \App\Models\User $author
 * @property \App\Models\Comment[]|\Illuminate\Database\Eloquent\Collection $comments
 * @property null|int $comments_count
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection $favoritedBy
 * @property null|int $favorited_by_count
 * @property \App\Models\Tag[]|\Illuminate\Database\Eloquent\Collection $tags
 * @property null|int $tags_count
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
 * @method static Builder|Article byFavorited($user)
 * @method static Builder|Article byTag($tag)
 * @method static Builder|Article followedAuthor(\Illuminate\Foundation\Auth\User $user)
 *
 * @mixin \Eloquent
 */
class Article extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'body',
        'slug',
    ];

    public function author(): BelongsTo
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
        if ($author) {
            return $query->whereRelation('author', 'name', 'ilike', "%{$author}%");
        }
    }

    public function scopeByFavorited(Builder $query, $user)
    {
        if ($user) {
            return $query->whereRelation('favoritedBy', 'name', 'ilike', "%{$user}%");
        }
    }

    public function scopeByTag(Builder $query, $tag)
    {
        if ($tag) {
            return $query->whereRelation('tags', 'name', 'ilike', "%{$tag}%");
        }
    }

    public function scopeFollowedAuthor(Builder $query, User $user)
    {
        return $query->whereRelation('author.followers', 'id', '=', $user->id);
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
