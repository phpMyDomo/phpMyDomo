{capture assign=page_content}
<div id='div_albums'>

{foreach from=$data.albums item=album key=id}
	<a href="?id={$id}" name="a_{$id}" title="{$album.time|date_format} - {$album.title|ucfirst}">
		<div class="panel panel-default panel_album{if $data.selected==$id} panel_selected{/if}">
			<div class="panel-heading">
				{$album.title|ucfirst|truncate:22:'.':true} <span class="badge">{$album.count}</span>
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