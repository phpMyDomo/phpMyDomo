<div id='lay_header'>
    <header class="navbar navbar-inverse navbar-fixed-top role="banner">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
			</div>

			<div class="collapse navbar-collapse navbar-ex1-collapse">
				<ul class="nav navbar-nav">
{foreach from=$p.menu_head item=k}
					<li class="menu_{$k}{if $p.code==$k} active{/if}"><a href='/{$p.menu_urls.$k}'><i class='{$p.menu_icons.$k}'></i> {$lg.menu_head.$k|default:$k}</a></li>	
{/foreach}
				</ul>
			    <ul class="nav navbar-nav navbar-right">
					<li class="menu_admin"><a href="{$p.urls.server_admin}" title="Admin"><img src='{$p.urls.static}/global/img/icon48_api_{$p.api}.png'></a></li>
				</ul>
			</div>
		</div>
	</header>
</div>