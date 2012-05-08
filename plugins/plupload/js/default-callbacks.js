$(function(){
  
  window.plupload_default_init = function(up, ids, params)
  {
  
    //Do nothing
  
  }
  
  window.plupload_default_error = function(up, ids, err)
  {
    
    if(typeof err.file == 'undefined')
    {
      
      alert('Plupload error: ['+err.code+'] '+err.message);
      return;
      
    }
    
    //Set the upload progress to failed.
    $('#' + err.file.id + " b")
      .html("Failed");
    
    //Add an error that will slide up after 5 seconds.
    $("<div class=\"error\">[" + err.code + "] " + err.message + (err.file ? " (File: " + err.file.name + ")" : "") + "</div>")
      .appendTo('#'+ids.filelist)
      .delay(5000)
      .slideUp('fast', function(){
        $(this).remove();
      });
    
    //Slide up the file row after 5 seconds.
    $('#' + err.file.id)
      .delay(5000)
      .slideUp('fast', function(){
        $(this).remove();
        up.refresh();
      });
    
  }
  
  window.plupload_default_upload_progress = function(up, ids, file)
  {
  
    //Set the progress.
    $('#' + file.id + " b").html(file.percent + "%");
  
  }
  
  window.plupload_default_file_uploaded = function(up, ids, file)
  {
    
    //Set the progress.
    $('#' + file.id + " b").html("100%");
    
    //Slide the file row up.
    $('#' + file.id)
      .delay(1500)
      .slideUp('fast', function(){
        $(this).remove();
        up.refresh();
      });
    
  }
  
  window.plupload_default_server_file_id_report = function(up, ids, file_id)
  {
    
    //Do nothing
    
  }
  
  window.plupload_default_files_added = function(up, ids, files, filelist)
  {
    
    //For all the files added, create a row in the file list
    $.each(files, function(i, file) {
      var size = '';
      if(typeof file.size != 'undefined')
        size = '(' + plupload.formatSize(file.size) + ')';
      
      $('#'+ids.filelist).append(
        '<div id="' + file.id + '" class="file">' +
        file.name + ' <b></b> <i>' + size + '</i>' +
      '</div>');
    });
    
  }

});
