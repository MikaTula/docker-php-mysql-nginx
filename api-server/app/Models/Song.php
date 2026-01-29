<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $user_id
 * @property int $year
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @method static Builder<static>|Song newModelQuery()
 * @method static Builder<static>|Song newQuery()
 * @method static Builder<static>|Song query()
 * @method static Builder<static>|Song whereCreatedAt($value)
 * @method static Builder<static>|Song whereId($value)
 * @method static Builder<static>|Song whereName($value)
 * @method static Builder<static>|Song whereUpdatedAt($value)
 * @method static Builder<static>|Song whereUserId($value)
 * @method static Builder<static>|Song whereYear($value)
 * @mixin \Eloquent
 */
class Song extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_id',
        'year',
    ];
}
