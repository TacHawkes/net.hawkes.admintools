<?php
require_once(WCF_DIR.'lib/action/AbstractAction.class.php');

/**
 * 	 Exports admin tools spiders
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
 * @subpackage acp.action
 * @category WCF
 */
class AdminToolsSpiderExportAction extends AbstractAction {

	/**
	 * @see Action::execute();
	 */
	public function execute() {
		parent::execute();

		// header
		@header('Content-type: text/xml');

		// file name
		@header('Content-disposition: attachment; filename="spiders.xml"');
			
		// no cache headers
		@header('Pragma: no-cache');
		@header('Expires: 0');

		// content
		echo "<?xml version=\"1.0\" encoding=\"".CHARSET."\"?>\n<spiders>\n";

		$spiders = $this->getSpiders();
		foreach ($spiders as $spider) {
			echo "\t<spider>\n";
			echo "\t\t<spiderName><![CDATA[".StringUtil::escapeCDATA($spider['spiderName'])."]]></spiderName>\n";
			echo "\t\t<spiderIdentifier><![CDATA[".StringUtil::escapeCDATA($spider['spiderIdentifier'])."]]></spiderIdentifier>\n";
			echo "\t\t<spiderUrl><![CDATA[".StringUtil::escapeCDATA($spider['spiderURL'])."]]></spiderUrl>\n";			
			echo "\t</spider>\n";
		}

		echo '</spiders>';
		$this->executed();
		exit;
	}

	/**
	 * Returns a list of spiders.
	 *	 
	 * @return	array
	 */
	public function getSpiders() {
		$sql = "SELECT * FROM wcf".WCF_N."_admin_tools_spider";				
		return WCF::getDB()->getResultList($sql);
	}
}
?>