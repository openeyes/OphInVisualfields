<?php

class m130509_081459_event_type_OphInVisualfields extends OEMigration {

  public function up() {

    // --- EVENT TYPE ENTRIES ---

    foreach(array('ability', 'assessment', 'pattern', 'strategy', 'testtype') as $table) {
      
    $this->createTable('ophinvisualfields_' . $table, array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'name' => 'varchar(100) DEFAULT NULL',
        'PRIMARY KEY (`id`)',
        ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
    }

    $migrations_path = dirname(__FILE__);
    $this->initialiseData($migrations_path);

    // create an event_type entry for this event type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name' => 'OphInVisualfields'))->queryRow()) {
      $group = $this->dbConnection->createCommand()->select('id')->from('event_group')->where('name=:name', array(':name' => 'Investigation events'))->queryRow();
      $this->insert('event_type', array('class_name' => 'OphInVisualfields', 'name' => 'Visual Fields', 'event_group_id' => $group['id']));
    }
    // select the event_type id for this event type name
    $event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name' => 'OphInVisualfields'))->queryRow();

    // --- ELEMENT TYPE ENTRIES ---
    // create an element_type entry for this element type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name' => 'TestType', ':eventTypeId' => $event_type['id']))->queryRow()) {
      $this->insert('element_type', array('name' => 'TestType', 'class_name' => 'Element_OphInVisualfields_Testtype', 'event_type_id' => $event_type['id'], 'display_order' => 1));
    }
    // select the element_type_id for this element type name
    $element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'TestType'))->queryRow();
    // create an element_type entry for this element type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name' => 'Condition', ':eventTypeId' => $event_type['id']))->queryRow()) {
      $this->insert('element_type', array('name' => 'Condition', 'class_name' => 'Element_OphInVisualfields_Condition', 'event_type_id' => $event_type['id'], 'display_order' => 1));
    }
    // select the element_type_id for this element type name
    $element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'Condition'))->queryRow();
    // create an element_type entry for this element type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name' => 'Details', ':eventTypeId' => $event_type['id']))->queryRow()) {
      $this->insert('element_type', array('name' => 'Details', 'class_name' => 'Element_OphInVisualfields_Details', 'event_type_id' => $event_type['id'], 'display_order' => 1));
    }
    // select the element_type_id for this element type name
    $element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'Details'))->queryRow();
    // create an element_type entry for this element type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name' => 'Image', ':eventTypeId' => $event_type['id']))->queryRow()) {
      $this->insert('element_type', array('name' => 'Image', 'class_name' => 'Element_OphInVisualfields_Image', 'event_type_id' => $event_type['id'], 'display_order' => 1));
    }
    // select the element_type_id for this element type name
    $element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'Image'))->queryRow();
    // create an element_type entry for this element type name if one doesn't already exist
    if (!$this->dbConnection->createCommand()->select('id')->from('element_type')->where('name=:name and event_type_id=:eventTypeId', array(':name' => 'Result', ':eventTypeId' => $event_type['id']))->queryRow()) {
      $this->insert('element_type', array('name' => 'Result', 'class_name' => 'Element_OphInVisualfields_Result', 'event_type_id' => $event_type['id'], 'display_order' => 1));
    }
    // select the element_type_id for this element type name
    $element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'Result'))->queryRow();



    // create the table for this element type: et_modulename_elementtypename
    $this->createTable('et_ophinvisualfields_test_type', array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'event_id' => 'int(10) unsigned NOT NULL',
        'test_type_id' => 'int(10) unsigned NOT NULL', // Test Type
        'comments' => 'text DEFAULT \'\'', // Comments
        'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'PRIMARY KEY (`id`)',
        'KEY `et_ophinvisualfields_test_type_lmui_fk` (`last_modified_user_id`)',
        'KEY `et_ophinvisualfields_test_type_cui_fk` (`created_user_id`)',
        'KEY `et_ophinvisualfields_test_type_ev_fk` (`event_id`)',
        'KEY `et_ophinvisualfields_test_type_test_type_id_fk` (`test_type_id`)',
        'CONSTRAINT `et_ophinvisualfields_test_type_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_test_type_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_test_type_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_test_type_test_type_id_fk` FOREIGN KEY (`test_type_id`) REFERENCES `ophinvisualfields_testtype` (`id`)',
            ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



    // create the table for this element type: et_modulename_elementtypename
    $this->createTable('et_ophinvisualfields_condition', array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'event_id' => 'int(10) unsigned NOT NULL',
        'ability_id' => 'int(10) unsigned NOT NULL DEFAULT 1', // Ability
        'other' => 'text DEFAULT \'\'', // Other
        'glasses' => 'tinyint(1) unsigned NOT NULL', // Glasses
        'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'PRIMARY KEY (`id`)',
        'KEY `et_ophinvisualfields_condition_lmui_fk` (`last_modified_user_id`)',
        'KEY `et_ophinvisualfields_condition_cui_fk` (`created_user_id`)',
        'KEY `et_ophinvisualfields_condition_ev_fk` (`event_id`)',
        'KEY `et_ophinvisualfields_condition_ability_id_fk` (`ability_id`)',
        'CONSTRAINT `et_ophinvisualfields_condition_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_condition_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_condition_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_condition_ability_id_fk` FOREIGN KEY (`ability_id`) REFERENCES `ophinvisualfields_ability` (`id`)',
            ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



    // create the table for this element type: et_modulename_elementtypename
    $this->createTable('et_ophinvisualfields_details', array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'event_id' => 'int(10) unsigned NOT NULL',
        'pattern_id' => 'int(10) unsigned NOT NULL DEFAULT 1', // Pattern
        'strategy_id' => 'int(10) unsigned NOT NULL DEFAULT 1', // Strategy
        'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'PRIMARY KEY (`id`)',
        'KEY `et_ophinvisualfields_details_lmui_fk` (`last_modified_user_id`)',
        'KEY `et_ophinvisualfields_details_cui_fk` (`created_user_id`)',
        'KEY `et_ophinvisualfields_details_ev_fk` (`event_id`)',
        'KEY `et_ophinvisualfields_details_pattern_id_fk` (`pattern_id`)',
        'KEY `et_ophinvisualfields_details_strategy_id_fk` (`strategy_id`)',
        'CONSTRAINT `et_ophinvisualfields_details_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_details_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_details_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_details_pattern_id_fk` FOREIGN KEY (`pattern_id`) REFERENCES `ophinvisualfields_pattern` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_details_strategy_id_fk` FOREIGN KEY (`strategy_id`) REFERENCES `ophinvisualfields_strategy` (`id`)',
            ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



    // create the table for this element type: et_modulename_elementtypename
    $this->createTable('et_ophinvisualfields_image', array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'event_id' => 'int(10) unsigned NOT NULL',
        'left_image' => 'int(10) unsigned NOT NULL', // Left image
        'right_image' => 'int(10) unsigned NOT NULL', // Right image
        'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'PRIMARY KEY (`id`)',
        'KEY `et_ophinvisualfields_image_lmui_fk` (`last_modified_user_id`)',
        'KEY `et_ophinvisualfields_image_cui_fk` (`created_user_id`)',
        'KEY `et_ophinvisualfields_image_ev_fk` (`event_id`)',
        'CONSTRAINT `et_ophinvisualfields_image_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_image_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_image_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
            ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');



    // create the table for this element type: et_modulename_elementtypename
    $this->createTable('et_ophinvisualfields_result', array(
        'id' => 'int(10) unsigned NOT NULL AUTO_INCREMENT',
        'event_id' => 'int(10) unsigned NOT NULL',
        'reliability' => 'tinyint(1) unsigned NOT NULL', // Reliability
        'assessment' => 'tinyint(1) unsigned NOT NULL DEFAULT 0', // Assessment
        'assessment_id' => 'int(10) unsigned NOT NULL DEFAULT 1', // Assessment
        'other' => 'text DEFAULT \'\'', // Other
        'last_modified_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'last_modified_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'created_user_id' => 'int(10) unsigned NOT NULL DEFAULT 1',
        'created_date' => 'datetime NOT NULL DEFAULT \'1901-01-01 00:00:00\'',
        'PRIMARY KEY (`id`)',
        'KEY `et_ophinvisualfields_result_lmui_fk` (`last_modified_user_id`)',
        'KEY `et_ophinvisualfields_result_cui_fk` (`created_user_id`)',
        'KEY `et_ophinvisualfields_result_ev_fk` (`event_id`)',
        'KEY `et_ophinvisualfields_result_assessment_id_fk` (`assessment_id`)',
        'CONSTRAINT `et_ophinvisualfields_result_lmui_fk` FOREIGN KEY (`last_modified_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_result_cui_fk` FOREIGN KEY (`created_user_id`) REFERENCES `user` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_result_ev_fk` FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)',
        'CONSTRAINT `et_ophinvisualfields_result_assessment_id_fk` FOREIGN KEY (`assessment_id`) REFERENCES `ophinvisualfields_assessment` (`id`)',
            ), 'ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_bin');
  }

  public function down() {
    // --- drop any element related tables ---
    // --- drop element tables ---
    $this->dropTable('et_ophinvisualfields_test_type');



    $this->dropTable('et_ophinvisualfields_condition');



    $this->dropTable('et_ophinvisualfields_details');



    $this->dropTable('et_ophinvisualfields_image');



    $this->dropTable('et_ophinvisualfields_result');


    $this->dropTable('ophinvisualfields_ability');
    $this->dropTable('ophinvisualfields_assessment');
    $this->dropTable('ophinvisualfields_pattern');
    $this->dropTable('ophinvisualfields_strategy');
    $this->dropTable('ophinvisualfields_testtype');


    // --- delete event entries ---
    $event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name' => 'OphInVisualfields'))->queryRow();

    foreach ($this->dbConnection->createCommand()->select('id')->from('event')->where('event_type_id=:event_type_id', array(':event_type_id' => $event_type['id']))->queryAll() as $row) {
      $this->delete('audit', 'event_id=' . $row['id']);
      $this->delete('event', 'id=' . $row['id']);
    }

    // --- delete entries from element_type ---
    $this->delete('element_type', 'event_type_id=' . $event_type['id']);

    // --- delete entries from event_type ---
    $this->delete('event_type', 'id=' . $event_type['id']);

    // echo "m000000_000001_event_type_OphInVisualfields does not support migration down.\n";
    // return false;
    echo "If you are removing this module you may also need to remove references to it in your configuration files\n";
    return true;
  }

}

?>
