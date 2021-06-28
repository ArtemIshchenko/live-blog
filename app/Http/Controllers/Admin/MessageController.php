<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Message;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DateTime;

class MessageController extends Controller
{
    /**
     * Display the specified contact.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function contact($id)
    {
        $id = intval($id);
        $contact = Contact::where('user_id', $id)->firstOrFail();

        $messages = Message::where('writer_id', Auth::id())
                ->where('reader_id', $id)
                ->get();

        $unreadMessages = Message::where('writer_id', Auth::id())
                ->where('reader_id', $id)
                ->where('is_read', 0)
                ->get();

        $user = User::find(Auth::id());
        foreach ($unreadMessages as $msg) {
            $msg->is_read = 1;
            if ($msg->save()) {
                if (!is_null($user) && ($user->count_unread_messages > 0)) {
                    $user->count_unread_messages -= 1;
                }
            }
        }
        $user->save();

        $isFirstQuestion = false;
        $firstMessage = Message::where('writer_id', Auth::id())
            ->where('reader_id', $id)
            ->orderBy('created_at', 'asc')
            ->first();
        $firstMessageFromReader = Message::where('writer_id', $id)
            ->where('reader_id', Auth::id())
            ->orderBy('created_at', 'asc')
            ->first();

        if ((!is_null($firstMessage) && !is_null($firstMessageFromReader) && ($firstMessageFromReader->created_at < $firstMessage->created_at)) || (!is_null($firstMessageFromReader) && is_null($firstMessage))) {
            $isFirstQuestion = true;
        }

        return view('admin.message.contact', [
            'contact' => $contact,
            'messages' => $messages,
            'isFirstQuestion' => $isFirstQuestion,
            'firstMessageFromReader' => $firstMessageFromReader,
        ]);
    }

    /**
     * Write answer.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function writeAnswer($id)
    {
        $id = intval($id);
        $request = app()->request;
        if ($request->ajax()) {
            $json = ['result' => 'error'];
            $answer = Message::findOrFail($id);
            $answer->writer_answer = $request->writer_answer;
            $answer->is_read = 1;
            if ($answer->save()) {
                $message = new Message();
                $message->text = $request->writer_answer;
                $message->writer_id = $answer->reader_id;
                $message->reader_id = $answer->writer_id;
                $message->writer_answer = '';
                $message->is_read = 0;

                if ($message->save()) {
                    Contact::where('user_id', $answer->writer_id)->where('writer_id', $answer->reader_id)->firstOr(function () use($message, $answer) {
                        $user = User::find($answer->writer_id);
                        if (!is_null($user)) {
                            $contact = new Contact();
                            $contact->user_id = $answer->writer_id;
                            $contact->user_name = $user->name;
                            $contact->writer_id = $answer->reader_id;
                            $contact->save();
                        }
                    });

                    $user = User::find($message->writer_id);
                    if (!is_null($user)) {
                        $user->count_unread_messages += 1;
                        $user->save();
                    }

                    $json = ['result' => 'success', 'updatedAt' => (new DateTime($answer->updated_at))->format('M j, Y h:i')];
                }
            }
            print json_encode($json);
            exit;
        }
    }
}
