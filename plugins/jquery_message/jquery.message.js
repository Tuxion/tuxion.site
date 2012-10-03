  
  /** jquery.message
  *
  */
  
  ;(function($){

    type = function(o){ //reliable
      return o===null && "null"
          || o===undefined && "undefined"
          || ({}).toString.call(o).slice(8,-1).toLowerCase();
    };
  
    var message, timeout;
    
    message = function(m){
      if(type(m) == 'string'){
        return $('<div class="tx-message-wrapper">'+m+'</div>');
      }else{
        if($(m).hasClass('tx-message-wrapper')){
          return $(m);
        }else{
          return $('<div class="tx-message-wrapper">').append(m.clone().show());
        }
      }
    }
    
    message.show = function(m, c){
      if(c!==null) m.addClass(c);
      (($('.tx-message, #tx_message').size() == 1) ? $('.tx-message, #tx_message').eq(0) : $('body')).prepend(m);
      m.hide().fadeIn(500);
    }
    
    message.hide = function(cb){
      $('*').unbind('keyup');
      clearTimeout(timeout);
      $('.tx-message-wrapper').stop(true, true).fadeOut(100, function(){
        $(this).remove();
        if(type(cb) == 'function') cb();
      });
    }
    
    message.alert = $.alert = function(m, cb){
      m = message(m);
      message.show(m, 'tx-message-alert');
      timeout = setTimeout(function(){
        $('*').bind('keyup', function(e){
          if(e.which == 27){
            $(document).unbind('click');
            message.hide(cb);
          }
        });
        $(document).bind('click', function(e){
          $(document).unbind('click');
          message.hide(cb);
        });
      }, 1000);
      return m;
    }
    
    message.notice = $.notice = function(m, cb){
      var hide = function(t){
        timeout = setTimeout(function(){
          message.hide(cb);
        }, t);
      }
      m = message(m);
      message.show(m, 'tx-message-notice');
      $('*').bind('keyup', function(e){
        if(e.which == 27 || e.which == 32){message.hide(cb);}
      });
      m.bind('click', function(e){
        message.hide(cb);
      });
      m.bind('mouseenter', function(){
        clearTimeout(timeout)
      });
      m.bind('mouseleave', function(){
        hide(700);
      });
      hide(5000);
      return m;
    }
    
    message.confirm = $.confirm = function(m, cbt, cbf){
      m = message(m);
      message.show(m, 'tx-message-confirm');
      m.append('<form><input type="button" value="no" class="tx-message-cancel" /><input type="submit" value="yes" class="tx-message-submit" /></form>');
      m.find('.tx-message-submit').focus();
      m.find('form').bind('submit', function(e){
        message.hide(cbt);
        return false;
      }).focus();
      m.find('.tx-message-cancel').bind('click', function(){
        message.hide(cbf);
        return false;
      });
      $('*').bind('keyup', function(e){
        if(e.which == 27){message.hide(cbf);}
      });
      return m;
    }
    
    message.prompt = $.prompt = function(m, mixed, cbo){
      if((type(m) == 'string' && type(mixed) == 'string'))
      {
        m = message(m);
        message.show(m, 'tx-message-prompt');
        m.append('<form><input type="text" name="prompt" value="'+mixed+'"><input type="submit" value="ok" /><input type="button" value="cancel" class="tx-message-cancel" /></form>');
        m.find('input[name=prompt]').focus().select();
        m.find('form').bind('submit', function(){
          message.hide();
          if(type(cbo)=='function')
            cbo($(this).find('input[name=prompt]').val());
          return false;
        });
      }
      
      else if(!$(m).is('form') && $(m).has('form').size()==0)
      {
        return message.notice(m, mixed);
      }
      
      else
      {
        m = message(m);
        message.show(m, 'tx-message-prompt');
        m.find(':input').eq(0).focus().select();
        if(m.find(':submit').size() == 0){
          m.find('form').append('<input type="submit" value="ok" />')
        }
        m.find('form').bind('submit', function(){
          if(type(mixed) == 'function'){
            var data = {};
            $(this).find(':input').filter(function(){return ($(this).attr('type')=='radio' ? ($(this).is(':checked')) : true)}).each(function(){
              var key = $(this).attr('name'), val = $(this).val();
              data[key] = val;
            });
            $(this).find(':checkbox').each(function(){
              var key = $(this).attr('name'), val = $(this).val();
              if($(this).is(':checked')){
                data[key] = val;
              }else{
                data[key] = false;
              }
            });
            message.hide(function(){mixed(data)});
            return false;
          }
        });
      }

      m.find('.tx-message-cancel').bind('click', function(e){
        message.hide();
      });
      $('*').bind('keyup', function(e){
        if(e.which == 27){message.hide();}
      });
      return m;
    }
    
    $.fn.alert = function(cb){return message.alert(this, cb)}
    $.fn.notice = function(cb){return message.notice(this, cb)}
    $.fn.prompt = function(cb){return message.prompt(this, cb)}

  })(jQuery);
