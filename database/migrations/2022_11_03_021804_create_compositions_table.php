<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('compositions', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary();
            $table->string('template')->index();
            $table->string('label')->nullable();
            $table->json('payload');
            $table->foreignUuid('user_id')->index();
            $table->foreignUuid('team_id');
            $table->foreignUuid('root_composition_id')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('compositions');
    }
};
