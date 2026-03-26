<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\SongFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property int $singer_id
 * @property int $created_by
 * @property int $year
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $creator
 * @property-read Singer|null $singer
 * @property int|null $file_id
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Genre> $genres
 * @property-read int|null $genres_count
 * @property-read \App\Models\File|null $file
 *
 * @method static SongFactory factory($count = null, $state = [])
 * @method static Builder<static>|Song newModelQuery()
 * @method static Builder<static>|Song newQuery()
 * @method static Builder<static>|Song onlyTrashed()
 * @method static Builder<static>|Song query()
 * @method static Builder<static>|Song whereCreatedAt($value)
 * @method static Builder<static>|Song whereId($value)
 * @method static Builder<static>|Song whereName($value)
 * @method static Builder<static>|Song whereSingerId($value)
 * @method static Builder<static>|Song whereUpdatedAt($value)
 * @method static Builder<static>|Song whereYear($value)
 * @method static Builder<static>|Song withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|Song withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Song extends Model
{
    /** @use HasFactory<SongFactory> */
    use HasFactory, HasTimestamps, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'singer_id',
        'year',
        'created_by',
        'file_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function singer(): BelongsTo
    {
        return $this->belongsTo(Singer::class);
    }

    public function album(): BelongsToMany
    {
        return $this->belongsToMany(Album::class);
    }

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }
}
