<?php

use App\Enums\FollowupStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lead_followups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('lead_id')
                ->constrained('leads')
                ->cascadeOnDelete();

            $table->dateTime('followup_date');

            $table->text('notes');

            $table->enum(
                'status',
                array_column(FollowupStatus::cases(), 'value')
            )->default(FollowupStatus::PENDING->value);

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->timestamps();

            $table->index('lead_id');
            $table->index('followup_date');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lead_followups');
    }
};
