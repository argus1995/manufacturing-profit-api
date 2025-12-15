<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleItemResource extends JsonResource
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
            'sale_id'     => $this->sale_id,
            'product_id'   => $this->product_id,
            'qty' => $this->qty,
            'price'   => $this->price,
            'subtotal'   => $this->subtotal,
            'product' => new ProductResource($this->whenLoaded('product')),
        ];
    }
}
