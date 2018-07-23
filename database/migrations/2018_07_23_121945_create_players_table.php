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
            $table->increments('id');
            $table->string('realm', 3)->nullable(false);
//            $table->unsignedInteger('tenant')->nullable(false)->default(1);
            
            $table->unsignedInteger('account_id')->nullable(false);
            
            $table->boolean('hidden_profile');
            $table->unsignedInteger('karma');
            
            $table->timestamp('last_battle_time'); //Warning. The field will be disabled.

            $table->unsignedInteger('leveling_points');
            $table->unsignedInteger('leveling_tier');
            
            $table->timestamp('logout_at');

            $table->string('nickname', 25);

            $table->timestamp('wg_stats_updated_at');
            $table->timestamp('wg_updated_at');            
            
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
