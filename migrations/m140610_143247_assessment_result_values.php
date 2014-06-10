<?php

class m140610_143247_assessment_result_values extends CDbMigration
{
	public function up()
	{
		$this->insert('ophinvisualfields_assessment',array('id'=>1,'active'=>1,'name'=>'Wonderful'));
		$this->insert('ophinvisualfields_assessment',array('id'=>2,'active'=>1,'name'=>'Couldn\'t be better'));
		$this->insert('ophinvisualfields_assessment',array('id'=>3,'active'=>1,'name'=>'Just great'));
	}

	public function down()
	{
		$this->delete('ophinvisualfields_assessment');
	}
}
