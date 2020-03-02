<?php
require_once 'bin.php';

set_error_handler("error_handler");

ini_set('session.use_only_cookies', 1);
session_start();

if (isset($_SESSION['username'])) {
	destroy_session_and_data();
	header("Location: signup.php");
}

$connection = new mysqli($hn, $un, $pw, $db);
if ($connection -> connect_error) die(goto_error());

if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['email'])) {
	if (!is_string($_POST['username']) || !is_string($_POST['password']) || !is_string($_POST['email'])) {
		goto_error();
	}
	
	$username = get_post($connection, 'username');
	$password = get_post($connection, 'password');
	$email = get_post($connection, 'email');
	$salt = bin2hex(openssl_random_pseudo_bytes(32));
	$token = hash('ripemd128', "$salt$password");
	
	//echo "$username<br>";
	//echo "$password<br>";
	//echo "$email<br>";
	
	$query = "SELECT * FROM final_users WHERE username='$username'";
	$result = $connection -> query($query);
	if (!$result) die(goto_error());
	elseif ($result -> num_rows) {
		echo "Error: Please pick a different username.";
	} else {
		$error = FALSE;
		if (preg_match("/[^a-zA-Z0-9_-]/", $username)) {
			$error = TRUE;
			echo "Error: Invalid character(s) in username.";
		}
		if (!((strpos($email, ".") > 0) && (strpos($email, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $email)) {
			$error = TRUE;
			echo "Error: Malformed email address.";
		}
		
		if (!$error) {
			$query2 = "INSERT INTO final_users VALUES(NULL, '$username', '$token', '$salt', '$email')";
			$result2 = $connection -> query($query2);
			if (!$result2) die(goto_error());
			
			$_SESSION['username'] = $username;
			$_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);
			
			header("Location: final.php");
		}
	}
	$result -> close();
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
	<html><head><title>Sign Up</title>
	<style>
	table {
		border:1px solid #999999; font: normal 14px helvetica; color: #444444; margin-left:auto; margin-right:auto;
	}
	</style>
	<script>
	function validate(form) {
		fail = validateUsername(form.username.value)
		fail += validatePassword(form.password.value)
		fail += validateEmail(form.email.value)
		
		if (fail == "") return true
		else {
			//document.write(fail)
			alert(fail); 
			return false 
		}
	}
	
	function validateUsername(field) {
		if (field == "") return "No Username was entered.\\n"
		else if (field.length < 5)
			return "Usernames must be at least 5 characters.\\n"
		else if (/[^a-zA-Z0-9_-]/.test(field))
			return "Only a-z, A-Z, 0-9, - and _ allowed in Usernames.\\n"
		return ""
	}
	
	function validatePassword(field) {
		if (field == "") return "No Password was entered.\\n"
		return ""
	}
	
	function validateEmail(field) {
		if (field == "") return "No Email was entered.\\n"
		else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
			return "The Email address is invalid.\\n"
		return ""
	}
	
	</script>
	</head>
	<body>
	<table border="0" cellpadding="2" cellspacing="10" bgcolor="#eeeeee">
		<th colspan="2" align="center">Signup Form</th>
		<form method="post" action="signup.php" onsubmit="return validate(this)">
			<tr><td>Username</td>	<td><input type="text" maxlength="16" name="username"></td></tr>
			<tr><td>Password</td>	<td><input type="password" maxlength="12" name="password"></td></tr>
			<tr><td>Email</td>		<td><input type="text" maxlength="64" name="email"></td></tr>
			<tr><td colspan="2" align="center"><input type="submit" value="Signup"></td></tr>
		</form>
	</table>
	</body>
	</html>
_END;
?>