{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/optionImportAndExportL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.importAndExport{/lang}</h2>
	</div>
</div>

{if $success|isset}
	<p class="success">{lang}wcf.acp.option.import.success{/lang}</p>	
{/if}

{if $errorField != ''}
	<p class="error">{lang}wcf.global.form.error{/lang}</p>
{/if}

<form method="post" action="index.php?form=AdminToolsImportAndExport" enctype="multipart/form-data">
	<div class="border content">
		<div class="container-1">
			<fieldset>
				<legend>{lang}wcf.acp.option.import{/lang}</legend>
			
				<div class="formElement{if $errorField == 'optionImport'} formError{/if}" id="optionImportDiv">
					<div class="formFieldLabel">
						<label for="optionImport">{lang}wcf.acp.option.import.upload{/lang}</label>
					</div>
					<div class="formField">
						<input type="file" id="optionImport" name="optionImport" value="" />
						{if $errorField == 'optionImport'}
							<p class="innerError">
								{if $errorType == 'empty'}{lang}wcf.global.error.empty{/lang}{/if}
								{if $errorType == 'importFailed'}{lang}wcf.acp.option.import.error.importFailed{/lang}{/if}
								{if $errorType == 'uploadFailed'}{lang}wcf.acp.option.import.error.uploadFailed{/lang}{/if}
							</p>
						{/if}
					</div>
					<div class="formFieldDesc hidden" id="optionImportHelpMessage">
						{lang}wcf.acp.option.import.upload.description{/lang}
					</div>
				</div>
				<script type="text/javascript">//<![CDATA[
					inlineHelp.register('optionImport');
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
			<legend>{lang}wcf.acp.option.export{/lang}</legend>
		
			<div class="formElement" id="optionExportDiv">
				<div class="formField">
					<a href="index.php?action=AdminToolsExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" id="optionExport">{lang}wcf.acp.option.export.download{/lang}</a>
				</div>
				<div class="formFieldDesc hidden" id="optionExportHelpMessage">
					{lang}wcf.acp.option.export.download.description{/lang}
				</div>
			</div>
			<script type="text/javascript">//<![CDATA[
				inlineHelp.register('optionExport');
			//]]></script>
		</fieldset>
		
		{if $additionalFields|isset}{@$additionalFields}{/if}
	</div>
</div>

{include file='footer'}