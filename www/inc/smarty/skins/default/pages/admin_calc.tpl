{capture assign=page_content}

<H3>AB400 (Phenix, Elro, etc...)</H3>
<div class='div_calc'>
	<table class="table table_code">
		<tr class='tr_label'>
			<td class='td_name'>Label :</td>
			<td class='td_name2'></td>
			<td class=''>1</td>
			<td class=''>2</td>
			<td class=''>3</td>
			<td class=''>4</td>
			<td class='td_sep'></td>
			<td class=''>5</td>
			<td class=''>A</td>
			<td class=''>B</td>
			<td class=''>C</td>
			<td class=''>D</td>
		</tr>
		<tr class='tr_dip'>
			<td class='td_name'>DIP Values :</td>
			<td class='td_name2'>1<br>0</td>
			<td class=''>
				<input type='radio' name='ab400_h[0]' value='1'><br>
				<input type='radio' name='ab400_h[0]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_h[1]' value='1'><br>
				<input type='radio' name='ab400_h[1]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_h[2]' value='1'><br>
				<input type='radio' name='ab400_h[2]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_h[3]' value='1'><br>
				<input type='radio' name='ab400_h[3]' value='0' checked="checked">
			</td>

			<td class='td_sep'></td>

			<td class=''>
				<input type='radio' name='ab400_u[1]' value='1'><br>
				<input type='radio' name='ab400_u[1]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_u[2]' value='1'><br>
				<input type='radio' name='ab400_u[2]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_u[3]' value='1'><br>
				<input type='radio' name='ab400_u[3]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_u[4]' value='1'><br>
				<input type='radio' name='ab400_u[4]' value='0' checked="checked">
			</td>
			<td class=''>
				<input type='radio' name='ab400_u[0]' value='1'><br>
				<input type='radio' name='ab400_u[0]' value='0' checked="checked">
			</td>
		</tr>
		<tr class='tr_code'>
			<td class='td_name'>Code :</td>
			<td class='td_name2'></td>
			<td class='td_code' colspan=4>House <input type='text' id='ab400_house' value='' size=2></td>
			<td class='td_sep'></td>
			<td class='td_code' colspan=6>Unit <input type='text' id='ab400_unit' value='' size=2></td>
		</tr>
	</table>
	<span id='d'></span>
</div>


<script language=javascript>
/* --------------------------------------------- */
$(document).ready(function() {
	$('#ab400_house').keyup(function(){
		var val = $(this).val();
		val=val.charCodeAt(0) - 65;
		DecToBinForm(val,4,'ab400_h');
	});
	$('#ab400_unit').keyup(function(){
		var val = $(this).val();
		DecToBinForm(val -1 ,5,'ab400_u');
	});
	ab400_BindBinaryForm('ab400_h');
	ab400_BindBinaryForm('ab400_u');
});

/* --------------------------------------------- */
function ab400_BindBinaryForm(id){
	$('input[name^="'+id+'["]').each(function(i) {
		/* $("input[name='"+id+'['+i+"]']").keyup(function(){ */
		$("input[name='"+id+'['+i+"]']").change(function(){
			ab400_ParseBinaryForm(id);
		});
	});	
}

/* --------------------------------------------- */
function ab400_ParseBinaryForm(id){
	var bin='';
	for(i = 0; i < 7; i++){
		/* var bit=$("input[name='"+id+'['+i+"]']"); */
		var bit=$("input[name='"+id+'['+i+"]']:checked");
		if(bit.length){
			bin +=bit.val();
		}
	}

	var dec=parseInt(bin, 2);
	if(id=='ab400_h'){
		dec +=65;
		var val=String.fromCharCode(dec);
		var id_label='ab400_house'
	}
	else if(id=='ab400_u'){
		var val=dec +1;
		var id_label='ab400_unit'
	}
	$("#"+id_label).val(val);
	console.log(id+'='+bin+' --> '+val);
}

/* --------------------------------------------- */
function DecToBinForm(val, len, id){
	if(isNaN(val)){
		val=0
	}
	var bin =PadBinary(Number(val).toString(2),len);
	console.log('val: '+val+', bin: '+bin);
	for(i = 0; i < len; i++){
		/* $("input[name='"+id+'['+i+"]']").val(bin[i]); */
		$("input[name='"+id+'['+i+"]'][value="+bin[i]+"]").attr('checked', 'checked');
	}
}

/* --------------------------------------------- */
function PadBinary(n, p, c) {
    var pad_char = typeof c !== 'undefined' ? c : '0';
    var pad = new Array(1 + p).join(pad_char);
    return (pad + n).slice(-pad.length);
}

</script>

{/capture}


{include file="{$p.template}/layout.tpl"}