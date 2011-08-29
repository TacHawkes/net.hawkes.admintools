{include file='header'}
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/MultiPagesLinks.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/AjaxRequest.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}js/InlineListEdit.class.js"></script>
<script type="text/javascript" src="{@RELATIVE_WCF_DIR}acp/js/SpiderListEdit.class.js"></script>
<script type="text/javascript">
	//<![CDATA[
	// data array
	var spiderData = new Hash();
	var url = '{@$url|encodeJS}';
	
	// language
	var language = new Object();
	language['wcf.global.button.mark']		= '{lang}wcf.global.button.mark{/lang}';
	language['wcf.global.button.unmark']		= '{lang}wcf.global.button.unmark{/lang}';
	language['wcf.global.button.delete']		= '{lang}wcf.global.button.delete{/lang}';	
	language['wcf.acp.admintools.spider.markedSpiders']		= '{lang}wcf.acp.admintools.spider.markedSpiders{/lang}';		
	language['wcf.acp.admintools.delete.sure'] 		= '{lang}wcf.acp.admintools.delete.sure{/lang}';
	
	onloadEvents.push(function() { spiderListEdit = new SpiderListEdit(spiderData, {@$markedSpiders}); });
	//]]>
</script>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}wcf.acp.admintools.spider.list{/lang}</h2>
		<p>{lang}wcf.acp.admintools.spider.list.count{/lang}</p>
	</div>
</div>

	<div class="contentHeader">
		{pages print=true assign=pagesLinks link="index.php?page=AdminToolsSpiderList&pageNo=%d&sortField=$sortField&sortOrder=$sortOrder&packageID="|concat:PACKAGE_ID:SID_ARG_2ND_NOT_ENCODED}
		
		<div class="largeButtons">
			<ul>				
				<li><a href="index.php?form=AdminToolsSpiderAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.add{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.add{/lang}</span></a></li>
				<li><a href="index.php?action=AdminToolsSpiderSynchronize&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.synchronize{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderSynchronizeM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.synchronize{/lang}</span></a></li>
				<li><a href="index.php?form=AdminToolsSpiderImportAndExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.importandexport{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsExportM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.importandexport{/lang}</span></a></li>
				{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
			</ul>
		</div>
	</div>

{if !$spiders}
	<div class="border content">
		<div class="container-1">
			{lang}wcf.acp.admintools.spider.noneAvailable{/lang}
		</div>
	</div>
{else}
	{assign var=encodedURL value=$url|rawurlencode}	
	<div class="border titleBarPanel">
		<div class="containerHead"><h3>{lang}wcf.acp.admintools.spider.list.count{/lang}</h3></div>
	</div>
	<div class="border borderMarginRemove">
		<table class="tableList">
			<thead>
				<tr class="tableHead">		
					<th class="columnMarkSpiders" style="width: 24px;"><div><label class="emptyHead"><input name="spiderMarkAll" type="checkbox" /></label></div></th>
					<th class="columnSpiderID{if $sortField == 'spiderID'} active{/if}" colspan="2"><div><a href="index.php?page=AdminToolsSpiderList&amp;pageNo={@$pageNo}&amp;sortField=spiderID&amp;sortOrder={if $sortField == 'spiderID' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.spider.spiderid{/lang}{if $sortField == 'spiderID'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnSpiderName{if $sortField == 'spiderName'} active{/if}"><div><a href="index.php?page=AdminToolsSpiderList&amp;pageNo={@$pageNo}&amp;sortField=spiderName&amp;sortOrder={if $sortField == 'spiderName' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.spider.spidername{/lang}{if $sortField == 'spiderName'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnSpiderIdentifier{if $sortField == 'spiderIdentifier'} active{/if}"><div><a href="index.php?page=AdminToolsSpiderList&amp;pageNo={@$pageNo}&amp;sortField=spiderIdentifier&amp;sortOrder={if $sortField == 'spiderIdentifier' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.spider.spideridentifier{/lang}{if $sortField == 'spiderIdentifier'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>
					<th class="columnSpiderURL{if $sortField == 'spiderURL'} active{/if}"><div><a href="index.php?page=AdminToolsSpiderList&amp;pageNo={@$pageNo}&amp;sortField=spiderURL&amp;sortOrder={if $sortField == 'spiderURL' && $sortOrder == 'ASC'}DESC{else}ASC{/if}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{lang}wcf.acp.admintools.spider.spiderurl{/lang}{if $sortField == 'spiderURL'} <img src="{@RELATIVE_WCF_DIR}icon/sort{@$sortOrder}S.png" alt="" />{/if}</a></div></th>										
					
					{if $additionalColumnHeads|isset}{@$additionalColumnHeads}{/if}
				</tr>
			</thead>
			<tbody>
			{foreach from=$spiders item=spider}
				{assign var=spiderID value=$spider->spiderID}
				<tr class="{cycle values="container-1,container-2" advance=false}" id="spiderRow{@$spider->spiderID}">
					<td class="columnMarkSpiders">
						<label>
							<input id="spiderMark{@$spider->spiderID}" type="checkbox" value="{@$spider->spiderID}" /></td>
						</label>
					<td class="columnIcon">
						<script type="text/javascript">
							//<![CDATA[
							spiderData.set({@$spider->spiderID}, {
                                                                'isMarked': {@$spider->isMarked()},
                                                                'className': '{cycle values="container-1,container-2"}'
                                                        });
							//]]>
						</script>												
						<a href="index.php?form=AdminToolsSpiderEdit&amp;spiderID={@$spider->spiderID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/editS.png" alt="" title="{lang}wcf.acp.admintools.spider.edit{/lang}" /></a>												
						<a onclick="return confirm('{lang}wcf.acp.admintools.spider.delete.sure{/lang}')" href="index.php?page=AdminToolsSpiderAction&amp;spiderID={@$spider->spiderID}&amp;action=delete&amp;url={@$encodedURL}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}"><img src="{@RELATIVE_WCF_DIR}icon/deleteS.png" alt="" title="{lang}wcf.acp.admintools.spider.delete{/lang}" /></a>
						
						{if $additionalButtons.$spiderID|isset}{@$additionalButtons.$spiderID}{/if}
					</td>
					<td class="columnSpiderID columnID">{@$spider->spiderID}</td>
					<td class="columnSpiderName columnText"><a title="{lang}wcf.acp.admintools.spider.edit{/lang}" href="index.php?form=AdminToolsSpiderEdit&amp;spiderID={@$spider->spiderID}&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}">{$spider->spiderName}</a></td>
					<td class="columnSpiderIdentifier columnText">{@$spider->spiderIdentifier}</td>
					<td class="columnSpiderURL columnText">{if $spider->spiderURL}<a href="{@RELATIVE_WCF_DIR}acp/dereferrer.php?url={$spider->spiderURL|rawurlencode}" class="externalURL">{$spider->spiderURL}</a>{else}-{/if}</td>					
					{if $additionalColumns.$spiderID|isset}{@$additionalColumns.$spiderID}{/if}
				</tr>
			{/foreach}
			</tbody>
		</table>
	</div>

	<div class="contentFooter">
		{@$pagesLinks} <div id="spiderEditMarked" class="optionButtons"></div>
		
		<div class="largeButtons">
			<ul>				
				<li><a href="index.php?form=AdminToolsSpiderAdd&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.add{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.add{/lang}</span></a></li>
				<li><a href="index.php?action=AdminToolsSpiderSynchronize&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.synchronize{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsSpiderSynchronizeM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.synchronize{/lang}</span></a></li>
				<li><a href="index.php?form=AdminToolsSpiderImportAndExport&amp;packageID={@PACKAGE_ID}{@SID_ARG_2ND}" title="{lang}wcf.acp.admintools.spider.importandexport{/lang}"><img src="{@RELATIVE_WCF_DIR}icon/adminToolsExportM.png" alt="" /> <span>{lang}wcf.acp.admintools.spider.importandexport{/lang}</span></a></li>
				{if $additionalLargeButtons|isset}{@$additionalLargeButtons}{/if}
			</ul>
		</div>
	</div>
{/if}
{include file='footer'}