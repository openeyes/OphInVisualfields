<?php

class m130514_100729_fix_bad_table_name extends CDbMigration {

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $this->update('element_type', array('name' => 'Condition', 'class_name' => 'Element_OphInVisualfields_Condition'), 'class_name=\'Element_OphInVisualfields_Condtition\'');
    $this->renameTable('et_ophinvisualfields_condtition', 'et_ophinvisualfields_condition');
  }

  public function safeDown() {
    $this->update('element_type', array('name' => 'Condtition', 'class_name' => 'Element_OphInVisualfields_Condtition'), 'class_name=\'Element_OphInVisualfields_Condition\'');
    $this->renameTable('et_ophinvisualfields_condition', 'et_ophinvisualfields_condtition');
  }

}