<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
date_default_timezone_set('Asia/Jakarta');

class PostController extends Controller
{
    public function index() {
        $posts = Post::latest()->get();
        return view('posts.index', ['posts' => $posts]);
    }

    public function create() {
        return view("posts.create");
    }

    public function store(Request $request) {
        $this->validate($request, [
            "title" => "required | string | max:155",
            "content" => "required",
            "status" => "required"
        ]);

        $post = Post::create([
            "title" => $request->title,
            "content" => $request->content,
            "status" => $request->status,
            "slug" => Str::slug($request->title)
        ]);

        if($post) {
            return redirect()
            ->route("post.index")
            ->with([
                "success" => "New post has been created successfully"
            ]);
        } else {
            return redirect()
            ->back()
            ->withInput()
            ->with([
                "error" => "Some problem occurred, please try again"
            ]);
        }
    }

    public function edit($id) {
        $post = Post::findOrFail($id);
        return view("posts.edit", compact("post"));
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            "title" => "required | string | max:155",
            "content" => "required",
            "status" => "required"
        ]);

        $post = Post::findOrFail($id);

        $post->update([
            "title" => $request->title,
            "content" => $request->content,
            "status" => $request->status,
            "slug" => Str::slug($request->title)
        ]);

        if($post) {
            return redirect()
            ->route("post.index")
            ->with([
                "success" => "Post has been updated successfully"
            ]);
        } else {
            return redirect()
            ->back()
            ->withInput()
            ->with([
                "error" => "Some problem occurred, please try again"
            ]);
        }
    }

    public function destroy($id) {
        $post = Post::findOrFail($id);
        $post->delete();

        if($post) {
            return redirect()
            ->route("post.index")
            ->with([
                "success" => "Post has been deleted successfully"
            ]);
        } else {
            return redirect()
            ->back()
            ->withInput()
            ->with([
                "error" => "Some problem occurred, please try again"
            ]);
        }
    }
}
