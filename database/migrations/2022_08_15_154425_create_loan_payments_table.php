<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loan_id');
            $table->date('due_date');
            $table->date('pay_date')->nullable();
            $table->decimal('due_amount',14,2);
            $table->enum('pay_status',array('PENDING','PAID'))->default('PENDING');
            $table->index('loan_id');
            $table->foreign('loan_id')->references('id')->on('loans')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loan_payments');
    }
};
