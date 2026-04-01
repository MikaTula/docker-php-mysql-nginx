<?php

namespace App\Http\Resources;

use App\Models\Singer;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SingerResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Singer $this */
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'age' => $this->age,
            'created_by' => $this->created_by,
            'created_at' => $this->created_at?->format('d/m/Y'),
            'updated_at' => $this->updated_at?->format('d/m/Y'),
        ];
    }
}
