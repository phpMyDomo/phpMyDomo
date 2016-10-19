{capture assign=page_content}
<div id='div_cameras'>

{if $data.selected}
	{$cam=$data.selected}
<style>
#cam_{$cam.uid},
#cam_{$cam.uid} IMG {
	width: 100%;
}

</style>
	<div class="panel panel-default pmd_panel camera_full" id="cam_{$cam.uid}">
		<div class="panel-heading"><i class="fa fa-video-camera"></i> {$cam.name}</div>
		<div class="panel-body-full">
			<div class='iframe_border'>
				<img src="{$cam.url}">
			</div>
		</div>
	</div>
<p class="buttons">
	<a class="btn btn-primary btn-lg" href="?"><i class="fa fa-arrow-left"></i> {$lg.menu_head.cameras}</a>
</p>
{else}
	{foreach $data.cameras item=cam}
	<div class="panel panel-default pmd_panel" id="cam_{$cam.uid}">
		<div class="panel-heading"><i class="fa fa-video-camera"></i> {$cam.name}</div>
		<div class="panel-body-full">
			<div class='iframe_border'>
				<a href="?id={$cam.uid}"><img src="{$cam.url}" width={{$cam.f_x}+2} height={{$cam.f_y}+2}></a>
			</div>
		</div>
	</div>
	{/foreach}
{/if}
</div>
{/capture}


{include file="{$p.template}/layout.tpl"}