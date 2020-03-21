<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use \Illuminate\Support\Facades\DB;

class CreateDataPermissionTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('permission.table_names');
        $columnNames = config('permission.column_names');

        Schema::create('sy_data_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('display_name');
            $table->integer('pid');
            $table->timestamps();
        });
        DB::statement("ALTER TABLE `sy_data_permissions` comment '数据权限表'");//表注释一定加上前缀

        Schema::create('sy_model_has_data_permissions', function (Blueprint $table) use ($tableNames, $columnNames) {
            $table->unsignedInteger('data_permission_id');
            $table->unsignedBigInteger($columnNames['model_morph_key']);
            $table->index([$columnNames['model_morph_key'], 'data_permission_id', ]);

            $table->foreign('data_permission_id')
                ->references('id')
                ->on('sy_data_permissions')
                ->onDelete('cascade');

            $table->primary(['data_permission_id', $columnNames['model_morph_key']],
                'data_permission_id_model_id_primary');
        });
        DB::statement("ALTER TABLE `sy_model_has_data_permissions` comment '用户数据权限关联表'");//表注释一定加上前缀

        Schema::create('sy_role_has_data_permissions',  function (Blueprint $table) use ($tableNames) {
            $table->unsignedInteger('data_permission_id');
            $table->unsignedInteger('role_id');

            $table->foreign('data_permission_id')
                ->references('id')
                ->on($tableNames['permissions'])
                ->onDelete('cascade');

            $table->foreign('role_id')
                ->references('id')
                ->on($tableNames['roles'])
                ->onDelete('cascade');

            $table->primary(['data_permission_id', 'role_id']);
        });
        DB::statement("ALTER TABLE `sy_role_has_data_permissions` comment '角色组数据权限关联表'");//表注释一定加上前缀
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sy_data_permissions');
        Schema::drop('sy_model_has_data_permissions');
        Schema::drop('sy_role_has_data_permissions');
    }
}
