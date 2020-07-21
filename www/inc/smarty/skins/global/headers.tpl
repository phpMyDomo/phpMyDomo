{assign var='head_title' value=$p.title|cat:' - {$p.app_name}'}}
	<title>{eval var=$head_title}</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta http-equiv="content-language" content="{$c.app.lang}" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, user-scalable=no, minimal-ui" />
	<meta name="apple-mobile-web-app-title" content="{$p.app_name}">
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="apple-mobile-web-app-status-bar-style" content="black">
	
	<link rel="SHORTCUT ICON" href="{$p.urls.static}/global/img/app_icon32.gif" />
	<link rel="icon" type="image/gif" href="{$p.urls.static}/global/img/app_icon32.gif" />
	<link rel="apple-touch-icon" href="{$p.urls.static}/global/img/app_icon57.png" />
{$p.headers}