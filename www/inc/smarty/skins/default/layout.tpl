{capture assign=main_content}

<div id="lay_page">
{include file='global/inc_head.tpl'}

	<div id='lay_content'>
		<div class='container{if $cont_fluid}-fluid{/if}'>
			<div class="row">
{if $page_right}
				<div class="col-md-9">
	{$page_content}
				</div>
				<div class="col-md-3">
	{$page_right}
				</div>
{else}
	{$page_content}
{/if}
			</div>
		</div>

	</div>
	<div id="lay_push"><!--//--></div>
</div>

{include file='global/inc_foot.tpl'}

{/capture}
{include file='global/main.tpl'}