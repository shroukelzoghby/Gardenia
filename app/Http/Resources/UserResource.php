<?php

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id'=>$this->id,
            'username'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'image'=>$this->image,
            'posts' =>$this->posts,
        ];


    }

    public function with(Request $request)
    {
        return ['posts'];
    }
}
