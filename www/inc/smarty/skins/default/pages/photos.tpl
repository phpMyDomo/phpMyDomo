{capture assign=pagination}
{if $data.pages > 1}
<div class="text-center">
	<ul class="pagination">
	{if $data.current_page > 1}
 		<li class="navpage"><a href="?p={$data.current_page - 1}"><i class="fa fa-arrow-circle-o-left"></i></a></li>
	{/if}
	{for $page=1 to $data.pages}
		<li class="{if $page==$data.current_page}active{/if}"><a href="?p={$page}">{$page}</a></li>
	{/for}
	{if $data.current_page < $data.pages }
		<li class="navpage"><a href="?p={$data.current_page + 1}"><i class="fa fa-arrow-circle-o-right"></i></a></li>
	{/if}
	</ul>
</div>
{/if}
{/capture}
{capture assign=page_content}

<div id='div_albums'>

{foreach from=$data.rows item=album key=id}
	<a href="?id={$id}" name="a_{$id}" title="{$album.time|date_format} - {$album.title|ucfirst}">
		<div class="panel panel-default panel_album{if $data.selected==$id} panel_selected{/if}">
			<div class="panel-heading">
				<span class="badge">{$album.count}</span>
				{$album.title|ucfirst|truncate:22:'.':true} 
			</div>
			<div class="panel-body-full">
				<img src="{$album.thumb|default:$album.image}">
			</div>
		</div>
	</a>
{/foreach}

</div>

{$pagination}

{/capture}

{include file="{$p.dir_templates}/layout.tpl"}