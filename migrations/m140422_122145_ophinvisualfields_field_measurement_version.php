<?php

class m140422_122145_ophinvisualfields_field_measurement_version extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->dropForeignKey('acv_ophinvisualfields_field_measurement_pid_fk', 'ophinvisualfields_field_measurement_version');
        $this->dropColumn('ophinvisualfields_field_measurement_version', 'patient_id');
    }

    public function safeDown() {
        
    }

}