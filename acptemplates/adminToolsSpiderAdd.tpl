{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.spider.{$action}{/lang}</h2>
		{if $menuItem|isset}<p>{lang}{$menuItem}{/lang}</p>{/if}
	</div>
</div>

{if $errorField}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

{if $success|isset && $success}
	<p class="success">{lang}wcf.acp.admintools.spider.{$action}.success{/lang}</p>	
{/if}


<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=AdminToolsSpiderList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.spider{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.spider{/lang}</span></a></li></ul>
	</div>
</div>
<form method="post" action="index.php?form=AdminToolsSpider{$action|ucfirst}">
	
	<div class="border content">
		<div class="container-1">
		
			<fieldset>
				<legend>{lang}wcf.acp.admintools.spider.data{/lang}</legend>		
				
				<div class="formElement{if $errorField == 'spiderName'} formError{/if}" id="spiderNameDiv">
					<div class="formFieldLabel">
						<label for="spiderName">{lang}wcf.acp.admintools.spider.spidername{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="spiderName" id="spiderName" value="{$spiderName}" />
						{if $errorField == 'spiderName'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="spiderNameHelpMessage">
						{lang}wcf.acp.admintools.spider.spidername.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('spiderName');
				//]]></script>

				<div class="formElement{if $errorField == 'spiderIdentifier'} formError{/if}" id="spiderIdentifierDiv">
					<div class="formFieldLabel">
						<label for="spiderIdentifier">{lang}wcf.acp.admintools.spider.spideridentifier{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="spiderIdentifier" id="spiderIdentifier" value="{$spiderIdentifier}" />
						{if $errorField == 'spiderIdentifier'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="spiderIdentifierHelpMessage">
						{lang}wcf.acp.admintools.spider.spideridentifier.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('spiderIdentifier');
				//]]></script>

				<div class="formElement{if $errorField == 'spiderURL'} formError{/if}" id="spiderURLDiv">
					<div class="formFieldLabel">
						<label for="spiderURL">{lang}wcf.acp.admintools.spider.spiderurl{/lang}</label>
					</div>
					<div class="formField">
						<input type="text" class="inputText" name="spiderURL" id="spiderURL" value="{$spiderURL}" />
						{if $errorField == 'spiderURL'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="spiderURLHelpMessage">
						{lang}wcf.acp.admintools.spider.spiderurl.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('spiderURL');
				//]]></script>
				
				
			</fieldset>

			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>
		
	<div class="formSubmit">
		<input type="submit" accesskey="s" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
		{if $spiderID|isset}
		<input type="hidden" name="spiderID" value="{@$spiderID}" />
		{/if}		
 		{@SID_INPUT_TAG}
 	</div>
</form>

{include file='footer'}