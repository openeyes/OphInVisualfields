<?php

class DefaultController extends BaseEventTypeController {

	public function actionCreate() {
		parent::actionCreate();
	}

	public function actionUpdate($id) {
		parent::actionUpdate($id);
	}

	public function actionView($id) {
		parent::actionView($id);
	}

	public function actionPrint($id) {
		parent::actionPrint($id);
	}
	

	/**
	 * Render an optional element based on the action provided
	 *
	 * @param BaseEventTypeElement $element
	 * @param string $action
	 * @param BaseCActiveBaseEventTypeCActiveForm $form
	 * @param array $data
	 * @throws Exception
	 */
	protected function renderOptionalElement($element, $action, $form, $data)
	{
		try {
			$this->renderPartial(
				'_optional_'	. get_class($element),
				array(
					'element' => $element,
					'data' => $data,
					'form' => $form
				),
				false, false
			);
		} catch (Exception $e) {
			// TODO should check to see if this is legacy event; for the moment this will do
		}


	}

}
