{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.spider.spider.importAndExport{/lang}</h2>
	</div>
</div>

{if $success|isset}
	<p class="success">{lang}wcf.acp.admintools.spider.import.success{/lang}</p>	
{/if}

{if $errorField != ''}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="index.php?form=AdminToolsSpiderImportAndExport" enctype="multipart/form-data">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.admintools.spider.import{/lang}</legend>
			
				<div class="formElement{if $errorField == 'spiderImport'} formError{/if}" id="spiderImportDiv">
					<div class="formFieldLabel">
						<label for="spiderImport">{lang}wcf.acp.admintools.spider.import.upload{/lang}</label>
					</div>
					<div class="formField">
						<input type="file" id="spiderImport" name="spiderImport" value="" />
						{if $errorField == 'spiderImport'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'importFailed'}{lang}wcf.acp.admintools.spider.import.error.importFailed{/lang}{/if}
								{if $errorType == 'uploadFailed'}{lang}wcf.acp.admintools.spider.import.error.uploadFailed{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="spiderImportHelpMessage">
						{lang}wcf.acp.admintools.spider.import.upload.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('spiderImport');
				//]]></script>
				
				<div class="formElement" id="oldAdminToolsDiv">
					<div class="formField">
						<label><input type="checkbox" id="oldAdminTools" name="oldAdminTools" value="1"/> {lang}wcf.acp.admintools.spider.oldAdminTools{/lang}</label>
					</div>
					<div class="formFieldDesc hidden" id="oldAdminToolsHelpMessage">
						{lang}wcf.acp.admintools.spider.oldAdminTools.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('oldAdminTools');
				//]]></script>
			</fieldset>
			
			{if $additionalFields|isset}{@$additionalFields}{/if}
		</div>
	</div>

	<div class="formSubmit">
		<input type="submit" accesskey="s" name="submitButton" value="{lang}wcf.global.button.submit{/lang}" />
		<input type="reset" accesskey="r" value="{lang}wcf.global.button.reset{/lang}" />
		<input type="hidden" name="packageID" value="{@PACKAGE_ID}" />
 		{@SID_INPUT_TAG}
	</div>
</form>

<div class="border content">
	<div class="container-1">
		<fieldset>
			<legend>{lang}wcf.acp.admintools.spider.export{/lang}</legend>
		
			<div class="formElement" id="spiderExportDiv">
				<div class="formField">
					<a href="index.php?action=AdminToolsSpiderExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" id="spiderExport">{lang}wcf.acp.admintools.spider.export.download{/lang}</a>
				</div>
				<div class="formFieldDesc hidden" id="spiderExportHelpMessage">
					{lang}wcf.acp.admintools.spider.export.download.description{/lang}
				</div>
			</div>
			<script type="text/javascript">//<![CDATA[
				inlineHelp.register('spiderExport');
			//]]></script>
		</fieldset>
		
		{if $additionalFields|isset}{@$additionalFields}{/if}
	</div>
</div>

{include file='footer'}