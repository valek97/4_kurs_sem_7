<?php

class VK{
	
	function auth($login, $pass){
		
		if(empty($login) and empty($pass)) return false;
        
        $vk = curl_init('http://m.vk.com/');
        curl_setopt_array($vk, array(
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 6.3; rv:38.0) Gecko/20100101 Firefox/38.0',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_COOKIEFILE => '')
        );

        preg_match('/<form method="post" action="([\w\W]+)" novalidate>/U', curl_exec($vk), $url);
        if(empty($url[1])) {
            curl_close($vk);
            return false;
        }
		
        curl_setopt($vk, CURLOPT_URL,  $url[1]);
        curl_setopt($vk, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($vk, CURLOPT_HTTPHEADER, array('Host' => 'login.vk.com'));
        curl_setopt($vk, CURLOPT_POST, true);
        curl_setopt($vk, CURLOPT_POSTFIELDS, http_build_query(array('email' => (string)$login, 'pass' => (string)$pass)));
        curl_exec($vk);

        curl_setopt($vk, CURLOPT_URL, curl_getinfo($vk, CURLINFO_EFFECTIVE_URL));
        curl_setopt($vk, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($vk, CURLOPT_HTTPHEADER, array('Host' => 'm.vk.com'));
        curl_setopt($vk, CURLOPT_POST, false);
        curl_exec($vk);

        curl_setopt($vk, CURLOPT_URL, 'http://oauth.vk.com/authorize?client_id=5077418&scope=2079999&redirect_uri=https://oauth.vk.com/blank.html&display=wap&response_type=token&v=5.44');

        preg_match('/<form method="post" action="([\w\W]+)">/U', curl_exec($vk), $url);
        if(empty($url[1])) {
            curl_close($vk);
            return false;
        }
		
        curl_setopt($vk, CURLOPT_URL,  $url[1]);
        curl_setopt($vk, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($vk, CURLOPT_HTTPHEADER, array('Host' => 'login.vk.com'));
        curl_setopt($vk, CURLOPT_POST, true);
        curl_exec($vk);
        $url = parse_url(curl_getinfo($vk, CURLINFO_EFFECTIVE_URL));
        parse_str($url['fragment'], $url);
        curl_close($vk);
		
        if(array_key_exists('access_token', $url)) {
            return $url;
        }
        return false;
	}
	
	function request($method, $pars){
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

class Profile{
	
	function User($uid){
		$user = VK::request('users.get', 'user_id=61943436&fields=photo_100,bdate,city,country,followers_count,online,online_mobile,contacts,connections,status,last_seen,counters,sex&v=5.92'););
		if(!$user){
			messageDlg("Не удалось получить информацию о пользователе!", mtConfirmation, MB_OK);
		}else{
			pre($user);
			c('User->imageUser')->loadFromUrl($user['response']['0']['photo_100']);
			c('User->firstName')->caption = iconv('UTF-8', 'cp1251', $user['response']['0']['first_name']);
			c('User->lastName')->caption = iconv('UTF-8', 'cp1251', $user['response']['0']['last_name']);
			c('User->status')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['status']);
			LoadForm(c("User"), LD_NONE);
		}
	}
}

?>