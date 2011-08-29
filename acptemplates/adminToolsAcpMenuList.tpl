{include file='header'}
<style type="text/css">
    #content { padding-bottom: 0 !important; }
	.sitemap { padding-top: 0 !important; }
</style>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.menulink{/lang}</h2>
	</div>
</div>

{if $deletedItemID|isset && $deletedItemID}
	<p class="success">{lang}wcf.acp.admintools.acpmenu.delete.success{/lang}</p>	
{/if}

	<div class="contentHeader">
		<div class="largeButtons">
			<ul><li><a href="index.php?page=AdminTools&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.index{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.index{/lang}</span></a></li></ul>
			<ul><li><a href="index.php?form=AdminToolsMenuAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkM.png" alt="" title="{lang}wcf.acp.headerItem.add{/lang}" /> <span>{lang}wcf.acp.admintools.acpmenu.add{/lang}</span></a></li></ul>
		</div>
	</div>
	<p class="info">{lang}wcf.acp.admintools.acpmenu.editinfo{/lang}
{if $items|count > 0}
</div>
	<div class="sitemap">
		 {acpmenumap menuItemData=$items}	
{/if}


{include file='footer'}
