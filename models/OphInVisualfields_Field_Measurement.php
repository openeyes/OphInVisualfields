<?php

class OphInVisualfields_Field_Measurement extends Measurement
{
    public function relations()
	{
        return array(
            'eye' => array(self::BELONGS_TO, 'Eye', 'eye_id'),
            'image' => array(self::BELONGS_TO, 'ProtectedFile', 'image_id'),
            'cropped_image' => array(self::BELONGS_TO, 'ProtectedFile', 'cropped_image_id'),
            'strategy' => array(self::BELONGS_TO, 'OphInVisualfields_Strategy', 'strategy_id'),
            'pattern' => array(self::BELONGS_TO, 'OphInVisualfields_Pattern', 'pattern_id'),
        );
    }
}
