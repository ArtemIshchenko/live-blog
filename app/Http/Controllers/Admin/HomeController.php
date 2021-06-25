<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Contact;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index() {
        $posts_count = Post::where('created_by', Auth::id())->count();
        $contacts_count = Contact::where('writer_id', Auth::id())->count();

        return view('admin.home.index', [
            'posts_count' => $posts_count,
            'contacts_count' => $contacts_count,
        ]);
    }
}
