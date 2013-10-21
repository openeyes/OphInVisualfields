<?php

try {
  $assetUrl = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('application.modules.OphInVisualfields.assets.js'));
  Yii::app()->clientScript->registerScriptFile($assetUrl . '/imageLoader.js');
} catch (Exception $e) {
  
}
$patient = $this->patient;

//$eyeRightFiles = DiscUtils::getDiscFileList($patient, 'R');
//$eyeLeftFiles = DiscUtils::getDiscFileList($patient, 'L');

$doc = new ScannedDocument();
$eyeRightFilesVfa = $doc->getScannedDocuments('Visualfields', 'humphreys', $patient->hos_num, array('eye' => 'R', 'associated' => '1'));
$eyeLeftFilesVfa = $doc->getScannedDocuments('Visualfields', 'humphreys', $patient->hos_num, array('eye' => 'L', 'associated' => '1'));


?>

<script type="text/javascript">
        var imagesStereoRight = new Array();
        var imagesStereoLeft = new Array()
        var imagesVfaRight = new Array();
        var imagesVfaLeft = new Array();
        var imagesAlgRight = new Array();
        var imagesAlgLeft = new Array();
                
//        imagesAlgRight[0] = '/images/demo/r/1.jpg';
//        imagesAlgRight[1] = '/images/demo/r/2.jpg';
//        imagesAlgRight[2] = '/images/demo/r/3.jpg';
//        imagesAlgRight[3] = '/images/demo/r/4.jpg';
//        imagesAlgRight[4] = '/images/demo/r/5.jpg';
//        imagesAlgRight[5] = '/images/demo/r/6.jpg';
//        imagesAlgRight[6] = '/images/demo/r/7.jpg';
//        imagesAlgRight[7] = '/images/demo/r/8.jpg';
//        imagesAlgRight[8] = '/images/demo/r/9.jpg';
//        imagesAlgRight[9] = '/images/demo/r/10.jpg';
//                
//        imagesAlgLeft[0] = '/images/demo/l/1.jpg';
//        imagesAlgLeft[1] = '/images/demo/l/2.jpg';
//        imagesAlgLeft[2] = '/images/demo/l/3.jpg';
//        imagesAlgLeft[3] = '/images/demo/l/4.jpg';
//        imagesAlgLeft[4] = '/images/demo/l/5.jpg';
//        imagesAlgLeft[5] = '/images/demo/l/6.jpg';
//        imagesAlgLeft[6] = '/images/demo/l/7.jpg';
//        imagesAlgLeft[7] = '/images/demo/l/8.jpg';
//        imagesAlgLeft[8] = '/images/demo/l/9.jpg';
//        imagesAlgLeft[9] = '/images/demo/l/10.jpg';
<?php
$imageIndex = 0;
//foreach ($eyeRightFiles as $file) {
//  // large-size image storage location:
//  echo "\nimagesStereoRight[" . ($imageIndex++) . "] = \"" . DiscUtils::getEncodedDiscFileName($patient->hos_num, $file->file->name) . "/thumbs/" . $file->file->name . "\";";
//}
//$imageIndex = 0;
//foreach ($eyeLeftFiles as $file) {
//  // large-size image storage location:
//  echo "\n imagesStereoLeft[" . ($imageIndex++) . "] = \"" . DiscUtils::getEncodedDiscFileName($patient->hos_num, $file->file->name) . "/thumbs/" . $file->file->name . "\";";
//}

$imageIndex = 0;
foreach ($eyeRightFilesVfa as $file) {
  if ($file->fsScanHumphreyImage) {
//    $x = $file->fsScanHumphreyImage->getXPath($file, '/images');
    // large-size image storage location:
    echo "\nimagesVfaRight[" . ($imageIndex++) . "] = \"" . $file->fsScanHumphreyImage->getPath('thumbs/') . $file->file_name . "\";";
  }
}
$imageIndex = 0;
foreach ($eyeLeftFilesVfa as $file) {
  // large-size image storage location:
  if ($file->fsScanHumphreyImage) {
    echo "\n imagesVfaLeft[" . ($imageIndex++) . "] = \"" . $file->fsScanHumphreyImage->getPath('thumbs/') . $file->file_name . "\";";
  }
}
?>
        window.onload = function() {
          var canvas = document.getElementById("canvasStereoRight");
          if (canvas != null) {
            var imageObjRight = new Image();
            var context = canvas.getContext("2d");
            imageObjRight.onload = function() {
              context.drawImage(imageObjRight, 0, 0);
            };
            imageObjRight.src = imagesStereoRight[0];
            canvas.addEventListener('mousemove', function(e){ev_mousemove(e,
              "canvasStereoRight", imageObjRight, imagesStereoRight,
              "canvasVfaRight", imagesVfaRight)}, false);
          }
                    
          var canvas2 = document.getElementById("canvasStereoLeft");
          if (canvas2 != null) {
            var imageObjLeft = new Image();
            var context2 = canvas2.getContext("2d");
            imageObjLeft.onload = function() {
              context2.drawImage(imageObjLeft, 0, 0);
            };
            imageObjLeft.src = imagesStereoLeft[0];
            canvas2.addEventListener('mousemove', function(e){ev_mousemove(e, 
              "canvasStereoLeft", imageObjLeft, imagesStereoLeft,
              "canvasVfaLeft", imagesVfaLeft)}, false);
          }
          // =========================================================
                    
          var canvasVfa = document.getElementById("canvasVfaRight");
          if (canvasVfa != null) {
            var imageObjVfaRight = new Image();
            var contextVfa = canvasVfa.getContext("2d");
            imageObjVfaRight.onload = function() {
              contextVfa.drawImage(imageObjVfaRight, 0, 0);
            };
            imageObjVfaRight.src = imagesVfaRight[0];
//            var imageObjAlgRight = new Image();
            canvasVfa.addEventListener('mousemove', function(e){ev_mousemove(e, 
              "canvasVfaRight", imageObjVfaRight, imagesVfaRight,
              "canvasVfaLeft", imageObjVfaLeft, imagesVfaLeft,
              "canvasStereoRight", imagesStereoRight)}, false);
//            ,
//              "canvasAlgRight", imagesAlgRight, imageObjAlgRight,
//              "canvasAlgLeft", imagesAlgLeft, imageObjAlgLeft)}, false);
          }
                    
          var canvasVfa2 = document.getElementById("canvasVfaLeft");
          if (canvasVfa2 != null) {
            var imageObjVfaLeft = new Image();
            var contextVfa2 = canvasVfa2.getContext("2d");
            imageObjVfaLeft.onload = function() {
              contextVfa2.drawImage(imageObjVfaLeft, 0, 0);
            };
            imageObjVfaLeft.src = imagesVfaLeft[0];
            
//            var canvasAlg2 = document.getElementById("canvasAlgLeft");
//            var imageObjAlgLeft = new Image();
//            var contextAlg2 = canvasAlg2.getContext("2d");
//            imageObjAlgLeft.onload = function() {
//              contextAlg2.drawImage(imageObjAlgLeft, 0, 0);
//            };
//            imageObjAlgLeft.src = imagesAlgLeft[0];
            
            
//            var canvasAlg = document.getElementById("canvasAlgRight");
//            var contextAlg = canvasAlg.getContext("2d");
//            imageObjAlgRight.onload = function() {
//              contextAlg.drawImage(imageObjAlgRight, 0, 0);
//            };
//            imageObjAlgRight.src = imagesAlgRight[0];
            
            canvasVfa2.addEventListener('mousemove', function(e){ev_mousemove(e, 
              "canvasVfaLeft", imageObjVfaLeft, imagesVfaLeft,
              "canvasVfaRight", imageObjVfaRight, imagesVfaRight,
              "canvasStereoLeft", imagesStereoLeft)}, false);
//            ,
//              "canvasAlgLeft", imagesAlgLeft, imageObjAlgLeft,
//              "canvasAlgRight", imagesAlgRight, imageObjAlgRight)}, false);
          }
          // =========================================================
        };
</script>


<!--<div style="clear: both"></div> 
<div id="x" style="float:left; margin-bottom: 10px; margin-top: 10px; margin-left: 100px; ">
  <?php // echo "Stereo images: " . count($eyeRightFiles) ?>
</div>

<div id="x" style="float:right; margin-bottom: 10px; margin-top: 10px; margin-right: 100px; ">
  <?php // echo "Stereo images: " . count($eyeLeftFiles) ?>
</div>-->
<div style="clear: both"></div> 
<?php
//if (count($eyeRightFiles) > 0) {
//  ?>

<!--  <div id="XYZ2" class="jThumbnailScroller" style="margin-left: 100px; float:left; height:225px; width:300px; ">
    <canvas id="canvasStereoRight" class="tmp" width="300" height="225" tabindex="1"></canvas>
  </div>-->
  <?php
//}
//
//if (count($eyeLeftFiles) > 0) {
//  ?>

<!--  <div id="XYZ" class="jThumbnailScroller" style="margin-right: 100px; float:right; height:225px; width:300px; ">
    <canvas id="canvasStereoLeft" class="tmp" width="300" height="255" tabindex="1"></canvas>
  </div>-->
  <?php
//}
?>
<div style="clear: both"></div> 

<div id="x" style="float:left; margin-bottom: 10px; margin-top: 10px; margin-left: 100px; ">
  <?php echo "VFA images: " . count($eyeRightFilesVfa) ?>
</div>

<div id="x" style="float:right; margin-bottom: 10px; margin-top: 10px; margin-right: 100px; ">
  <?php echo "VFA images: " . count($eyeLeftFilesVfa) ?>
</div>
<div style="clear: both"></div> 

<?php
if (count($eyeRightFilesVfa) > 0) {
  ?>
  <div id="XYZa2" class="jThumbnailScroller" style="margin-left: 100px; float:left; height:306px; width:300px; ">
    <canvas id="canvasVfaRight" class="tmp" width="300" height="306" tabindex="1"></canvas>
  </div>
  <?php
}

if (count($eyeLeftFilesVfa) > 0) {
  ?>
  <div id="XYZa" class="jThumbnailScroller" style="margin-right: 100px; float:right; height:306px; width:300px; ">
    <canvas id="canvasVfaLeft" class="tmp" width="300" height="306" tabindex="1"></canvas>
  </div>
  <?php
}
?>
<?php
//if (count($eyeRightFilesVfa) > 0) {
  ?>
<!--  <div id="XYZb2" class="jThumbnailScroller" style="margin-left: 100px; float:left; height:306px; width:300px; ">
    <canvas id="canvasAlgRight" class="tmp" width="300" height="306" tabindex="1"></canvas>
  </div>-->
  <?php
//}
//
//if (count($eyeLeftFilesVfa) > 0) {
  ?>
<!--  <div id="XYZb" class="jThumbnailScroller" style="margin-right: 100px; float:right; height:306px; width:300px; ">
    <canvas id="canvasAlgLeft" class="tmp" width="300" height="306" tabindex="1"></canvas>
  </div>-->
  <?php
//}
?>

<div style="clear: both"></div> 
