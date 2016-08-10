<div class="row">
    <div class="col s12">
        <div class="z-depth-1 pointer header-image white-text" style="background-image:url('<?= $config['cover_image'] ?>');" onclick="player.load(-1)">
            <h4 class='card-title'>Listen Live</h4>
        </div>
    </div>
</div>

<div class="row">
    <div class="col s12">
        <!-- Short site description panel -->
        <h4><?= $config['site_welcome'] ?></h4>
        <p><?= $config['site_description'] ?></p>
    </div>
</div>

<!-- Video List Section -->

<!-- Live List -->

<?php
//API url for finding active channels
//TODO - move to client side
$api_url_l = $config['publicphp'] . '?action=plugin_videomanager&list';
//Get array of active channels
$channels = json_decode(file_get_contents($api_url_l),true);

if(count($channels) > 0){
?>

<div class="row">
    <div class="col s12">
        <h4>Live Now</h4>
    </div>
</div>

<div class="row" id="live-channels">
     <div id="channel_pane_1" class="col s12 m6 l3 channel-pane" style="display:none"></div>
     <div id="channel_pane_2" class="col s12 m6 l3 channel-pane" style="display:none"></div>
     <div id="channel_pane_3" class="col s12 m6 l3 channel-pane" style="display:none"></div>
</div>
    <?php
}
    ?>

<!-- End Live List -->
<!-- Recent List -->

<?php
//Limit of recent videos to pull
$limit = 8;
//API url for recent videos
$api_url_r = $config['publicphp'] . "?action=plugin_vod&list&limit=$limit";
//Get array of recent videos
$recent = json_decode(file_get_contents($api_url_r),true);

?>

<div class="row">
    <div class="col s12">
            <h4>Recent videos</h4>
    </div>
</div>

<div class="row" id="search-results">
    <?php
        //Print each video cell
        foreach($recent as $index => $result){

            ?>
        <div class="col s12 m6 l3">
            <div class="card small pointer z-depth-0">
                <a href="javascript:void(0);" onclick="pages.loadPage('video&play=<?= $result['id'] ?>')">
                <div class="card-image z-depth-1">
                    <div class="video-container" style="background-image:url('<?= $result['poster'] ?>');"></div>
                </div>
                <div class="card-content">
                    <div class="search-result-title black-text"><?= $result['title'] ?></div>
                </div>
                <!-- <div class="card-title"><?= $result['title'] ?></div> -->
                </a>
            </div>
        </div>
        <?php
        }
    ?>
</div>

<script class="dynamic-script">
    //Run JS to display channel panes
    updateChannelPanes();
    var channel_timer = setInterval(function(){ updateChannelPanes(); }, 10000);
</script>