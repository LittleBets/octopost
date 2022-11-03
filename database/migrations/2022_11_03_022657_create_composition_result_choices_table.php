<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('composition_result_choices', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->foreignUuid('composition_result_id')->index();
            $table->longText('text');
            $table->json('extras');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('composition_result_choices');
    }
};
