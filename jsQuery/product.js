$(document).ready(function(){
    var purchaseData = $('#productList').DataTable({
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'listProduct' },
            dataType: "json"
        },
        "pageLength": 10,
        "columnDefs": [{
            "target": [0,3,4,5,6, 7],
            "orderable": false
        }],
        'rowCallback': function(row, data, index) {
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0),td:eq(3), td:eq(4), td:eq(5), td:eq(6), td:eq(7)').addClass('text-center')
        },
    });



});