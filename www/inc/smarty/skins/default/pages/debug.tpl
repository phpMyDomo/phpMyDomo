{capture assign=page_content}
<div class='h1'>Debug</div>
<div class="div_debug">
	<span class="backtrace">{$p.content_backtrace}</span>
	<h5>{$p.content_txt}</h5>
	<pre>{$p.content_arr}</pre>
</div>
{/capture}

{include file="{$p.template}/layout.tpl"}