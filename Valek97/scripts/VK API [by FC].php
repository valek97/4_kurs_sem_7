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
	
	function User($uid2){
        $uid2= c("uId2")->text;
        //$uid2=61943436;
		$user = VK::request('users.get', 'user_id=' . $uid2.'&fields=photo_100,bdate,city,country,followers_count,online,online_mobile,contacts,connections,status,last_seen,counters,sex&v=5.92');
		if(!$user){
			messageDlg("Не удалось получить информацию о пользователе!", mtConfirmation, MB_OK);
		}else{
			pre($user);
			//c('User->imageUser')->loadFromUrl  ('https://vk.com/id61943436?z=photo61943436_456241353%2Falbum61943436_0%2Frev');
			c('User->firstName')->caption = iconv('UTF-8', 'cp1251', $user['response']['0']['first_name']);
			c('User->lastName')->caption = iconv('UTF-8', 'cp1251', $user['response']['0']['last_name']);
            c('User->bDate')->caption = iconv('UTF-8', 'cp1251', $user['response']['0']['bdate']);
			c('User->status')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['status']);
            c('User->cityId')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['city']['title']);
            c('User->countryName')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['country']['title']);
            c('User->friends')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['counters']['friends']);
            c('User->time')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['last_seen']['time']);
            c('User->platform')->text = iconv('UTF-8', 'cp1251', $user['response']['0']['last_seen']['platform']);
			LoadForm(c("User"), LD_NONE);
		}
	}
}
class Status{
    
	
	function statusGet(){
        //$uid= c("VK->uId")->text;
        $uid=61943436;
        
		$get = VK::request('status.get','user_id=61943436&v=5.92');
		$get = iconv('UTF-8', 'cp1251', $get['response']['text']);
		return $get;
	}
	
	function statusSet($set){
		$set = iconv('cp1251', 'UTF-8', $set);
		$set = str_replace(' ', '+', $set);
		
		$set = VK::request('status.set', 'text=' . $set.'&v=5.92');
        return $set['response'];
	}
}
?>