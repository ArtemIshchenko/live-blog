<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Image;
use App\Models\Message;
use App\Models\Post;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $itemsQuery = Post::orderBy('created_at', 'DESC');

        $user = Auth::user();
        if (!is_null($user) && ($user->getRoleNames()->search('user') !== false)) {
            $itemsQuery->where('is_public', 'in', [Post::VISIBILITY['isPrivate'], Post::VISIBILITY['isPublic']]);
        } else {
            $itemsQuery->where('is_public', Post::VISIBILITY['isPublic']);
        }
        $items = $itemsQuery
            ->where('status', Post::STATUS['approved'])
            ->get();

        $posts = [];
        if ($items->isNotEmpty()) {
            foreach ($items as $it) {
                $authorName = '';
                $author = User::find($it->created_by);
                if (!is_null($author) && !empty($author)) {
                    $authorName = $author->name;
                }
                $posts[] = [
                    'id' => $it->id,
                    'title' => $it->title,
                    'text' => $it->text,
                    'createdAt' => (new DateTime($it['created_at']))->format('M j, Y'),
                    'authorName' => $authorName,
                ];
            }
        }
        return view('home', [
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
        $postModel = Post::findOrFail($id);

        $authorName = '';
        $author = User::find($postModel->created_by);
        if (!is_null($author) && !empty($author)) {
            $authorName = $author->name;
        }


        $images = Image::where('post_id', $postModel->id)->orderBy('created_at', 'DESC')->get();

        $post = [
            'id' => $postModel->id,
            'title' => $postModel->title,
            'text' => $postModel->text,
            'createdAt' => (new DateTime($postModel->created_at))->format('M j, Y'),
            'authorName' => $authorName,
            'images' => $images,
            'readerIsAuthor' => !Auth::guest() && ($postModel->created_by == Auth::id()),
        ];

        return view('show', [
            'post' => $post,
            'images' => $images,
        ]);
    }

    public function writeToAuthor(Request $request, $id) {
        $id = intval($id);
        $postModel = Post::findOrFail($id);

        $request->validate([
            'text' => 'string|required',
        ]);

        $message = new Message();
        $message->text = $request->text;
        $message->writer_id = $postModel->created_by;
        $message->reader_id = Auth::id();
        $message->writer_answer = '';
        $message->is_read = 0;

        if ($message->save()) {
            Contact::where('user_id', $message->reader_id)->where('writer_id', $message->writer_id)->firstOr(function () use($message) {
                $contact = new Contact();
                $contact->user_id = $message->reader_id;
                $contact->user_name = Auth::user()->name;
                $contact->writer_id = $message->writer_id;
                $contact->save();
            });
            $writer = User::find($message->writer_id);
            if (!is_null($writer)) {
                $writer->count_unread_messages += 1;
                $writer->save();
            }

            return redirect()->route('show', ['id' => $id])->withSuccess('Сообщение успешно отправлено');
        }

        return redirect()->route('show', ['id' => $id])->withErrors('Ошибка отправки сообщения');
    }
}
