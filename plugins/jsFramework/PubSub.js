;(function($, exports){
  
  var PubSub = {
    pubsub:   $({}),
    subscribe: function(){
      this.pubsub.on.apply(this.pubsub, arguments);
    },
    unsubscribe: function(){
      this.pubsub.off.apply(this.pubsub, arguments);
    },
    publish: function(){
      this.pubsub.trigger.apply(this.pubsub, arguments);
    }
  };
  
  exports.PubSub = PubSub;
  
})(jQuery, window);