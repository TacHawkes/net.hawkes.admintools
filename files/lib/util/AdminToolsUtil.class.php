<?php
/**
 *   This file is part of Admin Tools 2.
 *
 *   Admin Tools 2 is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   Admin Tools 2 is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 */

/**
 * The central utility class
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage util
 * @category WCF 
 */
class AdminToolsUtil {
	
	/**
	 * Reads the disk quota info
	 *
	 * @param integer $pow
	 * @param integer $dec
	 * @return array
	 */
	public static function readDiskInfo($pow = 2, $dec = 2) {
		$diskInformation = array();
		if(function_exists('disk_free_space') && function_exists('disk_total_space')) {
			$root = '';
			if($tmp = @disk_total_space($_SERVER["DOCUMENT_ROOT"])) $root = $_SERVER["DOCUMENT_ROOT"];
			else {
				$sql = "SELECT packageDir FROM wcf".WCF_N."_package
            			WHERE packageID = ".PACKAGE_ID;
				$row = WCF::getDB()->getFirstRow($sql);
				$root = FileUtil::getRealPath(WCF_DIR.$row['packageDir']);
			}
			if(!empty($root)) {
				$diskInformation['totalSpace'] = round(disk_total_space($root) / pow(1024, $pow), $dec);
				$diskInformation['freeSpace']  = round(disk_free_space($root) / pow(1024, $pow), $dec);
				$diskInformation['usedSpace']  = round($diskInformation['totalSpace'] - $diskInformation['freeSpace'], $dec);
				if($diskInformation['totalSpace'] > 0) {
					$diskInformation['freeQuota'] = round($diskInformation['freeSpace'] * 100 / $diskInformation['totalSpace'], $dec);
					$diskInformation['usedQuota'] = round($diskInformation['usedSpace'] * 100 / $diskInformation['totalSpace'], $dec);
				} else {
					$diskInformation['freeQuota'] = $diskInformation['usedQuota'] = 0;
				}
			}
		}
		return $diskInformation;
	}
}
?>