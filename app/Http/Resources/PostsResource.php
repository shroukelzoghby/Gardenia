<?php

namespace App\Http\Resources;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\Console\Input\Input;
use App\Models\User;
use App\Http\Controllers\Api\V1\PostController;
use Tymon\JWTAuth\Facades\JWTAuth;


class PostsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            //'user_id'=>$this->user->id,
           'caption'=>$this->caption,
           'image'=>$this->image,
           'post_id'=>$this->id,


        ];

    }
}
