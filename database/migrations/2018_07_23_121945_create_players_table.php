<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); //account_id
            $table->string('realm', 3)->nullable(false);
            
            $table->boolean('hidden_profile');
            $table->unsignedInteger('karma');
            
            $table->timestamp('last_battle_time')->nullable(); //Warning. The field will be disabled.

            $table->unsignedInteger('leveling_points');
            $table->unsignedInteger('leveling_tier');
            
            $table->timestamp('logout_at')->nullable();

            $table->string('nickname', 25);

            $table->timestamp('wg_stats_updated_at')->nullable();
            $table->timestamp('wg_updated_at')->nullable();            
            
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
        Schema::dropIfExists('players');
    }
}
