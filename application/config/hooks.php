<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/
$hook['language_choose'] = array(
    'class'    => 'LangClass',
    'function' => 'set_lang',
    'filename' => 'Langclass.php',
    'filepath' => 'hooks',
);
/* End of file Hook.php */