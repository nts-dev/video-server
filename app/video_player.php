<?php
$file = $_GET['mfile'];

$vid_url = "http://77.61.38.201:1935/vod/mp4:" . $file . "/manifest.mpd?wowzacaptionfile=SampleVideo_720x480_10mb.srt";
?>
<!DOCTYPE html>
<html>
    <head>
        <!--<link href="../lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">-->
        <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="../lib/bootstrap/js/bootstrap.min.js"></script>

        <script src="MPEGDASHPlayer/js/dash.all.js"></script>
        <script>
            // setup the video element and attach it to the Dash player
            function setupVideo() {
                var url = " <?php echo $vid_url ?>";
                var context = new Dash.di.DashContext();
                var player = new MediaPlayer(context);
                player.startup();
                player.attachView(document.querySelector("#videoplayer"));
                player.attachSource(url);
            }
        </script>

        <!------ Include the above in your HEAD tag ---------->

        <style>

            video {
                width: 100%;
                height: 99%;
            }

        </style>
    </head>
    <body onload="setupVideo()" class="hm-gradient">
        <div  style="margin-top:3%;  ">
            <video id="videoplayer" controls></video>
        </div>
    </body>

</html>