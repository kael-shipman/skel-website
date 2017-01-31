// init

var init = {
  global : function() {
    var controls = document.getElementsByClassName('app-control');
    for(var i = 0; i < controls.length; i++) controls[i].addEventListener('click', App.toggleMenu);

    // Email addresses
    Skel.Utils.convertEmailAddresses();

    // Load any audio controls
    var musicPlayers = document.getElementsByClassName('music-player'), audioControls;
    for (var i = 0; i < musicPlayers.length; i++) {
      if (musicPlayers[i].playerLoaded) continue;
      musicPlayers[i].playerLoaded = true;

      audioControls = musicPlayers[i].getElementsByClassName('control');
      for (var j = 0; j < audioControls.length; j++) {
        if (audioControls[j].classList.contains('audio-play')) {
          audioControls[j].addEventListener('click', (function(player) {
            return function(e) {
              player.getElementsByTagName('audio')[0].play();
              player.setAttribute('data-player-state', 'playing');
              e.preventDefault();
            }
          })(musicPlayers[i]));
        } else if (audioControls[j].classList.contains('audio-pause') || audioControls[j].classList.contains('audio-loading')) {
          audioControls[j].addEventListener('click', (function(player) {
            return function(e) {
              player.getElementsByTagName('audio')[0].pause();
              player.setAttribute('data-player-state', 'paused');
              e.preventDefault();
            }
          })(musicPlayers[i]));
        }
      }
    }



    // Load any photo carousels
    var iv, tm, cm;
    var ivs = document.getElementsByClassName("photo-carousel");
    for(var i = 0; i < ivs.length; i++) {
      if (ivs[i].imageViewer) continue;
      iv = new Skel.ImageViewer();
      ivs[i].imageViewer = iv;

      tm = new Skel.ScrollingSelectionManager(ivs[i].getElementsByClassName('thumbnails')[0]);
      iv.registerThumbnailManager(tm);

      cm = new Skel.CanvasManager(ivs[i].getElementsByClassName('photo-canvas')[0]);
      iv.registerCanvasManager(cm);

      // Load fullscreen capabilities
      cm.addEventListener('itemClick', {
        respondToEvent : function(eventName, src) {
          if (eventName == 'itemClick') {
            var fs = Skel.Utils.createFullscreenApi(cm.container);
            if (fs.fullscreenElement) fs.exitFullscreen();
            else fs.requestFullscreen();

            setTimeout(function() { cm.selectItem(cm.selectedItem); }, 50);
          }
        }
      });

      if (!Skel.ImageViewer.instances) Skel.ImageViewer.instances = [];
      Skel.ImageViewer.instances.push(iv);
    }



    // post-index pagination
    if (!window.scrollListenerAdded) {
      window.scrollListenerAdded = true;
      window.addEventListener('scroll', function(e) {
        if (!window.scrollTimeSpacer) window.scrollTimeSpacer = 0;
        if(Date.now() - window.scrollTimeSpacer > 200) {
          // If there's no post-index element on the page, don't do anything
          // (We can't totally disable this, though, cause a post-index element might appear later)
          if (document.getElementsByClassName('post-index').length == 0) {
            window.scrollTimeSpacer = Date.now()*1 + 5000*1;
            return;
          }
          window.scrollTimeSpacer = Date.now();

          var vh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
          var dh = document.scrollHeight || document.documentElement.scrollHeight;
          var scrollTop = window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop;

          if (dh - (scrollTop + vh) < 400) App.loadNextIndexPage();
        }
      });
    }
  }
}

var App = {
  onPageLoad : function() {
    var pgid = document.body.getAttribute('data-pgid');

    init.global();
    if (typeof init[pgid] == 'function') init[pgid]();
  },

  postIndex : {},

  toggleMenu : function(e) {
    var container = e.target.parentNode;
    while (!container.classList.contains('app-control') && container.tagName != 'body') container = container.parentNode;

    var menu = container.getElementsByClassName('main-menu');
    var classes;
    for(var i = 0; i < menu.length; i++) {
      classes = menu[i].classList;
      if (classes.contains('active')) {
        classes.remove('active');
        setTimeout((function(v) {
          return function() { menu[v].style.display = ''; }
        })(i), 500);
      } else {
        menu[i].style.display = 'block';
        setTimeout(function() { classes.add('active'); }, 20);
      }
    }
  },
  
  loadNextIndexPage : function() {
    if (App.postIndex.XHR) return;
    
    var query = window.location.search.substr(1);
    var q = {};

    if (query.length > 0) {
      query = query.split('&');
      for(var i = 0; i < query.length; i++) {
        query[i] = query[i].split('=');
        q[decodeURIComponent(query[i][0])] = decodeURIComponent(query[i][1]);
      }
    }

    if (!q.pg) q.pg = 1;
    q.pg++;

    query = [];
    for(var x in q) query.push(encodeURIComponent(x) + '=' + encodeURIComponent(q[x]));
    query = query.join('&');

    if (history.replaceState) history.replaceState(null, null, '?'+query);

    App.postIndex.XHR = new XMLHttpRequest();
    App.postIndex.XHR.open('GET', window.location.href, true);

    App.postIndex.XHR.setRequestHeader('Accept', 'text/html,application/json');
    App.postIndex.XHR.onreadystatechange = function() {
      if (App.postIndex.XHR.readyState == 4) {
        if (App.postIndex.XHR.status != 200) App.toggleLoader('Sorry, something went wrong getting the next index page :(. You might want to reload...');
        else {
          App.toggleLoader(false);
          var data = JSON.parse(App.postIndex.XHR.responseText);
          var index = document.getElementsByClassName('post-index')[0];
          var generator = document.createElement('div');

          for(var i = 0; i < data.elements.posts.length; i++) {
            generator.innerHTML = data.elements.posts[i];
            index.appendChild(generator.firstElementChild);
          }

          App.onPageLoad();
          if (data.elements.posts.length > 0) App.postIndex = {};
        }
      }
    }
    App.postIndex.XHR.send();

    App.toggleLoader('loading more posts...');
  },

  toggleLoader : function(str) {
    var loader = document.getElementsByClassName('loading')[0];
    if (!str) loader.classList.remove('active');
    else {
      loader.innerHTML = str;
      loader.classList.add('active');
    }
  }
}


document.addEventListener('DOMContentLoaded', function() {
  App.onPageLoad();
});








Skel.CanvasManager  = function(container, options) {
  Skel.ScrollingSelectionManager.call(this, container, options);
}
Skel.CanvasManager.prototype = Object.create(Skel.ScrollingSelectionManager.prototype);

Skel.CanvasManager.prototype.onForwardClick = function(elmt) {
  var i = 1;
  if (this.selectedItem) i = this.getIndexOf(this.selectedItem)*1 + 1*1;
  if (i >= this.items.length) return;
  this.selectItem(this.items[i]);
}

Skel.CanvasManager.prototype.onBackwardClick = function(elmt) {
  var i = 0;
  if (this.selectedItem) i = this.getIndexOf(this.selectedItem)*1 - 1*1;
  if (i < 0) return;
  this.selectItem(this.items[i]);
}



