{include file="global/inc_functions.tpl" inline}
{* ##################################################################################### *}
{capture assign=page_content}
<meta name='jsvar' id="jsReload" data-time='120' />


	{foreach from=$p.groups key=gid item=group}	
		<div class="panel panel-default groups">
			<div class="panel-heading">
				{$p.groups_names.$gid}
		{if $group.sensor}
			<div class="pull-right sensors">
			{foreach from=$group.sensor item=sid}
				{call makeSensorHome row=$data.devices.$sid}
			{/foreach}
			</div>
		{/if}
			</div>
			<div class="panel-body">
		{foreach from=$group.command item=sid}
				{call makeButton row=$data.devices.$sid}
		{/foreach}
			</div>	
		</div>
	{/foreach}
		

{if !$p.groups}
		<div class="panel panel-info groups">
			<div class="panel-heading">{$lg.groups_names.all_commands}</div>
			<div class="panel-body">
		{foreach from=$data.commands item=i}
			{makeButton row=$i}
		{/foreach}
			</div>	
		</div>
{/if}
{/capture}
{* ##################################################################################### *}
{capture assign=page_right}

{* Weather Block -------------------------------------------------- *}

{if $p.blocks.weather}
<div class="panel panel-default block_right" id="block_weather">
	<div class="panel-heading"><i class="fa fa-cloud"></i> {$lg.blocks_titles.weather}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
	{foreach from=$p.blocks.weather item=id}
		<tr>
			<td class='td_icon'><img src='{$p.dirs.static}/global/img/icon48_type_{$data.devices.$id.img_type}.png'></td>
			<td class='td_name'>{$lg.types.{$data.devices.$id.type}}</td>
			<td class='td_value'>{$data.devices.$id.value}</td>
			<td class='td_unit'>{$p.units.{$data.devices.$id.type}}</td>
		</tr>	
	{/foreach}
	</table>
	</div>
</div>
{/if}

{* Sensors Block -------------------------------------------------- *}

{if $p.blocks.sensors}
<div class="panel panel-default block_right" id="block_sensors">
	<div class="panel-heading"><i class="fa fa-info-circle"></i> {$lg.blocks_titles.sensors}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
	{foreach from=$p.blocks.sensors item=id}
		<tr>
			<td class='td_icon'><img src='{$p.dirs.static}/global/img/icon48_type_{$data.devices.$id.img_type}.png'></td>
			<td class='td_name'>{$data.devices.$id.name}</td>
			<td class='td_value'>{$data.devices.$id.value}</td>
			<td class='td_unit'>{$p.units.{$data.devices.$id.type}}</td>
		</tr>	
	{/foreach}
	</table>
	</div>
</div>
{/if}


{* Calendar Block -------------------------------------------------- *}
<div class="panel panel-default block_right" id="block_calendar">
	<div class="panel-heading"><i class="fa fa-calendar"></i> {{$data.infos.server_time|default:{$smarty.now}}|date_format:{$lg.dates.day}|ucwords}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
		<tr>
			<td class='td_name'>{$lg.infos.now}</td>
			<td class='td_value'>{$data.infos.server_time|date_format:{$lg.dates.time}}</td>
		</tr>	
		<tr>
			<td class='td_name'>{$lg.infos.sunrise}</td>
			<td class='td_value'>{$data.infos.sunrise_time|date_format:{$lg.dates.time}}</td>
		</tr>	
		<tr>
			<td class='td_name'>{$lg.infos.sunset}</td>
			<td class='td_value'>{$data.infos.sunset_time|date_format:{$lg.dates.time}}</td>
		</tr>	
	</table>
	</div>
</div>


{/capture}
{include file="{$p.template}/layout.tpl"}