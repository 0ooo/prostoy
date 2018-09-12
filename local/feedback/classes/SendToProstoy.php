<?php

class SendToProstoy
{

    public $id_task = '';

    public $password = '';

    public $thema_message = '';

    public $message = '';

    public $name_user = '';

    public $email = '';

    public $array_column = [];

    private $max_lenght_comment = 2000;

    private $time_live_memcache = 15;

    private $check_frequency = false;

    /**
     * Установить максимальную длину сообщения с символах.
     * Все что больше заданного значниея будет отсечено
     *
     * @param int $max_lenght_comment
     */
    public function setMaxLenghtComment($max_lenght_comment)
    {
        $this->max_lenght_comment = $max_lenght_comment;
    }

    /**
     * Конвертирует строуку из utf в 1251
     *
     * @param string $string
     *
     * @return string
     */
    private function convertTo1251Curl($string)
    {
        return iconv('utf-8', 'windows-1251', $string);
    }

    /**
     * Конвертация из utf в 1251 с дополнительной конвертацияей в
     * (%) с последующими двумя 16-ричными цифрами и пробелами
     *
     * @param string $string
     *
     * @return string
     */
    private function convertTo1251($string)
    {
        return urlencode(iconv('utf-8', 'windows-1251', $string));
    }

    /**
     * Установить email отправителя
     *
     * @param type $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Получить установленный email
     *
     * @return type
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Добавить email к сформированному запросу
     *
     * @return string
     */
    private function addEmailToRequest()
    {
        if ($this->getEmail() <> '') {
            return $this->convertTo1251($this->getEmail());
        }
        return '';
    }

    /**
     * Установить имя отправителя сообщения
     *
     * @param string $name_user
     */
    public function setNameUser($name_user)
    {
        $this->name_user = $name_user;
    }

    /**
     * Получить имя отправителя
     *
     * @return string
     */
    public function getNameUser()
    {
        return $this->name_user;
    }

    /**
     * Добавить имя отправителя в запрос
     *
     * @return string
     */
    private function addNameUserToRequest()
    {
        if ($this->getNameUser() <> '') {
            return $this->convertTo1251($this->getNameUser());
        }
        return '';
    }

    /**
     * Установить id номер задачи в которую отправляется сообщение
     *
     * @param int $id_task
     */
    public function setIdTask($id_task)
    {
        $this->id_task = $id_task;
    }

    /**
     * Получить id задачи в которую отправляется сообщение
     *
     * @return int
     */
    public function getIdTask()
    {
        return $this->id_task;
    }

    /**
     * Установить пароль задачи в которую отправляется сообщение
     *
     * @param string $password
     */
    public function setPasswordTask($password)
    {
        $this->password = $password;
    }

    /**
     * Получить пароль задачи в которую отправляется сообщение
     *
     * @return string
     */
    public function getPasswordTask()
    {
        return $this->password;
    }

    /**
     * Установить тему отправляемого сообщения
     *
     * @param string $thema_message
     */
    public function setThemaMessage($thema_message)
    {
        $this->thema_message = $thema_message;
    }

    /**
     * Получить тему отправляемого сообщения
     *
     * @return type
     */
    public function getThemaMessage()
    {
        return $this->thema_message;
    }

    /**
     * добавить тему сообщения в формируемые запрос
     *
     * @return string
     */
    private function addThemaMessageToRequest()
    {
        if ($this->getThemaMessage() <> '') {
            return $this->convertTo1251($this->getThemaMessage());
        }
        return '';
    }

    /**
     * определить сообщение которое будет отправляться
     *
     * @param type $message
     */
    public function setMessage($message)
    {
        $this->message = substr($message, 0, $this->max_lenght_comment);
    }

    /**
     * Получить текст сообщения которое будет отправляться
     *
     * @return type
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Добавить текст сообщения которое будет отправляться
     *
     * @return string
     */
    private function addMessageToRequest()
    {
        if ($this->getMessage() <> '') {
            return $this->convertTo1251($this->getMessage());
        }
        return '';
    }

    /**
     * Очистить массив с данными таблицы
     */
    public function cleareArray()
    {
        $this->array_column = [];
    }

    public function addColumnToArray($hash_column, $data_column)
    {
        $this->array_column[$hash_column] = $data_column;
    }

    /**
     * Формирует запрос по методу пост для отправки сообщения
     *
     * @return string
     */
    private function getRequestForFileSendComment()
    {
        $request = "http://agent.prostoy.ru/addComment.php";
        $request .= "?tid=" . $this->getIdTask();
        $request .= "&tpass=" . $this->getPasswordTask();
        $request .= "&tname=" . $this->addNameUserToRequest();
        $request .= "&tsubj=" . $this->addThemaMessageToRequest();
        $request .= "&temail=" . $this->addEmailToRequest();
        $request .= "&tcom=" . $this->addMessageToRequest();

        return $request;
    }

    /**
     * Формирует массив для пост запроса
     *
     * @return array - сформированный массив для post запроса по методу curl
     */
    private function getRequestForCurlSendComment()
    {
        $array_for_post = [
            "tpass" => $this->getPasswordTask(),
            "tid" => $this->getIdTask(),
            "tname" => $this->convertTo1251Curl($this->getNameUser()),
            "tsubj" => $this->convertTo1251Curl($this->getThemaMessage()),
            "tcom" => $this->convertTo1251Curl($this->getMessage()),
            "temail" => $this->convertTo1251Curl($this->getEmail()),
        ];
        return $array_for_post;
    }

    /**
     * Отправка комментария в задачку Простого по методу file
     *
     * @return boolean
     */
    public function sendCommentToTask_()
    {
        return $result = file($this->getRequestForFileSendComment());
    }

    /**
     * Отправка комментария в задачку Простого по методу curl
     *
     * @return boolean
     */
    public function sendCommentToTask()
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://agent.prostoy.ru/addComment.php');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $this->getRequestForCurlSendComment());
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

    /**
     * Отправка комментария в задачку Простого по методу curl
     *
     * @return boolean
     */
    public function sendRowToTable()
    {
        $array = [
            'iform' => "true",
            "action" => "send_iform",
            "referer" => "",
            "task_id" => $this->getIdTask(),
            "hash" => $this->getPasswordTask(),
            "formdata" => $this->array_column,
        ];

        $curl = curl_init('http://agent.prostoy.ru/api/crmform.php');
        $array_json = json_encode($array);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $array_json);
        $result = curl_exec($curl);
        curl_close($curl);
        return $result;
    }

}
//      Пример отправки сообщения
//
//	$obj = new sendToProstoyClass();
//	$obj->setMessage('Текст сообщения');
//	$obj->setIdTask(31517);
//	$obj->setPasswordTask('00988042af00ae339235b09f27383d87');
//	$obj->setEmail('Email отправителя');
//	$obj->setNameUser('Имя пользователя');
//	$obj->setThemaMessage('Тема сообщения');
//	$result = $obj->sendCommentToTask();

//      Пример записи в таблицу
//	$obj = new sendToProstoyClass();
//	$obj->setPasswordTask('00988042af00ae339235b09f27383d87');
//	$obj->addColumnToArray('A0B67EA362CEF27A', '66');
//	$obj->addColumnToArray('AC52D301F4FF19F0', '77');
//	$result = $obj->sendRowToTable();