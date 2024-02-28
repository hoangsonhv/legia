$(function () {
  'use strict'

  // Init
  $('[data-toggle="tooltip"]').tooltip();
  
  // Process event
  $('.btn_payment_search').click(function() {
    $.ajax({
      method: "POST",
      url: $(this).attr('data-url'),
      data: { keyword_co: $(this).parents('.input-group:first').find('[name=payment_search]').val() }
    })
    .done(function( data ) {
      var eleCoId = $("[name=payment_id]");
      eleCoId.empty();
      if (!jQuery.isEmptyObject(data)) {
        $.each(data, function( index, value ) {
          eleCoId.append('<option value="'+index+'">'+value+'</option>');
        });
      }
    });
  });

  $('[name=payment_id]').change(function(e) {
    if (!parseInt($(this).val())) {
      $(this).parents('.card-body:first').find('.block-co').removeClass('d-none');
    } else {
      $(this).parents('.card-body:first').find('.block-co').addClass('d-none');
    }
  });

  $('.btn_co_search').click(function() {
    $.ajax({
      method: "POST",
      url: $(this).attr('data-url'),
      data: { keyword_co: $(this).parents('.input-group:first').find('[name=co_search]').val() }
    })
    .done(function( data ) {
      var eleCoId = $("[name=co_id]");
      eleCoId.empty();
      if (!jQuery.isEmptyObject(data)) {
        $.each(data, function( index, value ) {
          eleCoId.append('<option value="'+index+'">'+value+'</option>');
        });
      }
    });
  });

  $('[name=payment_id]').trigger( "change" );

  $('#add-upload').click(function() {
    var html = '<div class="input-group block-file">'
      + '<div class="custom-file">'
        + '<input type="file" name="accompanying_document[]" class="custom-file-input" multiple />'
        + '<label class="custom-file-label">Chọn file</label>'
      + '</div>'
      + '<div class="input-group-append">'
        + '<span class="input-group-text btn btn-danger" onclick="removeUpload(this)">Xoá upload</span>'
      + '</div></div>';
    $(this).before(html);
    bsCustomFileInput.init();
  });

  $('[name="tmp_money_total"]').keyup(function(){
    var data = formatCurrent(this.value);
    $(this).val(data.format);
    // $('[name="money_total"]').val(data.original);
  });
});

function removeUpload(_this) {
  $(_this).parents('.block-file').remove();
}
