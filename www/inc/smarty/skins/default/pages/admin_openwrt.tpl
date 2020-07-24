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
				v {$router.sys_board.release.version} - {$router.sys_board.release.revision}<br>
				Load Avg : <span class='jsOwLoad'></span><br>
				<a href='#' class='jsOwReboot' title='Reboot {$router.sys_board.hostname}'><i class='fa fa-power-off'></i></a>
				</small>
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