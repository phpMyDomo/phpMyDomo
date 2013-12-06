jQuery( document ).ready(function() {
    console.log( "JS Ready!" );
    
    var ajax_url='/ajax';
	var refresh_time=12;

    /* Reload page -------------------------------------- */
	var reload_time=$('#jsReload').attr('data-time');
	SetReload(reload_time);
    
    /* Button Switch -------------------------------------- */
    $('.jsButSwitch').click(function(e){
    	e.preventDefault();
    	var but		=$(this);
    	var img		=but.find('IMG');
    	var images={
    		'on': img.attr('data-on'),
    		'off': img.attr('data-off'),
    	};
    	var type	=but.attr('data-type');
    	var address	=but.attr('data-address');
    	var state	=but.attr('data-state');
    	var onclass	=but.attr('data-onclass');
    	but.removeClass('active').addClass('active');
    	
    	var target	='on';
    	if(state=='on'){
    		target='off'
    	}
    	
    	$.getJSON( ajax_url, { mode: "set", a: address, v: target, t: type } )
  			.done(function( json ) {
			    but.removeClass('active');
  				if(json.status=='ok'){
					SetReload(refresh_time);
  					console.log('OK');
					but.removeClass(onclass);
					but.attr('data-state',target);
					img.attr('src',images[target]);
					if(target=='on'){
						but.addClass(onclass);
					}
  				}
  				else{
  					console.log('ERROR');
  				}
			})
			.fail(function( jqxhr, textStatus, error ) {
			    but.removeClass('active');
				var err = textStatus + ", " + error;
				console.log( "Request Failed: " + err );
			});    	
    });

    /* Button Level (to do) -------------------------------------- */
   $('.jsButLevel').click(function(e){
    	e.preventDefault();
    	var but		=$(this);
		
    });

 	
 	var sliderVal;


    $("#body_home .jsPopover").popover({
        html: true,
        placement: 'auto bottom',
        content: function () {
            return $("#dim_popover").html();
        }
    }).click(function () {

        $('#rangeSlider').slider().on('slide', function (ev) {
            sliderVal = ev.value;
        });
        if (sliderVal) {
            $('#rangeSlider').slider('setValue', sliderVal)
        }
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
		setTimeout("ReloadPage()", time * 1000);
	}
}
/* ReloadPage -------------------------------------- */
function ReloadPage() {
   location.reload();
};
