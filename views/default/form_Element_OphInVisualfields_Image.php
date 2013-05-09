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
  function updateLeftImage() {
    var leftImages = new Array();
<?php
$leftImages = VfaUtils::getVfaFileList($this->patient, 'L');
foreach ($leftImages as $index => $leftImage) {
  $asset_id = $leftImage->vfa_file->file->asset->id;
  echo 'leftImages[' . $index . ']="' . VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $asset_id . '.tif"; ';
}
?>
    var index = <?php echo $element->elementType->class_name . '_left_image' ?>.selectedIndex;
    //alert('index=' + index);
    document.getElementById('<?php echo $divName?>_left').src = leftImages[index];
  }
  function updateRightImage() {
    var rightImages = new Array();
<?php
$rightImages = VfaUtils::getVfaFileList($this->patient, 'R');
foreach ($rightImages as $index => $rightImage) {
  $asset_id = $rightImage->vfa_file->file->asset->id;
  echo 'rightImages[' . $index . ']="' . VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $asset_id . '.tif"; ';
}
?>
    var index = <?php echo $element->elementType->class_name . '_right_image' ?>.selectedIndex;
    //alert('index=' + index);
    document.getElementById('<?php echo $divName?>_right').src = rightImages[index];
  }
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
      <img id="<?php echo $divName?>_right" src="" />
    </div>

    <div class="side right eventDetail"
         data-side="left">
           <?php echo $form->dropDownList($element, 'left_image', CHtml::listData(VfaUtils::getVfaFileList($this->patient, 'L'), 'id', 'file_name'), array('empty' => '- Please select -', 'onchange' => 'updateLeftImage()')) ?>
      <img id="<?php echo $divName?>_left" src="" />
    </div>
  </div>
</div>