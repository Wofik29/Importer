<?php

use yii\db\Migration;

/**
 * Handles the creation for table `plans_properties`.
 */
class m161020_075057_create_plans_properties_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('plans_properties', [
            'property_id' => $this->integer()->notNull(),
            'property_type_id' => $this->integer(11),
            'active_from'=>$this->date(),
            'active_to'=>$this->date(),
            'plan_id' => $this->integer(11),
            'prop_value' => $this->string(80),
            'PRIMARY KEY(property_id)'
        ]);

        $this->addForeignKey('plan_id', 'plans_properties', 'plan_id', 'plans', 'plan_id');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('plans_properties');
    }
}
