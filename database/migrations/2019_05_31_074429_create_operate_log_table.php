<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateOperateLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sy_operate_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('action', 255)->comment('操作')->default('');
            $table->string('data', 500)->comment('参数')->default('');
            $table->string('username', 255)->comment('操作人账号')->default('');
            $table->string('operator', 255)->comment('操作人描述')->default('');
            $table->string('dealResult', 255)->comment('处理结果')->default('');
            $table->integer('uid')->comment('操作人ID');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `sy_operate_log` comment'操作日志'");//表注释一定加上前缀
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_operate_log');
    }
}
