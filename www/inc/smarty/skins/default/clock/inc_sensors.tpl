
		<div id="div_clock_sensors">
			<table class="" cellpadding=0 cellspacing=0>


{foreach $data.sensors key=k item=sens}
				<tr>
					<th>{$sens.name}</th>
	{foreach $sens.uid  key=s item=uid }
		{$d=$data.devices.$uid}
					<td><span class='jsAutoSensors' data-uid='{$uid}' data-type='{$d.type}'>{$d.value}</span><span class="unit">{$d.unit}</span></td>
	{/foreach}
				</tr>
{/foreach}

			</table>
		</div>
