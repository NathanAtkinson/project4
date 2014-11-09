<?php 


function newInvoiceNumber($custid, $mysqli) {
	$insertinvoice ='INSERT INTO invoices (saledate, customer_id) 
						VALUES ("2014-10-29", "' . $custid . '")
						';
	$mysqli->query($insertinvoice);
	return $mysqli->insert_id;
}

function removeLineItem($invnumber, $prodid, $mysqli) {
	$removequery = "DELETE FROM line_items
					WHERE line_items.invoice_id = '{$invnumber}'
					AND line_items.product_id = '{$prodid}'
					";
	$mysqli->query($removequery);
}

function getCustomerName($custid, $mysqli) {
	$sqlname = "SELECT firstname, lastname
				FROM customers
				WHERE customers.id = '{$custid}'
				";
	$customerrecord = $mysqli->query($sqlname);
	$customerrecord = $customerrecord->fetch_assoc();
	return $customerrecord['firstname'] . ' ' . $customerrecord['lastname'];
}

function addLineItem($invnumber, $prodid, $quantity, $mysqli) {
	$insertitem = 'INSERT INTO line_items (invoice_id, product_id, quantity) 
					VALUES ("' . $invnumber .'", "' . $prodid . '", "' . $_POST['quantity'] . '") 
					ON DUPLICATE KEY UPDATE quantity=VALUES(quantity)
					';
	$mysqli->query($insertitem);
}

function getLineItems($invnumber, $mysqli) {
	$invoicesql = "SELECT DISTINCT quantity, name, price, product_id
					FROM invoices, line_items, products 
					WHERE line_items.invoice_id = '{$invnumber}' 
					AND invoices.invoice = '{$invnumber}' 
					AND products.id = line_items.product_id
					";
	return $mysqli->query($invoicesql);
}

function getProducts($mysqli) {
	$productsql = "SELECT * FROM products;";
	return $mysqli->query($productsql);
}


if (isset($_GET['id']) == 1) {
	$custid = $_GET['id'];
} elseif (isset($_POST['id']) == 1) {
	$custid = $_POST['id'];
} else {
	$error = true;
}

$mysqli = new mysqli("dba", "root", "", "project4");

if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	if(isset($_GET['invnumber']) == 0 && isset($_POST['invnumber']) == 0  ) {
		$customername = getCustomerName($custid, $mysqli);
		$invnumber = newInvoiceNumber($custid, $mysqli);
		$productlist = getProducts($mysqli);
	} else if (isset($_GET['remove']) == 1 && isset($_GET['invnumber']) == 1 && isset($_GET['customername']) == 1) {
		$prodid = $_GET['remove'];
		$invnumber = $_GET['invnumber'];
		$customername = $_GET['customername'];
		removeLineItem($invnumber, $prodid, $mysqli);
		$invoiceinfo = getLineItems($invnumber, $mysqli);
		$productlist = getProducts($mysqli);
	} else if (isset($_POST['invnumber']) == 1 && isset($_POST['customername']) == 1 && isset($_POST['productid']) == 1 && isset($_POST['quantity']) == 1) {
		$invnumber = $_POST['invnumber'];
		$customername = $_POST['customername'];
		$prodid = $_POST['productid'];
		$quantity = $_POST['quantity'];
		addLineItem($invnumber, $prodid, $quantity, $mysqli);
		$invoiceinfo = getLineItems($invnumber, $mysqli);
		$productlist = getProducts($mysqli);
	}
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
 	<div><h1><a href="customers.php">Back</a></h1></div>
 	<?php if(isset($error) ==1 && $error) { ?>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php	} else {  ?>
 		<h1>Invoice No. <?=$invnumber ?></h1>
 		<h3>Customer: <?=$customername ?></h3>
		<form action="newinvoice.php" method="POST">
 	 	<table>
	 	 	<tr>

				<td>Quantity</td>
				<td>Item</td>
				<td>Cost</td>
				<td>Subtotal</td>
			</tr>
					<input type="hidden" name="invnumber" value="<?= $invnumber?>">
					<input type="hidden" name="id" value="<?= $custid ?>">
					<input type="hidden" name="customername" value="<?= $customername ?>">
				<?php 

					while(isset($invoiceinfo) == 1 && $info = $invoiceinfo->fetch_assoc()) {
						$product_id = $info['product_id'];
						$subtotal = $info['quantity'] * $info['price'];
						$total += $subtotal;
					?>
					<tr>
						<td> <?=$info['quantity'] ?></td>
						<td> <?=$info['name'] ?></td>
						<td> <?=$info['price'] ?></td>
						<td> <?=$info['quantity'] * $info['price'] ?></td>
						<td><a href="newinvoice.php?id=<?=$custid ?>&invnumber=<?= $invnumber?>&remove=<?= $info['product_id']?>&customername=<?= $customername?>">Remove</a></td>
					</tr>
				<?php } ?>
				
				<tr>
					<td></td><td></td><td><b>Total</b></td><td><b> <?= $total ?> </b>
				</tr>

		</table>
		
		<br>
		<table>
			<tr>
				<td>Item</td>
				<td>Quantity</td>
			</tr>
			<tr>
				<td>
					<select name="productid">
				<?php while ($product=$productlist->fetch_assoc()) { ?>
						<option  value=" <?= $product['id'] ?>"><?= $product['name']?></option>
				<?php } ?>
					</select>
				</td>
				<td><input type="number" name="quantity" min=1></td>
				<td><button>Add</button></td>
			</tr>

		</table>
		</form>
		<?php } ?>
 </body>
 </html>