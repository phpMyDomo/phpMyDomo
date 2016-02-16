{capture assign=page_content}
{* --- Modal -------------------*}

{include file="{$p.template}/clock/inc_modal.tpl"}

<div id="clock_type_{$data.type}">

	<div class="col-sm-8 jsClockCol">
{include file="{$p.template}/clock/inc_date.tpl"}
{if $data.type=='digital'}
{include file="{$p.template}/clock/inc_time_digital.tpl"}
{else if $data.type=='analog'}
{include file="{$p.template}/clock/inc_time_analog.tpl"}
{/if}
	</div>

	<div class="col-sm-4">

{include file="{$p.template}/clock/inc_sensors.tpl"}
{include file="{$p.template}/clock/inc_timer.tpl"}

	</div>

</div>

{/capture}

{include file="{$p.template}/layout.tpl"}