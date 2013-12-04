jQuery( document ).ready(function() {
    console.log( "ready!" );
    
    var ajax_url='/ajax'
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
    	var id		=but.attr('data-id');
    	var state	=but.attr('data-state');
    	var onclass	=but.attr('data-onclass');
    	but.removeClass('active').addClass('active');
    	
    	var target	='on';
    	if(state=='on'){
    		target='off'
    	}
    	
    	$.getJSON( ajax_url, { mode: "set", i: id, v: target, t: type } )
  			.done(function( json ) {
				SetReload(5);
			    but.removeClass('active');
  				if(json.status=='ok'){
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
				SetReload(reload_time);
			    but.removeClass('active');
				var err = textStatus + ", " + error;
				console.log( "Request Failed: " + err );
			});    	
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
