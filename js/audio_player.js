
function iframeUrl(video_id){
    return publicphp + '?action=plugin_vod&iframe=' + video_id;
}

function isLive(duration){
    //Switch is in case any browsers are idiots
    switch(duration){
        case Number.POSITIVE_INFINITY:
            return true;
            break;
        default:
            return false;
    }
}

function niceTime(totalSeconds){
    if(isLive(totalSeconds)){
        return 'LIVE';
    }else if(isNaN(totalSeconds)){
        return '00:00';
    }else{
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds - (hours * 3600)) / 60);
        var seconds = Math.floor(totalSeconds - (hours * 3600) - (minutes * 60));
        minutes = (minutes < 10)? '0' + minutes : minutes;
        seconds = (seconds < 10)? '0' + seconds : seconds;
        if(hours > 0){
            return hours + ':' + minutes  + ':' + seconds;
        }else{
            return minutes  + ':' + seconds;
        }
    }
}

function audioPlayer(){
    
    publicphp = config['publicphp'];
    //DOM Element containing controls + playback info
    this.element = document.createElement('div');
    this.element.id = 'player-container';
    
    //Video elements
    this.IframeElement = document.createElement('div');
    this.IframeElement.id = 'iframe-container';
    
    //Load HTML bits and pieces
    this.element.innerHTML = '<div class="row">\
    <div id="player_left" class="col s12 l2">\
        <div id="player_controls">\
            <i class="material-icons" id="rewind_button">fast_rewind</i>\
            <a class="play_button" id="play_button_container"><i class="material-icons" id="play_button">play_arrow</i></a>\
            <i class="material-icons" id="forward_button">fast_forward</i>\
        </div>\
    </div>\
    <div id="player_centre" class="col s12 l6">\
        <div id="player_live" style="display:none;"><div class="circle"></div>Live</div>\
        <div class="row" id="player_time">\
            <span id="player_elapsed" class="col s1">00:00</span>\
            <div id="player_seekbar" class="col s10">\
                <input id="player_seekrange" type="range" min="0" max="1000" value="0" step="1">\
                <style id="webkit_progress">input[type=range]::-webkit-slider-runnable-track { background: -webkit-linear-gradient(left, #EF5350 0%, #EF5350 0%, white 0%); }</style>\
            </div>\
            <span id="player_duration" class="col s1">00:00</span>\
        </div>\
    </div>\
    <div id="player_right" class="col s6 l4">\
        <div class="row">\
            <div id="player_poster" class="col s3">\
            </div>\
            <div id="current_info" class="col s9">\
                <span id="player_title" class="truncate"><span id="player_buffering" style="display:none;"><img src="res/loading_ring.svg"></span></span>\
                <span id="player_nowplaying" class="truncate"></span>\
            </div>\
        </div>\
    </div></div>';
    
    this.element.className = 'playerbar';
    
    this.IframeElement.className = 'iframe_container';
    
    this.IframeElement.innerHTML = '\
    <div id="iframe_backdrop"></div>\
        <div id="iframe_player_holder">\
            <iframe id="iframe_player" allowfullscreen></iframe>\
        </div>\
        <div id="iframe_info">\
            <div id="iframe_title"></div>\
            <div id="iframe_description"></div>\
        </div>\
        ';
    
    //Mini player code
    this.miniPlayer = document.createElement('div');
    this.miniPlayer.id = 'player-mini';
    this.miniPlayer.className = 'responsive-video';

    this.miniTitle = document.createElement('div');
    this.miniTitle.id = 'player-mini-bar';
    this.miniTitle.innerHTML = "Playing";
    this.miniPlayer.appendChild(this.miniTitle);
    
    document.body.appendChild(this.element);
    document.body.appendChild(this.IframeElement);
    
    //Pamphlet Video ID of current content
    this.contentId = null;
    //Array of video IDs containing playlist content
    this.upcomingContent = [];
    //Boolean value of whether to load new content automatically
    this.autoPlay = false;
    //Bool of whether to play video on load immediately
    this.playOnLoad = true;
    //HTML5 Audio Element
    this.AudioElement = new Audio();
    //IFrame Elements
    this.IframeBackdrop = document.getElementById('iframe_backdrop');
    this.IframeInfo = document.getElementById('iframe_info');
    this.IframeHolder = document.getElementById('iframe_player_holder');
    this.IframePlayer = document.getElementById('iframe_player');
    this.IframeTitle = document.getElementById('iframe_title');
    this.IframeDescription = document.getElementById('iframe_description');
    //iFrame transition locked when true
    this.IframeLocked = false;
    
    this.playingVideo = false;
    
    
    this.timer = setInterval(function(){ self.refreshContentInfo(); }, 10000);
    
    //Current playing array
    this.info;
    //DOM sections
    //Poster element
    this.posterElement = document.getElementById('player_poster');
    //Track title
    this.title = document.getElementById('player_title');
    //Current song
    this.nowplaying = document.getElementById('player_nowplaying');
    
    //Buttons
    this.playBtn = document.getElementById('play_button');
    this.rrBtn = document.getElementById('rewind_button');
    this.ffBtn = document.getElementById('forward_button');
    
    //Seekbar
    this.seekBar = document.getElementById('player_seekrange');
    this.liveLab = document.getElementById('player_live');
    
    //Time labels
    this.timeLab = document.getElementById('player_time');
    this.elapsedLab = document.getElementById('player_elapsed');
    this.durationLab = document.getElementById('player_duration');
    this.bufferLab = document.getElementById('player_buffering');
    
    //Reference to self for event listener usage
    var self = this;
    
    /* Dynamic vars */
    //Whether user is currently seeking
    this.seeking = false;
    //Whether current content is a live stream
    this.live = false;
    
    /* DISPLAY PLAYER */
    /* Now done inside load function
    this.element.style.display = "block";
    setTimeout(function(){ self.element.style.opacity = 1; }, 100); */
    
    /* MAIN FUNCTIONS */
    
    //Loads in a new Pamphlet video based on the video ID
    this.load = function(contentId, forceAudio){
        this.contentId = contentId; 
        if(this.contentId > 0){
            var request_url =  publicphp + "?action=plugin_vod&id=" + this.contentId;
        }else{
            var request_url =  publicphp + "?action=plugin_videomanager&id=" + Math.abs(this.contentId);
        }
        //Stop audio player
        this.pause();
        //Break Iframe
        this.IframePlayer.src = ''; 
        //Clear description
        this.IframeTitle.innerHTML = '';
        this.IframeDescription.innerHTML = '';
        //this.refreshContentInfo();
        var self = this;
        $.ajax({
                url: request_url,
                type : 'GET',
                contentType: 'application/json',
                success: function(data){
                    var info = JSON.parse(data);
                    self.info = info;
                    
                    var audioOnly = true;
                    for(n in info['sources']){
                        var mtype = info['sources'][n]['type'];
                        if(mtype.toLowerCase().indexOf('audio') == -1){
                            audioOnly = false;
                        }
                    }
                    
                    if(info['type'] == 'audio' || forceAudio || audioOnly){
                        self.hideIframe();
                        //Load as HTML5 Audio
                        var source = info['sources'][0]['src'];
                        self.AudioElement.src = source;
                        self.AudioElement.load();
                        if(self.playOnLoad == true){
                            self.play();
                        }
                        self.show();
                        this.playingVideo = false;
                    }else{
                        //Load as iFrame
                        var itemURL = iframeUrl(contentId);
                        
                        if(self.showIframe() == true){
                            self.IframePlayer.src = itemURL + '&autoplay=true';
                            self.IframeHolder.className = info['type'];
                        }
                        self.hide();
                        this.playingVideo = true;
                    }
                    
                    self.setContentInfo(info);
                    
                    
                }
            });
    }
   //Pull and set content info
    this.refreshContentInfo = function(){
        //Locally scoped copy of contentId
        var currentRequestId = this.contentId;
        if(this.contentId > 0){
            var request_url =  publicphp + "?action=plugin_vod&id=" + this.contentId;
        }else{
            var request_url =  publicphp + "?action=plugin_videomanager&id=" + Math.abs(this.contentId);
        }
        var $this = this;
        $.ajax({
                url: request_url,
                type : 'GET',
                contentType: 'application/json',
                success: function(data){
                    var self = $this; //Reference to player object
                    var info = JSON.parse(data);
                    self.info = info;
                    //Check if info is still valid
                    if(this.contentId == currentRequestId){
                        self.setContentInfo(self.info);
                    }
                }
            });
    }
    //Set content info based on video object
    this.setContentInfo = function(info){
        self.title.innerHTML = info['title'];      
        var f_onclick =  function(){ pages.loadPage('video&play=' + self.contentId); }
        self.title.onclick = f_onclick;
        self.posterElement.onclick = f_onclick;
        self.nowplaying.onclick = f_onclick;

        self.IframeTitle.innerHTML = info['title'];
        if(info['nowplaying']){
            self.nowplaying.innerHTML = info['nowplaying'];
        }else{
           self.nowplaying.innerHTML = '';
        }
        self.IframeDescription.innerHTML = info['description'];
        self.posterElement.style.backgroundImage = "url('" + info['poster'] + "')";
        console.log('Data refreshed');
        //Mini player data
        self.miniPlayer.style.backgroundImage = "url('" + info['poster'] + "')";
    }
    
    this.replaceInner = function(player_element, id, url){
        this.hide();
        this.contentId = id;
        player_element.innerHTML = '<iframe class="player-inner" allowfullscreen src="' + url + '"></iframe>';
    }
    
    this.loadMiniPlayer = function(elmini){
        elmini.onclick = null;
        elmini.innerHTML = '';
        elmini.appendChild(self.miniPlayer);
    }
    
    this.togglePlayPause = function(){
        if(this.paused() == true){
            this.play();
        }else{
            this.pause();
        }
    }
    
    this.paused = function(){
        return this.AudioElement.paused;
    }
    
    this.play = function(){
        this.AudioElement.play();
        this.playBtn.innerHTML = 'pause';
    }
    
    this.pause = function(){
        this.AudioElement.pause();
        this.playBtn.innerHTML = 'play_arrow';
    }
    
    this.rewind = function(){
        this.AudioElement.currentTime = 0;
    }
    
    this.ff = function(){
        if(this.live == true){
            //Reload player to get to live stream
            this.AudioElement.load();
            this.AudioElement.play();
        }else{
            //Fast-forward by 10%
            var tenpc = this.AudioElement.duration / 10;
            this.AudioElement.currentTime += tenpc;
        }
    }
    
    this.hide = function(){
        this.pause();
        this.element.style.opacity = '';
        setTimeout(function(){ self.element.style.display = ''}, 1000)
    }
    
    this.show = function(){
        this.element.style.display = 'block';
        setTimeout(function(){ self.element.style.opacity = 1; }, 10)
    }
    
    this.hideIframe = function(){
        if(this.IframeLocked == false){
            //Lock iframe
            this.IframeLocked = true;
            this.IframeBackdrop.style.opacity = 0;
            this.IframeInfo.style.opacity = 0;
            this.IframePlayer.src = '';
            this.IframeHolder.style.display = 'none';
            document.body.style.paddingRight = '0px';
            document.body.style.overflow = 'visible';
            setTimeout(function(){ 
                self.IframeInfo.style.display = 'none';
                self.IframeBackdrop.style.display = 'none'; 
                self.IframeLocked = false;
            }, 1000);
        }
    }
    
    this.showIframe = function(){
        if(this.IframeLocked == false){
            this.IframeLocked = true;
            this.IframeBackdrop.style.display = 'block';
            this.IframeInfo.style.display = 'block';
            this.IframeHolder.style.display = 'block';
            document.body.style.overflow = 'hidden';
            document.body.style.paddingRight = '16px';
            setTimeout(function(){  
                self.IframeInfo.style.opacity = 1;
                self.IframeBackdrop.style.opacity = 1;
                self.IframeLocked = false;
            }, 10);
            return true;
        }else{
            console.log('Cannot load IFrame, currently locked');
            return false;
        }
    }
    
    this.updateSeekInfo = function(){
        if(isLive(self.AudioElement.duration)){
            self.updateWebkitProgress(0);
            self.seekBar.style.display = "none";
            self.liveLab.style.display = "block";
            self.timeLab.style.display = "none";
            self.live = true;
        }else{
            var elapsed = self.AudioElement.currentTime;
            var duration = self.AudioElement.duration;
            var permille = (elapsed / duration) * 1000;
            if(self.seeking == false){
                self.seekBar.value = permille;
                self.seekBar.style.display = "block";
            }
            self.updateWebkitProgress(permille / 10);
            if(isFinite(duration) == false){
                /* If length = NaN set seekbar to 0 
                 * for when content is loading */
                self.seekBar.value = 0;
                self.updateWebkitProgress(0);
                self.seekBar.style.display = "block";
            }
            self.liveLab.style.display = "none";
            
            self.timeLab.style.display = "block";
            self.elapsedLab.innerHTML = niceTime(elapsed);
            self.durationLab.innerHTML = niceTime(duration);
            self.live = false;
        }
    }
    
    
    /* Button event listeners */
    
    this.playBtn.addEventListener("click", function(){ self.togglePlayPause(); } );
    this.rrBtn.addEventListener("click", function(){ self.rewind(); } );
    this.ffBtn.addEventListener("click", function(){ self.ff(); });
    
    /* Audio Object Event listeners */
    
    this.AudioElement.addEventListener('pause', function(){
       //Mobile function - change pause buttons when player is paused by browser
       self.pause(); 
    });
    
    this.AudioElement.addEventListener('playing', function(){
       //Mobile function - change pause buttons when player resumes
       self.bufferLab.style.display = 'none'; 
       self.play(); 
    });
    
    this.AudioElement.addEventListener('waiting', function(){
       //Mobile function - change pause buttons when player is paused by browser
       self.bufferLab.style.display = 'inline'; 
    });
    
    this.AudioElement.addEventListener('timeupdate', function(){
        self.updateSeekInfo();
    });
    
    this.AudioElement.addEventListener('canplay', function(){
        self.updateSeekInfo();
    });
    
    
    this.seekBar.addEventListener('change', function(){
        var permille = self.seekBar.value;
        var seconds = (permille/1000) * self.AudioElement.duration;
        self.AudioElement.currentTime = seconds;
        self.updateWebkitProgress(permille/10);
    });
    
    this.seekBar.addEventListener('mousedown', function(){
       self.seeking = true;
    });
    
    this.seekBar.addEventListener('mouseup', function(){
       self.seeking = false;
    });
    
    document.body.onkeyup = function(e){
        if(e.keyCode == 32){
            self.togglePlayPause();
        }
    }
    
    /* Video related listeners */
    
    this.IframeBackdrop.addEventListener('click', function(){
        self.hideIframe();
    });
    
    /* Non-vital functions */
    this.webkit_progress_style = document.getElementById('webkit_progress');
    
    this.updateWebkitProgress = function(percent){
        //Compensate for offset
        percent = 0.5 + percent * 0.99;
        this.webkit_progress_style.innerHTML = "input[type=range]::-webkit-slider-runnable-track { background: -webkit-linear-gradient(left, #EF5350 0%, #EF5350 " + percent + "%, white " + (percent + 0.001) + "%); }";
    }
    
}
