<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Thread;
use App\Models\Friend;
use App\Models\Comment;
use App\Models\Group;
use App\Models\Vote;
use App\Models\Bookmark;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'alphanumeric_id',
        'pseudonym',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
        'email',
        'email_verified_at',
        'is_admin',
        'deleted_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i',
        'created_at' => 'datetime:Y-m-d H:i',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute()
    {
        return 'ressource/avatars/'.$this->alphanumeric_id;
    }

    public function threads() {
        return $this->hasMany(Thread::class);
    }

    public function friends() {
        return $this->hasMany(Friend::class);
    }

    public function comments() {
        return $this->hasMany(Comment::class, 'poster_id');
    }

    public function subscribedGroups() {
        return $this->belongsToMany(Group::class);
    }

    public function ownGroups() {
        return $this->hasMany(Group::class, 'owner_id');
    }

    public function votes() {
        return $this->belongsToMany(Bookmark::class, 'votes');
    }

    public function redirections() {
        return $this->belongsToMany(Bookmark::class, 'redirections')->withPivot('count');
    }

    public function pinnedThreads() {
        return $this->belongsToMany(Thread::class, 'pinned_threads');
    }

    public function countThreads () {
        return count($this->threads);
    }

    public function countBookmarks () {
        return count($this->getBookmarks());
    }

    public function countVotes () {
        return count($this->votes);
    }

    public function getBookmarks () {
        $bookmarks = [];
        $threads = $this->threads;
        foreach ($threads as $thread) {
            foreach ($thread->bookmarks as $bookmark) {
                array_push($bookmarks, $bookmark);
            }
        }
        return $bookmarks;
    }

    public function getLastBookmarkDate () {
        $collection = collect($this->getBookmarks());
        $last = $collection->where('id', $collection->max('id'))->first();

        if (empty($last)) {
            return "";
        }

        $date = $last->created_at;
        return date("Y-m-d H:i", strtotime($date));
    }

    public function getLastCommentDate () {
        $comment = $this->comments->last();

        if (empty($comment)) {
            return "";
        }

        $date = $comment->created_at;
        return date('Y-m-d H:i', strtotime($date));
    }

    public function countRedirections () {
        $redirections = $this->redirections;
        if (empty($redirections)) {
            return 0;
        }

        $count = 0;
        foreach ($redirections as $redirection) {
            $count += $redirection->pivot->count;
        }

        return $count;
    }

    public function countComments () {
        return count($this->comments);
    }

    public function getFellowDetails () {
        $this->threads->map(function ($thread) {
            return $thread->visibility === "private" ? 
                null : $thread->getThreadDetails();
        });
        $this->total_bookmarks = $this->countBookmarks();
        $this->total_threads = $this->countThreads();
        $this->total_redirection = $this->countRedirections();
        $this->total_comments = $this->countComments();
        $this->total_votes = $this->countVotes();
        $this->last_comment = $this->getLastCommentDate();
        $this->last_bookmark = $this->getLastBookmarkDate();

        return $this;
    }

}
