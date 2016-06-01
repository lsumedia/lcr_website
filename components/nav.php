<!-- Dropdown for More button -->
<ul id="more-dropdown" class="dropdown-content red-text">
    <li><a href="./blog">Blog</a></li>
  <li><a href="./about">About</a></li>
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
                <a href="javascript:void(0);" class="brand-logo" onclick="pages.loadPage('home');">
                    <img src="res/lcr_white.png" alt="LSUTV Logo" id="tv-logo" class="left" />
                    <!-- <span>Loughborough Campus Radio</span> -->
                </a>
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
                <li><a href="./shows">Shows</a></li>
                <li class="divider"></li>
                 <li><a href="./search?term=entertainment">Entertainment</a></li>
                <li><a href="./search?term=news">News</a></li>
                <li><a href="./search?term=music">Music</a></li>
                <li><a href="./search?term=sport">Sport</a></li>
                <li class="divider"></li>
                <li><a href="./blog">Blog</a></li>
                <li><a href="./about">About</a></li>
          </ul>
        </div>
<div class="progress" id="loading-bar">
            <div class="indeterminate"></div>
        </div>
    </nav>
    
</div>