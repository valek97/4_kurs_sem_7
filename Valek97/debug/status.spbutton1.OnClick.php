<?php if(c("statusEdit")->text == null){
    messageDlg("Поле статуса пустое!", mtConfirmation, MB_OK);
}else{
    $set = Status::statusSet(c("statusEdit")->text);

    if($set == 1){
        messageDlg("Статус успешно изменен!", mtConfirmation, MB_OK);
    }else{
        messageDlg("Не удалось сменить статус!", mtConfirmation, MB_OK);
    }
}
