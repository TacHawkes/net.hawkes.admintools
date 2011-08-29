{include file='header'}
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsAboutL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.about{/lang}</h2>		
	</div>
</div>
<div class="border content">
	<div class="container-1">
		<h3 class="subHeadline">{lang}wcf.acp.admintools.credits{/lang}</h3>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.developedFor{/lang}</p>
			<p class="formField"><a href="{@RELATIVE_WCF_DIR}acp/dereferrer.php?url={"http://www.wbb3addons.de"|rawurlencode}" class="externalURL">wbb3Addons</a></p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.developer{/lang}</p>
			<p class="formField">Oliver Kliebisch</p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.icons{/lang}</p>
			<p class="formField">Oxygen Set</p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.contributor{/lang}</p>
			<p class="formField">Sani9000</p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.translators{/lang}</p>
			<p class="formField">Sani9000 (en)</p>
		</div>
		
		<div class="formElement" style="margin-top: 10px">
			<p class="formFieldLabel"></p>
			<p class="formField">Copyright &copy; 2009 Oliver Kliebisch. Admin Tools 2 is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.</p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">{lang}wcf.acp.admintools.credits.donate{/lang}</p>
			<p class="formField">{lang}wcf.acp.admintools.credits.donate.description{/lang}</p>
		</div>
		
		<div class="formElement">
			<p class="formFieldLabel">&nbsp;</p>
			<p class="formField">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
					<input type="hidden" name="cmd" value="_s-xclick" />
					<input type="hidden" name="hosted_button_id" value="3346640" />
					<input type="image" src="https://www.paypal.com/de_DE/DE/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="" />
					<img alt="" border="0" src="https://www.paypal.com/de_DE/i/scr/pixel.gif" width="1" height="1" />
				</form>
			</p>
		</div>
	</div>
</div>

{include file='footer'}