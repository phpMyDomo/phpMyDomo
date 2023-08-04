/* var ajax_url='/ajax'; */
var refresh_time		=3;
//var refresh_time_blinds	=25;
var feedback_time		=0.001;
var sleep_time			=25;

var global_class_on		='btn-success';
var global_class_active	="btn-info";

var ow_ajax_timeout=10;
var ow_ajax_refresh=3;
var ow_tabulator;

var demo_mode=false;


jQuery( document ).ready(function() {
	
	/* Hide browser address bar -------------------------------------- */
	/Mobile/.test(navigator.userAgent) && !location.hash && setTimeout(function () {
		if (!pageYOffset) window.scrollTo(0, 1);
	}, 500);

	/* #### DASHBOARD ##################################################################### */
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
			but.removeClass(global_class_active).addClass(global_class_active);
			
			//var my_refresh_time = refresh_time_blinds;
					
			$.getJSON( ajax_url, { mode: "set", a: address, v: target, t: 'blinds', i: invert } )
				.done(function( json ) {
					but.removeClass(global_class_active);
					if(json.status=='ok'){
						SetTimerRefresh(feedback_time);
						console.log('OK');
					}
					else{
						console.log('ERROR');
					}
				})
				.fail(function( jqxhr, textStatus, error ) {
					but.removeClass(global_class_active);
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

			var parent_group = $(this).closest('.jsButGroup');			
			if( 
					parent_group.hasClass('jsButGroup_value') || 
					parent_group.hasClass('jsButGroup_therm') || 
					parent_group.hasClass('jsButGroup_selector') 
			 	){
				return false;
			}
			
			SetTimerSleep(sleep_time);		
			var but		=$(this);
			var data	=GetButData(but);
			var dim		=data.object.find('.jsButDimmer')
			var img		=but.find('IMG');

			var images={
				'on': img.attr('data-on'),
				'off': img.attr('data-off'),
			};
			
			but.removeClass(global_class_active).addClass(global_class_active);
			
			var target	='on';
			if(data.state=='on'){
				target='off'
			}

			if(data.type=='push'){
				but.addClass(global_class_on);
			}
			
			$.getJSON( ajax_url, { mode: "set", a: data.address, v: target, t: data.type, i: data.invert } )
				.done(function( json ) {
					but.removeClass(global_class_active);
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
					but.removeClass(global_class_active);
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

			but.removeClass(global_class_active).addClass(global_class_active);
			
			//var my_refresh_time = refresh_time;
					
			$.getJSON( ajax_url, { mode: "set", a: data.address, v: value, t: 'selector' } )
			.done(function( json ) {
					but.removeClass(global_class_active);
					
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
					but.removeClass(global_class_active);
					var err = textStatus + ", " + error;
					console.log( "Selector Request Failed: " + err );
			});    	
		});

		/* Button Popup Selector ------------------------------------------------------------------------- */
		$('.jsButPopup').on('change',function(e){
			e.preventDefault();
			SetTimerSleep(sleep_time);		
			var but		=$(this);
			var data	=GetButData(but);

			var value	=but.val();
			var but_group=but.closest('.jsButGroup').find('.jsButSelector');

			but.removeClass(global_class_active).addClass(global_class_active);
			
			//var my_refresh_time = refresh_time;
					
			$.getJSON( ajax_url, { mode: "set", a: data.address, v: value, t: 'selector' } )
			.done(function( json ) {
					but.removeClass(global_class_active);
					
					if(json.status=='ok'){
						but_group.removeClass(global_class_on);
						//but.addClass(global_class_on);
						SetTimerRefresh(feedback_time);
						console.log('OK');
					}
					else{
						console.log('ERROR');
					}
				})
			.fail(function( jqxhr, textStatus, error ) {
					but.removeClass(global_class_active);
					var err = textStatus + ", " + error;
					console.log( "Selector Popup Request Failed: " + err );
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



		/* Button Value ------------------------------------------------------------------------------------------ */
		$('.jsButValue').each(function(){
			var but				=$(this);
			var data			=GetButData(but);
			var address			=data.address;

			var js_address		=but.data('js_address');
			var popover_id		='.jsValuePopoverHidden_'+ js_address;
			var popover_input	=$(popover_id).find('.jsValuePopoverInput');
			var input_sel		='#jsInput_'+ js_address;

			but.popover({
				//animation: false,
				html: true,
				container: 'body',
				placement: 'bottom',
				trigger:'manual',
				content: ValueGetPopoverContent 
			})
			.click(function(e){
					e.preventDefault();
					SetTimerSleep(sleep_time);		
					but.popover('toggle');
			});
			
			but.on('shown.bs.popover', function(){
				popover_input.html('');
				$(input_sel).val(GetButData(but).value);
				$(input_sel).focus().select();

				$(input_sel).on('keyup',function(e) {
					if(e.key === 'Enter' || e.keyCode === 13) {
						but.popover('toggle');
					}
				});

			});
		
			but.on('hidden.bs.popover', function(){

				/* Do Ajax Call, avoiding sending continous values */
				var value=$(input_sel).val();
				console.log('Sending Value '+value);		
				$.getJSON( ajax_url, { mode: "set", a: address, v: value, t: 'value' } )
					.done(function( json ) {
						if(json.status=='ok'){
							SetTimerRefresh(feedback_time);
							console.log('Value OK '+value);
							//but.html(value);
						}
						else{
							console.log('Value ERROR');
						}
					})
					.fail(function( jqxhr, textStatus, error ) {
						var err = textStatus + ", " + error;
						console.log( "Value Request Failed: " + err );
				});


				/* move back to popover hidden div */
				var last_input_html=$(input_sel)[0].outerHTML;
				popover_input.html(last_input_html);
			});
			
			
		});

		function ValueGetPopoverContent(e){
			var js_address=$(this).data('js_address');
			var popover_content	=$('.jsValuePopoverHidden_'+ js_address);
			var html = popover_content.html();
			html ="<div class='jsValuePopoverShown' data-js_address='"+js_address+"'>"+html+"</div>";
			return html;
		}



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



	/* ### ADMIN: Devices DEBUG ################################################################# */

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






	/* #### ADMIN: OpenWRT ##################################################################### */

	if($('#body_admin_openwrt').length){



		
		// Stations table -------------------
		var formatterBytes = function(cell, params, onRendered){
			return (cell.getValue()/1000).toFixed(1);
		}
		var formatterDuration = function(cell, params, onRendered){
			var row=cell.getRow();
	        var data = row.getData();
			var rout =data.f_rout_id;
			onRendered(function(){	
				if(global_ow_durations_times[rout] > ow_ajax_timeout){
					DisplayOwDurations(rout,false);	
				}
				else{
					DisplayOwDurations(rout,true);	
				}
			});
			return "<span class='ow_router_refresh2'><span class='jsOwDuration ow_duration'>"+global_ow_durations_times[rout]+"</span> <span class='jsOwState ow_state'></span></span>";
		}
		var formatterDisconnect = function(cell, params, onRendered){
			return '<a href="#" class="ow_stat_disconnect jsOwDisconnect2" title="Disconnect: '+cell.getValue() +'"><i class="fa fa-sign-out"></i></a>';
		}
		var formatterSignal = function(cell, params, onRendered){
			var row=cell.getRow();
	        var data = row.getData();
			return '<span class="'+data.f_level+'"><span class="ow_stat_signal"><i class="fa fa-signal"></i> '+cell.getValue() +'</span></span>';
		}
		var rowFormatter = function(row){
	        var data = row.getData();
			var el=row.getElement();

			el.classList.add('jsOwRouter','jsOwRouter2');
			el.setAttribute('data-host',data.f_rout_id);
			el.setAttribute('data-mac',data.mac);
			//console.log(data.f_level);

			var css_new		='ow_stat_new';
			var css_blank	='ow_stat_blank';

			el.classList.remove(css_new);
			el.classList.remove(css_blank);
			
			if(data.f_class == css_new){
				el.classList.add(css_new);
			}
			else if(data.info.ip == ''){
				el.classList.add(css_blank);
			}


			//row.reformat();
		}

		ow_tabulator = new Tabulator("#ow_table", {
			index:'mac',
			height:900, 						// set height of table (in CSS or here), this enables the Virtual DOM and improves render speed dramatically (can be any valid css height value)
			data: [], 							//assign data to table
			//reactiveData:true, 					//enable reactive data
			headerFilterLiveFilterDelay:500,	//wait from last keystroke before triggering filter
			movableColumns: true,
			headerSortElement: "<i class='fa fa-caret-up'></i>",
			headerHozAlign:'center',
			rowFormatter: rowFormatter,
			rowFormatterHtmlOutput: rowFormatter,
			layout:"fitColumns", 
			columns:[ //Define Table Columns
				{title:"",		field:"f_name",			hozAlign:"center",	width:10,	headerSort:false, 		formatter:formatterDisconnect},
				{title:"Name",	field:"f_name", 		hozAlign:"left", 			headerFilter:"input"},
				{title:"Host",	field:"info.host",		hozAlign:"right",	width:180,	headerFilter:"input",	formatter:'link',	formatterParams:{urlPrefix:"http://",target:"_blank"}},
				{title:"IP",	field:"info.ip",		hozAlign:"left",	width:100,	headerFilter:"input",	formatter:'link',	formatterParams:{urlPrefix:"http://",target:"_blank"}},
				{title:"MAC",	field:"mac", 			hozAlign:"right",	width:140,	headerFilter:"input"},
				{title:"Brand",	field:"info.vendor",	hozAlign:"left",	width:110,	headerFilter:"autocomplete", headerFilterParams:{values:true,sortValuesList:"asc",allowEmpty:true,freetext:true,showListOnEmpty:true} },
				{title:"Router",field:"f_rout_id", 		hozAlign:"left",	width:120,	headerFilter:"autocomplete", headerFilterParams:{values:true,sortValuesList:"asc",allowEmpty:true,freetext:true,showListOnEmpty:true} },
				{title:"Desc",	field:"f_rout_desc", 	hozAlign:"left",	width:100,	headerFilter:"autocomplete", headerFilterParams:{values:true,sortValuesList:"asc",allowEmpty:true,freetext:true,showListOnEmpty:true} },
				{title:"SSID",	field:"f_ssid", 		hozAlign:"left",	width:100,	headerFilter:"autocomplete", headerFilterParams:{values:true,sortValuesList:"asc",allowEmpty:true,freetext:true,showListOnEmpty:true} },
				{title:"If",	field:"f_if_id", 		hozAlign:"left",	width:80,	headerFilter:"autocomplete", headerFilterParams:{values:true,sortValuesList:"asc",allowEmpty:true,freetext:true,showListOnEmpty:true} },
				{title:"Sig",	field:"signal",			hozAlign:"right",	width:55,	headerFilter:"input", headerFilterFunc:"<=", headerFilterPlaceholder:'max', formatter:formatterSignal},
				{title:"Down",	field:"rx.rate",		hozAlign:"right",	width:85,	formatter:formatterBytes},	//, mutatorParams:{fa:'down'}
				{title:"Up",	field:"tx.rate",		hozAlign:"right",	width:50,	formatter:formatterBytes},
				{title:"Dur",	field:"f_rout_id",		hozAlign:"right",	width:50,	headerSort:false,	formatter:formatterDuration},
				{title:"",		field:"",				hozAlign:"right",	width:1,	headerSort:false},
			],
		});

		ow_tabulator.on("dataFiltered", function(filters, rows){
			$(".jsOwCountSearch").html(rows.length);
		});
		ow_tabulator.on("dataLoaded", function(data){
			$(".jsOwCountTotal").html(data.length);
		});


		//ow_tabulator.setData(ow_table);


		// handle stations ---------------------------------

		var i=1;
		var host='';
		$('.jsOwRouter1').each(function(index){
			host=$(this).attr('data-host');
			SetIntervalOwDurations(host);
			SetTimerOwStations(host,$(this),i);
			//console.log('Set timer '+i+' on : '+host)
			i++;

		});

		$('.jsOwRadio').each(function(index){
			var bssid=$(this).find('.jsOwRadioBssid');
			bssid.html(formatMacAddress(bssid.html()));
			//console.log(bssid);
		});



		$('.jsOwButDetails').click(function(e){
			e.preventDefault();
			var obj=$(this).closest('.jsOwRouter1').find('.jsOwDetails');
			obj.toggle();
			//console.log("click");
		});

		$('.jsOwButReboot').click(function(e){
			e.preventDefault();
			var obj=$(this).closest('.jsOwRouter1');
			var host=obj.attr('data-host');
			obj.find('.jsOwLoading').addClass('loading');
			obj.find('.jsOwStations').html('');
			SetTimerOwStations(host,obj,60);
			var query=JSON.stringify( {act:'reboot',host:host} ) ;
			$.getJSON( '?', { ajax: query } );
		});

		$('BODY').on('click','.jsOwDisconnect1',function(e){
			e.preventDefault();
			var router=$(this).closest('.jsOwRouter1');
			var host=router.attr('data-host');

			var interface=$(this).closest('.jsOwInterface');
			var ifname=interface.attr('data-ifname');
			
			var station=$(this).closest('.jsOwStation');
			var mac=station.attr('data-mac');
			station.remove();
			//console.log('disconnect1: '+ mac);

			//faster refresh
			SetTimerOwStations(host,router,6);

			//remove from list stations table
			delete ow_connected[host][mac];
			ow_tabulator.deleteRow(mac);
			ow_tabulator.redraw();

			  //send command
			var query=JSON.stringify( {act:'disconnect',host:host, mac:mac, ifname:ifname} ) ;
			$.getJSON( '?', { ajax: query } );


		});

		$('BODY').on('click','.jsOwDisconnect2',function(e){
			e.preventDefault();
			var row=$(this).closest('.jsOwRouter2');
			var mac=row.attr('data-mac');
			var target=$('.jsOwStation[data-mac="'+mac+'"] .jsOwDisconnect1')
			row.remove();
			target.trigger('click');
		});

		$('.jsOwRadioInfoBut').click(function(){
			var caret=$(this).find('.jsOwRadioCaret');
			var info=$(this).closest('.jsOwRadio').find('.jsOwRadioInfo');
			if(caret.hasClass('fa-caret-right')){
				caret.removeClass('fa-caret-right').addClass('fa-caret-down');
				info.show();
			}
			else{
				caret.removeClass('fa-caret-down').addClass('fa-caret-right');
				info.hide();
			}
		});


		$('#jsOwBut_routers_show').on('change',function(){
			if($(this).is(':checked')){
				$('.jsOwDivRouters').show();
			}
			else{
				$('.jsOwDivRouters').hide();
			}
		});
		$('#jsOwBut_list_show').on('change',function(){
			if($(this).is(':checked')){
				$('.jsOwDivList').show();
				ow_tabulator.redraw();
			}
			else{
				$('.jsOwDivList').hide();
			}
		});
		$('#jsOwBut_routers_stat2').on('change',function(){
			if($(this).is(':checked')){
				$('.jsOwRouter1').removeClass('hide_stat2');
			}
			else{
				$('.jsOwRouter1').addClass('hide_stat2');				
			}
		});
		$('#jsOwBut_routers_stat3').on('change',function(){
			if($(this).is(':checked')){
				$('.jsOwRouter1').removeClass('hide_stat3');
			}
			else{
				$('.jsOwRouter1').addClass('hide_stat3');				
			}
		});

		$('#jsOwBut_routers_show').trigger('change');
		$('#jsOwBut_list_show').trigger('change');
		$('#jsOwBut_routers_stat2').trigger('change');
		$('#jsOwBut_routers_stat3').trigger('change');

		// $('[data-toggle="popover"]').popover();
	   
		$('#jsOwButClearFilters').on('click',function(e){
			e.preventDefault();
			console.log('clear fil');
			ow_tabulator.clearHeaderFilter();
		});
		$('#jsOwButClearSort').on('click',function(e){
			e.preventDefault();
			console.log('clear sort');
			ow_tabulator.clearSort();
		});

		$('BODY').on('click','.jsCopyBut',function(e){
			e.preventDefault();
			var o_txt=$(this).closest('SPAN').find('.jsCopyText');
			var txt=o_txt.text();
			CopyToClipAndAnimate(txt,o_txt,'Text',1);
		});


	}
	

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
		else if(row.type=='therm'){
			SetStateCommandValue(row);
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
			var highlight_time=10;
			sens.removeClass('changed').addClass('changed');
			// sens.removeClass('changed').addClass('changed').delay(highlight_time * 1000).removeClass('changed');
			setTimeout(function() {sens.removeClass('changed'); }, highlight_time * 1000);
		}
		else{
			//sens.removeClass('changed');
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
	var buttons=bgroup.find(".jsButSelector");
	if(buttons.length>0){
		var sel_but=buttons.filter("[data-value="+row.value+"]");	
		buttons.removeClass(global_class_on);
		sel_but.addClass(global_class_on);
		bgroup.attr('data-state',row.state);
		bgroup.attr('data-value',row.value);
		console.log('is Sel');
	}
	else{
		var popup	=bgroup.find(".jsButPopup");
		popup.find('option[value="'+row.value+'"]').prop('selected',true);	
		bgroup.attr('data-state',row.state);
		bgroup.attr('data-value',row.value);
		console.log(popup);
		console.log('is Pop ' + row.value);
	}
}

/* ---------------------------------- */
function SetStateCommandValue(row){
	var bgroup=$("[data-uid='"+row.uid+"']");
	if (bgroup.length){
		var button=bgroup.find(".jsButValue");
		button.html(row.value);
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

var global_ow_durations_times={};
var global_ow_durations_intervals={};
/* DisplayOwDurations -------------------------------------- */
function DisplayOwDurations(rout_id,state){
	//console.log(rout_id+' - '+state);
	var o_router=$(".jsOwRouter[data-host='"+rout_id+"']" );
	var o_dur	=o_router.find('.jsOwDuration');
	var o_state	=o_router.find('.jsOwState');
	if(state==false){
		o_state.html('<i class="fa fa-warning"></i>');
	}
	else if(state==true){
		//global_ow_durations_times[rout_id]=0;
		o_state.html('<i class="fa fa-check"></i>');
	}
}

/* SetIntervalOwDurations -------------------------------------- */
function SetIntervalOwDurations(rout_id){
	global_ow_durations_times[rout_id]=0;
	clearInterval(global_ow_durations_intervals[rout_id]);
	global_ow_durations_intervals[rout_id]=setInterval( function(){
		//o_dur.html(global_ow_durations_times[rout_id]);
		$(".jsOwRouter[data-host='"+rout_id+"']" ).find('.jsOwDuration').html(global_ow_durations_times[rout_id]); 
		global_ow_durations_times[rout_id] +=1;
		//	console.log("Index="+rout_id+" / "+global_ow_durations[rout_id] );
	}, 1000);	
}


/* Format MAC Address -------------------------------------- */
function formatMacAddress(mac){
	mac=mac.toUpperCase();
	if(demo_mode){
		mac=mac.replace(/:\w+:\w+$/gi,':xx:xx');
	}
	return mac;
}

var global_timer_ow_stations={};
/* SetTimerOwStations -------------------------------------- */
function SetTimerOwStations(rout_id,obj,time){
	clearTimeout(global_timer_ow_stations[rout_id]);
	if(time > 0){
		global_timer_ow_stations[rout_id]=setTimeout(function(){
			RefreshOwStations(rout_id,obj);
		}, time * 1000);
	}
}

/* Copy to Clipboard ----------------------------------- */
function CopyToClipAndAnimate(txt,obj,err_txt,with_class){
	var log='';
	if(err_txt !=''){
		log='### '+err_txt+' ';
		log=log.padEnd(76,'#');
	}
	console.log(log+'\n'+txt+'\n');

	if (navigator.clipboard && window.isSecureContext) {
		navigator.clipboard.writeText(txt).then(
			function() {
				/* clipboard successfully set */
				if(with_class){
				  obj.toggleClass('copied');
				}
				obj.fadeOut(200).fadeIn(100);
			  }, 
			  function() {
				/* clipboard write failed */
				console.log('Opps! Your browser does not support the Clipboard API. '+err_txt+' was : '+txt);
			  }	  
		);
	  } 
	  else {
		const textarea = document.createElement('textarea');
		textarea.value = txt;
		textarea.style.position = 'absolute';
		textarea.style.left = '-99999999px';
		document.body.prepend(textarea);	
		textarea.select();
	
		try {
			document.execCommand('copy');
			/* clipboard successfully set */
			if(with_class){
				obj.toggleClass('copied');
			}
			obj.fadeOut(200).fadeIn(100);
		} 
		catch (err) {
			console.log(err);
		}
		finally {
			textarea.remove();
		}
	  }

}

var ow_connected={};
var ow_connected_last={};
var ow_table=[{mac:'loading',info:{}}];

function RefreshOwStations(rout_id,obj){
	var query=obj.attr('data-query');
	var host=obj.attr('data-host');
	var obj_loading=obj.find('.jsOwLoading');
	obj_loading.addClass('loading');
	$.ajax({ 
			dataType: "json",
			url:'?ajax='+query,
			//data: {ajax: query},
			//processData: false,
			timeout: ow_ajax_timeout * 1000,
			complete: function(jqxhr, textStatus){
				SetTimerOwStations(rout_id, obj, ow_ajax_refresh);
				//console.log("Ow Station "+rout_id+" Completed: "+textStatus);	
			}
		} )
		.done(function( json ) {	
			if(json !=null && json.error==0){
				/* stations ------- */
				if(ow_connected[host] !==undefined){
					ow_connected_last[host]=ow_connected[host];
				}
				ow_connected[host]={};
				var html="";
				var target;
				var blank_class="";
				var vendor_span="";
				var new_class ="";
				var level ="";
				var link ="";
				var name ="";
				$.each(json.data.stations, function(ifname,stations){
					html='';
					//ow_connected[host][ifname]={};
					$.each(stations, function(mac,station){
						/* check if new station */
						if( ow_connected_last[host] !== undefined  && ow_connected_last[host][mac] !== undefined ){
							new_class="";
						}
						else{
							new_class=" ow_stat_new";
						}

						blank_class='';
						vendor_span="";
						if(station.info.ip==''){
							blank_class=' ow_stat_blank';
							vendor_span='<span class="ow_stat_vendor">'+station.info.vendor+'</span>';
						}
						//make level ----
						level='';
						$.each(ow_levels, function(l_name,l_val){
							if( - station.signal >=  - l_val){
								level=' '+l_name;
							}
						});
						// make link
						if(station.info.host != undefined || station.info.host != null || station.info.host != '' ){
							link=station.info.host
						}
						else if(station.info.ip != undefined || station.info.ip != null || station.info.ip != '' ){
							link=station.info.ip
						}
						//make name
						name=station.info.name;
						if(name ==''){
							name="???";
						}
						html = html + '<li class="ow_stat jsOwStation'+new_class+blank_class+level+'" data-mac="'+station.mac+'">'
									+'<div class="ow_stat_1">'
										+'<span class="ow_stat_rates">'
											+'<span class="ow_stat_rx"><i class="fa fa-arrow-down"></i>' + (station.rx.rate /1000).toFixed(1) + '</span> '
											+'<span class="ow_stat_tx"><i class="fa fa-arrow-up"></i>' + (station.tx.rate /1000).toFixed(1)+ '</span> '
										+'</span>'
										+'<span class="ow_stat_name"><a href="http://'+link+'" target="_blank">'+ name +'</a></span>'
									+'</div>'
									+'<div class="ow_stat_2 jsOwStat2">'
										+'<span class="ow_stat_ip"><a href="http://'+station.info.ip+'" target="_blank">' + vendor_span + station.info.ip + '</a></span> '
										+'<span class="ow_stat_host"><a href="http://'+station.info.host+'" target="_blank">' + station.info.host +'</a></span>'
									+'</div>'
									+'<div class="ow_stat_3 jsOwStat3">'
									+'<span class="ow_stat_mac"><i class="fa fa-copy jsCopyBut ow_stat_copy_mac"></i> <a href="#" title="'+station.info.vendor+'" class="jsCopyText">'+ formatMacAddress(station.mac) + '</a></span> '
									+'<span class="ow_stat_signal"><i class="fa fa-signal"></i>' + station.signal +' <a href="#" class="ow_stat_disconnect jsOwDisconnect1" title="Disconnect: '+ station. info.name+'"><i class="fa fa-sign-out"></i></a></span>'
									+'</div>'
									+"</li>\n";

						ow_connected[host][mac]=station;
						ow_connected[host][mac].f_name		= name;
						ow_connected[host][mac].f_class		=new_class.trim();
						ow_connected[host][mac].f_level		=level.trim();
						ow_connected[host][mac].f_link		=link;
						ow_connected[host][mac].f_rout_id	=host;
						ow_connected[host][mac].f_if_id		=ifname;
						ow_connected[host][mac].f_ssid		=ow_routers[host].interfaces[ifname].config.ssid;
						ow_connected[host][mac].f_rout_desc	=ow_routers[host].desc;


					});
					target = obj.find('.jsOwIf_'+ifname+' .jsOwStations');
					target.html(html);
				});

				//refresh table
				RefreshOwStationsTableSorted(host);
				DisplayOwDurations(rout_id,true);
				SetIntervalOwDurations(rout_id);


				if(json.data.sys_info != null){
					/* sys info Load ------- */
					html= (json.data.sys_info.load[0] /65535.0).toFixed(2)
								+ ', '+(json.data.sys_info.load[1] /65535.0).toFixed(2)
								+ ', '+(json.data.sys_info.load[2] /65535.0).toFixed(2);
					target = obj.find('.jsOwLoad');
					target.html(html);

					/* sys info memory ------- */
					var mem_free=(json.data.sys_info.memory.free /1048576.0).toFixed(1);
					var mem_total=(json.data.sys_info.memory.total /1048576.0).toFixed(1);
					var mem_perc=mem_free/mem_total * 100.0;
					var mem_level=Math.round(4 - 4 * mem_perc/100);
					obj.find('.jsOwMemoryIcon I').attr('class','fa fa-battery-'+mem_level);
					obj.find('.jsOwMemoryTitle').attr('title','Memory: '+mem_free+"Mo Free / "+mem_total+"Mo Total");
					obj.find('.jsOwMemory').html(mem_perc.toFixed(1)+'%');

				}
			}
			else{
				DisplayOwDurations(rout_id,false);
				console.log('ERROR');
			}
			obj_loading.removeClass('loading');
		})
		.error(function( jqxhr, textStatus, error ) {
			DisplayOwDurations(rout_id,false);
			var err = textStatus + ", " + error;
			//console.log( "Ow Router "+rout_id+" Failed: " + err );
			obj_loading.removeClass('loading');
		})
}


/* ---------------------------------- */
function RefreshOwStationsTableSorted(host){
	var tmp={};
	$.each(ow_connected, function (h, stations) { 
		$.extend(tmp,stations);		 
	});

	// remove keys 	
	var data=Object.keys(tmp).map(key => tmp[key]);
	
	//sort
	data.sort((a, b) => a.sort_key - b.sort_key);

	//ow_table=data;
	//ow_tabulator.setData(ow_table);
	//console.log(data);
	//return true;


	if($('#jsOwBut_list_live').is(':checked')){
		//ow_tabulator.updateOrAddData(data)
		//ow_tabulator.replaceData(data);
		ow_tabulator.replaceData(data)
		.then((row) => {
			// ow_tabulator.setSort(ow_tabulator.getSorters());
			//SetIntervalOwDurations(host); //we need to reset interval, because Table > .jsOwDuration is not ready on init 
			//DisplayOwDurations(host,true);
			//row.scollTo();
		});	
	}

}

