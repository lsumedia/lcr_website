<?php
$no_show_message = "No shows scheduled";

$api_url_c = $config['publicphp'] . "?action=plugin_videomanager&list";
$channels = json_decode(file_get_contents($api_url_c),true);
?>
<div class="row">
    <div class="col s12">
      <ul class="tabs z-depth-1">
        <?php foreach($channels as $channel){ 
            ?>
            <li class="tab col s3">
                <a href="#channel<?= $channel['id'] ?>">
                    <?= $channel['title'] ?>
                </a>
            </li>
        <?php } ?>
      </ul>
    </div>
    <div class="col s12">
        <?php foreach($channels as $channel){ 
            echo "<div id=\"channel{$channel['id']}\">";
                   if($channel['schedule_id'] != null){
               ?>
                   
                       <?php
                       
                    $api_url_s = $config['publicphp'] . "?action=schedule&request=upcoming&schedule_id=" . $channel['schedule_id'];
                    //echo $api_url_s;
                    $schedule_data = json_decode(file_get_contents($api_url_s),true);
                    if(count($schedule_data) > 0){
                        
                        //Schedule has events
                        echo '<ul class="collapsible" data-collapsible="accordion">';
                        foreach($schedule_data as $event){ 
                            $show = $event['show'];
                            $details = $event['event'];
                        
                        ?>
                        <li>
                            <div class="collapsible-header" style="border-bottom: 1px solid <?= $show['theme_colour'] ?>">
                                <?= ($details['title'])? $details['title'] : $show['title'] ?>
                                <span class="right"><?= $event['niceTime'] ?></span>
                            </div>
                            <div class="collapsible-body" >
                                <span><?= ($details['description'])? $details['description'] : $show['description'] ?></span>
                            </div>
                        </li>    
                        <?php }
                        echo '</ul>';     
                        
                        
                    }else{
                       echo "<div class=\"card-panel theme-blue white-text\">{$no_show_message}</div>";
                    }
                       
                       ?>
        <?php }else{
                echo "<div class=\"card-panel theme-blue white-text\">{$no_show_message}</div>";
            }
        echo "</div>";  
        //ENDFOREACH
        } ?>
    </div>
  </div>
        
<script class="dynamic-script">
    $('ul.tabs').tabs();
    $('.collapsible').collapsible({
      accordion : true
    });
    <?php
    if(isset($_GET['id']) && $id = $_GET['id']){
        echo "$('ul.tabs').tabs('select_tab', 'channel{$id}');";
    }
    ?>
</script>