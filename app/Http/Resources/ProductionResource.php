<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'batch_code' => $this->batch_code,
            'product_id' => $this->product_id,
            'production_date' => $this->production_date,
            'finished_date' => $this->finished_date,
            'quantity' => $this->quantity,
            'failed_qty' => $this->failed_qty,
            'good_qty' => $this->good_qty,
            'unit' => $this->unit,
            'status' => $this->status,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            // relasi
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
