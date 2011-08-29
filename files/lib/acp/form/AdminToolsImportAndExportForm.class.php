<?php
require_once(WCF_DIR.'lib/acp/form/OptionImportAndExportForm.class.php');

/**
 * 	 Imports and exports admin tools function options
 * 
 *
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
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF
 */
class AdminToolsImportAndExportForm extends OptionImportAndExportForm {
	public $templateName = 'adminToolsImportAndExport';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.importandexport';
	public $neededPermissions = 'admin.system.admintools.canView';	
	
	/**
	 * @see Form::save()
	 */
	public function save() {
		ACPForm::save();
		
		// get option ids
		$sql = "SELECT		optionName, optionID 
			FROM		wcf".WCF_N."_admin_tools_option acp_option,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		acp_option.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".PACKAGE_ID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		$optionIDArray = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$optionIDArray[$row['optionName']] = $row['optionID'];
		}
		
		// save
		foreach ($this->options as $name => $value) {
			if (isset($optionIDArray[$name])) {
				$sql = "UPDATE	wcf".WCF_N."_admin_tools_option
					SET	optionValue = '".escapeString($value)."'
					WHERE	optionID = ".$optionIDArray[$name];
				WCF::getDB()->sendQuery($sql);
			}
		}
		
		// reset cache
		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.admin_tools-option*');
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}

}
?>