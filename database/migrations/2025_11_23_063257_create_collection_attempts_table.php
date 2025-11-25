<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCollectionAttemptsTable extends Migration
{
    public function up()
    {
        Schema::create('collection_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loan_id')->constrained('loans')->cascadeOnDelete();
            $table->foreignId('borrower_id')->constrained('borrowers')->cascadeOnDelete();
            $table->foreignId('collector_id')->nullable()->constrained('collectors')->nullOnDelete();
            $table->dateTime('attempted_at');
            $table->string('outcome')->default('attempted'); // attempted, promised, paid, failed, escalated
            $table->decimal('collected_amount', 15, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('collection_attempts');
    }
}
