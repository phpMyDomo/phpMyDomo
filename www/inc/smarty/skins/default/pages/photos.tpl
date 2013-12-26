{capture assign=page_content}
<div id='div_albums'>

{foreach from=$data.albums item=album key=id}
	<a href="?id={$id}">
		<div class="panel panel-default panel_album{if $data.selected==$id} panel_selected{/if}">
			<div class="panel-heading">
				{$album.title}
			</div>
			<div class="panel-body-full">
				<img src="{$album.thumb|default:$album.image}">
			</div>
		</div>
	</a>


{/foreach}

</div>
{/capture}

{include file="{$p.template}/layout.tpl"}