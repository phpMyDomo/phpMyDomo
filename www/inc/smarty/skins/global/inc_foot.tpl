<div id='lay_footer'>
	<ul class="menu_foot">
{foreach from=$p.menu_foot item=k}
		<li class="menu_{$k}{if $p.code==$k} active{/if}"><a href='{$p.urls.www}/{$p.menu_urls.$k}'><i class='{$p.menu_icons.$k}'></i> {$lg.menu_head.$k|default:$k}</a></li>	
{/foreach}
	</ul>
	<div class="copyright"><a href="{$p.urls.pmd_url}">{$c.app.name}</a> v{$c.app.version} {if $c.app.dl_version} <i>(version {$c.app.dl_version} <a href='{$p.urls.www}/utils/update'>available</a>)</i>{/if}</div>
</div>