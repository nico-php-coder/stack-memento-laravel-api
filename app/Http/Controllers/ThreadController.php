<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use App\Models\Thread;
use App\Models\User;

class ThreadController extends Controller
{

    public function countAllOfAuth() {
        return Auth::user()->threads->count();
    }

    private function allFullByUser($user) {
        return $user->threads->map(function ($thread) {
            return $thread->getThreadDetails();
        });
    }

    public function allFullOfAuth() {
        return $this->allFullByUser(Auth::user());
    }

    public function allFullOfGlobal() {
        $global = User::where('email', 'global@stackmemento.com')->first();

        $threads = $global->threads->map(function ($thread) {
            $thread->bookmarks = $thread->getGlobalBookmarks();
            return $thread;
        });

        return $threads;

    }

    public function pinnedOfAuth() {
        return Auth::user()->pinnedThreads->map(function ($thread) {
            return $thread->getThreadDetails();
        });
    }

    public function postThread (Request $request) {

        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max: 128'],
            'visibility' => ['required', 'string'],
            'color' => ['required', 'string', 'max: 6'],
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors());
        }

        $thread = Thread::create([
            'alphanumeric_id' => $this->generateANID(8),
            'user_id' => Auth::user()->id,
            'title' => $request->title,
            'visibility' => $request->visibility,
            'color' => $request->color,
        ]);

        return response()->json([
            'status' => 'thread added',
            'thread' => $thread->getThreadDetails(),
        ]);
    }

    public function generateANID($length) {
        $alphaNumerics = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
        $random = "";
        for ($i = 0; $i < $length; $i++) {
            $random .= $alphaNumerics[rand(0, strlen($alphaNumerics) - 1)];
        }
        return $random;
    }

}
