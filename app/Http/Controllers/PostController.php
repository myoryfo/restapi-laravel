<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\PostDetailResource;



class PostController extends Controller
{


    public function index()
    {
        $posts = Post::all();
        //this is for taking all data
        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::with('author:id,username')->findOrFail($id);
        // this is only for taking 1 array data
        return new PostDetailResource($post);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'news_content' => 'required',
        ]);

        $request['author_id'] = Auth::user()->id;
        // return response()->json($request);
        $post = Post::create($request->all());
        return new PostDetailResource($post->loadMissing('author:id,username'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|unique:posts|max:255',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);
        $post->update($request->all());
        return response()->json('update completed');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(['status' => 200, 'message' => 'data has beed deleted'], 200);
    }
}
