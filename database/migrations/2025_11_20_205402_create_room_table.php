<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id()->unique();//room id
            $table->longText('allText');//all the text in one string
            /**structure:
             * like a json{
             *  id:user_id,
             * text:message,
             * time:timestamp,
             * }
             * separating this 3 things before going to the page
             * every message with a "|"
             * example:
             * {id:1,text:hello,time:123456}|{id:2,text:hi,time:123457}
             */
            $table->string("allowedusers");
            //allowed users when enter get all alloweduser ids and check if user id is in the string
            //type of text: "1,2,3,4"
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
