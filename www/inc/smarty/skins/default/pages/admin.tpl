{capture assign=page_content}

<div id="admin_menu">
{foreach from=$p.menu_admin item=k}
	<li>
		<a href='{$p.urls.www}/{$p.menu_admin_urls.$k}' class='btn btn-info btn-lg'><i class='{$p.menu_admin_icons.$k}'></i> {$lg.menu_admin.$k|default:$k}</a>
	</li>	
{/foreach}
</div>
{/capture}


{include file="{$p.dir_templates}/layout.tpl"}