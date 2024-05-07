<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Mockery\Exception;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class CommentController extends Controller
{

    public function index(Request $request)
    {
        $id = $request->query('post_id');
        $post = Post::find($id);

        if(!$post)
        {
            return response([
                'status' => 'Error',
                'message' => 'Post not found.'
            ], 403);
        }

        return response([
            'status' => 'Success',
            'msg'=>'comment list',
            'data' => ['comments'=>$post->comments()->with('user:id,username,image')->get()]
        ], 200);
    }

    public function store(Request $request)
    {
      try{
        $user = JWTAuth::parseToken()->toUser();
    //  $id = $request->query('post_id');
        $post = Post::find(request('post_id'));

           if(!$post)
    {
        return response([
            'status' => 'Error',
            'msg' => 'Post not found.'
        ], 403);
    }

    //validate fields
          $attrs = $request->validate([
        'content' => 'required|string'
    ]);

           $comment= Comment::create([
        'content' => $attrs['content'],
        'post_id' => request('post_id'),
        'user_id' => User::find($user->id)->id,
    ]);

           return response([
        'status' => 'Success',
        'msg' => 'Comment created.',
        'data'=>$comment,
    ], 200);

    }catch (TokenExpiredException $e){
          return response()->json(['error' => 'Error','msg'=>'Token Expired'], 401);

      }
    catch (\Exception $exception){

    return response()->json(['status'=>'Error','message'=>$exception->getMessage()],422);
    }

    }

    public function update(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->toUser();
            $id = $request->query('comment_id');
            $id1 = $request->query('post_id');
            $comment = Comment::where('post_id', $id1)->where('id', $id)->first();
            if (!$comment) {
                return response([
                    'status' => 'Error',
                    'msg' => 'Comment not found.'
                ], 403);
            }

            if ($comment->user_id != User::find($user->id)->id) {
                return response([
                    'status' => 'Error',
                    'msg' => 'Permission denied.'
                ], 403);
            }

            //validate fields
            $attrs = $request->validate([
                'content' => 'required|string'
            ]);

            $comment->update([
                'content' => $attrs['content']
            ]);

            return response([
                'status' => 'Success',
                'msg' => 'Comment updated.'
            ], 200);
        }catch (TokenExpiredException $e){
            return response()->json(['error' => 'Error','msg'=>'Token Expired'], 401);
        }
        catch (Exception $e){
            return response()->json(['status'=>'Error','message'=>$e->getMessage()],422);
        }
    }


    public function destroy(Request $request)
    {
        try {


            $id = $request->query('comment_id');
            $id1 = $request->query('post_id');

            $comment = Comment::where('post_id', $id1)->where('id', $id)->first();
            $user = JWTAuth::parseToken()->toUser();


            if (!$comment) {
                return response([
                    'status' => 'Error',
                    'msg' => 'Comment not found.'
                ], 403);
            }

            if ($comment->user_id != User::find($user->id)->id) {
                return response([
                    'status' => 'Error',
                    'msg' => 'Permission denied.'
                ], 403);
            }

            $comment->delete();

            return response([
                'status' => 'Success',
                'msg' => 'Comment deleted.'
            ], 200);
        }catch (Exception $e){
            return response()->json(['status'=>'Error','message'=>$e->getMessage()],422);

        }catch (tokenExpiredException $e){
            return response()->json(['error' => 'Error','msg'=>'Token Expired'], 401);
        }

}
}
