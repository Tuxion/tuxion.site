var com_cms = (function(TxComCms){

  var //private properties
    defaults = {
    }

  //public properties
  $.extend(TxComCms, {
    options: null
  });

  //public init(o)
  TxComCms.init = function(o){

    //create options
    this.options = new Options(o);

    return this;

  }

  //private Options Options(o)
  function Options(o){
    $.extend(this, defaults, o);
    return this;
  }

  return TxComCms;

})({});

com_cms.init();
