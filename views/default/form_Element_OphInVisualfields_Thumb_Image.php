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
if (isset($element)) {
  $s = $element;
}
?>

<?php
$divside = 'left';
if (isset($element_id)) {
  $tt = $element_id;
}
if (isset($event_id)) {
  $tt = $event_id;
}
if ($side == 'R') {
  $divside = 'right';
}
if ($assetId != null && !isset($element)) {
  if ($side == 'R') {
    ?>
    <script>
      $('#abcdefg').val('<?php echo $assetId ?>');
      alert($('#abcdefg').val());
      //    alert($('#Element_OphInVisualfields_Image78_right_image').val());
    </script>
    <?php
//    $element = Element_OphInVisualfields_Image::model()->find('right_image=' . $assetId);
  } else {
//    $element = Element_OphInVisualfields_Image::model()->find('left_image=' . $assetId);
    ?>
    <script>
      $('#abcdefg').val('<?php echo $assetId ?>');
      //    alert($('#Element_OphInVisualfields_Image78_left_image').val());
      alert($('#abcdefg').val());
    </script>
    <?php
  }
}
if (isset($_GET['side'])) {
  $x = 'y';
}
if (isset($_GET['div'])) {
  $x = $div;
}
if (isset($_GET['image'])) {
  $x = 'y';
}
if ($assetId) {
  Yii::import('application.modules.module_esb_mirth.models.*');
  ?>
  <div id='<?php echo $div ?>' class="side <?php echo $divside ?> eventDetail"
       data-side="left">
    <img id="<?php echo $div ?>" src="<?php echo VfaUtils::getEncodedDiscFileName($patient->hos_num) . '/thumbs/' . $assetId . '.tif"; '; ?>" />
    <div id="<?php echo $div ?>_date"></div>
  </div>

  <?php
}?>