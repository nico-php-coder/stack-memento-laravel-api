<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFriendThreadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // FRIEND_THREAD (friend_id, thread_id, created_at, deleted_at)
        Schema::create('friend_thread', function (Blueprint $table) {
            $table->unsignedBigInteger('friend_id');
            $table->unsignedBigInteger('thread_id');
            $table->primary(['friend_id', 'thread_id']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('friend_thread');
    }
}
