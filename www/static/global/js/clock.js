
jQuery( document ).ready(function() {

	/* ----- Digital clock ------------------ */
	function ClockUpdateDigital() {
		function pad(n) {
			return (n < 10) ? '0' + n : n;
		}

		var now = new Date();
		var clock = pad(now.getHours()) + '<span class="sep">:</span>' +
					pad(now.getMinutes()) + '<span class="sep">:</span>' +
					pad(now.getSeconds());

		$('#jsClockDigital').html(clock);

		var date = moment().locale('fr').format('dddd')+', '+moment().locale('fr').format('LL');
		$('#jsClockDate').html(date);

		//var delay = 1000 - (now % 1000);
		//setTimeout(ClockUpdateDigital, delay);
	};

	/* ----- Analog clock ------------------ */
	var clock_body_w	=$(document).width();
	var clock_body_h	=$(document).height();
	if(clock_body_w > clock_body_h){
		var clock_w	=$('.jsClockCol').width() * 0.95;
		if(clock_w > clock_body_h * 0.80){
			clock_w=clock_body_h * 0.70;
		}
	}
	else{
		var clock_w	=clock_body_h * 0.4;
	}

	//clock plugin constructor
	$('#jsClockAnalog').thooClock({
		size:clock_w,
		dialColor: 			pmd_clock.dialColor,
		dialBackgroundColor:pmd_clock.dialBackgroundColor,
		secondHandColor:	pmd_clock.secondHandColor,
		minuteHandColor:	pmd_clock.minuteHandColor,
		hourHandColor:		pmd_clock.hourHandColor,
		alarmHandColor:		pmd_clock.alarmHandColor,
		alarmHandTipColor:	pmd_clock.alarmHandTipColor,
		showNumerals:true,
		brandText:'phpMyDomo',
		brandText2:'World',
		onEverySecond:function(){
			ClockUpdateDigital();
		}
	});


	/* Timer -------------------------------------------------------- */
	var clock_bg_timeout;
	var clock_body_bg=$('BODY').css('background-color');
	var e_view		=$("#jsView");
	var e_view_alarm=$("#jsViewAlarm");
	var e_view_timer= $("#jsViewTimer");
	e_view_timer.countdown({autoStart: false});


	$("#jsButStop").click(function() {
		ClockAlarmStop();
	});
	$("#jsButStop").hide();

	/* modal  -------------------------------------------------------- */
	$("#jsButAlarm").hide();
	$("#jsModalTabs A").on('shown.bs.tab', function (e) {
		var panel=$(e.target).attr('href'); // newly activated tab
		if(panel=='#tab_timer'){
			$("#jsButTimer").show();
			$("#jsButAlarm").hide();
		}
		else{
			$("#jsButTimer").hide();
			$("#jsButAlarm").show();
		}
	});

	$("#jsButTimer").click(function() {
		var min=parseInt($('#jsInputTimerMin').val());
		var sec=parseInt($('#jsInputTimerSec').val());
		var a_time=moment().add(min,'m').add(sec,'s');
		ClockAlarmSet(a_time);
	});

	$("#jsButAlarm").click(function() {
		var hour=parseInt($('#jsInputAlarmHour').val());
		var min	=parseInt($('#jsInputAlarmMin').val());
		var a_time=moment({ hour:hour, minute:min });
		ClockAlarmSet(a_time);
	});





	
	/* --------------------------------------------------*/
	function ClockAlarmSet(moment){
		ClockAlarmStop();
		console.log('Alarm set to : '+moment.format('HH:mm:ss'));
	
		e_view_alarm.html(moment.format('HH:mm:ss'));		
		e_view_timer.countdown('destroy');
		e_view_timer.removeData("countdown");
		e_view_timer.countdown({
			autoStart: true,
			date: moment.toDate(),
			text: "%h:%m:%s",
			sync: true,
			resync: 60,
			pad: true,
			end: function(){ClockAlarmRing();}
		});
		$.fn.thooClock.setAlarm(moment.toDate());
		e_view.addClass('active');
		$("#jsButStop").show();

		ClockAudioSet();

	}

	/* --------------------------------------------------*/
	function ClockAlarmStop(){
		console.log('Alarm STOP');
		e_view_alarm.html('00:00:00');
		e_view_timer.countdown("destroy");
		e_view.removeClass('active');
		clearTimeout(clock_bg_timeout);
		$('BODY').css('background-color',clock_body_bg);
		$("#jsButStop").hide();
		$.fn.thooClock.clearAlarm();
		ClockAudioStop();
	}

	/* --------------------------------------------------*/
	function ClockAlarmRing(){
		console.log('Alarm ring');
		//$('#jsDivAlarmOff').show();
		if($('#jsInputLight').is(':checked')){
			ClockAlarmFlipBackground(0);
		}
		if($('#jsInputSound').is(':checked')){
			ClockAudioPlay();
		}
	}

	/* --------------------------------------------------*/
	function ClockAlarmFlipBackground(y){
			var color;
			if(y===1){
				color = '#000000';
				y=0;
			}
			else{
				color = '#FFFFFF';
				y+=1;
			}
			$('body').css('background-color',color);
			clock_bg_timeout = setTimeout(function(){ClockAlarmFlipBackground(y);},200);
	}

	/* --------------------------------------------------*/
	var clock_sound;
	function ClockAudioSet(){
		var audio_file=pmd_url_static + '/global/audio/clock/'+ $("#jsSelectSound").val();
		clock_sound = new Howl({
		urls: [
			audio_file+'.mp3',
			audio_file+'.ogg',
		],
		autoplay: false,
		volume: 1,
		loop: true
		});

	}
	function ClockAudioPlay(){
		if(clock_sound !== undefined){
			clock_sound.play();
		}
	}
	function ClockAudioStop(){
		if(clock_sound !== undefined){
			clock_sound.stop();
		}
	}






    /* Button Blinds & Shutter -------------------------------------- */
/*
    $('.jsButBlinds').click(function(e){
    	e.preventDefault();
    	var but		=$(this);
    	var address	=but.attr('data-address');
    	var target	=but.attr('data-target');
    	var invert	=but.attr('data-invert');
    	but.removeClass('active').addClass('active');
    	
    	var my_refresh_time = refresh_time_blinds;
    	    	
    	$.getJSON( ajax_url, { mode: "set", a: address, v: target, t: 'blinds', i: invert } )
  			.done(function( json ) {
			    but.removeClass('active');
  				if(json.status=='ok'){
					SetReload(my_refresh_time);
  					console.log('OK');
  				}
  				else{
  					console.log('ERROR');
  				}
			})
			.fail(function( jqxhr, textStatus, error ) {
			    but.removeClass('active');
				var err = textStatus + ", " + error;
				console.log( "Switch Request Failed: " + err );
			});    	
    });
*/


});


