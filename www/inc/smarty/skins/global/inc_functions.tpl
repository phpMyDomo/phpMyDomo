{* ##################################################################################### *}

{function name=makeButton row='' style='primary'}
	{$c=''}
	{if $row.value && $row.value != 'off'}{$c=' btn-success'}{/if}
	{$command='device'}
	{if $row.type=='group' || $row.type=='scene'}{$command='scene'}{/if}
	<a href='#' name='but_{$row.uid}' data-id='{$row.id}' data-type='{$command}' data-state='{$row.value}' data-onclass='btn-success' class='btn btn-{$style} btn-lg jsButSwitch button_big{$c}'><span class='but_img'><img src='{$p.dirs.static}/global/img/icon48_type_{$row.img_type}.png' data-on="{$p.dirs.static}/global/img/icon48_type_{$row.type}_on.png" data-off="{$p.dirs.static}/global/img/icon48_type_{$row.type}_off.png"></span>{$row.name}</a>
{/function}


{* ##################################################################################### *}

{function name=makeSensorHome row=''}
	<span class='sensor'><img src='{$p.dirs.static}/global/img/icon48_type_{$row.img_type}.png'> <b>{$row.value}</b>{$p.units.{$row.type}}</span>
{/function}
