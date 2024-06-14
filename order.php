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
<script src="jsQuery/order.js"></script>
<script src="jsQuery/common.js"></script>
<script src="jsQuery/pay.js"></script>
<?php include('inc/container.php');?>
<div class="container">		
		
	<?php include("menus.php"); ?> 	
	
	<div class="row">
			<div class="col-lg-12">
				<div class="card card-default rounded-0 shadow">
                    <div class="card-header">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="card-title">Manage Orders</h3>
                            </div>
                        
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 text-end">
                                <button type="button" name="add" id="addOrder" class="btn btn-primary btn-sm rounded-0"><i class="far fa-plus-square"></i> New Order</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">                           
                                <input type="radio" name="AllOrder" id="AllOrder" value="AllOrder" />
                                <label class="col-form-label-sm">All</label>
                            </div>
                            <div class="col-sm-2">                           
                                <input type="radio" name="PendingOrder" id="PendingOrder" value="PendingOrder" />
                                <label class="col-form-label-sm">Pending</label>
                            </div>
                            <div class="col-sm-2">                           
                                <input type="radio" name="PaidOrder" id="PaidOrder" value="PaidOrder" />
                                <label class="col-form-label-sm">Paid</label>
                            </div>
                        </div>
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="orderList" class="table table-bordered table-striped">
                                <thead><tr>
                                      
                                    <th>Order Number</th>
                                    <th>Customer Name</th>
                                    <th>Date</th>    
									<th>Crates Quantity</th>
                                    <th>Amount</th>
									<th>Action</th>
                                </tr></thead>
                            </table>
                        </div></div>
                    </div>
                </div>
			</div>
		</div>

        <div id="orderModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Add Order</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form method="post" id="orderForm">
                            <input type="hidden" name="order_id" id="order_id" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                                <div class="mb-3">
                                    <label class="col-form-label-lg fw-bold">Sales Invoice</label>
                                    <div class="input-group">
                                        <input type="text" name="OrdSI"  id="OrdSI" class="form-control rounded-0" required />        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Order Date</label>
                                                <div class="input-group">
                                                    <input type="date" name="OrdDate" id="OrdDate" class="form-control rounded-0" required />        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Truck ID</label>
                                                <div class="input-group">
                                                    <select name="Truck" id="Truck" class="form-select rounded-0" required>
                                                        <option value=""></option>
                                                        <?php echo $inventory->getTrucks(); ?>
                                                            
                                                    </select>       
                                                </div>
                                            </div>
                                        </div>                                        
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label-lg fw-bold">Customer Name</label>
                                    <div class="input-group">
                                        <select name="getCustomer" id="getCustomer" class="form-select rounded-0" required>
                                        <option value=""></option>
                                        <?php echo $inventory->getCustomer(); ?>
                                        </select>                                                
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="col-form-label-lg fw-bold">Product Name</label>
                                    <div class="input-group">
                                        <select name="getProduct" id="getProduct" class="form-select rounded-0" required>
                                        <option value=""></option>
                                           
                                        </select>                                                
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Crates#</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdCrates" id="OrdCrates" class="form-control rounded-0" required disabled/>        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Weight</label>
                                                <div class="input-group">
                                                <select name="Weight" id="Weight" class="form-select rounded-0" required>
                                                    <option value=""></option>
                                                    
                                                </select>       
                                                </div>
                                            </div>                                      
                                        </div>
                                        <div class="col-4">                                           
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Actual Weight</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdActual" id="OrdActual" class="form-control rounded-0" required />        
                                                </div>
                                            </div> 
                                        </div>
                                    </div>    
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Unit Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdPrice" id="OrdPrice" class="form-control rounded-0" value="" required />        
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Total Price</label>
                                                <div class="input-group">
                                                    <input type="text" name="OrdTPrice" id="OrdTPrice" class="form-control rounded-0" required disabled />        
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            
                            <input type="submit" name="action" id="action" class="btn btn-primary rounded-0" value="Add" form="orderForm"/>
                            <button type="button" name="pay" id="payOrder" class="btn btn-default border rounded-0">Pay</button>
                            <button type="button" class="btn btn-default border rounded-0" >View</button>
                            <button type="button" class="btn btn-default border rounded-0" data-bs-dismiss="modal">Close</button>
                            <!-- <a href="">Proceed for payment?</a> -->
                        </div>
                    </div>
            </div>
        </div>
        <div id="orderViewList" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa fa-plus"></i> View List Order</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                        <form id="" method="post">
                        <div class="form-group">
                            <div class="row">
                                <div class="mb-3">
                                    <label class="col-form-label-lg fw-bold">Sales Invoice</label>
                                    <div class="input-group">
                                        <input type="text"  name="viewSI" id="viewSI" class="form-control rounded-0" required disabled />        
                                    </div>
                                </div>

                            </div>
                            <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="viewListOrder" class="table table-bordered table-striped">
                                <thead><tr>
                                      
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                    
                                </tr></thead>
                            </table>
                            </div></div>

                        </div>

                        </form>

                        </div>


        <div id="payModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa-solid fa-cash-register"></i>Payment</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
                        </div>

                        <div class="modal-body">
                            <form id="payForm" method="post">
                                <input type="hidden" name="pay_id" id="pay_id" />                           
                                <input type="hidden" name="pay_btn_action" id="pay_btn_action" />
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="mb-3">
                                                <label class="col-form-label-lg fw-bold">Sales Invoice</label>
                                                <div class="input-group">
                                                    <input type="text"  name="PaySI" id="PaySI" class="form-control rounded-0" required disabled />        
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
                                </div>
                                 
                
                    <div class="row">
                        <div class="col-lg-12">
				            <div class="card card-default rounded-0 shadow">
                                <div class="card-body">
                                 
                                        <div class="col-12 table-responsive">
                                            <table id="checkoutList" class="table table-bordered table-striped" width="100%">
                                                <thead><tr>
                                                    <th>Product Name</th>      
                                                    <th>Total Kilo</th>   
                                                    <th>Total Price</th>  
                                                    
                                                    
                                                                                            
                                                </tr></thead>
                                            </table>
                                        </div>
                                    </div>
                               
                            </div>
                        </div>
                    </div>
                   

                                
                                <div class="row">
                                    <div class="col-12">
                                        <div class="mb-3">
                                            <label class="col-form-label-lg fw-bold">Customer Name</label>
                                            <div class="input-group">
                                                <select name="Customer" id="Customer" class="form-select rounded-0" required>
                                                    <option value=""></option>
                                                    <?php echo $inventory->getCustomer(); ?>
                                                            
                                                </select>        
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
                                            <input type="text" name="TPrice" id="TPrice" class="form-control rounded-0" required disabled />        
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
                                            <input type="text" name="PayCash" id="PayCash" class="form-control rounded-0" required />        
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
                                            <input type="text" name="PayBal" id="PayBal" class="form-control rounded-0" required disabled />        
                                        </div>
                                    </div>
                                </div>
                                

                                   


                            </form>
                        </div>


                        <div class="modal-footer">
                            
                            <input type="submit" name="action" id="action" class="btn btn-primary rounded-0" value="Checkout" form="payForm"/>
                            <button type="button" name="" id="" class="btn btn-primary rounded-0">Back</button>
                            <button type="button" class="btn btn-default border rounded-0" data-bs-dismiss="modal">Close</button>
                            <!-- <a href="">Proceed for payment?</a> -->
                        </div>
                    </div>
            </div>
        </div>
</div>		
<?php include('inc/footer.php');?>