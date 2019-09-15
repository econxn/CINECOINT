<?php
//eco.nxn//
date_default_timezone_set("Asia/Jakarta");
error_reporting(0);
class curl {

	function post($url, $data, $headers, $hasHeader=true, $hasBody=true) {
		curl:
		$ch = curl_init();
		$tmpfname = dirname(__FILE__).'/tmp/cookies.txt';
		curl_setopt_array($ch, [
			CURLOPT_URL					=> $url,
			CURLOPT_USERAGENT		=> 'okhttp/3.12.1',
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_NOBODY			=> $hasBody ? 0 : 1,
			CURLOPT_HTTPHEADER	=> $headers,
			CURLOPT_POST				=> true,
			CURLOPT_POSTFIELDS	=> $data,
			CURLOPT_ENCODING		=> 'gzip',
			CURLOPT_SSL_VERIFYHOST	=> 0,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_CONNECTTIMEOUT	=> 10,
			CURLOPT_TIMEOUT 		=> 120,
			CURLOPT_COOKIEJAR		=> $tmpfname,
			CURLOPT_COOKIEFILE	=> $tmpfname
		]);

		$result = curl_exec ($ch);
		$error = curl_error ($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($error) {
			echo "     Error: ".$error."\n";
			sleep(2);
			goto curl;
		}
		curl_close($ch);
		return $result;
	}

	function get($url, $headers, $hasHeader=true, $hasBody=true) {
		curl:
		$ch = curl_init();
		curl_setopt_array($ch, [
			CURLOPT_URL					=> $url,
			CURLOPT_USERAGENT		=> 'okhttp/3.12.1',
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER			=> false,
			CURLOPT_NOBODY			=> $hasBody ? 0 : 1,
			CURLOPT_HTTPHEADER	=> $headers,
			CURLOPT_POST				=> false,
			CURLOPT_ENCODING		=> 'gzip',
			CURLOPT_SSL_VERIFYHOST	=> 0,
			CURLOPT_SSL_VERIFYPEER	=> false,
			CURLOPT_CONNECTTIMEOUT	=> 10,
			CURLOPT_TIMEOUT 		=> 120,
		]);

		$result = curl_exec ($ch);
		$error = curl_error ($ch);
		if ($error) {
			echo "     Error: ".$error."\n";
			sleep(2);
			goto curl;
		}
		curl_close($ch);
		return $result;
	}
}

function random ($length)
{
    $data = 'qwertyuioplkjhgfdsazxcvbnm1234567890';
    $string = '';
    for($i = 0; $i < $length; $i++) {
        $pos = rand(0, strlen($data)-1);
        $string .= $data{$pos};
    }
    return $string;
}

function randomuser($curl) {

	$randomuser = $curl->get('https://uinames.com/api/?ext&amount=50&region=indonesia&gender=random&source=uinames.com', null);

	$json = json_decode($randomuser);
	return $json;
}

function check ($curl, $phone, $country, $header) {

	if($country=="62") {
		$country_id = "Wpmbk5XezJ";
	} elseif($country=="1") {
		$country_id = "yJrb28JeWL";
	}

	$field =
	'{
	  "country_id": "'.$country_id.'",
	  "mobile_number": "'.$phone.'"
	}';

	$check = $curl->post('https://api.cinepoint.id/user/check-mobile-number', $field, $header);

	$json = json_decode($check);
	$status = $json->data->detail->exist;

	if($status==0) {
		return true;
	} else {
		print "[i] Checking: ".$phone." | sudah terdaftar.\n";
		return false;
	}
}

function request_otp ($curl, $phone, $country, $header) {

	if($country=="62") {
		$country_id = "Wpmbk5XezJ";
	} elseif($country=="1") {
		$country_id = "yJrb28JeWL";
	}

	$field =
	'{
	  "country_id": "'.$country_id.'",
	  "mobile_number": "'.$phone.'"
	}';

	$request_otp = $curl->post('https://api.cinepoint.id/otp/request', $field, $header);

	$json = json_decode($request_otp);
	$status = $json->data->detail->success;

	if($status==1) {
		return true;
	} else {
		print "[i] Sent OTP: ".$phone." | Failed. ".$json->meta->message."\n";
		return false;
	}
}

function otp_verify ($curl, $phone, $otp_code, $country, $header) {

	if($country=="62") {
		$country_id = "Wpmbk5XezJ";
	} elseif($country=="1") {
		$country_id = "yJrb28JeWL";
	}

	$field =
	'{
	  "country_id": "'.$country_id.'",
	  "mobile_number": "'.$phone.'",
	  "otp": "'.$otp_code.'"
	}';

	$otp_verify = $curl->post('https://api.cinepoint.id/otp/verify', $field, $header);

	$json = json_decode($otp_verify);
	$status = $json->data->detail->success;
	$token  = $json->data->detail->token;

	if($status==1) {
		return $token;
	} else {
		print "[i] OTP Verify: ".$phone." | Failed.\n";
		return false;
	}
}

function check_username ($curl, $username, $header) {

	$field =
	'{
	  "username": "'.$username.'"
	}';

	$check_username = $curl->post('https://api.cinepoint.id/user/check-username', $field, $header);

	$json = json_decode($check_username);
	$status = $json->data->detail->exist;

	if($status==0) {
		return true;
	} else {
		print "[i] Check Username: ".$phone." | Not Available.\n";
		return false;
	}
}

function register ($curl, $phone, $country, $first_name, $last_name, $gender, $reff, $token, $username, $header) {

	if($country=="62") {
		$country_id = "Wpmbk5XezJ";
	} elseif($country=="1") {
		$country_id = "yJrb28JeWL";
	}

	$field =
	'{
	  "country_id": "'.$country_id.'",
	  "debug": false,
	  "device_id": "'.random(16).'",
	  "first_name": "'.$first_name.'",
	  "gender": "'.$gender.'",
	  "last_name": "'.$last_name.'",
	  "mobile_number": "'.$phone.'",
	  "referral_id": "'.$reff.'",
	  "registration_id": "cv2p8R6a38w:APA91bGXL03gM7Nsg9bWr5zpaD11rHLU-qVhu0atLm0o5Go5AoRgUk2iKcV_qbT8pmCDv1708wmwJfMYlgsR8Rnq3DTOJejh6cOBGvgiJ3_ylC0mEtboJUgYK7lnnewF0voCxm7RjIXG",
	  "token": "'.$token.'",
	  "username": "'.$username.'"
	}';

	$register = $curl->post('https://api.cinepoint.id/user', $field, $header);

	$json = json_decode($register);
	$id = $json->data->detail->id;
	if(isset($id)) {
		return $json;
	} else {
		print "[i] Register: ".$phone." | Failed.\n";
		return false;
	}
}


$curl = new curl();

$Authorization = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VybmFtZSI6ImNpbmVwb2ludCIsInBhc3N3b3JkIjoiJSVjaW5lcG9pbnQlJSIsImlhdCI6MTU2ODUyNTQ2NiwiZXhwIjoxNjY4NTI1MzY2LCJzdWIiOiJjaW5lcG9pbnQuY29tIn0.OsocUQzE-NKq__ehn98Jd9MB2XvOU5O9OMBQkGbR7mI";
$header = [
	'Host: api.cinepoint.id',
	'Authorization: Bearer '.$Authorization,
	'Content-Type: application/json; charset=UTF-8',
	'Accept-Encoding: gzip',
	'User-Agent: okhttp/3.12.1'
];

// $reff = "II026815";
echo "Enter REFFERAL Code  :";
$reff = trim(fgets(STDIN));
if (strtolower($reff)=='z') {
	echo "\n";
	die();
}

user:
$user = randomuser($curl);

$i=1;
foreach ($user as $value) {

	$first_name = $value->name;
	$last_name = $value->surname;
	$gender = $value->gender;

	if(strlen($first_name)<3) {
		goto user;
	} else {
		input:
		echo "Enter Country [1/62] :";
		$country = trim(fgets(STDIN));
		if (strtolower($country)=='z') {
		  echo "\n";
		  die();
		}
		echo "Enter Phone  Number  :";
		$phone = trim(fgets(STDIN));
		if (strtolower($phone)=='z') {
		  echo "\n";
		  die();
		}


		check:
		$check = check ($curl, $phone, $country, $header);
		if($check==true) {
			request_otp:
			$request_otp = request_otp ($curl, $phone, $country, $header);
			if($request_otp==true) {
				otp:
				echo "Enter OTP Code  :";
				$otp_code = trim(fgets(STDIN));
				if (strtolower($otp_code)=='z') {
					echo "\n";
					die();
				}

				$otp_verify = otp_verify ($curl, $phone, $otp_code, $country, $header);
				if($otp_verify==false) {
					goto otp;
				} else {

					$token = $otp_verify;

					username:
					$username = strtolower($first_name).rand(123,999);

					$check_username = check_username ($curl, $username, $header);
					if($check_username==false) {
						goto username;
					} else {

						regsiter:
						$register = register ($curl, $phone, $country, $first_name, $last_name, $gender, $reff, $token, $username, $header);

						if($register==false) {
							goto regsiter;
						} else {

							$user_id = $register->data->detail->id;
							$point_referral = $register->data->detail->referral->point_referral;

							print str_pad($i, 2, "0",  STR_PAD_LEFT).". REGISTER SUCCESS! UserID: ".$user_id." | Refferal Point: ".$point_referral."\n";
							$i++;
						}
					}
				}
			}	else {
				goto input;
			}
		} else {
			goto input;
		}
	}
}

?>
