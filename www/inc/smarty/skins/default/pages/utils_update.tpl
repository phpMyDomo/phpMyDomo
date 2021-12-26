{function name="makeChangeLog"}
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
{/function}

{capture assign=page_content}
<div class="row">
	<div class='col-md-8 col-md-offset-2'>


	{if $data.step=='done'}

		<div class="panel panel-success">
			<div class="panel-heading h3"><i class='fa fa-thumbs-up'></i> Congratulation!</div>
			<div class="panel-body">
				<p class="mess mess_success">{$data.text|default:"Update sucessfully completed !"}</p>
				<p class="mess">Some new parameters might have been added to the <i>inc/conf_sample/config.php</i> file.<br>
				 Please compare this file with your current <i>inc/conf/config.php</i> file to start using these new parameters.</p>
				<div class="centered">
					<a href='{$p.urls.www}/' class="btn btn-lg btn-success"><i class='fa fa-reply'></i> Return to the home page</a>
				</div>
			</div>
		</div>

	{else}

		<div class="panel panel-default">
		{if $data.step}
			{$next_step=$data.step+1}
			{if $data.refresh}<meta http-equiv='refresh' content='{$data.refresh}; url={$p.urls.www}/utils/update?step={$next_step}'>{/if}

			<div class="panel-heading h3">
				<B><i class='fa fa-flag'></i> Step {$data.step}</B> : {$data.title}
			</div>
			
			<div class="panel-body">
				<p class="mess">{$data.text}</p>
				<a href='?step={$next_step}' class="btn btn-lg btn-warning pull-right"><i class='fa fa-chevron-circle-right'></i> Continue to Step {$next_step}</a>
			</div>

		{elseif $data.dl_version}
			<div class="panel-heading h3">
				<i class='fa fa-warning'></i> New version <b>{$data.dl_version}</b> available
			</div>

			<div class="panel-body">
				<div class="dl_help">
				<p class="mess centered">A new version of {$c.app.name} if available to download!</p>

				{makeChangeLog}

				<h3>Installation</h3>
				If you had installed {$c.app.name} by cloning it with git, you just have to do:<br>
				<code>cd {$data.paths.root} ; git pull</code><br>
				them direclty jump to step 8.<br>
				<br>
				Else follow these steps:
				
				<ul class="dl_install">
			
					<li> 1) make a copy of your current <b>conf directory</b> : <br>
						<code>cp -r {$data.paths.confs} ~/pmd_conf/</code><br>
						<i>if you are using a custom skin, or had made any changes to the templates, also backup your custom files.</i>
					</li>
					<li> 2) Download the latest version as a <a href="{$p.urls.pmd_dl_zip}"><b>ZIP</b></a> or <a href="{$p.urls.pmd_dl_gz}"><b>GZ</b></a> archive, ie:<br>
						<code>cd ~/ ; wget  {$p.urls.pmd_dl_zip} -O phpMyDomo_latest.zip</code> or<br>
						<code>cd ~/ ; wget  {$p.urls.pmd_dl_gz} -O phpMyDomo_latest.tar.gz</code>
					</li>
					<li> 3) de-compress the archive you have just downloaded :<br>
						<code>unzip phpMyDomo_latest.zip </code> or<br>
						<code>tar xvfz phpMyDomo_latest.tar.gz </code>
					</li>
					<li> 4) remove your current phpMyDomo "www" directory :<br>
						<code>rm -rf {$data.paths.www}</code>
					</li>
					<li> 5) replace it with your newest 'www' directory :<br>
						<code>mv ~/phpMyDomo-{$c.app.name}-v.{$data.dl_version}-XXXX/www {$data.paths.root}</code>
					</li>
					<li> 6) restore your backuped conf folder to its original location :<br>
						<code>mv ~/pmd_conf/ {$data.paths.confs}</code>
					</li>
					<li> 7) make sure that the cache directory and all it sub-directories are writable :<br>
						<code>chmod -R 777 {$data.paths.caches}</code>
					</li>
					<li> 8) point your browser to <a href='{$p.urls.www}/utils/update'>{$p.urls.www}/utils/update</a> to finish the update procedure
					</li>
					
					</ul>
				</div>
			</div>

		{else}

			<div class="panel-heading h3">
				<i class='fa fa-warning'></i> Update <b>{$c.app.name}</b> v{$data.old_version} to v{$data.new_version}
			</div>
			
			<div class="panel-body">

				{makeChangeLog}

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