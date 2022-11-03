<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('team_invitations', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->foreignUuid('team_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('role')->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'email']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('team_invitations');
    }
};
