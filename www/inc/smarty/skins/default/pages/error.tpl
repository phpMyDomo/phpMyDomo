{capture assign=page_content}
<div class='h1'>Error <b>{$p.err_code}</b></div>
<div class="well"><i class="fa fa-warning"></i> {$p.err_txt}</div>
{/capture}

{include file="{$p.template}/layout.tpl"}