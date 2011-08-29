{include file='header'}
 <style type="text/css">
    .iFrame {
        {if $iFrameData.width}width: {@$iFrameData.width};{/if}
        {if $iFrameData.height}height: {@$iFrameData.height};{/if}
        {if $iFrameData.borderWidth}
            border-width: {@$iFrameData.borderWidth};
            {if $iFrameData.borderStyle}border-style: {@$iFrameData.borderStyle};{/if}
            {if $iFrameData.borderColor}border-color: {@$iFrameData.borderColor};{/if}
        {/if}
        overflow: auto;
    }
    </style>

<div class="mainHeadline">
	<img src="{@RELATIVE_WCF_DIR}icon/adminToolsMenuLinkL.png" alt="" />
	<div class="headlineContainer">
		<h2>{lang}{$iFrameData.menuItem}{/lang}</h2>		
	</div>
</div>

	<iframe src="{@$iFrameData.url}" class="iFrame" name="iFrame" frameborder="0"></iframe>

{include file='footer'}