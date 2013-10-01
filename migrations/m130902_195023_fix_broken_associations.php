<?php
/**
 * When initially imported in cardiff, running the import
 * broke. The result was that all visual field events were
 * created, with the exception that the TIF file IDs were
 * not associated with the event. The fix for this is
 * simple - find all the IDs for the events and associate
 * them.
 */
class m130902_195023_fix_broken_associations extends CDbMigration {

  // Use safeUp/safeDown to do migration with transaction
  public function safeUp() {
    $images = Element_OphInVisualfields_Image::model()->findAll("");
    
    foreach($images as $image) {
      $left = FsScanHumphreyXml::model()->find("tif_file_id=" . $image->left_image);
      $right = FsScanHumphreyXml::model()->find("tif_file_id=" . $image->right_image);
      if ($left && $right) {
        if ($left->associated == 0 || $right->associated == 0) {
          echo "  Bad associations for image pair " . $image->id . PHP_EOL;
        }
        if ($left->associated == 0) {
          $left->associated = 1;
          $left->save();
          echo "  ... Fixed inconsistent image association for " .
                  $left->file->name . ' (id ' . $left->id . ') '. PHP_EOL;
        }
        if ($right->associated == 0) {
          $right->associated = 1;
          $right->save();
          echo "  ... Fixed inconsistent image association " .
                  $right->file->name . ' (id ' . $right->id . ') '. PHP_EOL;
        }
      }
    }
    
  }

  public function safeDown() {
    
  }

}
