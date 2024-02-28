$(function () {
  'use strict'
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  $(document)
    .ajaxStart(function () {
      $('#loading-all').addClass('show');
    })
    .ajaxStop(function () {
      $('#loading-all').removeClass('show');
    });
});

function randomString(length) {
  var result           = '';
  var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
  var charactersLength = characters.length;
  for ( var i = 0; i < length; i++ ) {
    result += characters.charAt(Math.floor(Math.random() * charactersLength));
  }
  return result;
}

function formatCurrent(number) {
  var original = parseFloat(number.replace(/[^0-9.]/g, '')).toFixed(0);
  var res = {'original': original, 'format': original};
  
  if (number.includes('.')) {
    var integerPart = original.split('.')[0];
    res.format = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  } else if (original.length > 3) {
    res.format = original.replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }

  return res;
}

function checkFile(file) {
  var path      = file.path;
  var extension = path.substr( (path.lastIndexOf('.') +1) );
  switch(extension) {
    case 'jpg':
    case 'png':
    case 'gif':
    case 'jpeg':
    case 'bmp':
      return '<p>&#45;&#160;'+file.name+'</p><a href="'+domain+path+'" target="_blank"><img height="70" src="'+domain+path+'" /></a>';
    default:
      return '<p style="color: blue;">&#45;&#160;<a href="'+domain+path+'" target="_blank">'+file.name+'</a></p>';
  }
}
