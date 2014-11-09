<?php 



$mysqli = new mysqli("dba", "root", "", "project4");
if ($mysqli->connect_errno) {
	/*echo "Failed to connect to MySQL: (" . $mysqli->connect_errno 
		. ")" .	$mysqli->connect_error;*/
	$error = true;
} else {
	$sql = "SELECT name, description, price
			FROM products
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
 	<div><h1><a href="index.php">Home</a></h1></div>
 	<?php if(isset($error) ==1 && $error) { ?>
		<h1>There was an error with your request.  Sorry for the inconvenience, please try again later.</h1>
 	<?php	} else {  ?>
 	<h1>Items</h1>
 	<table>
		<tr>
			<td>Name</td>
			<td>Description</td>
			<td>Price</td>
		</tr>
 	<!-- //dynamically create list of products here -->
 	 	<?php while ($row = $results->fetch_assoc()) { ?>
		<tr>
			<td><?= $row['name'] ?></td>
			<td><?= $row['description'] ?></td>
			<td><?= $row['price'] ?></td>
		</tr>
		<?php } ?>
	</table>
	<?php } ?>

 </body>
 </html>