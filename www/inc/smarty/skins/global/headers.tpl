{assign var='head_title' value={$p.title|default:$p.code|cat:' - {$p.app_name}'}}
	<title>{eval var=$head_title}</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="content-language" content="{$c.app.lang}" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<link rel="SHORTCUT ICON" href="{$p.dirs.static}/global/img/app_icon32.gif" />
	<link rel="icon" type="image/gif" href="{$p.dirs.static}/global/img/app_icon32.gif" />
	<link rel="apple-touch-icon" href="{$p.dirs.static}/global/img/app_icon57.png" />
{$p.headers}