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
require_once(WCF_DIR.'lib/system/cache/CacheBuilder.class.php');

/**
 * Caches admin tools functions
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage system.cache
 * @category WCF 
 */
class CacheBuilderAdminToolsFunction implements CacheBuilder {
	/**
	 * @see CacheBuilder::getData()
	 */
	public function getData($cacheResource) {
		list($cache, $packageID) = explode('-', $cacheResource['cache']); 
		$data = array();

		// get all functions and filter functions with low priority
		$sql = "SELECT		function.*,  package.packageDir						
			FROM		wcf".WCF_N."_package_dependency package_dependency,
						wcf".WCF_N."_admin_tools_function function					
			LEFT JOIN	wcf".WCF_N."_package package
			ON			(package.packageID = function.packageID)
			WHERE 		function.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);		
		while ($row = WCF::getDB()->fetchArray($result)) {
			$row['functionClassName'] = StringUtil::getClassName($row['classPath']);
			
			$data[$row['functionID']] = $row;
		}
		
		return $data;
	}
}
?>