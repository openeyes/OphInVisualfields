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
?>

<h4 class="elementTypeName"><?php echo $element->elementType->name ?></h4>

<table class="subtleWhite normalText">
  <tbody>
    <tr>
      <?php
      if ($element->right_image) {
        ?>
      <td width="50%"><a href="<?php echo VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/' . $element->right_image . '.tif' ?>"><img src="<?php echo VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $element->right_image . '.tif' ?>"</img></a></td>
        <?php
      } else {
        ?>
        <td>&nbsp;</td>
        <?php
      }
      if ($element->left_image) {
        ?>
      <td width="50%"><a href="<?php echo VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/' . $element->left_image . '.tif' ?>"><img src="<?php echo VfaUtils::getEncodedDiscFileName($this->patient->hos_num) . '/thumbs/' . $element->left_image . '.tif' ?>"</img></a></td>
        <?php
      } else {
        ?>
        <td>&nbsp;</td>
        <?php
      }
      ?>
    </tr>
    <tr>
      <?php
      if ($element->right_image) {
        ?>
        <td width="50%">Date: <?php echo Asset::model()->find('id=' . $element->right_image)->created_date ?></td>
        <?php
      } else {
        ?>
        <td>&nbsp;</td>
        <?php
      }
      ?>
      <?php
      if ($element->left_image) {
        ?>
        <td width="50%">Date: <?php echo Asset::model()->find('id=' . $element->left_image)->created_date ?></td>
        <?php
      } else {
        ?>
        <td>&nbsp;</td>
        <?php
      }
      ?>
    </tr>
  </tbody>
</table>
