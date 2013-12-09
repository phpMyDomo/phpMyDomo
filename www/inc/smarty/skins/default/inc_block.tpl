{* require
$type
$icon
$devices
*}

{if $p.blocks.$type}
<div class="panel panel-default block_right" id="block_{$type}">
	<div class="panel-heading"><i class="{$icon}"></i> {$lg.blocks_titles.$type}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
	{foreach from=$p.blocks.$type item=id}
		{if $type=='weather'}{$my_name=$lg.types.{$devices.$id.type}}{else}{$my_name=$devices.$id.name}{/if}
		<tr>
			<td class='td_icon'><img src='{$p.urls.static}/global/img/icon48_type_{$devices.$id.img_type}.png'></td>
			<td class='td_name'>{$my_name}</td>
			<td class='td_value'>{$devices.$id.value}</td>
			<td class='td_unit'>{$p.units.{$devices.$id.type}}</td>
		</tr>	
	{/foreach}
	</table>
	</div>
</div>
{/if}
