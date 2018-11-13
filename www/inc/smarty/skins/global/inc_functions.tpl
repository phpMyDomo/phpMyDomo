{* ##################################################################################### *}
{$my_popover_created=0}

{function name=makeButton row='' style='default'}
	{$c=''}
	{if $row.state == 'on'}{$c=' btn-success'}{/if}
	{$command=$row.type}
	{if $row.type=='group' || $row.type=='scene'}{$command='scene'}{/if}
	{if $row.type=='blinds' or $row.type=='shutter'}{$command='blinds'}{/if}

<div class="btn-group jsButGroup button_group">
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='{$command}' data-state='{$row.state}' data-invert='{$row.invert_set}' data-onclass='btn-success' class='btn btn-{$style} btn-lg jsButSwitch button_big{$c}'><span class='but_img'><img src='{$p.urls.static}{$row.img_url}' data-on="{$p.urls.static}{$row.img_on_url}" data-off="{$p.urls.static}{$row.img_off_url}"></span>{$row.name}</a>

{if $row.type=='dimmer'}
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-js_address='{$row.js_address}' data-type='dimmer' data-value='{$row.value}'  title="{$row.name}" class='btn btn-lg btn-default jsButDimmer jsPopover button_dim'>{$row.value}</a>
{/if}

{if $command=='blinds'}
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='blinds' data-target='off' data-invert='{$row.invert_set}' title="" class='btn btn-lg btn-default jsButBlinds button_blinds'><i class="fa fa-chevron-up"></i><u>.</u></a>
	<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='blinds' data-target='on' data-invert='{$row.invert_set}' title="" class='btn btn-lg btn-default jsButBlinds button_blinds'><i class="fa fa-chevron-down"></i><u>.</u></a>
{/if}

{if $command=='selector'}
	{foreach from=$row.choices key=cv item=cn}
		{$my_class=''}
		{if $row.value==$cv}{$my_class='btn-success'}{/if}
		<a href='#' name='but_{$row.uid}' data-address='{$row.address}' data-type='selector' data-value='{$cv}' data-onclass='btn-success' title="" class='btn btn-lg btn-default jsButSelector button_selector {$my_class}'>{$cn}</a>
	{/foreach}
{/if}

</div>

{if $row.type=='dimmer' && !$my_popover_created}
<div id="jsPopover_{$row.js_address}" class="hidden">
        <span class="legend">0</span><input type="text" id="jsSlider_{$row.js_address}" class="jsSlider" value="{$row.value}" data-slider-min="0" data-slider-max="100" data-slider-step="{100/{$row.dim_steps}}" data-slider-value="{$row.value}"><span class="legend">100</span>
</div>
{$my_popover_created=1}
{/if}

{/function}


{* ##################################################################################### *}

{function name=makeSensorHome row=''}
	{if $row.type=='text'}
		{$my_value=$row.value}
	{else}
		{$my_value=$row.state|ucwords|default:{call number_format_lang from=$row.value}}
	{/if}
	{$my_name=''}
	{if $c.app.groups_sensors_names==1}
		{$my_name=$row.name}
	{elseif $c.app.groups_sensors_names}
		{$my_name=$row.name|truncate:{$c.app.groups_sensors_names}}
	{/if}
	{$my_class=''}
	{if $row.warning > 0}
		{$my_class=" sensor_warn sensor_warn_{$row.warning}"}
	{/if}
	<span class='sensor{$my_class}'><img src='{$p.urls.static}{$row.img_url}'> {$my_name} <b>{$my_value}</b>{$row.unit|default:$p.units.{$row.type}}</span>
{/function}

{function name=number_format_lang from='' count=0 thous_sep=',' dec_sep=',' }
	{if $c.conf.lang=='en' || $c.conf.lang=='uk'}
		{$from|number_format:$count:'.':','}
	{else}
		{$from|number_format:$count:'.':' '|replace:' ':'&nbsp;'}
	{/if}
{/function}