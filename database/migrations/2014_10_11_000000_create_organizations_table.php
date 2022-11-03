<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

return new class extends Migration {
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->string('name');
            $table->foreignUuid('owner_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('organizations');
    }
};
