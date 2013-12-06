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
		<tr>
			<td class='td_icon'><img src='{$p.dirs.static}/global/img/icon48_type_{$devices.$id.img_type}.png'></td>
			<td class='td_name'>{$lg.types.{$devices.$id.type}}</td>
			<td class='td_value'>{$devices.$id.value}</td>
			<td class='td_unit'>{$p.units.{$devices.$id.type}}</td>
		</tr>	
	{/foreach}
	</table>
	</div>
</div>
{/if}
