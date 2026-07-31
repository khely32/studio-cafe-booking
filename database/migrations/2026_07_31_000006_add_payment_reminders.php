<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->timestamp('payment_reminder_sent_at')->nullable()->after('internal_notes');
        });

        Setting::updateOrCreate(['key' => 'payment_reminder_days'], ['value' => '3']);
        Setting::updateOrCreate(['key' => 'payment_reminder_enabled'], ['value' => '1']);
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('payment_reminder_sent_at');
        });
    }
};
