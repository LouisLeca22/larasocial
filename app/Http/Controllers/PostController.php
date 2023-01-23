<?php

namespace App\Http\Controllers;

use App\Jobs\SendNewPostEmail;
use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PostController extends Controller
{   

    public function showCreateForm(){

        return view("create-post");
    }

    public function storeNewPost(Request $request){
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);
        $fields['user_id'] = auth()->id();

        $newPost = Post::create($fields);

   
        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'title' => $newPost["title"],             'name' => auth()->user()->username
    ]));

        return redirect("/post/{$newPost->id}")->with('success', 'Nouveau poste créé');
    }

    public function storeNewPostApi(Request $request){
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);

        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);
        $fields['user_id'] = auth()->id();

        $newPost = Post::create($fields);

        dispatch(new SendNewPostEmail(['sendTo' => auth()->user()->email, 'title' => $newPost["title"],             'name' => auth()->user()->username
    ]));

    return response()->json(['post' => $newPost->id], 200);

    }


    public function viewSinglePost(Post $post){
        $post['body'] = strip_tags(Str::markdown($post->body), '<p><ul><ol><li><strong><em><h1><h2><h3><br>');
        return view('single-post', ['post' => $post]);
    }

    public function delete(Post $post){
        // if(auth()->user()->cannot('delete', $post)){
        //     return "Non autorisé";
        // }
        $post->delete();
        return redirect('/profile/'.auth()->user()->username)->with('success', 'Le poste a été supprimé');
    }

    
    public function deletePostApi(Post $post){
        $post->delete();
        return response()->json(['message' => "Le poste a été supprimé"], 404);

    }

    public function showEditForm(Post $post){

        return view('edit-post', ['post' => $post]);
    }

    public function update(Post $post, Request $request){
        $fields = $request->validate([
            'title' => 'required',
            'body' => 'required'
        ]);
        $fields['title'] = strip_tags($fields['title']);
        $fields['body'] = strip_tags($fields['body']);

        $post->update($fields);
        return back()->with('success', 'Le poste a été mis à jour');
    }
    
    public function search($term){
        $posts = Post::search($term)->get();
        $posts->load('user:id,username,avatar'); 
        return $posts;
    }
}
