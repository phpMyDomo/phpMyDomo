{* ##################################################################################### *}
{function MakeButton row='' mode='' title='' v1='' v2='' icon='' type='button' id='' txt='' size=''}
	{if size}{$size_class=" btn-{$size}"}{/if}
	{if is_array($row)}{if $row.f_states.$mode}{$my_class=' on'}{/if}{/if}
	<a href='#' class='jsSqzBut jsSqzBut_{$mode} btn btn-default{$size_class}{$my_class}'  data-mode='{$mode}' data-type='{$type}' data-id='{$id|default:$row.playerid}' title="{$title|default:$l.$mode}" data-v1='{$v1|default:$mode}'  data-v2='{$v2}'>{if $icon !='NO'}<i class='fa fa-fw fa-{$icon|default:$mode}'></i>{/if}{$txt}</a> 
{/function}

{function MakeField data='' field='' icon='' value='' h_default=''  post='' noblank='0'}{strip}
{if $icon}{$icon="<i class='fa fa-{$icon}'></i>"}{/if}
{if $value==''}{$value=$data[$field]}{/if}
{if $post}{$h_post="<u>{$post}</u>"}{/if}
<span class="player_fields field_{$field}">{$icon}<b class="jsSqzData" data-type="field" data-field="{$field}" data-value="{$value}" data-noblank='{$noblank}' data-h_default="{$h_default}" data-post="{$post}">{$data["h_{$field}"]|default:$h_default|default:$value}{$h_post}</b></span>
{/strip}{/function}

{function MakeLink link='' field='' blank=1}{strip}
<span class="player_fields field_{$field}"><a href="{$link.href}" title="{$link.title}"{if $blank} target="_blank"{/if} class="jsSqzData" data-type="link" data-field="{$field}" data-value="{$link.href}"><i class='fa fa-{$link.icon}'></i></a></span>
{/strip}{/function}

{function MakeIcon data='' field='' icons=""}{strip}
<span class="player_icons field_{$field}"><b class="jsSqzData" data-type="icon" data-field="{$field}" data-value="{$data[$field]}" data-icons='{$icons}'><i class='fa fa-refresh'></i></b></span>
{/strip}{/function}

{function MakeImage data='' field=''}{strip}
<span class="player_fields field_{$field}"><img class="jsSqzData" src='{$data[$field]}' data-type="image" data-field="{$field}" data-value="{$data[$field]}"></span>
{/strip}{/function}

{function MakePlayerTitle row=''}
	<div class="player_right">

		<span class='player_fulltitle'>
			<span class='player_album'>
				{MakeField data=$row.song field='album'}{MakeField data=$row.song field='radio_name'}
			</span>
			<span class='player_title'>
				{MakeField data=$row.song field='title'}
			</span> 

			<span class='player_artist'>
				{MakeField data=$row.song field='artist'}
			</span>
		</span>

		<span class='player_radio' {if !$row.status.remote}style='display:none'{/if}>
			<i class='fa fa-signal'></i> 
			<span class='player_radio_name'>{$row.status.current_title}</span>
		</span>

	{if $row.song}
		<span class="player_links">
			{foreach from=$row.song.links item=link key=k}
				{MakeLink link=$link field=$k}
			{/foreach}
		</span>
	{/if}

	</div>

	<div class="player_left">
		{MakeIcon data=$row field='mode' icons='{"play":"play","pause":"pause","stop":"stop"}'}

		<span  class='player_name'>
			<b>
			{if $row.ip}
				<a href="http://{$row.ip}" target="_blank">{$row.name}</a>
			{else}
				{$row.name}
			{/if}
			</b>
		</span>	

		<span class='player_position'>
			{MakeField data=$row field='time'}
		</span>
	</div>

{/function}

{function MakePlayer row=''}
			<div class="row_lcd">

				<div class="lcd_screen">
					{strip}
					
					<div class='lcd_icons'>
						<div class='lcd_icons_left'>
							<span class='player_duration'>
								{MakeField data=$row field='duration' icon='clock-o'}
							</span>
							<span class='player_remain'>
								{MakeIcon data=$row field='remain_icon' icons='["hourglass-1","hourglass-2","hourglass-3","hourglass-o"]'}{MakeField data=$row field='remain'}
							</span>

						</div>
						<div class='lcd_icons_right'>
						
							{MakeField data=$row field='bpm' icon='heartbeat' h_default="&nbsp;"}
							{MakeIcon data=$row field='type' icons='{"file":"file-o","radio":"podcast","net":"wifi"}'}
							{MakeField data=$row field='filetype'}

							<span class='player_encoder'>
								{MakeField data=$row field='bitrate'}
								{MakeField data=$row field='bitrate_unit'}
								{MakeField data=$row field='bitrate_info'}
							</span>

							{MakeField data=$row field='volume' icon='volume-up'}

						</div>
					</div>
			
					<div class="lcd_time">
						<li class="lcd_num lcd_min jsLcdMin">--</li>
						<li class="lcd_sep jsLcdSep">:</li>
						<li class="lcd_num lcd_sec jsLcdSec">--</li>
						<li class="lcd_sep jsLcdSep">.</li>
						<li class="lcd_num lcd_ms jsLcdMs">--</li>
					</div>

					<div class="lcd_info">
						<span class="lcd_song">
							<span class='player_song1 player_title'>{MakeField data=$row field='title' h_default="&nbsp;"}</span> 
							<span class='player_song2 player_artist'>{MakeField data=$row field='artist' h_default="&nbsp;"}</span>
							<span class='player_song3'>
								<span class='player_year'>{MakeField data=$row field='year' noblank=1}</span>
								<span class='player_album'>{MakeField data=$row field='album' icon="chevron-circle-right" noblank=1}{MakeField data=$row.song field='radio_name' icon="podcast" noblank=1}</span>
							</span>
						</span>
					</div>
					
					{/strip}
				</div>

			</div>

			<div class="row_progression">
					<div class="progress_border">
						<div class="progress_inner jsSqzProgress"><span class="progress_head"></span></div>
					</div>
			</div>

			<div class="row_transport">
				<span class="but_player but_player_transport">
					{MakeButton size='lg' row=$row mode='prev'	v1='rew.single' icon='fast-backward'}
					{MakeButton size='lg' row=$row mode='play'}
					{MakeButton size='lg' row=$row mode='pause'}
					{MakeButton size='lg' row=$row mode='stop'}
					{MakeButton size='lg' row=$row mode='next'	v1='fwd.single' icon='fast-forward'}
				</span>

				<span class="but_player but_player_move">
					{MakeButton size='lg' row=$row mode='rw2' title="{$l.rw} ({$data.prefs.scroll_time2})"		type='time' icon='NO' txt="<i class='fa fa-backward'></i><i class='fa fa-backward'></i>"}
					{MakeButton size='lg' row=$row mode='rw1' title="{$l.rw} ({$data.prefs.scroll_time1})"		type='time' icon='backward'}
					{MakeButton size='lg' row=$row mode='ff1'	title="{$l.ff} ({$data.prefs.scroll_time1})"	type='time' icon='forward'}
					{MakeButton size='lg' row=$row mode='ff2' title="{$l.ff} ({$data.prefs.scroll_time2})"		type='time'	icon='NO' txt="<i class='fa fa-forward'></i><i class='fa fa-forward'></i>"}
				</span>

				<span class="but_player but_player_playlist">
					<select class="form-control input-sm jsSqzSelectPlaylistAdd" value=''></select>
					{MakeButton row=$row icon='plus' type='pl_add'}
				</span>

			</div>


			<div class="row_studio">
				<span class="but_player but_player_cue">
					<div class="btn-group" role="group">
						{MakeButton size='lg' row=$row mode='cue_in_set'	type='cue'	v1=$row.f_jsid	v2='in'		icon='toggle-down' txt="{$l.set_in}"}
						{MakeButton size='lg' row=$row mode='cue_in_jump' type='time'	v1=""	icon='NO' txt="{$row.f_cue_in_view|default:'--:--:---'}"}
					</div>
				
					{MakeButton size='lg' row=$row 	mode='loop' 			type='loop'	v1=$row.f_jsid				icon='repeat fa-rotate-270'}

					<div class="btn-group" role="group">
						{MakeButton size='lg' row=$row mode='cue_out_set'		type='cue'	v1=$row.f_jsid	v2='out'	icon='toggle-up' txt="{$l.set_out}"}
						{MakeButton size='lg' row=$row mode='cue_out_jump'	type='time'	v1=""						icon='NO' txt="{$row.f_cue_out_view|default:'--:--:---'}"}
					</div>
				</span>
			</div>


			<div class="row_settings">

				<span class="but_player but_player_mode">
					<div class="btn-group" role="group">
						{MakeButton size='' row=$row mode='repeat_0' icon='repeat' txt="<span class='mini'>{$l.repeat_off}</span>"}
						{MakeButton size='' row=$row mode='repeat_1' icon='repeat' txt="<span class='mini'>{$l.repeat_one}</span>"}
						{MakeButton size='' row=$row mode='repeat_2' icon='repeat' txt="<span class='mini'>{$l.repeat_all}</span>"}
					</div>
					<div class="btn-group" role="group">
						{MakeButton size='' row=$row mode='shuffle_0'	type='playlist'	v1='shuffle' 	v2=0	icon='random' txt="<span class='mini'>{$l.shuffle_off}</span>"}
						{MakeButton size='' row=$row mode='shuffle_1'	type='playlist'	v1='shuffle' 	v2=1	icon='random' txt="<span class='mini'>{$l.shuffle_songs}</span>"}
						{MakeButton size='' row=$row mode='shuffle_2'	type='playlist'	v1='shuffle'	v2=2	icon='random' txt="<span class='mini'>{$l.shuffle_albums}</span>"}
					</div>
				</span>

				<span class="but_player but_player_misc">
					<span class="but_player_volume">
						{MakeButton size='' row=$row mode='voldown' v2='1' icon='volume-down'}
						{MakeButton size='' row=$row mode='mute' v1='muting' icon='volume-off' txt="<span class='mini'>{$l.repeat_off}</span>"}
						{MakeButton size='' row=$row mode='volup' v2='1' icon='volume-up'}
					</span>
					{MakeButton size='' row=$row mode='power' icon='power-off'}
				{if $row.model=='squeezelite'}
					{MakeButton size='' row=$row title="{$l.restart_squeezelite}"  type='pcp' id=$row.f_ip v1='restartsqlt' icon='refresh'}
				{/if}
				</span>

			</div>

			<div class="row_playlist">
				<div class="cur_playlist jsSqzPlaylist">
					
				</div>
			</div>


{/function}

{capture assign=page_content}


<div class="">
{foreach from=$data.players item=row }
		<div class="panel panel-default pmd_panel pmd_panel_sqz jsSqzPlayer" id='jsPlayer_{$row.f_jsid}' data-id='{$row.playerid}' data-jsid='{$row.f_jsid}'>
			<div class="panel-heading player_head jsPlayerHead">{MakePlayerTitle row=$row}</div>
			<div class="panel-body player_body jsPlayerBody">

{MakePlayer row=$row}

			</div>	
		</div>
{/foreach}
</div>


{/capture}
{* 
	



##################################################################################### *}
{capture assign=page_right}
{*
https://github.com/Logitech/slimserver/blob/public/7.9/IR/Default.map	
*}
{* All Block -------------------------------------------------- *}

		<div class="panel panel-default block_right pmd_panel jsSqzInformation" id="block_information">
			<div class="panel-heading text-center">{MakeField field='name' value=$l.selected_player  icon='check' h_default="&nbsp;"}</div>
			<div class="panel-body-full">
			 	{MakeImage field='url_img'}
			</div>
		</div>


		<div class="pmd_panel block_right" id="block_sqz_links">
			<div class="panel-body-full">
			<ul class="list-group">
{if $data.agent !='ios'}
    			<li class="list-group-item"><a class="btn btn-sm btn-info btn-block" target="_blank" href="market://search?q=pname:com.logitech.squeezeboxremote"><i class='fa fa-fw fa-android'></i> {$l.link_android}</a></li>
{/if}
{if $data.agent !='android'}
				<li class="list-group-item"><a  class="btn btn-sm btn-info btn-block" target="_blank" href="https://itunes.apple.com/fr/app/logitech-squeezebox-controller/id431302899?mt=8"><i class='fa fa-fw fa-apple'></i> {$l.link_ios}</a></li>
{/if}
				<li class="list-group-item"><a  class="btn btn-sm btn-info btn-block" target="_blank" href="{$data.prefs.url_server}" target='_blank'><i class='fa fa-fw fa-exchange'></i> {$l.link_server}</a></i>
			</ul>
			</div>
		</div>


		<div id="block_all_players" class="text-center">
			<div class="panel-heading text-center">{$l.all_players}</div>

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



{/capture}
{include file="{$p.template}/layout.tpl"}