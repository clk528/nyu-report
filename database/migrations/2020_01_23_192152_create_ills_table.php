<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateIllsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ills', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('netId', 20)->index();

            $table->string('gowuhang', 10)->default(false);

            $table->string('touch', 10)->default(false)->comment('是否有过接触');

            $table->string('ill', 10)->comment('是否不舒服');

            $table->string('fever', 10)->nullable()->comment('发热');
            $table->string('cough', 10)->nullable()->comment('咳嗽');

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
        Schema::dropIfExists('ills');
    }
}
