<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddDateColumnToAttendancesTable extends Migration
{
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->date('date')->after('user_id')->nullable();
        });

        // Actualizar los registros existentes en una consulta separada
        DB::statement('UPDATE attendances SET date = DATE(created_at)');
    }

    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
}
