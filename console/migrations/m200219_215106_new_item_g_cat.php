<?php

use yii\db\Migration;

/**
 * Class m200219_215106_new_item_g_cat
 */
class m200219_215106_new_item_g_cat extends Migration
{
    public function up()
    {
        $data = [
            '46' => 'Художні засоби',
            '47' => 'Народні пісні',
            '48' => 'Літературні жанри',
            '49' => 'Письменники',
            '50' => 'Стиль твору',
            '51' => 'Віршовий розмір',
            '52' => 'Літературні угрупування',
            '53' => 'Персонажі',
            '54' => 'Присвяти',
            '55' => 'Компоненти сюжету',
            '56' => 'Вірші',

        ];

        foreach($data as $id => $name) {
            $this->insert('g_cat', [
                'id'   => $id,
                'name' => $name
            ]);
        }
    }

    public function down()
    {
        $this->delete('g_cat', ['in', 'id', [46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56]]);
    }

}
