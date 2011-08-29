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
require_once(WCF_DIR.'lib/page/AbstractPage.class.php');

/**
 * Displays iFrame pages
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsiFramePage extends AbstractPage {
	public $templateName = 'adminToolsiFrame';
	public $iFrameID = 0;
	public $iFrameData = array();
	
	/**
	 * @see Page::readParameters()	 
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_GET['iFrameID'])) $this->iFrameID = intval($_GET['iFrameID']);
		else {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Page::readData()	 
	 */
	public function readData() {
		parent::readData();
		
		$sql = "SELECT item.menuItem, iframe.* FROM wcf".WCF_N."_admin_tools_iframe iframe
				LEFT JOIN			wcf".WCF_N."_acp_menu_item item
				ON (item.menuItemID = iframe.menuItemID)
				WHERE iframeID = ".$this->iFrameID;
		$this->iFrameData = WCF::getDB()->getFirstRow($sql);
	}
	
	/**
	 * @see Page::assignVariables()	 
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCFACP::getMenu()->setActiveMenuItem($this->iFrameData['menuItem']);
		
		WCF::getTPL()->assign(array('iFrameData' => $this->iFrameData));
	}

	/**
	 * @see Page::show()	 
	 */
	public function show() {
		WCF::getUser()->checkPermission('admin.system.admintools.canView');

		parent::show();
	}
}
?>