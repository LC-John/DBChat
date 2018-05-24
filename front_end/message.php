<?php
    session_start();
    if ($_POST['submit'] == 'Send')
    {
        $_SESSION['sessid'] = $_POST['id'];
        $_SESSION['sessname'] = $_POST['name'];
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL MSG_SEND($_SESSION[userid], '$_SESSION[password]', $_POST[id], '$_POST[text]', $tmp_granted);");
            mysqli_close($conn);
        }
    }
    elseif ($_POST['submit'] == 'Withdraw')
    {
        $_SESSION['sessid'] = $_POST['id'];
        $_SESSION['sessname'] = $_POST['name'];
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL MSG_DELETE($_SESSION[userid], '$_SESSION[password]', $_POST[id], $_POST[msgid], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
                echo "<script>alert(\"Fail to withdraw! Maybe it doesn't belong to you.\");</script>";
            }
            mysqli_close($conn);
        }
    }
    elseif ($_POST['submit'] == 'Invite')
    {
        $_SESSION['sessid'] = $_POST['id'];
        $_SESSION['sessname'] = $_POST['name'];
        $conn = mysqli_connect("47.94.138.231", "user_dbchat", "", "DBChat");
        if (!$conn)
        {
            echo "<script>alert(\"No connection to the DB server!\");</script>";
        }
        else
        {
            $tmp_granted = "@tmp_granted_" . (string)(date("YmdHis")) . (string)(rand(0, 63));
            mysqli_query($conn, "CALL SESSION_INVITE($_SESSION[userid], '$_SESSION[password]', $_POST[id], $_POST[userid], $tmp_granted);");
            $result = mysqli_query($conn, "SELECT $tmp_granted");
            $row = mysqli_fetch_array($result);
            if ($row[$tmp_granted] <= 0)
            {
                echo "<script>alert(\"Fail to invite!\");</script>";
            }
            mysqli_close($conn);
        }
    }
    else
    {
        echo "<script>alert(\"Something wrong... Try again later.\");</script>";
    }
    header("Refresh:0; url=session_main.php");
?>