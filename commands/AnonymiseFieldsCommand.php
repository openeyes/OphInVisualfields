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
class AnonymiseFieldsCommand extends CConsoleCommand {


    public function getHelp() {
        return "Usage: LegacyFields anonymise\n\n"
                . "Import Humphrey visual fields into OpenEyes from the given import directory.\n"
                . "Successfully imported files are moved to the given archive directory;\n"
                . "likewise, errored files and duplicate files (already within OE) are moved to\n"
                . "the respective directory. --interval is used to check for tests within\n"
                . "the specified time limit, so PT10M looks for files within 10 minutes of the other to\n"
                . "bind to an existing field.\n\n"
                . "The import expects to find .XML files in the given directory, two\n"
                . "for each field test, and each file is expected to be in a format\n"
                . "acceptable by OpenEyes (specifically they must conform to the API).\n"
                . "For each pair of files, the first is a patient measurement, the\n"
                . "second a humphrey field test reading.\n"
                . "\n";
    }

    /**
     * Take a list of real patient identifiers that appear in a collection
     * of FMES files, and remove the 'real' PID in the FMES file in favour
     * of 
     * 
     * @param type $realPidFile
     * @param type $anonPidFile 
     */
    public function actionFmes($fmesDir, $realPidFile, $anonPidFile) {
	foreach(array($fmesDir, $realPidFile, $anonPidFile) as  $file) {
		if (!file_exists($file)) {
			echo $file . ' does not exist' . PHP_EOL;
			exit(1);
		}
	}
        $realPids = file_get_contents($realPidFile);
        $anonPids = file_get_contents($anonPidFile);
        $rPids = explode(PHP_EOL, $realPids);
        $aPids = explode(PHP_EOL, $anonPids);
        // make sure PID count is equal:
        if (count($rPids) != count($aPids)) {
            echo 'Error: PID counts do not match; file contents must match 1-1' . PHP_EOL;
            exit (1);
        }
        // check all real patients exist:
        foreach($aPids as $pid) {
		if ($pid) {
            		if (count(Patient::model()->find("hos_num='". $pid . "'")) < 1) {
                		echo 'Failed to find anonymous patient ' . $pid . PHP_EOL;
				exit (1);
			}
		}
        }
        // now check that all 'real' patients are listed in the files:
        $entries = array();

        $smgr = Yii::app()->service;
        $fhirMarshal = Yii::app()->fhirMarshal;
        if ($entry = glob($fmesDir . '/*.fmes')) {
            foreach ($entry as $file) {
                $field = file_get_contents($file);
                $fieldObject = $fhirMarshal->parseXml($field);
                $match = $this->getHosNum($file, $field);
		if (!in_array($match, $entries)) {
			if (in_array($match, $rPids)) {
                    		array_push($entries, $match);
                	}
		}
            }
        }
//        if (count($entries) != count($aPids)) {
//            echo 'Error: mismatch ' . count($entries) . ' ' . count($aPids) . PHP_EOL;
//            exit (1);
//        } 
	$floor = count($aPids);
	if (count($rPids) < $floor) {
		$floor = count($rPids);
	}
	// now create new FMES files
	// need to go through each one, pairing anonymised IDs with real ones, relacing the real ID with the anonymised ID:
        if ($entry = glob($fmesDir . '/*.fmes')) {
            foreach ($entry as $file) {
                $field = file_get_contents($file);
                $fieldObject = $fhirMarshal->parseXml($field);

                $match = $this->getHosNum($file, $field);
                if (in_array($match, $rPids)) {
			$index = array_search($match, $rPids);
			$anonPid = $aPids[$index];
	                $field = preg_replace("/__OE_PATIENT_ID_([0-9]*)__/", $anonPid, $field);
			echo 'replacing ' . $match . ' with ' . $anonPid . PHP_EOL;
                }
            }
        }
    }
    
    /**
     *
     * @param type $file
     * @param type $field
     * @param array $matches
     * @return type 
     */
    private function getHosNum($file, $field) {
        
        $matches = array();
                    preg_match("/__OE_PATIENT_ID_([0-9]*)__/", $field, $matches);
                    if (count($matches) < 2) {
                        echo "Failed to extract patient ID in " . basename($file) . "; moving to " . $this->errorDir . PHP_EOL;
                        $this->move($this->errorDir, $file);
                        continue;
                    }
                    return str_pad($matches[1], 7, '0', STR_PAD_LEFT);
    }

}
