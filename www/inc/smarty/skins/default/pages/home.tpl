{include file="global/inc_functions.tpl" inline}
{* ##################################################################################### *}
{capture assign=page_content}
<meta name='jsvar' id="jsReload" data-time='{$c.app.reload_time}' data-url="{$c.app.screensaver_url}" />

	{foreach from=$p.groups key=gid item=group}	
		<div class="panel panel-default home_groups pmd_panel">
			<div class="panel-heading">
				<i class="fa fa-folder"></i> {$p.groups_names.$gid}
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
		<div class="panel panel-info home_groups pmd_panel">
			<div class="panel-heading"><i class="fa fa-folder"></i> {$lg.groups_names.all_commands}</div>
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

{include file="{$p.template}/inc_block.tpl" type='weather'	devices=$data.devices icon='fa fa-cloud'}
{include file="{$p.template}/inc_block.tpl" type='sensors'	devices=$data.devices icon='fa fa-info-circle'}
{include file="{$p.template}/inc_block.tpl" type='links'	devices=$data.devices icon='fa fa-bookmark'}


{* Calendar Block -------------------------------------------------- *}
<div class="panel panel-default block_right pmd_panel" id="block_calendar">
	<div class="panel-heading"><i class="fa fa-calendar"></i> {{$data.infos.server_time|default:{$smarty.now}}|date_format:{$lg.dates.day}|ucwords}</div>
	<div class="panel-body-full">
	<table cellspacing=0 cellpadding=0>
{if $data.infos.server_time}
		<tr>
			<td class='td_icon'><img src='{$p.urls.static}/global/img/icon48_time_now.png'></td>
			<td class='td_name'>{$lg.infos.now}</td>
			<td class='td_value'>{$data.infos.server_time|date_format:{$lg.dates.time}}</td>
		</tr>
{/if}
{if $data.infos.sunrise_time}
		<tr>
			<td class='td_icon'><img src='{$p.urls.static}/global/img/icon48_time_sunrise.png'></td>
			<td class='td_name'>{$lg.infos.sunrise}</td>
			<td class='td_value'>{$data.infos.sunrise_time|date_format:{$lg.dates.time}}</td>
		</tr>	
{/if}
{if $data.infos.sunset_time}
		<tr>
			<td class='td_icon'><img src='{$p.urls.static}/global/img/icon48_time_sunset.png'></td>
			<td class='td_name'>{$lg.infos.sunset}</td>
			<td class='td_value'>{$data.infos.sunset_time|date_format:{$lg.dates.time}}</td>
		</tr>	
{/if}
{if $c.debug.show}
			<td class='td_name' colspan=2>Location</td>
			<td class='td_value'>{$data.infos.google_city}, {$data.infos.google_country}</td>
{/if}

	</table>
	</div>
</div>


{/capture}
{include file="{$p.template}/layout.tpl"}