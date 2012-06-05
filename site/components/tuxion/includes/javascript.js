var Tuxion = (function($, root, undefined){
  
  //Define a template helper function.
  function tmpl(id){
    
    return function(){
      var tmpl;
      if(!$.domReady){
        throw "Can not generate templates before DOM is ready.";
      }else{
        tmpl = tmpl || _.template($('#'+id).html());
        return tmpl.apply(this, arguments);
      }
    }
    
  }
  
  //Resize a box and maintain aspect ratio.
  function resize(old, New)
  {
    
    //Fill in missing variables.
    old.height = old.height || -1;
    old.width = old.width || -1;
    
    //Resize by height.
    if(New.width == undefined){
      New.height = New.height || 0;
      var ratio = old.height / New.height;
      New.width = old.width*ratio;
    }
    
    //Resize by width.
    else if(New.height == undefined){
      New.width = New.width || 0;
      var ratio = old.width / New.width;
      New.height = old.height*ratio;
    }
    
    //Return the new width and height.
    return New;
    
  }
  
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
    
    //Stringify out JSON?
    if(!process) data = JSON.stringify(data);
    
    //Convert method to string for use in the jQuery ajax API.
    method = (method == GET && 'GET')
          || (method == POST && 'POST')
          || (method == PUT && 'PUT')
          || (method == DELETE && 'DELETE')
          || 'GET';
    
    //Build the url
    var url = window.location.host + window.location.pathname + '?rest=tuxion/' + model;
    
    //Do it, jQuery!
    return _($.ajax({
      url: url,
      type: method,
      data: data,
      dataType: 'json',
      contentType: 'application/json',
      processData: process,
      headers: {
        'X-Requested-With': 'XMLHttpRequest'
      }
    }))
    
    //Extend with simple fail alert.
    .extend({
      alertFail: function(){
        return this.fail(function(xhr){
          if(xhr.status<400){
            alert('Invalid server response.');
          }else{
            alert('ERROR '+xhr.status+":\n"+xhr.statusText);
          }
        });
      }
    });
    
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
  
  //Return the app interface.
  return new Class(null, {
    
    options: {
      
    },
    
    init: function(options){
      this.options = _(options).defaults(this.options);
      this.createContent();
      this.bindHash();
      this.bindScroll();
      if(window.location.hash.length > 0){
        this.openPage(window.location.hash);
      }else{
        this.Content.renderFirst();
      }
    },
    
    //Bind hashchange events.
    bindHash: function(){
      
      var app = this;
      
      $(window).hashchange(function(){
        
      });
      
    },
    
    bindScroll: function(){
      
      var app = this;
      
      $(window).scroll(function(e){
        // console.log('scrolled');
        app.Content.showVisible();
      });
      
    },
    
    createColumn: tmpl('item-column'),
    
    Item: Controller.sub({
      
      elements: {
        'more': '.read-more'
      },
      
      events: {
        'click on more': function(){}
      },
      
      data: {},
      
      templator: tmpl('item'),
      namespace: 'item',
      
      init: function(data){
        this.data = data;
        this.previous($('<li>', {
          'class': 'item-container'
        }));
      },
      
      render: function(){
        this.view.empty().append( this.templator(this.data) );
        return this;
      }
      
    }),
    
    createContent: function(){ var app = this; app.Content = new Controller('#content', 'items', {
      
      elements: {
        'el_items': '.item',
        'el_columns': '.items'
      },
      
      items: {},
      itemArray: [],
      itemFilters: [
        function(item){
          return item !== 'first';
        },
        function(item){
          return item !== 'last';
        }
      ],
      
      fetchItem: function(id){
        
        var app = this;
        
        //Return the item if we already had it.
        if(app.items[id]){
          return _(($.Deferred()).resolve(app.items[id]).promise()).extend({
            alertFail: $.noop
          });
        }
        
        //Add a request to the item to the pending items.
        var req = request('item/'+id)
        
        //Add it to the list of items when it's done.
        .done(function(data){
          app.itemArray.push(data);
          app.items[id] = data;
        });
              
        //Return the pending item.
        return req;
        
      },
      
      //Fetches the 50 latest items, or the 50 items closest to the given item id.
      fetchClosest: function(id, amount){
        
        var data = {}
          , app = this
          , req;
          
        amount && _(data).extend({amount:amount});
        
        //Try to fetch from cache
        if(app.items[id]){
          
          var half = Math.floor((amount || 50) / 2)
            , item = app.items[id]
            , idx = _(app.itemArray).indexOf(item);
          
          var before = app.itemArray.slice((idx-half < 0 ? 0 : idx-half), idx);
          var after = app.itemArray.slice((idx+1), (idx+half+1));
          
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
            
            app.items[val.id] = val;
            
          });
          
          //The indexes of items in our already existing array that we are going to replace in between.
          var first = 0, last = undefined, this_itemArray = app.itemArray;
                  
          //Detect the closest date in the future if we're not the first node.
          if(_(itemArray).first() != 'first'){
            
            var startDate = Date.parse( _(itemArray).first().dt_created ), closest = 0;
            
            for(var i = 0; i < this_itemArray.length; i++){
              
              var val = this_itemArray[i];
              
              if(Date.parse(val.dt_created) >= startDate){
                closest = i;
                continue;
              }
              
              break;
              
            }
            
            first = closest;
            
          }
          
          //We don't need this chunk anymore.
          this_itemArray = this_itemArray.slice(first);
          
          //Are we dealing with the end of our items?
          if(_(itemArray).last() != 'last'){
            
            var endDate = Date.parse( _(itemArray).last().dt_created );
            
            for(var i = 0; i < this_itemArray.length; i++){
              
              var val = this_itemArray[i];
              
              if(Date.parse(val.dt_created) >= endDate){
                continue;
              }
              
              last = i;
              break;
              
            }
            
          }
          
          if(last === undefined){
            app.itemArray.splice(first);
            app.itemArray = app.itemArray.concat(itemArray);
          }else{
            Array.prototype.splice.apply(app.itemArray, [first, (last-first)].concat(itemArray));
          }
          
        });
        
        return req;
        
      },
      
      renderFirst: function(){
        
        var content = this;
        
        content.fetchClosest(0).done(function(data){
          content.append(
            _(data.after)
            .chain()
            .values()
            .unshift(data.item)
            .value()
          );
          $.after(0).done(content.proxy(content.showVisible));
        });
        
        
      },
      
      renderNext: function(){
        
        var content = this, lastId = parseInt( content.el_items.filter(':last-child').attr('data-id') , 10 );
        
        if(_(lastId).isNaN()){
          throw "Could not identify the last item on the screen.";
        }
        
        content.fetchClosest(lastId).done(function(data){
          content.append(data.after);
        });
        
        return this;
        
      },
      
      append: function(items){
        
        var content = this
          , items = content.filterItems(items)
          , appendNext = _(function(){
            
            var last_column = content.el_columns.last();
            
            if(last_column.size() == 0){
              last_column = $(app.createColumn({})).appendTo(content.view).matches();
            }
            
            var item = new app.Item(items.shift())
              , ul = last_column.find('ul');
            
            item.render().view.appendTo(ul);
            
            $.after(0).done(function(){
              
              console.log(ul.height(), last_column.height());
              
              if(ul.height() > last_column.height()){
                item.view.appendTo($(app.createColumn({})).appendTo(content.view).find('ul'));
              }
              
              content.refreshElements();
              
              if(items.length > 0){
                appendNext();
              }
            
            });
            
          }).debounce(0);
          
          appendNext();
          
          $.after(0).done(content.proxy(content.showVisible));
        
          // item = new app.Item(item);
          // item.render();
          // append.appendChild(item.view[0]);
          // 
        
        return this;
        
      },
      
      showVisible: function(){
        
        var filtered = this.el_items.filter(':in-viewport').filter(function(){
          return $(this).css('visibility') == 'hidden';
        });
        
        filtered.each(function(i){
          var item = this;
          $.after(150*i).done(function(){
            $(item).css('visibility', 'visible').fadeIn(300);
          });
        });
        
        this.el_items.not(':in-viewport').not(filtered).css('visibility', 'hidden');
        
      },
      
      filterItems: function(items, extraFilters){
        
        var app = this
          , filtered = []
          , filters = app.itemFilters.concat(extraFilters || []);
        
        iterateItems:
        for(var i = 0; i < items.length; i++){
          
          var item = items[i];
        
          for(var j = 0; j < filters.length; j++){
            
            var filter = filters[j];
            
            if(!filter(item)){
              continue iterateItems;
            }
            
          }
          
          filtered.push(item);
        
        }
        
        return filtered;
        
      }
      
    })}
    
  });
  
})(jQuery, window);
