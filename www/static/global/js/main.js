/* var ajax_url='/ajax'; */
var refresh_time		=12;
var refresh_time_blinds	=25;

jQuery( document ).ready(function() {
    
    /* Hide browser address bar -------------------------------------- */
	/Mobile/.test(navigator.userAgent) && !location.hash && setTimeout(function () {
    	if (!pageYOffset) window.scrollTo(0, 1);
	}, 500);


    /* Reload page -------------------------------------- */
	var reload_time	=$('#jsReload').attr('data-time');
	SetReload(reload_time);
    

    /* Button Blinds & Shutter -------------------------------------- */
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

    /* Button Switch -------------------------------------- */
    $('.jsButSwitch').click(function(e){
    	e.preventDefault();
    	var but		=$(this);
    	var img		=but.find('IMG');
    	var dim		=but.closest('.jsButGroup').find('.jsButDimmer')

    	var images={
    		'on': img.attr('data-on'),
    		'off': img.attr('data-off'),
    	};
    	var type	=but.attr('data-type');
    	var address	=but.attr('data-address');
    	var state	=but.attr('data-state');
    	var invert	=but.attr('data-invert');
    	var onclass	=but.attr('data-onclass');
    	but.removeClass('active').addClass('active');
    	
    	var my_refresh_time = refresh_time;
    	if(type =='blinds'){
    		my_refresh_time = refresh_time_blinds;
    	}
    	
    	var target	='on';
    	if(state=='on'){
    		target='off'
    	}
    	
    	$.getJSON( ajax_url, { mode: "set", a: address, v: target, t: type, i: invert } )
  			.done(function( json ) {
			    but.removeClass('active');
  				if(json.status=='ok'){
					SetReload(my_refresh_time);
  					console.log('OK');
					but.removeClass(onclass);
					but.attr('data-state',target);
					img.attr('src',images[target]);
					if(target=='on'){
						but.addClass(onclass);
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
    	var but		=$(this);
    	var address	=but.attr('data-address');
    	var value	=but.attr('data-value');
    	var onclass	=but.attr('data-onclass');
    	var group_buts=but.closest('.jsButGroup').find('.jsButSelector');

    	but.removeClass('active').addClass('active');
    	
    	var my_refresh_time = refresh_time;
    	    	
    	$.getJSON( ajax_url, { mode: "set", a: address, v: value, t: 'selector' } )
  		.done(function( json ) {
			    but.removeClass('active');
				
  				if(json.status=='ok'){
					group_buts.removeClass(onclass);
  					but.addClass(onclass);
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
				console.log( "Selector Request Failed: " + err );
		});    	
    });



    /* Button Dimmer (to finish) --------------------------------------------------------------- */
	$('.jsButDimmer').each(function(){
		var but			=$(this);
		var address		=but.attr('data-address');
		var popover_id	='#jsPopover_'+ but.attr('data-js_address');
		var slider_id	='#jsSlider_'+ but.attr('data-js_address');
		
		but.popover({
			html: true,
			placement: 'bottom',
			trigger:'manual',
			content: $(popover_id).html()
	   })
	   .click(function(e){
			e.preventDefault();
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
					SetReload(reload_time);
					value = Math.round(ev.value);
            		if(value != last_value){
	            		last_value= value;
						/* slider.slider('setValue', value); */
					
						/* Do Ajax Call, avoiding sending continous values */
						console.log('Sending Dim '+value);		
						$.getJSON( ajax_url, { mode: "set", a: address, v: value, t: 'dim_level' } )
							.done(function( json ) {
								if(json.status=='ok'){
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
	function RvbGetPopoverContent(e){
    	var popover_id	='#jsPopover_'+ $(this).data('js_address');
		$(popover_id+ ' INPUT').attr('style',''); /* remove display:none */
		return $(popover_id).html();
	}

	$('.jsButColor').each(function(){
		var but				=$(this);
		var address			=but.data('address');
		var popover_src		=$('#jsPopover_'+ but.data('js_address')).find('.rgb_popover_content');
		var input_sel		='#jsInput_'+ but.data('js_address');
		var colorpick_sel	='.jsColorPicker_'+ but.data('js_address');
		var last_value		=$(input_sel).val();
		but.css('background','#'+last_value);

    	but.popover({
			//animation: false,
			html: true,
			container: 'body',
			placement: 'bottom',
			trigger:'manual',
//			trigger:'focus',
			content: RvbGetPopoverContent //popover_src.html()
	   })
	   .click(function(e){
			e.preventDefault();
			//but.filter(function(index, element) { return element != e.target; }).popover('hide');
			//but.off('click').on('click', function(e) { e.stopPropagation(); });
			//$('.jsButColor').filter(function(index, element) { return element != e.target; }).popover('hide');
			but.popover('toggle');
			//e.stopPropagation();
		});
		
		but.on('shown.bs.popover', function(){
			var in_value=$(input_sel).attr('value');
			//console.log('in val='+in_value);
			popover_src.html('');
			$(input_sel).wheelColorPicker({ layout: 'block', live: true, format: "hex", preview:true, animDuration: 0 });
			//but.popover('update');

			$(input_sel).on('changed-value', $.throttle( 250, function (ev) {
				var color=$(this).wheelColorPicker('getColor');
				var value=$(this).wheelColorPicker('getValue');
				var level=Math.round(color.v * 100)

				but.html(level);
				but.css('background','#'+value);
			
				/* Do Ajax Call, avoiding sending continous values */
				if(value != last_value){
					last_value= value;
					// console.log('Sending Color: '+value + ' level: ' + level);		
					$.getJSON( ajax_url, { mode: "set", a: address, v: value, t: 'rgb_color' } )
						.done(function( json ) {
							if(json.status=='ok'){
								//console.log('Color set to '+ value);
			            		//but.html(level);
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
			//var last_input_html=$(input_sel)[0].outerHTML;
			var last_input_html=$(input_sel)[0].outerHTML;
			$(input_sel).wheelColorPicker('destroy');
			popover_src.html(last_input_html);
			console.log('hide');
		});
		
		
	});

    /* Clear Popover on click outside  ------------------------------------------------------------------------ */
	$(document).on('click', function (e) {
		$('[data-toggle="popover"],[data-original-title]').each(function () {
	        //the 'is' for buttons that trigger popups
	        //the 'has' for icons within a button that triggers a popup
	        if (!$(this).is(e.target) && $(this).has(e.target).length === 0 && $('.popover').has(e.target).length === 0 ) {                
				//(($(this).popover('hide').data('bs.popover')||{}).inState||{}).click = false  // fix for BS 3.3.6
				
				/* https://stackoverflow.com/questions/13442749/how-to-check-whether-a-twitter-bootstrap-popover-is-visible-or-not/13442857 */
				var isVisible = $(this).data('bs.popover').tip().hasClass('in');
				//var isVisible = $('#element').data('bs.popover')._activeTrigger.click; //bs 4
				
				if(isVisible){
					console.log("visible");
					$(this).popover('toggle');
				}
	        }
	    });
	});



    /* Debug Devices ------------------------------------------------------------------------ */

    $('#body_devices .jsPopoverDebug').popover({
    	trigger: 'click',
    	html: true,
    	template: '<div class="popover popover_debug" role="tooltip"><div class="arrow"></div><div class="popover-title"></div><div class="popover-content"></div></div>'
    });
	$('#body_devices .jsPopoverDebug').on('show.bs.popover',function(){
		$(this).closest('TR').addClass('selected');
	});
	$('#body_devices .jsPopoverDebug').on('hide.bs.popover',function(){
		$(this).closest('TR').removeClass('selected');
	});
	$('#body_devices .jsPopoverDebug').on('shown.bs.popover',function(){
  		var po=$(this).next();
		var new_top =parseInt( po.css('top')) + parseInt(po.height()/ 2) - 16;
		po.css('top',new_top + 'px');
	});

	

});

/* FUNCTIONS ################################################################################### */
var global_timeout;
/* SetReload -------------------------------------- */
function SetReload(time){
	clearTimeout(global_timeout);
	if(time > 0){
		if(time == refresh_time || time == refresh_time_blinds){
			global_timeout=setTimeout("ReloadPage(0)", time * 1000);		
		}
		else{
			global_timeout=setTimeout("ReloadPage(1)", time * 1000);		
		}
	}
}
/* ReloadPage -------------------------------------- */
function ReloadPage(jump) {
	var reload_url=$('#jsReload').attr('data-url');
	if(jump == 1 && reload_url !=''){
		window.location.href=reload_url;
	}
	else{
		location.reload();
	}
};
