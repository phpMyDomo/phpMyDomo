{capture assign=page_content}
<div id="ow_div">

<div id="ow_filters">
	<div id="ow_filters_block">
		<span class='ow_filt_title'>View : </span>
		<label class="checkbox-inline">
			<input type="checkbox" id="jsOwStat2But"{if $p.prefs.stations_show_ip} checked="checked"{/if}> Hostname & IP
		</label>
		<label class="checkbox-inline">
			<input type="checkbox" id="jsOwStat3But"{if $p.prefs.stations_show_stats} checked="checked"{/if}> Stats
		</label>
	</div>
</div>


{foreach from=$data.routers item=router}
	<div class="col-md-{$data.bs_col}">
		<div class="panel panel-default pmd_panel jsOwRouter"  data-query='{$router.json_interfaces}' data-host='{$router.sys_board.hostname}'>
			<div class="panel-heading">
				
				<a href="http://{$router.sys_board.hostname}/cgi-bin/luci/" target="_blank" title="View LUCI for {$router.sys_board.hostname}">{$router.sys_board.hostname}</a>
			</div>
			<div class="panel-body ow_router_info">
				<div class="ow_router_info1">
					<a href='#' class='jsOwButDetails ow_but_details' title='Show Details'><i class='fa fa-info-circle'></i></a>
					{if $router.desc}<span class="ow_router_desc">{$router.desc}</span>{/if}
					<span class="ow_router_buttons">
						<a href='#' class='jsOwButReboot' title='Reboot {$router.sys_board.hostname}'><i class='fa fa-power-off'></i></a>
					</span>
					<span class='ow_router_refresh'>
						<span class="jsOwLoading ow_loading"></span>
						<span class="jsOwDuration ow_duration"></span>
						<span class="jsOwState ow_state"></span>
					</span>
				</div>
				<div class="jsOwDetails ow_router_details">
					<div class="ow_router_model">{$router.sys_board.model}</div>
					<div class="ow_router_version">v {$router.sys_board.release.version} <i>{$router.sys_board.release.revision}</i></div>
				</div>	
				<div class="ow_router_stats">
					<span class='ow_router_stats1'>
						<i class="fa fa-area-chart"></i> <span class='jsOwLoad ow_load'></span>
					</span>
					<span class='ow_router_stats2'>
						<a href="#" class="jsOwMemoryTitle" title=""><span class="jsOwMemoryIcon"><i class="fa fa-battery-2"></i></span> <span class='jsOwMemory ow_memory'></span></a>
					</span>
				</div>
			</div>
			<ul class="list-group pmd_panel_group">
			{foreach from=$router.radios item=radio key=radio_name}
				<li class="list-group-item ow_radio jsOwRadio{if !$radio.iwinfo.channel } ow_radio_warning{/if}">
					<div class="ow_radio_title jsOwRadioInfoBut">
						<span class='ow_radio_caret'><i class='fa fa-caret-right jsOwRadioCaret'></i></span>
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

					<div class='ow_radio_info jsOwRadioInfo' style="display:none">
						<table cellpadding=0 cellspacing=0 >
							<tr><td class='k'>Radio:	</td><td class='v'>{$radio_name}			</td><td class='k'>Phy:			</td><td class='v'>{$radio.iwinfo.phy}			</td></tr>
							<tr><td class='k'>Signal:	</td><td class='v'>{$radio.iwinfo.signal}	</td><td class='k'>Noise:		</td><td class='v'>{$radio.iwinfo.noise}		</td></tr>
							<tr><td class='k'>Chan.:	</td><td class='v'>{$radio.iwinfo.channel}	</td><td class='k'>Freq.:		</td><td class='v'>{$radio.iwinfo.frequency}	</td></tr>
							<tr><td class='k'>Mode:		</td><td class='v'>{$radio.config.hwmode}	</td><td class='k'>Coun.:		</td><td class='v'>{$radio.iwinfo.country}		</td></tr>
							<tr><td class='k'>Power:	</td><td class='v'>{$radio.iwinfo.txpower}	</td><td>						</td><td>										</td></tr>
						</table>
					</div>
					
					{foreach from=$radio.interfaces item=interface}
					<div class="ow_if jsOwInterface jsOwIf_{$interface.ifname}" data-ifname="{$interface.ifname}">
						<div class="ow_if_title">
							<span class="ow_if_ssid">{$interface.config.ssid}</span>
							<span class="ow_if_name">{$interface.ifname}</span>
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