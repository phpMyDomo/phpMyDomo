{* require
$type
$ids
$devices
*}

{if 	 $type=='weather'}
	{$icon='fa fa-cloud'}
{else if $type=='sensors'}
	{$icon='fa fa-info-circle'}
{else if $type=='links'}
	{$icon='fa fa-bookmark'}
{else}
	{$icon='fa fa-info-circle'}
{/if}

<div class="panel panel-default block_right pmd_panel" id="block_{$type}">
	<div class="panel-heading"><i class="{$icon}"></i> {$lg.blocks_titles.$type|default:$type}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
	{foreach from=$ids item=id}

		{if $type=='links'}
		<tr>
			<td><a href="{$id.url}" class="btn btn-xs btn-info" {if $id.blank}target="_blank"{/if}><i class="fa fa-{$id.icon|default:'bookmark'}"></i> {$id.name}</a></td>
		</tr>
		{else}
			{if $type=='weather'}{$my_name=$lg.types.{$devices.$id.type}}{else}{$my_name=$devices.$id.name}{/if}
			{$my_class=''}{if $devices.$id.warning > 0}{$my_class="sensor_warn sensor_warn_{$devices.$id.warning}"}{/if}
		<tr class="{$my_class}">
			<td class='td_icon'><img src='{$p.urls.static}{$devices.$id.img_url}'></td>
			<td class='td_name' nowrap>{$my_name|truncate:13:'…':true}</td>
			<td class='td_value'>
{if $devices.$id.url_view_sensor}<A href="{$devices.$id.url_view_sensor}" target="_blank">{/if}
				{if $devices.$id.type=='text'}{$devices.$id.value}{else}{$devices.$id.state|ucwords|default:{call number_format_lang from=$devices.$id.value count=1}}{/if}
{if $devices.$id.url_view_sensor}</A>{/if}
			</td>
			<td class='td_unit'>{$devices.$id.unit|default:$p.units.{$devices.$id.type}}</td>
		</tr>	
		{/if}
	{/foreach}
	</table>
	</div>
</div>
