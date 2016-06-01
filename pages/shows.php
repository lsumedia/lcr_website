
<div class="row"><div class="col s12"><h4>Shows</h4></div></div>
<div class="row" id="show-results">
    <?php
    $publicphp = $config['publicphp'];
    $shows = json_decode(file_get_contents($publicphp . '?action=shows&r=list'),true);
    foreach($shows as $show){
         /* Only load shows with tags as others cannot have episodes */
         if($show['tag']){
             //$matching = json_decode(file_get_contents($publicphp . '?action=plugin_vod&tag=' . $show['tag']),true);
             //if(count($matching) > 0){
             $show['poster_url'] = ($show['poster_url'])? $show['poster_url'] : $config['filler_image'];
             ?>
             <div class="show-container col s12 m6 l4">
                  <div class="card medium">
                       <div class="card-image waves-effect waves-block waves-light">
                           <div class="video-container">
                           <img class="activator" src="<?= $show['poster_url'] ?>">
                           </div>
                       </div>
                      <div class="card-content">
                           <div class="card-title activator"><?= $show['title'] ?><i class="material-icons right">more_vert</i></div>
                           <!-- <p><?= count($matching)?> episode<?= (count($more['episodes']) == 1)? '' : 's' ;?></p> -->
                      </div>
                      <div class="card-action">
                          <a href="./show?id=<?= $show['id'] ?>">Browse</a>
                        </div>
                      <div class="card-reveal">
                          <span class="card-title grey-text text-darken-4"><?= $show['title'] ?><i class="material-icons right">close</i></span>
                          <p><?= $show['description'] ?></p>
                      </div>
                  </div>
              </div>
        <?php
             //}
         }
    }
    ?>
</div>
