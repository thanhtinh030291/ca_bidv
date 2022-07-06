<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTreatmentGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('treatment_group', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('treatment_group_name',500)->nullable();
            $table->string('type_max',10)->default('days');
            $table->integer('value_max')->nullable();
            $table->string('ben_head_code',25)->nullable();
            $table->integer('created_user');
            $table->integer('updated_user');
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
        Schema::dropIfExists('treatment_group');
    }
}
