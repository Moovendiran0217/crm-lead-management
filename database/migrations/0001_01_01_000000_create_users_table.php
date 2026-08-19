<?php

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 30)->nullable();

            $table->enum(
                'role',
                array_column(UserRole::cases(), 'value')
            )->default(UserRole::SALES->value);

            $table->enum(
                'status',
                array_column(UserStatus::cases(), 'value')
            )->default(UserStatus::ACTIVE->value);

            $table->string('password');

            $table->rememberToken();
            $table->timestamps();

            $table->index(['role', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
