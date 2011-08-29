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
require_once(WCF_DIR.'lib/acp/form/CronjobsAddForm.class.php');

/**
 * An extend cronjobs add form which allows to create cronjobs with functions as payload
 *
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF 
 */
class AdminToolsCronjobsAddForm extends CronjobsAddForm {		
	public $templateName = 'adminToolsCronjobsAdd';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.cronjobs';
	public $neededPermissions = 'admin.system.admintools.canView';
	public $functions = array();
	public $activeFunctions = array();
	public $wcfCronjob = 0;
	
	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
				
		if (isset($_POST['wcfCronjob'])) {
			$this->wcfCronjob = intval($_POST['wcfCronjob']);
			if($this->wcfCronjob) {
				$this->packageID = 1;
			}
		}
		
		if(isset($_POST['functions']) && is_array($_POST['functions'])) $this->activeFunctions = ArrayUtil::toIntegerArray($_POST['functions']);
	}
	
	/**
	 * @see Page::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		WCF::getCache()->addResource('admin_tools_functions-'.PACKAGE_ID, WCF_DIR.'cache/cache.admin_tools_functions-'.PACKAGE_ID.'.php', WCF_DIR.'lib/system/cache/CacheBuilderAdminToolsFunction.class.php');
		$this->functions = WCF::getCache()->get('admin_tools_functions-'.PACKAGE_ID);		
		foreach($this->functions as $key => $function) {
			if(!$function['executeAsCronjob']) unset($this->functions[$key]);
		}
	}
	
	/**
	 * @see Page::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array('functions' => $this->functions,
									'activeFunctions' => $this->activeFunctions,
									'wcfCronjob' => $this->wcfCronjob));
	}
	
	/**
	 * @see Form::validate()
	 */
	public function validate() {
		try {
			parent::validate();
		}
		catch(UserInputException $e) {
			$errorField = $e->getField();
			$errorType = $e->getType();
			
			if($errorField != 'classPath') {
				throw new UserInputException($errorField, $errorType);
			}
		}
		
		if($this->wcfCronjob) {			
			foreach($this->activeFunctions as $functionID) {							
				if(!empty($this->functions[$functionID]['packageDir'])) unset($this->activeFunctions[$functionID]);
			}
		}				
		
		if(!count($this->activeFunctions)) {
			throw new UserInputException();
		}
		
	}
	
	/**
	 * @see Form::save()
	 */
	public function save() {		
		ACPForm::save();
		
		// save cronjob
		CronjobEditor::create($this->classPath, $this->packageID, $this->description, $this->execMultiple, $this->startMinute, $this->startHour, $this->startDom, $this->startMonth, $this->startDow);		
		
		$sql = "SELECT cronjobs.cronjobID, package.packageDir FROM wcf".WCF_N."_cronjobs cronjobs
				LEFT JOIN wcf".WCF_N."_package package
				ON (package.packageID = cronjobs.packageID)
				 WHERE cronjobs.classPath = '".$this->classPath."'				 
				 AND cronjobs.packageID = ".$this->packageID;
		$row = WCF::getDB()->getFirstRow($sql);
		$cronjobID = $row['cronjobID'];
		$cronjob = new CronjobEditor($cronjobID);		
		$cronjob->update('lib/system/cronjob/AdminToolsCronjob'.$cronjobID.'.class.php', $this->packageID, $this->description, $this->execMultiple, $this->startMinute, $this->startHour, $this->startDom, $this->startMonth, $this->startDow);
		
		$inserts = '';
		foreach($this->activeFunctions as $functionID) {
			if(!empty($inserts)) $inserts .= ',';
			$inserts .= '('.$functionID.', '.$cronjobID.')';
		}
		$sql = "INSERT IGNORE INTO wcf".WCF_N."_admin_tools_function_to_cronjob
					(functionID, cronjobID)
					VALUES ".$inserts;
		WCF::getDB()->sendQuery($sql);		
		$path = FileUtil::getRealPath(WCF_DIR.$row['packageDir']);
		$fileName = $path.'lib/system/cronjob/AdminToolsCronjob'.$cronjobID.'.class.php';
		$this->writeCronjob($cronjobID, $fileName);
		
		$this->saved();
		
		// reset values
		$this->classPath = $this->description = '';
		$this->execMultiple = 0;
		$this->startMinute = $this->startHour = $this->startDom = $this->startMonth = $this->startDow = '*';
		
		// show success.
		WCF::getTPL()->assign(array(
			'success' => true
		));
		
		$this->activeFunctions = array();
		$this->wcfCronjob = 0;
	}
	
	/**
	 * Writes the cronjob to the file system
	 * 
	 * @param Integer $cronjobID
	 * @param String  $filename
	 */
	protected function writeCronjob($cronjobID, $filename) {
		$output = "<?php\n/**\n* Admin Tools \n* Cronjob: ".$cronjobID."\n* Compiled at: ".gmdate('r')."\n* \n* DO NOT EDIT THIS FILE\n*/\n";
		$output .= "require_once(WCF_DIR.'lib/data/cronjobs/Cronjob.class.php');\n";
		$output .= "require_once(WCF_DIR.'lib/acp/admintools/AdminToolsFunctionExecution.class.php');\n\n";
		$output .= "class AdminToolsCronjob".$cronjobID." implements Cronjob {\n";
		$output .= "\t/**\n";
		$output .= "\t* @see Cronjob::execute()\n";
		$output .= "\t*/\n";
		$output .= "\tpublic function execute(\$data) {\n";
		$output .= "\t\t\$functionIDs = array(".implode(',', $this->activeFunctions).");\n";
		$output .= "\t\t\$executor = AdminToolsFunctionExecution::getInstance();\n";
		$output .= "\t\tforeach(\$functionIDs as \$functionID) {\n";
		$output .= "\t\t\t\$executor->callFunction(\$functionID);\n";
		$output .= "\t\t}\n";
		$output .=" \t}\n";
		$output .="}\n";
		$output .="?>";
		
		require_once(WCF_DIR.'lib/system/io/File.class.php');
		$file = new File($filename);
		$file->write($output);
		chmod($filename, 0777);
		$file->close();
	}
}
?>