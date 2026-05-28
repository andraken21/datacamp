<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('practice_session', function (Blueprint $table) {
        $table->string('url')->nullable()->after('nama_session');
    });
}

public function down()
{
    Schema::table('practice_session', function (Blueprint $table) {
        $table->dropColumn('url');
    });
}
};
