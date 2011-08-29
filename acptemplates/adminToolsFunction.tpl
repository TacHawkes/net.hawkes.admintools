{include file='header'}
<script
	type="text/javascript" src="{@RELATIVE_WCF_DIR}js/TabMenu.class.js"></script>
<script type="text/javascript">
	//<![CDATA[	
	var tabMenu = new TabMenu();
	onloadEvents.push(function() { tabMenu.showSubTabMenu("{$activeTabMenuItem}", "{$activeSubTabMenuItem}"); });
	
	function callFunction(functionID, functionName) {
		var confirmMessage = {lang}wcf.acp.admintools.function.execute.confirm{/lang};		
		if(confirm(confirmMessage)) {
        document.forms['functionForm'].elements['functionID'].value = functionID;
        document.forms['functionForm'].submit();
  		}
    }
    
    function toggleAll(categoryName) {
    	var state=false;
		var formLength=document.forms['functionForm'].length;
 			for(i=0;i<formLength;i++) {
 				if(document.forms['functionForm'].elements[i].type == 'checkbox' && !document.forms['functionForm'].elements[i].disabled && document.forms['functionForm'].elements[i].name.indexOf(categoryName) != -1) {
 					if(!document.forms['functionForm'].elements[i].checked) {
 						state = true;
 						break;
 					}
 				}
 			}
 			for(i=0;i<formLength;i++) {
 				if(document.forms['functionForm'].elements[i].type == 'checkbox' && !document.forms['functionForm'].elements[i].disabled && document.forms['functionForm'].elements[i].name.indexOf(categoryName) != -1) {
 					document.forms['functionForm'].elements[i].checked = state;
 				}
 			}
    }			
	//]]>
</script>

<div class="mainHeadline"><img
	src="{@RELATIVE_WCF_DIR}icon/adminToolsFunctionL.png" alt="" />
<div class="headlineContainer">
<h2>{lang}wcf.acp.admintools.functions{/lang}</h2>
</div>
</div>

{if $errorField}
<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if} {if $success|isset && $success === true}
<p class="success">{lang}wcf.acp.admintools.functions.success{/lang}</p>
{elseif $success|isset}
<div class="success">{@$success}</div>
{/if} {if $info|isset}
<div class="info">{@$info}</div>
{/if} {if $warning|isset}
<div class="warning">{@$warning}</div>
{/if} {if $error|isset}
<div class="error">{@$error}</div>
{/if}

<div class="contentHeader">
<div class="largeButtons">
<ul>
	<li><a
		href="index.php?page=AdminTools&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img
		src="{@RELATIVE_WCF_DIR}icon/adminToolsM.png" alt=""
		title="{lang}wcf.acp.menu.link.admintools.index{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.index{/lang}</span></a></li>
</ul>
<ul>
	<li><a
		href="index.php?form=AdminToolsImportAndExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img
		src="{@RELATIVE_WCF_DIR}icon/adminToolsExportM.png" alt=""
		title="{lang}wcf.acp.menu.link.admintools.export{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.export{/lang}</span></a></li>
</ul>
</div>
</div>
<form enctype="multipart/form-data" method="post" name="functionForm"
	action="index.php?form=AdminToolsFunction">

<div class="tabMenu">
<ul>
	{foreach from=$options item=categoryLevel1}
	<li id="{@$categoryLevel1.categoryName}"><a
		onclick="tabMenu.showSubTabMenu('{@$categoryLevel1.categoryName}');"><span>{lang}wcf.acp.admintools.option.category.{@$categoryLevel1.categoryName}{/lang}</span></a></li>
	{/foreach}
</ul>
</div>
<div class="subTabMenu">
<div class="containerHead">{foreach from=$options item=categoryLevel1}
<ul class="hidden" id="{@$categoryLevel1.categoryName}-categories">
	{foreach from=$categoryLevel1.categories item=categoryLevel2}
	<li
		id="{@$categoryLevel1.categoryName}-{@$categoryLevel2.categoryName}"><a
		onclick="tabMenu.showTabMenuContent('{@$categoryLevel1.categoryName}-{@$categoryLevel2.categoryName}');"><span>{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}{/lang}</span></a></li>
	{/foreach}
</ul>
{/foreach}</div>
</div>

{foreach from=$options item=categoryLevel1} {foreach
from=$categoryLevel1.categories item=categoryLevel2}
<div class="border tabMenuContent hidden"
	id="{@$categoryLevel1.categoryName}-{@$categoryLevel2.categoryName}-content">
<div class="container-1">
<h3 class="subHeadline">{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}{/lang}</h3>
<p class="description">{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}.description{/lang}</p>

{if $categoryLevel2.options|isset && $categoryLevel2.options|count}
{include file='optionFieldList' options=$categoryLevel2.options
langPrefix='wcf.acp.admintools.option.'} {/if} {if
$categoryLevel2.categories|isset} {foreach
from=$categoryLevel2.categories item=categoryLevel3}
<fieldset><legend>{lang}wcf.acp.admintools.option.category.{@$categoryLevel3.categoryName}{/lang}</legend>
<p class="description">{lang}wcf.acp.admintools.option.category.{@$categoryLevel3.categoryName}.description{/lang}</p>

<div>{include file='optionFieldList' options=$categoryLevel3.options
langPrefix='wcf.acp.admintools.option.'}</div>
{if $categoryLevel3.showCheckall}
<div class="smallButtons">
<ul>
	<li><a href="javascript: toggleAll('{$categoryLevel3.categoryName}');"><img
		src="{@RELATIVE_WCF_DIR}icon/adminToolsCheckallM.png" alt=""
		title="{lang}wcf.acp.admintools.function.checkall{/lang}" /></a></li>
</ul>
</div>
{/if}</fieldset>
{/foreach} {/if}
<div class="smallButtons formElement">{capture assign=functionName}
{lang}wcf.acp.admintools.option.category.{@$categoryLevel2.categoryName}{/lang}
{/capture} {if $additionalFunctionButtons|isset &&
$additionalFunctionButtons.functionID|isset}{@$additionalFunctionButtons.functionID}{/if}
<ul>
	<li><a
		href="javascript: callFunction({$categoryLevel2.functionID}, '{@$functionName|encodeJS}');"><img
		src="{@RELATIVE_WCF_DIR}icon/adminToolsRunM.png" alt=""
		title="{lang}wcf.acp.admintools.function.run{/lang}" /> <span>{lang}wcf.acp.admintools.function.run{/lang}</span></a></li>
</ul>
{if $categoryLevel2.showCheckall}
<ul>
	<li><a href="javascript: toggleAll('{$categoryLevel2.categoryName}');"><img
		src="{@RELATIVE_WCF_DIR}icon/adminToolsCheckallM.png" alt=""
		title="{lang}wcf.acp.admintools.function.checkall{/lang}" /></a></li>
</ul>
{/if}</div>
</div>
</div>
{/foreach} {/foreach}

<div class="formSubmit"><input type="submit" accesskey="s"
	value="{lang}wcf.global.button.saveSettings{/lang}" /> <input
	type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
<input type="hidden" name="packageID" value="{@PACKAGE_ID}" /> <input
	type="hidden" name="functionID" value="0" /> {@SID_INPUT_TAG} <input
	type="hidden" name="action" value="{@$action}" /> <input type="hidden"
	id="activeTabMenuItem" name="activeTabMenuItem"
	value="{$activeTabMenuItem}" /> <input type="hidden"
	id="activeSubTabMenuItem" name="activeSubTabMenuItem"
	value="{$activeSubTabMenuItem}" /></div>
</form>

{include file='footer'}
