<?php
	session_start();
	if ($_POST['submit'] == 'Join')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_JOIN($_SESSION[userid], '$_SESSION[password]', $_POST[sessid], '$_POST[password]', $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            mysqli_close($conn);
            if ($row[$tmp_granted] <= 0)
            {
            	echo "<script>alert(\"Fail to join session...\");</script>";
            	header("Refresh:0; url=main.php");
            }
            else
            {
	            echo "<script>alert(\"Join into session $_POST[sessid]\");</script>";
	            header("Refresh:0; url=main.php");
        	}
        }
    }
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>