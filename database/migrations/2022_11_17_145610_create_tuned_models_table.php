<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tuned_models', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('label')->nullable();
            $table->string('base_model_name');
            $table->string('fine_tuned_model_name')->nullable()->index();
            $table->string('composition_type_id')->index();
            $table->string('fine_tune_id')->nullable();
            $table->string('training_file_id');
            $table->string('status', 20)->nullable();
            $table->foreignUuid('team_id')->index();
            $table->foreignUuid('user_id')->index();
            $table->json('extras')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tuned_models');
    }
};
