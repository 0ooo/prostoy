<?php

require('../../config.php');
require_once($CFG->libdir . '/adminlib.php');
require_once($CFG->libdir . '/formslib.php');
require('./classes/feedback.php');

//POST запрос с формы обратной связи
$requestData = optional_param('data', '', PARAM_RAW);

if(!$requestData){
    redirect('/');
    die;
}

$feedback = new feedback($requestData);

//проверка полей
if ($feedback->checkValidate()) {
    echo json_encode(['status' => 'errorvalidate', 'message' => 'Форма заполнена не верно!']);

    die;
}

//сохранение в бд
if (!$feedback->save()) {
    echo json_encode(['status' => 'errorsave', 'message' => 'Произошла ошибка. Попробуйте повторить позже']);

    die;
}

//отправка в простой
if (!$feedback->send()) {
    echo json_encode(['status' => 'errorsend','message' => 'Произошла ошибка. Попробуйте повторить позже']);

    die;
}

echo json_encode(['status' => 'success', 'message' => 'Сообщение отправлено!']);

die;