<?php global $token, $id;

if(preg_match('/access_token/', $url)){
    preg_match('/access_token=(.*)&/', $url, $a);
    preg_match('/user_id=(.*)/', $url, $b);

    $token = $a[1];
    $id = $b[1];

    pre($token);
    pre($id);
}
