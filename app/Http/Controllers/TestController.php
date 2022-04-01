<?php

namespace App\Http\Controllers;

use App\Gamify\Points\PostCreated;
use App\Models\Quest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function eventPoint(Request $request) {
        $user = User::first();
        $post = Post::first();
        $event = new PostCreated($post);
//       return User::first()->givePoint($event);
        $ep = Quest::first();
        dd($ep->isQuotaReached());
        $fu = $ep->isRequirementFulfill($user, $post);
        return User::first()->givePoint($event);
    }
}
