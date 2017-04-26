<?
require(dirname(__FILE__).'/src/mysensors.class.php');

// define your default here ------------------------------------------------
$defaults['node']		="0";
$defaults['child']		="0";
$defaults['type']		="0";
$defaults['ack']		="0";
$defaults['sub']		="0";
$defaults['payload']	="";
$defaults['message']	="";
$defaults['wait']		="";

// set the gateway type "eth" or "serial" -------------------------------------
$gateway 			= "eth";		// gateway type to use
$defaults['ip']		="10.1.7.40";	// default IP address
$defaults['port']	="5003";		// default TCP Port

//or 
//$gateway 			= "serial"; 	// gateway type to use
//$defaults['s_port']	="COM1";	// default Serial Port



// ############################################################################################

// process form inputs --------------------------------------------
foreach($defaults as $d => $v){
	$form[$d] = isset($_REQUEST[$d]) ? $_REQUEST[$d] : $v;
}


// make javascript types objects -----------------------------------
$mys= new MySensors();
$mess_types=$mys->getMessageTypes();
$sub_types=$mys->getSubTypes();

$js_types .="var types	={};\n";
$js_types .="var mess_types=[];\n";
foreach($mess_types as $t_name => $t){
	$js_types .="mess_types[$t]= '$t_name';\n";
	$js_types .="types.$t_name= [];\n";
	$ts='';
	if($t==$form['type']){$ts=" selected='selected'";}
	$html_options_mess .="	<option value='$t'$ts>$t_name</option>\n";
	foreach($sub_types[$t] as $code => $n){
		$js_types.="types.{$t_name}[{$n}] = '$code';\n";
	}
}


// process the  form -------------------------------------------------
if($_REQUEST['do']){
	if($gateway=='serial'){
		$mys=new MySensorSendSerial($form['s_port']);
	}
	else{
		$mys=new MySensorSendEthernet($form['ip'],$form['port']);
	}

	$result=$mys->sendMessage($form['node'],$form['child'],$form['type'],$form['ack'],$form['sub'],$form['payload'],$form['wait']);
	if($result){
		$class='success';
		if($form['wait']){
			$err_mess="Received : <b style='color:black'>$result</b>";
		}
		else{
			$err_mess="Successfull!";
		}
	}
	else{
		$class='danger';
		if($form['wait']){
			$err_mess="Did not receive an answer!";
		}
		else{
			$err_mess="Something went wrong. Check GW is receiving on {$form['ip']}:{$form['port']} ! ";			
		}
	}

	$last_mess	 	  = "<li> Query&nbsp;&nbsp; : <b>".$mys->GetRawMessage()."</b></li>\n" ;
	if($form['wait']){
		$last_mess	 .= "<li> Answer : <b>".$mys->GetRawAnswer()."</b></li>\n" ;
	}

	$date=date('l, H\h i\m s\s');
	$html_result=<<<EOF
<div id='results' class='alert alert-$class'>
	<H4>$err_mess</H4>
	<hr>
	<ul>
	$last_mess
	</ul>
	<hr>
	<i>Message sent to gateway {$form['ip']}:{$form['port']} on $date</i>
</div>
EOF;
}


// build ack menu --------------------------------------------------------------
for($i=0;$i<=1;$i++){
	$html_options_ack .="<option value='$i'";
	$html_options_ack .= $form['ack']==$i ? ' selected="selected"':'';
	$html_options_ack .=">$i</option>\n";
}
$html_checked_wait = $form['wait'] ? ' checked="checked"':'';


// show form input depending on the gateway type --------------------------------
if($gateway=='serial'){
	$html_gateway=<<<EOF
					Gateway SerialPort : 
					<div class="form-group">
						<input type="text" class="form-control" name="s_port" value="{$form['s_port']}" size=25 placeholder="Serial Port">
					</div>

EOF;
}
else{
	$html_gateway=<<<EOF
					Gateway IP : 
					<div class="form-group">
						<input type="text" class="form-control" name="ip" value="{$form['ip']}" size=12 placeholder="IP address">
					</div>
					<div class="form-group">
						&nbsp; Port :
						<input type="text" class="form-control" name="port" value="{$form['port']}" size=4  placeholder="Port">
						&nbsp;
					</div>

EOF;
	
}

// Display HTML -----------------------------------------------------------------------
echo <<<EOF
<!DOCTYPE html>
<html lang='en'>
	<head>
		<title>MySensors Sender &amp; Converter</title>
    	<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta charset="UTF-8">
		<link rel="icon" href="http://www.mysensors.org/favicon.ico">
		<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script> 
		<script language="javascript">
$js_types
function form2message(){
	var mess ='';
	mess +=$('#td_node INPUT').val();
	mess +=';';
	mess +=$('#td_child INPUT').val();
	mess +=';';
	mess +=$('#td_type SELECT').val();
	mess +=';';
	mess +=$('#td_ack SELECT').val();
	mess +=';';
	mess +=$('#td_sub SELECT').val();
	mess +=';';
	mess +=$('#td_payload INPUT').val();
	$('#td_message INPUT').val(mess);
}

function message2form(){
	var txt=$('#td_message INPUT').val();
	var f = txt.split(";");
	$('#td_node INPUT').val(f[0]);
	$('#td_child INPUT').val(f[1]);
	$('#td_type SELECT').val(f[2]);
	$('#td_ack SELECT').val(f[3]);
	$('#td_sub SELECT').val(f[4]);
	$('#td_payload INPUT').val(f[5]);
	formTypeChange(false);
}

function formTypeChange(with_update){
	var mess_type	=$('#td_type SELECT').val();
	var sub_list	=[];
	var select_sub 	= $('#td_sub SELECT');
	var current_val=select_sub.val();
	select_sub.empty();

	//console.log('type='+mess_type);
	
	sub_list=types[mess_types[mess_type]];

	for (var j = 0; j < sub_list.length; j++){
		select_sub.append("<option value='"+j+"'>" +sub_list[j]+ "     </option>");
 	}

	if(current_val == null || current_val == undefined){
		current_val=0;
	}
	select_sub.val(current_val);

	//$('#td_sub SELECT').trigger('change');
	if(with_update){
		form2message();
	}
}

function UpdateWait(){
	var t_val=$('#td_type SELECT').val();
	var s_val=$('#td_sub SELECT').val();
	var w_span=$('#span_wait');
	var w_input=$('#span_wait INPUT');
	
	if(t_val==0 || t_val==1){
		w_input.prop( "checked", false );
		w_span.hide();
	}
	else if (t_val==2){
		w_input.prop( "checked", true );
		w_span.show();
	}
	else if (t_val==3 || t_val==4){
		w_span.show();
		if(t_val==3 && (s_val <3)){
			w_input.prop( "checked", true );
		}
	}
}
$( document ).ready(function() {
	formTypeChange(true);
	$('#td_type SELECT').on('keyup change',function(e){
		formTypeChange(true);
	});
	
	$('#td_node INPUT, #td_child INPUT, #td_type SELECT, #td_ack SELECT, #td_sub SELECT, #td_payload INPUT').on('keyup change',function(e){
		form2message();
		UpdateWait();
	});
	$('#td_message INPUT').on('keyup',function(e){
		message2form();
	});

	$('#but_submit').click(function(e){
		$('#but_submit').prop('disabled',true);
		//$('#form INPUT,#form SELECT').prop('disabled',true);
		$('#results').fadeOut();
	});

});
		</script>
	</head>

	<body>
		<div class="bg-info">
			<div class="container">
				<h1><img src="https://www.mysensors.org/images/MySensorsLogo.png" height=50>&nbsp; Message Sender / Converter</h1>
			</div>
		</div>
		
		<div class="container">
			<p>
			<form id='form' action="?">
				<input type='hidden' name='do' value=1>
				<table class='table table-bordered .table-condensed'>
					<thead>
						<th id='th_node'>Node</th>
						<th id='th_child'>Child</th>
						<th id='th_type'>Mess&nbsp;Type</th>
						<th id='th_ack'>ACK</th>
						<th id='th_sub'>Sub&nbsp;Type</th>
						<th id='th_payload'>Payload</th>
						<th id='th_space'></th>
						<th id='th_message'>Message</th>
					</thead>
					<tbody>
						<td id='td_node'><input type="text" class="form-control" name='node' value="{$form['node']}" size=1></td>
						<td id='td_child'><input type="text" class="form-control" name='child' value="{$form['child']}" size=1></td>
						<td id='td_type'><select class="form-control" name='type'>\n{$html_options_mess}</select></td>
						<td id='td_ack'><select class="form-control" name='ack'>\n{$html_options_ack}</select></td>
						<td id='td_sub'><select class="form-control" name='sub'><option value='{$form['sub']}'>-- Select a type first --</option></select></td>
						<td id='td_payload'><input type="text" class="form-control" name='payload' value="{$form['payload']}"  size=10></td>
						<td id='td_space'>&lt;====&gt;</td>
						<td id='td_message'><input type="text" class="form-control input-lg" name='message' value="{$form['message']}" size=20></td>
					</tbody>
				</table>
				<div class="form-inline text-right">
$html_gateway
					<div class="form-group">
						<span id="span_wait"><input type="checkbox" class="form-control" name='wait' id="wait" value="1" $html_checked_wait> Wait for Answer?</span>
					</div>
					<button type="submit" class="btn btn-primary btn-lg" id='but_submit'>Send Message</button>
				</div>
			</form>
			</p>
$html_result
		</div>

	</body>
</html>
EOF;

?>