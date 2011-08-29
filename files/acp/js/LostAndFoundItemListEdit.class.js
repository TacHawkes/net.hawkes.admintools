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
 
/**
 * Handles the JS item actions
 * 
 * @author	Oliver Kliebisch
 * @copyright	2009 Oliver Kliebisch
 * @license	GNU General Public License <http://www.gnu.org/licenses/>
 * @package	net.hawkes.admintools
 * @subpackage acp.js
 * @category WCF 
 */
function LostAndFoundListEdit(data, count, page, classname) {
	this.data = data;
	this.count = count;
	this.page = page;	
	this.classname = classname;
	
	/**
	 * Saves the marked status.
	 */
	this.saveMarkedStatus = function(data) {
		var ajaxRequest = new AjaxRequest();		
		ajaxRequest.openPost('index.php?page=AdminToolsLostAndFoundAction&pagename='+this.page+'&classname='+this.classname+'&packageID='+PACKAGE_ID+SID_ARG_2ND, data);
	}
	
	/**
	 * Returns a list of the edit options for the edit menu.
	 */
	this.getEditOptions = function(id) {
		var options = new Array();
		var i = 0;
					
		
			// delete
			//if (permissions['canDeleteItem']) {
				options[i] = new Object();
				options[i]['function'] = 'itemListEdit.remove('+id+');';
				options[i]['text'] = language['wcf.global.button.delete'];
				i++;
		//	}
					
			// marked status
		//	if (permissions['canMarkItem']) {
				var markedStatus = this.data[id] ? this.data[id]['isMarked'] : false;
				options[i] = new Object();
				options[i]['function'] = 'itemListEdit.parentObject.markItem(' + (markedStatus ? 'false' : 'true') + ', '+id+');';
				options[i]['text'] = markedStatus ? language['wcf.global.button.unmark'] : language['wcf.global.button.mark'];
				i++;
		//	}
		
				
		return options;
	}
	
	/**
	 * Returns a list of the edit options for the edit marked menu.
	 */
	this.getEditMarkedOptions = function() {
		var options = new Array();
		var i = 0;
		
		// delete
		//if (permissions['canDeleteItem']) {
			options[i] = new Object();
			options[i]['function'] = 'itemListEdit.removeAll();';
			options[i]['text'] = language['wcf.global.button.delete'];
			i++;
		//}
		
		// unmark all
		options[i] = new Object();
		options[i]['function'] = 'itemListEdit.unmarkAll();';
		options[i]['text'] = language['wcf.global.button.unmark'];
		i++;
		
		
		return options;
	}
	
	/**
	 * Returns the title of the edit marked menu.
	 */
	this.getMarkedTitle = function() {
		return eval(language['wcf.acp.admintools.lostandfound.markedItems']);
	}
	
	
	
	/**
	 * Deletes an item.
	 */
	this.remove = function(id) {
		if (confirm(language['wcf.acp.admintools.delete.sure'])) {
				document.location.href = fixURL('index.php?page=AdminToolsLostAndFoundAction&pagename='+this.page+'&classname='+this.classname+'&action=delete&itemID='+id+'&url='+encodeURIComponent(url)+SID_ARG_2ND);
		}
	}
	
	
	/**
	 * Deletes the marked items.
	 */
	this.removeAll = function() {
		if (confirm(language['wcf.acp.admintools.delete.sure'])) {
			document.location.href = fixURL('index.php?page=AdminToolsLostAndFoundAction&pagename='+this.page+'&classname='+this.classname+'&action=deleteAll&url='+encodeURIComponent(url)+SID_ARG_2ND);
		}
	}	
	
	/**
	 * Ummarked all marked items.
	 */
	this.unmarkAll = function() {
		var ajaxRequest = new AjaxRequest();
		ajaxRequest.openGet('index.php?page=AdminToolsLostAndFoundAction&pagename='+this.page+'&classname='+this.classname+'&action=unmarkAll'+SID_ARG_2ND);
		
		// checkboxes
		this.count = 0;
		for (var id in this.data) {
			this.data[id]['isMarked'] = 0;
			var checkbox = document.getElementById(this.page + 'Mark' + id);
			if (checkbox) {
				checkbox.checked = false;
			}
			
			this.showStatus(id);
		}
		
		// mark all checkbox
		this.parentObject.checkMarkAll(false);
		
		// edit marked menu
		this.parentObject.showMarked();
	}
	
	
	/**
	 * Show the status of a item.
	 */
	this.showStatus = function(id) {
		// get row
		var row = document.getElementById(this.page + 'Row'+id);
		
		// update css class
		if (row) {
			// get class
			var className = row.className;

			// remove all classes except first one
			//className = className.replace(/ .*/, '');
			
			// original className
			if (this.data[id]['class'] != className) {
				className = this.data[id]['class'];
			}
								
			
			// marked
			if (this.data[id]['isMarked']) {
				className += ' marked';
			}
			
			row.className = className;
		}
		
	}
	

	this.parentObject = new InlineListEdit(this.page, this);
}