<?php

class m140416_122557_remove_patient_fks extends CDbMigration {

    // Use safeUp/safeDown to do migration with transaction
    public function safeUp() {
        $this->dropForeignKey('ophinvisualfields_field_measurement_pid_fk', 'ophinvisualfields_field_measurement');
        $this->dropColumn('ophinvisualfields_field_measurement', 'patient_id');
    }

    public function safeDown() {
        return false;
    }

}