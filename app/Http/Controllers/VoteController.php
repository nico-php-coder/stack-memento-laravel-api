<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function allOfAuth() {
        return Auth::user()->votes;
    }

    public function countAllOfAuth() {
        return count($this->allOfAuth());
    }
}
