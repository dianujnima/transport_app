<style>
    .dataTables_wrapper>.row {
        justify-content: center;
        align-items: center;
    }

    .dataTables_wrapper .dt-buttons {
        margin-bottom: 10px;
    }

    .dt_table tr td,
    .dt_table tr th {
        vertical-align: middle;
    }

    .dt_table .table-img {
        width: 40px;
        height: 40px;
        object-fit: cover;
    }
</style>
<script src="{{ asset('admin_assets') }}/libs/flatpickr/flatpickr.min.js"></script>
<script src="{{ asset('admin_assets') }}/js/dataTable_bundled.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/pdfmake.min.js" integrity="sha256-DgMKT/pyAKjuP9wB3FRJa8IAVMWlWYjUFfd3UgSCtU0=" crossorigin="anonymous"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.60/vfs_fonts.js" integrity="sha256-UsYCHdwExTu9cZB+QgcOkNzUCTweXr5cNfRlAAtIlPY=" crossorigin="anonymous"></script>

<script>
    var dtable = $("table.table").DataTable({
        scrollX: !0,
        lengthMenu: [
            [25, 50, 100, 250, -1],
            [25, 50, 100, 250, "All"]
        ],
        buttons: [
               {extend: 'copy',
                title: '',},
                {extend: 'csv',
                title: '',},
                {extend: 'excel',
                title: '',},
                {extend: 'pdf',
                title: '',},
                {extend: 'print',
                title: '',
                }
            ],
        language: {
            paginate: {
                previous: "<i class='mdi mdi-chevron-left'>",
                next: "<i class='mdi mdi-chevron-right'>"
            }
        },
        drawCallback: function() {
            $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
        }
    });
    dtable.buttons().container().prependTo(".dataTables_wrapper .col-md-6:eq(0)");

    $(".human_datepicker").flatpickr({
        altInput: !0,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d"
    });
</script>

@if(isset($load_swtichery))
<link href="{{ asset('admin_assets') }}/libs/switchery/switchery.min.css" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin_assets') }}/libs/switchery/switchery.min.js"></script>
<script>
    $('[data-toggle="switchery"]').each(function(a, e) {
        new Switchery($(this)[0], $(this).data())
    });
</script>
@endif