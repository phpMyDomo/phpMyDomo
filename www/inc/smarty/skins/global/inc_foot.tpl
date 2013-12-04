<div id='lay_footer'>
	<ul class="menu_foot">
{foreach from=$p.menu_foot item=k}
		<li class="menu_{$k}{if $p.code==$k} active{/if}"><a href='/{$p.menu_urls.$k}'><i class='{$p.menu_icons.$k}'></i> {$lg.menu_head.$k|default:$k}</a></li>	
{/foreach}
	</ul>
	<div class="copyright">{$c.app.name} v{$c.app.version}</div>
</div>