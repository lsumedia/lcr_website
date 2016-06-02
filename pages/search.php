<?php
$term = $_GET['term'];
?>
    <div class="row">
       <div class="column s12">
            <nav class="nav-wrapper theme-blue">
               <div class="input-field">
                    <input id="search-bar" onchange="function(){ var term = $('#search-bar').val(); pages.loadPage('search&term=' + term); }" class="active" name="term" type="search" required value="<?= (trim($term) == '')? '' : $term ?>" placeholder="Search" >
                    <label for="search-bar"><i class="material-icons">search</i></label>
               </div>
            </nav>
       </div>
    </div>

    <div class="row" id="search-results">
        <?php
        //If term is not blank or whitespace
        if(strlen($term) > 0 && !(trim($term) == '')){
            $api_url_s = $config['publicphp'] . '?action=plugin_vod&search=' . urlencode($term) . '&limit=100';
            $results = json_decode(file_get_contents($api_url_s),1);

            foreach($results as $index => $result){
                ?>
            <div class="col s12 m6 l3">
                <div class="card small pointer z-depth-0">
                    <a onclick="pages.loadPage('video&play=<?= $result['id'] ?>');">
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
        }

        ?>
    </div>
<script class="dynamic-script">
    $('#search-bar').change(function(){
        var term = $('#search-bar').val();
        pages.loadPage('search&term=' + term);
    });
    $("#search-bar").focus();
</script>
