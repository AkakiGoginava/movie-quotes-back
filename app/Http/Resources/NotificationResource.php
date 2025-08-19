<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'user_id'         => $this->user_id,
            'from_user_id'    => $this->from_user_id,
            'type'            => $this->type,
            'notifiable_id'   => $this->notifiable_id,
            'notifiable_type' => $this->notifiable_type,
            'read_at'         => $this->read_at,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
            'from_user'       => new UserResource($this->fromUser),
            'quote'           => new QuoteResource($this->quote),
        ];
    }
}
