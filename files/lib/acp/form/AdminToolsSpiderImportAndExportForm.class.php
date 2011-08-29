<?php
require_once(WCF_DIR.'lib/acp/form/ACPForm.class.php');

/**
 * 	 Imports and exports admin tools function options
 *
 *
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
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.form
 * @category WCF
 */
class AdminToolsSpiderImportAndExportForm extends ACPForm {
	public $templateName = 'adminToolsSpiderImportAndExport';
	public $activeMenuItem = 'wcf.acp.menu.link.admintools.spider';
	public $neededPermissions = 'admin.system.admintools.canView';

	// parameters
	public $spiderImport = null;
	public $oldAdminTools = false;

	// data
	public $spiders = array();

	/**
	 * @see Form::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();

		if (isset($_FILES['spiderImport'])) $this->spiderImport = $_FILES['spiderImport'];
		if (isset($_POST['oldAdminTools'])) $this->oldAdminTools = intval($_POST['oldAdminTools']) ? true : false;
	}

	/**
	 * @see Form::validate()
	 */
	public function validate() {
		parent::validate();

		// upload
		if ($this->spiderImport && $this->spiderImport['error'] != 4) {
			if ($this->spiderImport['error'] != 0) {
				throw new UserInputException('spiderImport', 'uploadFailed');
			}

			if ($this->oldAdminTools) {
				$csv = file($this->spiderImport['tmp_name']);
				if(count($csv)) {
					foreach($csv as $line) {
						$line = trim($line);
						if(preg_match('/^"/', $line)) {
							$spiderIdentifier = $spiderName = $spiderURL = '';
							list($spiderIdentifier, $spiderName, $spiderURL) = preg_split('/";"/', $line, 3);
							$spiderIdentifier = preg_replace('/^"/', '', $spiderIdentifier);
							if($spiderURL) $spiderURL = preg_replace('/"$/', '', $spiderURL);
							if(!empty($spiderIdentifier) && !empty($spiderName)) {
								$this->spiders[$spiderIdentifier] = array($spiderIdentifier, $spiderName, $spiderURL);
							}
						}
					}
				}
			}
			else {
				try {
					$xml = new XML($this->spiderImport['tmp_name']);
					$spidersXML = $xml->getElementTree('spiders');
					foreach ($spidersXML['children'] as $spider) {
						$name = $value = '';
						foreach ($spider['children'] as $spiderData) {
							switch ($spiderData['name']) {
								case 'spiderName':
									$name = $spiderData['cdata'];
									break;
								case 'spiderIdentifier':
									$identifier = $spiderData['cdata'];
									break;
								case 'spiderUrl' :
									$url = $spiderData['cdata'];
							}
						}
							
						if (!empty($name)) {
							$this->spiders[$identifier] = array($identifier, $name, $url);
						}
					}
				}
				catch (SystemException $e) {
					throw new UserInputException('spiderImport', 'importFailed');
				}
			}
		}
		else {
			throw new UserInputException('spiderImport');
		}
	}

	/**
	 * @see Form::save()
	 */
	public function save() {
		parent::save();

		if(count($this->spiders)) {
			$sql = "TRUNCATE TABLE wcf".WCF_N."_admin_tools_spider";
			WCF::getDB()->sendQuery($sql);
				
			$inserts = '';
			foreach ($this->spiders as $spider) {
				$identifier = $spider[0];
				$name = $spider[1];
				$url = $spider[2];
				if (!empty($inserts)) $inserts .= ',';
				$inserts .= "('".escapeString(StringUtil::toLowerCase($identifier))."', '".escapeString($name)."', '".escapeString($url)."')";
			}
			if (!empty($inserts)) {
				$sql = "INSERT IGNORE INTO	wcf".WCF_N."_admin_tools_spider
								(spiderIdentifier, spiderName, spiderURL)
					VALUES			".$inserts;
				WCF::getDB()->sendQuery($sql);
			}
		}
		$this->saved();

		// show success message
		WCF::getTPL()->assign('success', true);
	}

}
?>