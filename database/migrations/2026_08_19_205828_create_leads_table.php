<?php

use App\Enums\LeadSource;
use App\Enums\LeadStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();

            $table->string('lead_code', 50)->unique();

            $table->string('customer_name');
            $table->string('email');
            $table->string('phone', 30);

            $table->enum(
                'source',
                array_column(LeadSource::cases(), 'value')
            );

            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->enum(
                'status',
                array_column(LeadStatus::cases(), 'value')
            )->default(LeadStatus::NEW->value);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->index('email');
            $table->index('status');
            $table->index('source');
            $table->index('assigned_to');
            $table->index(['email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
