/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


function dynamicPages(element_id, player){
    
    //DOM element representing the main section
    this.element = document.getElementById(element_id);
    //audioPlayer object used to play media
    this.player = player;
    
    var loading_bar = document.getElementById('loading-bar');
    //Self reference
    var self = this;
    
    //Load page with action string
    this.loadPageByActionName = function(action){
        self.element.innerHTML = action;
    }
    
    //Load page base function
    this.loadPage = function(action, history_mode){
        var page_url = 'ajax.php?action=' + action;
        loading_bar.style.display = 'block';
        console.log('Loading page ' + page_url);
        $.ajax({
                url: page_url,
                type : 'GET',
                success: function(data){
                    self.element.innerHTML = data;
                    self.updateWindowHistory(action, history_mode);
                    window.scrollTo(0,0);
                    loading_bar.style.display = 'none';
                    self.enableDynamicContent();
                }
            });
    }
    
    
    this.enableDynamicContent = function(){
        $('select').material_select();
        $(".dropdown-button").dropdown();
        $('.slider').slider({full_width: true});
        $('.parallax').parallax();
        //Load dynamic scripts
        var scripts = document.getElementsByClassName('dynamic-script');
        for(n in scripts){
            eval(scripts[n].innerHTML);
        }
        console.log('Dynamic content reenabled');
    }
    
    this.updateWindowHistory = function(action, history_mode){
        switch(history_mode){
            case 'back':
                break;
            default:
                var code = '?action=' + action;
                window.history.pushState('','',code);
                break;
        }
    }
    
    /**
     * Get the action code from the URL
     * @returns string
     */
    this.getWindowAction = function(){
        var string = window.location.href;
        var full_array = string.split('/');
        var end = full_array[full_array.length - 1];
        
        var end_bits = end.split('=');
        end_bits[0] = '';
        var variables = end_bits.join('=').substr(1);
        
        return variables;
    }
}
