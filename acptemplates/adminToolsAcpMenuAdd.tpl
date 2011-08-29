{include file='header'}
<script type="text/javascript">
//<![CDATA[
function toggleiFrame() {
	var fieldset = document.getElementById('iFrameFieldset');
	if(document.getElementById('useiFrame').checked == true) {		
		fieldset.style.display = null;
	}
	else {
		fieldset.style.display = 'none';
	}
}
//]]>
</script>
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.acpmenu.{$action}{/lang}</h2>
		{if $menuItem|isset}<p>{lang}{$menuItem}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset && $success}
	<p class="success">{lang}wcf.acp.admintools.acpmenu.{$action}.success{/lang}</p>	
{/if}


<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=AdminToolsMenu&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.menu{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.menu{/lang}</span></a></li></ul>
	</div>
</div>
<form enctype="multipart/form-data" method="post" action="index.php?form=AdminToolsMenu{$action|ucfirst}">
	
	<div class="border content">
		<div class="container-1">
			
			{if $action == 'edit'}
			<fieldset>
				<legend>{lang}wcf.acp.admintools.acpmenu.delete{/lang}</legend>

				<div class="formElement" id="deleteItemDiv">
					<div class="formField">
						<label><input type="checkbox" id="deleteItem" name="deleteItem" value="1"/> {lang}wcf.acp.admintools.acpmenu.deleteItem{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="deleteItemHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.deleteItem.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('deleteItem');
				//]]></script>
			</fieldset>
			{/if}
		
			<fieldset>
				<legend>{lang}wcf.acp.admintools.acpmenu.data{/lang}</legend>		
				
				<div class="formElement{if $errorField == 'menuItem'} formError{/if}" id="menuItemDiv">
					<div class="formFieldLabel">
						<label for="menuItem">{lang}wcf.acp.admintools.acpmenu.menuItem{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="menuItem" id="menuItem" value="{$menuItem}" />
						{if $errorField == 'menuItem'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="menuItemHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.menuItem.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('menuItem');
				//]]></script>
				
				<div class="formElement{if $errorField == 'menuItemLink'} formError{/if}" id="menuItemLinkDiv">
					<div class="formFieldLabel">
						<label for="menuItemLink">{lang}wcf.acp.admintools.acpmenu.menuItemLink{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="menuItemLink" id="menuItemLink" value="{$menuItemLink}" />
						{if $errorField == 'menuItemLink'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'notUnique'}{lang}wcf.acp.admintools.acpmenu.error.menuItemLink.notUnique{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="menuItemLinkHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.menuItemLink.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('menuItemLink');
				//]]></script>
				
				<div class="formElement{if $errorField == 'menuItemIcon'} formError{/if}" id="menuItemIconDiv">
					<div class="formFieldLabel">
						<label for="menuItemIcon">{lang}wcf.acp.admintools.acpmenu.menuItemIcon{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="menuItemIcon" id="menuItemIcon" value="{$menuItemIcon}" />
						{if $errorField == 'menuItemIcon'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}								
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="menuItemIconHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.menuItemIcon.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('menuItemIcon');
				//]]></script>
				
				{if $options|count}
				<div class="formElement{if $errorField == 'permissions'} formError{/if}" id="permissionsDiv">
					<div class="formFieldLabel">
						<label for="permissions">{lang}wcf.acp.admintools.acpmenu.permissions{/lang}</label>
					</div>
					<div class="formField">
						<select name="permissions[]" id="permissions" multiple="multiple">
							<option value=""></option>
							{acpmenugroupoptions options=$options selected=$permissions}
						</select>
						{if $errorField == 'permissions'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}								
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="permissionsHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.permissions.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('permissions');
				//]]></script>
				{/if}
				
				{if $menuItemSelect|count > 0}
					<div class="formElement{if $errorField == 'parentMenuItem'} formError{/if}" id="parentMenuItemDiv">
						<div class="formFieldLabel">
							<label for="parentMenuItem">{lang}wcf.acp.admintools.acpmenu.parentMenuItem{/lang}</label>
						</div>
						<div class="formField">
							<select name="parentMenuItem" id="parentMenuItem">
								<option value=""></option>
								{htmlOptions options=$menuItemSelect disableEncoding=true selected=$parentMenuItem}
							</select>
							{if $errorField == 'parentMenuItem'}
								<p class="innerError">
									{if $errorType == 'invalid'}{lang}wcf.acp.admintools.acpmenu.error.parentMenuItem.invalid{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="parentMenuItemHelpMessage">
							{lang}wcf.acp.admintools.acpmenu.parentMenuItem.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('parentMenuItem');
					//]]></script>
				{/if}
				
				<div class="formElement" id="showOrderDiv">
					<div class="formFieldLabel">
						<label for="showOrder">{lang}wcf.acp.admintools.acpmenu.showOrder{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="showOrder" id="showOrder" value="{@$showOrder}" />
					</div>
					<div class="formFieldDesc hidden" id="showOrderHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.showOrder.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('showOrder');
				//]]></script>
				
				<div class="formElement" id="useiFrameDiv">
					<div class="formField">
						<label><input type="checkbox" id="useiFrame" name="useiFrame" value="1" {if $useiFrame == 1}checked="checked" {/if} onclick="toggleiFrame();"/> {lang}wcf.acp.admintools.acpmenu.useiFrame{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="useiFrameHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.useiFrame.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('useiFrame');
				//]]></script>
				
			</fieldset>
			
			<fieldset id="iFrameFieldset"{if !$useiFrame} style="display: none;"{/if}>
				<legend>{lang}wcf.acp.admintools.acpmenu.iframe{/lang}</legend>	
				
				<div class="formElement" id="iframeWidthDiv">
					<div class="formFieldLabel">
						<label for="iframeWidth">{lang}wcf.acp.admintools.acpmenu.iframeWidth{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="iframeWidth" id="iframeWidth" value="{@$iframeWidth}" />
					</div>
					<div class="formFieldDesc hidden" id="iframeWidthHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.iframeWidth.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('iframeWidth');
				//]]></script>

				<div class="formElement" id="iframeHeightDiv">
					<div class="formFieldLabel">
						<label for="iframeHeight">{lang}wcf.acp.admintools.acpmenu.iframeHeight{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="iframeHeight" id="iframeHeight" value="{@$iframeHeight}" />
					</div>
					<div class="formFieldDesc hidden" id="iframeHeightHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.iframeHeight.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('iframeHeight');
				//]]></script>

				<div class="formElement" id="borderWidthDiv">
					<div class="formFieldLabel">
						<label for="borderWidth">{lang}wcf.acp.admintools.acpmenu.borderWidth{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="borderWidth" id="borderWidth" value="{@$borderWidth}" />
					</div>
					<div class="formFieldDesc hidden" id="borderWidthHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.borderWidth.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('borderWidth');
				//]]></script>
				
				<div class="formElement" id="borderColorDiv">
					<div class="formFieldLabel">
						<label for="borderColor">{lang}wcf.acp.admintools.acpmenu.borderColor{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="borderColor" id="borderColor" value="{@$borderColor}" />
					</div>
					<div class="formFieldDesc hidden" id="borderColorHelpMessage">
						{lang}wcf.acp.admintools.acpmenu.borderColor.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('borderColor');
				//]]></script>
				
				<div class="formElement{if $errorField == 'borderStyle'} formError{/if}" id="borderStyleDiv">
						<div class="formFieldLabel">
							<label for="borderStyle">{lang}wcf.acp.admintools.acpmenu.borderStyle{/lang}</label>
						</div>
						<div class="formField">
							<select name="borderStyle" id="borderStyle">
								<option value=""></option>
								{htmlOptions options=$borderStyles disableEncoding=true selected=$borderStyle}
							</select>
							{if $errorField == 'borderStyle'}
								<p class="innerError">
									{if $errorType == 'invalid'}{lang}wcf.acp.admintools.acpmenu.error.borderStyle.invalid{/lang}{/if}
								</p>
							{/if}
						</div>
						<div class="formFieldDesc hidden" id="borderStyleHelpMessage">
							{lang}wcf.acp.admintools.acpmenu.borderStyle.description{/lang}
						</div>
					</div>
					<script type="text/javascript">//<![CDATA[
						inlineHelp.register('borderStyle');
					//]]></script>				

			</fieldset>

			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		{if $menuItemID|isset}
		<input type="hidden" name="menuItemID" value="{@$menuItemID}" />
		{/if}
		{if $iframeID|isset}
		<input type="hidden" name="iframeID" value="{@$iframeID}" />
		{/if}
 		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}