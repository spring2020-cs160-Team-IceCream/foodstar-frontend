<?php
require_once 'bin.php';

set_error_handler("error_handler");

ini_set('session.use_only_cookies', 1);
session_start();
if (isset($_SESSION['username'])) {
	destroy_session_and_data();
	header("Location: login.php");
}

$connection = new mysqli($hn, $un, $pw, $db);
if ($connection -> connect_error) die(goto_error());

if (isset($_POST['username']) && isset($_POST['password'])) {
	if (!is_string($_POST['username']) || !is_string($_POST['password'])) {
		goto_error();
	}
	
	$username = get_post($connection, 'username');
	$password = get_post($connection, 'password');
	
	$query = "SELECT * FROM final_users WHERE username='$username'";
	$result = $connection -> query($query);
	if (!$result) die(goto_error());
	elseif ($result -> num_rows) {
		$row = $result -> fetch_array(MYSQLI_ASSOC);
		$result -> close();
		
		$salt = $row['salt'];
		$token = hash('ripemd128', "$salt$password");
		if ($token != $row['token']) {
			echo "Invalid username/password combination";
		} else {
			$_SESSION['username'] = $username;
			$_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
		
			header("Location: final.php");
		}
	} else {
		echo "Invalid username/password combination";
	}
} else {
	echo "Please populate all fields.";
}

$connection -> close();



function get_post($connection, $var) {
	return mysql_entities_fix_string($connection, $_POST[$var]);
}

function mysql_entities_fix_string($connection, $string) {
	return htmlentities(mysql_fix_string($connection, $string));
}

function mysql_fix_string($connection, $string) {
	if (get_magic_quotes_gpc()) $string = stripslashes($string);
	return $connection -> real_escape_string($string);
}

function destroy_session_and_data() {
	$_SESSION = array();	// Delete all the information in the array
	setcookie(session_name(), '', time() - 2592000, '/');
	session_destroy();
}


function error_handler($errno, $errstr) {
	goto_error();
}

function goto_error() {
	//echo "hmmm";
	header("Location: error.php");
}

echo <<<_END
	<html><head><title>Login</title>
	<style>
	table {
		border:1px solid #999999; font: normal 14px helvetica; color: #444444; margin-left:auto; margin-right:auto;
	}
	</style>
	<script>
	function validate(form) {
		fail = validateUsername(form.username.value)
		fail += validatePassword(form.password.value)
		
		if (fail == "") return true
		else {
			//document.write(fail)
			alert(fail); 
			return false 
		}
	}
	
	function validateUsername(field) {
		if (field == "") return "No Username was entered.\\n"
		return ""
	}
	
	function validatePassword(field) {
		if (field == "") return "No Password was entered.\\n"
		return ""
	}
	</script>
	</head>
	<body>
	<table border="0" cellpadding="2" cellspacing="10" bgcolor="#eeeeee">
		<th colspan="2" align="center">Login</th>
		<form method="post" action="login.php" onsubmit="return validate(this)">
			<tr><td>Username</td>	<td><input type="text" maxlength="16" name="username"></td></tr>
			<tr><td>Password</td>	<td><input type="password" maxlength="12" name="password"></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="Login"></td></tr>
		</form>
	</table>
	</body>
	</html>
_END;
?>