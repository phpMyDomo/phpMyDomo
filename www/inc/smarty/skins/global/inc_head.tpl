<div id='lay_header'>
    <header class="navbar navbar-inverse navbar-fixed-top" role="banner">
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

{foreach from=$p.menu_head key=k item=menu}
					<li class="menu_{$k}{if $menu.active} active{/if}"><a href='{$menu.url}'><i class='{$menu.icon}'></i> {$menu.name}</a></li>	
{/foreach}
				</ul>
			    <ul class="nav navbar-nav navbar-right">
					<li class="menu_api"><a href="{$p.urls.server_admin}" title="{$p.api|ucfirst} Admin"><img src='{$p.urls.static}/global/img/icon48_api_{$p.api}.png'></a></li>
				</ul>
			</div>
		</div>
	</header>
</div>

{if $c.app.version_needs_update && $p.code !="utils_update"}
<div class="container">
	<div class="alert alert-danger text-center">
		<div class='h3'><div class="text-danger"><i class='fa fa-warning'></i> Please <u><a href='{$p.urls.www}/utils/update?step=1'>click here</a></u> to finish the update !</div></div>
	</div>
</div>
{/if}
