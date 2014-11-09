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
	if(isset($customerid) == 1 && is_numeric($customerid) && $customerid > 0){
		$sql = "SELECT *
				FROM customers 
				WHERE id = '{$customerid}'
				";
		$results = $mysqli->query($sql);
		if(!$exists = $results->fetch_assoc()) {
			$error = true;
		}

		//checks if passed id is not higher than the total id's in DB
		/*$sql2 = "SELECT count(id) FROM customers;";
		$idcount = $mysqli->query($sql2);
		$idcount = $idcount->fetch_assoc();*/
		// had off by one error.  Added =
		// echo ($idcount['count(id)']);
		// echo $customerid;
		/*ID count did not work because if some ID was deleted, it would be invalid. 
		Changed to different check above*/
			$results = $mysqli->query($sql);
			$customer = $results->fetch_assoc();	
		
	} else {
		$error = true;
	}

	$customername = $customer['firstname'] . ' ' . $customer['lastname'];
		
}
 ?>

 <!DOCTYPE html>
 <html lang="en">
 <head>

 	<meta charset="UTF-8">
 	<title>Home</title>
 </head>
 <body>
 	<?php if(isset($error) == 1) { ?>
 		<h3><a href="index.php">Home</a></h3>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php } else { ?>
 	<!-- <div><h3><a href="index.php">Home</a></h3></div> -->
 	<!-- when had $customer['firstname'] . ' ' . $customer['lastname'] in a while statement, weren't accessible -->
 	<h1>Edit <?= $customername ?> </h1>
 	<!-- //dynamically output list of customers here -->
 	<form action="customers.php" method="post">
 	<table>
 		<tr>
 			<td><label>First Name: </label></td>
 			<td><input type="text" name="firstname" value="<?=$customer['firstname'] ?>"></td>
 		</tr> 
		<tr>
			<td><label>Last Name: </label></td>
			<td><input type="text" name="lastname" value="<?=$customer['lastname']?>"></td>
		</tr>
		<tr>
			<td><label>Date of Birth: </label></td>
			<td><input type="text" name="dateofbirth" value="<?=$customer['dateofbirth'] ?>"></td>
		</tr>
		<tr>
			<td><label>Gender: </label></td>
			<td><input type="text" name="gender" value="<?= $customer['gender'] ?>"></td>
		</tr>
		<tr>
			<td><button>Update</button></td>
			<!-- <td><a href="customers.php">Update **TODO**</a> </td> -->
			<td><a href="customers.php">Cancel</a></td>
		</tr> 
		<input type="hidden" name="id" value="<?= $customerid ?>">
			<!-- need to grab the values from all of the fields and send over when updated -->
	</table>
	</form>
	<?php } ?>
 </body>
 </html>