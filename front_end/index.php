<!DOCTYPE html>
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
				background-image: url("img/bg_home.png");  
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
     			<a class="navbar-brand" href="index.php" target="_self"> Home </a>
			</div>

			<div class="collapse navbar-collapse" id="navbar-collapse-01">
			    <ul class="nav navbar-nav navbar-right">

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Sign up <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;">
                            <form action="signup.php" method="post"> 
                                User name:<br /> 
                                <input type="text" name="username" value="" /> 
                                <br /><br /> 
                                Password:<br /> 
                                <input type="password" name="password" value="" /> 
                                <br /><br /> 
                                <input type="submit" name="submit" class="btn btn-primary" value="signup" /> 
                            </form> 
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Sign in <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;">
                            <form action="signin.php" method="post"> 
                                User ID:<br /> 
                                <input type="text" name="userid" value="" /> 
                                <br /><br /> 
                                Password:<br /> 
                                <input type="password" name="password" value="" /> 
                                <br /><br /> 
                                <input type="submit" name="submit" class="btn btn-primary" value="signin" /> 
                            </form> 
                        </div>
                    </li>
                    
                    <li role="presentation"><a href="aboutus.html" target="_self">About us</a></li>
                    <li role="presentation"><a href="aboutus.html" target="_self"></a></li>

                </ul>
			</div><!-- /.navbar-collapse -->
		</nav>

	</body>
</html>
