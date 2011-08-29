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
require_once(WCF_DIR.'lib/acp/page/CronjobsListPage.class.php');

/**
 * An extend cronjobs list which filters non-admin-tools cronjobs
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsCronjobsListPage extends CronjobsListPage  {
	public $templateName = 'adminToolsCronjobs';
	
	/**
	 * Gets the list of cronjobs.
	 */
	protected function readCronjobs() {
		parent::readCronjobs();

		// filter non admin tools cronjobs
		$cronjobIDs = array();
		foreach($this->cronjobs as $cronjob) {
			$cronjobIDs[]  = $cronjob['cronjobID'];
		}
		
		$sql = "SELECT DISTINCT cronjobID FROM wcf".WCF_N."_admin_tools_function_to_cronjob
				WHERE cronjobID IN (".implode(',', $cronjobIDs).")";
		$result = WCF::getDB()->sendQuery($sql);
		
		$adminToolsCronjobIDs = array();
		while($row = WCF::getDB()->fetchArray($result)) {
			$adminToolsCronjobIDs[] = $row['cronjobID'];
		}
				
		foreach($this->cronjobs as $key => $cronjob) {
			if(!in_array($cronjob['cronjobID'], $adminToolsCronjobIDs)) {
				unset($this->cronjobs[$key]);
			}
		}
	}

	/**
	 * @see MultipleLinkPage::countItems()
	 */
	public function countItems() {
		SortablePage::countItems();
				
		$sql = "SELECT COUNT(DISTINCT cronjobID) AS count FROM wcf".WCF_N."_admin_tools_function_to_cronjob";
		$row = WCF::getDB()->getFirstRow($sql);
		return $row['count'];
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		// set active menu item.
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.cronjobs');

		// check permission
		WCF::getUser()->checkPermission(array('admin.system.cronjobs.canEditCronjob', 'admin.system.cronjobs.canDeleteCronjob', 'admin.system.cronjobs.canEnableDisableCronjob', 'admin.system.admintools.canView'));		
		SortablePage::show();
	}
}
?>