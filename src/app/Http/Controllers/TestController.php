<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function test()
    {
        $comment = Comment::all();

        var_dump($comment);

    }
}
