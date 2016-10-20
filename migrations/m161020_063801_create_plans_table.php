<?php

use yii\db\Migration;

/**
 * Handles the creation for table `plans`.
 */
class m161020_063801_create_plans_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plans', [
            'plan_id' => $this->integer(11)->notNull(),
            'plan_name'=> $this->string(120),
            'plan_group_id'=>$this->integer(11),
            'active_from'=>$this->date(),
            'active_to'=>$this->date(),
            'company_id'=>$this->integer(11),
            'PRIMARY KEY(plan_id)'
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('plans');
    }
}
