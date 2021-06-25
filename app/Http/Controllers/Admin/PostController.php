<?php

namespace App\Http\Controllers\Admin;

use App\Models\Image;
use App\Models\Post;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $postsQuery = Post::orderBy('created_at', 'DESC');
        $user = Auth::user();
        if (!is_null($user) && ($user->getRoleNames()->search('user') !== false)) {
            $postsQuery->where('created_by', $user->id);
        }
        $posts = $postsQuery->get();

        return view('admin.post.index', [
            'posts' => $posts
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.post.create', [
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
            'title' => 'required|unique:posts|max:128',
            'text' => 'string|required',
            'status' => 'integer',
            'is_public' => 'integer',
            'refuse_reason' => 'string|max:128',
            'created_by' => 'integer',
            'updated_by' => 'integer',
            'path' => 'unique:images',
        ]);

        $post = new Post();
        $post->title = $request->title;
        $post->text = $request->text;
        $post->status = Post::STATUS['new'];
        $post->is_public = $request->is_public;
        $post->refuse_reason = '';
        $post->created_by = Auth::id();
        $post->updated_by = Auth::id();

        if ($post->save()) {
            if (isset($_POST['feature_image']) && !empty($_POST['feature_image'])) {
                foreach ($_POST['feature_image'] as $path) {
                    $image = new Image();
                    $image->post_id = $post->id;
                    $image->path = $path;
                    $image->save();
                }
            }

            return redirect()->route('post.edit', $post->id)->withSuccess('Статья была успешно добавлена');
        }

        return redirect()->back()->withErrors('Ошибка добавления статьи');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     *  @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        if (in_array($post->status, [Post::STATUS['sendToModerate'], Post::STATUS['locked']])) {
            abort(404);
        }

        $images = Image::where('post_id', $post->id)->orderBy('created_at', 'ASC')->get();

        return view('admin.post.edit', [
            'post' => $post,
            'images' => $images,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        if (in_array($post->status, [Post::STATUS['sendToModerate'], Post::STATUS['approved'], Post::STATUS['locked']])) {
            abort(404);
        }

        $request->validate([
            'title' => 'required|max:128',
            'text' => 'required|string',
            'status' => 'integer',
            'is_public' => 'integer',
            'path' => 'unique:images',
        ]);

        $post->title = $request->title;
        $post->text = $request->text;
        $post->is_public = $request->is_public;

        $post->save();

        $images = Image::where('post_id', $post->id)->orderBy('created_at', 'DESC')->get();
        if (isset($_POST['feature_image']) && !empty($_POST['feature_image'])) {
            foreach ($_POST['feature_image'] as $i => $path) {
                if (!is_null($images) && isset($images[$i])) {
                    if ($path != $images[$i]['path']) {
                        $images[$i]['path'] = $path;
                        $images[$i]->save();
                    }
                } else {
                    $image = new Image();
                    $image->post_id = $post->id;
                    $image->path = $path;
                    $image->save();
                }
            }
        }

        return redirect()->back()->withSuccess('Статья была успешно обновлена');
    }

    public function sendToModerate($id)
    {
        if (in_array($post->status, [Post::STATUS['sendToModerate'], Post::STATUS['approved'], Post::STATUS['locked']])) {
            abort(404);
        }
        $id = intval($id);
        $postModel = Post::findOrFail($id);
        $postModel->status = Post::STATUS['sendToModerate'];

        if ($postModel->save()) {
            return redirect()->route('post.index')->withSuccess('Статья успешно отправлена на модерацию');
        }

        return redirect()->route('post.index')->withErrors('Ошибка отправки статьи на модерацию');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        if ($post->delete()) {
            Image::where('post_id', $post->id)->delete();
        }
        return redirect()->back()->withSuccess('Статья была успешно удалена');
    }
}
