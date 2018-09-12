<?php

require 'SendToProstoy.php';

class feedback
{
    /**
     * @var array
     */
    protected $data;
    /**
     * @var SendToProstoy
     */
    protected $prostoy;
    /**
     * @var moodle_database
     */
    protected $db;
    /**
     * @var object|stdClasss
     */
    protected $cfg;

    public function __construct(array $data)
    {
        global $DB, $CFG;

        $this->db = $DB;
        $this->cfg = $CFG;

        $this->data = (array)$this->getData($data);
        $this->prostoy = new SendToProstoy;

        if (!$this->checkValidate()) {
            $this->setDataForProstoy();
        }
    }

    public function save(): bool
    {
        $result = $this->db->insert_record('local_feedback', (object)$this->data, true);

        if ($result) {
            return true;
        }

        return false;
    }

    public function getData(array $data): array
    {
        return array_map([$this, 'clearField'], $data);
    }

    public function clearField($field)
    {
        $field = trim($field);
        $field = strip_tags($field);
        $field = stripslashes($field);
        $field = htmlspecialchars($field);

        return $field ?? null;
    }

    public function checkValidate(): bool
    {
        $check = array_search(null, $this->data);

        return (bool)$check;
    }

    public function send(): bool
    {
        return $this->prostoy->sendCommentToTask();
    }

    protected function getMessage(): string
    {
        $message = "Должность: {$this->data['post']}" . PHP_EOL;
        $message .= "Телефон: {$this->data['phone']}" . PHP_EOL;
        $message .= "Сообщение: {$this->data['message']}";

        return $message;
    }

    protected function setDataForProstoy(): void
    {
        $this->prostoy->setMessage($this->getMessage());
        $this->prostoy->setEmail($this->data['email']);
        $this->prostoy->setNameUser($this->data['fio']);
        $this->prostoy->setIdTask('1891936');
        $this->prostoy->setPasswordTask('8a2dc59ec0cc2adf9a483499d3b4b8f5');
        $this->prostoy->setThemaMessage('Обратная связь');
    }
}
