<?php

namespace App\Models;

use Database\Factories\SingerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $first_name
 * @property string|null $last_name
 * @property int|null $age
 * @property \Carbon\CarbonImmutable|null $created_at
 * @property \Carbon\CarbonImmutable|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Song> $songs
 * @property-read int|null $songs_count
 *
 * @method static \Database\Factories\SingerFactory factory($count = null, $state = [])
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
     * @return HasMany<Song, Singer>
     */
    public function songs(): HasMany
    {
        return $this->hasMany(Song::class);
    }

    /**
     * @return Attribute
     */
    public function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->first_name.' '.$this->last_name
        );
    }
}
