<?php

class m140730_145311_unique_field_ids extends OEMigration
{
    public function safeUp()
    {
        $this->createIndex('et_ophinvisualfields_image_left_field_id_unique', 'et_ophinvisualfields_image', 'left_field_id', true);
        $this->createIndex('et_ophinvisualfields_image_right_field_id_unique', 'et_ophinvisualfields_image', 'right_field_id', true);
    }

    public function safeDown()
    {
        echo "m140730_145311_unique_field_ids does not support migration down.\n";
        return false;
    }
}
