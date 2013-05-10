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
?>

<?php
// TODO - how to deal with cross-module dependencies?
Yii::import('application.modules.module_esb_mirth.models.*');
$divName = $element->elementType->class_name . $element->elementType->id;
?>

<script>
  var rightImages = new Array();
  var rightImageDates = new Array();
  var leftImages = new Array();
  var leftImageDates = new Array();
    
  function updateLeftImage() {
<?php
$leftImages = VfaUtils::getVfaFileList($this->patient, 'L');
foreach ($leftImages as $index => $leftImage) {
  $asset_id = $leftImage->vfa_file->file->asset->id;
  echo 'leftImages[' . $asset_id . ']="' . VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $asset_id . '.tif"; ';
  echo 'leftImageDates[' . $asset_id . ']="' . $leftImage->vfa_file->file->asset->created_date . '"; ';
}
?>
    var index = <?php echo $element->elementType->class_name . '_left_image' ?>.value;
    document.getElementById('<?php echo $divName ?>_left_image').src = leftImages[index];
    document.getElementById('<?php echo $divName ?>_left_image_date').innerText = leftImageDates[index];
  }
  function updateRightImage() {
<?php
$rightImages = VfaUtils::getVfaFileList($this->patient, 'R');
foreach ($rightImages as $index => $rightImage) {
  $asset_id = $rightImage->vfa_file->file->asset->id;
  echo 'rightImages[' . $asset_id . ']="' . VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $asset_id . '.tif"; ';
  echo 'rightImageDates[' . $asset_id . ']="' . $rightImage->vfa_file->file->asset->created_date . '"; ';
}
?>
    var index = <?php echo $element->elementType->class_name . '_right_image' ?>.value;
    document.getElementById('<?php echo $divName ?>_right_image').src = rightImages[index];
    document.getElementById('<?php echo $divName ?>_right_image_date').innerText = rightImageDates[index];
  }
  
<?php
$leftSrc = "";
$rightSrc = "";
if ($element->left_image) {
  $leftSrc = VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $element->left_image . '.tif"; ';
}
if ($element->right_image) {
  $rightSrc = VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $element->right_image . '.tif"; ';
}
?>
   
</script>

<div class="element <?php echo $element->elementType->class_name ?>"
     data-element-type-id="<?php echo $element->elementType->id ?>"
     data-element-type-class="<?php echo $element->elementType->class_name ?>"
     data-element-type-name="<?php echo $element->elementType->name ?>"
     data-element-display-order="<?php echo $element->elementType->display_order ?>">
  <h4 class="elementTypeName"><?php echo $element->elementType->name; ?></h4>


  <div class="cols2 clearfix">
    <div class="side left eventDetail"
         data-side="right">
           <?php echo $form->dropDownList($element, 'right_image', CHtml::listData(VfaUtils::getVfaFileList($this->patient, 'R'), 'id', 'file_name'), array('empty' => '- Please select -', 'onchange' => 'updateRightImage()')) ?>
      <img id="<?php echo $divName ?>_right_image" src="<?php echo $rightSrc ?>" />
      <div id="<?php echo $divName ?>_right_image_date"></div>
    </div>

    <div class="side right eventDetail"
         data-side="left">
           <?php echo $form->dropDownList($element, 'left_image', CHtml::listData(VfaUtils::getVfaFileList($this->patient, 'L'), 'id', 'file_name'), array('empty' => '- Please select -', 'onchange' => 'updateLeftImage()')) ?>
      <img id="<?php echo $divName ?>_left_image" src="<?php echo $leftSrc ?>" />
      <div id="<?php echo $divName ?>_left_image_date"></div>
    </div>
  </div>
</div>