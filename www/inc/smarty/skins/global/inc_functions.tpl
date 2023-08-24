{* ##################################################################################### *}
{$my_popover_created=0}
{$my_colorwheel_created=0}
{$my_valuepopup_created=0}

{function name=makeButton row='' style='default'}
	{$class_on=''}
	{if $row.state == 'on' && $row.type !='push'}{$class_on=' btn-success'}{/if}
	{$command=$row.type}
	{if $row.type=='group' || $row.type=='scene'}{$command='scene'}{/if}
	{if $row.type=='blinds' || $row.type=='shutter'}{$command='blinds'}{/if}
	{if $row.type=='therm'}{$command='value'}{/if}
	{if $command=='selector' and $c.app.selector_thres and  $row.choices|@count >= $c.app.selector_thres and ! in_array($row.uid,$c.app.selector_keep)}
		{$my_popup=1}
	{else}
		{$my_popup=0}
	{/if}
	
<div class="btn-group jsButGroup jsButGroup_{$row.type} jsButGroup_{$row.js_address} button_group button_group_{$row.type}" 
	data-uid="{$row.uid}"
	data-type='{$command}'
	data-address='{$row.address}'
	data-state='{$row.state}'
	data-value='{$row.value}'
	data-invert='{$row.invert_set}' 
>
	<a href='#' class='btn btn-{$style} btn-lg jsButSwitch button_big{$class_on}'>
		<span class='but_img'><img src='{$p.urls.static}{$row.img_url}' data-on="{$p.urls.static}{$row.img_on_url}" data-off="{$p.urls.static}{$row.img_off_url}"></span>
		{$row.name}
	</a>

{if $command=='dimmer'}
	<a href='#' data-address='{$row.address}' data-js_address='{$row.js_address}' data-type='dimmer' data-value='{$row.value}'  title="{$row.name}" class='btn btn-lg btn-default jsButDimmer jsPopover button_dim'>{$row.value}</a>
{/if}

{if $command=='value'}
	<a tabindex="0" role="button"  href='#' data-js_address='{$row.js_address}'  title="{$row.name}" class='btn btn-lg btn-default jsButValue '>
	{$row.value}
	</a>	
{/if}

{if $command=='rgb' || $command=='rgbw'}
	<a tabindex="0" role="button"  href='#' data-js_address='{$row.js_address}'  title="{$row.name}" class='btn btn-lg btn-default jsButColor button_color'>
	{$row.value}
	</a>	
{/if}

{if $command=='blinds'}
	<a href='#' data-address='{$row.address}' data-type='blinds' data-target='on' data-invert='{$row.invert_set}' title="" class='btn btn-lg btn-default jsButBlinds button_blinds'><i class="fa fa-chevron-up"></i><u>.</u></a>
	<a href='#' data-address='{$row.address}' data-type='blinds' data-target='off' data-invert='{$row.invert_set}' title="" class='btn btn-lg btn-default jsButBlinds button_blinds'><i class="fa fa-chevron-down"></i><u>.</u></a>
{/if}

{if $command=='selector'}
	{if $my_popup}
		<span class='btn btn-{$style} btn-lg button_popup'>
		<select class="form-control jsButPopup">
		{html_options options=$row.choices selected=$row.value}
		</select>
		</span>
	{else}
		{foreach from=$row.choices key=cv item=cn}
			{$my_class=''}
			{if $row.value==$cv}{$my_class='btn-success'}{/if}
			<a href='#' data-value='{$cv}' title="" class='btn btn-lg btn-default jsButSelector button_selector {$my_class}'>{$cn}</a>
		{/foreach}	
	{/if}
{/if}

</div>


{if $command=='dimmer' && !$my_popover_created}
<div id="jsDimmerPopover_{$row.js_address}" class="hidden">
		<div class="dimmer_popover_content">
        	<span class="legend">0</span><input type="text" id="jsSlider_{$row.js_address}" class="jsSlider" value="{$row.value}" data-slider-min="0" data-slider-max="100" data-slider-step="{100/{$row.dim_steps}}" data-slider-value="{$row.value}"><span class="legend">100</span>
		</div>
</div>
{$my_popover_created=1}
{/if}

{if $command == 'value' && !$my_valuepopup_created}
<div class="jsValuePopoverHidden_{$row.js_address} hidden">
	<div class='value_popover_content jsValuePopoverInput'>
		<input id="jsInput_{$row.js_address}" type="text" name='input_{$row.uid}' value='{$row.value}' class="input_value jsInputValue">
	</div>
</div>
{$my_valuepopup_created=1}
{/if}

{if $row.color_rgb && !$my_colorwheel_created}
<div class="jsRgbPopoverHidden_{$row.js_address} hidden">
	<div class='rgb_popover_content'>
		{if $c.colors}
		<div class="rgb_presets">
			<div class="rgb_presets_bar">
				{foreach from=$c.colors key=k item=color}
					<span class="rgb_preset jsRgbPreset" data-rgb="{$color}" style="background-color: #{$color}">{$k|ucfirst}</span>
				{/foreach}
			</div>
		</div>
		{/if}
		<div class="rgb_sliders jsRgbPopoverInput">
			<input id="jsInput_{$row.js_address}" type="text" name='input_{$row.uid}' value='{$row.color_rgb}' class="input_color jsInputColor" data-wcp-sliders="{$c.conf.rgb_sliders|default:'hsvwrgb'}" data-wcp-cssclass="colorpicker jsColorPicker_{$row.js_address}">
		</div>
		<div class="rgb_preview">
			<div class="jsRgbPreview" style="background-color: #{$row.color_rgb}"></div>
		</div>
		
	</div>
</div>
{$my_colorwheel_created=1}
{/if}


{/function}


{* ##################################################################################### *}

{function name=makeSensorHome row=''}
	{if $row.html_value}
		{$my_value=$row.html_value}
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
	<span data-uid="{$row.uid}" data-value="{$my_value}" class='sensor{$my_class}'>
		<img src='{$p.urls.static}{$row.img_url}'> {$my_name} 
{if $row.url_view_sensor}<A href="{$row.url_view_sensor}" target="_blank">{/if}
		<b class='jsSensorValue'>{$my_value}</b>
{if $row.url_view_sensor}</A>{/if}
		{$row.unit|default:$p.units.{$row.type}}
	</span>
{/function}

{function name=number_format_lang from='' count=0 thous_sep=',' dec_sep=',' }
	{if $c.conf.lang=='en' || $c.conf.lang=='uk'}
		{$from|number_format:$count:'.':','}
	{else}
		{$from|number_format:$count:'.':' '|replace:' ':'&nbsp;'}
	{/if}
{/function}