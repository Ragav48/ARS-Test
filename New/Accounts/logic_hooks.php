<?php
// Do not store anything in this file that is not part of the array or the hook version.  This file will	
// be automatically rebuilt in the future. 
$hook_version = 1;
$hook_array = array();
// position, file, function 
$hook_array['process_record'] = array();
$hook_array['process_record'][] = array(1, '', 'custom/modules/Accounts/customlogichooks.php', 'Accounts_cstm', 'update_bean');

$hook_array['after_save'] = array();
$hook_array['after_save'][] = array(11, 'update record number in database', 'custom/modules/Accounts/customlogichooks.php', 'Accounts_cstm', 'add_record_number');

$hook_array['before_save'] = array(); 
$hook_array['before_save'][] = array(45, 'Calculating dates based on Due Start Date', 'custom/modules/Accounts/customlogichooks.php', 'Accounts_cstm', 'calculateDates');


$hook_array['after_save'] = array();
$hook_array['after_save'][] = array(
    10,
    'CreateTasksLogic',
    'custom/modules/Accounts/customlogichooks.php',
    'Accounts_cstm',
    'createTask'
);
