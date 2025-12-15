<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\SaleItemResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'date'     => $this->date,
            'customer'   => $this->customer,
            'total_amount'   => $this->total_amount,
            'items' => SaleItemResource::collection($this->whenLoaded('items')),
        ];
    }
}
