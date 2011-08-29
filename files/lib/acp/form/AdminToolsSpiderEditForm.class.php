<?php
require_once(WCF_DIR.'lib/acp/form/AdminToolsSpiderAddForm.class.php');

/**
 * Edits spiders
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
class AdminToolsSpiderEditForm extends AdminToolsSpiderAddForm  {	
	public $action = 'edit';
	public $neededPermissions = 'admin.system.admintools.canView';
	
	public $spider;
	public $spiderID = 0;
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['spiderID'])) $this->spiderID = intval($_REQUEST['spiderID']);
		else {
			require_once(WCF_DIR.'lib/system/exception/IllegalLinkException.class.php');
			throw new IllegalLinkException();
		}
		
		$this->spider = new SpiderEditor($this->spiderID);
	}
	
	/**
	 * @see Page::readData()
	 */
	public function readData() {
		parent::readData();
		
		if(!count($_POST)) {
			$this->spiderName = $this->spider->spiderName;
			$this->spiderIdentifier = $this->spider->spiderIdentifier;
			$this->spiderURL = $this->spider->spiderURL; 
		}
	}

	/**
	 * @see Form::save()	 
	 */
	public function save() {
		ACPForm::save();

		$this->spider->update($this->spiderIdentifier, $this->spiderName, $this->spiderURL);
		
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

		WCF::getTPL()->assign(array('spiderID' => $this->spiderID));	
	}

}
?>