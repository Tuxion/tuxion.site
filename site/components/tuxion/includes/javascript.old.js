var Tuxion = (function($, root, undefined){
  
return new Class(null, {
    
    options: {
      smallView: 800,
      phoneView: 550
    },
    
    smallView: false,
    phoneView: false,
    
    init: function(options){
      this.options = _(options).defaults(this.options);
      this.smallView = $(window).width() <= this.options.smallView;
      this.phoneView = $(window).width() <= this.options.phoneView;
      this.createContent();
      this.bindHash();
      this.bindScroll();
      this.bindResize();
      if(window.location.hash.length > 0){
        this.openPage(window.location.hash);
      }else{
        this.Content.renderFirst();
      }
    },
    
    createContent: function(){ var app = this; app.Content = new Controller('#content', 'items', {
      
      elements: {
        'el_items': '.item',
        'el_columns': '.col'
      },
      
      init: function(){
        this.fixContainer();
      },
      
      fixContainer: function(){
        this.view.width($(window).width()+530);
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
      
      renderFirst: function(id){
        
        var content = this;
        content.view.empty();
        content.refreshElements();
        
        content.fetchClosest(id || 0).done(function(data){
          
          content.append(
            _(data.after)
            .chain()
            .values()
            .unshift(data.item)
            .value()
          )
          
          .done(content.proxy(content.showVisible));
          
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
          , D = $.Deferred()
          , items = content.filterItems(items)
          , appendNext = _(function(){
            
            var lastColumn = content.el_columns.last();
            
            if(lastColumn.size() == 0){
              lastColumn = $(app.createColumn({})).addClass(app.phoneView ? 'fluid' : 'small').appendTo(content.view);
            }
            
            var item = new app.Item(items.shift());
            item.view.appendTo(lastColumn);
            
            $.after(0).done(function(){
              
              if(!app.phoneView){
              
                var max = item.view.height() + item.view.position().top + (50);
                
                if(max > lastColumn.height()){
                  var newColumn = $(app.createColumn({}));
                  newColumn.addClass(lastColumn.hasClass('small') && app.smallView == false ? 'wide' : 'small');
                  item.view.appendTo(newColumn.appendTo(content.view));
                }
                
              }
              
              content.refreshElements();
              
              if(items.length > 0){
                appendNext();
              }else{
                $.after(0).done(function(){
                  D.resolve();
                });
              }
            
            });
            
          }).debounce(0);
          
        appendNext();
        return D;
        
      },
      
      showVisible: function(){
        
        var filtered = this.el_items.filter(':in-viewport').filter(function(){
          return $(this).css('visibility') == 'hidden';
        });
        
        filtered.each(function(i){
          var item = this;
          $.after(150*i).done(function(){
            $(item).css('visibility', 'visible').css('opacity', .1).animate({opacity: 1}, 300);
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
