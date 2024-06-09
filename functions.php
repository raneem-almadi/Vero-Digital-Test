<?php
/**
* This function makes request to the server
*@param array $connectionHeader
*@param string $url
*@param array $postCredentials
*
*return array
*/
function makeRequest($connectionHeader, $url, $userCredentials = null){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0); //added for local settings -- should be removed for production
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); //added for local settings -- should be removed for production
	curl_setopt_array($curl, [
	  CURLOPT_URL => $url,
	  CURLOPT_RETURNTRANSFER => true,
	  CURLOPT_ENCODING => "",
	  CURLOPT_MAXREDIRS => 10,
	  CURLOPT_TIMEOUT => 30,
	  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	  CURLOPT_HTTPHEADER => $connectionHeader,
	]);
	if($userCredentials != null){
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($userCredentials));
	}
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
	if ($err) {
		return [
			'status' => 'fail',
			'errorMsg' => "cURL Error #:" . $err
		];
	} else {
		return [
			'status' => 'success',
			'data' => $response
		];
	}
}

/**
* This function fetches data from server (login + get dataset)
*@param array $connectionHeader
*@param array $userCredentials
*
*/
function getDataFromServer($connectionHeader, $userCredentials){
	//Get user info from the server
	$loginUrl = 'https://api.baubuddy.de/index.php/login';
	$user = makeRequest($connectionHeader, $loginUrl, $userCredentials);
	if($user['status'] == 'fail')
		die($user['errorMsg']);

	$userData = json_decode($user['data']);
	$connectionHeader[0] = 'Authorization: Bearer ' . $userData->oauth->access_token;

	//Get data from the server
	$dataUrl = 'https://api.baubuddy.de/dev/index.php/v1/tasks/select';
	$result = makeRequest($connectionHeader, $dataUrl);
	$dataset = json_decode($result['data']);
	return $dataset;
}