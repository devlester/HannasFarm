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
<script src="jsQuery/purchase.js"></script>
<script src="jsQuery/common.js"></script>
<?php include('inc/container.php');?>
<div class="container">		
		
	<?php include("menus.php"); ?> 
	    <div class="row">
			<div class="col-lg-12">
				<div class="card card-default rounded-0 shadow">
                    <div class="card-header">
                    	<div class="row">
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-6">
                            	<h3 class="card-title">Purchase List</h3>
                            </div>
                        
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-6 text-end">
                                <button type="button" name="addPurchase" id="addPurchase" class="btn btn-primary btn-sm rounded-0"><i class="far fa-plus-square"></i> Add Purchase</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row"><div class="col-sm-12 table-responsive">
                            <table id="purchaseList" class="table table-bordered table-striped">
                                    <thead><tr>
                                        <th>Date</th> 									
                                        <th>Supplier Name</th>	
                                        <th>Product Name</th>	                                        
                                        <th>Crates Sequence</th>
                                        <th>Heads per Crates</th>
                                        <th>Kilo per Crates</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                        

                                    </tr></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
	    </div>
</div>

        <div id="purchaseModal" class="modal fade">
            <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title"><i class="fa fa-plus"></i> Add Purchase</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div> 

                       <div class="modal-body">                           
                            <form method="post" id="purchaseForm">
                            <input type="hidden" name="PurchaseID" id="PurchaseID" />
                            <input type="hidden" name="btn_action" id="btn_action" />
                            <input type="hidden" name="ProductID" id="ProductID" />
                                <div class="form-group">
                                    <label class="col-form-label-lg fw-bold">Purchase Number / DR Number</label>
                                    <div class="input-group">
                                        <input type="text" name="PurchaseNumber" id="PurchaseNumber" class="form-control rounded-0" required/>        
                                    </div>
                                </div>	
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="col-form-label-lg fw-bold">Date Purchase</label>
                                            <div class="input-group">
                                                <input type="date" name="PurchaseDate" id="PurchaseDate" class="form-control rounded-0" required/> 
                                            </div>                                  
                                        </div>
                                    
                                    
                                        <div class="col-6">
                                            <label class="col-form-label-lg fw-bold">Truck Number</label>
                                            <div class="input-group">
                                                <select name="TruckID" id="TruckID" class="form-select rounded-0" required>
                                                    <option value=""></option>
                                                     <?php echo $inventory->getTrucks(); ?>                                               
                                                </select>                                                
                                            </div>
                                        </div>
                                    </div>    
                                    
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label-lg fw-bold">Supplier Name</label>
                                    <select name="Supplier" id="Supplier" class="form-select rounded-0" required>
                                        <option value="">Select Supplier</option>
                                        <?php echo $inventory->SupplierDropdownList();?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label-lg fw-bold">Product Name</label>
                                    <select  name="Product" id="Product" class="form-select rounded-0" required>
                                        <option value="">Select Product</option>
                                        <?php echo $inventory->ProductDropdownList();?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-4">
                                            <label for="" class="col-form-label-lg">Weight</label>
                                            <input type="number" placeholder="0.00" name="PurchasePWeight" id="PurchasePWeight" class="form-control rounded"  step=any required /> 
                                        </div>
                                        <div class="col-4">
                                            <label for="" class="col-form-label-lg">Crates</label>
                                            <input type="number" name="PurchaseNCrates" id="PurchaseNCrates"  class="form-control rounded" value="1" disabled /> 
                                        </div>
                                        <div class="col-2">                                           
                                            <label for="" class="col-form-label-lg">Heads</label>
                                            <input type="number" name="PurchaseNHeads" id="PurchaseNHeads" class="form-control rounded" value="" disabled/> 
                                        </div>
                                        <div class="col-2">
                                            <div class="row-cols-2">
                                                <label for="" class="col-form-label-lg"></label>
                                                <label for="" class="col-form-label-lg form-text"></label>
                                            </div> 

                                            <input type="checkbox" name="CheckNHeads" id="CheckNHeads"  /> 
                                            <label for="editNHeads">edit</label>                                           
                                           
                                        </div>
                                       
                                        
                                        
                                    </div>    
                                </div>
                                <div class="form-group">
                                    <label class="col-form-label-lg fw-bold">Employee Name</label>
                                    <div class="input-group">
                                        <input type="text" name="EmployeeName" id="EmployeeName" class="form-control rounded-0" value=" <?php echo $_SESSION['EmployeeName']; ?> " disabled />  
                                            
                                    </div>
                                </div>                        
                               
                            </form> 
                        </div> 
                        <div class="modal-footer">
                            <input type="submit" name="action" id="action" class="btn btn-primary btn-sm rounded-0" value="Add" form="purchaseForm"/>
                            <button type="button" class="btn btn-default border btn-sm rounded-0" data-bs-dismiss="modal">Finish</button>
                        </div>
                    </div>
            </div>
    </div>
</div>	
<?php include('inc/footer.php');?>