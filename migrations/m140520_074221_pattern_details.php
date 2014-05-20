<?php

class m140520_074221_pattern_details extends CDbMigration {

    public function safeUp() {

        // change pattern_ocr to something else
        $this->createTable('et_ophinvisualfields_pattern_ocr', array(
            'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
            // give appropriate names to values:
            'val1' => 'int(10) unsigned NOT NULL', // Test Type
            'val2' => 'int(10) unsigned NOT NULL', // Test Type
            // ...
            'val32' => 'int(10) unsigned NOT NULL', // Test Type
            // now core table fields:
            'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
            'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
            'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
            'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
            'PRIMARY KEY (`id`)',
            'KEY `et_ophinvisualfields_pattern_ocr_lmui_fk` (`last_modified_user_id`)',
            'KEY `et_ophinvisualfields_pattern_ocr_cui_fk` (`created_user_id`)',
            'CONSTRAINT `et_ophinvisualfields_pattern_ocr_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
            'CONSTRAINT `et_ophinvisualfields_pattern_ocr_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
                ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
    }

    public function safeDown() {
        $this->dropTable('et_ophinvisualfields_pattern_ocr');
    }

}