<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Http\Requests\PostStoreRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\str;
use App\Http\Resources\PostsResource;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(User $user)
    {
        $posts= Post::orderBy('created_at', 'desc')->with('user:id,username,image')->withCount('comments')->get();
        return response()->json(['status'=>'Success','msg'=>'Posts found','data'=>['posts'=>$posts]],200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostStoreRequest $request)
    {
        try{
            $user = JWTAuth::parseToken()->toUser();

            //validate fields
            $attrs = $request->validate([
                'caption' => 'required|string'
            ]);

           // $imageName= str::random(32).".".$request->image->getClientOriginalExtension();
            $imageUrl = '/images/' . str::random(32).".".$request->image->getClientOriginalExtension();

            $post = Post::create([
                'caption' => $attrs['caption'],
                'user_id' => User::find($user->id)->id,
                'image' => $imageUrl
            ]);
            //Storage::disk('public')->put($imageName,file_get_contents($request->image));
            $request->file('image')->move(public_path('images'), $imageUrl);

            $request->user()->posts()->save($post);


            // for now skip for post image

            return response([
                'status'=>'Success',
                'msg' => 'Post created.',
                'data' => $post,
            ], 200);

        }catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 401);

        }catch (Exception $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 422);

        }




    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $id = $request->query('post_id');
        $post=Post::find($id);
        $post1=Post::where('id', $id)->withCount('comments')->get();

        if(!$post){
            return response()->json([
               'status'=>'Error', 'msg'=>'Post Not Found!'
            ],404);

        }

        return response()->json([
            'status'=>'Success',
            'msg'=>'Post Found',
            'post'=>$post1,
        ],200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        try {
            $user = JWTAuth::parseToken()->toUser();
            $id1 = $request->query('post_id');
            //$id = $request->query('user_id');

            $post=Post::find($id1);


            if(!$post){
                return response()->json([
                    'status'=>'Error', 'msg'=>'Post Not Found!'

                ],404);
            }

            


            $post->caption=$request->caption;

            if($request->image){

                // Public storage with subdirectory
                $storage = Storage::disk('public');

                // Delete old image with subdirectory path (using path helper)
                !is_null($post->image) && $storage->delete(public_path('images/' . $post->image));

                //image name
                $imageUrl = '/images/' . str::random(32).".".$request->image->getClientOriginalExtension();
                $post->image=$imageUrl ;

                //save image in storage
                $request->file('image')->move(public_path('images'), $imageUrl);




            }
            $post->save();

            return response()->json(['status'=>'Success','msg'=>'post is updated'],200);


        }catch (TokenExpiredException $e){
            return response()->json(['status'=>'Error','msg'=>$e->getMessage()], 401);

        }
        catch (\Exception $e){
            return response()->json([
                'status'=>'Error',
                'msg'=>$e->getMessage()
            ],422);

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        $id = $request->query('post_id');
        $post= Post::find($id);


            if(!$post){
                return response()->json([
                    'status'=>'Error', 'msg'=>'Post Not Found!'

                ],404);
            }

            // Public storage
            $storage = Storage::disk('public');

            // image delete
            !is_null($post->image) && $storage->delete(public_path('images/' . $post->image));

            // Delete post
            $post->comments()->delete();
            $post->delete();


        return response()->json([
            'status'=>'Success',
            'msg' => "Post successfully deleted."
        ],200);
    }
}
