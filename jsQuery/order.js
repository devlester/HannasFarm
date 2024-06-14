
$(document).ready(function(){
   

    var orderData = $('#orderList').DataTable({
        
       
        
        "bFilter":false,
        "lengthChange":false,
        "processing": true,
        "serverSide": true,
        "order": [],
       
        "ajax": {
            url: "action.php",
            type: "POST",
            data: { action: 'listOrder'},
            dataType: "json",
            
        },
        "pageLength": 10,
        "columnDefs": [{
            className: "dt-center",
            "target": [0,1,2,3,4, 5],
            "orderable": false
        }],
        'rowCallback': function(row, data, index) {
           
            $(row).find('td').addClass('align-middle')
            $(row).find('td:eq(0),td:eq(2),td:eq(3), td:eq(4),td:eq(5)').addClass('text-center')
         
        }

        
        
    });

    $('#addOrder').click(function() {
        $('#orderModal').modal('show');
        $('#orderForm')[0].reset();
        $('.modal-title').html("<i class='fa fa-plus'></i> Add Order");
        $('#action').val("Add");
        $('#btn_action').val("addOrder");
        
    });
    
    $(document).on('submit', '#orderForm', function(event) {
        event.preventDefault();
        $('#action').attr('disabled', 'disabled');
        $("#OrdCrates").attr('disabled',false);
        var formData = $(this).serialize();
        $.ajax({
            url: "action.php",
            method: "POST",
            data: formData,
            success: function(data) {

                
                //$('#orderForm')[0].reset();
                document.getElementById("getProduct").selectedIndex=0;
                document.getElementById("Weight").innerHTML="";
                $("#OrdCrates").val("");
                $("#OrdActual").val("");


                // document.getElementById("getProduct").reload();
                //$('#orderModal').modal('hide');
                $("#OrdTPrice").val("");
                $('#action').attr('disabled', false);
                
                orderData.ajax.reload();
                

            }
        })
        
        alert("successfully added");
        $("#OrdCrates").attr('disabled',true);
        
    });

    $(document).on('click', '.view', function() {
       
        var OrderID = $(this).attr("id");
        var btn_action = 'getOrderDetails';
        $.ajax({
            url: "action.php",
            method: "POST",
            data: { OrderID: OrderID, btn_action: btn_action },
            dataType: "json",
            success: function(data) {
                $('#orderViewList').modal('show');
                //$('#viewSI').val(data.OrderID);
                // $('#shipped').val(data.total_shipped);
                // $('#customer').val(data.customer_id);
                // $('.modal-title').html("<i class='fa fa-edit'></i> Edit Order");
                // $('#order_id').val(order_id);
                // $('#action').val("Edit");
                // $('#btn_action').val("updateOrder");
            }
        })
    });



    $("#Truck").change(function(){        
        var TruckID = $('#Truck').val();        
        $.ajax({
            type: 'POST',
            url: 'action.php',
            data: {id: TruckID, action:'Truck'},
            
            success: function(data)
            {
                $('#getProduct').html(data);
                $('#Weight').val("");
                $("#OrdCrates").val("");
                $("#OrdActual").val("");
                $("#OrdPrice").val("");
            }
        });
    });

    $("#getProduct").change(function(e){
    //    alert($("#OrdSI").val());
        var e = document.getElementById("getProduct");
        var OrderID= document.getElementById("OrdSI").value;
        var CustomerID= document.getElementById("getCustomer").value;
        var ProductID = e.options[e.selectedIndex].value;
        var TruckID=e.options[e.selectedIndex].id;
        alert(OrderID);
        $("#OrdPrice").val("");
        $("#OrdTPrice").val("");
        $.ajax({
            type: 'POST',
            url: 'action.php',
            data: {ProductID: ProductID, TruckID : TruckID, action:'getProductWeight'},
            
            success: function(data)
            {
                $('#Weight').html(data);
                $("#OrdCrates").val("");
                $("#OrdActual").val("");
                
               
            }
        });
        $.ajax({
            type: 'POST',
            url: 'action.php',
            data: { ProductID:ProductID,CustomerID:CustomerID,action:'getPrice'},
            
            success: function(data)
            {
                 $("#OrdPrice").val(data);
                 const OrderPrice = $("#OrdPrice").prop('value'); 
                const ActualWeight = $("#OrdActual").prop('value'); 
                var TotalPrice = OrderPrice*ActualWeight;
                $("#OrdTPrice").val(TotalPrice);
               
            }
        });
        
       
    });  

    $("#OrdPrice").change(function(){
        const OrderPrice = $("#OrdPrice").prop('value'); 
        const ActualWeight = $("#OrdActual").prop('value'); 
        var TotalPrice = OrderPrice*ActualWeight;
        $("#OrdTPrice").val(TotalPrice); 
        
    });

    $("#Weight").change(function(e){
        var e = document.getElementById("Weight");
        var weight = e.options[e.selectedIndex].value;
        $("#OrdCrates").val(weight);
       
    });

    $("#OrdActual").change(function(){
        const OrderPrice = $("#OrdPrice").prop('value'); 
        const ActualWeight = $("#OrdActual").prop('value'); 
        var TotalPrice = OrderPrice*ActualWeight;
        $("#OrdTPrice").val(TotalPrice); 
        
    });


    
    

});