<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quests', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('event_name')->unique();
            $table->unsignedInteger('point');
            $table->boolean('repeatable')->default(false);
            $table->boolean('multipliable')->default(false);
            $table->string('requirement_type');
            $table->string('requirement_user_relation');
            $table->unsignedInteger('requirement_value');
            $table->string('requirement_field')->nullable();
            $table->string('requirement_operator')->default('count');
            $table->unsignedInteger('quota')->nullable();
            $table->unsignedInteger('usage')->nullable();
            $table->unsignedTinyInteger('limit_interval')->nullable();
            $table->unsignedInteger('limit_per_user')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->timestamps();
        });
//        1	User made a Post	NULL	PostCreated	10	1	1	App\Models\Post	posts	50000	body	>=	NULL	NULL
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('quests');
    }
};
