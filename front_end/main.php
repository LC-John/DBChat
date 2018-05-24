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

			<div class="collapse navbar-collapse" id="navbar-collapse-01">
			    <ul class="nav navbar-nav navbar-right">

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> <?php echo (string)($_SESSION['username']); ?> <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <p class="text-info">
                                <?php echo (string)($_SESSION['username']); ?>
                            </p>
                            <p class="text-info">
                                ID: <?php echo (string)($_SESSION['userid']); ?>
                            </p>
                            <form action="user_rename.php" method="get"> 
                                <input type="submit" name="submit" class="btn btn-link" value="Rename" /> 
                            </form>
                            <form action="user_password.php" method="get"> 
                                <input type="submit" name="submit" class="btn btn-link" value="Password" /> 
                            </form>
                            <form action="user.php" method="post"> 
                                <input type="submit" name="submit" class="btn btn-link" value="Leave" /> 
                            </form>
                            <form action="user.php" method="post"> 
                                <input type="submit" name="submit" class="btn btn-default" value="Purge" /> 
                            </form> 
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> My <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="my.php" method="post">
                                <input type="submit" name="submit" class="btn btn-link" value="Friend" /><br>
                                <input type="submit" name="submit" class="btn btn-link" value="Group" /><br>
                                <input type="submit" name="submit" class="btn btn-link" value="Application" />
                            </form>
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Friend <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="friend.php" method="post">
                                User / application ID<br>
                                <input type="text" name="id" value="" /><br><br>
                                <input type="submit" name="submit" class="btn btn-link" value="Delete friend" />
                                <input type="submit" name="submit" class="btn btn-link" value="Friend application" /><br>
                                <input type="submit" name="submit" class="btn btn-link" value="Accept application" /><br>
                                <input type="submit" name="submit" class="btn btn-link" value="Refuse application" />
                            </form>
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Join <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="session_join.php" method="post"> 
                                Session ID<br>
                                <input type="text" name="sessid" value="" /><br><br>
                                Session password<br>
                                <input type="text" name="password" value="" /><br><br>
                                <input type="submit" name="submit" class="btn btn-primary" value="Join" />
                            </form>
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Search <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="search.php" method="post"> 
                                Search for<br>
                                <input type="text" name="name" value="" /><br><br>
                                <input type="submit" name="submit" class="btn btn-link" value="User" />
                                <input type="submit" name="submit" class="btn btn-link" value="Group" /> 
                            </form>
                        </div>
                    </li>

                    <li role="presentation"><a href="main.php" target="_self"></a></li>
                    <li role="presentation"><a href="main.php" target="_self"></a></li>

                </ul>
			</div><!-- /.navbar-collapse -->
		</nav>

        <div class="container-fluid" style = "padding-top: 50px; background-color: rgba(0, 0, 0, 0);">
            <div class="row" style="height:400px;">
                <div class="col-lg-2 btn-group-vertical" style="padding:0px; height:100%">
                    <div style="height:80px; border-bottom:5px solid #000000; border-top:15px solid #000000; text-align:center; background-color: rgba(0, 0, 0, 0);  ">
                        <h4><dl class="text-muted">Session</dl></h4>
                    </div>
                    <div class="row pre-scrollable" style="max-height: 100%;">
                    <div class="divider-vertical"></div>
<?php
    $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
    if (!$conn)
    {
        echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
    }
    else
    {
        $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
        $result=mysqli_query($conn, "CALL SESSION_GET_MY_SESSION($_SESSION[userid], '$_SESSION[password]', $tmp_granted);");
        while ($row = mysqli_fetch_array($result))
        {
            echo '<form action="session_main.php" method="post">';
            echo '<input type="hidden" name="id" value="';
            echo $row['SID'];
            echo '" />';
            echo '&nbsp&nbsp&nbsp&nbsp<input type="submit" name="submit" class="btn btn-link" value="';
            echo $row['SNAME'];
            echo '" />';
            echo '<br>';
            echo '</form>';
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
                    </div>

                    <br>
                    <form action="session_create.php" method="get">
                        <input type="submit" name="submit" class="btn btn-link" value="Create group session" />
                        <input type="submit" name="submit" class="btn btn-link" value="Create private session" /> 
                    </form>
                </div>
            </div>
        </div>

	</body>
</html>
