<?php

	// var_dump($_SERVER); exit(0); # shows HTTP request data

    require '../../database/database.php';
 
    if ( !empty($_POST)) {
        // keep track validation errors
        $nameError = null;
        $emailError = null;
        $mobileError = null;
         
		// DANGER: Code below may be vulnerable to HTML injection
		
        // keep track post values
		// Code below is vulnerable to HTML injection
        $name = $_POST['name'];
        $email = $_POST['email'];
        $mobile = $_POST['mobile'];
		// Code below protects against HTML injection
		$name = htmlspecialchars($_POST['name']);
        $email = htmlspecialchars($_POST['email']);
        $mobile = htmlspecialchars($_POST['mobile']);
         
        // validate input
        $valid = true;
		
        if (empty($name)) {
            $nameError = 'Please enter Name';
            $valid = false;
        }
         
        if (empty($email)) {
            $emailError = 'Please enter Email Address';
            $valid = false;
			
        } else if ( !filter_var($email,FILTER_VALIDATE_EMAIL) ) {
            $emailError = 'Please enter a valid Email Address';
            $valid = false;
        }
         
        if (empty($mobile)) {
            $mobileError = 'Please enter Mobile Number';
            $valid = false;
        }
         
		// DANGER: Code below may be vulnerable to SQL injection
		
        // insert data
        if ($valid) {
            $pdo = Database::connect();
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			// Code below protects against SQL injection
            $sql = "INSERT INTO customers233 (name,email,mobile) values(?, ?, ?)";
			// Code below is vulnerable to SQL injection
            //$sql = "INSERT INTO customers233 (name,email,mobile)";
			//$sql .= " values('$name','$email','$mobile')";
            $q = $pdo->prepare($sql);
            $q->execute(array($name,$email,$mobile));
			//$q->execute(array()); # For vulnerable code use empty array
            Database::disconnect();
            header("Location: index.php");
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link   href="css/bootstrap.min.css" rel="stylesheet">
    <script src="js/bootstrap.min.js"></script>
</head>
 
<body>
    <div class="container">
     
		<div class="span10 offset1">
			<div class="row">
				<h3>Create a Customer</h3>
			</div>
	 
			<form class="form-horizontal" action="create.php" method="post">
			  <div class="control-group <?php echo !empty($nameError)?'error':'';?>">
				<label class="control-label">Name</label>
				<div class="controls">
					<input name="name" type="text"  placeholder="Name" value="<?php echo !empty($name)?$name:'';?>">
					<?php if (!empty($nameError)): ?>
						<span class="help-inline"><?php echo $nameError;?></span>
					<?php endif; ?>
				</div>
			  </div>
			  <div class="control-group <?php echo !empty($emailError)?'error':'';?>">
				<label class="control-label">Email Address</label>
				<div class="controls">
					<input name="email" type="text" placeholder="Email Address" value="<?php echo !empty($email)?$email:'';?>">
					<?php if (!empty($emailError)): ?>
						<span class="help-inline"><?php echo $emailError;?></span>
					<?php endif;?>
				</div>
			  </div>
			  <div class="control-group <?php echo !empty($mobileError)?'error':'';?>">
				<label class="control-label">Mobile Number</label>
				<div class="controls">
					<input name="mobile" type="text"  placeholder="Mobile Number" value="<?php echo !empty($mobile)?$mobile:'';?>">
					<?php if (!empty($mobileError)): ?>
						<span class="help-inline"><?php echo $mobileError;?></span>
					<?php endif;?>
				</div>
			  </div>
			  <div class="form-actions">
				  <button type="submit" class="btn btn-success">Create</button>
				  <a class="btn" href="index.php">Back</a>
				</div>
			</form>
		</div>
                 
    </div> <!-- /container -->
  </body>
</html>
