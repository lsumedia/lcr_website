
        <?php 
            $pagename = "show";
            
             $show_id = $_GET['id'];
            $api_url = $config['publicphp'] . '?action=shows&r=show&id=' . $show_id;
            $show = json_decode(file_get_contents($api_url),true);
          
        ?>
        
        <!--
        <div id="show-cover" class="parallax-container">
            <div class="section no-pad bot" id="show-title">
                <div class="container">
                    <h3><?= $show['title'] ?></h3>
                </div>
            </div>
            <div class="parallax">
                <img src="<?= $show['poster_url'] ?>">
            </div>
        </div> -->
        
         <!-- Content section -->
        <?php
       
        
        $current_episode = $show['episodes'][0];
        ?>
        
        
            
            <!-- Player & playlist -->
            <div class="row">
                
                
                
                <?php
                if($current_episode){
                    ?>
                <div class="col s12 l8 hide-on-med-and-down">
                    <div class="video-container z-depth-1">
                        <img src="<?= $current_episode['poster'] ?>" width="100%"/>
                    </div>
                </div>
                    <?php
                }else{
                    ?>
                <div class="col s12 l8 hide-on-med-and-down">
                    <div class="video-container z-depth-1">
                        <img src="<?= $show['poster_url'] ?>" width="100%"/>
                    </div>
                </div>
                <?php
                }
                ?>
               
                <div class="col s12 l4">
                    
                    <div id="show-description" class="card z-depth-0">
                        <!-- <div class="card-image">
                            <img src="<?= $show['poster_url'] ?>" />
                        </div> -->
                        <div class="card-content">
                            <!-- <div id="player-title" class="card-title"><?= $show['title'] ?></div> -->
                            <p><?= $show['description'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php
            
            /* Academic years, from the calendar year it starts (eg. 2015 => 2015-16) */
            $years = [];
            
            foreach($show['episodes'] as $episode_d){
                //Get date of episode
                $datestring = $episode_d['date'];
                //Convert to timestamp
                $e_timestamp = strtotime($datestring);
                //Subtract 9 months (so we only see episodes after September 1st)
                //244 - DOY of September 1st on non-leap year
                $e_timestamp -= 60 * 60 * 24 * 244;
                //Convert back to a year
                $e_year = intval(date('Y', $e_timestamp));
                //echo $e_year;
                if(!in_array($e_year, $years)){
                    $years[] = $e_year;
                }
            }
            
            //Academic year to show results for
            $ac_year;

            if(isset($_GET['year'])){
                //Get selected year if one is selected
                $ac_year = $_GET['year'];
            }else{
                //Else pick [0] from years array as this will always be the latest
                $ac_year = $years[0];
            }
            /* Get available years from episodes */
            
            ?>
            <a name="episodes" id="episodes"></a>
            <div class="row show-episode-header" id="episodes-header">
                <div class="col s12 l6"><h4>Episodes</h4></div>
                <select class="col s12 l3 offset-l3" id="show-year-select">
                    <?php
                    //Add years to select menu
                    foreach($years as $year_a){
                        //Pick last two numbers of year
                        $plusone = substr(($year_a + 1),2,2);
                        $selected = ($year_a == $ac_year)? "selected" : "";
                        echo "<option $selected value=\"$year_a\">$year_a-$plusone</option>";
                    }
                    ?>
                </select>
            </div>
            <script class="dynamic-script">
                //Onchange year function
                $('#show-year-select').change(function(){
                    var val = $('#show-year-select').val();
                    pages.loadPage('show&id=<?= $show_id ?>&year=' + val);
                });
            </script>
            
            <div class="row">
                <div class="col s12">
                    <?php
                    
                    //Dates after start of selected academic year
                    $after = $ac_year . '-09-01';
                    //Dates before end of selected academic year
                    $before = ($ac_year + 1) . '-08-31';
                    //Episode list API URL
                    $api_url_e = $config['publicphp'] . '?action=plugin_vod&tag=' . $show['tag'] . "&after=$after&before=$before";
                    //Episodes array
                    $episodes = json_decode(file_get_contents($api_url_e),true);
                    foreach($episodes as $result){
                    ?>
                    <div class="col s12 m6 l3">
                        <div class="card small pointer z-depth-0">
                            <a href="javascript:void(0):" onclick="pages.loadPage('video&play=<?= $result['id'] ?>')">
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
            </div>
         
        <?php
        //If year is set => user selected a year, go to episode list
        if(isset($_GET['year'])){
        ?>
        <script class="dynamic-script">
        $(document).ready(function(){
            //Scroll to episodes section
           location.hash = '#episodes';
           <?php if(count($episodes) > 4){ ?>
            //Compensate for navbar - only if there are >4 episodes
           setTimeout(function(){ window.scrollBy(0,-50); }, 1);
           <?php } ?>
       });
        </script>
         <?php
        }
        ?>
