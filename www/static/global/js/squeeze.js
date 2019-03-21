
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
		var player=$(this).closest('.jsSqzPlayer');
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
				SqzSetDataFieldValues(player,'mode', v1);
			}
			else if(v1=='rew.single' || v1=='fwd.single'){
				SqzSetDataFieldValues(player,'mode', 'stop');
				//SqzSetDataFieldValues(player,'remain_icon',3);
			}
			//SqzRazCounter(player);
		}

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
function SqzGetElementsHavingDataField(player,field){
	return player.find(".jsSqzData[data-field='"+field+"']");
}

/* ----------------------------------------------------------------------------------- */	
function SqzGetDataFieldValue(player,field){
	var elements=SqzGetElementsHavingDataField(player,field);
	return elements.first().data('value');
}
/* ----------------------------------------------------------------------------------- */	
function SqzSetDataFieldValues(player,field, value, h_value){
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

				SqzRefreshAllData(pid, player);

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
				
				/* current player ------------- */
	  			if(jsid == selected_player_jsid){
	  				var current_info=$('.jsCurrentPlayer .jsCurrentPlayerBody');
	  				if( player.song.f_url_img !==null && player.song.url_img !==undefined && player.song.url_img !=''){
	  					current_info.html("<img src='"+player.song.url_img+"' width=100%>");
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
function SqzRefreshAllData(player, data){
	SqzRefreshData(player, data);
	SqzRefreshData(player, data.song);
	SqzRefreshData(player, data.song.links);
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshData(player, data){
	player.find('.jsSqzData').each(function(index){
		SqzRefreshField( $(this), data );
	});
}

/* ----------------------------------------------------------------------------------- */	
function SqzRefreshField(el, row){
	var field = el.data('field');
	var type = el.data('type');
	if(field in row){
		var value=row[field];
		//console.log(el.parent().attr('class') + ', f=' + field + ', v=' + row[field]);
		if(type == 'link'){
			el.data('value',value.href);
			el.attr('href',	value.href);
			el.attr('title',value.title);
		}
		else if(type == 'icon'){
			el.data('value',value);
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
			var post =el.data('post');
			if(post != "" && value !='' && value !=0  ){
				html=html+"<u>"+post+"</u>";
			}
			
			if( value=='' || value=='0' || value == null){
				el.parent().removeClass('on').removeClass('off').addClass('off');
			}
			else{
				el.parent().removeClass('on').removeClass('off').addClass('on');
			}
			
			el.html(html);
		}
	}
}


/* ----------------------------------------------------------------------------------- */	
function SqzRefreshCounter() {
    
    /*refresh counters*/
    var elapsed = Date.now() - last_refresh_date;
	if(isNaN(elapsed)){elapsed=0;}
	last_refresh_date =Date.now();
	$.each(current_times, function(jsid, ctime) {
		var pid=$('#jsPlayer_'+jsid);

		/* skip if not playing --------- */
		var current_mode = SqzGetDataFieldValue(pid,'mode');
		if(current_mode !='play' ){
			if(current_mode =='stop' ){
				SqzRazCounter(pid);
			}
			return true;
		}

		/* set times----- */
		current_times[jsid] = ctime + (elapsed / 1000);
		var this_time=current_times[jsid];

		/* Update LCD time Display  --------------------*/
		/*	console.log(jsid + ' => ' + ctime + ' + ' + elapsed); */
		SqzSetDataFieldValues(pid, 'time', this_time , SqzFormatTime(Math.round(this_time)));		
		pid.find('.jsLcdMin').html(SqzFormatTimeLcd(this_time, 'min'));
		pid.find('.jsLcdSec').html(SqzFormatTimeLcd(this_time, 'sec'));
		pid.find('.jsLcdMs').html(SqzFormatTimeLcd(this_time, 'ms10'));
		
		/* Update LCD remaining Display ------ */
		var remain		=0;
		var h_remain	="--:--";
		var dur		=	SqzGetDataFieldValue(pid,'duration');
		if( dur > 0){
			remain	=dur - this_time;
			h_remain = SqzFormatTime( remain );
		}
		SqzSetDataFieldValues(pid,'remain',remain, h_remain);

		/* Update LCD remaining Icon ------ */
		if(this_time > 0){
			var steps=3;
			var state=Math.abs(Math.round( (steps -1) * this_time / dur ));
			//console.log(state);
			SqzSetDataFieldValues(pid,'remain_icon',state);
		}

		/* Set ff & rw times --------------- */
		pid.find('.jsSqzBut_rw1').attr('data-v1', Math.max(this_time - pmd_sqz_prefs.scroll_time1 , 0));
		pid.find('.jsSqzBut_ff1').attr('data-v1', Math.min(this_time + pmd_sqz_prefs.scroll_time1 , dur));
		pid.find('.jsSqzBut_rw2').attr('data-v1', Math.max(this_time - pmd_sqz_prefs.scroll_time2 , 0));
		pid.find('.jsSqzBut_ff2').attr('data-v1', Math.min(this_time + pmd_sqz_prefs.scroll_time2 , dur));

		/* process loops --------- */
		if(loops[jsid]==true && this_time >= cues[jsid]['out'] ){
			var playerid=pid.find('.jsSqzBut_play').attr('data-id');
			this_time=cues[jsid]['in'];
			SqzRequestButton(playerid,'time',cues[jsid]['in'],'');
		}
		
	});
}

/* ----------------------------------------------------------------------------------- */	

function SqzRazCounter(player){
	player.find('.jsLcdMin').html('00');
	player.find('.jsLcdSec').html('00');
	player.find('.jsLcdMs').html('--');
	SqzSetDataFieldValues(player, 'remain', 0, '');
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

