<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/InlineListEdit.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}acp/js/LostAndFoundItemListEdit.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/StringUtil.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	// data array
	var itemData = new Array();
	
	// ids	
	var itemID = {if $item|isset}{@$item->itemID}{else}0{/if};
		

	// language
	language['wcf.global.button.mark']					= '{lang}wcf.global.button.mark{/lang}';
	language['wcf.global.button.unmark'] 				= '{lang}wcf.global.button.unmark{/lang}';
	language['wcf.global.button.delete'] 				= '{lang}wcf.global.button.delete{/lang}';		
	language['wcf.acp.admintools.lostandfound.markedItems'] 		= '{lang}wcf.acp.admintools.lostandfound.markedItems{/lang}';	
	language['wcf.acp.admintools.delete.sure'] 		= '{lang}wcf.acp.admintools.delete.sure{/lang}';
	
	// permissions
	var permissions = new Object();
	
	
	// item editing	
	permissions['canMarkItem'] = 1;		
	
	// init
	onloadEvents.push(function() {
		itemListEdit = new LostAndFoundListEdit(itemData, {$markedItems}, '{$jsname}', '{$classname}');		
	});
	//]]>
</script>