;(function(root, $, _, undefined){
  
  //Do an ajax request.
  var GET=1, POST=2, PUT=4, DELETE=8;
  function request(){
    
    //Predefine variables.
    var method, model, data;
    
    //Handle arguments.
    switch(arguments.length){
      
      //A get request to the given model name.
      case 1:
        method = GET;
        model = arguments[0];
        data = {};
        break;
      
      //A custom request to the given model name, or a PUT request with the given data.
      case 2:
        if(_(arguments[0]).isNumber()){
          method = arguments[0];
          model = arguments[1];
          data = {};
        }else{
          method = PUT;
          model = arguments[0];
          data = arguments[1];
        }
        break;
      
      //A custom request to given model name with given data.
      case 3:
        method = arguments[0];
        model = arguments[1];
        data = arguments[2];
        break;
      
    }
    
    //Should data be processed by jQuery?
    var process = (method == GET);
    
    //Stringify our JSON?
    if(!process) data = JSON.stringify(data);
    
    //Convert method to string for use in the jQuery ajax API.
    method = (method == GET && 'GET')
          || (method == POST && 'POST')
          || (method == PUT && 'PUT')
          || (method == DELETE && 'DELETE')
          || 'GET';
    
    //Build the url
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname + '?rest=tuxion/' + model;
    
    //Do it, jQuery!
    return $.ajax({
      url: url,
      type: method,
      data: data,
      dataType: 'json',
      contentType: 'application/json',
      processData: process,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    });
    
  }
  
  //A template helper function.
  function tmpl(id){
    
    return function(){
      var tmpl;
      if(!$.domReady){
        throw "Can not generate templates before DOM is ready.";
      }else{
        tmpl = tmpl || _.template($('#'+id).html());
        return $(tmpl.apply(this, arguments));
      }
    }
    
  }
  
  //A data-id extractor helper function.
  $.fn.id = function(setter){
    
    if(setter){
      return $(this).attr('data-id', setter);
    }
    
    return parseInt( $(this).attr('data-id') , 10 );
    
  };
  
  //A helper function for getting the delta out of mouse wheel events.
  function getWheelDelta(event){
    
    if(event.wheelDelta){
      return -(event.wheelDelta/120);
    }
    
    if(event.detail){
      return (event.detail/3);
    }
    
    else return false;
    
  }
  
  //Mixin a logging functions into underscore.
  _.mixin({
    
    log: function(object){
      console.log(object);
      return object;
    },
    
    dir: function(object){
      console.dir(object);
      return object;
    }
    
  });
  
  var app;
  
  root.Tuxion = new Class(null, {
    
    /**
    * OPTIONS AND INITIATOR
    */
    options: {},
    init: function(options){
      app = this;
      this.options = _(options).defaults(this.options);
      this.Content = new this.ContentController;
      this.Sidebar = new this.SidebarController;
    },
    
    /**
    * ITEM FETCHING AND CACHING (model)
    */
    Items: {
      
      array: [],
      object: {},
      
      //Return the item with the given id.
      fetch: function(id){

        var Items = this;
        
        //Return the item if we already had it.
        if(id in Items.object){
          return ($.Deferred()).resolve(Items.object[id]).promise();
        }
        
        //Add a request to the item to the pending items.
        var req = request('item/'+id)
        
        // //Add it to the list of items when it's done.
        // .done(function(data){
          
        //   //#TODO: Items.array.push(data);
        //   Items.object[id] = data;
          
        // });
        
        //Return the pending item.
        return req;
        
      },
      
      //Return the item that comes after the given id (before it in time).
      fetchNext: function(id){
        
        var Items = this;
        
        //Find it in our cache.
        if(Items.object[id]){
          
          //Look for the item key.
          var item = Items.object[id]
            , key = Items.array.indexOf(item) + 1;
          
          //No items come after the last one.
          if(item == "last"){
            return ($.Deferred()).reject("last").promise();
          }
          
          //Found it?
          if(Items.array[key]){
            return ($.Deferred()).resolve(Items.array[key]).promise();
          }
          
        }
        
        var D = $.Deferred()
          , P = D.promise();
        
        //Fetch a new chunk of items.
        Items.fetchClosest(id).done(function(data){
          Items.fetchNext(id).done(function(item){
            D.resolve(item);
          });
        });
        
        return P;
        
      },
      
      //Return the item that comes before the item with the given id.
      fetchPrevious: function(id){
        
        var Items = this;
        
        //Find it in our cache.
        if(id in Items.object){
          
          //Look for the item key.
          var item = Items.object[id]
            , key = Items.array.indexOf(item) - 1;
          
          //No items come before the first one.
          if(item == "first"){
            return ($.Deferred()).reject("first").promise();
          }
          
          //Found it?
          if(key in Items.array){
            return ($.Deferred()).resolve(Items.array[key]).promise();
          }
          
        }
        
        var D = $.Deferred()
          , P = D.promise();
        
        //Fetch a new chunk of items.
        Items.fetchClosest(id).done(function(data){
          Items.fetchPrevious(id).done(function(item){
            D.resolve(item);
          });
        });
        
        return P;
        
      },
      
      //Return the first item.
      fetchFirst: function(){
        
        var Items = this;
        
        //Try to fetch it from cache.
        if(0 in Items.array && Items.array[0] == "first" && 1 in Items.array){
          return ($.Deferred()).resolve(Items.array[1]).promise();
        }
        
        var D = $.Deferred()
          , P = D.promise();
        
        //Fetch it from the server.
        Items.fetchClosest(0).done(function(){
          Items.fetchFirst().done(function(item){
            D.resolve(item);
          });
        });
        
        return P;
        
      },
      
      //Return the closest (amount||50) items to the given id.
      fetchClosest: function(id, amount){
        
        var data = {}
          , Items = this
          , req;
          
        amount && _(data).extend({amount:amount});
        
        //Try to fetch from cache
        if(Items.object[id]){
          
          var half = Math.floor((amount || 50) / 2)
            , item = Items.object[id]
            , idx = _(Items.array).indexOf(item);
          
          var before = Items.array.slice((idx-half < 0 ? 0 : idx-half), idx);
          var after = Items.array.slice((idx+1), (idx+half+1));
          
          req = _(($.Deferred()).resolve({before: before, item: item, after: after}).promise()).extend({
            alertFail: $.noop
          });
          
          //Was that all?
          if((before.length == half || _(before).first() == 'first')
          && (after.length == half || _(after).last() == 'last')){
            return req;
          }
          
        }
        
        //Fetch it from the server.
        req = request(GET, 'closest/'+id, data);

        //When we're done, we are going to cache them.
        req.done(function(data){

          var itemArray = [];
          
          //Add the "before"-items to the itemArray.
          _(data.before).each(function(val){  
            itemArray.push(val);
          });
          
          //Add the item to the item array.
          itemArray.push(data.item);
          
          //Add the "after"-items to the itemArray.
          _(data.after).each(function(val){  
            itemArray.push(val);
          });
          
          //Add the items to our id-based container.
          _(itemArray).each(function(val){
            
            if(_(val).isString()){
              return true;
            }
            
            Items.object[val.id] = val;
            
          });
          
          //The indexes of items in our already existing array that we are going to replace in between.
          var first = 0, last = undefined, tmp = Items.array.copy();
          
          //Detect the closest date in the future if we're not the first node.
          if(_(itemArray).first() != 'first' && _(tmp).first() != 'first'){
            
            var startDate = Date.parse( _(itemArray).first().dt_created.replace(' ', 'T') ), closest = 0;
            
            for(var i = 0; i < tmp.length; i++){
              
              if(Date.parse(tmp[i].dt_created.replace(' ', 'T')) >= startDate){
                closest = i;
                continue;
              }
              
              break;
              
            }
            
            first = closest;
            
          }
          
          //We don't need this chunk anymore.
          tmp = tmp.slice(first);
          
          //Are we dealing with the end of our items?
          if(_(itemArray).last() != 'last'){
            
            var endDate = Date.parse( _(itemArray).last().dt_created );
            
            for(var i = 0; i < tmp.length; i++){
              
              var val = tmp[i];
              
              if(Date.parse(val.dt_created) >= endDate){
                continue;
              }
              
              last = i;
              break;
              
            }
            
          }
          
          if(last === undefined){
            Items.array = Items.array.concat(itemArray);
          }else{
            Items.array.splice(first);
            Array.prototype.splice.apply(Items.array, [first, (last-first)].concat(itemArray));
          }
          
        });
        
        return req;
        
      }
      
    },
    
    /**
    * ITEM CONTROLLER
    */
    items: {},
    Item: Controller.sub({
      
      elements: {
        'more': '.read-more'
      },
      
      events: {
        'click on more': function(e){
          if(app.Content.mode !== 'list') e.preventDefault();
        }
      },
      
      data: {},
      
      templator: tmpl('item'),
      namespace: 'item',
      
      init: function(data){
        this.id = data.id;
        this.data = data;
        app.items[this.id] = this;
        this.previous(this.templator(data));
      },
      
      render: function(){
        this.view.replaceWith( this.templator(this.data) );
        return this;
      }
      
    }),
    
    /**
    * COLUMN CONTROLLER
    */
    columns: [],
    Column: Controller.sub({
      
      templator: tmpl('item-column'),
      namespace: 'column',
      
      elements: {
        'el_items': '.item'
      },
      
      init: function(){
        this.id = app.columns.length;
        app.columns.push(this);
        this.previous( this.templator({id:this.id}) );
      },
      
      //Rerender with a new template.
      render: function(){
        this.view.replaceWith( this.templator({}) );
        return this;
      },
      
      //Append an instance of Item.
      append: function(item){
        
        var Column = this;
        this.view.append(item.view);
        
        $.after(0).done(function(){
          Column.refreshElements();
          app.Content.refreshElements();
        });
        
        return this;
        
      },
      
      //Append our view to the Content.
      toContent: function(){
        
        if(app.Content.el_columns.filter(this.view).size() == 1){
          return this;
        }
        
        var lastColumn = app.Content.el_columns.last();
        this.view.appendTo(app.Content.view);
        this.view.addClass(lastColumn.size() == 0 || lastColumn.hasClass('wide') ? 'small' : 'wide');
        
        $.after(0).done(function(){
          app.Content.refreshElements();
        });
        
        return this;
        
      },
      
      //Return true if this column has too many items.
      overflowing: function(){
        if(this.el_items.size() < 2) return false;
        var lastItem = this.el_items.last();
        return (lastItem.height() + lastItem.position().top + 50) > this.view.height();
      }
      
    }),
    
    /**
    * SIDEBAR CONTROLLER
    */
    SidebarController: Controller.sub({
      
      el: '#sidebar',
      namespace: 'sidebar',
      
      elements: {
        el_menu_items: 'a.menu-item'
      },
      
      events: {
        'click on a.menu-item': 'itemClick'
      },
      
      init: function(){
      
        this.previous();
        
        var Sidebar = this;
        Sidebar.initialWidth = Sidebar.view.outerWidth();
        
        $(window)
          .on('mousewheel DOMMouseScroll', Sidebar.view, function(e){
            
            //Only in effect if scroll was used on the sidebar when it isn't allowed.
            if($(e.target).closest('#sidebar').size() == 0 || app.Sidebar.scrollAllowed())
              return;
            
            var delta = getWheelDelta(e.originalEvent);
            
            var el = Sidebar.view.find('.col');
            el.scrollTop(el.scrollTop() + (delta*50));
            
            app.Sidebar.scootOver($('#container').scrollLeft());
            
          })
        
      },
      
      scootOver: function(distance, duration){
        
        var percentage = (this.initialWidth - distance) / this.initialWidth;
        
        if(percentage < 0)
          percentage = 0;
        
        if(duration){
          this.view.animate({
            opacity: percentage,
            left: '-'+distance+'px'
          }, duration);
        }else{
          this.view.css({
            opacity: percentage,
            left: '-'+distance+'px'
          });
        }
        
        return this;
        
      },
      
      scrollAllowed: function(){
        return this.view.css('opacity') < 1;
      },
      
      itemClick: function(e){
        e.preventDefault();
        this.view.find('.col').scrollTo(this.view.find($(e.target).attr('href')), {axis:'y', duration: 300, offset: -25});
        return false;
      }
      
    }),
    
    /**
    * CONTENT CONTROLLER
    */
    ContentController: Controller.sub({
      
      el: '#content',
      namespace: 'content',
      
      elements: {
        'el_items': '.item',
        'el_columns': '.col'
      },
      
      templators: {
        portfolio: tmpl('portfolio'),
        blog: tmpl('blog')
      },
      
      mode: 'list',
      
      init: function(){
        
        var Content = this;
        
        Content.previous();
        Content.fixWidth();
        Content.fixHeight();
        
        $.after(20).done(function(){
          Content.navigate();
        });
        
        $(window)
          
          .on('mousewheel DOMMouseScroll', Content.view, function(e){
            
            //Only in effect if scroll was not used on the sidebar when it isn't allowed.
            if($(e.target).closest('#sidebar').size() > 0 && !app.Sidebar.scrollAllowed())
              return;
            
            var delta = getWheelDelta(e.originalEvent);
            
            if(Content.mode == 'list'){
              $('#container').scrollLeft($('#container').scrollLeft()+(delta*100));
              Content.renderItems(delta < 0);
            }
            
            else if(Content.mode == 'full'){
              var el = Content.view.find('.col.full');
              el.scrollTop(el.scrollTop() + (delta*50));
            }
            
            app.Sidebar.scootOver($('#container').scrollLeft());
            
          })
          
          .on('resize', _(function(){
            
            if(Content.mode == 'list'){
              Content.rerender();
            }
            
            else if(Content.mode == 'full'){
              Content.el_full.animate({width: ($(window).width() * .9)}, 100);
              $('#container').scrollTo(Content.el_full, {
                offset: {left: -($(window).width() * .05)},
                duration: 100
              });
            }
            
          }).debounce(120))
          
          .on('keyup', function(e){
            
            //Escape key to close an article.
            if(e.which == 27 && Content.mode == 'full'){
              Content.closePage();
            }
            
          })
          
          .on('hashchange', function(e){
            if(window.location.hash.length < 1) return;
            Content.navigate();
          })
        
        ;//eof: $(window)
        
      },
      
      fixHeight: function(){
        
        this.view.css('height', ($('body').innerHeight() - $('#footer').outerHeight()) + 'px');
        
      },
      
      navigate: function(){
        
        var Content = this;
        
        if(window.location.hash.length > 0){
          Content.openPage(window.location.hash.slice(1));
        }else{
          Content.renderFrom();
        }
        
      },
      
      setMode: function(mode){
        
        var Content = this;
        
        switch(mode){
          
          case 'list':
            Content.view.removeClass('disabled');
            break;
            
          case 'full':
            Content.view.addClass('disabled');
            break;
          
        }
        
        
        Content.mode = mode;
        
      },
      
      openPage: function(id){
        
        var Content = this
          , column = $('<div>', {"class":"col"})
          , wait = 0;
          
        if(Content.mode != 'full'){
        
          if(app.items[id] && app.items[id].view.is(':in-viewport')){
            
            var f, closest = app.items[id].view.closest('.col');
            
            if((closest.offset().left + (closest.width() / 2)) > ($(window).width() / 2)){
              f = 'before';
            }else{
              f = 'after';
            }
            
            closest[f](column);
            wait = 250;
            
          }
          
          else{
            
            Content.empty();
            (new app.Column).toContent();
            Content.view.append(column);
            
          }
            
          $.after(0).done(function(){
            
            Content.fixWidth(($(window).width() * .95));
            column.addClass('full').css('width', '0').animate({width: ($(window).width() * .9)}, 500);
            $('#container').scrollTo(column, {axis: 'x', offset: {left: -($(window).width() * .05)}, duration: 500});
            app.Sidebar.scootOver($(window).width() * .95, 500);
            
          });
          
          Content.el_full = column;
          
        }
        
        $.after(0).done(function(){
          
          $.after(wait).done(function(){
            Content.setMode('full');
          });
          
          app.Items.fetch(id).done(function(data){
            
            var tmpl = Content.templators[data.typeName || 'blog'];
            Content.el_full.html(tmpl(data)).find('.inner').css('width', ($(window).width() * .9));
            
          });
          
        });
        
        Content.openPageID = id;
        
      },
      
      closePage: function(){
        
        var Content = this;
        
        if(!Content.el_full){
          return;
        }
        
        var to = ($('#container').scrollLeft())-($(window).width()/2);
        to = (to < 0 ? 0 : to);
        $('#container').scrollTo(to, {axis: 'x', duration:500});
        app.Sidebar.scootOver($(window).width() > Content.view.width() - Content.el_full.width() ? 0 : to, 500);
        
        Content.fixWidth(0,500);
        Content.el_full.animate({'width': 0}, 500).queue(function(){
         
          Content.el_full.remove();
          delete Content.el_full;
          Content.setMode('list');
          
          if(Content.el_items.size() > 0){
            Content.renderItems();
          }
          
          else{
            //#TODO: Content.renderFrom(Content.openPageID);
            Content.renderFrom();
          }
          
          window.location.hash = '#';
          
          delete Content.openPageID;
          
        });
        
      },
      
      renderFrom: function(id){
        
        var Content = this;
        Content.empty();
        
        app.Items[(id ? 'fetch' : 'fetchFirst')](id).done(function(data){
          Content.append(new app.Item(data)).done(function(data){
            Content.renderItems();
          });
        });
        
        return this;
        
      },
      
      rerender: function(){
        
        var Content = this;
        
        Content.fixHeight();
        
        if(Content.rendering){
          
          if(Content.rendering.rerender){
            return this;
          }
          
          Content.rendering.rerender = true;
          Content.rendering.done(function(){
            Content.rerender();
          });
          
          return this;
          
        }
        
        var id = Content.el_items.filter(':in-viewport').first().id();
        Content.renderFrom( id );
        return this;
        
      },
      
      rendering: false,
      renderItems: function(left){
        
        if(this.rendering){
          return this;
        }
        
        var D = $.Deferred()
          , P = D.promise();
          
        this.rendering = P;
        
        var Content = this, next = function(){
          Content[left ? "appendPrevious" : "appendNext"]().done(function(){
            next();
          }).fail(function(){
            Content.rendering = false;
            D.resolve();
          })
        };
        next();
        
        return this;
        
      },
      
      appendNext: function(){
        
        var Content = this
          , lastId = Content.el_items.last().id()
          , D = $.Deferred()
          , P = D.promise();
          
        app.Items.fetchNext(lastId).done(function(data){
          
          if(data == 'last'){
            D.reject();
            return;
          }
          
          var item = new app.Item(data);
          
          Content.append(item).done(function(){
            
            if(!item.view.is(':in-viewport')){
              item.view.remove();
              delete app.items[item.id];
              Content.refreshElements();
              D.reject();
            }
            
            D.resolve();
            
          });
          
        });
        
        return P;
        
      },
      
      appendPrevious: function(){
        return $.Deferred().reject().promise();
      },
      
      append: function(item){
        
        var Content = this
          , D = $.Deferred()
          , P = D.promise();
        
        $.after(0).done(function(){Content
          
          var lastColumn
            , key = Content.el_columns.last().id();
            
          //Create a new column?
          if(key > -1 && app.columns[key]){
            lastColumn = app.columns[key];
          }else{
            lastColumn = (new app.Column);
          }
          
          //Append the item.
          lastColumn.append(item);
          
          //Wait for rendering to finish.
          $.after(0).done(function(){
            
            //Was it too much?
            if(lastColumn.overflowing()){
              lastColumn = (new app.Column);
            }
            
            //Append the item to a brand new column.
            lastColumn.append(item);
            lastColumn.toContent();
            
            //Wait for rendering to finish.
            $.after(0).done(function(){
              
              item.view.addClass('solid');
              
              Content.fixWidth();
              D.resolve();
              
            });
            
          });
          
        });
        
        return P;
        
      },
      
      prepend: function(item){
        
      },
      
      empty: function(){
        
        this.view.empty();
        app.items = {};
        app.columns = [];
        this.view.width($(window).width() + 1000);
        return this;
        
      },
      
      fixWidth: function(extraWidth, duration){
        
        var colWidth = _(this.el_columns.map(function(){
          return $(this).width();
        }).get()).reduce(function(a,b){return a+b;}, 0);
        
        this.view.animate({width: (colWidth + (extraWidth||20))}, duration||0);
        
      }
      
    })
    
  });
  
})(this, jQuery, _);
