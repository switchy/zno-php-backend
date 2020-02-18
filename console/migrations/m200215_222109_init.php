<?php

use yii\db\Migration;

/**
 * Class m200215_222109_init
 */
class m200215_222109_init extends Migration
{
    public function Up()
    {
        $this->createTable('g_cat', [
          'id' => 'INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT',
          'name' => 'VARCHAR(255) NULL'
        ]);

        $this->createTable('task_on_cats', [
            'id_task' => 'VARCHAR(20) NOT NULL',
            'id_cat'  => 'INTEGER NOT NULL',
            'tm'      => 'INTEGER'
        ]);
        $this->createIndex('uidx_task_on_cats', 'task_on_cats', ['id_task', 'id_cat'], true);

        $data = [
            '1' => 'Наголос',
            '2' => 'Ускладнене речення',
            '3' => 'Складне речення',
            '4' => 'Синоніми',
            '5' => 'антоніми',
            '6' => 'Спільнокореневі слова',
            '7' => 'Узгодження в  словосполученні',
            '8' => 'Утворення слів',
            '9' => 'Розділові знаки',
            '10' => 'Не зі  словами',
            '11' => 'Відмінки',
            '12' => 'Фонетика',
            '13' => 'Будова слова',
            '14' => 'Лексика',
            '15' => 'Апостроф',
            '16' => 'Ненаголош И, е',
            '17' => 'Спрощення',
            '18' => 'Дефіс',
            '19' => 'Редагування речення',
            '20' => 'Милозвучність мови',
            '21' => 'Розуміння текстів',
            '22' => 'Пом’якшення',
            '23' => 'Омоніми',
            '24' => 'Пряма мова',
            '25' => 'Дієприкметник',
            '26' => 'Числівники',
            '27' => 'Прикметники',
            '28' => 'Прийменник',
            '29' => 'Дієслово',
            '30' => 'Прислівник',
            '31' => 'Іменник',
            '32' => 'Велика буква',
            '33' => 'І, и у прислівниках',
            '34' => 'Подвоєння подовження',
            '35' => 'Члени речення',
            '36' => 'Написання Й',
        ];

        foreach($data as $id => $name) {
            $this->insert('g_cat', [
                'id'   => $id,
                'name' => $name
            ]);
        }
    }

    public function Down()
    {
        $this->dropTable('g_cat');
    }
}