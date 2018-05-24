<!DOCTYPE html>
<?php session_start(); ?>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title> DBChat </title>
		<!-- Loading Bootstrap -->
    	<link href="css/css/bootstrap.min.css" rel="stylesheet">
    	<!-- Loading Flat UI -->
   		<link href="css/css/flat-ui.min.css" rel="stylesheet">
		<!--<link href="css/docs.css" rel="stylesheet">--> 
		<script src="js/jquery.min.js"></script>
		<script src="js/flat-ui.min.js"></script>
		<script src="js/d3.min.js"></script>
		<style>
			body {  
				background-image: url("img/bg.png");  
				background-position: center 0;  
				background-repeat: no-repeat;  
				background-attachment: fixed;  
				background-size: cover;  
				-webkit-background-size: cover;  
				-o-background-size: cover;  
				-moz-background-size: cover;  
				-ms-background-size: cover;  
            }  
		</style>
	</head>
	<body>
		<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-01">
      			<span class="sr-only">Toggle navigation</span>
    			</button>
     			<a class="navbar-brand" href="main.php" target="_self"> Home </a>
			</div>
		</nav>

		<div class="container" style="margin-top: 80px; margin-bottom: 100px;">
            <div class="row">
                <div class="container">
                    <div class="col-md-4"> </div>
                    <div class="col-md-8">
                        <p><dl class="text-muted">
                        	<form action="session_create_action.php" method="post"> 


<?php
	if ($_GET['submit'] == 'Create group session')
    {
    	echo 'Session name<br>';
        echo '<input type="text" name="sessname" value="" /><br><br>';
        echo 'Session password<br>';
        echo '<input type="password" name="password" value="" /><br><br>';
        echo '<input type="submit" name="submit" class="btn btn-info" value="Create group session" />';
    }
    elseif ($_GET['submit'] == 'Create private session')
    {
    	echo 'Friend ID<br>';
        echo '<input type="text" name="userid" value="" /><br><br>';
        echo '<input type="submit" name="submit" class="btn btn-info" value="Create private session" />';
    }
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>
							</form>
                        </dl></p>
                    </div>
                </div>
            </br>

	</body>
</html>