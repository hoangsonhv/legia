$(document).ready(function () {
    // Setup - add a text input to each footer cell
    $('#dataTable tfoot th').each(function (i) {
        var title = $('#dataTable thead th')
            .eq($(this).index())
            .text();
        $(this).html(
            '<input width="100%" type="text" placeholder="' + title + '" data-index="' + i + '" />'
        );
    });
 
    // DataTable
    var table = $('#dataTable').DataTable({
        scrollY: 'auto',
        scrollX: true,
        scrollCollapse: true,
        paging: false,
        fixedColumns: true
    });
 
    // Filter event handler
    $(table.table().container()).on('keyup', 'tfoot input', function () {
        table
            .column($(this).data('index'))
            .search(this.value)
            .draw();
    });
  });