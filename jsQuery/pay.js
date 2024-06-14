

$(document).ready(function(){  
   
    $('#payOrder').click(function() { 
        $('#pay_btn_action').val("payOrder");
        SalesInvoice = $("#OrdSI").val();
             
        $("#PaySI").val(SalesInvoice);
       
        $('#payModal').modal('show');
        $('#orderModal').modal('hide'); 
           
        $('.modal-title').html("<i class='fa-solid fa-cash-register'></i> Payment");
        
        var Tprice;
        var checkoutItem = $('#checkoutList').DataTable({
            
            "bDestroy": true,
            "lengthChange":true,
            "bPaginate": false,
            "bFilter": false,
            "processing": true,
            "serverSide": true,
            "bInfo" : false,
            "width": '100%',
            "order": [],
            "ajax": {
                url: "action.php",
                type: "POST",
                data: { id:SalesInvoice ,action: 'listCheckout'},
                dataType: "json",         
            },
            "pageLength": 10,
            "columnDefs": [{                
                "target": [0,2],
                "orderable": false
            }],
            'rowCallback': function(row, data, index) {
                $(row).find('td').addClass('align-middle')
                $(row).find('td:eq(1), td:eq(2)').addClass('text-center')
                //alert(checkoutItem.rows().data(2))
                // alert(checkoutItem.cell(0,2).data());
                let api = this.api();
                let intVal = function (i) {
                    return typeof i === 'string'
                        ? i.replace(/[\$,]/g, '') * 1
                        : typeof i === 'number'
                        ? i
                        : 0;
                };
                total = api
                    .column(2)
                    .data()
                    .reduce((a, b) => intVal(a) + intVal(b), 0);
                    $("#TPrice").val(total);
                
            },
            
        });
        
        

        $("#PayCash").change(function(){
            var PayCash = $("#PayCash").prop('value'); 
            var TotalPrice = $("#TPrice").prop('value'); 
            var PayBal = (PayCash-TotalPrice);
            
            $("#PayBal").val(PayBal); 
           
            
        });
       
        
    });
    
    
    $('[data-bs-dismiss=modal]').on('click', function (e) {
        
        var $t = $(this),
            target = $t[0].href || $t.data("target") || $t.parents('.modal') || [];
    
      $(target)
        .find("select")
           .val('')
           .end()
        .find("input[type=text],input[type=date]")
           .val('')
           .end();
    });

    $(document).on('submit', '#payForm', function(event) {
        event.preventDefault();
        $("#PaySI").attr('disabled',false);
        $("#TPrice").attr('disabled',false);
        $("#PayBal").attr('disabled',false);
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {

                
             
                
                

            }
            
        })
        alert("payment has been processed!");
        $("#PaySI").attr('disabled',true);
        $("#TPrice").attr('disabled',true);
        $("#PayBal").attr('disabled',true);
        $('#payForm')[0].reset();
    });

    $("#Customer").change(function(e){
       
        var e = document.getElementById("Customer");
        
        var CustomerID = e.options[e.selectedIndex].value;
        
      
        $.ajax({
            type: 'POST',
            url: 'action.php',
            data: {CustomerID: CustomerID,  action:'getCustomerBal'},
            
            success: function(data)
            {
               alert(data);
               
            }
        });
       
    });



    


});