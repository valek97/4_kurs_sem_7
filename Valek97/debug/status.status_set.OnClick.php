<?php $get = Status::statusGet();

if(!isset($get)){
    messageDlg("�� ������� �������� ������!", mtConfirmation, MB_OK);
}else{
    c("statusEdit")->text = $get;
}
