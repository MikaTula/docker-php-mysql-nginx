<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property string $disk
 * @property string $path
 * @property string $original_name
 * @property string|null $mime_type
 * @property int $size
 * @property CarbonImmutable|null $created_at
 * @property CarbonImmutable|null $updated_at
 * @property-read User|null $owner
 * @property-read Song|null $song
 */
class File extends Model
{
    protected $table = 'files';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'description',
        'disk',
        'path',
        'original_name',
        'mime_type',
        'size',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function song(): HasOne
    {
        return $this->hasOne(Song::class);
    }

    public function deletePhysicalFile(): void
    {
        if ($this->path === '') {
            return;
        }

        Storage::disk($this->disk)->delete($this->path);
    }
}
