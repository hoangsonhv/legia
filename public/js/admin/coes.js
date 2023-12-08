$(function () {
  'use strict'

  var totalMoney = 0;

  $('#ngay_bao_gia').datetimepicker({
    format: 'YYYY-MM-DD',
    icons: { time: 'far fa-clock' }
  });

  // Process event
  $('#import-offer').click(function() {
    var eleModel = '#modal-import-offer';
    $(eleModel + ' .form-group .custom-file input').val('');
    $(eleModel + ' .form-group .custom-file .custom-file-label').text('');
    $(eleModel).modal('show');
  });

  $('#load-product').click(function() {
    var formData = new FormData();
    var uploadFiles = document.getElementById('file-import-offer').files;
    if (!uploadFiles.length) {
      alert('Vui lòng chọn file.');
      return false;
    }
    formData.append('file', uploadFiles[0]);
    $.ajax({
      method: "POST",
      url: $(this).attr('data-url'),
      data: formData,
      dataType: 'json',
      contentType: false,
      processData: false
    })
    .done(function( data ) {
      if (data.success) {
        $(".warehouse .offer-price").html(data.data);
        $(".warehouse .check-warehouse").html(data.content_more);
        if (data.data_more) {
          $.each(data.data_more, function(index, value) {
            $(".warehouse .more-info [name='"+index+"']").val(value);
          });
        }
        sumTotal(".warehouse .table tbody");
      } else {
        alert('Vui lòng kiểm tra lại Danh mục hàng hoá!');
      }
      $('#modal-import-offer').modal('hide');
    });
  });
  $('#co-form').on('submit', function(e) {
    var eleForm = $(this);
    if (eleForm.attr('isdone')) {
      return true;
    } else {
      e.preventDefault();
    }
    var formData = eleForm.serialize();
    $.ajax({
      method: "POST",
      url: $("[name=url-get-data-warehouses]").val(),
      data: formData
    })
    .done(function( data ) {
      if (data.success) {
        eleForm.attr('isDone', true);
        eleForm.submit();
      } else {
        alert('Vui lòng kiểm tra lại Danh mục hàng hoá!');
        $('html, body').animate({
          scrollTop: $('#block-offer-price').offset().top
        }, 1000);
      }
    });
  });
  $('.add-upload-file').click(function() {
    var html = '<div class="input-group block-file">'
      + '<div class="custom-file">'
        + '<input type="file" name="' + $(this).attr('type-document') + '[]" class="custom-file-input" multiple />'
        + '<label class="custom-file-label">Chọn file</label>'
      + '</div>'
      + '<div class="input-group-append">'
        + '<span class="input-group-text btn btn-danger" onclick="removeUpload(this)">Xoá upload</span>'
      + '</div></div>';
    $(this).before(html);
    bsCustomFileInput.init();
  });
});

function calPaymentPer(_this, field) {
  let per = $(_this).val();
  let moneyTotal = totalMoney;
  let format = formatCurrent(Math.round(((per * moneyTotal)/100)).toString());
  $("[name='tmp[amount_money][" +field+ "]']").val(format['format']);
  $("[name='thanh_toan[amount_money][" +field+ "]']").val(format['original']);
}

function getNumberFormat(_this) {
  var number = formatCurrent($(_this).val());
  $(_this).val(number['format']);
  $(_this).parent().find('.data-origin').val(number['original']);
}

function updateGiaiDoanThanhToan(moneyTotal) {
  let fields = ['truoc_khi_lam_hang', 'truoc_khi_giao_hang', 'ngay_khi_giao_hang', 'sau_khi_giao_hang_va_cttt'];
  fields.forEach(e => {
    let per = $("[name='thanh_toan[percent][" +e+ "]']").val();
    let format = 0;
    if (moneyTotal > 0) {
      format = formatCurrent(Math.round(((per * moneyTotal)/100)).toString())
    }
    $("[name='tmp[amount_money][" +e+ "]']").val(format['format']);
    $("[name='thanh_toan[amount_money][" +e+ "]']").val(format['original']);
  })
}

function caclTotalMoney(_this) {
  var number = $(_this).val();
  var price = $(_this).parents('tr:first').find('td.price').attr('data-price');
  var total = formatCurrent((number * price).toString());
  $(_this).parents('tr:first').find('td.total').text(total['format']);
  $(_this).attr('value', number);
  if ($(_this).parents('.warehouse:first').length) {
    sumTotal('.warehouse .table tbody');
  }
}

function sumTotal(eleForm) {
  var total = 0;
  if($('.warehouse .table.data-product-co').length) {
    $('.warehouse .table.data-product-co td.total').each(function() {
      var format = formatCurrent($(this).text());
      if (format['original']) {
        total += parseInt(format['original']);
      }
    });
  }
  else {
    $(eleForm + ' td.total').each(function() {
      var format = formatCurrent($(this).text());
      if (format['original']) {
        total += parseInt(format['original']);
      }
    });
  }
  if (total) {
    var vat = (total * 10) / 100;
    totalMoney = total + vat;
    $('.price_total input').val(total);
    $('.price_total b').text(formatCurrent(total.toString())['format']);
    $('.vat input').val(vat);
    $('.vat b').text(formatCurrent(vat.toString())['format']);
    $('.money_total b').text(formatCurrent((total + vat).toString())['format']);
  } else {
    $('.price_total input').val(0);
    $('.price_total b').text(0);
    $('.vat input').val(0);
    $('.vat b').text(0);
    $('.money_total b').text(0);
  }
  updateGiaiDoanThanhToan(totalMoney);
}

function deteleItem(_this) {
  $(_this).parents('tr:first').remove();
  // Load sum total
  sumTotal('.warehouse .table tbody');
  var classList = $('.warehouse .table tbody tr');
  // Load sequence
  for (var i = 0; i < classList.length; i++) {
    classList.eq(i).find('td.sequence').text(i + 1);
  }
}

function removeUpload(_this) {
  $(_this).parents('.block-file').remove();
}
