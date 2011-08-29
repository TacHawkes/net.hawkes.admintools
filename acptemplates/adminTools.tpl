{capture append='specialStyles'}
<style type="text/css">
	@import url("{@RELATIVE_WCF_DIR}acp/style/adminTools.css");
</style>
{/capture}
{include file='header'}

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.menu.link.admintools{/lang}</h2>
	</div>
</div>

<div class="border content">
	<div class="container-1">
		<ul class="adminToolsOverview">
				<li>
					<a href="index.php?form=AdminToolsFunction&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsFunctionXL.png" alt="" /></a>		
					<ul>
						<li><a href="index.php?form=AdminToolsFunction&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.functions{/lang}</a></li>
					</ul>
				</li>
				
				
				<li>
					<a href="index.php?page=AdminToolsCronjobsList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsCronjobXL.png" alt="" /></a>				
					<ul>
						<li><a href="index.php?page=AdminToolsCronjobsList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.cronjob{/lang}</a></li>
					</ul>
				</li>
				
				<li>
					<a href="index.php?page=AdminToolsSpiderList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderXL.png" alt="" /></a>				
					<ul>
						<li><a href="index.php?page=AdminToolsSpiderList&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.spider{/lang}</a></li>
					</ul>
				</li>				
				
				<li>
					<a href="index.php?page=AdminToolsMenu&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkXL.png" alt="" /></a>				
					<ul>
						<li><a href="index.php?page=AdminToolsMenu&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.menulink{/lang}</a></li>
					</ul>
				</li>

				{if $additionalFunctions|isset}{@$additionalFunctions}{/if}
					
				<li>
					<a href="index.php?page=AdminToolsAbout&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsAboutXL.png" alt="" /></a>				
					<ul>
						<li><a href="index.php?page=AdminToolsAbout&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.about{/lang}</a></li>
					</ul>
				</li>	
												
			</ul>
	</div>
</div>

{include file='footer'}