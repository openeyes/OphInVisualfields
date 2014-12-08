<?php

class DefaultController extends BaseEventTypeController
{
	protected function setComplexAttributes_Element_OphInVisualfields_Condition($element, $data, $index)
	{
		$abilities = array();

		if (!empty($data['MultiSelect_ability'])) {
			foreach ($data['MultiSelect_ability'] as $ability) {
				$assignment = new Element_OphInVisualfields_Condition_Ability_Assignment;
				$assignment->id = $ability['id'];

				$abilities[] = OphInVisualfields_Condition_Ability::model()->findByPk($ability['id']);
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

	protected function setComplexAttributes_Element_OphInVisualfields_Result($element, $data, $index)
	{
		$assessments = array();

		if (!empty($data['MultiSelect_assessment'])) {
			foreach ($data['MultiSelect_assessment'] as $assessment) {
				$_assignment = new Element_OphInVisualfields_Result_Assessment_Assignment;
				$_assignment->id = $assessment['id'];

				$assessments[] = OphInVisualfields_Result_Assessment::model()->findByPk($assessment['id']);;
			}
		}

		$element->assessment = $assessments;
	}

	protected function saveComplexAttributes_Element_OphInVisualfields_Result($element, $data, $index)
	{
		$element->updateMultiSelectData('Element_OphInVisualfields_Result_Assessment_Assignment',empty($data['MultiSelect_assessment']) ? array() : $data['MultiSelect_assessment'],'ophinvisualfields_result_assessment_id');
	}

}
