<?php
/**
 * Created by PhpStorm.
 * User: wyvern
 * Date: 16/7/1
 * Time: 上午10:31
 */

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;

return [
    'up' => function (Builder $schema) {
        $schema->table('users', function (Blueprint $table) {
            $table->string('create_from');
            $table->integer('ask_count')->unsigned()->default(0);
            $table->integer('answer_count')->unsigned()->default(0);
        });

        // Store slugs for existing discussions
        $schema->getConnection()->table('users')->chunk(100, function ($users) use ($schema) {
            foreach ($users as $user) {
                $schema->getConnection()->table('users')->where('id', $user->id)->update([
                    'create_from' => '来自社区账号系统'
                ]);
            }
        });
    },

    'down' => function (Builder $schema) {
        $schema->table('users', function (Blueprint $table) {
            $table->dropColumn('create_from');
            $table->dropColumn('ask_count');
            $table->dropColumn('answer_count');
        });
    }
];