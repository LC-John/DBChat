<!DOCTYPE html>
<?php
    session_start();
    if (isset($_POST['submit']))
    {
        $_SESSION['sessname'] = $_POST['submit'];
        $_SESSION['sessid'] = $_POST['id'];
    }
?>
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
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Message <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="message.php" method="post"> 
                                Message ID<br>
                                <input type="text" name="msgid" value="" /><br><br>
                                <input type="hidden" name="name" value=<?php echo $_SESSION['sessname']; ?> />
                                <input type="hidden" name="id" value=<?php echo $_SESSION['sessid']; ?> />
                                <input type="submit" name="submit" class="btn btn-link" value="Withdraw" />
                            </form>
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Invite <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="message.php" method="post"> 
                                Friend user ID<br>
                                <input type="text" name="userid" value="" /><br><br>
                                <input type="hidden" name="name" value=<?php echo $_SESSION['sessname']; ?> />
                                <input type="hidden" name="id" value=<?php echo $_SESSION['sessid']; ?> />
                                <input type="submit" name="submit" class="btn btn-link" value="Invite" />
                            </form>
                        </div>
                    </li>

                    <li class="divider-vertical"></li>
                    <li class="dropdown">
                        <a class="dropdown-toggle" href="#" data-toggle="dropdown"> Session <strong class="caret"></strong></a>
                        <div class="dropdown-menu" style="padding: 15px; padding-bottom: 15px;"> 
                            <form action="manager.php" method="post"> 
                                <input type="hidden" name="name" value=<?php echo $_SESSION['sessname']; ?> />
                                <input type="hidden" name="id" value=<?php echo $_SESSION['sessid']; ?> />
                                <input type="submit" name="submit" class="btn btn-link" value="Manager" />
                            </form>
                            <form action="session_leave.php" method="post"> 
                                <input type="hidden" name="name" value=<?php echo $_SESSION['sessname']; ?> />
                                <input type="hidden" name="id" value=<?php echo $_SESSION['sessid']; ?> />
                                <input type="submit" name="submit" class="btn btn-link" value="Leave" />
                            </form>
                        </div>
                    </li>

                    <li role="presentation"><a href="session_main.php" target="_self">Refresh</a></li>
                    &nbsp&nbsp&nbsp&nbsp

                </ul>
			</div><!-- /.navbar-collapse -->
		</nav>

        <div class="container-fluid" style = "padding-top: 50px">
            <div class="row" style="height:400px;">
                <div class="col-lg-2 btn-group-vertical" style="padding:0px; height:100%">
                    <div style="height:120px; border-bottom:5px solid #000000; border-top:15px solid #000000; text-align:center;">
                        <h4><dl class="text-muted">
<?php
    echo $_SESSION['sessname'];
?>
                        </dl></h4>
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
        $result=mysqli_query($conn, "CALL SESSION_GET_OTHERS_INSESSION($_SESSION[userid], '$_SESSION[password]', $_SESSION[sessid], $tmp_granted);");
        while ($row = mysqli_fetch_array($result))
        {
            if ($row['MANAGER'] == 0)
            {
                echo '<dl class="text-muted" style="text-align:center;">';
            }
            else
            {
                echo '<dl class="text-danger" style="text-align:center;">';
            }
            echo $row['CNAME'] . " [" . $row['CID'] . "]";
            echo "</dl>";
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
                    </div>
                </div>
                <div class="col-lg-9 pull-right modal-content" style="background-color: rgba(0, 0, 0, 0); height: 80%; text-align:left;"> 
                    <div class="row pre-scrollable" style="max-height: 100%;">
<?php
    $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
    if (!$conn)
    {
        echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
    }
    else
    {
        $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
        $result=mysqli_query($conn, "CALL MSG_GET($_SESSION[userid], '$_SESSION[password]', $_SESSION[sessid], $tmp_granted);");
        while ($row = mysqli_fetch_array($result))
        {
            echo '<dl class="text-muted">&nbsp&nbsp';
            echo $row['CNAME'] . " [" . $row['CID'] . "] (" . $row['MID'] . ", " . $row['MBIRTH'] . ")";
            echo "</dl>";
            echo '<dl class="text-primary">&nbsp&nbsp&nbsp&nbsp' . $row['MTEXT'] . "</dl>";
        }
        mysqli_free_result($result);
        mysqli_close($conn);
    }
?>
                    </div>
                </div>
                <br><br>
                <div class="col-lg-9 pull-right modal-content" style="background-color: rgba(0, 0, 0, 0); height: 30%"> <dl class="text-muted">
                    <form action="message.php" method="post"> 
                        <textarea name="text" required style="background-color:rgba(1, 1, 1, 0.5); width:80%;height:100%;overflow:auto;word-break:break-all;resize: none"></textarea><br>
                        <input type="hidden" name="name" value=<?php echo $_SESSION['sessname']; ?> />
                        <input type="hidden" name="id" value=<?php echo $_SESSION['sessid']; ?> />
                        <input type="submit" name="submit" class="btn btn-info" value="Send" />
                    </form>
                </dl></div>
            </div>
        </div>

	</body>
</html>
