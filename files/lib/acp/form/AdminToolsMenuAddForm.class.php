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
require_once(WCF_DIR.'lib/acp/form/DynamicOptionListForm.class.php');
require_once(WCF_DIR.'lib/system/language/LanguageEditor.class.php');

/**
 * Adds acp menu items
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF
 */
class AdminToolsMenuAddForm extends DynamicOptionListForm  {
	public $templateName = 'adminToolsAcpMenuAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.menu';
	public $action = 'add';
	public $menuItemSelect = array();
	public $optionsSelect = array();

	public $cacheName = 'group-option-';
	public $additionalFields = array();
	public $options;

	public $menuItem = '';
	public $menuItemLink = '';
	public $menuItemIcon = '';
	public $parentMenuItem = '';
	public $permissions = array();
	public $showOrder = 0;

	public $useiFrame = false;
	public $iframeWidth = "800px";
	public $iframeHeight = "700px";
	public $borderWidth = "1px";
	public $borderColor = "black";
	public $borderStyle = "solid";
	public $borderStyles = array('solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset');

	public $createLangVar = false;

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		WCF::getLanguage()->get(StringUtil::encodeHTML('wcf.acp.group.add.success')); //this line serves as work around for a bug in the language system of the wcf
		$this->options = $this->getOptionTree();
		$this->makeItemSelect();
	}

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['menuItem'])) $this->menuItem = StringUtil::trim($_POST['menuItem']);
		if (isset($_POST['menuItemLink'])) $this->menuItemLink = StringUtil::trim($_POST['menuItemLink']);
		if (isset($_POST['menuItemIcon'])) $this->menuItemIcon = StringUtil::trim(($_POST['menuItemIcon']));
		if (isset($_POST['parentMenuItem'])) $this->parentMenuItem = StringUtil::trim($_POST['parentMenuItem']);
		if (isset($_POST['permissions']) && is_array($_POST['permissions'])) {
			$this->permissions = implode(',',$_POST['permissions']);
		}
		else $this->permissions = '';
		if (isset($_POST['showOrder'])) $this->showOrder = intval($_POST['showOrder']);
		if (isset($_POST['useiFrame'])) $this->useiFrame = intval($_POST['useiFrame']) ? true : false;
		if (isset($_POST['iframeWidth'])) $this->iframeWidth = StringUtil::trim($_POST['iframeWidth']);
		if (isset($_POST['iframeHeight'])) $this->iframeHeight = StringUtil::trim($_POST['iframeHeight']);
		if (isset($_POST['borderWidth'])) $this->borderWidth = StringUtil::trim($_POST['borderWidth']);
		if (isset($_POST['borderColor'])) $this->borderColor = StringUtil::trim($_POST['borderColor']);
		if (isset($_POST['borderStyle'])) $this->borderStyle = $this->borderStyles[intval($_POST['borderStyle'])];
	}

	/**
	 * Creates the selection for parent menu items
	 *
	 * @param string $parentMenuItem
	 * @param integer $depth
	 */
	public function makeItemSelect($parentMenuItem='', $depth=0) {
		$acpMenu = WCFACP::getMenu();
		foreach($acpMenu->getMenuItems($parentMenuItem) as $item) {
			$title = WCF::getLanguage()->get(StringUtil::encodeHTML($item['menuItem']));
			if ($depth > 0) $title = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth). ' ' . $title;
			$this->menuItemSelect[$item['menuItem']] = $title;
			if (count($acpMenu->getMenuItems($item['menuItem'])) && $depth < 2) {
				$this->makeItemSelect($item['menuItem'], $depth+1);
			}
		}
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		AbstractForm::validate();

		if (empty($this->menuItem)) {
			throw new UserInputException('menuItem');
		}

		$this->validateMenuitem();
	}

	/**
	 * Validates if the title of the menu item is valid. If not it triggers the dynamic
	 * creation of a language variable
	 *
	 */
	protected function validateMenuItem() {
		if (WCF::getLanguage()->get($this->menuItem) == $this->menuItem) {
			$this->createLangVar = true;
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();
		$this->showOrder = $this->getShowOrder($this->showOrder, $this->parentMenuItem, 'parentMenuItem');

		$iframeID = 0;
		// save iframe
		if ($this->useiFrame) {
			$sql = "INSERT INTO wcf".WCF_N."_admin_tools_iframe (url, width, height, borderWidth, borderColor, borderStyle)
					VALUES	('".escapeString($this->menuItemLink)."',
							 '".escapeString($this->iframeWidth)."',
							 '".escapeString($this->iframeHeight)."',
							 '".escapeString($this->borderWidth)."',
							 '".escapeString($this->borderColor)."',
							 '".escapeString($this->borderStyle)."')";
			WCF::getDB()->sendQuery($sql);
			$iframeID = WCF::getDB()->getInsertID();
			$this->menuItemLink = 'index.php?page=AdminToolsiFrame&iFrameID='.$iframeID;
		}

		// create menu item
		$sql = "INSERT INTO wcf".WCF_N."_acp_menu_item (packageID, menuItem, menuItemLink, menuItemIcon, permissions, showOrder,  parentMenuItem)
				VALUES	(1,
				 '".escapeString($this->menuItem)."',
				 '".escapeString($this->menuItemLink)."',
				 '".escapeString($this->menuItemIcon)."',
				 '".escapeString($this->permissions)."',
				 ".$this->showOrder.",				 
				 '".escapeString($this->parentMenuItem)."')";
		WCF::getDB()->sendQuery($sql);

		if ($this->useiFrame) {
			$sql = "UPDATE wcf".WCF_N."_admin_tools_iframe SET menuItemID = ".WCF::getDB()->getInsertID()." WHERE iframeID = ".$iframeID;
			WCF::getDB()->sendQuery($sql);
		}

		// create language variable if necessary
		if ($this->createLangVar) {
			$menuItemID = WCF::getDB()->getInsertID();
			$name = 'wcf.acp.menu.menuItem'.$menuItemID;
			$value = $this->menuItem;
			$languages = WCF::getLanguage()->getAvailableLanguages();
			foreach($languages as $language) {
				$langEdit = new LanguageEditor($language['languageID']);
				$langEdit->updateItems(array($name => $value));
			}

			$sql = "UPDATE wcf".WCF_N."_acp_menu_item
							SET menuItem = '".escapeString($name)."'
							WHERE menuItemID = ".$menuItemID;
			WCF::getDB()->sendQuery($sql);
		}

		// reset values
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
	 * Returns the show order value.
	 *
	 * @param	integer		$showOrder
	 * @param	string		$parentName
	 * @param	string		$columnName
	 * @param	string		$tableNameExtension
	 * @return	integer 	new show order
	 */
	protected function getShowOrder($showOrder, $parentName = null, $columnName = null, $tableNameExtension = '') {
		if ($showOrder === null) {
			// get greatest showOrder value
			$sql = "SELECT	MAX(showOrder) AS showOrder
			  	FROM	wcf".WCF_N."_acp_menu_item".$tableNameExtension." 
				".($columnName !== null ? "WHERE ".$columnName." = '".escapeString($parentName)."'" : "");
			$maxShowOrder = WCF::getDB()->getFirstRow($sql);
			if (is_array($maxShowOrder) && isset($maxShowOrder['showOrder'])) {
				return $maxShowOrder['showOrder'] + 1;
			}
			else {
				return 1;
			}
		}
		else {
			// increase all showOrder values which are >= $showOrder
			$sql = "UPDATE	wcf".WCF_N."_acp_menu_item".$tableNameExtension."
				SET	showOrder = showOrder+1
				WHERE	showOrder >= ".$showOrder." 
				".($columnName !== null ? "AND ".$columnName." = '".escapeString($parentName)."'" : "");
			WCF::getDB()->sendQuery($sql);
			// return the wanted showOrder level
			return $showOrder;
		}
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array('menuItemSelect' => $this->menuItemSelect,
									'menuItem' => $this->menuItem,
									'menuItemLink' => $this->menuItemLink,
									'menuItemIcon' => $this->menuItemIcon,
									'parentMenuItem' => $this->parentMenuItem,
									'permissions' => $this->permissions,
									'useiFrame' => $this->useiFrame,
									'iframeWidth' => $this->iframeWidth,
									'iframeHeight' => $this->iframeHeight,
									'borderWidth' => $this->borderWidth,
									'borderColor' => $this->borderColor,
									'borderStyle' => $this->borderStyle,
									'borderStyles' => $this->borderStyles,
									'showOrder' => $this->showOrder,
									'action' => $this->action,
									'options' => $this->options));
	}

	/**
	 * @see Page::show()
	 */
	public function show() {
		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);

		WCF::getUser()->checkPermission('admin.system.admintools.canView');

		// check master password
		WCFACP::checkMasterPassword();

		$this->readCache();

		//WCF::getUser()->checkPermission('admin.headermenu.canAddItem');
		parent::show();
	}

	/**
	 * Returns a list with the options of a specific option category.
	 *
	 * @param	string		$categoryName
	 * @param	boolean		$inherit
	 * @return	array
	 */
	protected function getCategoryOptions($categoryName = '', $inherit = true) {
		$children = array();

		// get sub categories
		if ($inherit && isset($this->cachedCategoryStructure[$categoryName])) {
			foreach ($this->cachedCategoryStructure[$categoryName] as $subCategoryName) {
				$children = array_merge($children, $this->getCategoryOptions($subCategoryName));
			}
		}

		// get options
		if (isset($this->cachedOptionToCategories[$categoryName])) {
			$i = 0;
			$last = count($this->cachedOptionToCategories[$categoryName]) - 1;
			foreach ($this->cachedOptionToCategories[$categoryName] as $optionName) {
				if (!$this->checkOption($optionName) || !isset($this->activeOptions[$optionName])) continue;

				// get option data
				$option = $this->activeOptions[$optionName];

				$option['localizedName'] = WCF::getLanguage()->get('wcf.acp.group.option.'.$option['optionName']);

				// add option to list
				if ($option['optionType'] == 'boolean') {
					$children[] = $option;
				}

				$i++;
			}
		}

		return $children;
	}

	/**
	 * Returns the tree of options.
	 *
	 * @param	string		$parentCategoryName
	 * @param	integer		$level
	 * @return	array
	 */
	protected function getOptionTree($parentCategoryName = '', $level = 0) {
		$options = array();

		if (isset($this->cachedCategoryStructure[$parentCategoryName])) {
			// get super categories
			foreach ($this->cachedCategoryStructure[$parentCategoryName] as $superCategoryName) {
				$superCategory = $this->cachedCategories[$superCategoryName];
				$superCategory['localizedName'] = WCF::getLanguage()->get('wcf.acp.group.option.category.'.$superCategoryName);

				if ($level <= 1) {
					$superCategory['categories'] = $this->getOptionTree($superCategoryName, $level + 1);
				}
				if ($level > 1 || count($superCategory['categories']) == 0) {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName);
				}
				else {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName, false);
				}

				if ((isset($superCategory['categories']) && count($superCategory['categories']) > 0) || (isset($superCategory['options']) && count($superCategory['options']) > 0)) {
					$options[] = $superCategory;
				}
			}
		}

		return $options;
	}

	/**
	 * @see DynamicOptionListForm::getTypeObject()
	 */
	protected function getTypeObject($type) {
		if (!isset($this->typeObjects[$type])) {
			$className = 'GroupOptionType'.ucfirst(strtolower($type));
			$classPath = WCF_DIR.'lib/acp/group/'.$className.'.class.php';

			// include class file
			if (!file_exists($classPath)) {
				throw new SystemException("unable to find class file '".$classPath."'", 11000);
			}
			require_once($classPath);

			// create instance
			if (!class_exists($className)) {
				throw new SystemException("unable to find class '".$className."'", 11001);
			}
			$this->typeObjects[$type] = new $className();
		}

		return $this->typeObjects[$type];
	}
}
?>