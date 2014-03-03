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
			if (isset($this->episode->firm->serviceSubspecialtyAssignment->subspecialty->name)) {
				$name = $this->episode->firm->serviceSubspecialtyAssignment->subspecialty->name;
			} else {
				$name = $this->firm->serviceSubspecialtyAssignment->subspecialty->name;
			}
			if ($element->right_field_id) {
				// l/r image are file ids

				$right_test = OphInVisualfields_Humphrey_Xml::model()->find('cropped_image_id=' . $element->right_field_id);

				?>
				<td width="50%"><a href="<?php echo str_replace("/var/www/protected", "", $right_test->cropped_image->getPath()) ?>"><img src="<?php echo "http://localhost:8888/file/view/" . $right_test->cropped_image_id . "/test.txt"; ?>"></img></a></td>
				<?php

			} else {
				?>
				<td>&nbsp;</td>
				<?php
			}
			if ($element->left_field_id) {

				$left_test = OphInVisualfields_Humphrey_Xml::model()->find('cropped_image_id=' . $element->left_field_id);
				?>
				<td width="50%"><a href="<?php echo str_replace("/var/www/protected", "", $left_test->cropped_image->getPath()) ?>"><img src="<?php echo "http://localhost:8888/file/view/" . $left_test->cropped_image_id . "/test.txt"; ?>"></img></a></td>
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
			if ($element->right_field_id) {
				?>
				<td width="50%">Date: <?php echo $right_test->study_datetime ?></td>
				<?php
			} else {
				?>
				<td>&nbsp;</td>
				<?php
			}
			?>
			<?php
			if ($element->left_field_id) {
				?>
				<td width="50%">Date: <?php echo $left_test->study_datetime  ?></td>
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
			if ($element->right_field_id) {
				?>
				<td width="50%">Strategy: <?php echo $right_test->test_strategy ?></td>
				<?php
			} else {
				?>
				<td>&nbsp;</td>
				<?php
			}
			?>
			<?php
			if ($element->left_field_id) {
				?>
				<td width="50%">Strategy: <?php echo $left_test->test_strategy ?></td>
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
			if ($element->right_field_id) {
				?>
				<td width="50%">Test Name: <?php echo $right_test->test_name ?></td>
				<?php
			} else {
				?>
				<td>&nbsp;</td>
				<?php
			}
			?>
			<?php
			if ($element->left_field_id) {
				?>
				<td width="50%">Test Name: <?php echo $left_test->test_name ?></td>
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