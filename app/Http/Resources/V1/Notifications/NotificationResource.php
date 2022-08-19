<?php

namespace App\Http\Resources\v1\Notifications;

use App\Http\Resources\v1\User\UserNotificationResource;
use App\Http\Resources\V1\User\UserPostResource;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $data = [
            'type' => $this->type,
            'action_user' => new UserNotificationResource($this->action_user),
        ];

        if ($this->type == 'likeComment' || $this->type == 'likePost' || $this->type == 'reply' || $this->type == 'comment')
        $data['object_id'] = $this->object_id;

        return $data;
    }
}
