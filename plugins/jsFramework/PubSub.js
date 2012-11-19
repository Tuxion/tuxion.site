;(function($, exports){
  
  var PubSub = {
    pubsub: $({}),
    subscribe: function(){
      this.pubsub.bind.apply(this.pubsub, arguments);
      return this;
    },
    unsubscribe: function(){
      this.pubsub.unbind.apply(this.pubsub, arguments);
      return this;
    },
    publish: function(){
      arguments = $.makeArray(arguments);
      this.pubsub.trigger.call(this.pubsub, arguments.shift(), arguments);
      return this;
    }
  };
  
  exports.PubSub = PubSub;
  
})(jQuery, window);
