<?php

class DefaultController extends BaseEventTypeController
{
	protected function setComplexAttributes_Element_OphInVisualfields_Condition($element, $data, $index)
	{
		$abilities = array();

		if (!empty($data['MultiSelect_ability'])) {
			foreach ($data['MultiSelect_ability'] as $ability_id) {
				$assignment = new Element_OphInVisualfields_Condition_Ability_Assignment;
				$assignment->id = $ability_id;

				$abilities[] = OphInVisualfields_Condition_Ability::model()->findByPk($ability_id);;
			}
		}

		$element->abilitys = $abilities;
	}

	protected function saveComplexAttributes_Element_OphInVisualfields_Condition($element, $data, $index)
	{
		$element->updateMultiSelectData('Element_OphInVisualfields_Condition_Ability_Assignment',empty($data['MultiSelect_ability']) ? array() : $data['MultiSelect_ability'],'ophinvisualfields_condition_ability_id');
	}


	public function getophinvisualfields_condition_ability_defaults() {
		$ids = array();
		foreach (OphInVisualfields_Condition_Ability::model()->findAll('`default` = ?',array(1)) as $item) {
			$ids[] = $item->id;
		}
		return $ids;
	}

}
