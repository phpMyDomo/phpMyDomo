{* ##################################################################################### *}
{$my_popover_created=0}

{function name=makeButton row='' style='default'}
	{$c=''}
	{if $row.state == 'on'}{$c=' btn-success'}{/if}
	{$command='switch'}
	{if $row.type=='group' || $row.type=='scene'}{$command='scene'}{/if}
	{if $row.type=='dimmer' or $row.type=='shutter'}{$command='dimmer'}{/if}

<div class="btn-group jsButGroup button_group">
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='{$command}' data-state='{$row.state}' data-onclass='btn-success' class='btn btn-{$style} btn-lg jsButSwitch button_big{$c}'><span class='but_img'><img src='{$p.urls.static}/global/img/icon48_type_{$row.img_type}.png' data-on="{$p.urls.static}/global/img/icon48_type_{$row.type}_on.png" data-off="{$p.urls.static}/global/img/icon48_type_{$row.type}_off.png"></span>{$row.name}</a>

{if $row.type=='dimmer'}
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='dimmer' data-value='{$row.value}'  title="{$row.name}" class='btn btn-lg btn-default jsButDimmer jsPopover button_dim'>{$row.value}</a>
{/if}

</div>

{if $row.type=='dimmer' && !$my_popover_created}
<div id="jsPopover_{$row.address}" class="hidden">
        <span class="legend">0</span><input type="text" id="jsSlider_{$row.address}" class="jsSlider" value="{$row.value}" data-slider-min="0" data-slider-max="100" data-slider-step="{100/{$row.dim_steps}}" data-slider-value="{$row.value}"><span class="legend">100</span>
</div>
{$my_popover_created=1}
{/if}

{/function}


{* ##################################################################################### *}

{function name=makeSensorHome row=''}
	{if $row.value}{$row.value=$row.value|number_format}{/if}
	<span class='sensor'><img src='{$p.urls.static}/global/img/icon48_type_{$row.img_type}.png'> <b>{$row.value|default:$row.state}</b>{$p.units.{$row.type}}</span>
{/function}
