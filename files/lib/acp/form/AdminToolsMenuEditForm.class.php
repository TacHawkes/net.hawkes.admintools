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
require_once(WCF_DIR.'lib/acp/form/AdminToolsMenuAddForm.class.php');

/**
 * Edits acp menu items
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF 
 */
class AdminToolsMenuEditForm extends AdminToolsMenuAddForm {
	public $action = 'edit';
	public $menuItemID;
	public $iframeID = 0;
	public $deleteItem = false;
	
	/**
	 * @see Page::readParameters()	 
	 */
	public function readParameters() {
		parent::readParameters();

		if(isset($_GET['menuItem'])) {
			$menuItem = StringUtil::trim(($_REQUEST['menuItem']));
			$sql = "SELECT		menuItem, menuItemID
			FROM		wcf".WCF_N."_acp_menu_item menu_item,
					wcf".WCF_N."_package_dependency package_dependency
			WHERE 		menu_item.packageID = package_dependency.dependency
					AND package_dependency.packageID = ".PACKAGE_ID."
					AND menu_item.menuItem = '".$menuItem."'
			ORDER BY	package_dependency.priority";
			$row = WCF::getDB()->getFirstRow($sql);
			$this->menuItemID = $row['menuItemID'];
		}
		if(isset($_POST['menuItemID'])) {
			$this->menuItemID = intval($_POST['menuItemID']);
		}
		if(isset($_POST['iframeID'])) {
			$this->iframeID = intval($_POST['iframeID']);
		}				
		
		if(!$this->menuItemID) {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @see Form::readFormParamerters()	 
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		if(isset($_POST['deleteItem'])) {
			$this->deleteItem = $_POST['deleteItem'] ? true : false;
			
			if($this->deleteItem) {
				$sql = "DELETE FROM wcf".WCF_N."_acp_menu_item WHERE menuItemID = ".$this->menuItemID;
				WCF::getDB()->sendQuery($sql);
				
				$sql = "DELETE FROM wcf".WCF_N."_admin_tools_iframe WHERE menuItemID = ".$this->menuItemID;
				WCF::getDB()->sendQuery($sql);
				
				WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.menu-*');
				
				HeaderUtil::redirect('index.php?page=AdminToolsMenu&deletedItemID='.$this->menuItemID.'&packageID='.PACKAGE_ID.SID_ARG_2ND_NOT_ENCODED);
				exit;
			}
		}
	}

	/**
	 * @see Page::readData()	 
	 */
	public function readData() {
		parent::readData();

		//no cache use here because we need a clean item
		$sql = "SELECT item.*, iframe.* FROM wcf".WCF_N."_acp_menu_item item
				LEFT JOIN wcf".WCF_N."_admin_tools_iframe iframe
				ON (iframe.menuItemID = item.menuItemID)  WHERE item.menuItemID=".$this->menuItemID;
		$item = WCF::getDB()->getFirstRow($sql);
		if(is_array($item)) {
			$this->menuItem = StringUtil::trim($item['menuItem']);			
			$this->menuItemLink = $item['menuItemLink'];
			$this->menuItemIcon = $item['menuItemIcon'];
			$this->showOrder = $item['showOrder'];
			$this->parentMenuItem = StringUtil::trim($item['parentMenuItem']);
			$this->permissions = explode(',',$item['permissions']);
			if(!empty($item['url'])) {
				$this->iframeWidth = $item['width'];
				$this->iframeHeight = $item['height'];
				$this->menuItemLink = $item['url'];
				$this->useiFrame = true;
				$this->iframeID = $item['iframeID'];
				$this->borderWidth = $item['borderWidth'];
				$this->borderColor = $item['borderColor'];
				$this->borderStyle = $item['borderStyle'];
				$flippedStyles = array_flip($this->borderStyles);
				$this->borderStyle = $flippedStyles[$this->borderStyle];
			}
		}
		else {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		
		if(isset($this->menuItemSelect[$this->menuItem])) {
			unset($this->menuItemSelect[$this->menuItem]);
		}

	}

	/**
	 * @see Form::save()	 
	 */
	public function save() {
		AbstractForm::save();
		$this->showOrder = $this->getShowOrder($this->showOrder, $this->parentMenuItem, 'parentMenuItem');

		if($this->useiFrame) {
			$sql = "UPDATE wcf".WCF_N."_admin_tools_iframe SET
						url = '".$this->menuItemLink."',
						width = '".escapeString($this->iframeWidth)."',
						height = '".escapeString($this->iframeHeight)."',
						borderWidth = '".escapeString($this->borderWidth)."',
						borderColor = '".escapeString($this->borderColor)."',
						borderStyle = '".escapeString($this->borderStyle)."'
						WHERE iframeID = ".$this->iframeID;
			WCF::getDB()->sendQuery($sql);
			$this->menuItemLink = 'index.php?page=AdminToolsiFrame&iFrameID='.$this->iframeID;
		}

		if($this->createLangVar) {
			$menuItemID = WCF::getDB()->getInsertID();
			$name = 'wcf.acp.menu.menuItem'.$menuItemID;
			$value = $this->menuItem;
			$languages = WCF::getLanguage()->getAvailableLanguages();
			foreach($languages as $language) {
				$langEdit = new LanguageEditor($language['languageID']);
				$langEdit->updateItems(array($name => $value));
			}

		}

		$sql = "SELECT menuItem FROM wcf".WCF_N."_acp_menu_item
                 			WHERE menuItemID=".$this->menuItemID;
		$row = WCF::getDB()->getFirstRow($sql);		
		if($row['menuItem'] != $this->menuItem) {
			//	relink children
			$sql = "UPDATE wcf".WCF_N."_acp_menu_item
				SET parentMenuItem='".escapeString($this->menuItem)."'
				WHERE parentMenuItem='".$row['menuItem']."'";
			WCF::getDB()->sendQuery($sql);
		}
			
		//update the item
		$sql = "UPDATE IGNORE wcf".WCF_N."_acp_menu_item SET
				  menuItem = '".escapeString($this->menuItem)."',
				  menuItemLink = '".escapeString($this->menuItemLink)."',
				  menuItemIcon ='".escapeString($this->menuItemIcon)."',
				  permissions ='".escapeString($this->permissions)."',
				  showOrder = ".$this->showOrder.",
				  parentMenuItem = '".escapeString($this->parentMenuItem)."'
				  WHERE menuItemID=".$this->menuItemID;				
		WCF::getDB()->sendQuery($sql);

		$this->menuItem = $this->menuItemLink = $this->menuItemIcon = $this->parentMenuItem = $this->iframeHeight = $this->iframeWidth = $this->borderWidth = $this->borderColor = $this->borderStyle = '';
		$this->permissions = array();
		$this->showOrder = 0;

		WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.menu-*');

		$this->saved();

		WCF::getTPL()->assign(array(
			'success' => true
		));

	}

	/**
	 * @see Page::assignVariables()	 
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array('menuItemID' => $this->menuItemID,
									'iframeID' => $this->iframeID));
	}

	/**
	 * @see Page::show()	 
	 */
	public function show() {

		parent::show();
	}
}
?>