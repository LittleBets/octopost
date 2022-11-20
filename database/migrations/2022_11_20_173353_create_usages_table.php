<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('usages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->integer('prompt_credits');
            $table->integer('completion_credits');
            $table->integer('total_credits');
            $table->uuidMorphs('usageable');
            $table->foreignUuid('user_id');
            $table->foreignUuid('team_id');
            $table->foreignUuid('organization_id');
            $table->json('extras')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('usages');
    }
};
