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
if (!isset($patient)) {
	$patient = $this->patient;
}
if (isset($element)) {
	Yii::app()->session['_image_element'] = $element;
}
if (!isset($element)) {
	$element = Yii::app()->session['_image_element'];
}

$divName = $element->elementType->class_name;
if (!isset($leftImages)) {
	$leftImages = Element_OphInHumphreys_Document::getScannedDocuments(
					$patient->hos_num, array('associated' => 0, 'eye' => 'L'));
}
if (!isset($rightImages)) {
	$rightImages = Element_OphInHumphreys_Document::getScannedDocuments(
					$patient->hos_num, array('associated' => 0, 'eye' => 'R'));
}

// l/r image are file ids
if ($element->left_image) {
	$image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $element->left_image);
	array_unshift($leftImages, $image);
}
if ($element->right_image) {
	$image = OphInVisualfields_Humphrey_Xml::model()->find('tif_file_id=' . $element->right_image);
	array_unshift($rightImages, $image);
}
?>
<script TYPE="TEXT/JAVASCRIPT">
  
	var patient_id = <?php echo $patient->hos_num ?>;
    
	function checkSelectedValues() {
      
		var test_type = $('#Element_OphInVisualfields_Testtype_test_type_id').children('option:selected').val();
		var pattern = $('#Element_OphInVisualfields_Details_pattern_id').children('option:selected').val();
		var strategy = $('#Element_OphInVisualfields_Details_strategy_id').children('option:selected').val();
		if (test_type < 1) {
			alert('Error: please set test type.');
			return false;
		}
		if (strategy < 1) {
			alert('Error: please set test strategy.');
			return false;
		}
		if (pattern < 1) {
			alert('Error: please set pattern.');
			return false;
		}
		return true;
	}
    
	$('#<?php echo $element->elementType->class_name ?>_right_image').change(function() {
		if (checkSelectedValues()) {
			updateImage('#<?php echo $divName ?>_right_image_thumb', '#<?php echo $divName ?>_right_image_url', '#<?php echo $divName ?>_right_image_date', '#<?php echo $divName ?>_right_image_name', $(this).children('option:selected').text(), $(this).children('option:selected').val());
			return false;
		}
	});
  
	$('#<?php echo $element->elementType->class_name ?>_left_image').change(function() {
		if (checkSelectedValues()) {
			//			$(divName).attr('src', dirs[parseInt(index)+1] + fileId + ".jpg");
			updateImage('#<?php echo $divName ?>_left_image_thumb', '#<?php echo $divName ?>_left_image_url', '#<?php echo $divName ?>_left_image_date', '#<?php echo $divName ?>_left_image_name', $(this).children('option:selected').text(), $(this).children('option:selected').val());
			return false;
		}
	});
    
	function updateImage(divName, divNameUrl, divNameDate, divNameName, fileId, index) {
  
		//alert('update image dnme:' + divName + ' dnmeurl:' + divNameUrl + ' fid:' +  fileId + ' idx:' + index);
<?php
if (ScannedDocumentUid::model()->find('pid=\'' . $patient->hos_num . '\'')) {
	?>
				if (index > 0) {
	<?php
	$name = null;
	if (isset($this->episode)) {
		$name = $this->episode->firm->serviceSubspecialtyAssignment->subspecialty->name;
	}
	echo 'var dirs = { \'index\' : \'value\' };' . PHP_EOL;
	echo 'var dates = { \'index\' : \'value\' };' . PHP_EOL;
	echo 'var names = { \'index\' : \'value\' };' . PHP_EOL;
	foreach ($rightImages as $imageFile) {
		// get file path of specified file:
//                          $scan = new ScannedDocument();
		$file = OphInVisualfields_Humphrey_Image::model()->find('file_id=:file_id', array(':file_id' => $imageFile->fsScanHumphreyImage->file_id));
		echo 'dirs[' . $imageFile->file->id . ']="' . $file->getPath(Element_OphInVisualfields_Image::getSubSpecialitySubimage($name)) . '";' . PHP_EOL;
		echo 'dates[' . $imageFile->file->id . ']="' . $imageFile->study_date . ' ' . $imageFile->study_time . '";' . PHP_EOL;
		echo 'names[' . $imageFile->file->id . ']="' . $imageFile->given_name . ' ' . $imageFile->family_name . '";' . PHP_EOL;
	}
	foreach ($leftImages as $imageFile) {
		// get file path of specified file:
//                          $scan = new ScannedDocument();
		$file = OphInVisualfields_Humphrey_Image::model()->find('file_id=:file_id', array(':file_id' => $imageFile->fsScanHumphreyImage->file_id));
		echo 'dirs[' . $imageFile->file->id . ']="' . $file->getPath(Element_OphInVisualfields_Image::getSubSpecialitySubimage($name)) . '";' . PHP_EOL;
		echo 'dates[' . $imageFile->file->id . ']="' . $imageFile->study_date . ' ' . $imageFile->study_time . '";' . PHP_EOL;
		echo 'names[' . $imageFile->file->id . ']="' . $imageFile->given_name . ' ' . $imageFile->family_name . '";' . PHP_EOL;
//  
	}
	?>
					$(divName).attr('src', dirs[parseInt(index)+1] + fileId + ".jpg");
					$(divNameUrl).attr('href', dirs[parseInt(index)+1] + fileId + ".jpg");
					$(divName).show('fast');
					$(divNameDate).text(dates[parseInt(index)+1]);
					$(divNameName).text(names[parseInt(index)+1]);
				} else {
					$(divName).hide();
				}
	<?php
}
?>
				
	}
</SCRIPT>

<section class="element <?php echo $element->elementType->class_name ?>"
		 data-element-type-id="<?php echo $element->elementType->id ?>"
		 data-element-type-class="<?php echo $element->elementType->class_name ?>"
		 data-element-type-name="<?php echo $element->elementType->name ?>"
		 data-element-display-order="<?php echo $element->elementType->display_order ?>">
	<header class="element-header">
		<h3 class="element-title"><?php echo $element->elementType->name; ?></h3>
	</header>

	<div class="element-fields">

		<div class="cols2 clearfix">
			<div class="side left eventDetail"
				 data-side="right">
					 <?php
					 $leftSrc = "";
					 $leftHref = "";
					 if (count($leftImages) > 0) {
						 $f = $leftImages[0]->id;
					 }
					 if (count($leftImages) > 0) {
						 if ($element->left_image && count($leftImages) > 0) {
							 foreach ($leftImages as $image) {
								 if ($image->tif_file_id) {
									 if ($image->fsScanHumphreyImage->file->id == $element->left_image) {
										 $leftSrc = $image->fsScanHumphreyImage->getPath(Element_OphInVisualfields_Image::getSubSpecialitySubimage($name)) . $image->file_name;
										 $leftHref = $image->fsScanHumphreyImage->getPath() . $image->file_name;
									 }
								 }
							 }
						 }
					 }
					 $rightSrc = null;
					 $rightHref = null;
					 $x = $element->right_image;
					 if (count($leftImages) > 0) {
						 if ($element->right_image && count($rightImages) > 0) {
							 foreach ($rightImages as $image) {
								 if ($image->tif_file_id) {
									 $x = $image->fsScanHumphreyImage->file->id;
									 $y = $element->right_image;
									 if ($image->fsScanHumphreyImage->file->id == $element->right_image) {
										 $rightSrc = $image->fsScanHumphreyImage->getPath(Element_OphInVisualfields_Image::getSubSpecialitySubimage($name)) . $image->file_name;
										 $rightHref = $image->fsScanHumphreyImage->getPath() . $image->file_name;
										 $date = $image->study_date;
									 }
								 }
							 }
						 }
					 }
					 ?>

				<?php
				echo CHtml::activeDropDownList($element, 'right_image', CHtml::listData($rightImages, 'fsScanHumphreyImage.file.id', 'file_name'), array('empty' => '- Please select -'))
				?>


				<div id='<?php echo $divName ?>' class="side left eventDetail"
					 data-side="left">
					<a id="<?php echo $divName ?>_right_image_url" href="<?php echo $rightHref ?>"><img id="<?php echo $divName ?>_right_image_thumb" src="<?php echo $rightSrc ?>" /></a>
					<?php
					if (!isset($date)) {
						$date = "";
					}
					?>
					<div id="<?php echo $divName ?>_right_image_date">Date:</div>
					<div id="<?php echo $divName ?>_right_image_name">Date:</div>
				</div>
			</div>

			<div class="side right eventDetail"
				 data-side="left">
					 <?php
					 echo CHtml::activeDropDownList($element, 'left_image', CHtml::listData($leftImages, 'fsScanHumphreyImage.file.id', 'file_name'), array('empty' => '- Please select -'))
					 ?>

				<div id='<?php echo $divName ?>' class="side right eventDetail"
					 data-side="left">
					<a id="<?php echo $divName ?>_left_image_url" href="<?php echo $leftHref ?>"><img id="<?php echo $divName ?>_left_image_thumb" src="<?php echo $leftSrc ?>" /></a>
					<div id="<?php echo $divName ?>_left_image_date">Date: </div>
					<div id="<?php echo $divName ?>_left_image_name">Date:</div>
				</div>
			</div>
		</div>
			</section>