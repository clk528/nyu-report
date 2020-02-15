<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVirusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virus', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('netId', 20)->index();

            $table->string('coronavirus', 5)->comment('是否感染新型冠状病毒，1：确诊2：疑似3：无');

            $table->string('touch_coronavirus', 5)->comment('是否有过接触冠状病毒患者，1：是的2：无');

            $table->string('go_hubei', 5)->comment('2020年1月10日之后您是否去过湖北省，1：是的2：无');

            $table->string('location', 5)->comment('目前在哪儿：1：上海2：湖北省3：国内其他城市4：国外');

            $table->string('back_school', 5)->comment('是否返校是途中：1：是的2无');

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
        Schema::dropIfExists('virus');
    }
}
