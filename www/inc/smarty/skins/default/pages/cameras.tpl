{capture assign=page_content}
<div id='div_cameras'>

{foreach $data.cameras item=cam}
	<div class="panel panel-default camera" id="cam_{$cam.id}">
		<div class="panel-heading"><i class="fa fa-video-camera"></i> {$cam.name}</div>
		<div class="panel-body-full">
			<div class='iframe_border'>
				<iframe src="{$cam.url}" width='322' height=242></iframe>
			</div>
		</div>
	</div>
{/foreach}

</div>
{/capture}


{include file="{$p.template}/layout.tpl"}