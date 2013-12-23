<div id='lay_footer'>
	<ul class="menu_foot">
{foreach from=$p.menu_foot item=k}
		<li class="menu_{$k}{if $p.code==$k} active{/if}"><a href='{$p.urls.www}/{$p.menu_urls.$k}'><i class='{$p.menu_icons.$k}'></i> {$lg.menu_head.$k|default:$k}</a></li>	
{/foreach}
	</ul>
	<div class="copyright">
		<a href="{$p.urls.pmd_url}">{$c.app.name}</a> v{$c.app.version} {if $c.app.dl_version} <i>(version {$c.app.dl_version} <a href='{$p.urls.www}/utils/update'>available</a>)</i>{/if}
		{if $c.app.demo}<span class='foot_demo'>DEMO MODE Some functions are desactivated!</span> <span class='foot_skin'><a href='?skin=black'>Black</a> or <a href='?skin=metal'>Metal</a> skin</span>{/if}
	</div>
</div>