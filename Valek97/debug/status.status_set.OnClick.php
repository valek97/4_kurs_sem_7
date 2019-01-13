<?php $get = Status::statusGet();

if(!isset($get)){
    messageDlg("Не удалось получить статус!", mtConfirmation, MB_OK);
}else{
    c("statusEdit")->text = $get;
}
