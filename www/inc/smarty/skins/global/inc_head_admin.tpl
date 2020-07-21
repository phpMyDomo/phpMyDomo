<div id='lay_admin_menu'>
				<ul class="">
{foreach from=$p.menu_admin item=k}
					<li class="menu_{$k}{if $p.code=="admin_{$k}"} active{/if}"><a href='{$p.urls.www}/{$p.menu_admin_urls.$k}'><i class='{$p.menu_admin_icons.$k}'></i> {$lg.menu_admin.$k|default:$k}</a></li>	
{/foreach}
				</ul>
</div>