<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2012
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2012, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */

class Element_OphInVisualfields_Condition extends BaseEventTypeElement
{
	public function tableName()
	{
		return 'et_ophinvisualfields_condition';
	}

	public function rules()
	{
		return array(
			array('other, glasses', 'safe'),
			array('glasses', 'required'),
		);
	}

	protected function afterSave()
	{
		if (!empty($_POST['MultiSelect_ability'])) {

			$existing_ids = array();

			foreach (Element_OphInVisualfields_Condition_Ability_Assignment::model()->findAll('element_id = :elementId', array(':elementId' => $this->id)) as $item) {
				$existing_ids[] = $item->ophinvisualfields_condition_ability_id;
			}

			foreach ($_POST['MultiSelect_ability'] as $id) {
				if (!in_array($id,$existing_ids)) {
					$item = new Element_OphInVisualfields_Condition_Ability_Assignment;
					$item->element_id = $this->id;
					$item->ophinvisualfields_condition_ability_id = $id;

					if (!$item->save()) {
						throw new Exception('Unable to save MultiSelect item: '.print_r($item->getErrors(),true));
					}
				}
			}

			foreach ($existing_ids as $id) {
				if (!in_array($id,$_POST['MultiSelect_ability'])) {
					$item = Element_OphInVisualfields_Condition_Ability_Assignment::model()->find('element_id = :elementId and ophinvisualfields_condition_ability_id = :lookupfieldId',array(':elementId' => $this->id, ':lookupfieldId' => $id));
					if (!$item->delete()) {
						throw new Exception('Unable to delete MultiSelect item: '.print_r($item->getErrors(),true));
					}
				}
			}
		}

		return parent::afterSave();
	}


	public function getophinvisualfields_condition_ability_defaults() {
		$ids = array();
		foreach (OphInVisualfields_Condition_Ability::model()->findAll('`default` = ?',array(1)) as $item) {
			$ids[] = $item->id;
		}
		return $ids;
	}

	public function relations()
	{
		return array(
			'event' => array(self::BELONGS_TO, 'Event', 'event_id'),
			'abilitys' => array(self::HAS_MANY, 'Element_OphInVisualfields_Condition_Ability_Assignment', 'element_id'),
		);
	}

	public function attributeLabels()
	{
		return array(
			'ability_id' => 'Ability',
		);
	}

	public function afterValidate()
	{
		if ($this->hasMultiSelectValue('abilitys','Other'))
		{
			$this->addError('other', 'Please enter details');
		}

		parent::afterValidate();
	}

	public function beforeSave()
	{
		return parent::beforeSave();
	}
}
