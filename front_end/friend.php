<?php
	session_start();
	if ($_POST['submit'] == 'Delete friend')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_DELETE_FRIEND($_SESSION[userid], '$_SESSION[password]', $_POST[id], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            mysqli_close($conn);
            if ($row[$tmp_granted] > 0)
            {
            	echo "<script>alert(\"Friend deleted!\"); history.go(-1);</script>";
            }
            else
            {
            	echo "<script>alert(\"Fail to delete friend!\"); history.go(-1);</script>";
            }
        }
    }
    elseif ($_POST['submit'] == 'Friend application')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            $tmp_faid = "@tmp_faid_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_FRIEND_APPLICATION($_SESSION[userid], '$_SESSION[password]', $_POST[id], $tmp_faid, $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            if ($row[$tmp_granted] > 0)
            {
            	$result = mysqli_query($conn, "SELECT $tmp_faid;");
            	$row = mysqli_fetch_array($result);
            	mysqli_free_result($result);
            	mysqli_close($conn);
            	echo "<script>alert(\"Friend application sent!\"); history.go(-1);</script>";
            }
            else
            {
            	mysqli_close($conn);
            	echo "<script>alert(\"Fail to send friend application!\"); history.go(-1);</script>";
            }
        }
    }
    elseif ($_POST['submit'] == 'Accept application')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_ACCEPT_FRIEND_APPLICATION($_SESSION[userid], '$_SESSION[password]', $_POST[id], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            mysqli_close($conn);
            if ($row[$tmp_granted] > 0)
            {
            	echo "<script>alert(\"Friend application accepted!\"); history.go(-1);</script>";
            }
            else
            {
            	echo "<script>alert(\"Fail to accept friend application!\"); history.go(-1);</script>";
            }
        }
    }
    elseif ($_POST['submit'] == 'Refuse application')
    {
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\"); history.go(-1);</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL USER_REFUSE_FRIEND_APPLICATION($_SESSION[userid], '$_SESSION[password]', $_POST[id], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted;");
            $row = mysqli_fetch_array($result);
            mysqli_free_result($result);
            mysqli_close($conn);
            if ($row[$tmp_granted] > 0)
            {
            	echo "<script>alert(\"Friend application refused!\"); history.go(-1);</script>";
            }
            else
            {
            	echo "<script>alert(\"Fail to refuse friend application!\"); history.go(-1);</script>";
            }
        }
    }
	else
	{
		echo "<script>alert(\"Something wrong... Try again later.\"); history.go(-1);</script>";
	}
?>