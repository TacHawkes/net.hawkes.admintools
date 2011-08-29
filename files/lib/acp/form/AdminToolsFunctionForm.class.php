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
require_once(WCF_DIR.'lib/acp/admintools/AdminToolsFunctionExecution.class.php');

/**
 * This form allows the dynamic execution of functions or saving function parameters
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF
 */
class AdminToolsFunctionForm extends DynamicOptionListForm {
	public $templateName = 'adminToolsFunction';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.functions';
	public $neededPermissions = 'admin.system.admintools.canView';
	public $cacheName = 'admin_tools-option-';
	public $options;
	public $functions;

	public $functionName = '';
	public $functionID = 0;
	public $activeTabMenuItem = '';
	public $activeSubTabMenuItem = '';
	
	const CHECKALL_LOWER_LIMIT = 1;

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['functionName'])) $this->functionName = StringUtil::trim($_POST['functionName']);
		if (isset($_POST['functionID'])) $this->functionID = intval($_POST['functionID']);
		if (isset($_POST['activeTabMenuItem'])) $this->activeTabMenuItem = $_POST['activeTabMenuItem'];		
	}

	/**
	 * Returns an array with all options that have to be saved
	 *
	 * @return	array
	 */
	protected function getSaveOptions() {
		$options = array();
		$oldOptions = $this->activeOptions;
		foreach($this->options as $category) {
			foreach($category['categories'] as $functionCategory) {
				$function = $this->functions[$functionCategory['functionID']];
				if(!$function['saveSettings']) continue;
				$this->activeOptions = array();
				$this->loadActiveOptions($functionCategory['categoryName']);
				$options = array_merge($options, $this->activeOptions);
			}
		}
		$this->activeOptions = $oldOptions;
		return $options;
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		$executor = AdminToolsFunctionExecution::getInstance();
		$executor->setValues($this->values);
		$saveOptions = $this->getSaveOptions();
		$inserts = '';
		foreach ($saveOptions as $option) {
			if (!empty($inserts)) $inserts .= ',';
			$inserts .= "(".$option['optionID'].", '".escapeString($option['optionValue'])."')";
		}

		if (!empty($inserts)) {
			$sql = "INSERT INTO	wcf".WCF_N."_admin_tools_option
						(optionID, optionValue)
				VALUES 		".$inserts."
				ON DUPLICATE KEY UPDATE optionValue = VALUES(optionValue)";
			WCF::getDB()->sendQuery($sql);
			WCF::getCache()->clear(WCF_DIR.'cache/', 'cache.admin_tools-option*');
		}

		if($this->functionID) {			
			$executor->callFunction($this->functionID);

			foreach($this->options as $superCategory) {
				foreach($superCategory['categories'] as $functionCategory) {
					if($functionCategory['functionID'] == $this->functionID) {
						$this->activeTabMenuItem = $superCategory['categoryName'];
						$this->activeSubTabMenuItem = $this->activeTabMenuItem.'-'.$functionCategory['categoryName'];
					}
				}
			}

			$returnMessages = WCF::getSession()->getVar('functionReturnMessage');
			if(isset($returnMessages[$this->functionID])) {
				$functionMessage = $returnMessages[$this->functionID];
				unset($returnMessages[$this->functionID]);
				WCF::getSession()->register('functionReturnMessage', $returnMessages);
				WCF::getTPL()->assign($functionMessage);
			}
			else {
				WCF::getTPL()->assign(array(
					'success' => true,
				));
			}
		}
		else {
			WCF::getTPL()->assign(array(
			'success' => true,
			));
		}
	}

	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();

		$this->options = $this->getOptionTree();
		if (!count($_POST)) {
			$this->activeTabMenuItem = $this->options[0]['categoryName'];
		}
	}

	/**
	 * @see Form::submit()
	 */
	public function submit() {
		$this->options = $this->getOptionTree();
		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$this->functions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);

		parent::submit();
	}

	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'options' 		=> $this->options,			
			'activeTabMenuItem' 	=> $this->activeTabMenuItem,
			'activeSubTabMenuItem' 	=> $this->activeSubTabMenuItem			
		));
	}

	/**
	 * @see Form::show()
	 */
	public function show() {
		// set active menu item
		WCFACP::getMenu()->setActiveMenuItem($this->activeMenuItem);
		
		// check permission
		WCF::getUser()->checkPermission('admin.system.admintools.canView');

                // check master password
		WCFACP::checkMasterPassword();

		// get user options and categories from cache
		$this->readCache();

		// show form
		parent::show();
	}
	
	/**
	 * @see DynamicOptionListForm::readCache()
	 */
	protected function readCache() {
		parent::readCache();
		
		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$this->functions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);
                if (!count($this->functions)) {
                        throw new NamedUserException(WCF::getLanguage('wcf.acp.admintools.functions.none'));
                }
	}

	/**
	 * Creates a list of all active options.
	 *
	 * @param	string		$parentCategoryName
	 */
	protected function loadActiveOptions($parentCategoryName) {
		if (isset($this->cachedOptionToCategories[$parentCategoryName])) {
			foreach ($this->cachedOptionToCategories[$parentCategoryName] as $optionName) {
				if (!$this->checkOption($optionName)) continue;
				$this->activeOptions[$optionName] =& $this->cachedOptions[$optionName];
			}
		}
		if (isset($this->cachedCategoryStructure[$parentCategoryName])) {
			foreach ($this->cachedCategoryStructure[$parentCategoryName] as $categoryName) {
				$this->loadActiveOptions($categoryName);
			}
		}
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

				if ($level <= 1) {
					$superCategory['categories'] = $this->getOptionTree($superCategoryName, $level + 1);
				}

				if ($level > 1 || count($superCategory['categories']) == 0) {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName);
					$superCategory['showCheckall'] = $this->showCheckAllButton($superCategory);
				}
				else {
					$superCategory['options'] = $this->getCategoryOptions($superCategoryName, false);
					$superCategory['showCheckall'] = $this->showCheckAllButton($superCategory);
				}


				if ((isset($superCategory['categories']) && count($superCategory['categories']) > 0) || (isset($superCategory['options']) && count($superCategory['options']) > 0)) {
					$options[] = $superCategory;
				}
			}
		}

		return $options;
	}
	
	/**
	 * Determines if the passed category is a checkbox category and displays a 'check all' button
	 *
	 * @param array $category
	 * @return boolean
	 */
	protected function showCheckAllButton($category) {
		if (!count($category['options'])) return false;
		if (isset($this->functions[$category['functionID']])) {
			if ($this->functions[$category['functionID']]['saveSettings']) return false;
		}
		$countBoxes = 0;
		foreach($category['options'] as $option) {
			if($option['optionType'] == 'boolean') {
				$countBoxes++;
			}			
		}		
		if ($countBoxes > self::CHECKALL_LOWER_LIMIT) return true;
		
		return false;
	}

	/**
	 * @see DynamicOptionListForm::getTypeObject()
	 */
	protected function getTypeObject($type) {
		if (!isset($this->typeObjects[$type])) {
			$className = 'OptionType'.ucfirst(strtolower($type));
			$classPath = WCF_DIR.'lib/acp/option/'.$className.'.class.php';

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