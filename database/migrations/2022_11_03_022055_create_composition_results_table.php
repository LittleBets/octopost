<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up()
    {
        Schema::create('composition_results', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary();
            $table->string('remote_id');
            $table->string('type');
            $table->string('model');
            $table->json('usage');
            $table->foreignUuid('composition_id')->index();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('composition_results');
    }
};
