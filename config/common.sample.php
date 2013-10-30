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
        /* Attempt to match and bind unassociated images to an event. If
         * set to false, images will be unbound and users will have to bind
         * images individually by hand via the UI. */
        'visualfields.bind' => false,
        /* Only used when visualfields.bind is set to true.
         * When searching for previous VFA images to create a new visual field
         * event, go back this amount of time for other non-associated.
         * images. */
        'visualfields.event_bond_time' => 'PT1H2M',
        /* Under legacy mode, all images will be paired with a legacy image
         * event; otherwise, the events will be generated based on the number
         * of episodes and specialities (see image_specialities).
         * This is designed specifically with importing massive sets of old
         * data that stretches back years; users can specify legacy mode as
         * true when importing old data, then set as false as soon as the
         * import is finished. */
        'visualfields.legacy_mode' => true,
        /* Ignored if legacy mode is false. When a patient has at least one
         * episode, each episode speciality that matches the image type's array
         * of specialities is bound to an event for that episode - this enables
         * multiple specialities to keep tabs on the same imported images: */
        'visualfields.image_specialities' => array(
            'humphreys' => array('Glaucoma', 'Cataract', 'Medical Retinal')),
        /* 'deep' or 'flat'. Specify if created directories should be flat
         * (all in one directory) or deep, in the form test_type/unique_pid/
         */
        'visualfields.dir_structure' => 'deep',
        /* What's the type of file extension for images? PDF, TIF etc. */
        'visualfields.img_extension' => 'tif',
        /* Where are files read from? */
        'visualfields.dir_in' => array('humphreys' => '/var/openeyes/vfa-in'),
        /* Where are files proessed to? */
        'visualfields.dir_out' => array('humphreys' => '/var/openeyes/vfa-images'),
        /* Where do files that have been errored get placed? */
        'visualfields.dir_err' => array('humphreys' => '/var/openeyes/vfa-err'),
        /* Policy for duplicates. */
        'visualfields.duplicates' => array(
            // true to delete all duplicates; duplicates are files with
            // the same name in the same directory:
            'delete' => false,
            // only used if delete is false (relative to dir_base) - duplicates
            // get placed in this directory, prefixed with the current time:
            'dir' => array('humphreys' => '/var/openeyes/vfa-duplicates'),
        ),
        // ==========================
        // Image cropping and scaling
        // ==========================

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
        /* Select regions to extract from the image, in the format
         *   test_type => array(
         *      directory_name => array(
         *          crop => width,height,x,y, 
         *          scale => XxY)
         *      )
         *   )
         * ... where crop specifies the width, height, x and y positions of
         * the cropped image; and scale specifies the size of the image to
         * resize to. The directory name specifies the name of the directory
         * to create the cropped and scaled image in; this will be relative
         * to the image being cropped as a subdirectory. If the scale
         * parameters are the same as the cropped size, or no scale parameters
         * are specified, the image is not scaled.
         */
        'visualfields.subimages' => array(
            'humphreys' => array(
                'thumbs' => array('crop' => '925,834,1302,520',
                    'scale' => '300x306'),
                'total_deviation' => array('crop' => '572,572,292,1990',
                    'scale' => '306x306'),
                'pattern_deviation' => array('crop' => '572,572,1018,1995',
                    'scale' => '306x306'),
            ),
        ),
        /* Which subimage to show per speciality; define a default for those
         * that do not define their own. The values must match the
         * visualfields.subimages declarations config. */
        'visualfields.subspeciality_subimage' => array(
            'humphreys' => array(
                'default' => 'thumbs',
                'Glaucoma' => 'thumbs',
                'Cataract' => 'pattern_deviation',
            )
        ),
        // TO BE DEPRECATED:
        // API requests only work when true; otherwise requests are ignored
        'visualfields.use_rest' => 'false',
        // at least one user must be in the database for the API to work
        'visualfields.users' => array('mirth'),
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
