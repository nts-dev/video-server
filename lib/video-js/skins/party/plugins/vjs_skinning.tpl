{literal}
<style>
.frm {display:table;margin:10px auto;}
.frm select {
    color: #000;border: 0 none;border-radius: 4px;-webkit-border-radius: 4px; -moz-border-radius: 4px; font-size: 15px; padding: 10px 10px;vertical-align: top;width: 220px;
}
</style>
{/literal}

<link href="/vdjs/skins/{$skinname}/video-js{$skintype}.css" rel="stylesheet" type="text/css" />
{if $ima=='1'}<link href="/vdjs/skins/{$skinname}/videojs.ima.css" rel="stylesheet" type="text/css" />{/if}
<section class="content">

<h1>Advanced progress thumbs for Video Js player</h1>
<div class="media-vjs">
<div class="media-parent">
<video id="veoplayer" class="video-js vjs-fluid vjs-default-skin" shareUrl='{$baseurl}.com/videojs/' poster="{$baseurl}/vdjs/media/surf.jpg" slideImage="{$baseurl}/vdjs/media/surf_slide.jpg" controls
 preload="auto">
  {if $skintype=='_full'}
  <source src="{$baseurl}/vdjs/media/surf_240.mp4" res="240" label="240p" type="video/mp4">
  <source src="{$baseurl}/vdjs/media/surf_360.mp4" res="360" label="360p" type="video/mp4">
  <source src="{$baseurl}/vdjs/media/surf_480.mp4" res="380" label="480p" type="video/mp4">
  {/if}
  <source src="{$baseurl}/vdjs/media/surf_720.mp4" default res="720" label="720p" type="video/mp4">

</video>
</div>
</div>

{if $ima == '1'}<script src="//imasdk.googleapis.com/js/sdkloader/ima3.js"></script>{/if}
<script type="text/javascript" src="//vjs.zencdn.net/6.6.3/video.js"></script>
<script type="text/javascript" src="{$baseurl}/vdjs/videojs.ads.min.js"></script>
{if $ima=='1'}
<script type="text/javascript" src="/vdjs/2017/nuevo.min.ima.js"></script>
{/if}
<script type="text/javascript" src="{$baseurl}/vdjs/2017/nuevo.2017.js"></script>
{if $skintype=='_full'}
	<script>
	{if $skinname=='daily'}
	{literal}
	var nuevo = {
			controlBar: {
			children: [ 'playToggle',  'liveDisplay', 'remainingTimeDisplay', 'progressControl', 'currentTimeDisplay','timeDivider', 'durationDisplay', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton', 'playbackRateMenuButton','volumePanel', 'fullscreenToggle'],
		    },
			"techOrder": ["youtube","html5"]
		};	
	{/literal}
	
	{elseif $skinname=='white'}
	{literal}
	var nuevo = {
	controlBar: {
        children: [ 'playToggle',  'liveDisplay','remainingTimeDisplay',  'currentTimeDisplay','timeDivider','durationDisplay','progressControl', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton', 'volumePanel','fullscreenToggle'],
    },
	"techOrder": ["youtube","html5"]
	};
	{/literal}
	
	{elseif $skinname=='custom'}
	{literal}
	var nuevo = {
	controlBar: {
        children: [  'progressControl', 'playToggle', 'liveDisplay', 'remainingTimeDisplay', 'currentTimeDisplay', 'timeDivider', 'durationDisplay', 'volumePanel', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton','fullscreenToggle'],
	 },
	"techOrder": ["youtube","html5"]
	};
	{/literal}
{/if}
var baseurl = '{$baseurl}';
{literal}
var related_videos=new Array; var obj;
obj = {thumb: baseurl+'/vdjs/media/freeride_thumb.jpg',url: baseurl+'/videojs/demo/freeride',title: 'People are amazin, bikers in particular!',duration: '00:33'}; 
related_videos.push(obj);
obj = {thumb: baseurl+'/vdjs/media/big_buck_thumb.jpg',url: baseurl+'/videojs/demo/big_buck',title: 'Big Buck Bunny',duration: '02:43'}; 
related_videos.push(obj);
obj = {thumb: baseurl+'/vdjs/media/animals_thumb.jpg',url: baseurl+'/videojs/demo/animals',title: 'Animals are amazing!'}; 
related_videos.push(obj);
obj = {thumb: baseurl+'/vdjs/media/bmv_thumb.jpg',url: baseurl+'/videojs/demo/bmv',title: 'BMV M4 Ultimate Racetrack'}; 
related_videos.push(obj);
obj = {thumb: baseurl+'/vdjs/media/australia_thumb.jpg',url: baseurl+'/videojs/demo/australia',title: 'Welcome to Australia'}; 
related_videos.push(obj);
obj = {thumb: baseurl+'/vdjs/media/base_jump_thumb.jpg',url: baseurl+'/videojs/demo/base_jump',title: 'Incredible BASE Jump'}; 
related_videos.push(obj);
var player = videojs('veoplayer',nuevo,function(){ 
	
	this.nuevoPlugin({
		//logo:baseurl+'/vdjs/media/yourlogo.png',
		logotitle:'Nuevolab Software',
		logoposition:'RT',
		logourl:baseurl+'.com/videojs/',
		related: related_videos,
		mirrorButton:true,
		videoInfo:true,
		shareTitle:"Nuevo plugin for VideoJs Player"

	});

});
{/literal}
</script>
{else}
<script>
	{if $skinname=='white'}
		{literal}
		var nuevo = {
			controlBar: {
			children: [ 'liveDisplay','playToggle', 'currentTimeDisplay','timeDivider','durationDisplay','progressControl', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton', 'playbackRateMenuButton', 'volumePanel','fullscreenToggle'],
		    },
			"techOrder": ["youtube","html5"],
			"playbackRates": [0.7, 1.0, 1.5, 2.0]
		};	
		
		var player = videojs('veoplayer',nuevo,function(){

		    
		});
		{/literal}
	{elseif $skinname=='daily'}
		{literal}

		var nuevo = {
			controlBar: {
			children: [ 'playToggle',  'liveDisplay', 'remainingTimeDisplay', 'progressControl', 'currentTimeDisplay','timeDivider', 'durationDisplay', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton', 'playbackRateMenuButton','volumePanel', 'fullscreenToggle'],
		    },
			"techOrder": ["youtube","html5"],
			"playbackRates": [0.7, 1.0, 1.5, 2.0] 
		};	
		var player = videojs('veoplayer',nuevo,function(){});
		{/literal}
	{elseif $skinname=='custom'}
		{literal}

		var nuevo = {
			controlBar: {
			children: [  'progressControl', 'playToggle', 'liveDisplay', 'remainingTimeDisplay', 'currentTimeDisplay', 'timeDivider', 'durationDisplay', 'volumePanel', 'chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton','playbackRateMenuButton','fullscreenToggle'],
		    },
			"techOrder": ["youtube","html5"],
			"playbackRates": [0.7, 1.0, 1.5, 2.0] 
		};	
		var player = videojs('veoplayer',nuevo,function(){});
		{/literal}
	{else}
		{literal}
		var nuevo = {
			
			controlBar: {
				volumePanel: { inline: false, vertical: true },
			children: [ 'liveDisplay','playToggle','currentTimeDisplay','remainingTimeDisplay','progressControl','durationDisplay','chaptersButton','descriptionsButton','subsCapsButton','audioTrackButton','volumePanel','fullscreenToggle'],
		    },
			"techOrder": ["youtube","html5"],
			"playbackRates": [0.7, 1.0, 1.5, 2.0] 
		};
		var player = videojs('veoplayer',nuevo,function(){});
		{/literal}
	{/if}
</script>
{/if}

{if $ima == '1'}
<script>
{literal}
var options = {
	id: 'veoplayer',
	adTagUrl: '{/literal}{$imatag}{literal}'
};

player.on('nuevoReady', function(){
	player.ima(options);
});


var contentPlayer =  document.getElementById('veoplayer_html5_api');
if ((navigator.userAgent.match(/iPad/i) ||
      navigator.userAgent.match(/Android/i)) &&
    contentPlayer.hasAttribute('controls')) {
  contentPlayer.removeAttribute('controls');
}
var startEvent = 'click';
if (navigator.userAgent.match(/iPhone/i) ||
    navigator.userAgent.match(/iPad/i) ||
    navigator.userAgent.match(/Android/i)) {
  startEvent = 'touchend';
}

player.one(startEvent, function() {
    player.ima.initializeAdDisplayContainer();
});    
{/literal}
</script>
{/if}
</section>
<div class="full-width">
<section class="article">
<span class="title">Select Skin</span>


<div class="frm">

<input type="text" style="width:100%;" id="debug" value="Debug" />
<br /><br />


<form id="re-form" method="POST" action = "/videojs/skins">

Skin name: <select name="skinname">
<option value="default">Default</option>
<option value="white"{if $skinname == 'white'} selected="selected"{/if}>White</option>
<option value="daily"{if $skinname == 'daily'} selected="selected"{/if}>Daily</option>
<option value="custom"{if $skinname == 'custom'} selected="selected"{/if}>Custom</option>
</select> Skin type: <select name="skintype">
<option value="">Normal</option>
<option value="_full"{if $skintype == '_full'} selected="selected"{/if}>Full</option>
</select>
<br /><br />
<input type="submit" value="Submit" />
</form>



</div>



</section>
</div>