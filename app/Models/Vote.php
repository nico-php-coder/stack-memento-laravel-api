<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Models\Bookmark;

class Vote extends Model
{
    use HasFactory;

    // public function user() {
    //     return $this->belongsToMany(User::class);
    // }

    // public function bookmark() {
    //     return $this->belongsTo(Bookmark::class);
    // }
}
