<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAmtTimesMaxAmtToHbsBenheadTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hbs_benhead', function (Blueprint $table) {
            $table->integer('amt_times')->nullable();
            $table->integer('max_amt')->nullable();
            $table->integer('max_days')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hbs_benhead', function (Blueprint $table) {
            $table->dropColumn('amt_times');
            $table->dropColumn('max_amt');
            $table->dropColumn('max_days');
        });
    }
}
