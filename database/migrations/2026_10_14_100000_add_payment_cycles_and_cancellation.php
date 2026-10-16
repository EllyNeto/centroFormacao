<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migração aditiva: Ciclos de frequência e campos de anulação de pagamentos.
 *
 * Altera a tabela 'payments':
 *   - cycle            : número do ciclo (1 = 1.ª frequência, 2 = 1.ª repetição, …)
 *   - previous_room_id : turma concluída antes da repetição (histórico)
 *   - cancellation_reason : motivo da anulação (obrigatório ao anular Confirmados)
 *   - canceled_at      : data e hora da anulação
 *
 * Altera a tabela 'enrollments':
 *   - code  : código único gerado automaticamente (Ex: INS-2026-0001)
 *   - cycle : ciclo de frequência atual da inscrição (1 = 1.ª frequência)
 */
class AddPaymentCyclesAndCancellation extends Migration
{
    public function up()
    {
        // ─── payments ───────────────────────────────────────────────────────
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedInteger('cycle')->default(1)->after('enrollment_id');

            $table->foreignId('previous_room_id')
                ->nullable()
                ->after('cycle')
                ->constrained('rooms')
                ->onDelete('set null');

            $table->text('cancellation_reason')->nullable()->after('status');
            $table->dateTime('canceled_at')->nullable()->after('cancellation_reason');
        });

        // ─── enrollments ────────────────────────────────────────────────────
        Schema::table('enrollments', function (Blueprint $table) {
            // Código único gerado automaticamente no momento da criação
            $table->string('code', 20)->nullable()->unique()->after('id');

            // Ciclo atual da inscrição
            $table->unsignedInteger('cycle')->default(1)->after('status');
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['previous_room_id']);
            $table->dropColumn(['cycle', 'previous_room_id', 'cancellation_reason', 'canceled_at']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['code', 'cycle']);
        });
    }
}
