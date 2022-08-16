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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->decimal('amount',14,2)->default(0);
            $table->integer('term',)->default(3);
            $table->enum('status',array('REJECTED','PENDING','APPROVED','PAID'))->default('PENDING');
            $table->index('user_id');
            $table->integer('approved_by')->default('1');
            $table->date('approved_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::table('loans', function (Blueprint $table) {
            $table->dropForeign('lists_user_id_foreign');
            $table->dropIndex('lists_user_id_index');
            $table->dropColumn('user_id');
        });

        Schema::dropIfExists('loans');
    }
};
