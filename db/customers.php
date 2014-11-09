<?php 


require_once('validator.php');

$mysqli = new mysqli("dba", "root", "", "project4");
if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	if(isset($_SERVER['REQUEST_METHOD']) == 'POST' && isset($_POST['id']) == 1) {
		$validatorFact = new ValidatorFactory();
// should have validated the id
// l/u prepared statements in addition to validators		
		$id = $_POST['id'];

		if(isset($_POST['firstname']) == 1) {
			$newfirstname = $_POST['firstname'];
			$validator = $validatorFact->createValidator('name');
			try {
				$validator->validate($newfirstname);
				// incorrect way to put into array... see the three below
				// $updateinfo[] = 'firstname' . '=' . "'$newfirstname'";
				$updateinfo['firstname'] = $newfirstname;
			} catch (ValidationException $e) {
			}
		}

		if(isset($_POST['lastname']) == 1) {
			$newlastname = $_POST['lastname'];
			$validator = $validatorFact->createValidator('name');
			try {
				$validator->validate($newlastname);
				$updateinfo['lastname'] =  $newlastname;
			} catch (ValidationException $e) {
			}
		}

		if(isset($_POST['dateofbirth']) == 1) {
			$newdateofbirth = $_POST['dateofbirth'];
			$validator = $validatorFact->createValidator('dateofbirth');
			try {
				$validator->validate($newdateofbirth);
				$updateinfo['dateofbirth'] =  $newdateofbirth;
			} catch (ValidationException $e) {
			}
		}

		if(isset($_POST['gender']) == 1) {
			$newgender = $_POST['gender'];
			$validator = $validatorFact->createValidator('gender');
			try {
				$validator->validate($newgender);
				$updateinfo['gender'] =  $newgender;
			} catch (ValidationException $e) {
			}
		}

		$updatesql = "UPDATE customers SET ";
		// $updatesql .= implode(', ', $updateinfo);
		//had the array setup prepped for sql with the '' (see firstname above)
		// was issue, because then not a clean array and not usable for anything else.
		// The below converts it to usable for mysql and adds single quotes and comma.
		//the substr takes off the extra comma (no need for logic to do so).
		foreach($updateinfo as $k => $v) {
			$updatesql .= $k . "=" . "'" . $v . "',";
		}
		// takes off extra comma
		$updatesql = substr($updatesql, 0, -1);
		$updatesql .= " WHERE id = '{$id}'";
		$mysqli->query($updatesql);
	}

	$sql = "
			SELECT 
				customers.firstname,
				customers.lastname,
				customers.id
			FROM customers
			";
	$results = $mysqli->query($sql);
}
?>

 <!DOCTYPE html>
 <html lang="en">
 <head>
 	<meta charset="UTF-8">
 	<title>Home</title>
 </head>
 <body>
 	<div><h3><a href="index.php">Home</a></h3></div>
 	<?php if(isset($error) == 1) { ?>
 		
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
	<?php } else { ?>
 	<h1>Customers</h1>
 		<table>
			<?php while ($row = $results->fetch_assoc()) {  ?>
	 		<tr>
		 		<td> <?= $row['firstname'] . ' ' . $row['lastname'] ?> </td>
			 	<td><a href="newinvoice.php?id=<?=$row['id'] ?>">New Invoice</a></td>
				<td><a href="editcustomer.php?id=<?=$row['id'] ?>">Edit Customer</a></td>
				<td><a href="removecustomer.php?id=<?=$row['id'] ?>">Remove Customer</a></td>
			</tr>
		<?php } ?>
		</table>
 		<?php } ?>
 </body>
 </html>