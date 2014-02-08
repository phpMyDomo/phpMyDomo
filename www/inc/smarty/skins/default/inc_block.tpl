{* require
$type
$icon
$devices
*}

{if $p.blocks.$type}
<div class="panel panel-default block_right home_panel" id="block_{$type}">
	<div class="panel-heading"><i class="{$icon}"></i> {$lg.blocks_titles.$type}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
	{foreach from=$p.blocks.$type item=id}
		{if $type=='links'}
		<tr>
			<td><a href="{$id.url}" class="btn btn-xs btn-info" {if $id.blank}target="_blank"{/if}><i class="fa fa-{$id.icon|default:'bookmark'}"></i> {$id.name}</a></td>
		</tr>
		{else}
			{if $type=='weather'}{$my_name=$lg.types.{$devices.$id.type}}{else}{$my_name=$devices.$id.name}{/if}
		<tr>
			<td class='td_icon'><img src='{$p.urls.static}/global/img/icon48_type_{$devices.$id.img_type}.png'></td>
			<td class='td_name' nowrap>{$my_name|truncate:13:'…':true}</td>
			<td class='td_value'>{$devices.$id.value|number_format:1}</td>
			<td class='td_unit'>{$devices.$id.unit|default:$p.units.{$devices.$id.type}}</td>
		</tr>	
		{/if}
	{/foreach}
	</table>
	</div>
</div>
{/if}
