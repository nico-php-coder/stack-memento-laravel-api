<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    public function postAvatar(Request $request)
    {
        $extension = ".".$request->avatar->extension();
        $path = $request->file('avatar')->storeAs('avatars', Auth::user()->alphanumeric_id.$extension);
 
        return $path;
    }

    public function getUserInfos() {
      $user = Auth::user();
      return [
        'alphanumeric_id' => $user->alphanumeric_id,
        'status' => 'authenticated',
        'email' => $user->email,
        'pseudonym' => $user->pseudonym,
        'image_url' => $user->image_url,
        'email_verified_at' => $user->email_verified_at,
      ];
    }

    public function test () {
      $user = User::where('id', 1)->first();

      return $user->subscribedGroups
        ->map(function ($group) {
        return [
          "alphanumeric_id" => $group->alphanumeric_id,
          "name" => $group->name,
          "image_url" => "ressource/groups/$group->alphanumeric_id",
          "owner" => $group->owner,
          "subscribers" => $group->subscribers,
        ];
      });

      // return $user->threads;
    }
}
