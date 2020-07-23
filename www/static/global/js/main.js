/* var ajax_url='/ajax'; */
var refresh_time		=3;
//var refresh_time_blinds	=25;
var feedback_time		=0.001;

var sleep_time			=25;
var global_class_on		='btn-success';

var ow_stations_time=5;


jQuery( document ).ready(function() {
	
	/* Hide browser address bar -------------------------------------- */
	/Mobile/.test(navigator.userAgent) && !location.hash && setTimeout(function () {
		if (!pageYOffset) window.scrollTo(0, 1);
	}, 500);

	if($('#body_home').length){

		/* Sleep Mode Reload page -------------------------------------- */
		sleep_time	=$('#jsReload').attr('data-time');
		SetTimerSleep(sleep_time);


		/* Refresh Devices states ------------------------- */
		SetTimerRefresh(refresh_time);


		/* Button Blinds & Shutter -------------------------------------- */
		$('.jsButBlinds').click(function(e){
			e.preventDefault();
			SetTimerSleep(sleep_time);		
			var but		=$(this);
			var address	=but.attr('data-address');
			var target	=but.attr('data-target');
			var invert	=but.attr('data-invert');
			but.removeClass('active').addClass('active');
			
			//var my_refresh_time = refresh_time_blinds;
					
			$.getJSON( ajax_url, { mode: "set", a: address, v: target, t: 'blinds', i: invert } )
				.done(function( json ) {
					but.removeClass('active');
					if(json.status=='ok'){
						SetTimerRefresh(feedback_time);
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

		/* --------------------------------- */
		function GetButData(but){
			var but_group	=but.closest('.jsButGroup');
			var data={};
			data.type	=but_group.attr('data-type');
			data.address=but_group.attr('data-address');
			data.state	=but_group.attr('data-state');
			data.invert	=but_group.attr('data-invert');
			data.value	=but_group.attr('data-value');
			data.object	=but_group;
			return data;
		}

		/* Button Switch -------------------------------------- */
		$('.jsButSwitch').click(function(e){
			e.preventDefault();
			SetTimerSleep(sleep_time);		
			var but		=$(this);
			var data	=GetButData(but);
			var dim		=data.object.find('.jsButDimmer')
			var img		=but.find('IMG');

			var images={
				'on': img.attr('data-on'),
				'off': img.attr('data-off'),
			};
			
			but.removeClass('active').addClass('active');
			
			var target	='on';
			if(data.state=='on'){
				target='off'
			}

			if(data.type=='push'){
				but.addClass(global_class_on);
			}
			
			$.getJSON( ajax_url, { mode: "set", a: data.address, v: target, t: data.type, i: data.invert } )
				.done(function( json ) {
					but.removeClass('active');
					if(json.status=='ok'){
						SetTimerRefresh(feedback_time);
						console.log('OK');
						but.removeClass(global_class_on);
						img.attr('src',images[target]);
						data.object.attr('data-state',target);
						if(target=='on'){
							but.addClass(global_class_on);
							dim.html(100);
						}
						else{
							dim.html(0);
						}
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


		/* Button Selector ------------------------------------------------------------------------- */
		$('.jsButSelector').click(function(e){
			e.preventDefault();
			SetTimerSleep(sleep_time);		
			var but		=$(this);
			var data	=GetButData(but);

			var value	=but.attr('data-value');
			var but_group=but.closest('.jsButGroup').find('.jsButSelector');

			but.removeClass('active').addClass('active');
			
			//var my_refresh_time = refresh_time;
					
			$.getJSON( ajax_url, { mode: "set", a: data.address, v: value, t: 'selector' } )
			.done(function( json ) {
					but.removeClass('active');
					
					if(json.status=='ok'){
						but_group.removeClass(global_class_on);
						but.addClass(global_class_on);
						SetTimerRefresh(feedback_time);
						console.log('OK');
					}
					else{
						console.log('ERROR');
					}
				})
			.fail(function( jqxhr, textStatus, error ) {
					but.removeClass('active');
					var err = textStatus + ", " + error;
					console.log( "Selector Request Failed: " + err );
			});    	
		});



		/* Button Dimmer (to finish) --------------------------------------------------------------- */
		$('.jsButDimmer').each(function(){
			var but			=$(this);
			var address		=but.attr('data-address');
			var popover_id	='#jsDimmerPopover_'+ but.attr('data-js_address');
			var slider_id	='#jsSlider_'+ but.attr('data-js_address');
			
			but.popover({
				html: true,
				placement: 'bottom',
				trigger:'manual',
				content: $(popover_id).html()
			})
			.click(function(e){
				e.preventDefault();
				SetTimerSleep(sleep_time);		
				but.popover('toggle');
				var last_value=null;
				var value;
				var slider	=$(slider_id).slider({
					value: but.html(),
					formater: function(v) {
							return Math.round(v);
						}
					})
				.on('slide', $.throttle( 250, function (ev) {
						value = Math.round(ev.value);
						if(value != last_value){
							last_value= value;
							/* slider.slider('setValue', value); */
						
							/* Do Ajax Call, avoiding sending continous values */
							console.log('Sending Dim '+value);		
							$.getJSON( ajax_url, { mode: "set", a: address, v: value, t: 'dim_level' } )
								.done(function( json ) {
									if(json.status=='ok'){
										SetTimerRefresh(feedback_time);
										console.log('Dim OK '+value);
										but.html(value);
									}
									else{
										/* but.html(value); */
										console.log('Dim ERROR');
									}
								})
								.fail(function( jqxhr, textStatus, error ) {
									var err = textStatus + ", " + error;
									console.log( "Dim Request Failed: " + err );
							});
						}
				}));
			});
		});



		/* Button RGB ------------------------------------------------------------------------------------------ */
		function RgbGetPopoverContent(e){
			var js_address=$(this).data('js_address');
			var popover_content	=$('.jsRgbPopoverHidden_'+ js_address);
			popover_content.find('INPUT').attr('style',''); /* remove display:none */
			var html = popover_content.html();
			html ="<div class='jsRgbPopoverShown' data-js_address='"+js_address+"'>"+html+"</div>";
			return html;
		}
		
		function RgbShowColorPreview(js_address, value){
			var button_color=$('.jsButGroup_'+js_address+' .jsButColor');
			var cur_preview=$('.jsRgbPopoverShown .jsRgbPreview');
			var hid_preview=$('.jsRgbPopoverHidden_'+js_address+' .jsRgbPreview');
			button_color.css('background-color','#'+value);
			cur_preview.css('background-color','#'+value);
			hid_preview.css('background-color','#'+value);
		}

		$('.jsButColor').each(function(){
			var but				=$(this);
			var data			=GetButData(but);

			var js_address		=but.data('js_address');
			var popover_id		='.jsRgbPopoverHidden_'+ js_address;
			var popover_input	=$(popover_id).find('.jsRgbPopoverInput');
			var input_sel		='#jsInput_'+ js_address;
			//var colorpick_sel	='.jsColorPicker_'+ js_address;
			var last_value		=$(input_sel).val();
			but.css('background','#'+last_value);

			but.popover({
				//animation: false,
				html: true,
				container: 'body',
				placement: 'bottom',
				trigger:'manual',
				content: RgbGetPopoverContent //popover_input.html()
		})
		.click(function(e){
				e.preventDefault();
				SetTimerSleep(sleep_time);		
				but.popover('toggle');
				//e.stopPropagation();
			});
			
			but.on('shown.bs.popover', function(){
				var in_value=$(input_sel).attr('value');
				popover_input.html('');
				$(input_sel).wheelColorPicker({ layout: 'block', live: true, format: "hex", preview:true, animDuration: 0 });

				$('.jsRgbPreset').on('click', function(e){
					var color=$(this).data('rgb');
					$(input_sel).wheelColorPicker('setValue',color);
				});

				$(input_sel).on('colorchange', $.throttle( 250, function (ev) {
					SetTimerSleep(sleep_time);		
					var color=$(this).wheelColorPicker('getColor');
					var value=$(this).wheelColorPicker('getValue');
					var level=Math.round(color.v * 100)

					but.html(level);
					RgbShowColorPreview(js_address, value);
				
					/* Do Ajax Call, avoiding sending continous values */
					if(value != last_value){
						last_value= value;
						$.getJSON( ajax_url, { mode: "set", a: data.address, v: value, t: 'rgb_color' } )
							.done(function( json ) {
								SetTimerRefresh(feedback_time);
								if(json.status=='ok'){
									//console.log('Color set to '+ value);
								}
								else{
									/* but.html(value); */
									console.log('Color ERROR');
								}
							})
							.fail(function( jqxhr, textStatus, error ) {
								var err = textStatus + ", " + error;
								console.log( "Color Request Failed: " + err );
						});
					}
				}));
			});
		
			but.on('hidden.bs.popover', function(){
				/* move back to popover hidden div */
				var last_input_html=$(input_sel)[0].outerHTML;
				$(input_sel).wheelColorPicker('destroy');
				popover_input.html(last_input_html);
			});
			
			
		});
	} /* End Dashboard */


	if($('#body_admin_openwrt').length){
		var $i=0;
		$('.jsOwRouter').each(function(index){
			SetTimerOwStations(index,$(this),ow_stations_time + $i);
			$i++;
		});

		

	}


	/* Clear Popover on click outside  ------------------------------------------------------------------------ */
	$(document).on('click', function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
			//the 'is' for buttons that trigger popups
			//the 'has' for icons within a button that triggers a popup
			if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0 ) {                
				//(($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
				
				/* https://stackoverflow.com/questions/13442749/how-to-check-whether-a-twitter-bootstrap-popover-is-visible-or-not/13442857 */
				var isVisible = false;
				if($(this).data('bs.popover') !== undefined){
					isVisible = $(this).data('bs.popover').tip().hasClass('in');
				}
				//var isVisible = $('#element').data('bs.popover')._activeTrigger.click; //bs 4
				
				if(isVisible){
					console.log("visible");
					$(this).popover('toggle');
				}
			}
		});
	});



	/* Admin Devices DEBUG ------------------------------------------------------------------------ */

	$('#body_admin_devices .jsPopoverDebug').popover({
		trigger: 'click',
		html: true,
		template: '<div class="popover popover_debug" role="tooltip"><div class="arrow"></div><div class="popover-title"></div><div class="popover-content"></div></div>'
	});
	$('#body_admin_devices .jsPopoverDebug').on('show.bs.popover',function(){
		$(this).closest('TR').addClass('selected');
	});
	$('#body_admin_devices .jsPopoverDebug').on('hide.bs.popover',function(){
		$(this).closest('TR').removeClass('selected');
	});
	$('#body_admin_devices .jsPopoverDebug').on('shown.bs.popover',function(){
		var po=$(this).next();
		var new_top =parseInt( po.css('top')) + parseInt(po.height()/ 2) - 16;
		po.css('top',new_top + 'px');
	});

	

});

/* FUNCTIONS ################################################################################### */

/* ---------------------------------- */
function AjaxRefreshData(){
	$.getJSON( ajax_url, { mode: "list_devices" } )
		.done(function( json ) {
			//console.log(json);
			$.each(json.data,function(k,row){
				SetState(row);
			});

		})
		.fail(function( jqxhr, textStatus, error ) {
		});    	
}
/* ---------------------------------- */
function SetState(row){
	if(row.class=='command'){
		if(row.type=='switch' || row.type=='push' || row.type=='scene'){
			SetStateCommandSwitch(row);
			SetStateCommandView(row);
		}
		else if(row.type=='selector'){
			SetStateCommandSelector(row);
		}
		else if(row.type=='rgb' || row.type=='rgbw'){
			SetStateCommandSwitch(row);
			SetStateCommandColor(row);
		}
		else if(row.type=='dimmer'){
			SetStateCommandSwitch(row);
			SetStateCommandDimmer(row);
		}
	}
	else if(row.class=='sensor'){
		SetStateSensor(row);
	}
}

/*-------------------------------- */
function SetStateCommandView(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if(bgroup.hasClass('jsCommandView')){
		var value =row.state;
		value = value.charAt(0).toUpperCase() + value.slice(1);
		row.value=value;
		SetStateSensor(row);
	}
}

/*-------------------------------- */
function SetStateSensor(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var sens=bgroup.find(".jsSensorValue");
		var last_val=bgroup.attr('data-value');
		if(last_val !=row.value ){
			sens.html(row.value);
			bgroup.attr('data-value',row.value);
			sens.removeClass('changed').addClass('changed');
		}
		else{
			sens.html(row.value).css('background','none');
			sens.removeClass('changed');
		}
	}
}
/* ---------------------------------- */
function SetStateCommandSwitch(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var but=bgroup.find(".jsButSwitch");
		var img=but.find('img');
		if(row.type == 'scene' || row.type == 'push'){
			but.removeClass(global_class_on);
			img.prop('src', img.data('off'));
			bgroup.attr('data-state',row.state);
			bgroup.attr('data-invert',row.invert);
		}
		else{
			if(row.state=='on'){
				but.removeClass(global_class_on).addClass(global_class_on);
			}
			else if(row.state=='off'){
				but.removeClass(global_class_on);
			}	
			img.prop('src', img.data(row.state));
			bgroup.attr('data-state',row.state);
			bgroup.attr('data-invert',row.invert);
		}
	}
}
/* ---------------------------------- */
function SetStateCommandSelector(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var buttons=bgroup.find(".jsButSelector");
		var sel_but=buttons.filter("[data-value="+row.value+"]");	
		buttons.removeClass(global_class_on);
		sel_but.addClass(global_class_on);
		bgroup.attr('data-state',row.state);
		bgroup.attr('data-value',row.value);
	}
}
/* ---------------------------------- */
function SetStateCommandColor(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var but=bgroup.find(".jsButColor");
		but.html(row.value);
		bgroup.attr('data-value',row.value);
		but.css('background','#'+row.color_rgb);
	}
}

/* ---------------------------------- */
function SetStateCommandDimmer(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var but=bgroup.find(".jsButDimmer");
		but.html(row.value);
		bgroup.attr('data-value',row.value);
	}
}




var global_timer_refresh;
/* SetTimerRefresh -------------------------------------- */
function SetTimerRefresh(time){
	clearTimeout(global_timer_refresh);
	if(time > 0){
		global_timer_refresh=setTimeout("RefreshPage()", time * 1000);		
	}
}
/* RefreshPage -------------------------------------- */
function RefreshPage() {
		//location.reload();
		AjaxRefreshData();
		SetTimerRefresh(refresh_time);
};


var global_timer_sleep;
/* SetTimerSleep -------------------------------------- */
function SetTimerSleep(time){
	clearTimeout(global_timer_sleep);
	if(time > 0){
		global_timer_sleep=setTimeout("JumpSleep()", time * 1000);		
	}
}
/* Jump to Sleep Page -------------------------------------- */
function JumpSleep() {
	var reload_url=$('#jsReload').attr('data-url');
	if(reload_url !=''){
		window.location.href=reload_url;
	}
};


var global_timer_ow_stations=[];
/* SetTimerOwStations -------------------------------------- */
function SetTimerOwStations(index,obj,time){
	clearTimeout(global_timer_ow_stations[index]);
	if(time > 0){
		global_timer_ow_stations[index]=setTimeout(function(){
			RefreshOwStations(index,obj,time);
		}, time * 1000);
	}
}
function RefreshOwStations(index,obj,time){
	var query=obj.attr('data-query');
	var obj_loading=obj.find('.jsOwLoading');
	obj_loading.addClass('loading');
	$.getJSON( '?', { ajax: query } )
		.done(function( json ) {	
			if(json.error==0){
				/* stations ------- */
				var html="";
				var target;
				var blank_class="";
				var vendor_span="";
				$.each(json.data.stations, function(ifname,stations){
					html='';
					$.each(stations, function(mac,station){
						blank_class='';
						vendor_span="";
						if(station.info.ip==''){
							blank_class=' ow_stat_blank';
							vendor_span='<span class="ow_stat_vendor">'+station.info.vendor+'</span>';
						}
						html = html + '<li class="ow_stat'+blank_class+'">'
									+'<div class="ow_stat_1"><span class="ow_stat_mac">' 
									+ station.mac + '</span> <span class="ow_stat_name">'+station.info.name+'</span></div>'
									+'<div class="ow_stat_2"><span class="ow_stat_ip">' 
									+ vendor_span
									+ station.info.ip + '</span> <span class="ow_stat_host">'+station.info.host+'</span></div>'
									+"</li>\n";
					});
					target = obj.find('.jsOwIf_'+ifname+' .jsOwStations');
					target.html(html);
				});
				/* sys info ------- */
				html= (json.data.sys_info.load[0] /65535.0).toFixed(2)
							+ ', '+(json.data.sys_info.load[1] /65535.0).toFixed(2)
							+ ', '+(json.data.sys_info.load[2] /65535.0).toFixed(2);
				target = obj.find('.jsOwLoad');
				target.html(html);
			}
			else{
				console.log('ERROR');
			}
			obj_loading.removeClass('loading');
		})
		.fail(function( jqxhr, textStatus, error ) {
			var err = textStatus + ", " + error;
			console.log( "Ow Station Failed: " + err );
			obj_loading.removeClass('loading');
	});
	SetTimerOwStations(index, obj,time);
}



