<?php
namespace app\commands;

use yii\console\Controller;

/**
 *  This command import xml file into database
 *
 * @author Wolf
 * @since 1.0
 */
class Importer extends Controller
{
    
    public function actionIndex()
    {
        $textxml = simplexml_load_file(__DIR__.'/test.xml');
        $now = date_create();
        
        /*
         * (TODO) Вынести работу с файлом в функцию. При начале прохождения по записям создать команду.
         */

        $command = Yii::$app->db->createCommand()->insert($table_name,
            [
                
            ]);

        // обрабатываем каждую запись.
        foreach ($textxml->result->ROWSET->ROW as $row) {
    		$time = date_create($row->ACTIVE_TO);
            $interval = $now->diff($time);

            // Если интервал положителен, то сущность актуальная и заносится в бд 
            if ($interval->invert == 0) {
                $this->print_struct($row);
            }
        }
    }

    private function print_struct($row)
    {
    	foreach ($row as $key => $value) {
        	if (!empty($value)) echo strtolower($key)." -> ".$value."\n";
        }
        echo "\n";
    }
}