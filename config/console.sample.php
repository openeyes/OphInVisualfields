<?php

return array(
    'name' => 'OphInVisualfields',
    'commandMap' => array(
        'importhumphreyimages' => array(
            'class' => 'application.modules.OphInVisualfields.commands.ImportHumphreyScanCommand',
        ),
    ),
    'components' => array(
        'db' => array(
			'class' => 'CDbConnection',
			'emulatePrepare' => true,
			'charset' => 'utf8',
			'schemaCachingDuration' => 300,
            'connectionString' => 'mysql:host=localhost;dbname=openeyes',
            'username' => 'root',
            'password' => '',
        ),
    ),
);
?>
