<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clans', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary();               //clan_id
            
            $table->timestamp('wg_created_at')->nullable();         //created_at
            $table->unsignedInteger('members_count')->nullable();   //members_count
            
            $table->string('name', 250)->nullable();                 //name
            $table->string('tag', 25)->nullable();                  //tag

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clans');
    }
}
