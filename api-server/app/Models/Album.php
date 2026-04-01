<?php

namespace App\Models;

use Database\Factories\AlbumFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $title
 * @property int|null $year
 * @property int|null $singer_id
 * @property int $created_by
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property \Carbon\CarbonImmutable|null $deleted_at
 * @property-read User|null $creator
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 *
 * @method static AlbumFactory factory($count = null, $state = [])
 * @method static Builder<static>|Album newModelQuery()
 * @method static Builder<static>|Album newQuery()
 * @method static Builder<static>|Album onlyTrashed()
 * @method static Builder<static>|Album query()
 * @method static Builder<static>|Album whereCreatedAt($value)
 * @method static Builder<static>|Album whereDeletedAt($value)
 * @method static Builder<static>|Album whereId($value)
 * @method static Builder<static>|Album whereSingerId($value)
 * @method static Builder<static>|Album whereTitle($value)
 * @method static Builder<static>|Album whereUpdatedAt($value)
 * @method static Builder<static>|Album whereYear($value)
 * @method static Builder<static>|Album withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Album withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Album extends Model
{
    /** @use HasFactory<AlbumFactory> */
    use HasFactory, HasTimestamps, SoftDeletes;

    protected $fillable = [
        'title',
        'singer_id',
        'year',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function songs(): BelongsToMany
    {
        return $this->belongsToMany(Song::class);
    }
}
