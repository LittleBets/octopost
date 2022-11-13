<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('composition_result_choices', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary();
            $table->foreignUuid('composition_result_id')->index();
            $table->longText('text');
            $table->json('extras');
            $table->foreignUuid('last_edited_by')->nullable();
            $table->dateTime('last_edited_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('composition_result_choices');
    }
};
