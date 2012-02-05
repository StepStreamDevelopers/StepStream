<?php
if (!defined('STATUSNET') && !defined('LACONICA')) { exit(1); }
$config['site']['name'] = 'StepStream';
$config['site']['server'] = '127.0.0.1';
$config['site']['path'] = '~andrew/StepStream/statusnet'; 
$config['db']['database'] = 'mysqli://root:password@127.0.0.1/statusnet';
$config['db']['type'] = 'mysql';
$config['site']['profile'] = 'private';
$config['site']['theme'] = 'stepstream';
$config['attachments']['uploads'] = false;
addPlugin('Tips');
$config['location']['share'] = 'false';

$config['mail']['backend'] = 'smtp';
$config['mail']['params'] = array(
'host' => 'mail.stephealth.org',
'port' => 587,
'auth' => true,
'username' => 'register@stephealth.org',
'password' => 'tsrbecl'
);
$config['mail']['appname'] = 'StepStream';
$config['mail']['check_domain'] = true;
$config['mail']['notifyfrom'] = '"StepStream RegisterBot" <register@stephealth.org>';
$config['mail']['domain'] = 'stephealth.org';