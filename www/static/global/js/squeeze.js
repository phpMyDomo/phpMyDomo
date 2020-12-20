
jQuery( document ).ready(function() {
	
	/* ----- Select Last Player ------------------ */
	selected_player_jsid		=Cookies.get('sqz_player');	
	
	var last_player=$('#jsPlayer_'+selected_player_jsid);
	if(! last_player.length > 0){
		last_player=$('.jsSqzPlayer').first();
	}
	JqzSelectPlayer(last_player);
	

	/* ----- Selected Player ------------------ */
	$('.jsSqzPlayer .jsPlayerHead').on('click', function(){
			var player=$(this).closest('.jsSqzPlayer');
			JqzSelectPlayer(player);
	});

	/* Load master playlists ----- */
	SqzAjaxFetchPlaylists();



	/* ----- Selected Playlist to add ------------------ */
	selected_pl_add_id	=Cookies.get('sqz_playlist_add');
	$('.jsSqzSelectPlaylistAdd').val(selected_pl_add_id);

	$('.jsSqzSelectPlaylistAdd').on('change', function(){
		selected_pl_add_id = $(this).val(); //$(this).val()
		// console.log('change cookie '+selected_pl_add_id);
		Cookies.set('sqz_playlist_add', selected_pl_add_id, { expires: 365 });
		SqzUpdatePlaylistAddSelectedOption();
	});

	/* Watch ProgressBar clicks  */
	$('.jsSqzProgress').parent().on('click', function(e){
		var player	=$(this).closest('.jsSqzPlayer');
		var playerid=	player.data('id')
		
		var x 		= e.pageX - $(this).offset().left ;
		var max 	= $(this).width();
		var ratio	= x / max;
		var new_position=Math.round( last_data.players[playerid].song.duration * ratio ) ;
		SqzRequestButton(player.data('id'),'time',new_position,'');
		//current_times[selected_player_jsid]=new_position;
		setTimeout(function(){
				SqzAjaxFetchPlayer(playerid, playlist_max +1);
		}, 200);

	});

	/* ----- Buttons ------------------ */
	$(".jsSqzBut").click(function(e){
		e.preventDefault();
		var type=$(this).attr('data-type');
		var id	=$(this).attr('data-id');
		var v1	=$(this).attr('data-v1');
		var v2	=$(this).attr('data-v2');
		var do_reload=true;
		var player=$(this).closest('.jsSqzPlayer');

		if(type=='cue'){
			SqzSetCue(v1,v2);
			return;
		}
		else if(type=='loop'){
			SqzSetLoop(v1);
			return;
		}
		else if(type=="pl_add"){
			SqzAddCurrentSongToSelectedPlaylist(player);
			return;
		}
		
		if(v1=='voldown' || v1=='volup'){
			var el_volume=SqzGetElementsHavingDataField(player, 'volume');
			var volume=parseInt(el_volume.data('value'));

			v2=parseInt(v2);
			if(v1=='voldown'){
				volume=volume - v2;
				if(volume < 0){volume=0;}
			}
			if(v1=='volup'){
				volume=volume + v2;
				if(volume > 100){volume=100;}
			}
			//el_volume.data('value',volume);
			SqzRefreshField(el_volume,{ volume: volume });
			do_reload=false;
		}
		
		SqzRequestButton(id,type,v1,v2);
		
		if(type=='button'){
			if((v1=='play' || v1=='pause' || v1=='stop')){
				SqzRefreshPlayerFieldValues(player,'mode', v1);
			}
			else if(v1=='rew.single' || v1=='fwd.single'){
				SqzRefreshPlayerFieldValues(player,'mode', 'stop');
				//SqzRefreshPlayerFieldValues(player,'remain_icon',3);
			}
			//SqzRazCounter(player);
		}

		if(do_reload){
			setTimeout(function(){
    			/* console.log("Force Reload"); */
				//SetIntervalRefresh( 'states' , 	pmd_sqz_prefs.refresh_states);
				SqzAjaxFetchPlayer(id, playlist_max +1);
			}, 200);
		}
	});
	
	
	/* ----- Keyboard -------------------------------------- */
	$(document).keypress(function(e) {
  		var jsid=selected_player_jsid;
  		var c=e.charCode;
  		var l=String.fromCharCode(c);
  		if( l == pmd_sqz_prefs.key_cue_in_set) {
	  		e.preventDefault();
  			SqzSetCue(jsid,'in');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_in_nudge1down) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'in',pmd_sqz_prefs.cue_nudge1,'-');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_in_nudge1up) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'in',pmd_sqz_prefs.cue_nudge1,'+');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_in_nudge2down) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'in',pmd_sqz_prefs.cue_nudge2,'-');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_in_nudge2up) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'in',pmd_sqz_prefs.cue_nudge2,'+');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_in_jump) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'cue_in_jump');
  		}

  		else if( l == pmd_sqz_prefs.key_cue_out_set) {
	  		e.preventDefault();
  			SqzSetCue(jsid,'out');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_out_nudge1down) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'out',pmd_sqz_prefs.cue_nudge1,'-');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_out_nudge1up) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'out',pmd_sqz_prefs.cue_nudge1,'+');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_out_nudge2down) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'out',pmd_sqz_prefs.cue_nudge2,'-');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_out_nudge2up) {
	  		e.preventDefault();
  			SqzNudgeCue(jsid,'out',pmd_sqz_prefs.cue_nudge2,'+');
  		}
  		else if( l == pmd_sqz_prefs.key_cue_out_jump) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'cue_out_jump');
  		}

  		else if( l == pmd_sqz_prefs.key_cue_loop) {
	  		e.preventDefault();
  			SqzSetLoop(jsid);
  		}

  		else if( l == pmd_sqz_prefs.key_play) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'play');
  		}
  		else if( l == pmd_sqz_prefs.key_pause) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'pause');
  		}

  		else if( l == pmd_sqz_prefs.key_prev) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'prev');
  		}
  		else if( l == pmd_sqz_prefs.key_next) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'next');
  		}

  		else if( l == pmd_sqz_prefs.key_rw2) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'rw2');
  		}
  		else if( l == pmd_sqz_prefs.key_rw1) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'rw1');
  		}
  		else if( l == pmd_sqz_prefs.key_ff1) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'ff1');
  		}
  		else if( l == pmd_sqz_prefs.key_ff2) {
	  		e.preventDefault();
  			SqzClickButton(jsid,'ff2');
  		}
  		
  		else{
			// console.log(l +" :  "+ c);	
  		}

	});
	
	SetIntervalRefresh( 'states' , 	pmd_sqz_prefs.refresh_states);
	SetIntervalRefresh( 'counters' , pmd_sqz_prefs.refresh_counters);

	SqzLoopFetchPlayers(1);
});




/* ########################################################################################################*/	
var playlist_max=pmd_sqz_prefs.songs;
var current_times={};
var cues={};
var loops={};
var last_refresh_date=Date.now;
var selected_player_jsid='';
var selected_pl_add_id='';
var last_data={
	players : {},
	song_ids : {},
};


/* ----------------------------------------------------------------------------------- */	
function JqzSelectPlayer(player){
	selected_player_jsid=player.attr('data-jsid');
	Cookies.set('sqz_player',selected_player_jsid, { expires: 365 })
	
	/* set jsSelected ----- */
	$('.jsSqzPlayer').removeClass('jsSelected');
	player.addClass('jsSelected');

	/* Put player on top----- */
	var first_player=$('.jsSqzPlayer').first();
	if(! player.is( first_player )){
		player.insertBefore(first_player);
	}
			
	/* show only this player ----- */
	$('.jsPlayerBody').slideUp(200);
	player.find('.jsPlayerBody').slideDown(270);
	$(window).scrollTop(0);
	
	/* Update Current player Infos block ----- */
	SqzRefreshInformationData(player);
}
/* ----------------------------------------------------------------------------------- */	
function SqzGetElementsHavingDataField(player,field){
	return player.find(".jsSqzData[data-field='"+field+"']");
}
/* ----------------------------------------------------------------------------------- */	
function SqzRefreshPlayerFieldValues(player,field, value, h_value){
	var elements=SqzGetElementsHavingDataField(player,field);
	elements.each(function(index){
		var o={};
		o[field]		=value;
		o['h_'+field]	=h_value;
		SqzRefreshField( $(this), o );
	});	
}
/* ----------------------------------------------------------------------------------- */	
function SqzRequestButton(id,type,v1,v2){
	var uniqid=Date.now();
	var url='?do=ajax&act=but&id=' + id + "&type="  + type + "&v1=" + v1 + "&v2="+ v2+'&time='+uniqid;
	/* console.log(url); */
	$.get(url,function(data){});
}
/* ----------------------------------------------------------------------------------- */	
function SqzClickButton(jsid,but){
	$('#jsPlayer_'+jsid).find('.jsSqzBut_'+but).trigger('click');
}
/* ----------------------------------------------------------------------------------- */	
function SqzLoopFetchPlayers(init){
	SqzAjaxFetch(init);
	
}
/* ----------------------------------------------------------------------------------- */	
function SqzAjaxFetchPlaylists(){
		var url='?do=ajax&act=playlists';
		$.getJSON(url,function(data){
			var html='';
			$.each(data,function(k,row){
				html +='<option value="'+row['id']+'">'+row['playlist']+"</option>\n";
			});
			$('.jsSqzSelectPlaylistAdd').html(html);
			SqzUpdatePlaylistAddSelectedOption();
			/*Change Selected Menu Option -------- */
			//$('.jsSelected .jsSqzSelectPlaylistAdd').trigger('change');
		});
}

/* ----------------------------------------------------------------------------------- */	
function SqzAddCurrentSongToSelectedPlaylist(player){
	var playerid	=player.data('id');
	var song_title	=encodeURIComponent(last_data.players[playerid].song.title);
	var song_url	=encodeURIComponent(last_data.players[playerid].song.url);
	var playlist_id = $('.jsSqzSelectPlaylistAdd').val();
	var url='?do=ajax&act=pl_add&id='+playlist_id+"&url="+song_url+"&title="+song_title;;
	$.getJSON(url,function(data){
		var obj=player.find('.jsSqzSelectPlaylistAdd');
		obj.fadeTo(100, 0.1).fadeTo(200, 1.0);
	});
}

/* ----------------------------------------------------------------------------------- */	
function SqzUpdatePlaylistAddSelectedOption(){
	// console.log('PL: val=' +selected_pl_add_id);
	if(selected_pl_add_id !='' && selected_pl_add_id !='null'){
		var selector='.jsSqzSelectPlaylistAdd OPTION[value='+selected_pl_add_id+']';	//.jsSelected 
		// console.log('Select val=' +selected_pl_add_id +' , Selctor='+selector);
		$(selector).attr('selected', 'selected');
	}
}


/* ----------------------------------------------------------------------------------- */	
function SqzAjaxFetchPlayer(playerid,limit){
	SqzAjaxFetch(false, limit, playerid);
}
/* ----------------------------------------------------------------------------------- */	
function SqzAjaxFetch(init, limit, playerid){
		if(limit === undefined || limit === null || limit == '' ){
			limit=playlist_max + 1;
		}
		var url='?do=ajax&act=players&limit='+limit;
		if(playerid !== undefined && playerid !== null && playerid != '' ){
			url=url+'&id='+playerid;
		}

		$.getJSON(url,function(data){
			last_refresh_date=Date.now;
			$.each(data, function(player_id, player_row){
				var jsid =player_row.f_jsid;

				/* store to vars */
				current_times[jsid]=player_row.time;
				last_data.players[player_id]=player_row;
				if(init == true){
					cues[jsid]={in:0,out:0};
					loops[jsid]=false;
				}
				
				var pid=	$('#jsPlayer_'+player_row.f_jsid);

				/* --- Refresh button states ---- */
				pid.find('.jsSqzBut').each(function(){
					var but			=$(this);
					var data_mode	=but.attr('data-mode');
					var data_type	=but.attr('data-type');
					if(data_type=='button' || data_type=='playlist'){
						but.removeClass('on');
					}
					$.each(player_row.f_states, function(button, val){
						if(data_mode == button){
							but.addClass('on');
						}
					});
				});

				/* -- Refresh Title, current position --*/
				if( player_row.song == undefined || player_row.song === null){
					player_row.song={};
				}
				SqzRefreshAllData(pid, player_row);

				/* current player ------------- */
				SqzRefreshInformationData(pid);

				var song_id=player_row.song.id;
				if(last_data.song_ids[player_row.playerid] != song_id){
					//SqzAjaxFetch(false, 5, playerid);
					//console.log('Changing track for player '+ player_row.playerid);
					last_data.song_ids[player_row.playerid] = song_id;
				}
				

				/* Current Playlist */
				SqzRefreshPlaylist(pid, player_row.playlist, player_row.song.type);
				//console.log('Reloading...'+ current_times[player_row.f_jsid] + ' = '+SqzFormatTime(current_times[player_row.f_jsid], true));
				
					
			});
			//SqzLoopRefreshCounter();
		});
}
/* ----------------------------------------------------------------------------------- */	
function SqzRefreshPlaylist(player, songs, type){
	var target=player.find('.jsSqzPlaylist');
	if(type !='file'){
		target.html();
		return true;
	}
	if(1 in songs){
		songs.shift(); //remove first (current) song
		var html='';
		$.each(songs, function(i, song){
			html +="<div class='pl_song'>";
				html +="<span class='song_li'><i class='fa fa-check'></i></span>";
				html +="<span class='song_artist'>"		+song.h_artist	+"</span>";
				if(song.album !=''){
					html +="<span class='song_album'><i class='fa fa-chevron-circle-right'></i>"	+song.h_album		+"</span>";
				}
				if(song.h_artist !='' || song.h_album !=''){
					html +="<span class='song_sep'>-</span>";
				}
				if(song.h_track !=''){
					html +="<span class='song_track'>"	+song.h_track		+"</span>";
				}
				if(song.h_title !=''){
					html +="<span class='song_title'>"	+song.h_title		+"</span>";
				}
				html +="<span class='song_duration'>"	+song.h_duration+"</span>";
				if(song.bpm !=''){
					html +="<span class='song_bpm'><i class='fa fa-heartbeat'></i>"		+song.bpm		+"</span>";
				}
			html +="</div>\n";
		});
		target.html(html);		
	}
}
/* ----------------------------------------------------------------------------------- */	
function SqzRefreshAllData(player, data){
	SqzRefreshData(player, data);
	SqzRefreshData(player, data.song);
	SqzRefreshData(player, data.song.links);
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshData(parent, data){
	parent.find('.jsSqzData').each(function(index){
		SqzRefreshField( $(this), data );
	});
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshInformationData(player){
	if(player.hasClass('jsSelected')){	
		var playerid=player.data('id');
		if(playerid in last_data.players){
			SqzRefreshData( $('.jsSqzInformation'), last_data.players[playerid]);
			SqzRefreshData( $('.jsSqzInformation'), last_data.players[playerid].song);
		}
	}
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshField(el, row){
	var field = el.data('field');
	var type = el.data('type');
	if(row == undefined){
		return false;
	}
	if(field in row){
		var value=row[field];
		var is_empty=false;
		if( value=='' || value==0 || value == null){
			is_empty=true;
		}
		//console.log(el.parent().attr('class') + ', f=' + field + ', v=' + row[field]);
		if(type == 'link'){
			el.attr('data-value',value.href);
			el.attr('href',	value.href);
			el.attr('title',value.title);
		}
		else if(type == 'image'){
			el.attr('data-value', value);
			el.attr('src',	value);
		}
		else if(type == 'icon'){
			el.attr('data-value', value);
			var icons=el.data('icons');
			el.find('I').attr('class', 'fa fa-'+icons[value] + " state_" + value);
		}
		else {	//if(type == 'field')
			el.attr('data-value',value);
		
			var html=value;
			var h_field="h_"+field;
			if( h_field in row ){
				html=row[h_field];
			}

			var h_default	= el.data('h_default');
			var noblank		= el.data('noblank');
			if( is_empty ){
				html=h_default;
				if(noblank==1){
					html='';
				}
			}

			var post =el.data('post');
			if(post != "" && value !='' && value !=0  ){
				html=html+"<u>"+post+"</u>";
			}
			
			el.html(html);
		}
		
		if( is_empty ){
			el.parent().removeClass('on').removeClass('off').addClass('off');
		}
		else{
			el.parent().removeClass('on').removeClass('off').addClass('on');
		}
	}
}


/* ----------------------------------------------------------------------------------- */	
function SqzLoopRefreshCounter() {
    
    /*refresh counters*/
    var elapsed = Date.now() - last_refresh_date;
	if(isNaN(elapsed)){elapsed=0;}
	last_refresh_date =Date.now();
	$.each(current_times, function(jsid, ctime) {
		var player=$('#jsPlayer_'+jsid);
		var playerid=player.data('id');

		/* skip if not playing --------- */
		var current_mode = last_data.players[playerid].mode;
		if(current_mode !='play' ){
			if(current_mode =='stop' ){
				SqzRazCounter(player);
			}
			return true;
		}

		/* set times----- */
		current_times[jsid] = ctime + (elapsed / 1000);
		var this_time=current_times[jsid];

		/* Update LCD time Display  --------------------*/
		/*	console.log(jsid + ' => ' + ctime + ' + ' + elapsed); */
		SqzRefreshPlayerFieldValues(player, 'time', this_time , SqzFormatTime(Math.round(this_time)));		
		player.find('.jsLcdMin').html(SqzFormatTimeLcd(this_time, 'min'));
		player.find('.jsLcdSec').html(SqzFormatTimeLcd(this_time, 'sec'));
		player.find('.jsLcdMs').html(SqzFormatTimeLcd(this_time, 'ms10'));
		
		/* Update LCD remaining Display ------ */
		var remain		=0;
		var h_remain	="";
		var dur		=last_data.players[playerid].song.duration;
		if( dur > 0){
			remain	= dur - this_time;
			h_remain = SqzFormatTime( remain );
		}
		SqzRefreshPlayerFieldValues(player,'remain',remain, h_remain);

		/* Update LCD remaining Icon + progress bar ------ */
		if(this_time > 0){
			var steps=3;
			var state=Math.abs(Math.round( (steps -1) * this_time / dur ));
			//console.log(state);
			SqzRefreshPlayerFieldValues(player,'remain_icon',state);

			var state_perc=Math.abs(Math.round( 100 * this_time / dur ));
			player.find('.jsSqzProgress').css('width',state_perc+'%');
			
		}

		/* Set ff & rw times --------------- */
		player.find('.jsSqzBut_rw1').attr('data-v1', Math.max(this_time - pmd_sqz_prefs.scroll_time1 , 0));
		player.find('.jsSqzBut_ff1').attr('data-v1', Math.min(this_time + pmd_sqz_prefs.scroll_time1 , dur));
		player.find('.jsSqzBut_rw2').attr('data-v1', Math.max(this_time - pmd_sqz_prefs.scroll_time2 , 0));
		player.find('.jsSqzBut_ff2').attr('data-v1', Math.min(this_time + pmd_sqz_prefs.scroll_time2 , dur));

		/* process loops --------- */
		if(loops[jsid]==true && this_time > cues[jsid]['out'] ){
			//this_time=cues[jsid]['in'];
			current_times[jsid]=cues[jsid]['in'];
			SqzRequestButton(playerid,'time',cues[jsid]['in'],'');
		}
		
	});
}

/* ----------------------------------------------------------------------------------- */	

function SqzRazCounter(player){
	player.find('.jsLcdMin').html('00');
	player.find('.jsLcdSec').html('00');
	player.find('.jsLcdMs').html('--');
	SqzRefreshPlayerFieldValues(player, 'remain', 0, '');
}

/* ----------------------------------------------------------------------------------- */	
function SqzSetCurrentTimes() {
    var elapsed = Date.now() - last_refresh_date;
	if(isNaN(elapsed)){elapsed=0;}
	last_refresh_date =Date.now();
	$.each(current_times, function(jsid, ctime) {
		current_times[jsid] = ctime + (elapsed / 1000);
	});
}

/* ----------------------------------------------------------------------------------- */	
function SqzFormatTimeLcd(sec_num,mode){
	var ms		= Math.round((sec_num - Math.floor(sec_num))*1000);
	sec_num 	= Math.round(sec_num);
	var hours   = Math.floor(sec_num / 3600);
	var minutes = Math.floor((sec_num - (hours * 3600)) / 60);	
	var seconds = sec_num - (hours * 3600) - (minutes * 60);
	if (hours   < 10) {hours   = "0"+hours;}
	if (minutes < 10) {minutes = "0"+minutes;}
	if (seconds < 10) {seconds = "0"+seconds;}
	
	if(mode=='min'){
		return minutes;
	}
	else if(mode=='sec'){
		return seconds;
	}
	else if(mode=='ms'){
		if (ms < 10) 		{ms = "00"+ms;}
		else if (ms < 100) 	{ms = "0"+ms;}
		return ms;
	}
	else if(mode=='ms10'){
		ms=Math.round( ms /10);
		if(ms > 99)		{ms=99;}
		if (ms < 10) 	{ms = "0"+ms;}
		return ms;
	}
}

/* ----------------------------------------------------------------------------------- */	
function SqzFormatTime(sec_num, with_ms){
	var out=SqzFormatTimeLcd(sec_num,'min') +  ':' + SqzFormatTimeLcd(sec_num,'sec');
	if(with_ms == true){
		out= out +  '.' + SqzFormatTimeLcd(sec_num,'ms')
	}
	if( parseInt(sec_num) == 0){
		out="--:--";
		if(with_ms == true){
			out= out +  '.---';
		}
	}
    return out;
}

/* ----------------------------------------------------------------------------------- */	
function SqzSetLoop(jsid){
	var but=$('#jsPlayer_'+jsid).find('.jsSqzBut_loop');
	if(but.hasClass('on')){
		loops[jsid]=false;
		but.removeClass('on');
	}
	else{
		if(cues[jsid]['in'] < cues[jsid]['out']){
			loops[jsid]=true;
			but.addClass('on');
		}
	}
}

/* ----------------------------------------------------------------------------------- */	
function SqzNudgeCue(jsid,point,offset ,dir) {
	offset= offset / 1000;
	if(dir=='-'){
		offset = - offset;
	}
	var ctime=cues[jsid][point] + offset;
	SqzStoreAndDisplayCue(jsid,point,ctime);
}
/* ----------------------------------------------------------------------------------- */	
function SqzSetCue(jsid,point) {
	SqzSetCurrentTimes();
	var ctime=current_times[jsid] - pmd_sqz_prefs.cue_offset/1000;
	SqzStoreAndDisplayCue(jsid,point,ctime);
	if(cues[jsid]['out'] < cues[jsid]['in']){
		SqzStoreAndDisplayCue(jsid,'out', Math.floor(cues[jsid]['in']) + 2);
	}
}

/* ----------------------------------------------------------------------------------- */	
function SqzStoreAndDisplayCue(jsid,point,ctime){
	cues[jsid][point]=ctime;
	var pid=$('#jsPlayer_'+jsid);
	pid.find('.jsSqzBut_cue_'+point+'_jump').html( SqzFormatTime(ctime, true) );
	pid.find('.jsSqzBut_cue_'+point+'_jump').attr( 'data-v1', ctime );
}

/* ----------------------------------------------------------------------------------- */	
var global_sqz_timeout={};
global_sqz_timeout.states={
	id : null,
	method : "SqzLoopFetchPlayers()"
};
global_sqz_timeout.counters={
	id : null,
	method : "SqzLoopRefreshCounter()"
};

function SetIntervalRefresh(name, ctime){
/* 	console.log('SetIntervalRefresh '+name+' to '+ctime); */
	clearTimeout(global_sqz_timeout[name]['id']);
	if(ctime > 0){
		global_sqz_timeout[name]['id']=setInterval(global_sqz_timeout[name]['method'], ctime);		
	}
}

