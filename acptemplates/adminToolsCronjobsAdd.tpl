{include file='header'}
<script type="text/javascript">
//<![CDATA[
function toggleStandaloneFunctions() {
	var fieldset = document.getElementById('standaloneFieldset');
	if(document.getElementById('wcfCronjob').checked == false) {		
		fieldset.style.display = null;
	}
	else {
		fieldset.style.display = 'none';
	}
}
//]]>
</script>
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/cronjobs{$action|ucfirst}L.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.cronjobs.{$action}{/lang}</h2>
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset}
	<p class="success">{lang}wcf.acp.cronjobs.{$action}.success{/lang}</p>	
{/if}

<p class="info">{lang}wcf.acp.cronjobs.intro{/lang}</p>

<div class="contentHeader">
	<div class="largeButtons">
		<ul>
			<li><a href="index.php?page=AdminToolsCronjobsList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.menu.link.cronjobs.view{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/cronjobsM.png" alt="" /> <span>{lang}wcf.acp.menu.link.cronjobs.view{/lang}</span></a></li>			
		</ul>
	</div>
</div>
<form method="post" action="index.php?form=AdminToolsCronjobs{$action|ucfirst}">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.cronjobs.edit.data{/lang}</legend>							
				
				{* The packageName field which tells us what package installed this cron job 
				is not to be edited because it is either set automatically at the time the 
				package itself is being installed, or, in the case of a manual install of 
				this cron job, it is set to the name of the package which is the current 
				acp package.
				In contrast, the description field is being set to a language variable 
				in case this cronjob has been installed by a package. This is remembered 
				by setting the installedBySystem field to True; the field is being evaluated 
				when calling this template, and if it is True, the description is not to be edited. *}
				
				
				<div class="formElement" id="descriptionDiv">
					<div class="formFieldLabel">
						<label for="description">{lang}wcf.acp.cronjobs.description{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="description" name="description" value="{$description}" />
					</div>
					<div class="formFieldDesc hidden" id="descriptionHelpMessage">
						{lang}wcf.acp.cronjobs.description.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('description');
				//]]></script>
				
				<div class="formElement" id="execMultipleDiv">
					<div class="formField">
						<label><input type="checkbox" id="execMultiple" name="execMultiple" value="1" {if $execMultiple == 1}checked="checked" {/if}/> {lang}wcf.acp.cronjobs.execMultiple{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="execMultipleHelpMessage">
						{lang}wcf.acp.cronjobs.execMultiple.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('execMultiple');
				//]]></script>
				
				<div class="formElement" id="wcfCronjobDiv">
					<div class="formField">
						<label><input type="checkbox" id="wcfCronjob" name="wcfCronjob" value="1" onclick="toggleStandaloneFunctions();" {if $wcfCronjob == 1}checked="checked" {/if}/> {lang}wcf.acp.admintools.cronjobs.wcfCronjob{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="wcfCronjobHelpMessage">
						{lang}wcf.acp.admintools.cronjobs.wcfCronjob.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('wcfCronjob');
				//]]></script>
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.acp.cronjobs.edit.timing{/lang}</legend>
				<div class="formElement{if $errorField == 'startMinute'} formError{/if}" id="startMinuteDiv">
					<div class="formFieldLabel">
						<label for="startMinute">{lang}wcf.acp.cronjobs.startMinute{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="startMinute" name="startMinute" value="{$startMinute}" />
						{if $errorField == 'startMinute'}
							<p class="innerError">
								{if $errorType == 'notValid'}{lang}wcf.acp.cronjobs.error.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="startMinuteHelpMessage">
						{lang}wcf.acp.cronjobs.startMinute.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('startMinute');
				//]]></script>
				
				<div class="formElement{if $errorField == 'startHour'} formError{/if}" id="startHourDiv">
					<div class="formFieldLabel">
						<label for="startHour">{lang}wcf.acp.cronjobs.startHour{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="startHour" name="startHour" value="{$startHour}" />
						{if $errorField == 'startHour'}
							<p class="innerError">
								{if $errorType == 'notValid'}{lang}wcf.acp.cronjobs.error.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="startHourHelpMessage">
						{lang}wcf.acp.cronjobs.startHour.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('startHour');
				//]]></script>
				
				<div class="formElement{if $errorField == 'startDom'} formError{/if}" id="startDomDiv">
					<div class="formFieldLabel">
						<label for="startDom">{lang}wcf.acp.cronjobs.startDom{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="startDom" name="startDom" value="{$startDom}" />
						{if $errorField == 'startDom'}
							<p class="innerError">
								{if $errorType == 'notValid'}{lang}wcf.acp.cronjobs.error.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="startDomHelpMessage">
						{lang}wcf.acp.cronjobs.startDom.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('startDom');
				//]]></script>
				
				<div class="formElement{if $errorField == 'startMonth'} formError{/if}" id="startMonthDiv">
					<div class="formFieldLabel">
						<label for="startMonth">{lang}wcf.acp.cronjobs.startMonth{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="startMonth" name="startMonth" value="{$startMonth}" />
						{if $errorField == 'startMonth'}
							<p class="innerError">
								{if $errorType == 'notValid'}{lang}wcf.acp.cronjobs.error.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="startMonthHelpMessage">
						{lang}wcf.acp.cronjobs.startMonth.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('startMonth');
				//]]></script>
				
				<div class="formElement{if $errorField == 'startDow'} formError{/if}" id="startDowDiv">
					<div class="formFieldLabel">
						<label for="startDow">{lang}wcf.acp.cronjobs.startDow{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" id="startDow" name="startDow" value="{$startDow}" />
						{if $errorField == 'startDow'}
							<p class="innerError">
								{if $errorType == 'notValid'}{lang}wcf.acp.cronjobs.error.notValid{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="startDowHelpMessage">
						{lang}wcf.acp.cronjobs.startDow.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('startDow');
				//]]></script>
			</fieldset>
			
			<fieldset>
				<legend>{lang}wcf.acp.admintools.cronjobs.wcffunctions{/lang}</legend>
				<p class="description">{lang}wcf.acp.admintools.cronjobs.wcffunctions.description{/lang}</p>
				
				{foreach from=$functions item=function}
				{if $function.packageDir|empty}
				{assign var=functionID value=$function.functionID}
				<div class="formElement" id="{$function.functionName}Div">
					<div class="formField">
						<label><input type="checkbox" id="{$function.functionName}" name="functions[]" value="{$function.functionID}" {if $functionID|in_array:$activeFunctions}checked="checked" {/if}/> {lang}wcf.acp.admintools.option.category.{$function.functionName}{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="{$function.functionName}HelpMessage">
						{lang}wcf.acp.admintools.option.category.{$function.functionName}.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('{$function.functionName}');
				//]]></script>
				{/if}
				{/foreach}
			</fieldset>
			
			<fieldset id="standaloneFieldset"{if $wcfCronjob} style="display: none;"{/if}>
				<legend>{lang}wcf.acp.admintools.cronjobs.standalonefunctions{/lang}</legend>
				<p class="description">{lang}wcf.acp.admintools.cronjobs.standalonefunctions.description{/lang}</p>
				
				{foreach from=$functions item=function}
				{if !$function.packageDir|empty}
				{assign var=functionID value=$function.functionID}
				<div class="formElement" id="{$function.functionName}Div">
					<div class="formField">
						<label><input type="checkbox" id="{$function.functionName}" name="functions[]" value="{$function.functionID}" {if $functionID|in_array:$activeFunctions}checked="checked" {/if}/> {lang}wcf.acp.admintools.option.category.{$function.functionName}{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="{$function.functionName}HelpMessage">
						{lang}wcf.acp.admintools.option.category.{$function.functionName}.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('{$function.functionName}');
				//]]></script>
				{/if}
				{/foreach}
			</fieldset>
			
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
 		{if $cronjobID|isset}<input type="hidden" name="cronjobID" value="{@$cronjobID}" />{/if}
	</div>
</form>

{include file='footer'}