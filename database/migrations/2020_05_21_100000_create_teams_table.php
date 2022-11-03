<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration {
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->foreignUuid('user_id')->nullable();
            $table->foreignUuid('organization_id')->nullable();
            $table->string('name');
            $table->boolean('personal_team');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('teams');
    }
};
