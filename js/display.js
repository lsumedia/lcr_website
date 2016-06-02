

function updateVideoInformation(json_url){
     $.ajax({
                url: json_url,
                type : 'GET',
                contentType: 'application/json',
                success: function(data){
                    var title = document.getElementById('player-title');
                    var subtitle = document.getElementById('player-subtitle');
                    var desc = document.getElementById('player-description'); 
                    var etags = document.getElementById('player-tags');
                    var poster = document.getElementById('player-holding-image');
                    var info = JSON.parse(data);
                    title.innerHTML = info['title'];
                    if(info['show']){
                        var show = info['show'];
                        title.innerHTML += '&nbsp;<i class="material-icons pointer" onclick="pages.loadPage(\'show&id=' + show['id'] + '\');">video_library</i>';
                        console.log(show['title']);
                    }
                    if(info['nowplaying']){
                        subtitle.innerHTML = info['nowplaying'];
                    }else{
                        subtitle.innerHTML = "";
                    }
                    desc.innerHTML = info['description'];
                    try{
                        poster.src = info['poster'];
                    }catch(err){
                        console.log(err.message);
                    }
                    etags.innerHTML = '';
                    var tagstring = info['tags'];
                    var taga = tagstring.split(' ');
                    for(n in taga){
                        if(taga[n].toString().length > 0){
                            var newtag = document.createElement('div');
                            newtag.className = "chip";
                            newtag.innerHTML = '<a href="./search?term=' + taga[n] + '">' + taga[n] + '</a>';
                            etags.appendChild(newtag);
                        }
                    }
                    document.tile = 'LCR - ' + info['title'];
                    console.log('Data refreshed');
                }
            });
}

function updateChannelPanes(){
    /* For homepage channel panes */
    json_url = config['publicphp'] + '?action=plugin_videomanager&list'; 
    $.ajax({
                url: json_url,
                type : 'GET',
                contentType: 'application/json',
                success: function(data){
                    data = JSON.parse(data);
                    var channelpanec = document.getElementById("live-channels");
                      
                    for(n in data){
                        
                        var channel = data[n];
                        var cid = channel['id'];
                        var json_url_2 = config['publicphp'] + '?action=plugin_videomanager&id=' + cid;
                        
                        $.ajax({
                            url: json_url_2,
                            type : 'GET',
                            contentType: 'application/json',
                            success: function(data){
                                
                                var info = JSON.parse(data);
                                
                                var pane_id = 'channel_pane_' + info['channelID'];
                                
                                var container = document.createElement('div');
                                
                                container.id = pane_id;
                                container.className = "col s12 m6 l3 channel-pane";
                                
                                var html = '<div class="card small pointer z-depth-0">\
                                        <a href="./video?play=-' + info['channelID'] + '">\
                                        <div class="card-image z-depth-1">\
                                            <div class="video-container" style="background-image:url(\'' + info['poster'] + '\');"></div>\
                                        </div>\
                                        <div class="card-content">\
                                            <div class="search-result-title truncate black-text">' + info['channel_name'] + '</div>\
                                            <div class="search-result-subtitle truncate black-text">' + info['title'] + '</div>';
                                       if(info['nowplaying']){
                                           html += '<div class="channel-nowplaying truncate black-text italic">' + info['nowplaying'] + '</div>';
                                       }
                                       
                                html += '</div>\
                                        </a>\
                                    </div>';
                                
                                if(document.getElementById(pane_id)){
                                    var pane = document.getElementById(pane_id);
                                    pane.innerHTML = html;
                                }else{
                                    container.innerHTML = html;
                                    channelpanec.appendChild(container);
                                }
                            }
                        });
                        
                    }
                    
                    var panes = document.getElementsByClassName('channel-pane');
                    /* Set/unset pane visibility based on status */
                    for(p in panes){
                        var pane = panes[p];
                        var isLive = false;
                        var id = pane.id;
                        
                        for(n in data){
                            var checkId = 'channel_pane_' + data[n]['id'];
                            if(checkId == id){
                                isLive = true;
                            }
                        }
                        
                        try{
                            if(isLive){
                                pane.style.display = "block";
                            }else{
                                pane.style.display = "none";
                            }
                        }catch(err){
                        }
                    }
                }
            });
}

function updateChannelList(){
    /* For homepage channel panes */
    var json_url_c = config['publicphp'] + '?action=plugin_videomanager&list'; 
    $.ajax({
                url: json_url_c,
                type : 'GET',
                contentType: 'application/json',
                success: function(data){
                    data = JSON.parse(data);
                    var channelpanec = document.getElementById("channel-list");
                    var livelabel = document.getElementById("live-indicator");
                    
                    livelabel.style.display = 'none';
                    for(n in data){
                        livelabel.style.display = 'block';
                        var channel = data[n];
                        var cid = channel['id'];
                        var json_url_2 = config['publicphp'] + '?action=plugin_videomanager&id=' + cid;
                        
                        $.ajax({
                            url: json_url_2,
                            type : 'GET',
                            contentType: 'application/json',
                            success: function(data){
                                
                                var info = JSON.parse(data);
                                
                                var pane_id = 'channel_pane_' + info['channelID'];
                                
                                var container = document.createElement('div');
                                
                                container.id = pane_id;
                                container.className = "playlist-item z-depth-0 channel-pane";
                                container.setAttribute('onclick', "pages.loadPage('video&play=-" + info['channelID'] + "');");
                                
                                var html = '<div class="row">\
                                            <div class="col s4 responsive-video">\
                                                <img src="' + info['poster'] + '" alt="Channel image" class="responsive-img left z-depth-1"/>\
                                            </div>\
                                            <div class="col s8">\
                                                <span class="black-text card-title">' + info['channel_name'] + '</span>\
                                                <span class="grey-text truncate">' + info['title'] + '</span>\
                                                <span class="grey-text truncate italic">' + ((info['nowplaying'])? info['nowplaying']: '') +'</span>\
                                            </div>';
                                
                                if(document.getElementById(pane_id)){
                                    var pane = document.getElementById(pane_id);
                                    pane.innerHTML = html;
                                }else{
                                    container.innerHTML = html;
                                    channelpanec.appendChild(container);
                                }
                            }
                        });
                        
                    }
                    
                    var panes = document.getElementsByClassName('channel-pane');
                    /* Set/unset pane visibility based on status */
                    for(p in panes){
                        var pane = panes[p];
                        var isLive = false;
                        var id = pane.id;
                        
                        for(n in data){
                            var checkId = 'channel_pane_' + data[n]['id'];
                            if(checkId == id){
                                isLive = true;
                            }
                        }
                        
                        try{
                            if(isLive){
                                pane.style.display = "block";
                            }else{
                                pane.style.display = "none";
                            }
                        }catch(err){
                        }
                        
                    }
                }
            });
}
