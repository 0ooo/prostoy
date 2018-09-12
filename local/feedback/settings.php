<?php

defined('MOODLE_INTERNAL') || die;

global $PAGE;

$ADMIN->add('localplugins', new admin_externalpage('feedback','Обратная связь',
        $CFG->wwwroot . "/local/feedback/index.php", 'moodle/site:config'
    )
);

$settings = null;
