{capture assign=page_content}
<div id="ow_div">
{foreach from=$data.routers item=router}
	<div class="col-md-{$data.bs_col}">
		<div class="panel panel-default pmd_panel jsOwRouter"  data-query='{$router.json_interfaces}' data-host='{$router.sys_board.hostname}'>
			<div class="panel-heading">
				<span class="jsOwLoading ow_loading"></span>
				<a href="http://{$router.sys_board.hostname}/cgi-bin/luci/" target="_blank" title="View LUCI for {$router.sys_board.hostname}">{$router.sys_board.hostname}</a>
			</div>
			<div class="panel-body">
				<small>
				{$router.sys_board.model}<br>
				{$router.sys_board.release.description}<br>
				Load Avg : <span class='jsOwLoad'></span><br>
				<a href='#' class='jsOwReboot' title='Reboot {$router.sys_board.hostname}'><i class='fa fa-power-off'></i></a>
				</small>
			</div>
			<ul class="list-group pmd_panel_group">
			{foreach from=$router.radios item=radio}
				<li class="list-group-item ow_radio{if !$radio.iwinfo.channel } ow_radio_warning{/if}">
					<div class="ow_radio_title">
					<span class="ow_radio_freq">
						{if $radio.iwinfo.channel}
							{if $radio.iwinfo.channel < 20}2.4GHz{else}5GHz{/if}
						{else}
							<i class="fa fa-warning"></i> OFF
						{/if}
					</span>
					<span class="ow_radio_bssid">{$radio.bssid}</span>
					<span class="label label-info pull-right">{$radio.iwinfo.channel}{if $radio.config.channel=='auto'} <i class='fa fa-magic'></i>{/if}</span>
					</div>
					
					{foreach from=$radio.interfaces item=interface}
					<div class="ow_if jsOwInterface jsOwIf_{$interface.ifname}" data-ifname="{$interface.ifname}">
						<div class="ow_if_title">
							<span class="ow_if_name">{$interface.config.ssid}</span>
							<span class="badge pull-right">{$interface.ifname}</span>
						</div>
						<div class="ow_if_stations jsOwStations">
						</div>
					</div>

					{/foreach}
				</li>
			{/foreach}
			</ul>
		</div>		
	</div>
{/foreach}
</div>

{/capture}


{include file="{$p.template}/layout.tpl" cont_fluid=1}