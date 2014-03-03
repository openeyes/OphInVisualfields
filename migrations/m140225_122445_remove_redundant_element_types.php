<?php

class m140225_122445_remove_redundant_element_types extends CDbMigration {

	// Use safeUp/safeDown to do migration with transaction
	public function safeUp() {
		$event_type = $this->dbConnection->createCommand()->select('id')->from('event_type')->where('class_name=:class_name', array(':class_name' => 'OphInVisualfields'))->queryRow();
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and name=:name', array(':eventTypeId' => $event_type['id'], ':name' => 'Details'))->queryRow();
		$this->dbConnection->createCommand()->delete('element_type', 'id=?', array($element_type['id']));
		$this->dbConnection->createCommand()->renameTable('et_ophinvisualfields_test_type', 'et_ophinvisualfields_comments');
		$this->dbConnection->createCommand()->renameTable('et_ophinvisualfields_test_type_version', 'et_ophinvisualfields_comments_version');
		
		
		$this->dbConnection->createCommand()->dropForeignKey('et_ophinvisualfields_test_type_test_type_id_fk', 'et_ophinvisualfields_comments');
		$this->dbConnection->createCommand()->dropForeignKey('acv_et_ophinvisualfields_test_type_test_type_id_fk', 'et_ophinvisualfields_comments_version');
		$this->dbConnection->createCommand()->dropColumn('et_ophinvisualfields_comments', 'test_type_id');
		
		$this->dbConnection->createCommand()->dropColumn('et_ophinvisualfields_comments_version', 'test_type_id');
		
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId' => $event_type['id'], ':class_name' => 'Element_OphInVisualfields_Testtype'))->queryRow();
		$this->dbConnection->createCommand()->update('element_type', array('name' => 'Comments', 'class_name' => 'Element_OphInVisualfields_Comments'), 'id=' . $element_type['id']);

		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId' => $event_type['id'], ':class_name' => 'Element_OphInVisualfields_Image'))->queryRow();
		$this->dbConnection->createCommand()->update('element_type', array('display_order' => '1'), 'id=' . $element_type['id']);
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId' => $event_type['id'], ':class_name' => 'Element_OphInVisualfields_Condition'))->queryRow();
		$this->dbConnection->createCommand()->update('element_type', array('display_order' => '10'), 'id=' . $element_type['id']);
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId' => $event_type['id'], ':class_name' => 'Element_OphInVisualfields_Comments'))->queryRow();
		$this->dbConnection->createCommand()->update('element_type', array('display_order' => '20'), 'id=' . $element_type['id']);
		$element_type = $this->dbConnection->createCommand()->select('id')->from('element_type')->where('event_type_id=:eventTypeId and class_name=:class_name', array(':eventTypeId' => $event_type['id'], ':class_name' => 'Element_OphInVisualfields_Result'))->queryRow();
		$this->dbConnection->createCommand()->update('element_type', array('display_order' => '30'), 'id=' . $element_type['id']);
		
	}

	public function safeDown() {
		return false;
	}

}