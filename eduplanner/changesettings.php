<?php  
	
	session_start();

	if (!isset($_SESSION['username'])) {
		header('Location: index.php');
		exit();
	}

	include 'includes/db_connect.php';
	include 'includes/security.php';

	$index_number = $_SESSION['index_number'];

	if (isset($_POST['submit'])) {

		$new_first_name 	= trim($_POST['first_name']);
		$new_last_name 		= trim($_POST['last_name']);
		$new_middle_name 	= trim($_POST['middle_name']);	
		$gender 			= trim($_POST['gender']);
		$old_password 		= trim($_POST['old_password']);
		$new_password 		= trim($_POST['new_password']);
		$new_student_number = trim($_POST['student_number']);
		$new_index_number 	= trim($_POST['index_number']);
		$new_email 			= trim($_POST['email']);

		if (empty($_POST['middle_name'])) {
			$new_full_name = $new_last_name.' '.$new_first_name;
		} else {
			$new_full_name = $new_last_name.' '.$new_first_name.' '.$new_middle_name;
		}

		if (!empty($old_password) && !empty($new_password) && !empty($new_first_name) && !empty($new_last_name) && !empty($new_student_number) && !empty($new_index_number) && !empty($new_email)) {
			
			$old_password = md5($old_password);
			$new_password = md5($new_password);

			$query = $db->query("SELECT * FROM students WHERE password = '$old_password' AND index_number = $index_number");

			if ($query->rowCount() === 1) {
				
				try {
					$sql = $db->query(" UPDATE students SET first_name = '$new_first_name', last_name = '$new_last_name', middle_name = '$new_middle_name', full_name = '$new_full_name', gender= '$gender', password = '$new_password', student_number = $new_student_number, index_number = $new_index_number, email = '$new_email', updated_at = NOW() WHERE index_number = $index_number ");
					$_SESSION['update_success'] = "You have successfully updated your account details.";
				} catch(PDOException $e) {
					echo "Email, student number or index number is already taken";
				}
						
			} else {
				$_SESSION['update_fail'] = "Password doesn't exist";
				echo "password not found";
			}
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale = 1.0">
	<title>Eduplanner - Change Settings</title>
	<?php include 'includes/stylesheets.php'; ?>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="main.php" class="navbar-brand"><img src="assets/images/envelope-w.png" alt="" class="img-responsive"></a>
			</div>
			<div id="myNavbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					<li><a href="main.php">Home <span class="fa fa-home"></span></a></li>
					<li><a href="dashboard.php">Dashboard <span class="fa fa-dashboard"></span></a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
					<li class="dropdown current">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo $_SESSION['username']; ?> <span class="fa fa-user"></span></a>
						<ul class="dropdown-menu">
							<li><a href="settings.php">Settings</a></li>
							<li><a href="logout.php">Log Out</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>
	<div id="wrapper">
		<div class="container">
			<div class="row">
				<div class="col-lg-8 col-md-8 col-sm-10 section">
					<h4><strong>Change Settings</strong></h4>
					<?php 
						$query = $db->query("SELECT * FROM students WHERE index_number = $index_number");
						if ($query->rowCount() === 1) {
							while ($row = $query->fetch(PDO::FETCH_OBJ)) {						
					?> 
						<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
							<table class="table table-condensed">
								
								<tr>
									<td><strong>Name</strong></td>
									<td>
										<div class="form-group">
											<label for="first_name">First Name:</label>
											<input type="text" class="form-control" value="<?php echo $row->first_name; ?>" name="first_name" id="first_name" required>
										</div>
										<div class="form-group">
											<label for="last_name">Last Name:</label>
											<input type="text" class="form-control" value="<?php echo $row->last_name; ?>" name="last_name" id="last_name" required>
										</div>
										<div class="form-group">
											<label for="middle_name">Middle Name:</label>
											<input type="text" class="form-control" value="<?php echo $row->middle_name; ?>" name="middle_name"  id="middle_name">
										</div>
									</td>
								</tr>

								<tr>
									<td><strong>Gender</strong></td>
									<td>
										<div class="form-group">
											<input type="text" name="gender" id="gender" value="<?php echo $row->gender; ?>" class="form-control" required>
										</div>
									</td>
								</tr>

								<tr>
									<td><strong>Index No.</strong></td>
									<td>
										<div class="form-group">
											<input type="text" class="form-control" value="<?php echo $row->index_number; ?>" name="index_number" id="index_number" required>
										</div>
									</td>
								</tr>

								<tr>
									<td><strong>Email</strong></td>
									<td>
										<div class="form-group">
											<input type="email" class="form-control" value="<?php echo $row->email; ?>" name="email" id="email" required>
										</div>
									</td>
								</tr>

								<tr>
									<td><strong>Password</strong></td>
									<td>
										<div class="form-group">
											<label for="old_password">Old Password:</label>
											<input type="password" class="form-control" name="old_password" id="old_password" required>
										</div>
										<div class="form-group">
											<label for="new_password">New Password:</label>
											<input type="password" class="form-control" name="new_password" id="new_password" required>
										</div>
									</td>
								</tr>

								<tr>
									<td></td>
									<td>
										<div class="form-group">
											<input type="submit" name="submit" value="Submit Changes" class="btn btn-warning">
										</div>
									</td>
								</tr>

							</table>
						</form>
					<?php  
							}
						}
					?>
				</div>
			</div>
			
		</div>
	</div>

	<?php include 'includes/footer.php'; ?>
	<?php include 'includes/scripts.php'; ?>

</body>
</html>