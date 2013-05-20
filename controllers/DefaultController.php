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
   * Update images for a VF based on test type and strategy.
   * 
   * @param type $patient_id
   * @param type $test_type_id
   * @param type $strategy_id
   */
  public function actionUpdateImages($patient_id, $test_type, $strategy) {
    $doc = new ScannedDocument;
    if ($doc->isSupported($test_type)) {
      $leftImages = $doc->getScannedDocuments('humphreys', $patient_id, array('strategy' => $strategy, 'eye' => 'L'));
      $rightImages = $doc->getScannedDocuments('humphreys', $patient_id, array('strategy' => $strategy, 'eye' => 'R'));
//      foreach($leftImages as $index => $leftImage) {
//        if ($leftImage->fsScanHumphreyImage->associated && $leftImage->fsScanHumphreyImage->file->asset->id != $left_asset_id) {
//          unset($leftImages[$index]);
//        }
//      }
//      foreach($rightImages as $index => $rightImage) {
//        if ($rightImage->fsScanHumphreyImage->associated && $rightImage->fsScanHumphreyImage->file->asset->id != $right_asset_id) {
//          unset($rightImages[$index]);
//        }
//      }
      $patient = Patient::model()->find('hos_num=\'' . $patient_id . '\'');
      $this->renderPartial('form_Element_OphInVisualfields_Image', array('rightImages' => $rightImages, 'leftImages' => $leftImages, 'patient' => $patient));
    }
  }

  /**
   * Update images for a VF based on test type and strategy.
   * 
   * @param type $patient_id
   * @param type $test_type_id
   * @param type $strategy_id
   */
  //'assetId' => $element->right_image,'rightSrc' => $rightSrc, 'divName' => $divName . '_right_image', 'side' => 'R', 'patient' => $patient
  public function actionUpdateImage($assetId, $side) {
    $doc = new ScannedDocument;
    if ($doc->isSupported('humphreys') && $assetId) {
      $image = $doc->getScannedDocument('humphreys', $patient_id, $assetId, array('assetId' => $assetId, 'eye' => $side));
      $patient = Patient::model()->find('hos_num=\'' . $patient_id . '\'');
      $this->renderPartial('form_Element_OphInVisualfields_Image', array('assetId' => $assetId));
    }
    $f = 'u';
  }

}
