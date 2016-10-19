{* ##################################################################################### *}
{function MakePlayerTitle row=''}
	<input type='radio' name='selectedPlayer' class="jsSelectedPlayer" value='{$row.f_jsid}'>
	<div class="pull-right player_right">
	{$my_item=$row.status.playlist_loop.{$row.status.playlist_cur_index}}
		<span class='player_fulltitle'>

			<span class='player_artist'>
			{$my_item.f_artist}
			</span>
		 - 
			<span class='player_title'>
				{$my_item.f_title}
			</span> 
		</span>

	{if $row.f_playing}
		<span class="player_links">
			<a class="player_link_youtube" href="{$row.f_playing.f_url_youtube}" target='_blank' title="{$l.search} [{$row.f_playing.f_full_title}] {$l.on} YouTube..."><i class='fa fa-youtube'></i></a>
			<a class="player_link_allmusic" href="{$row.f_playing.f_url_google}" target='_blank' title="{$l.search} [{$row.f_playing.f_full_title}] {$l.on} AllMusic..."><i class='fa fa-database'></i></a>
			<a class="player_link_google" href="{$row.f_playing.f_url_google}" target='_blank' title="{$l.search} [{$row.f_playing.f_full_title}] {$l.on} Google..."><i class='fa fa-google'></i></a>
		</span>
	{/if}
	
	<br>
	<span class='player_album'>
		{$my_item.f_album}
	</span>

	<span class='player_radio' {if !$row.status.remote}style='display:none'{/if}>
		<i class='fa fa-signal'></i> 
		<span class='player_radio_name'>{$row.status.current_title}</span>
	</span>

	</div>
	<span  class='player_name'>
		<b>
		{if $row.f_ip}
			<a href="http://{$row.f_ip}" target="_blank">{$row.name}</a>
		{else}
			{$row.name}
		{/if}
		</b>
	</span>	

	<span class='player_position'>
		{if $my_item.duration > 0}
			 {$row.f_position}
		{/if}
	</span>

{/function}

{function MakeButton row='' mode='' title='' v1='' v2='' icon='' type='button' id='' txt=''}
	{if is_array($row)}{if $row.f_states.$mode}{$my_class=' on'}{/if}{/if}
	<a href='#' class='jsSqzBut jsSqzBut_{$mode} btn btn-default{$my_class}'  data-mode='{$mode}' data-type='{$type}' data-id='{$id|default:$row.playerid}' title="{$title|default:$l.$mode}" data-v1='{$v1|default:$mode}'  data-v2='{$v2}'>{if $icon !='NO'}<i class='fa fa-fw fa-{$icon|default:$mode}'></i>{/if}{$txt}</a> 
{/function}


{function MakePlayer row=''}
			<span class="but_player but_player_transport">
				{MakeButton row=$row mode='prev'	v1='rew.single' icon='fast-backward'}
				{MakeButton row=$row mode='rw2' title="{$l.rw} ({$data.prefs.scroll_time2})"		type='time' v1=$row.f_rw2 	icon='NO' txt="<i class='fa fa-backward'></i><i class='fa fa-backward'></i>"}
				{MakeButton row=$row mode='rw1' title="{$l.rw} ({$data.prefs.scroll_time1})"		type='time' v1=$row.f_rw1 	icon='backward'}
				{MakeButton row=$row mode='play'}
				{MakeButton row=$row mode='pause'}
				{MakeButton row=$row mode='stop'}
				{MakeButton row=$row mode='ff1'	title="{$l.ff} ({$data.prefs.scroll_time1})"		type='time' v1=$row.f_ff1 		icon='forward'}
				{MakeButton row=$row mode='ff2' title="{$l.ff} ({$data.prefs.scroll_time2})"		type='time' v1=$row.f_ff2 	icon='NO' txt="<i class='fa fa-forward'></i><i class='fa fa-forward'></i>"}
				{MakeButton row=$row mode='next'	v1='fwd.single' icon='fast-forward'}
			</span>
			<span class="but_player but_player_volume">
				{MakeButton row=$row mode='voldown' v2='1' icon='volume-down'}
				<span class="player_volume"><b class="jsSqzVolume">{$row.f_volume}</b></span> 
				{MakeButton row=$row mode='volup' v2='1' icon='volume-up'}
			</span>
			<span class="but_player but_player_misc">
				{MakeButton row=$row mode='mute' v1='muting' icon='volume-off'}
				{MakeButton row=$row mode='power' icon='power-off'}
			{if $row.model=='squeezelite'}
				{MakeButton row=$row title="{$l.restart_squeezelite}"  type='pcp' id=$row.f_ip v1='restartsqlt' icon='refresh'}
			{/if}
			</span>
			<span class="but_player but_player_cue">
				<div class="btn-group" role="group">
					{MakeButton row=$row mode='cue_in_set'	type='cue'	v1=$row.f_jsid	v2='in'		icon='toggle-down' txt="{$l.set_in}"}
					{MakeButton row=$row mode='cue_in_jump' type='time'	v1=""	icon='NO' txt="{$row.f_cue_in_view|default:'--:--:---'}"}
				</div>
				
				{MakeButton row=$row 	mode='loop' 			type='loop'	v1=$row.f_jsid				icon='repeat fa-rotate-270'}

				<div class="btn-group" role="group">
					{MakeButton row=$row mode='cue_out_set'		type='cue'	v1=$row.f_jsid	v2='out'	icon='toggle-up' txt="{$l.set_out}"}
					{MakeButton row=$row mode='cue_out_jump'	type='time'	v1=""						icon='NO' txt="{$row.f_cue_out_view|default:'--:--:---'}"}
				</div>
			</span>
			<span class="but_player but_player_mode">
				<div class="btn-group" role="group">
					{MakeButton row=$row mode='repeat_0' icon='repeat' txt="<span class='mini'>{$l.repeat_off}</span>"}
					{MakeButton row=$row mode='repeat_1' icon='repeat' txt="<span class='mini'>{$l.repeat_one}</span>"}
					{MakeButton row=$row mode='repeat_2' icon='repeat' txt="<span class='mini'>{$l.repeat_all}</span>"}
				</div>
				<div class="btn-group" role="group">
					{MakeButton row=$row mode='shuffle_0'	type='playlist'	v1='shuffle' 	v2=0	icon='random' txt="<span class='mini'>{$l.shuffle_off}</span>"}
					{MakeButton row=$row mode='shuffle_1'	type='playlist'	v1='shuffle' 	v2=1	icon='random' txt="<span class='mini'>{$l.shuffle_songs}</span>"}
					{MakeButton row=$row mode='shuffle_2'	type='playlist'	v1='shuffle'	v2=2	icon='random' txt="<span class='mini'>{$l.shuffle_albums}</span>"}
				</div>
			</span>
{/function}

{capture assign=page_content}

<script language="javascript">
$(document).ready(function(){
});
</script>

{foreach from=$data.players item=row }
		<div class="panel panel-info pmd_panel sqz_panel" id='jsPlayer_{$row.f_jsid}'>
			<div class="panel-heading">{MakePlayerTitle row=$row}</div>
			<div class="panel-body jsSqzPlayer">
{MakePlayer row=$row}
			</div>	
		</div>
{/foreach}

{/capture}




{* ##################################################################################### *}
{capture assign=page_right}
{*
https://github.com/Logitech/slimserver/blob/public/7.9/IR/Default.map	
*}
{* All Block -------------------------------------------------- *}

		<div class="panel panel-default block_right pmd_panel jsCurrentPlayer" id="block_sqz_current">
			<div class="panel-heading text-center jsCurrentPlayerHead">Selected Player</div>
			<div class="panel-body-full jsCurrentPlayerBody">
			
			</div>
		</div>



		<div class="panel panel-default block_right pmd_panel" id="block_sqz_all">
			<div class="panel-heading text-center">{$l.all_players}</div>
		</div>

		<div id="block_all_players">
			<div class="but_all but_all_transport">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='play'	txt='&nbsp;'}
					{MakeButton id='ALL' mode='pause'	txt='&nbsp;'}
					{MakeButton id='ALL' mode='stop'	txt='&nbsp;'}
				</div>
			</div>
			<div class="but_all but_all_volume">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='voldown' v2='1' icon='volume-down' txt='&nbsp;<b>-</b>&nbsp;'}
					{MakeButton id='ALL' mode='volup' v2='1' icon='volume-up' txt='&nbsp;<b>+</b>&nbsp;'}
				</div>
			</div>
			<div class="but_all but_all_mute">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='mute'  v1='muting' 	icon='volume-off' txt="&nbsp;{$l.g_mute_on}"}
					{MakeButton id='ALL' mode='mute'  v1='muting' 	icon='volume-off' txt="&nbsp;{$l.g_mute_off}"}
				</div>
			</div>


			<div class="but_all but_all_power">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='power' v1='power_off' icon='power-off'	txt="&nbsp;{$l.g_power_off}"}
					{MakeButton id='ALL' mode='power' v1='power_on' icon='power-off'	txt="&nbsp;{$l.g_power_on}"}
				</div>
			</div>

			<div class="but_all but_all_repeat">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='repeat_0' icon='repeat' txt="&nbsp;{$l.g_repeat_off}"}
					{MakeButton id='ALL' mode='repeat_1' icon='repeat' txt="&nbsp;{$l.g_repeat_one}"}
					{MakeButton id='ALL' mode='repeat_2' icon='repeat' txt="&nbsp;{$l.g_repeat_all}"}
				</div>
			</div>
			<div class="but_all but_all_shuffle">
				<div class="btn-group" role="group">
					{MakeButton id='ALL' mode='shuffle_0'	type='playlist'	v1='shuffle' 	v2=0	icon='random' txt="&nbsp;{$l.g_shuffle_off}"}
					{MakeButton id='ALL' mode='shuffle_1'	type='playlist'	v1='shuffle' 	v2=1	icon='random' txt="&nbsp;{$l.g_shuffle_songs}"}
					{MakeButton id='ALL' mode='shuffle_2'	type='playlist'	v1='shuffle'	v2=2	icon='random' txt="&nbsp;{$l.g_shuffle_albums}"}
				</div>
			</div>

		</div>




		<div class="panel panel-default block_right pmd_panel" id="block_sqz_server">
			<div class="panel-heading text-center"><a href="{$data.prefs.url_server}" target='_blank'>{$l.all_server}</a></div>
		</div>


{/capture}
{include file="{$p.template}/layout.tpl"}