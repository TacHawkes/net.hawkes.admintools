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
 * Displays the acp menu as a sitemap
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.page
 * @category WCF 
 */
class AdminToolsMenuPage extends AbstractPage {
	public $templateName = 'adminToolsAcpMenuList';
	public $acpMenu;
	public $itemStructure;
	public $deletedItemID = 0;


	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if (isset($_REQUEST['deletedItemID'])) $this->deletedItemID = intval($_REQUEST['deletedItemID']);
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->renderItemStructure();
	}
	
	/**
	 * Creates the basic menu map
	 *
	 */
	public function renderItemStructure() {
		$this->acpMenu = WCFACP::getMenu();

		$this->makeItemStructure();
	}

	/**
	 * Prepares the menu map	 
	 */
	public function makeItemStructure() {
		if(!count($this->acpMenu->getMenuItems())) return;

		$menuItemData = array();
		$i = 0;
		foreach($this->acpMenu->getMenuItems() as $items) {
			foreach($items as $item) {
				$menuItemName = WCF::getLanguage()->get($item['menuItem']);
				$menuItemData[$i] = array($item['parentMenuItem'], $item['menuItem'], $menuItemName, $item['menuItemLink'], $item['menuItemIcon']);
					
				$i++;
			}
		}
		$this->itemStructure = $menuItemData;
	}


	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		//var_dump($this->itemStructure[1]); die;
		WCF::getTPL()->assign(array(
			'items' => $this->itemStructure,
			'deletedItemID' => $this->deletedItemID	
		));
	}


	/**
	 * @see Page::show()
	 */
	public function show() {
		// enable menu item
		WCFACP::getMenu()->setActiveMenuItem('wcf.acp.menu.link.admintools.menu');

		WCF::getUser()->checkPermission('admin.system.admintools.canView');

		parent::show();
	}

}
?>