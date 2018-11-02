<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResourse;
use App\Http\Resources\PostResourse;
use App\Post;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function store(Request $request)
    {
        if (!is_null(User::where('api_token', $request->bearerToken())->first())) {
            $valid = Validator::make($request->all(), [
                'title' => 'required|string|unique:posts',
                'anons' => 'required|string',
                'text' => 'required|string',
                'image' => 'required|image|mimes:jpg,png,jpeg|max:2048'
            ]);
            if ($valid->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $valid->messages()
                ])->setStatusCode(400, 'Creating error');
            } else {
                $name = '';
                if ($request->hasFile('image')) {
                    $image = $request->file('image');
                    $name = str_slug($request->title) . '.' . $image->getClientOriginalExtension();
                    $path = public_path('/post_images');
                    $image->move($path, $name);
                }
                $post = Post::create([
                    'title' => $request->title,
                    'anons' => $request->anons,
                    'text' => $request->text,
                    'tags' => $request->tags,
                    'image' => $name
                ]);
                return response()->json([
                    'status' => true,
                    'post_id' => $post->id
                ])->setStatusCode(201, 'Successful creation');
            }


        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ])->setStatusCode(401, 'Unauthorized');
        }
    }

    public function edit(Request $request, $post_id)
    {
        if (!is_null(User::where('api_token', $request->bearerToken())->first())) {

            $post = Post::find($post_id);
            if (!is_null($post)) {
                $valid = Validator::make($request->all(), [
                    'title' => 'required|string|unique:posts,title,' . $post_id,
                    'anons' => 'required|string',
                    'text' => 'required|string',
                    'image' => 'required|image|mimes:jpg,png|max:2048'
                ]);

                if ($valid->fails()) {
                    return response()->json([
                        'status' => false,
                        'message' => $valid->messages()
                    ])->setStatusCode(400, 'Editing  error');
                } else {

                    $name = '';
                    if ($request->hasFile('image')) {
                        $image = $request->file('image');
                        $name = str_slug($request->title) . '.' . $image->getClientOriginalExtension();
                        $path = public_path('/post_images');
                        $image->move($path, $name);
                    }
                    $post->update([
                        'title' => $request->title,
                        'anons' => $request->anons,
                        'text' => $request->text,
                        'tags' => $request->tags,
                        'image' => $name
                    ]);
                    return response()->json([
                        'status' => true,
                        'post' => PostResourse::collection(Post::where('id', $post_id)->get())
                    ])->setStatusCode(201, 'Successful creation');
                }
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ])->setStatusCode(404, 'Post not found');
            }

        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ])->setStatusCode(401, 'Unauthorized');
        }
    }

    public function delete(Request $request, $post_id)
    {
        if (!is_null(User::where('api_token', $request->bearerToken())->first())) {
            $post = Post::find($post_id);
            if (!is_null($post)) {
                $post->delete();
                return response()->json([
                    'status' => true,
                ])->setStatusCode(201, 'Successful delete');
            } else {
                return response()->json([
                    'message' => 'Post not found'
                ])->setStatusCode(404, 'Post not found');
            }
        } else {
            return response()->json([
                'message' => 'Unauthorized'
            ])->setStatusCode(401, 'Unauthorized');
        }
    }

    public function get(Request $request)
    {
        $posts = Post::all();
        return response()->json(PostResourse::collection($posts))->setStatusCode(200, 'List posts');
    }

    public function getOne(Request $request, $post_id)
    {
        $post = Post::find($post_id);
        if (!is_null($post)) {

            return response()->json([
                'title' => $post->title,
                'datatime' => date('H:i d.m.Y', strtotime($post->created_at)),
                'anons' => $post->anons,
                'text' => $post->text,
                'tags' => is_null($post->tags) ? null : explode(',', $post->tags),
                'image' => $post->image,
                'comments' => CommentResourse::collection($post->comments)
            ])->setStatusCode(200, 'View post');
        } else {
            return response()->json([
                'message' => 'Post not found'
            ])->setStatusCode(404, 'Post not found');
        }
    }

    public function search(Request $request, $tag)
    {
        $posts = Post::where('tags','LIKE',"%$tag%")->get();
        return response()->json(PostResourse::collection($posts))->setStatusCode(200, 'Found posts');
    }
}
