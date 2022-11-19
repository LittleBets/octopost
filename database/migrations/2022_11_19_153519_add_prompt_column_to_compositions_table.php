<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('compositions', function (Blueprint $table) {
            $table->longText('prompt')->nullable();
        });
    }
    public function down()
    {
        Schema::table('compositions', function (Blueprint $table) {
            $table->dropColumn('prompt');
        });
    }
};
