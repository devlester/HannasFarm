<?php
class Inventory {
    private $host  = 'localhost';
    private $user  = 'root';
    private $password   = '';
    private $database  = 'hannasfarm_ims';   
	private $categoryTable = "Categories";
    private $productTable = "Products";
    private $purchaseTable = "Purchase";
    private $supplierTable = "Suppliers";
	private $customerTable = "Customers";
    private $employeeTable = "Employees";
	private $orderTable = "Orders";
	private	$orderDetailTable= "orderdetails";
	private $truckTable = "trucks";
	private	$paymentTable = "payment";
	
	private $dbConnect = false;
	private $bool = "";
    public function __construct(){
        if(!$this->dbConnect){ 
            $conn = new mysqli($this->host, $this->user, $this->password, $this->database);
            if($conn->connect_error){
                die("Error failed to connect to MySQL: " . $conn->connect_error);
            }else{
                $this->dbConnect = $conn;
            }
        }
    }
	private function getData($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$data= array();
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			$data[]=$row;            
		}
		return $data;
	}
	private function getNumRows($sqlQuery) {
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		if(!$result){
			die('Error in query: '. mysqli_error());
		}
		$numRows = mysqli_num_rows($result);
		return $numRows;
	}
	public function login($EmployeeUser, $EmployeePassword){
		$password = md5($EmployeePassword);
    	$sqlQuery = "
			SELECT EmployeeID, EmployeeUser, EmployeePassword, EmployeeName, EmployeeDesignation
			FROM ".$this->employeeTable." 
			WHERE EmployeeUser='".$EmployeeUser."' AND EmployeePassword='".$EmployeePassword."'";
        return  $this->getData($sqlQuery);
	}	
	public function checkLogin(){
		if(empty($_SESSION['EmployeeID'])) {
			header("Location:login.php");
		}
	}
    //==============================================Purchase=====================================================================//
    public function listPurchase(){	 
        
        $sqlQuery = "SELECT * FROM " .$this->purchaseTable. " as ph 
                    INNER JOIN " .$this->supplierTable. " as s ON ph.SupplierID = s.SupplierID
                    INNER JOIN ". $this->productTable ." as p ON ph.ProductID = p.ProductID
                    INNER JOIN ". $this->employeeTable ." as e ON e.EmployeeID = ph.EmployeeID
                     ";
		
        // if(isset($_POST['orders'])) {
		// 	$sqlQuery .= 'ORDER BY '.$_POST['orders']['0']['column'].' '.$_POST['orders']['0']['dir'].' ';
		// } else {
		// 	$sqlQuery .= 'ORDER BY ph.PurchaseID DESC ';
		// }
        
		// if($_POST['length'] != -1) {
		// 	$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
		// }	
        $result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$purchaseData = array();	
		while( $purchase = mysqli_fetch_assoc($result) ) {			
			$productRow = array();
			 $productRow[] = $purchase['PurchaseDate'];
			 $productRow[] = $purchase['SupplierName'];
			 $productRow[] = $purchase['ProductName'];
             $productRow[] = $purchase['PurchaseNCrates'];
             $productRow[] = $purchase['PurchaseNHeads'];
             $productRow[] = $purchase['PurchasePWeight'];
             $productRow[] = $purchase['PurchaseStatus'];				 	
			 $productRow[] = '<div class="btn-group btn-group-sm"><button type="button" name="update" id="'.$purchase["PurchaseID"].'" class="btn btn-primary btn-sm rounded-0  update" title="Update"><i class="fa fa-edit"></i></button><button type="button" name="view" id="'.$purchase["PurchaseID"].'" class="btn btn-primary btn-sm rounded-0 border-dark  view" title="View"><i class="fa fa-eye"></i></button><button type="button" name="delete" id="'.$purchase["PurchaseID"].'" class="btn btn-danger btn-sm rounded-0  delete" title="Delete"><i class="fa fa-trash"></i></button></div>';
			$purchaseData[] = $productRow;
						
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$purchaseData
		);
		echo json_encode($output);		
	}
    public function SupplierDropdownList(){	
		$sqlQuery = "Select * from " .$this->supplierTable;
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';		
		while( $Supplier = mysqli_fetch_assoc($result) ) {	
			$dropdownHTML .= '<option  value="'.$Supplier["SupplierID"].'">'.$Supplier["SupplierName"].'</option>';
			
		}
		return $dropdownHTML;
	}
    public function ProductDropdownList(){	
		$sqlQuery = "Select * from " .$this->productTable;
		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$dropdownHTML = '';	
		while( $Product = mysqli_fetch_assoc($result) ) {	
			if(isset($_POST)){
				$dropdownHTML .= '<option id="'.$Product['CategoryID'].'" value="' .$Product["ProductID"].'" >'.$Product["ProductName"].'</option>';	
			
			}else{
			$dropdownHTML .= '<option  value="'.$Product["ProductID"].'">'.$Product["ProductName"].'</option>';	
			}
		}
		
		return $dropdownHTML;
	}
    public function addPurchase() {
		$CratesValue = $_POST['PurchaseNHeads'];
		if($CratesValue=="15"){
			$CratesValue= $CratesValue ." pcs";

		}else{
			$CratesValue= "1 bag";

		}
		
        $sqlInsert=" INSERT INTO ".$this->purchaseTable." (`PurchaseID`, `PurchaseNumber`, `SupplierID`, `ProductID`, `TruckID`, `EmployeeID`, `PurchaseNCrates`, `PurchaseNHeads`, `PurchasePWeight`, `PurchaseDate`, `PurchaseStatus`) 
                    VALUES ('','".$_POST['PurchaseNumber']."','".$_POST['Supplier']."','".$_POST['Product']."','". $_POST['TruckID'] ."','".$_SESSION['EmployeeID']."','".  $_POST['PurchaseNCrates'] ."','". $CratesValue ."','". $_POST['PurchasePWeight'] ."','". $_POST['PurchaseDate'] ."','') ";
        mysqli_query($this->dbConnect, $sqlInsert);
        echo 'New Purchase Added';

	}

	//================================================ORDER=====================================================================//

	public function listOrder(){		
			
		$sqlQuery = "SELECT od.OrderID, cu.CustomerName, od.OrderDate, COUNT(od.CratesNumber) as ProductQty, SUM(od.UnitPrice*od.Quantity) as Amount  FROM ". $this->orderDetailTable." as od
					INNER JOIN ". $this->customerTable ." as cu ON od.CustomerID=cu.CustumerID GROUP BY od.OrderID";
							
							// if(isset($_POST['orders'])) {
							// 	$sqlQuery .= 'ORDER BY '.$_POST['orders']['0']['column'].' '.$_POST['orders']['0']['dir'].' ';
							// } else {
							// 	$sqlQuery .= 'ORDER BY od.id ';
							// }
							
							// if($_POST['length'] != -1) {
							// 	$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
							// }	


		$result = mysqli_query($this->dbConnect, $sqlQuery);
		$numRows = mysqli_num_rows($result);
		$orderData = array();	
		while( $viewOrder = mysqli_fetch_assoc($result) ) {			
			$orderRow = array();
			
			$orderRow[] = $viewOrder['OrderID'];
			$orderRow[] = $viewOrder['CustomerName'];
			$orderRow[] = $viewOrder['OrderDate'];		
			$orderRow[] = $viewOrder['ProductQty'];
			$orderRow[] = $viewOrder['Amount'];	
			$orderRow[] = '<div class="btn-group btn-group-sm">
							
							<button type="button" name="view" id="'.$viewOrder["OrderID"].'" class="btn btn-primary btn-sm rounded-0  view" title="View">
							<i class="fa fa-eye"></i></button>
							
							</div>';	
			$orderData[] = $orderRow;
						
		}
		$output = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"  	=>  $numRows,
			"recordsFiltered" 	=> 	$numRows,
			"data"    			=> 	$orderData
		);
		echo json_encode($output);	
	}

	public function getTrucks(){
		
		$sqlSelect = "SELECT * FROM ". $this->truckTable ."";
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		$TruckIDHTML = '';		
		while( $Truck = mysqli_fetch_assoc($result) ) {	
			$TruckIDHTML .= '<option  value="'.$Truck["TruckID"].'">'.$Truck["TruckID"].'</option>';
			
		}
		return $TruckIDHTML;
	}

	public function getOrderDetails(){
		$sqlQuery = "
			SELECT * FROM ".$this->orderDetailTable." 
			WHERE OrderID = '".$_POST["OrderID"]."'";
			$result = mysqli_query($this->dbConnect, $sqlQuery);
			 $output = mysqli_fetch_array($result, MYSQLI_ASSOC);
			// echo json_encode($row);
			// $numRows = mysqli_num_rows($result);
			// $viewOrderData = array();	
			// while( $viewOrder = mysqli_fetch_assoc($result) ) {			
			// 	$viewOrderRow = array();
				
			// 	// $viewOrderRow[] = $viewOrder['OrderID'];
			// 	// $viewOrderRow[] = $viewOrder['ProductID'];
			// 	// $viewOrderRow[] = $viewOrder['Quantity'];		
				
			// 	// $viewOrderRow[] = '<div class="btn-group btn-group-sm">
								
			// 	// 				<button type="button" name="delete" id="'.$viewOrder["id"].'" class="btn btn-primary btn-sm rounded-0  delete" title="Delete">
			// 	// 				<i class="fa fa-trash"></i></button>
								
			// 	// 				</div>';	
			// 	$viewOrderData[] = $viewOrderRow;
							
			// }
			// $output = array(
			// 	"draw"				=>	intval($_POST["draw"]),
			// 	"recordsTotal"  	=>  $numRows,
			// 	"recordsFiltered" 	=> 	$numRows,
			// 	"data"    			=> 	$viewOrderData
			// );
			echo json_encode($output);	
	}


	// public function getProduct(){
		
	// 	$sqlSelect = "SELECT DISTINCT pu.ProductID,pr.ProductName FROM ". $this->purchaseTable ." as pu
	// 					INNER JOIN ". $this->productTable ." as pr ON pu.ProductID=pr.ProductID";
	// 	$result = mysqli_query($this->dbConnect, $sqlSelect);
	// 	$ProductHTML = '';		
	// 	while( $Product = mysqli_fetch_assoc($result) ) {	
	// 		$ProductHTML .= '<option  value="'.$Product["ProductID"].'">'.$Product["ProductName"].'</option>';
			
	// 	}
	// 	return $ProductHTML;
	// }


	public function getProductName(){
		$TruckID=$_POST['id'];
		$sqlSelect = "SELECT DISTINCT pu.ProductID,pr.ProductName,pu.TruckID FROM ". $this->purchaseTable ." as pu
						INNER JOIN ". $this->productTable ." as pr ON pu.ProductID=pr.ProductID where pu.TruckID = '". $TruckID ."'";
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		$getProductHTML='';
		while( $getProduct = mysqli_fetch_assoc($result) ) {	
			$getProductHTML .='<option value='.$getProduct["ProductID"].'  id='.$getProduct["TruckID"].'>'.$getProduct["ProductName"].' </option>';
			
		}
		
		// echo "<option></option><br/>" .$getProductHTML;
		echo  '<option></option><br/>'.$getProductHTML;
	}

	public function getProductWeight(){
		$ProductID = $_POST['ProductID'];
		$TruckID = $_POST['TruckID'];
		$sqlSelect="SELECT * FROM ". $this->purchaseTable ." WHERE TruckID='". $TruckID ."' AND ProductID='". $ProductID ."' AND PurchaseStatus=''";
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		$getWeightHTML = '';
		while( $getWeight = mysqli_fetch_assoc($result) ) {	
			$getWeightHTML .= '<option value="'.$getWeight["PurchaseNCrates"].'">'.$getWeight["PurchasePWeight"].'</option>';
			
		}
		echo "<option></option><br/>" .$getWeightHTML;

	}
	public function getPrice(){
		
		$sqlSelect="SELECT UnitPrice FROM ". $this->orderDetailTable." where  ProductID='". $_POST['ProductID'] ."' and CustomerID='". $_POST['CustomerID'] ."' Order by id desc LIMIT 1";
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		while( $row = mysqli_fetch_assoc($result) ) {	
			echo $row['UnitPrice'];
			
		}
		
		

	}

	public function getCustomer(){
		$sqlSelect="SELECT * FROM " . $this->customerTable ;
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		$getCustomerHTML ='';
		while( $getCustomer = mysqli_fetch_assoc($result) ) {	
			$getCustomerHTML .= '<option value="'.$getCustomer["CustumerID"].'">'.$getCustomer["CustomerName"].'</option>';
			
		}
		return $getCustomerHTML;


	}

	public function addOrder(){
		

		$sqlInsert="INSERT INTO ". $this->orderDetailTable." (`id`, `OrderID`, `CustomerID`, `OrderDate`, `ProductID`, `UnitPrice`, `Quantity`, `CratesNumber`, `TruckID`) 
						VALUES ('','". $_POST['OrdSI']."','". $_POST['getCustomer']."','". $_POST['OrdDate']."','". $_POST['getProduct']."','". $_POST['OrdPrice']."','".$_POST['OrdActual']."','". $_POST['OrdCrates']."','". $_POST['Truck']."')";
		$sqlUpdate = "UPDATE `purchase` SET `PurchaseStatus`='out' WHERE ProductID=". $_POST['getProduct'] ." AND PurchaseNCrates=". $_POST['OrdCrates'] ." AND TruckID='". $_POST['Truck'] ."'";
		
		mysqli_query($this->dbConnect, $sqlInsert);
		mysqli_query($this->dbConnect, $sqlUpdate);
		echo 'New Order Added';
		
	}

	public function checkoutOrder(){
	
		if(isset($_POST['id'])){
			
				$sqlSelect="SELECT ProductName, SUM(Quantity) as totalQTY,SUM(Quantity)*UnitPrice as TotalPrice FROM ". $this->orderDetailTable ." as od
							INNER JOIN ". $this->productTable ." as p ON od.ProductID=p.ProductID where od.OrderID='". $_POST['id'] ."'
							GROUP by od.ProductId" ;
					$result = mysqli_query($this->dbConnect, $sqlSelect);
					$numRows = mysqli_num_rows($result);
					$checkoutItem = array();	
					while( $viewOrder = mysqli_fetch_assoc($result) ) {			
						$orderRow = array();
						$orderRow[] = $viewOrder['ProductName'];
						$orderRow[] = $viewOrder['totalQTY'];
						$orderRow[] = $viewOrder['TotalPrice'];	
						$checkoutItem[] = $orderRow;
									
					}
					$output = array(
						"draw"				=>	intval($_POST["draw"]),
						"recordsTotal"  	=>  $numRows,
						"recordsFiltered" 	=> 	$numRows,
						"data"    			=> 	$checkoutItem
					);
					echo json_encode($output);

				}
				

		
	}

	public function payOrder(){

		$sqlInsert="INSERT INTO `payment`(`id`, `OrderID`, `CustomerID`, `EmployeeID`, `Date`, `TotalPrice`, `TotalPayment`, `TotalBalance`) 
					VALUES ('','". $_POST['PaySI']."','". $_POST['Customer']."','". $_SESSION['EmployeeID']."','". $_POST['PayDate']."','". $_POST['TPrice']."','". $_POST['PayCash']."','". $_POST['PayBal']."')";
		
		
		mysqli_query($this->dbConnect, $sqlInsert);
		echo 'checkout has been recorded!';
	}

	public function getCustomerBal(){
		$sqlSelect="SELECT * FROM `orders` where Balance <0 and CustomerID= '3301'";
		$result = mysqli_query($this->dbConnect, $sqlSelect);
		$getCustomerHTML =$result;
		$output = array(
				
			"data"    			=> 	$getCustomerHTML
		);
		echo json_encode($output);
					
	}

    

}
?>