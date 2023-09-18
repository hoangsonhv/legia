$(function () {
  'use strict'

  // Process event
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
    $('[name="money_total"]').val(data.original);
  });
});

function removeUpload(_this) {
  $(_this).parents('.block-file').remove();
}
