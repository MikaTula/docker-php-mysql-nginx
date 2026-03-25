<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Database\Factories\SingerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property int|null $age
 * @property int $created_by
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property string|null $deleted_at
 * @property-read User|null $creator
 * @property-read Collection<int, Song> $songs
 * @property-read int|null $songs_count
 *
 * @method static SingerFactory factory($count = null, $state = [])
 * @method static Builder<static>|Singer newModelQuery()
 * @method static Builder<static>|Singer newQuery()
 * @method static Builder<static>|Singer query()
 * @method static Builder<static>|Singer whereAge($value)
 * @method static Builder<static>|Singer whereCreatedAt($value)
 * @method static Builder<static>|Singer whereDeletedAt($value)
 * @method static Builder<static>|Singer whereFirstName($value)
 * @method static Builder<static>|Singer whereId($value)
 * @method static Builder<static>|Singer whereLastName($value)
 * @method static Builder<static>|Singer whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class Singer extends Model
{
    /** @use HasFactory<SingerFactory> */
    use HasFactory, HasTimestamps, SoftDeletes;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'age',
        'created_by',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * @return HasMany<Song, Singer>
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }
}
