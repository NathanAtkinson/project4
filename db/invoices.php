<?php 

$mysqli = new mysqli("dba", "root", "", "project4");
if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	$sql = "SELECT 
				invoices.invoice,
				customers.firstname,
				customers.lastname,
				products.price,
				line_items.quantity,
				SUM(products.price * line_items.quantity) AS total
			FROM 
				invoices, 
				customers, 
				products, 
				line_items 
			WHERE invoices.customer_id = customers.id 
			AND line_items.invoice_id = invoices.invoice 
			AND products.id = line_items.product_id
			GROUP BY invoices.invoice
			";

	$results = $mysqli->query($sql);
	$total = 0;
}

?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Home</title>
 </head>
 <body>
 	<div><h1><a href="index.php">Home</a></h1></div>
	<?php if(isset($error) ==1 && $error) { ?>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php	} else {  ?>

 	<h1>Invoices</h1>
 	 	<table>
	 	 	<tr>
				<td>Invoice #</td>
				<td>Customer</td>
				<td>Total</td>
			</tr>

	 	 	<?php while ($row = $results->fetch_assoc()) { 
				$subtotal = $row['total']; 
				$total += $subtotal; ?>
			<tr>
				<td> <?= $row['invoice'] ?> </td>
				<td> <?= $row['firstname'] . ' ' . $row['lastname'] ?> </td>
				<td> <?= $subtotal ?> </td>
				<!-- had an extra " in the below and it wasn't passing along the invoice number. In HTML mode
				remember that I'm not concat'ing strings -->
				<td><a href="invoicedetails.php?invoice=<?=$row['invoice'] ?>">Details</a></td>
			</tr>
			<?php }  ?>

			<tr>
				<td></td>
				<td><b>Total</b></td>
				<td><b> <?= $total ?></b></td>
			</tr>
		</table>
	<?php } ?>
 </body>
 </html>