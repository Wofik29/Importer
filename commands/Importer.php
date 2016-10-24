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
    private $current_time;
    private $count;
    private $compare;

    public function actionIndex()
    {
        $this->current_time = date_create();
        $this->compare = array();

        //$textxml = simplexml_load_file(__DIR__.'/test.xml');
        $textxml = simplexml_load_file(__DIR__.'/plans.xml');
        $command = \Yii::$app->db->createCommand('
            INSERT INTO `plans` (`plan_id`, `plan_name`, `plan_group_id`, `active_from`, `active_to`, `company_id`) 
            VALUES (:plan_id, :plan_name, :plan_group_id, :active_from, :active_to, :company_id)');
        $this->parseXML($textxml, $command);
        
        $compare =  \Yii::$app->db->createCommand('Select plan_id from plans')->queryColumn();

        //$textxml = simplexml_load_file(__DIR__.'/test2.xml');
        $textxml = simplexml_load_file(__DIR__.'/plan_properties.xml');
        $command = \Yii::$app->db->createCommand('
            INSERT INTO `plans_properties` (`property_id`, `property_type_id`, `active_from`, `active_to`, `plan_id`, `prop_value`) 
            VALUES (:property_id, :property_type_id, :active_from, :active_to, :plan_id, :prop_value)');
        $this->parseXML($textxml, $command, $compare);

    }

    // Пробегает по массиву и добавляет актуальные строки
    private function parseXML($textxml, $command, $compare = []) 
    {
        $this->count = 0;
        foreach ($textxml->result->ROWSET->ROW as $row) {
            // Проверяем, есть ли данный plan_id  в родительской таблице
            if (!empty($compare) && !in_array($row->PLAN_ID, $compare)) continue;
            
            $time = date_create($row->ACTIVE_TO);
            $interval = $this->current_time->diff($time);

            if ($interval->invert == 0) {
                $this->inputRow($command, $row);
            }
        }
        echo "Количество добавленных строк ".$this->count.".\n";
    }

    // Связывает значения и параметры запроса и добавляет строку в бд
    private function inputRow($command, $row)
    {
        foreach ($row as $key => $value) {
            $key = strtolower($key);

            // Приводим к строковому виду xmlElement и дату к нужному виду.
            if (empty($value)) {
                $value = null;
            } else if ($key == 'active_to' || $key == 'active_from') {
                $value = date_create($value)->format('Y-m-d');
            } else {
                $value = $value->__toString();
            }
                
            $command->bindValue(':'.$key, $value);
        }
        $result_number = $command->execute();
        $this->count += $result_number;
    }
}