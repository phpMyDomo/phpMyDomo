{capture assign=page_content}

<div class="row">
{foreach from=$data.routers item=router}
	<div class="col-md-{$data.bs_col}">
		<div class="panel panel-default pmd_panel jsOwRouter"  data-query='{$router.json_interfaces}'>
			<div class="panel-heading">
				<a href="http://{$router.sys_board.hostname}/cgi-bin/luci/" target="_blank">{$router.sys_board.hostname}</a>
			</div>
			<div class="panel-body">
				<small>
				{$router.sys_board.model}<br>
				{$router.sys_board.release.description}
				</small>
			</div>
			<ul class="list-group pmd_panel_group">
			{foreach from=$router.radios item=radio}
				<li class="list-group-item ow_radio">
					<div class="ow_radio_title">
					{if $radio.iwinfo.channel < 20}
					2.4GHz
					{else}
					5GHz
					{/if}
					<span class="label label-info pull-right">{$radio.iwinfo.channel}{if $radio.config.channel=='auto'} <i class='fa fa-magic'></i>{/if}</span>
					</div>
					
					{foreach from=$radio.interfaces item=interface}
					<div class="ow_if jsOwIf_{$interface.ifname}">
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


{include file="{$p.template}/layout.tpl"}