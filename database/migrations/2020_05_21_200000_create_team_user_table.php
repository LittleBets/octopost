<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration {
    public function up()
    {
        Schema::create('team_user', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->foreignUuid('team_id');
            $table->foreignUuid('user_id');
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_user');
    }
};
