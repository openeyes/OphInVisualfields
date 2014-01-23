<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Element_OphInVisualfields_Humphreys_Document
 *
 * @author rich
 */
class Element_OphInHumphreys_Document {
  

  /**
   * This method uses reflection to load the module specified by the image
   * type.
   * 
   * @param string $imageType the type of image to use; this is important
   * and is used in the reflective part to load a module named
   * OphScImage[imageType].
   * 
   * @param int $fileId the file to obtain.
   * 
   * @return the Humphrey image object if it existed; null otherwise.
   */
  public function getScannedDocument($fileId) {
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = 'file_id=' . $fileId;
    try {
      $data = OphInVisualfields_Humphrey_Image::model()->find($exam_criteria);
    } catch (Exception $e) {
      
    }
    return $data;
  }

  /**
   * 
   * @param type $imageType
   */
  public static function getScannedDocuments($pid, $params) {
    $condition =  '(pid=\'' . strtoupper($pid) . '\' or pid=\'' . strtolower($pid) . '\')';
    if ($params) {
      $eye = $params['eye'];
      if (isset($params['strategy'])) {
        $strategy = $params['strategy'];
        if ($strategy) {
          $condition = $condition . ' and test_strategy=\'' . $strategy . '\'';
        }
      }
      if (isset($params['associated'])) {
        $associated = $params['associated'];
        $condition = $condition . ' and associated=' . $associated;
      }
    } else {
      // set some defaults:
      $eye = 'L';
    }
    $exam_criteria = new CDbCriteria;
    $exam_criteria->condition = $condition . ' and eye=\'' . $eye . '\'';
//    Yii::import('application.modules.OphInVisualfields.models.*', true);
    include_once(Yii::app()->getBasePath() . '/modules/OphInVisualfields/models/OphInVisualfields_Humphrey_Xml' . '.php');
    return OphInVisualfields_Humphrey_Xml::model()->findAll($exam_criteria);
  }
}

?>
