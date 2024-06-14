$(document).ready(function() {
    var purchaseData = $('#purchaseList').DataTable({
        "lengthChange": false,
        "processing": true,
        "serverSide": true,
        "order": [],
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'listPurchase' },
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
    

    $('#addPurchase').click(function() {
        $('#purchaseModal').modal('show');
        $('#purchaseForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Purchase");
        $('#action').val("Add");
        $('#btn_action').val("addPurchase");
        //$('#PurchaseNHeads').val(15);

    });

    $(document).on('submit', '#purchaseForm', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        $('#PurchaseNHeads').attr('disabled',false);
        $('#PurchaseNCrates').attr('disabled',false);
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {
                //$('#purchaseForm')[0].reset();              
                //$('#purchaseModal').modal('hide');
                $('#PurchasePWeight').val('');
                $('#PurchaseNCrates').attr('disabled',true);
                $('#PurchaseNHeads').attr('disabled',true);
                $('#CheckNHeads').prop('checked', false);
                var NCrates = +$('#PurchaseNCrates').val() + 1;            
                (parseInt($('#PurchaseNCrates').attr('value',NCrates)));
                $('#action').attr('disabled', false);                
                purchaseData.ajax.reload();
            }
        })
    });

    $(document).click(function() {
        $("#CheckNHeads").change(function(){
           var numHeads = this.checked;
           if(numHeads){
                $("#PurchaseNHeads").prop("disabled",false);

            }else{
                $("#PurchaseNHeads").prop("disabled",true);
            }
        });
    });

    $("#Product").change(function(e){
        var e = document.getElementById("Product");
        var value = e.value;
        var CategoryID = e.options[e.selectedIndex].id;
        //alert(CategoryID);
        if(CategoryID==9901){
            $("#PurchaseNHeads").val("15");
        }else if(CategoryID==9903){
            $("#PurchaseNHeads").val("1");
        }
       
    });

  





});