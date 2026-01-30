<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $pass = bcrypt('12345678');
        // return $pass;

        // try {
        //     return PostResource::collection(Post::with('auther')->get());
        // } catch (Exception $e) {
        //     return $e->getMessage();
        // }

        $user = Request()->user();
        $posts = $user->posts()->paginate();
        return $posts;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request)
    {
        try {
            $data = $request->validated();
            $data['auther_id'] = $request->user()->id;
            $post = Post::create($data);
            return response()->json(new PostResource($post), 201);
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(post $post)
    {
        abort_if(Auth::id() != $post->auther_id, 403, 'Access Forbidden');

        return new PostResource($post);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StorePostRequest $request, Post $post)
    {
        try {
            abort_if(Auth::id() != $post->auther_id, 403, 'Access Forbidden');

            $data = $request->validate([
                'title' => 'required|string|min:2',
                'body' => ['required', 'string', 'min:2'],

            ]);
            $post->update($data);
            $post->save();


            return response()->json([
                'message' => 'Post updates succefully',
                [
                    new PostResource($post)
                ]
            ]);
        } catch (exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        abort_if(Auth::id() != $post->auther_id, 403, 'Access Forbidden');
        $post->delete();
        return response()->noContent();
    }
}
