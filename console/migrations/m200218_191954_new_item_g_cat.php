<?php

use yii\db\Migration;

/**
 * Class m200218_191954_new_item_g_cat
 */
class m200218_191954_new_item_g_cat extends Migration
{
    public function up()
    {
        $data = [
            '37' => 'Фразеологізми',
            '38' => 'Іншомовні слова',
            '39' => 'Займенник',
            '40' => 'Стиль тексту',
            '41' => 'Просте речення',
            '42' => 'Частка',
            '43' => 'Чергування',
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
        $this->delete('g_cat', ['in', 'id', [37, 38, 39, 40, 41, 42, 43]]);
    }
}
