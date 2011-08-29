<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');
require_once(WCF_DIR.'lib/acp/admintools/spider/SpiderEditor.class.php');

/**
 * Adds spiders
 *
 * This file is part of Admin Tools 2.
 *
 * Admin Tools 2 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Admin Tools 2 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Admin Tools 2.  If not, see <http://www.gnu.org/licenses/>.
 *
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF 
 */
class AdminToolsSpiderAddForm extends ACPForm  {
	public $templateName = 'adminToolsSpiderAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.spider';
	public $neededPermissions = 'admin.system.admintools.canView';
	public $action = 'add';
	
	public $spiderName = '';
	public $spiderIdentifier = '';
	public $spiderURL = '';
	
	/**
	 * @see Form::readFormParameters()	 
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_POST['spiderName'])) $this->spiderName = StringUtil::trim($_POST['spiderName']);
		if (isset($_POST['spiderIdentifier'])) $this->spiderIdentifier = StringUtil::trim($_POST['spiderIdentifier']);
		if (isset($_POST['spiderURL'])) $this->spiderURL = StringUtil::trim($_POST['spiderURL']);
	}

	/**
	 * @see Form::save()	 
	 */
	public function save() {
		parent::save();

		SpiderEditor::create($this->spiderIdentifier, $this->spiderName, $this->spiderURL);

		$this->spiderName = $this->spiderIdentifier = $this->spiderURL = '';

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

		WCF::getTPL()->assign(array('spiderName' => $this->spiderName,
									'spiderIdentifier' => $this->spiderIdentifier,
									'spiderURL' => $this->spiderURL));	
	}

}
?>