<?php
require_once 'bin.php';

set_error_handler("error_handler");

ini_set('session.use_only_cookies', 1);
session_start();

if (isset($_SESSION['username']) && $_SESSION['check'] == hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
	$username = $_SESSION['username'];
} else {
	destroy_session_and_data();
}


$connection = new mysqli($hn, $un, $pw, $db);
if ($connection -> connect_error) die(goto_error());

$error = "";
$input = "";
$output = "";

$ss_in = "abcdefghijklmnopqrstuvwxyz";
$ss_out = "kehjnvazxrqsgwuyocftmlidbp";

$dt_key1 = "352014";
$dt_key2 = "32740651";

$rc4_key = "Key";

if (isset($_POST['cipher']) && isset($_POST['direction'])) {
	if (is_string($_POST['cipher']) && is_string($_POST['direction'])) {
		$cipher = get_post($connection, 'cipher');
		$direction = get_post($connection, 'direction');
		if ($_FILES && $_FILES["filename"]["error"] == 0) {
			if (!file_exists($_FILES['filename']['tmp_name'])) {
				$error .= "Error: file does not exist. ";
			}
			if ($_FILES['filename']['type'] !== "text/plain") {
				$error .= "Error: file is not a text file. ";
			}
			if (pathinfo($_FILES['filename']['name'])['extension'] !== "txt") {
				$error .= "Error: file does not have 'txt' file extension. ";
			}
			
			$input = mysql_entities_fix_string($connection, file_get_contents($_FILES['filename']['tmp_name']));
		} elseif (isset($_POST['input'])) {
			$input = get_post($connection, 'input');
		}
		
		if ($cipher == "ss") {
			if ($direction == "encrypt") {
				$temp = str_split($input);
				foreach ($temp as $c) {
					$i = stripos($ss_in, $c);
					if ($i !== FALSE) {
						$output .= (ctype_upper($c) ? strtoupper($ss_out[$i]) : $ss_out[$i]);
					} else {
						$output .= $c;
					}
				}
			} elseif ($direction == "decrypt") {
				$temp = str_split($input);
				foreach ($temp as $c) {
					$i = stripos($ss_out, $c);
					if ($i !== FALSE) {
						$output .= (ctype_upper($c) ? strtoupper($ss_in[$i]) : $ss_in[$i]);
					} else {
						$output .= $c;
					}
				}
			}
			
		} elseif ($cipher == "dt") {
			if (strlen($input) >= max(strlen($dt_key1), strlen($dt_key2))) {
				if ($direction == "encrypt") {
					$temp = dt($dt_key1, $input);
					$output .= dt($dt_key2, $temp);
				} elseif ($direction == "decrypt") {
					$temp = un_dt($dt_key2, $input);
					$output .= un_dt($dt_key1, $temp);
				}
			}
		} elseif ($cipher == "rc4") {
			$s = [];
			for ($i=0; $i < 256; $i++) { 
				$s[$i] = $i;
			}
			$j = 0;
			for ($i=0; $i < 256; $i++) { 
				$j = ($j + $s[$i] + ord($rc4_key[$i % strlen($rc4_key)])) % 256;
				$t = $s[$i];
				$s[$i] = $s[$j];
				$s[$j] = $t;
			}
			
			if ($direction == "encrypt") {
				$temp = str_split($input);
				$i = 0;
				$j = 0;
				foreach ($temp as $c) {
					$i = ($i + 1) % 256;
					$j = ($j + $s[$i]) % 256;
					$t = $s[$i];
					$s[$i] = $s[$j];
					$s[$j] = $t;
					$k = $s[($s[$i] + $s[$j]) % 256];
					$output .= str_pad(dechex(ord($c) ^ $k), 2, "0", STR_PAD_LEFT) ;
				}
			} elseif ($direction == "decrypt") {
				$temp = str_split($input, 2);
				$i = 0;
				$j = 0;
				foreach ($temp as $c) {
					$i = ($i + 1) % 256;
					$j = ($j + $s[$i]) % 256;
					$t = $s[$i];
					$s[$i] = $s[$j];
					$s[$j] = $t;
					$k = $s[($s[$i] + $s[$j]) % 256];
					$output .= chr(hexdec($c) ^ $k);
				}
			}
		}
		
		if (isset($username)) {
			$query = "SELECT * FROM final_users WHERE username='$username'";
			$result = $connection -> query($query);
			if (!$result) goto_error();
			elseif ($result -> num_rows) {
				$row = $result -> fetch_array(MYSQLI_ASSOC);
				$user_id = $row['id'];
				
				$query2 = "INSERT INTO final_data VALUES(NULL, '$user_id', '$input', '$cipher', NULL)";
				$result2 = $connection -> query($query2);
				if (!$result2) goto_error();
			}
			$result -> close();
		}
	} else {
		goto_error();
	}
}

$connection -> close();

function dt ($key, $text) {
	$temp = str_split($text, strlen($key));
	$out = "";
	for ($i = 0; $i < strlen($key); $i++) { 
		$n = strpos($key, strval($i));
		$j = 0;
		while (isset($temp[$j][$n])) {
			$c = $temp[$j][$n];
			$out .= $c;
			$j++;
		}
	}
	return $out;
}

function un_dt ($key, $text) {
	$n = floor(strlen($text) / strlen($key));
	$r = strlen($text) % strlen($key);
	$t = [];
	for ($i=0; $i < strlen($key); $i++) { 
		if ($i < $r) {
			$t[$i] = $n + 1;
		} else {
			$t[$i] = $n;
		}
	}
	
	$temp = [];
	for ($i=0; $i < strlen($key); $i++) { 
		$s = strpos($key, strval($i));
		$c = $t[$s];
		$temp[$s] = substr($text, 0, $c);
		$text = substr($text, $c);
	}
	
	$out = "";
	for ($i = 0; $i < ($r > 0 ? $n + 1 : $n); $i++) {
		$j = 0;
		while (isset($temp[$j][$i])) {
			$c = $temp[$j][$i];
			$out .= $c;
			$j++;
		}
	}
	return $out;
}

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
<html><head><title>Decryptoid</title></head>

<body>
_END;

if (isset($username)) {
	echo "<div style='float: right;'>Welcome, $username! | <a href='login.php'>Logout</a></div>";
} else {
	echo "<div style='float: right;'><a href='login.php'>Login</a> | <a href='signup.php'>Sign Up</a></div>";
}

echo <<<_END
<h1>Decryptoid</h1>
<form method="post" action="final.php" enctype='multipart/form-data'>
<select name="cipher">
  <option default value="ss">Simple Substitution</option>
  <option value="dt">Double Transposition</option>
  <option value="rc4">RC4</option>
</select>
<select name="direction">
  <option default value="encrypt">Encrypt</option>
  <option value="decrypt">Decrypt</option>
</select>
_END;

echo $error;

echo <<<_END
<br><br>
<textarea name="input" rows="15" cols="100" spellcheck="false">
_END;

echo $input;

echo <<<_END
</textarea>
<br>Select File: <input type='file' name='filename' size='10'><br><br>
<input type="submit" value="Process">
</form>
<h2>Result:</h2>
<textarea name="result" rows="15" cols="100" spellcheck="false">
_END;

echo $output;

echo "</textarea>";



echo "</body></html>";
?>