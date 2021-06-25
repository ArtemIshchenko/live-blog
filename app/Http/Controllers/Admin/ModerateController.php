<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class ModerateController extends Controller
{

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware(['role:moder']);
    }

    public function index($status = -1) {
        $status = intval($status);
        $postsQuery = Post::where('status', '!=', Post::STATUS['new']);
        if ($status > -1) {
            $postsQuery->where('status', $status);
        }
        $posts = $postsQuery->orderBy('created_at', 'DESC')->get();

        return view('admin.moderate.index', [
            'posts' => $posts,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = intval($id);
        $post = Post::findOrFail($id);
        $images = Image::where('post_id', $post->id)->orderBy('created_at', 'ASC')->get();

        return view('admin.moderate.show', [
            'post' => $post,
            'images' => $images,
        ]);
    }

    /**
     * Update the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        if (!empty($request->post('update', false))) {
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

            return redirect()->back()->withSuccess('Статья обновлена');
        } elseif (!empty($request->post('approve', false))) {
            $request->validate([
                'status' => 'integer',
            ]);

            $post->status = Post::STATUS['approved'];
            $post->refuse_reason = '';

            $post->save();

            return redirect()->back()->withSuccess('Статья утверждена');
        } elseif (!empty($request->post('refuse', false))) {
            $request->validate([
                'status' => 'integer',
                'refuse_reason' => 'max:256',
            ]);

            $post->status = Post::STATUS['refused'];
            $post->refuse_reason = '';
            if (!is_null($request->refuse_reason)) {
                $post->refuse_reason = $request->refuse_reason;
            }

            $post->save();

            return redirect()->back()->withSuccess('Статья отклонена');
        } elseif (!empty($request->post('lock', false))) {
            $request->validate([
                'status' => 'integer',
                'refuse_reason' => 'max:256',
            ]);

            $post->status = Post::STATUS['locked'];
            $post->refuse_reason = '';
            if (!is_null($request->refuse_reason)) {
                $post->refuse_reason = $request->refuse_reason;
            }

            $post->save();

            return redirect()->back()->withSuccess('Статья заблокирована');
        }
    }

    public function user($status = -1) {
        $status = intval($status);
        $userQuery = User::newBaseQueryBuilder()->from('users');
        if ($status > -1) {
            $userQuery->where('status', $status);
        }
        $users = $userQuery->orderBy('created_at', 'DESC')->get();

        return view('admin.moderate.user', [
            'users' => $users,
        ]);
    }

    /**
     * Change status the specified resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @param  int $status
     * @return \Illuminate\Http\Response
     */
    public function userChangeStatus(Request $request, $id, $status)
    {
        $id = intval($id);
        $status = intval($status);
        $user = User::findOrFail($id);

        $request->validate([
            'status' => 'integer',
        ]);

        $user->status = $status;
        $user->save();
        $message = 'Пользователь заблокирован';
        if ($user->status == User::STATUS['actived']) {
            $message = 'Пользователь розблокирован';
        }

        return redirect()->back()->withSuccess($message);
    }
}
