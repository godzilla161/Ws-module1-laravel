<?php

namespace App\Http\Controllers;

use App\Comment;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function store(Request $request, $post_id)
    {
        if (!is_null(Post::find($post_id))) {
            $valid = null;
            $author = '';
            if (!is_null(User::where('api_token', $request->bearerToken())->first())) {
                $valid = Validator::make($request->all(), [
                    'comment' => 'required|string',
                ]);
                $author = 'admin';
            } else {
                $valid = Validator::make($request->all(), [
                    'comment' => 'required|string',
                    'author' => 'required|string'
                ]);
            }
            if ($valid->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $valid->messages()
                ])->setStatusCode(400, 'Creating   error');
            } else {
                if ($author == '') $author = $request->author;
                Comment::create([
                    'comment' => $request->comment,
                    'author'=>$author,
                    'post_id'=>$post_id
                ]);
                return response()->json([
                    'status' => true,
                ])->setStatusCode(201, 'Successful creation');
            }

        } else {
            return response()->json([
                'message' => 'Post not found'
            ])->setStatusCode(404, 'Post not found');
        }
    }

    public function delete(Request $request, $post_id, $comment_id)
    {
        if (!is_null(User::where('api_token', $request->bearerToken())->first())) {
            if (!is_null(Post::find($post_id))) {
                $comment = Comment::find($comment_id);
                if (!is_null($comment)) {
                    $comment->delete();
                    return response()->json([
                        'status' => true,
                    ])->setStatusCode(201, 'Successful delete');

                } else {
                    return response()->json([
                        'message' => 'Comment not found'
                    ])->setStatusCode(404, 'Comment not found');
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
}
