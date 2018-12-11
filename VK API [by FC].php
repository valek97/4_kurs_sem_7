<?php

class VK{
	
	function Request($method, $pars){
		global $token;
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_URL, 'https://api.vk.com/method/' . $method . '?' . $pars . '&access_token=' . $token);
		$response = curl_exec($curl);
		curl_close($curl);
		$response = json_decode($response, true);
		return $response;
	}
}

?>