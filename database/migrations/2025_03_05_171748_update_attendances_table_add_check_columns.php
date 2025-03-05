<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAttendancesTableAddCheckColumns extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Primero eliminar la restricción única si existe
            if (Schema::hasTable('attendances')) {
                $indexes = collect(\DB::select("SHOW INDEXES FROM attendances"))
                    ->pluck('Key_name');
                
                if ($indexes->contains('attendances_user_id_date_unique')) {
                    $table->dropUnique(['user_id', 'date']);
                }
            }

            // Eliminar columnas antiguas si existen
            $columns = Schema::getColumnListing('attendances');
            if (in_array('check_in_time', $columns)) {
                $table->dropColumn('check_in_time');
            }
            if (in_array('check_out_time', $columns)) {
                $table->dropColumn('check_out_time');
            }
            if (in_array('date', $columns)) {
                $table->dropColumn('date');
            }

            // Agregar nuevas columnas si no existen
            if (!in_array('check_in', $columns)) {
                $table->timestamp('check_in')->nullable();
            }
            if (!in_array('check_out', $columns)) {
                $table->timestamp('check_out')->nullable();
            }
            if (!in_array('break_start', $columns)) {
                $table->timestamp('break_start')->nullable();
            }
            if (!in_array('break_end', $columns)) {
                $table->timestamp('break_end')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Restaurar columnas antiguas
            $table->date('date')->nullable();
            $table->timestamp('check_in_time')->nullable();
            $table->timestamp('check_out_time')->nullable();

            // Restaurar restricción única
            $table->unique(['user_id', 'date']);

            // Eliminar columnas nuevas
            $table->dropColumn(['check_in', 'check_out', 'break_start', 'break_end']);
        });
    }
}
