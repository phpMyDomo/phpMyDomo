{capture assign=page_content}
<div class="row">
	<div class='col-md-8 col-md-offset-2'>


	{if $data.step=='done'}

		<div class="panel panel-success">
			<div class="panel-heading h3"><i class='fa fa-thumbs-up'></i> Congratulation!</div>
			<div class="panel-body">
				<p class="mess mess_success">{$data.text|default:"Update sucessfully completed !"}</p>
				<div class="centered">
					<a href='{$p.dirs.www}/' class="btn btn-lg btn-success"><i class='fa fa-reply'></i> Return to the home page</a>
				</div>
			</div>
		</div>

	{else}

		<div class="panel panel-default">

		{if $data.step}
			{$next_step=$data.step+1}
			{if $data.refresh}<meta http-equiv='refresh' content='{$data.refresh}; url={$p.dirs.www}/utils/update?step={$next_step}'>{/if}

			<div class="panel-heading h3">
				<B><i class='fa fa-flag'></i> Step {$data.step}</B> : {$data.title}
			</div>
			
			<div class="panel-body">
				<p class="mess">{$data.text}</p>
				<a href='?step={$next_step}' class="btn btn-lg btn-warning pull-right"><i class='fa fa-chevron-circle-right'></i> Continue to Step {$next_step}</a>
			</div>

		{else}

			<div class="panel-heading h3">
				<i class='fa fa-warning'></i> Update <b>{$c.app.name}</b> v{$data.old_version} to v{$data.new_version}
			</div>
			
			<div class="panel-body">
			{if $data.changelog}
				<h3>ChangeLog since version {$data.old_version}</h3>
				<ul class="changelog">
				{foreach from=$data.changelog key=v item=version}
					<h5>Version {$v} <i>{$version.title}</i></h5>
					<ul>	
					{foreach from=$version.lines key=type item=lines}
					{if $type=='new'}{$icon='plus-circle'}{elseif $type=='fix'}{$icon='check'}{elseif $type=='dev'}{$icon='gear'}{/if}
						{foreach from=$lines item=line}
						<li class='{$type}'><i class='fa fa-{$icon}'></i> {$type|ucfirst}: {$line}</li>
						{/foreach}
					{/foreach}
					</ul>
				{/foreach}
				</ul>
				<hr>
			{/if}
				<p class="mess centered">
					Click here to start updating your <b>{$c.app.name}</b>
				</p>
				<div class="centered">
					<a href='?step=1' class="btn btn-lg btn-warning"><i class='fa fa-arrow-circle-right'></i> Start Update</a>
				</div>
			</div>

		{/if}
		</div>

	{/if}


	</div>
</div>
{/capture}
{include file="{$p.template}/layout.tpl"}