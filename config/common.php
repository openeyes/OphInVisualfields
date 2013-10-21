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
return array(
    'params' => array(
        // API requests only work when true; otherwise requests are ignored
        'visualfields.use_rest' => 'true',
        // at least one user must be in the database for the API to work
        'visualfields.users' => array('mirth'),
        // Attempt to match and bind unassociated images to an event
        'visualfields.bind' => 'true',
        // Under legacy mode, all images will be paired with a legacy image
        // event; otherwise, the events will be generated based on the number
        // of episodes and specialities (and will appear in the main
        // appropriate episode
        'visualfields.legacy_mode' => array('default' => false,
            'humphreys' => false),
        // Ignored if legacy mode is false. When a patient has at least one
        // episode, each episode speciality that matches the image type's array
        // of specialities is bound to an event for that episode - this enables
        // multiple specialities to keep tabs on the same imported images:
        'visualfields.image_specialities' => array(
            'humphreys' => array('Glaucoma', 'Cataract', 'Medical Retinal')),
        // when searching for previous VFA images to create a new visual field
        // event, go back this many milliseconds for other non-associated
        // images. If 0, ignore and search back all records; otherwise,
        // specified in PHP TimeInterval format
        'visualfields.humphrey_event_bond_time' => 'PT1H2M',
        /* Although for demo purposes it is acceptable to place images
         * directly in the root of openeyes, there are other times, especially
         * for clinical use, where the real paths will be external to the
         * site directory (and will be sym-linked from say $SITE_DIR/images,
         * for example). Note also that file's stored within openeyes are
         * absolute ('real') system paths. The array specifies the
         * 'real' file system path, separated by a colon and a replacement
         * (internal to openeyes) path. For example, if 'default'
         * specified '/var/openeyes:/images' as it's path, then all occurrences 
         * of '/var/openeyes' will be replaced with '/images' when
         * building file names for images. This enables OE to correctly
         * display images without exposing their real path.
         */
        'visualfields.file_system_paths' => array('default' =>
            '/var/openeyes:/images', 'humphreys' => '/var/openeyes:/images'),
        /*
         * 
         */
        'visualfields.viewable' => array('OphInVisualfields_Testtype',
            'OphInVisualfields_Strategy', 'EventType'),
        /*
         * 
         */
        'visualfields.updatable' => array('FsFile', 'FsDirectory', 'FsFileAudit',
            'FsScanHumphreyImage', 'FsScanHumphreyXml',
            'Element_OphInVisualfields_Testtype',
            'Element_OphInVisualfields_Details',
            'Element_OphInVisualfields_Image', 'ScannedDocumentUid',
            'Episode', 'Event'),
        /*
         * 
         */
        'visualfields.api_id' => 'ASCCPE'
    )
);
