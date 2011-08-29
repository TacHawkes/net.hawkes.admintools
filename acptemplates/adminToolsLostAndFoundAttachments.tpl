  								<th colspan="2" class="columnFilename"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filename{/lang}</a></div></th>
                                <th class="columnFilesize"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.filesize{/lang}</a></div></th>
								<th class="columnTime"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.time{/lang}</a></div></th>
								{if $activeSubTabMenuItem == 'database'}<th class="columnUsername"><div><a href="#">{lang}wcf.acp.admintools.lostandfound.username{/lang}</a></div></th>{/if}
                            </tr>
                          </thead>
                          <tbody>
                		{foreach from=$itemData item=item}								
                              	<tr class="{cycle}" id="{$jsname}Row{$item->objectID}">                              	  
                              	<td class="columnMarkItems">
									<label><input id="{$jsname}Mark{$item->objectID}" type="checkbox" /></label>
								</td>								
								<td class="columnIcon">
									<img id="{$jsname}Edit{$item->objectID}" src="{@RELATIVE_WCF_DIR}icon/lostAndFoundAttachmentItemM.png" alt="" />									
									{cycle print=false}
										<script type="text/javascript">
									//<![CDATA[
									itemData[{$item->objectID}] = new Object();																																										
									itemData[{$item->objectID}]['isMarked'] = {$item->isMarked()};									
									itemData[{$item->objectID}]['class'] = '{cycle}';																		
									//]]>									
								</script>
								</td>     								               
								<td class="columnFilename">																
									{@$item->filename}
								</td>
								<td class="columnFilesize">
									{$item->filesize}
								</td>
								<td class="columnTime">
									{$item->fileLastModTime|date}
								</td>
								{if $activeSubTabMenuItem == 'database'}
								<td class="columnUsername">
									{$item->user}
								</td>
								{/if}
                                </tr>
                                {/foreach}