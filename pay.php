<?php 
ob_start();
session_start();
include('inc/header.php');
include 'Inventory.php';
$inventory = new Inventory();
$inventory->checkLogin();
?>

<script src="jsQuery/jquery.dataTables.min.js"></script>
<script src="jsQuery/dataTables.bootstrap.min.js"></script>		
<link rel="stylesheet" href="css/dataTables.bootstrap.min.css" />

<script src="jsQuery/common.js"></script>
<script src="jsQuery/pay.js"></script>
<?php include('inc/container.php');?>
<div class="container">		
		
	<?php include("menus.php"); ?> 	
	
	<div class="row">		

        <div id="payModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa-solid fa-cash-register"></i>Payment</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <form id="payForm" method="post">
                                <input type="hidden" name="pay_id" id="pay_id" />
                                <input type="hidden" name="btn_action" id="btn_action" />
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Sales Invoice</label>
                                                <div class="input-group">
                                                    <input type="text" name="PaySI" id="PaySI" class="form-control rounded-0" required />        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Order Date</label>
                                                <div class="input-group">
                                                    <input type="date" name="PayDate" id="PayDate"  class="form-control rounded-0" required />                                                    
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                
                                    <!-- <div class="row">
                                        <div class="col-6">                                    
                                            <div class="mb-3" id="checkout">
                                                <label class="col-form-label-lg fw-sm">Product Name</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                                </div>
                                            </div>                                                
                                        </div>
                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-sm">Kilo</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                                </div>
                                            </div>                                                
                                        </div>
                                        <div class="col-3">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-sm">Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                                </div>
                                            </div>
                                        </div>
                                    </div> -->
                                    <div class="row"><div class="col-sm-12 table-responsive">
                                        <table id="checkoutList" class="table table-bordered table-striped">
                                            <thead><tr>
                                                <th>Product Name</th>      
                                                <th>Total Kilo</th>   
                                                <th>Total Price</th>  
                                                
                                                                                        
                                            </tr></thead>
                                        </table>
                                    </div></div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="col-form-label-lg fw-bold">Customer Name</label>
                                            <div class="input-group">
                                                <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-5">
                                        
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label-lg fw-sm">Total Price</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label-lg fw-sm">Total Cash</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-5">
                                        
                                    </div>
                                    <div class="col-3">
                                        <label class="col-form-label-lg fw-sm">Balance</label>
                                    </div>
                                    <div class="col-4">
                                        <div class="input-group">
                                            <input type="text" name="OrdSI" id="OrdSI" class="form-control rounded-0" required />        
                                        </div>
                                    </div>
                                </div>
                                

                                   


                            </form>
                        </div>


                        <div class="modal-footer">
                            
                            <input type="submit" name="action" id="action" class="btn btn-primary rounded-0" value="Checkout" form=""/>
                            <button type="button" name="add" id="addOrder" class="btn btn-primary rounded-0">Back</button>
                            <button type="button" class="btn btn-default border rounded-0" data-bs-dismiss="modal">Close</button>
                            <!-- <a href="">Proceed for payment?</a> -->
                        </div>
                    </div>
            </div>
        </div>
</div>		
<?php include('inc/footer.php');?>