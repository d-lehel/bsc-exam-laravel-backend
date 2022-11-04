<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'from_user' => User::where('id',$this->from_user_id)->value('name'),
            'to_user' => User::where('id',$this->to_user_id)->value('name'),
            'subject' => $this->subject,
            'message' => $this->message,
        ];
    }
}
