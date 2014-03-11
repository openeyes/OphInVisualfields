<?php
/**
 * OpenEyes
 *
 * (C) Moorfields Eye Hospital NHS Foundation Trust, 2008-2011
 * (C) OpenEyes Foundation, 2011-2013
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (c) 2008-2011, Moorfields Eye Hospital NHS Foundation Trust
 * @copyright Copyright (c) 2011-2013, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>

<?php $this->beginContent('//patient/event_container'); ?>
<?php
$this->breadcrumbs = array($this->module->id);
$this->event_actions[] = EventAction::button('Save', 'save', array('level' => 'save'), array('form' => 'clinical-create'));
?>
<?php $this->renderPartial('//base/_messages'); ?>

<?php
$form = $this->beginWidget('BaseEventTypeCActiveForm', array(
    'id' => 'clinical-create',
    'enableAjaxValidation' => false,
    'layoutColumns' => array(
        'label' => 4,
        'field' => 8
    )
        ));
?>
<?php $this->displayErrors($errors) ?>

<div class="js-active-elements">
  <?php $this->renderDefaultElements($this->action->id, $form) ?>
</div>
<?php $this->displayErrors($errors, true) ?>

<?php $this->endWidget() ?>
<?php $this->endContent(); ?>
<!--<script lang="javascript">
  var http = new XMLHttpRequest();
  var url = "http://localhost:9999/api/MeasurementVisualFieldHumphrey?resource_type=MeasurementVisualFieldHumphrey_format=xml";
//  var url = "http://localhost:9999/api/PatientMeasurement?resource_type=PatientMeasurement&_format=xml";
//  var url = "http://localhost:9999/api/Patient?resource_type=Patient&_format=xml";
  /*
	"patient_id": "4",
	"image_scan_data": "-- image data --",
	"image_scan_crop_data": "-- cropped image data --",
	"xml_file_data": "-- contents of xml file --",
	"study_datetime": "2014-03-04",
	"eye": "L",
	"test_name": "test1",
	"test_strategy": "strategy1",*/
//  var encodedData = "testdata";
    var encodedData = "<?php echo base64_encode(file_get_contents('/var/www/protected/09c7e8802098c0a3385cc45e7e8e1189b6ca644e')) ?>";
  var encodedDataThumb = "<?php echo base64_encode(file_get_contents('/var/www/protected/09c7e8802098c0a3385cc45e7e8e1189b6ca644f')) ?>";
  var params = "<MeasurementVisualFieldHumphrey><patient_id value=\"1\"/> "
    + "<image_scan_data contentType=\"text/html\" value=\"" + encodedData
    + "\"/>" + "<image_scan_crop_data value=\""+encodedDataThumb+"\"/>"
    + "<xml_file_data value=\"some data here\"/>"
    + "<study_datetime value=\"2000-01-01 12:00:00\"/>"
    + "<eye value=\"L\"/>"
    + "<pattern value=\"10-2\"/>"
    + "<strategy value=\"SITA-Standard\"/>"
    + "</MeasurementVisualFieldHumphrey>";
//var params = "<PatientMeasurement><patient_id value=\"1\"/> "
//    + "</PatientMeasurement>";
//var params = "<Patient><id value=\"1\"/> "
//    + "</Patient>";
  http.open("POST", url, true);
//  http.open("GET", url, true);

  //Send the proper header information along with the request
  http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  http.setRequestHeader("Content-length", params.length);
  http.setRequestHeader("Connection", "close");

  http.onreadystatechange = function() {//Call a function when the state changes.
    if(http.readyState == 4 && http.status == 200) {
      alert(http.responseText);
    }
  }
//  http.send(params);
</script>-->