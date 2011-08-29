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
require_once(WCF_DIR.'lib/acp/action/CronjobsDeleteAction.class.php');

/**
 * Wrapper class for the original Cronjob Actions
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.action
 * @category WCF
 */
class AdminToolsCronjobsDeleteAction extends CronjobsDeleteAction {
	
	/**
	 * @see Action::execute()
	 */
	public function execute() {
		// check permission
		if (!$this->cronjob->canBeEdited) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		WCF::getUser()->checkPermission('admin.system.cronjobs.canDeleteCronjob');
		
		$sql = "DELETE FROM wcf".WCF_N."_admin_tools_function_to_cronjob
				WHERE cronjobID = ".$this->cronjobID;
		WCF::getDB()->sendQuery($sql);
		
		$sql = "SELECT packageDir FROM wcf".WCF_N."_package package
				LEFT JOIN wcf".WCF_N."_cronjobs cronjob
				ON (cronjob.packageID = package.packageID)
				WHERE cronjob.cronjobID = ".$this->cronjobID;
		$row = WCF::getDB()->getFirstRow($sql);
		if (empty($row['packageDir'])) {
			$path = WCF_DIR;
		}
		else {
			$path = FileUtil::getRealPath(WCF_DIR.$row['packageDir']);
		}
		@unlink($path.'lib/system/cronjob/AdminToolsCronjob'.$this->cronjobID.'.class.php');		
		
		parent::execute();
	}
	
	/**
	 * @see Action::execute()
	 */
	protected function executed() {
		parent::executed();
		
		// forward
		HeaderUtil::redirect('index.php?page=AdminToolsCronjobsList&deleteJob='.$this->cronjobID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
		exit;
	}
}
?>