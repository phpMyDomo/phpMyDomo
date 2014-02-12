/* var ajax_url='/ajax'; */
var refresh_time		=12;
var refresh_time_blinds	=20;

jQuery( document ).ready(function() {
    console.log( "JS Ready!" );
    
    /* Hide browser address bar */
	/Mobile/.test(navigator.userAgent) && !location.hash && setTimeout(function () {
    	if (!pageYOffset) window.scrollTo(0, 1);
	}, 500);

    /* Reload page -------------------------------------- */
	var reload_time	=$('#jsReload').attr('data-time');
	SetReload(reload_time);
    

    /* Button Blinds (Pause) -------------------------------------- */
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

    /* Button Dimmer (to finish) -------------------------------------- */
   $('.jsButDimmer').each(function(){
    	var but			=$(this);
    	var popover_id	='#jsPopover_'+ but.attr('data-address');
    	var slider_id	='#jsSlider_'+ but.attr('data-address');
    	var address	=but.attr('data-address');
    	
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

    
    /* Debug Devices ------------------------------------------------------------------------ */
    $('#body_devices .jsPopover').popover({
    	trigger: 'hover',
    	html: true
    });
    


});

/* FUNCTIONS ################################################################################### */

/* SetReload -------------------------------------- */
function SetReload(time){
	if(time > 0){
		if(time == refresh_time || time == refresh_time_blinds){
			setTimeout("ReloadPage(0)", time * 1000);		
		}
		else{
			setTimeout("ReloadPage(1)", time * 1000);		
		}
	}
}
/* ReloadPage -------------------------------------- */
function ReloadPage(jump) {
	var reload_album=$('#jsReload').attr('data-pf_album');
	var reload_url	=$('#jsReload').attr('data-pf_url');
	if(jump == 1 && reload_album){
		var url=reload_url+reload_album+'&back=1';
		window.location.href=url;
	}
	else{
		location.reload();
	}
};
