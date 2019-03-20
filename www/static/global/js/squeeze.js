
jQuery( document ).ready(function() {
	
	/* ----- Select Last Player ------------------ */
	selected_player_jsid=Cookies.get('sqz_player');	
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
	

	/* ----- Buttons ------------------ */
	$(".jsSqzBut").click(function(e){
		e.preventDefault();
		var type=$(this).attr('data-type');
		var id	=$(this).attr('data-id');
		var v1	=$(this).attr('data-v1');
		var v2	=$(this).attr('data-v2');
		var do_reload=true;
		if(type=='cue'){
			SqzSetCue(v1,v2);
			return;
		}
		if(type=='loop'){
			SqzSetLoop(v1);
			return;
		}
		if(v1=='voldown' || v1=='volup'){
			var id_player=$(this).closest('.jsSqzPlayer');
			var volume=parseInt(id_player.find('.jsSqzVolume').html());

			v2=parseInt(v2);
			if(v1=='voldown'){
				volume=volume - v2;
				if(volume < 0){volume=0;}
			}
			if(v1=='volup'){
				volume=volume + v2;
				if(volume > 100){volume=100;}
			}
			id_player.find('.jsSqzVolume').html(volume);
			 do_reload=false;
		}
		
		SqzRequestButton(id,type,v1,v2);

		if(do_reload){
			SetIntervalRefresh( 'states' , 	pmd_sqz_prefs.refresh_states);
			setTimeout(function(){
    			/* console.log("Force Reload"); */
				SqzRefreshAllStates();
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
	  		console.log(l +" :  "+ c);	
  		}

	});
	
	SetIntervalRefresh( 'states' , 	pmd_sqz_prefs.refresh_states);
	SetIntervalRefresh( 'counters' , pmd_sqz_prefs.refresh_counters);

	SqzRefreshAllStates(1);
});




/* ########################################################################################################*/	

var current_times={};
var cues={};
var loops={};
var last_refresh_date=Date.now;
var selected_player_jsid='';



/* ----------------------------------------------------------------------------------- */	
function JqzSelectPlayer(player){
	selected_player_jsid=player.attr('data-jsid');
	Cookies.set('sqz_player',selected_player_jsid, { expires: 365 })
	
	/* set jsSelected ----- */
	$('.jsSqzPlayer').removeClass('jsSelected');
	player.addClass('jsSelected');
			
	/* show only this player ----- */
	$('.jsPlayerBody').hide();
	player.find('.jsPlayerBody').show();
			
	/* Update Current player Infos block ----- */
	var player_name = player.find('.player_name').html();
	$('.jsCurrentPlayer .jsCurrentPlayerHead').html(player_name);
	$('.jsCurrentPlayer .jsCurrentPlayerBody').html('');
	
	/* Put player on top----- */
	var first_player=$('.jsSqzPlayer').first();
	if(! player.is( first_player )){
		player.insertBefore(first_player);
	}
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
function SqzJsidToPlayerId(jsid){
	return pid=$('#jsPlayer_'+jsid).find('.jsSqzBut_play').attr('data-id');
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshAllStates(init){
		var url='?do=ajax&act=state_all';
		/*	console.log('Reloading...'); */
		$.getJSON(url,function(data){
			last_refresh_date=Date.now;

			$.each(data, function(player_id, player){
				var jsid =player.f_jsid;
				current_times[jsid]=player.time;
				if(init == true){
					cues[jsid]={in:0,out:0};
					loops[jsid]=false;
				}
				
				var pid=	$('#jsPlayer_'+player.f_jsid);
				/* --- Refresh button states ---- */
				pid.find('.jsSqzBut').each(function(){
					var but			=$(this);
					var data_mode	=but.attr('data-mode');
					var data_type	=but.attr('data-type');
					if(data_type=='button' || data_type=='playlist'){
						but.removeClass('on');
					}
					$.each(player.f_states, function(button, val){
						if(data_mode == button){
							but.addClass('on');
						}
					});
				});
				/* -- Refresh Title, current position --*/
				//pid.find('.player_position').html(player.f_position);
				if( player.song == undefined || player.song === null){
					player.song={};
				}
				pid.find('.player_artist').html(player.song.f_artist);
				pid.find('.player_title').html(player.song.f_title);
				pid.find('.player_album').html(player.song.f_album);

				pid.find('.player_duration').html(player.song.f_duration).attr('rel', player.song.duration);
				pid.find('.player_etype').html(player.song.f_type);
				pid.find('.player_erate').html(player.song.f_rate);
				pid.find('.player_erate_unit').html(player.song.f_rate_unit);
				pid.find('.player_erate_info').html(player.song.f_rate_info);
				pid.find('.player_year').html(player.song.f_year);

				pid.find('.player_link_youtube').attr('href',player.song.f_url_youtube).attr('title', "Youtube : [ "+player.song.f_full_title+" ]");
				pid.find('.player_link_allmusic').attr('href',player.song.f_url_allmusic).attr('title', "AllMusic : [ "+player.song.f_full_title+" ]");
				pid.find('.player_link_google').attr('href',player.song.f_url_google).attr('title', "Google : [ "+player.song.f_full_title+" ]");
				
				pid.find('.jsSqzVolume').html(player.f_volume);
				/*
				if (player.status.remote){
					pid.find('.player_radio').show();
					pid.find('.player_radio_name').html(player.status.current_title);
					pid.find('.player_album').html('');
				}
				else{
					pid.find('.player_radio').hide();						
				}
				*/
				pid.find('.jsSqzBut_rw1').attr('data-v1',player.f_rw1);
				pid.find('.jsSqzBut_rw2').attr('data-v1',player.f_rw2);
				pid.find('.jsSqzBut_ff1').attr('data-v1',player.f_ff1);
				pid.find('.jsSqzBut_ff2').attr('data-v1',player.f_ff2);
				
				/* current player*/
				
	  			if(jsid == selected_player_jsid){
	  				var current_info=$('.jsCurrentPlayer .jsCurrentPlayerBody');
	  				if( player.song.f_url_img !==null && player.song.f_url_img !==undefined && player.song.f_url_img !=''){
	  					current_info.html("<img src='"+player.song.f_url_img+"' width=100%>");
	  				}
	  				else{
	  					current_info.html('');	  					
	  				}
	  			}

				//console.log('Reloading...'+ current_times[player.f_jsid] + ' = '+SqzFormatTime(current_times[player.f_jsid], true));
			});
			//SqzRefreshCounter();
		});
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshCounter() {
    
    /*refresh counters*/
    var elapsed = Date.now() - last_refresh_date;
	if(isNaN(elapsed)){elapsed=0;}
	last_refresh_date =Date.now();
	$.each(current_times, function(jsid, ctime) {
		var pid=$('#jsPlayer_'+jsid);
		/* skip if not playing */
		if(! pid.find('.jsSqzBut_play').hasClass('on')){
			return true;
		}

		/* set counter and display*/
		current_times[jsid] = ctime + (elapsed / 1000);
		/*	console.log(jsid + ' => ' + ctime + ' + ' + elapsed); */
		pid.find('.jsCurTime').html(SqzFormatTime(current_times[jsid]) );
		pid.find('.jsLcdMin').html(SqzFormatTimeLcd(current_times[jsid], 'min'));
		pid.find('.jsLcdSec').html(SqzFormatTimeLcd(current_times[jsid], 'sec'));
		pid.find('.jsLcdMs').html(SqzFormatTimeLcd(current_times[jsid], 'ms10'));
		
		var remain="--:--";
		var dur = parseInt(pid.find('.player_duration').attr('rel'));
		if( dur > 0){
			remain = SqzFormatTime( dur - current_times[jsid]);
		}
		pid.find('.jsRemain').html(remain);


		/* process loop */
		if(loops[jsid]==true && current_times[jsid] >= cues[jsid]['out'] ){
			var playerid=pid.find('.jsSqzBut_play').attr('data-id');
			current_times[jsid]=cues[jsid]['in'];
			SqzRequestButton(playerid,'time',cues[jsid]['in'],'');
		}
		
	});
}
/* ----------------------------------------------------------------------------------- */	
function SqzRefreshCounterTimes() {
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
	var ms		= Math.round((sec_num - Math.floor(sec_num))*1000);
	sec_num 	= Math.round(sec_num);
    var hours   = Math.floor(sec_num / 3600);
    var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
    var seconds = sec_num - (hours * 3600) - (minutes * 60);

    if (hours   < 10) {hours   = "0"+hours;}
    if (minutes < 10) {minutes = "0"+minutes;}
    if (seconds < 10) {seconds = "0"+seconds;}

    var out = minutes+':'+seconds;
	if(with_ms == true){
		if (ms < 10) 		{ms = "00"+ms;}
		else if (ms < 100) 	{ms = "0"+ms;}
		out = out + '.' + ms;
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
	SqzRefreshCounterTimes();
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
		interval : null,
		method : "SqzRefreshAllStates()"
	};
	global_sqz_timeout.counters={
		interval : null,
		method : "SqzRefreshCounter()"
	};
	
	function SetIntervalRefresh(name, ctime){
	/* 	console.log('SetIntervalRefresh '+name+' to '+ctime); */
		clearTimeout(global_sqz_timeout[name]['interval']);
		if(ctime > 0){
			global_sqz_timeout[name]['interval']=setInterval(global_sqz_timeout[name]['method'], ctime);		
		}
	}

