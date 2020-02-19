<?php

use yii\db\Migration;

/**
 * Class m200219_220446_new_item_g_cat
 */
class m200219_220446_new_item_g_cat extends Migration
{
    public function up()
    {
        $data = [
            '57' => 'Напрям літератури',
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
        $this->delete('g_cat', ['in', 'id', [57]]);
    }
}
