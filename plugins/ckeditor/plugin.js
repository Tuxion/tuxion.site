 var tx_editor = (function(TxEditor){

  var //private properties
    defaults = {
      selector: '.editor',
      config: {
        skin: 'kama',
        path_ckfinder: '/plugins/ckfinder/',
        path_ckfinder_uploads: '/files/explorer/'
      }
    }

  //public properties
  $.extend(TxEditor, {
    options: null
  });

  //public init(o)
  TxEditor.init = function(o){
      
    //create options
    this.options = options = new Options(o);

    //initialize the editor
    $(this.options.selector).ckeditor(this.options.config, function(){
      CKFinder.setupCKEditor(this, options.config.path_ckfinder);
    });

    return this;
    
  }

  //public destroy()
  TxEditor.destroy = function(){
    $(this.options.selector).ckeditorGet().destroy();
  }

  //private Options Options(o)
  function Options(o){
    $.extend(this, defaults, o);
    return this;
  }

  return TxEditor;

})(tx_editor||{});