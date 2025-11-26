<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('borrower_id')->constrained('borrowers')->onDelete('cascade');
            $table->decimal('principal', 15, 2);
            $table->decimal('total_due', 15, 2)->nullable(); // Added
            $table->decimal('balance', 15, 2);
            $table->decimal('interest_rate', 5, 2)->default(0);
            $table->date('issued_at')->nullable(); // Added
            $table->date('first_due_date');
            $table->enum('frequency', ['daily', 'weekly', 'twice-monthly', 'monthly'])->default('monthly'); // Added
            $table->integer('term')->nullable(); // Added, e.g., number of periods
            $table->enum('status', ['active', 'paid', 'defaulted'])->default('active');
            $table->text('notes')->nullable(); // Added
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
