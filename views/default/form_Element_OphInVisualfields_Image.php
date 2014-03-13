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
$api = new OphInVisualfields_API;
$right_fields = $api->getVisualfields($this->patient, Eye::RIGHT);
$left_fields = $api->getVisualfields($this->patient, Eye::LEFT);
$divName = $element->elementType->class_name;
?>
<script lang="javascript">
	

  var left_images = [];
  var right_images = [];
  var left_full_size_images = [];
  var right_full_size_images = [];
  var left_strategies = [];
  var right_strategies = [];
  var left_types = [];
  var right_types = [];
<?php
foreach ($left_fields as $left_field) {
  echo 'left_images[' . $left_field->cropped_image->id . ']=\'' . $left_field->cropped_image->getPath() . '\';' . PHP_EOL;
  echo 'left_full_size_images[' . $left_field->cropped_image->id . ']=\'' . $left_field->image->id . '\';' . PHP_EOL;
}
foreach ($right_fields as $right_field) {
  echo 'right_images[' . $right_field->cropped_image->id . ']=\'' . $right_field->cropped_image->getPath() . '\';' . PHP_EOL;
  echo 'right_full_size_images[' . $right_field->cropped_image->id . ']=\'' . $right_field->image->id . '\';' . PHP_EOL;
}
foreach ($left_fields as $left_field) {
  echo 'left_strategies[' . $left_field->cropped_image->id . ']=\'' . $left_field->strategy->name . '\';' . PHP_EOL;
}
foreach ($right_fields as $right_field) {
  echo 'right_strategies[' . $right_field->cropped_image->id . ']=\'' . $right_field->strategy->name . '\';' . PHP_EOL;
}
foreach ($left_fields as $left_field) {
  echo 'left_types[' . $left_field->cropped_image->id . ']=\'' . $left_field->pattern->name . '\';' . PHP_EOL;
}
foreach ($right_fields as $right_field) {
  echo 'right_types[' . $right_field->cropped_image->id . ']=\'' . $right_field->pattern->name . '\';' . PHP_EOL;
}
?>	
  function changeImage(select, side) {
	var index = select.options[select.selectedIndex].value;
	if (side == 'right' && index > 0) {
	  document.getElementById('Element_OphInVisualfields_Image_' + side + '_image_thumb').src = 
		"/file/view/" + index + "/test.txt";
	  document.getElementById('Element_OphInVisualfields_Image_' + side + '_image_url').href = 
		"/file/view/" + (right_full_size_images[index]) + "/test.txt";
	  $('div#Element_OphInVisualfields_Image_' + side + '_strategy').text(right_strategies[index]);
	  $('div#Element_OphInVisualfields_Image_' + side + '_type').text(right_types[index]);
			
	} else if (index > 0) {
	  document.getElementById('Element_OphInVisualfields_Image_' + side + '_image_thumb').src = 
		"/file/view/" + index + "/test.txt";
	  document.getElementById('Element_OphInVisualfields_Image_' + side + '_image_url').href = 
		"/file/view/" + (left_full_size_images[index]) + "/test.txt";
	  $('div#Element_OphInVisualfields_Image_' + side + '_strategy').text(left_strategies[index]);
	  $('div#Element_OphInVisualfields_Image_' + side + '_type').text(left_types[index]);
	}
  }
</script>

  <div class="element-fields">

	<div class="cols2 clearfix">
	  <div class="side left eventDetail"
		   data-side="right">

		<?php
		echo CHtml::activeDropDownList($element, 'right_field_id', CHtml::listData($right_fields, 'cropped_image.id', 'study_datetime'), array('empty' => '- Please select -', 'onclick' => 'changeImage(this, "right")'))
		?>
	  </div>

	  <div class="side right eventDetail"
		   data-side="left">
			 <?php
			 echo CHtml::activeDropDownList($element, 'left_field_id', CHtml::listData($left_fields, 'cropped_image.id', 'study_datetime'), array('empty' => '- Please select -', 'onclick' => 'changeImage(this, "left")'))
			 ?>

	  </div>
	</div>
  </div>
  <div class="element-fields">

	<div class="cols2 clearfix">
	  <div class="side left eventDetail"
		   data-side="right">

		<a id="<?php echo $divName ?>_right_image_url" href=""><img id="<?php echo $divName ?>_right_image_thumb" src="" /></a>

	  </div>

	  <div class="side right eventDetail"
		   data-side="left">

		<a id="<?php echo $divName ?>_left_image_url" href=""><img id="<?php echo $divName ?>_left_image_thumb" src="" /></a>


	  </div>
	</div>
  </div>
  <div class="element-fields">

	<div class="cols2 clearfix">

	  <div class="side left eventDetail"
		   data-side="right">
		<div id="<?php echo $divName ?>_right_strategy">

		</div>
	  </div>

	  <div class="side right eventDetail"
		   data-side="left">
		<div id="<?php echo $divName ?>_left_strategy">
		</div>


	  </div>
	</div>
  </div>

  <div class="element-fields">

	<div class="cols2 clearfix">
	  <div class="side left eventDetail"
		   data-side="right">

		<div id="<?php echo $divName ?>_right_type">

		</div>
	  </div>

	  <div class="side right eventDetail"
		   data-side="left">
		<div id="<?php echo $divName ?>_left_type">

		</div>

	  </div>
	</div>
  </div>

<script lang="javascript">
  function loadImages() {
	if (left_images.length > 0 ) {
	  document.getElementById('Element_OphInVisualfields_Image_left_field_id').selectedIndex = 1;
	  changeImage(document.getElementById('Element_OphInVisualfields_Image_left_field_id'), 'left');
	}
	if (right_images.length > 0 ) {
	  document.getElementById('Element_OphInVisualfields_Image_right_field_id').selectedIndex = 1;
	  changeImage(document.getElementById('Element_OphInVisualfields_Image_right_field_id'), 'right');
	}
  }
  window.onload=loadImages;
</script>

