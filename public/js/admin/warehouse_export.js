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
                        var eleForm = '#content_table .table tbody';
                        var lengthTrForm = $(eleForm + ' tr').length;
                        // Get data modal
                        $(eleModal + ' tr').each(function(index, value) {
                            var eleRow = $(eleModal + ' tr').eq(index);
                            if (eleRow.find('td.check-data input:checked').val()) {
                                lengthTrForm += 1;
                                var opts = {
                                    code: eleRow.find('td.code input').val(),
                                    name: eleRow.find('td.vat-lieu input').val(),
                                    do_day: eleRow.find('td.do_day input').val(),
                                    hinh_dang: eleRow.find('td.hinh_dang input').val(),
                                    dia_w_w1: eleRow.find('td.dia_w_w1 input').val(),
                                    l_l1: eleRow.find('td.l_l1 input').val(),
                                    w2: eleRow.find('td.w2 input').val(),
                                    l2: eleRow.find('td.l2 input').val(),
                                    warehouse_id: eleRow.find('td.warehouse_id input').val(),
                                    table_name: eleRow.find('td.table_name input').val(),
                                    merchandise_id: eleRow.find('td.merchandise_id input').val(),
                                    lot_no: eleRow.find('td.lot_no input').val(),
                                    ton_kho: eleRow.find('td.ton_kho input').val(),
                                    ton_kho_phu: eleRow.find('td.ton_kho_phu input').val(),
                                    inner: eleRow.find('td.m_inner').text(),
                                    hoop: eleRow.find('td.m_hoop').text(),
                                    filler: eleRow.find('td.m_filler').text(),
                                    outer: eleRow.find('td.m_outer').text(),
                                    thick: eleRow.find('td.m_thick').text(),
                                    trong_luong_cuon: eleRow.find('td.m_trong_luong_cuon').text(),
                                    m_cuon: eleRow.find('td.m_m_cuon').text(),
                                    m_cay: eleRow.find('td.m_m_cay').text(),
                                    size: eleRow.find('td.size input').val() ?? eleRow.find('td.m_size').text(),
                                    kich_co: eleRow.find('td.kich_co input').val(),
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

    $('#add-row-material').click(function() {
        var ele    = $(this).parents('.form-root').find(".table tbody");
        var index  = ele.find('tr').length + 1;
        var dvTinh = '';
        if ($(this).attr('data-dvTinh')) {
            dvTinh = $(this).attr('data-dvTinh');
        }
        ele.append(getItem(index, dvTinh, {code: '', vat_lieu: ''}));
        reloadDatepicker();
    });
});

function getNumberFormat(_this) {
    var number = formatCurrent($(_this).val());
    $(_this).val(number['format']);
    $(_this).parent().find('.data-origin').val(number['original']);
}

function getNumberFormatUnitPrice(_this) {
    /**
     * Format số tiền
     * @type {{original, format}}
     */
    var number = formatCurrent($(_this).val());
    $(_this).val(number['format']);
    $(_this).parent().find('.data-origin').val(number['original']);

    /**
     * Tính thành tiền
     * @type {*|jQuery|string|undefined}
     */
    var quantity = $(_this).parent().parent().find('.data-quantity').val();
    var intoMoney = Number(number['original']) * quantity;
    var intoMoneyFormat = formatCurrent(String(intoMoney));
    $(_this).parent().parent().find('.data-into-money').val(intoMoneyFormat['format']);
    $(_this).parent().parent().find('.data-origin-into-money').val(intoMoneyFormat['original']);

    /**
     * Tính tổng tiền
     * @type {HTMLCollectionOf<Element>}
     */
    const elementTotalMoney = document.getElementsByClassName("data-origin-into-money");
    let totalMoney = 0;
    for(let i=0; i <= elementTotalMoney.length -1; i++) {
        totalMoney += Number(elementTotalMoney[i].value);
    }

    var totalMoneyFormat = formatCurrent(String(totalMoney));
    $('[name=tmp_price_total]').val(totalMoneyFormat['format']);
    $('[name=price_total]').val(totalMoneyFormat['original']);

    let vat = $('#data-vat');
    formatTotalVat(vat)
}

function getNumberFormatQuantity(_this) {
    var value = $(_this).val();
    if(parseInt(value) > parseInt($(_this).attr('max'))) {$(_this).val($(_this).attr('max')); return;}
    var unitPrice = $(_this).parent().parent().find('.data-unit-price').val();
    var intoMoney = Number(value) * Number(unitPrice);
    var intoMoneyFormat = formatCurrent(String(intoMoney));
    $(_this).parent().parent().find('.data-into-money').val(intoMoneyFormat['format']);
    $(_this).parent().parent().find('.data-origin-into-money').val(intoMoneyFormat['original']);

    /**
     * Tính tổng tiền
     * @type {HTMLCollectionOf<Element>}
     */
    const elementTotalMoney = document.getElementsByClassName("data-origin-into-money");
    let totalMoney = 0;
    for(let i=0; i <= elementTotalMoney.length -1; i++) {
        totalMoney += Number(elementTotalMoney[i].value);
    }
    var totalMoneyFormat = formatCurrent(String(totalMoney));
    $('[name=tmp_price_total]').val(totalMoneyFormat['format']);
    $('[name=price_total]').val(totalMoneyFormat['original']);

    let vat = $('#data-vat');
    formatTotalVat(vat)
}

function formatTotalVat(_this) {
    let vat = $(_this).val();
    let priceTotal = $('[name=price_total]').val();
    let value = (priceTotal*vat)/100;
    value = Math.round(value);

    var format = formatCurrent(String(value));
    $('[name=tmp_total_vat]').val(format['format']);
    $('[name=total_vat]').val(format['original']);

    let valuePayment = value + Number(priceTotal);
    var formatPayment = formatCurrent(String(valuePayment));
    $('[name=tmp_total_payment]').val(formatPayment['format']);
    $('[name=total_payment]').val(formatPayment['original']);

    $('.total_payment_vnese').text(to_vietnamese(valuePayment))
}

function removeUpload(_this) {
    $(_this).parents('.block-file').remove();
}

function reloadDatepicker() {
    $('.calendar-date').datepicker({
        format: 'yyyy-mm-dd'
    });
}

function getItem(index, unit, opts) {
    return '<tr align="center">'
        + '<input class="warehouse_id" type="hidden" name="product[warehouse_id][]" value="'+opts.warehouse_id+'"></input>'
        + '<input class="warehouse_id" type="hidden" name="product[merchandise_id][]" value="'+opts.merchandise_id+'"></input>'
        + '<input class="table_name" type="hidden" name="product[table_name][]" value="'+opts.table_name+'"></input>'
        + '<td class=""><i class="fas fa-minus-circle text-danger delete-item" title="Xoá sản phẩm" onclick="deteleItem(this)"></i></td>'
        + '<td class="sequence">'+index+'</td>'
        + '<td class="name"><textarea readonly class="form-control w-auto" name="product[name][]" rows="1">'+opts.name+'</textarea></td>'
        + '<td class="code"><input readonly class="form-control w-auto" type="text" name="product[code][]" value="'+opts.code+'"></td>'
        + '<td class="do_day"><input readonly class="form-control w-auto" type="text" name="product[do_day][]" value="'+opts.do_day+'"></td>'
        + '<td class="hinh_dang"><input readonly class="form-control w-auto" type="text" name="product[hinh_dang][]" value="'+opts.hinh_dang+'"></td>'
        + '<td class="dia_w_w1"><input readonly class="form-control w-auto" type="text" name="product[dia_w_w1][]" value="'+opts.dia_w_w1+'"></td>'
        + '<td class="l_l1"><input readonly class="form-control w-auto" type="text" name="product[l_l1][]" value="'+opts.l_l1+'"></td>'
        + '<td class="w2"><input readonly class="form-control w-auto" type="text" name="product[w2][]" value="'+opts.w2+'"></td>'
        + '<td class="l2"><input readonly class="form-control w-auto" type="text" name="product[l2][]" value="'+opts.l2+'"></td>'
        + '<td style="min-width: 120px" class="inner"><input readonly class="form-control" type="text" name="product[inner][]" value="' + (opts.inner ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="hoop"><input readonly class="form-control" type="text" name="product[hoop][]" value="' + (opts.hoop ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="filler"><input readonly class="form-control" type="text" name="product[filler][]" value="' + (opts.filler ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="outer"><input readonly class="form-control" type="text" name="product[outer][]" value="' + (opts.outer ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="thick"><input readonly class="form-control" type="text" name="product[thick][]" value="' + (opts.thick ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="tieu_chuan"><input readonly class="form-control" type="text" name="product[tieu_chuan][]" value="' + (opts.tieu_chuan ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="kich_co"><input readonly class="form-control" type="text" name="product[kich_co][]" value="' + (opts.kich_co ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="trong_luong_cuon"><input readonly class="form-control" type="text" name="product[trong_luong_cuon][]" value="' + (opts.trong_luong_cuon ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="m_cuon"><input readonly class="form-control" type="text" name="product[m_cuon][]" value="' + (opts.m_cuon ?? '') + '"></td>'
        + '<td style="min-width: 120px" class="m_cay"><input readonly class="form-control" type="text" name="product[m_cay][]" value="' + (opts.m_cay ?? '') + '"></td>'
        + '<td class="lot_no"><input readonly class="form-control w-auto" type="text" name="product[lot_no][]" value="'+opts.lot_no+'"></td>'
        + '<td class=""><input readonly class="form-control w-auto" type="text" name="product[unit][]" value="'+unit+'"></td>'
        + '<td class="ton_kho"><input readonly class="form-control w-auto" type="text" name="product[ton_kho][]" value="'+opts.ton_kho+'"></td>'
        + '<td class="ton_kho_phu"><input readonly class="form-control w-auto" type="text" name="product[ton_kho][]" value="'+opts.ton_kho_phu+'"></td>'
        + '<td class=""><input class="form-control w-auto" type="number" name="product[quantity_doc][]"/> </td>'
        + '<td class=""><input class="form-control w-auto data-quantity" type="number" name="product[quantity_reality][]" max="'+opts.ton_kho+'" onKeyUp="return getNumberFormatQuantity(this)"/> </td>'
        + '<td class=""><input class="form-control w-auto" type="text" name="tmp_product[unit_price][]" onKeyUp="return getNumberFormatUnitPrice(this)" min="1" value="">'
        + '<input class="form-control w-auto data-origin data-unit-price" type="hidden" name="product[unit_price][]" value=""></td>'
        + '<td class=""><input class="form-control w-auto data-into-money" type="text" name="tmp_product[into_money][]" onKeyUp="return getNumberFormat(this)" min="1" value="">'
        + '<input class="form-control w-auto data-origin data-origin-into-money" type="hidden" name="product[into_money][]" value=""></td>'
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
