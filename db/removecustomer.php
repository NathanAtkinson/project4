<?php 

if(isset($_GET['id']) == 1) {
	$customerid = $_GET['id'];
}

$mysqli = new mysqli("dba", "root", "", "project4");
if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	$sql = "DELETE FROM	customers
			WHERE customers.id = $customerid
			LIMIT 1
			";

	$mysqli->query($sql);
	$sql = "SELECT invoices.invoice
			FROM invoices
			WHERE invoices.customer_id = $customerid
			";
	$invoicestodelete = $mysqli->query($sql);

	$sql = "DELETE FROM	invoices
			WHERE customer_id = $customerid
			";
	$mysqli->query($sql);

	while($item = $invoicestodelete->fetch_assoc()) {
		$sql = "DELETE FROM	line_items
				WHERE invoice_id = '{$item['invoice']}'
				";
		$mysqli->query($sql);
	}
}
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Home</title>
 </head>
 <body>
 	<div><h3><a href="customers.php">Back</a></h3></div>
 	<?php if(isset($error) ==1 && $error) { ?>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php	} else {  ?>
 	<h1>Customer Removed</h1>
 		<table>
	 		<tr>
				
			</tr>
		</table>
	<?php } ?>
 </body>
 </html>