<?php if(c("statusEdit")->text == null){
    messageDlg("���� ������� ������!", mtConfirmation, MB_OK);
}else{
    $set = Status::statusSet(c("statusEdit")->text);

    if($set == 1){
        messageDlg("������ ������� �������!", mtConfirmation, MB_OK);
    }else{
        messageDlg("�� ������� ������� ������!", mtConfirmation, MB_OK);
    }
}
