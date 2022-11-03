<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table
                ->uuid('id')
                ->primary()
                ->default(new Expression('uuid_generate_v4()'));
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignUuid('current_team_id')->nullable();
            $table->foreignUuid('organization_id')->nullable()->index();
            $table->string('organization_role', 25)->default('member');
            $table->string('profile_photo_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
