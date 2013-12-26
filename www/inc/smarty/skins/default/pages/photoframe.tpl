{capture assign=main_content}
		<script type="text/javascript">
			
			jQuery(function($){
				$.supersized({
					// Functionality
					slide_interval          :   {$data.prefs.speed},
					performance          	:   {$data.prefs.performance},
					transition              :   {$data.prefs.transition},
					transition_speed		:	{$data.prefs.transition_speed},
															   
					// Components
					slide_links				:	'blank',	// Individual links for each slide (Options: false, 'num', 'name', 'blank')
					slides 					:  	[			// Slideshow Images
{foreach from=$data.photos item=i name=loop}
{$my_title=$i.title}
{if $data.prefs.show_date}{$my_title=$my_title|cat:" <span class='photoDate'> - {$i.time|date_format}</span>"}{/if}
{	image: "{$i.image}", title : "{$my_title}" , thumb : "{$i.thumb|default:$i.image}", url : "{$i.image}"	}
{if !$smarty.foreach.loop.last},{/if}
{/foreach}
												]
					
				});
				
				$('.jsSelect').change(function(){
					var val=$(this).val();
					window.location = "?id="+val;
				});
				
		    });
		    
		</script>	



<div id='div_photos'>
	<a id="pmd_goback" href='?selected={$data.selected}#a_{$data.selected}' title="Albums"></div></a>

{if $data.prefs.show_thumb_nav}
	<div id="prevthumb"></div>
	<div id="nextthumb"></div>
{/if}	
{if $data.prefs.show_slide_nav}
	<a id="prevslide" class="load-item"></a>
	<a id="nextslide" class="load-item"></a>
{/if}	
	
{if $data.prefs.show_thumb}
	<div id="thumb-tray" class="load-item">
		<div id="thumb-back"></div>
		<div id="thumb-forward"></div>
	</div>
{/if}	
	
{if $data.prefs.show_progress_bar and $data.prefs.show_control_bar}
	<div id="progress-back" class="load-item">
		<div id="progress-bar"></div>
	</div>
{/if}

{if $data.prefs.show_control_bar}
	<!--Control Bar-->
	<div id="controls-wrapper" class="load-item">
		<div id="controls">
			
	{if $data.prefs.show_play}
			<a id="play-button"><img id="pauseplay" src="{$p.urls.static}/global/img/supersized/pause.png"/></a>
	{/if}			

	{if $data.prefs.show_album}
			<div id="albumName">{$data.albums.{$data.selected}|ucfirst} :</div>
	{/if}
		
	{if $data.prefs.show_counter}
			<div id="slidecounter"><span class="slidenumber"></span> / <span class="totalslides"></span></div>
	{/if}			
	{if $data.prefs.show_caption}
			<div id="slidecaption"></div>
	{/if}

	{if $data.prefs.show_thumb}			
			<!--Thumb Tray button-->
			<a id="tray-button"><img id="tray-arrow" src="{$p.urls.static}/global/img/supersized/button-tray-up.png"/></a>
	{/if}

	{if $data.prefs.show_albums}
			<ul id="selectAlbum">
				<select name='album' class="jsSelect">
{html_options options=$data.albums selected=$data.selected}
				</select>
			</ul>
	{/if}

	{if $data.prefs.show_bullets}			
			<ul id="slide-list"></ul>
	{/if}

			
		</div>
	</div>
{/if}


</div>
{/capture}

{include file="global/main.tpl"}