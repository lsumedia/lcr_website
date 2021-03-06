<!-- Dropdown for More button -->
<ul id="more-dropdown" class="dropdown-content red-text">
    <li><a href='javascript:void(0);' onclick="pages.loadPage('blog');">Blog</a></li>
  <li><a href='javascript:void(0);' onclick="pages.loadPage('about');">About</a></li>
  <li><a href="https://github.com/lsumedia/lcr_website" target="_blank">GitHub</a></li>
  <li><a href="//media.lsu.co.uk" target="_blank">LSU Media</a></li>
</ul>
<!-- Dropdown for Shows button -->
<ul id="cosec-dropdown" class="dropdown-content red-text">
    <li><a href="./search?term=features">Features</a></li>
    <li><a href="./search?term=entertainment">Entertainment</a></li>
    <li><a href="./search?term=news">News</a></li>
    <li><a href="./search?term=music">Music</a></li>
    <li><a href="./search?term=sport">Sport</a></li>
</ul>
<div class="navbar-fixed">
    <nav>
        <!-- Desktop nav bar -->
        <div class="nav-wrapper" id="main-nav">
           <div class="container">
                <div class="brand-logo"  class="left">
                    <img src="res/lcr_white.png" class="nav-logo pointer" onclick="pages.loadPage('home');" alt="LCR Logo" id="lcr-logo" />
                    <!-- <i class="material-icons pointer" id="section-drop-down" onclick="toggleSectionSelect();">arrow_drop_down</i>
                    
                    <div id="section-select" >
                        <div id="section-select-inner" class="z-depth-1">
                            <div class="center-align">
                                <img src="res/media_master.png" class="section-icon-media" />
                            </div>

                            <div class="center-align">
                                <img src="res/Label.png" class="section-icon"/>
                                <img src="res/LCR.png" class="section-icon"/>
                            </div>
                            <div class="center-align">
                                <img src="res/Lens.png" class="section-icon"/>
                                <img src="res/LSUTV.png" class="section-icon"/>
                            </div>
                        </div>
                    </div> -->
                    
                </div>
               <div class="other-logos" class="left">
                   
               </div>
                <a class="hide-on-large-only button-collapse" data-activates="mobile-nav">
                    <i class="material-icons">menu</i>
                </a>
                <ul class="right hide-on-med-and-down">
                    <?php if($pagename != "search"){  ?>
                    <li><a href="javascript:void(0);" onclick="pages.loadPage('search');"><i class="material-icons left">search</i>Search</a></li>   
                    <?php } ?>
                    <!-- <li><a href="#!">Live</a></li> -->
                    <li><a href='javascript:void(0);' onclick="pages.loadPage('schedule');">Schedule</a></li>
                    <li><a href='javascript:void(0);' onclick="pages.loadPage('shows');">Shows</a></li>
                    <!-- Dropdown Trigger -->
                    <li><a class="dropdown-button" href="#!" data-activates="more-dropdown">More<i class="material-icons right">arrow_drop_down</i></a></li>
                </ul>
            </div>
            <ul class="side-nav" id="mobile-nav">
                <li><a href=".">Home</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('search');">Search</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('shows');">Shows</a></li>
                <li class="divider"></li>
                 <li><a href="javascript:void(0);" onclick="pages.loadPage('search&term=entertainment');">Entertainment</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('search&term=news');">News</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('search&term=music');">Music</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('search&term=sport');">Sport</a></li>
                <li class="divider"></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('blog');">Blog</a></li>
                <li><a href="javascript:void(0);" onclick="pages.loadPage('about');">About</a></li>
          </ul>
        </div>
<div class="progress" id="loading-bar">
            <div class="indeterminate"></div>
        </div>
    </nav>
    
</div>

<!-- UI Scripts -->
<script>
    $(document).ready(function(){
        $('select').material_select();
        $(".dropdown-button").dropdown();
        $(".button-collapse").sideNav();
        $('.slider').slider({full_width: true});
        $('.parallax').parallax();
        //Disable scroll on space bar
        window.onkeydown = function(e) { 
            return !(e.keyCode == 32);
        };
    });
</script>