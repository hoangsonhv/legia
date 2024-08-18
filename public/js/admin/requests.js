// Global
var aCode = [];

$(function () {
  'use strict'

  // Init
  $('[data-toggle="tooltip"]').tooltip();
  reloadDatepicker();

  $('.add-upload').click(function() {
    var field = $(this).attr('field-file');
    if (!field) {
      field = 'accompanying_document';
    }
    var html = '<div class="input-group block-file">'
      + '<div class="custom-file">'
        + '<input type="file" name="'+field+'[]" class="custom-file-input" multiple />'
        + '<label class="custom-file-label">Chọn file</label>'
      + '</div>'
      + '<div class="input-group-append">'
        + '<span class="input-group-text btn btn-danger" onclick="removeUpload(this)">Xoá upload</span>'
      + '</div></div>';
    $(this).before(html);
    bsCustomFileInput.init();
  });

  $('[name=category]').change(function(e) {
    var ele = $(this).parents('.form-root:first').find('.data-materials table thead tr th.t-dinh-luong');
    if ($(this).val().indexOf('dinh_ky') === -1 && $(this).val().indexOf('chiet_khau') === -1) {
      ele.text('Số lượng');
    } else {
      ele.text('Số tiền');
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

  $('[name=category]').trigger( "change" );

  // Load material
  $('#display-material').click(function() {
    $('.item-material .table tbody').empty();
    $('.item-material').addClass('d-none');
    $('.item-material .table thead th.check-all input').prop('checked', false);
    $('#modal-materials').modal('show');
  });

  $('#search-material').click(function() {
    $.ajax({
      method: "POST",
      url: $(this).attr('data-url'),
      data: {
        code: $(this).parents('.modal-body:first').find('[name=code]').val(),
        lot_no: $(this).parents('.modal-body:first').find('[name=lot_no]').val(),
      }
    })
    .done(function( data ) {
      if (data.success) {
        var ele = $('.item-material');
        ele.html(data.content);
        ele.removeClass('d-none');
      } else {
        alert('Không tìm thấy vật liệu trong Kho.');
      }
    });
  });
  $('#show-form').click(function() {
    $.ajax({
      method: "GET",
      url: $(this).attr('data-url') + '?model_type=' + $(this).parents('.modal-body:first').find('[name=model_type]').val(),
    })
    .done(function( data ) {
        var ele = $('.form-material');
        ele.html(data);
        ele.removeClass('d-none');
    });
    $('#warehouse_date').datetimepicker({
        format: 'YYYY-MM-DD',
        icons: {
            time: 'far fa-clock'
        }
    });
  })
  $('#load-material').click(function() {
    var eleModal = '.item-material .table tbody';
    if (!$(eleModal + ' tr td.check-data input:checked').length) {
      alert('Vui lòng chọn vật liệu sẽ thêm.');
      return false;
    }

    if ($(eleModal + ' tr').length) {
      checkCodeExists(eleModal, aCode).then(
        function(exists) {
          if (exists !== true) {
            var eleForm = '.form-root .table-content tbody';
            var lengthTrForm = $(eleForm + ' tr').length;
            // Get data modal
            $(eleModal + ' tr').each(function(index, value) {
              var eleRow = $(eleModal + ' tr').eq(index);
              if (eleRow.find('td.check-data input:checked').val()) {
                lengthTrForm += 1;
                var opts = {
                  merchandise_id: eleRow.find('td.merchandise_id input').val(),
                  code: eleRow.find('td.code input').val(),
                  vat_lieu: eleRow.find('td.vat-lieu input').val(),
                  dv_tinh: eleRow.find('td.dv_tinh input').val(),
                  kich_thuoc: eleRow.find('td.chi-tiet input').val(),
                };
                $(eleForm).append(getItem(lengthTrForm, 'Tấm', opts));
                // Add code
                aCode.push(eleRow.find('td.code input').val());
              }
            });
            reloadDatepicker();
            // Hide modal
            $('#modal-materials').modal('hide');
          }
        },
        function(error) { /* code if some error */ }
      );
    }
  });

  $('#load-other-material').click(function() {
    var eleForm = $('.form-material');
    var codeMaterial = eleForm.find('input[name="code"]').first().val();
    var motaMaterial = eleForm.find('input[name="vat_lieu"]').length ? eleForm.find('input[name="vat_lieu"]').first().val() : '';
    var ele    = $('.data-materials').find(".table-content tbody");
    var index  = ele.find('tr').length + 1;
    var dvTinh = '';
    if ($(this).attr('data-dvTinh')) {
      dvTinh = $(this).attr('data-dvTinh');
    }
    submitFormMaterial(eleForm.find('form').first().attr('action'), eleForm.find('form').first().serializeArray(), function(res) {
      ele.append(getItem(index, 'Tấm', { code: codeMaterial, vat_lieu: motaMaterial, merchandise_id: res.l_id, kich_thuoc: res.detail, dv_tinh: res.dv_tinh }));
      reloadDatepicker();
      $('#modal-another-material').modal('hide');
    });
  });
  function submitFormMaterial(url, data, callback) {
    $.ajax({
        method: "POST",
        url: url,
        data: data
    })
    .done(function(res) {
        callback(res);
    });
  }

  $('#add-row-material').click(function() {
    $('#modal-another-material').modal('show');
    $('.form-material').empty();
  });

  $('#add-row-service').click(function() {
    var ele    = $(this).parents('.form-root').find(".table-content tbody");
    var index  = ele.find('tr').length + 1;
    var dvTinh = '';
    if ($(this).attr('data-dvTinh')) {
      dvTinh = $(this).attr('data-dvTinh');
    }
    ele.append(getItemService(index, dvTinh, {code: '', vat_lieu: ''}, false));
    reloadDatepicker();
  });
});

function calPaymentPer(_this, field) {
  let per = $(_this).val();
  let moneyTotal = $("[name='money_total']").val();
  let format = formatCurrent(Math.round(((per * moneyTotal)/100)).toString());
  $("[name='tmp[amount_money][" +field+ "]']").val(format['format']);
  $("[name='thanh_toan[amount_money][" +field+ "]']").val(format['original']);
}

function getNumberFormat(_this) {
  var number = formatCurrent($(_this).val());
  $(_this).val(number['format']);
  $(_this).parent().find('.data-origin').val(number['original']);
}

function removeUpload(_this) {
  $(_this).parents('.block-file').remove();
}

function reloadDatepicker() {
  $('.calendar-date').datepicker({
    format: 'yyyy-mm-dd'
  });
}

function getItem(index, unit, opts,readonly = true) {
  console.log(opts);
  let result = '';
    if(Array.isArray(opts.kich_thuoc)) {
      // Lặp qua tất cả các key-value trong detail
      $.each(opts.kich_thuoc, function(key, value) {
          result += `${key}: ${value}, `;
      });
  
      // Loại bỏ dấu phẩy cuối cùng nếu có
      result = result.slice(0, -2);
    } else {
      result = opts.kich_thuoc
    }

  $readonly = readonly ? 'readonly' : '';
  return '<tr align="center">'
    + '<td class=""><i class="fas fa-minus-circle text-danger delete-item" title="Xoá vật liệu" onclick="deteleItem(this)"></i></td>'
    + '<td class="sequence">'+index+'</td>'
    + '<td class="code"><input type="hidden" name="material[merchandise_id][]" value="'+opts.merchandise_id+'" /><input '+$readonly+' class="form-control" type="text" name="material[code][]" value="'+opts.code+'"></td>'
    + '<td class=""><textarea '+$readonly+' class="form-control" name="material[mo_ta][]" rows="1">'+opts.vat_lieu+'</textarea></td>'
    + '<td class=""><textarea class="form-control" name="material[kich_thuoc][]" rows="1">'+result+'</textarea></td>'
    + '<td class=""><textarea class="form-control" name="material[quy_cach][]" rows="1"></textarea></td>'
    + '<td class=""><input class="form-control" style="width: 70px" type="text" name="material[dv_tinh][]" value="'+opts.dv_tinh+'"></td>'
    + '<td class=""><input class="form-control" style="width: 120px" type="text" name="tmp_material[dinh_luong][]" onKeyUp="return getNumberFormat(this)" min="1" value=""><input class="form-control data-origin" type="hidden" name="material[dinh_luong][]" value=""></td>'
    + '<td class=""><input class="form-control calendar-date" style="width: 120px" type="text" name="material[thoi_gian_can][]" value=""></td>'
    + '<td class=""><textarea class="form-control" name="material[ghi_chu][]" rows="1"></textarea></td>'
    + '</tr>';
}
function getItemService(index, unit, opts, readonly = true) {
  $readonly = readonly ? 'readonly' : '';
  return '<tr align="center">'
    + '<td class=""><i class="fas fa-minus-circle text-danger delete-item" title="Xoá vật liệu" onclick="deteleItem(this)"></i></td>'
    + '<td class="sequence">'+index+'</td>'
    + '<td class="code"><input type="hidden" name="material[merchandise_id][]" value="'+opts.merchandise_id+'" /><input '+$readonly+' class="form-control" type="text" name="material[code][]" value="'+opts.code+'"></td>'
    + '<td class=""><textarea '+$readonly+' class="form-control" name="material[mo_ta][]" rows="1">'+opts.vat_lieu+'</textarea></td>'
    + '<td class=""><textarea class="form-control" name="material[dv_tinh][]" rows="1">'+opts.dv_tinh+'</textarea></td>'
    + '<td class=""><input class="form-control" style="width: 120px" type="text" name="tmp_material[dinh_luong][]" onKeyUp="return getNumberFormat(this)" min="1" value=""><input class="form-control data-origin" type="hidden" name="material[dinh_luong][]" value=""></td>'
    + '<td class=""><input class="form-control" style="width: 120px" type="text" name="tmp_material[don_gia][]" onKeyUp="return getNumberFormat(this)" min="1" value=""><input class="form-control data-origin" type="hidden" name="material[don_gia][]" value=""></td>'
    + '<td class=""><input class="form-control" style="width: 120px" type="text" name="tmp_material[thanh_tien][]" onKeyUp="return getNumberFormat(this)" min="1" value=""><input class="form-control data-origin" type="hidden" name="material[thanh_tien][]" value=""></td>'
    + '<td class=""><input class="form-control calendar-date" style="width: 120px" type="text" name="material[thoi_gian_can][]" value=""></td>'
    + '<td class=""><textarea class="form-control" name="material[ghi_chu][]" rows="1"></textarea></td>'
    + '</tr>';
}

function deteleItem(_this) {
  var existsWarehouse = $(_this).parents('.form-root:first').length;
  // Remove item
  var item = aCode.indexOf($(_this).parents('tr:first').find('td.code input').val());
  if (item !== -1) {
    aCode.splice(item, 1);
  }
  $(_this).parents('tr:first').remove();
  if (existsWarehouse) {
    var classList = $('.form-root .table tbody tr');
  } else {
    var classList = $('.item-material .table tbody tr');
  }
  // Load sequence
  var lengthSequence = classList.length;
  for (var i = 0; i < lengthSequence; i++) {
    classList.eq(i).find('td.sequence').text(i + 1);
  }
}

function checkedAllRows(_this) {
  var ele = $(_this).parents('.table:first').find('tbody tr td.check-data input');
  if (_this.checked) {
    ele.prop('checked', true);
  } else {
    ele.prop('checked', false);
  }
}

async function checkCodeExists(eleModal, aCode) {
  var exists = false;
  return exists;
  await $(eleModal + ' tr').each(async function(index, value) {
    var eleRow = $(eleModal + ' tr').eq(index);
    if (eleRow.find('td.check-data input:checked').val()) {
      var codeExists = await aCode.indexOf(eleRow.find('td.code input').val());
      if (!exists && codeExists !== -1) {
        alert('Tồn tại mã vật liệu đã được chọn.');
        exists = true;
        return false;
      }
    }
  });
  return exists;
}

  function insertRequestPriceSurvey(id) {
  let tbody = $(".tbody-request-price-survey-" + id);
  tbody.append("<tr>\n" +
      "        <input type=\"hidden\" name=\"id[]\" value=\"\" />\n" +
      "        <td>\n" +
      "            <input name=\"supplier[]\" class=\"form-control\" placeholder=\"Nhà cung cấp\"/>\n" +
      "        </td>\n" +
      "        <td>\n" +
      "            <select name=\"type[]\" class=\"form-control\">\n" +
      "                <option value=\"1\">NVLNK</option>\n" +
      "                <option value=\"2\">NVLND</option>\n" +
      "                <option value=\"3\">Khác</option>\n" +
      "            </select>\n" +
      "        </td>\n" +
      "        <td>\n" +
      "            <input name=\"product_group[]\" class=\"form-control\" placeholder=\"Nhóm sản phẩm\"/>\n" +
      "        </td>\n" +
      "        <td>\n" +
      "            <input class=\"form-control calendar-date\" style=\"width: 120px\" type=\"text\"\n" +
      "                   name=\"deadline[]\">\n" +
      "        </td>\n" +
      "        <td>\n" +
      "            <input name=\"tmp_price[]\" class=\"form-control\" placeholder=\"giá trị báo giá\"/>\n" +
      "            <input name=\"price[]\" type=\"hidden\"/>\n" +
      "        </td>\n" +
      "        <td>\n" +
      "            <input name=\"number_date_wait_pay[]\" type=\"number\" class=\"form-control\" placeholder=\"Số ngày quá hạn thanh toán\"/>\n" +
      "        </td>\n" +
      "        <td>\n" +
      "        </td>\n" +
      "        <td></td>\n" +
      "    </tr>")
}