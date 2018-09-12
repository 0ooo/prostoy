<?php

defined('MOODLE_INTERNAL') || die;

function local_feedback_extend_settings_navigation($navigation, $course)
{
    global $CFG;

    $url = new moodle_url($CFG->wwwroot . '/local/feedback/index.php');
    $devcoursenode = navigation_node::create('Обратная связь', $url);

    $navigation->add_node($devcoursenode);
}