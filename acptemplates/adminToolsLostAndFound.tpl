{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>	
<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsLostNFoundL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.lostandfound{/lang}</h2>
	</div>
</div>

<div class="contentHeader">
	<div class="largeButtons">
		<ul><li><a href="index.php?page=AdminTools&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsM.png" alt="" title="{lang}wcf.acp.menu.link.admintools.index{/lang}" /> <span>{lang}wcf.acp.menu.link.admintools.index{/lang}</span></a></li></ul>		
	</div>
</div>

	{assign var=multiplePagesLink value="index.php?page=AdminToolsLostAndFound&pageNo=%d"}
	{assign var=multiplePagesLink value=$multiplePagesLink|concat:'&activeTabMenuItem=':$activeTabMenuItem}
	{assign var=multiplePagesLink value=$multiplePagesLink|concat:'&activeSubTabMenuItem=':$activeSubTabMenuItem}
	{if $sortField != $defaultSortField}{assign var=multiplePagesLink value=$multiplePagesLink|concat:'&sortField=':$sortField}{/if}
	{if $sortOrder != $defaultSortOrder}{assign var=multiplePagesLink value=$multiplePagesLink|concat:'&sortOrder=':$sortOrder}{/if}
	{assign var=multiplePagesLink value=$multiplePagesLink|concat:'&packageID=':PACKAGE_ID}	
	{assign var=multiplePagesLink value=$multiplePagesLink|concat:SID_ARG_2ND_NOT_ENCODED}
	{assign var=tempSuffix value=$activeSubTabMenuItem|ucfirst}
	{assign var=jsname value=$activeTabMenuItem|concat:$tempSuffix}
			<div class="tabMenu">
				<ul>					
					<li{if $activeTabMenuItem == 'backup'} class="activeTabMenu"{/if}><a href="index.php?page=AdminToolsLostAndFound&amp;activeTabMenuItem=backup&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.acp.admintools.lostandfound.backup{/lang}</span></a></li>
					<li{if $activeTabMenuItem == 'attachments'} class="activeTabMenu"{/if}><a href="index.php?page=AdminToolsLostAndFound&amp;activeTabMenuItem=attachments&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.acp.admintools.lostandfound.attachments{/lang}</span></a></li>
					<li{if $activeTabMenuItem == 'avatars'} class="activeTabMenu"{/if}><a href="index.php?page=AdminToolsLostAndFound&amp;activeTabMenuItem=avatars&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.acp.admintools.lostandfound.avatars{/lang}</span></a></li>
					{if $additionalActiveTabMenuItems|isset}{@$additionalActiveTabMenuItems}{/if}					
				</ul>
			</div>
			<div class="subTabMenu">
				<div class="containerHead">
					<ul>
					{if $activeTabMenuItem == 'attachments' || $activeTabMenuItem == 'avatars'}
						<li{if $activeSubTabMenuItem == 'database'} class="activeSubTabMenu"{/if}><a href="index.php?page=AdminToolsLostAndFound&amp;activeTabMenuItem={$activeTabMenuItem}&amp;activeSubTabMenuItem=database&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.acp.admintools.lostandfound.database{/lang}</span></a></li>
						<li{if $activeSubTabMenuItem == 'filesystem'} class="activeSubTabMenu"{/if}><a href="index.php?page=AdminToolsLostAndFound&amp;activeTabMenuItem={$activeTabMenuItem}&amp;activeSubTabMenuItem=filesystem&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><span>{lang}wcf.acp.admintools.lostandfound.filesystem{/lang}</span></a></li>
					{/if}
					{if $additionalActiveSubTabMenuItems|isset}{@$additionalActiveSubTabMenuItems}{/if}
					</ul>  					
				</div>
			</div>		

			<script type="text/javascript">
				//<![CDATA[	
				var language = new Object();				
				var url = 'index.php?page=AdminToolsLostAndFound&pageNo={$pageNo}&action={$action}&activeTabMenuItem={$activeTabMenuItem}&activeSubTabMenuItem={$activeSubTabMenuItem}&sortField={$sortField}&sortOrder={$sortOrder}&packageID={@PACKAGE_ID}{@SID_ARG_2ND_NOT_ENCODED}';				
				//]]>				
			</script>
			{include file='adminToolsLostAndFoundInlineEdit'}

			<div class="border tabMenuContent">
	    {cycle values='container-1,container-2' print=false advance=false}
                      <table class="tableList">
                          <thead>
                            <tr class="tableHead">
								<th class="columnMarkItems" style="width: 24px;">
									<div>
										<label class="emptyHead">
											<input name="{$jsname}MarkAll" type="checkbox" />
										</label>
									</div>
								</th>
								{assign var=activeItem value=$activeTabMenuItem|ucfirst}
								{assign var=template value='adminToolsLostAndFound'|concat:$activeItem}									
								{include file=$template}   
						</table>
			</div>		
	<div class="contentFooter">
			{pages link=$multiplePagesLink}
			
			<div id="{$jsname}EditMarked" class="optionButtons"></div>
	</div>                  