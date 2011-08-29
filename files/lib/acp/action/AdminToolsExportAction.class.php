<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * 	 Exports admin tools function options
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
 * @subpackage acp.action
 * @category WCF
 */
class AdminToolsExportAction extends AbstractAction {

	/**
	 * @see Action::execute();
	 */
	public function execute() {
		parent::execute();

		// header
		@header('Content-type: text/xml');

		// file name
		@header('Content-disposition: attachment; filename="admintools.xml"');
			
		// no cache headers
		@header('Pragma: no-cache');
		@header('Expires: 0');

		// content
		echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n<options>\n";

		$options = $this->getOptions();
		foreach ($options as $option) {
			echo "\t<option>\n";
			echo "\t\t<name><![CDATA[".StringUtil::escapeCDATA($option['optionName'])."]]></name>\n";
			echo "\t\t<value><![CDATA[".StringUtil::escapeCDATA($option['optionValue'])."]]></value>\n";
			echo "\t</option>\n";
		}

		echo '</options>';
		$this->executed();
		exit;
	}

	/**
	 * Returns a list of options.
	 *
	 * @param	integer		$packageID
	 * @return	array
	 */
	public function getOptions($packageID = PACKAGE_ID) {
		$sql = "SELECT		optionName, optionID
			FROM		wcf".WCF_N."_admin_tools_option acp_option,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		acp_option.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".$packageID."
			ORDER BY	package_dependency.priority";
		$result = WCF::getDB()->sendQuery($sql);
		$optionIDs = array();
		while ($row = WCF::getDB()->fetchArray($result)) {
			$optionIDs[$row['optionName']] = $row['optionID'];
		}

		$options = array();
		if (count($optionIDs) > 0) {
			// get needed options
			$sql = "SELECT		optionName, optionValue, optionType
				FROM		wcf".WCF_N."_admin_tools_option
				WHERE		optionID IN (".implode(',', $optionIDs).")
				ORDER BY	optionName";
			$result = WCF::getDB()->sendQuery($sql);
			while ($row = WCF::getDB()->fetchArray($result)) {
				$options[strtoupper($row['optionName'])] = $row;
			}
		}

		return $options;
	}
}
?>