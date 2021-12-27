{if $c.app.custom_client_api}
<div class="container">
	<div class="alert alert-danger text-center">
		It seems that sou you are using a custom made Api client, "<b>{$c.app.api}</b>".<br>
		We'd be really grateful if you could <b>submit it back to <a href="{$c.urls.pmd_github}" target='_blank'>our GitHub</a></b>, so that everyone could use it or improve it.
	</div>
</div>
{/if}

<div id='lay_footer'>
	<ul class="menu_foot">
{foreach from=$p.menu_foot key=k item=menu}
		<li class="menu_{$k}{if $menu.active} active{/if}"><a href='{$menu.url}'><i class='{$menu.icon}'></i> {$menu.name}</a></li>	
{/foreach}
	</ul>
	<div class="copyright">
		<a href="{$p.urls.pmd_url}">{$c.app.name}</a> v{$c.app.version} {if $c.app.dl_version} <i>(version {$c.app.dl_version} <a href='{$p.urls.www}/utils/update'>available</a>)</i>{/if}
		{if $c.app.demo}<span class='foot_demo'>DEMO MODE Some functions are desactivated!</span> <span class='foot_skin'><a href='?skin=black'>Black</a> or <a href='?skin=metal'>Metal</a> skin</span>{/if}
	</div>
</div>