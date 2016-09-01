<?php
$play = $_GET['play'];

if($play > 0){
    //Play video
    $api_url = $config['publicphp'] . '?action=plugin_vod&id=' . $play;
    $iframe_url = $config['publicphp'] . '?action=plugin_vod&iframe=' . $play . '&autoplay=1';
    $is_vod = true;
}else{
    //Play channel
    $api_url = $config['publicphp'] . '?action=plugin_videomanager&id=' . abs($play);
    $iframe_url = $config['publicphp'] . '?action=plugin_videomanager&iframe=' . abs($play)  . '&autoplay=1';
    $is_live = true;
}

$content = json_decode(file_get_contents($api_url),true);

if($content == null){
    header('location:error.php');
}

$tags = explode(' ', $content['tags']);

$playing = $play;

$relatedtag = strtolower(end($tags));

$pagetitle = "LCR- " . $content['title'];

if(audio_only($content) == true){
    $onclick = "player.load($play); player.loadMiniPlayer(this);";
}else{
    $onclick = "player.replaceInner(this, $play, '$iframe_url')";
}
?>

<!-- Player & playlist -->
<div class="row">
    <div class="col s12 l8">
        <?php if(on_mobile()){ ?>
        <div id="player-container" class="z-depth-1 player-container <?= $content['type'] ?>-container">
            <iframe class="player-inner <?= $content['type'] ?>" allowfullscreen src="<?= $iframe_url ?>"></iframe>
        </div>
        <?php }else { ?>
        <div id="player-container" audio="<?= $content['audioonly'] ?>" videoid="<?= $play ?>" class="z-depth-1 player-container <?= $content['type'] ?>-container" onclick="<?= $onclick ?>">
            <div class="play-button"><i class="material-icons play-button-inner">play_arrow</i></div>
            <img class="player-inner" id="player-holding-image" src="<?= $content['poster'] ?>"/>
        </div>
        <?php } ?>
        <div id="player-info" class="card z-depth-1">
            <div id="player-important" class="card-content">
                <div id="player-channel-title"><?= $content['channel_name'] ?></div>
                <div id="player-title" class="card-title"><?= $content['title'] ?></div>
                <div id="player-subtitle" class="italic truncate"><?= $content['nowplaying'] ?></div>
                <?php
                if($is_vod){
                    ?>
                <p id="player-date" class=""><?= nice_date($content['date']) ?></p>
                    <?php
                }
                ?>
                <div id="player-tags">
                    <?php
                    foreach($tags as $tag){
                        if(strlen($tag) > 0){
                            ?>
                    <div class="chip"><a href="./search?term=<?= strtolower($tag) ?>"><?= strtolower($tag) ?></a></div>
                        <?php
                        }
                    }
                    ?>
                </div>

                <div id="player-description"><?= $content['description'] ?></div>
            </div>
        </div>
        
        
        <?php 
        /* Comments (VOD) or schedule (Live) */
        if($play > 0 && $config['disqus_name']){ 
        ?>
        <!-- Comments section -->
        <div class="card z-depth-1">
            <div class="card-content">
                <div id="disqus_thread"></div> 
                <script class="dynamic-script">
                var disqus_config = function () { 
                    this.page.url = "<?= $config['site_root'] ?>?action=video&play=<?= $play ?>"; 
                    // Replace PAGE_URL with your page's canonical URL variable 
                    this.page.identifier = <?= $play ?>; 
                    //Replace PAGE_IDENTIFIER with your page's unique identifier variable 
                    }; 
                (function() { 
                    var d = document, 
                    s = d.createElement('script'); 
                    s.src = '//<?= $config['disqus_name'] ?>.disqus.com/embed.js'; 
                    s.setAttribute('data-timestamp', +new Date()); 
                    (d.head || d.body).appendChild(s); })(); 
                </script> 
                <noscript>Please enable JavaScript to view the <a href="https://disqus.com/?ref_noscript" rel="nofollow">comments powered by Disqus.</a></noscript>
            </div>
        </div>
        <?php }else if($play < 0 && intval($content['schedule_id'])){ 
                $api_url_s = $config['publicphp'] . "?action=schedule&request=upcoming&schedule_id=" . $content['schedule_id'] . "&before=" . (time() + (60*60*24*3));
                //echo $api_url_s;
                $schedule_data = json_decode(file_get_contents($api_url_s),true);
                if(count($schedule_data) > 0){
            ?>
        <div class="card z-depth-1">
            <div class="card-content">
                <span class="card-title">Upcoming shows</span>
                <table class="bordered">
            <?php
                
                $limit = 5;
                for($i = 0; $i <= 5; $i++){
                    $schedule_item = $schedule_data[$i];
                    $ashow = $schedule_item['show'];
            ?>
                    <tr onclick="pages.loadPage('show&id=<?= $ashow['id'] ?>')" class="pointer">
                        <td>
                            <img src="<?= ($ashow['poster_url'])? $ashow['poster_url']: $config['filler_image'] ?>" alt="" style="width:123px;" class="left z-depth-1">
                        </td>
                        <td>
                            <span class="black-text card-title"><?= $ashow['title'] ?></span>
                        </td>
                        <td>
                            <span class="grey-text truncate"><?= $schedule_item['niceTime'] ?></span>
                        </td>
                    </tr>
                <?php } ?>
                </table>
                <div style="margin-top:1rem;" onclick="pages.loadPage('schedule');" class="pointer">See full schedule</div>
            </div>
        </div>
        <?php }} ?>
        
    </div>

    <!-- Related/live videos section -->
    <div class="col s12 l4">
        <?php
            $api_url_c = $config['publicphp'] . '?action=plugin_videomanager&list';
            $channels = json_decode(file_get_contents($api_url_c),true);
        ?>
        <div id="player-playlist">
            <h5 id="live-indicator">Live Now</h5>
            <div id="channel-list">
                
                <?php foreach($channels as $channel){ ?>
                <div id="channel_pane_<?= $channel['id'] ?>" class="playlist-item z-depth-0 channel-pane" onclick="pages.loadPage('video&play=-<?= $channel['id'] ?>')">
                    <div class="row">
                        <div class="col s4 responsive-video">                                                
                            <img src="<?= $config['filler_image'] ?>" alt="Channel image" class="responsive-img left z-depth-1">                                   
                        </div>                                            
                        <div class="col s8">                                                
                            <span class="black-text card-title"><?= $channel['title'] ?></span>                                                
                            <span class="grey-text truncate"></span>                                                
                            <span class="grey-text truncate italic"></span>                                            
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        <?php

        $related = json_decode(file_get_contents($config['publicphp'] . '?action=plugin_vod&limit=7&tag=' . $relatedtag),true);

        if(count($related) > 1 && strlen($relatedtag) > 0){
            $rtitle = "More episodes";
        }else{
            $related = json_decode(file_get_contents($config['publicphp'] . '?action=plugin_vod&limit=7&list'),true);
            $rtitle = "Recent shows & videos";
        }

        ?>
             <h5><?= $rtitle ?></h5>

            <?php
            $counter = 0;
            foreach($related as $result){
                if($counter < 6 && $result['id'] != $playing){
            ?>
            <div class="hoverable z-depth-0 playlist-item" onclick="pages.loadPage('video&play=<?= $result['id'] ?>')">
                <div class="row">
                    <div class="col s4 responsive-video">
                        <img src="<?= $result['poster'] ?>" alt="" class="responsive-img left z-depth-1" />
                    </div>
                    <div class="col s8">
                        <span class="black-text card-title">
                          <?= $result['title'] ?>
                        </span>
                        <span class="grey-text truncate"><?= nice_date($result['date']) ?></span>
                    </div>

                </div>
            </div>
            <?php 
                $counter++;
                }

            } 
            ?>

        </div>
    </div>
</div>
<script class="dynamic-script">
    try{
        clearInterval(infotimer);
        infotimer = null;
    }catch(err){
        console.log(err.message);
    }
    try{
        clearInterval(channel_timer);
        channel_timer = null;
    }catch(err){
        console.log(err.message);
    }
    try{
        clearInterval(home_channel_timer);
        home_channel_timer = null;
    }catch(err){
        console.log(err.message);
    }
    
    var json_url = "<?= $api_url ?>";
    updateVideoInformation(json_url);
   
    infotimer = setInterval(function(){ updateVideoInformation(json_url); }, 10000);

    //Run JS to display channel panes
    updateChannelList();
    channel_timer = setInterval(function(){ updateChannelList(); }, 10000);
</script>
<!-- Facebook comments activator -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.6&appId=<?= $config['fb_appId'] ?>";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>

<script class="exit-script">
    try{
        clearInterval(infotimer);
        infotimer = null;
    }catch(err){
        console.log(err.message);
    }
    try{
        clearInterval(channel_timer);
        channel_timer = null;
    }catch(err){
        console.log(err.message);
    }
</script>
<script type="application/json" id="page-info">
    {
        "title" : "LCR - <?= $content['title'] ?>"
    }
</script>

      
