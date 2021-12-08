<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Resources\Json\JsonResource;


class BookingRatingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            => $this->id,
            'rating'        => $this->rating,
            'review'        => $this->review,
            'service_id'    => $this->service_id,
            'service_name'    => optional($this->service)->name,
            'attchments' => getAttachments($this->service->getMedia('service_attachment')),
            'booking_id'    => $this->booking_id,
            'created_at'    => date('Y-m-d', strtotime($this->created_at)),
            'customer_id'   => $this->customer_id,
            'customer_name' => optional($this->customer)->display_name,
            'profile_image' => getSingleMedia($this->customer, 'profile_image',null)
        ];
    }
}
