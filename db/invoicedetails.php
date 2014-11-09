<?php 

$total = 0;

if(isset($_GET['invoice']) == 1) {
	$invoicenum = $_GET['invoice'];
}

$mysqli = new mysqli("dba", "root", "", "project4");
if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	if(isset($invoicenum) == 1 && is_numeric($invoicenum) && $invoicenum > 0){
		$sql = "SELECT * FROM invoices WHERE invoice = " . $invoicenum  ;	
	} 

	$sql = "
			SELECT DISTINCT 
				quantity,
				price,
				name 
			FROM 
				invoices, 
				line_items, 
				products, 
				customers
			WHERE 
				invoices.customer_id = customers.id 
				AND line_items.invoice_id = invoices.invoice 
				AND line_items.product_id = products.id 
				AND invoices.invoice = '{$invoicenum}' 
				";
	$results = $mysqli->query($sql);
	// starts the connection, doesn't fetch anything yet
	$output = '';
	$title = $invoicenum;
	
	// different query for the name...  
	$namesql = "SELECT DISTINCT firstname, lastname
				FROM customers, invoices 
				WHERE customers.id = invoices.customer_id 
				AND invoices.invoice = '{$invoicenum}' 
				";
	$nameresults = $mysqli->query($namesql);
	$name = $nameresults->fetch_assoc();
	$fullname = $name['firstname'] . ' ' . $name['lastname'];
}

?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Invoice Details</title>
 </head>
 <body>
 	<div><h1><a href="invoices.php">Back</a></h1></div>
 	<?php if(isset($error) ==1 && $error) { ?>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php	} else {  ?>
 	<h1>Invoice No. <?= $title ?></h1>
	<h3>Customer <?= $fullname ?> </h3>
	<table>
		<tr>
			<td>Quantity</td>
			<td>Item</td>
			<td>Price</td>
			<td>Subtotal</td>
		</tr>

	 	<?php 
			// fetch assoc goes AND gets what is queried
	 		while ($row = $results->fetch_assoc()) {  
		 		$subtotal = $row['quantity'] * $row['price'];
				$total += $subtotal;
	 		?>

				<tr>
					<td> <?= $row['quantity'] ?> </td>
					<td> <?= $row['name'] ?> </td>
					<td> <?= $row['price'] ?> </td>
					<td> <?= $subtotal ?> </td>
					<!-- <td><a href="invoices.php" >Remove</a></td> -->
				</tr>
			<?php } ?>
				<tr><td></td><td></td><td><b>Total</b></td><td><b> <?= $total ?> </b></td></tr>
		</table>

	<?php } ?>
 </body>
 </html>