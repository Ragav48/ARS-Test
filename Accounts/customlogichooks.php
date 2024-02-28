<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

class Accounts_cstm
{
    function add_record_number($bean, $event, $arguments)
    {
        global $db;
        $query = "UPDATE `" . $bean->table_name . "` SET `name` = concat('ARS_', record_number) WHERE `id` = '" . $bean->id . "'";
        $db->query($query);
    }

    function update_bean($bean, $event, $arguments)
    {
        $bean->record_number = 'ARS_' . $bean->record_number;
    }
    public function calculateDates($bean, $event, $arguments)
    {
        if ($event == 'before_save') {
            $dates = [];
            $intervalDays = 8;
            $totalDates = 10;

            $givenDate = $bean->due_start_date_c;

            $givenDateTime = strtotime($givenDate);

            for ($i = 0; $i < $totalDates; $i++) {
                $newDate = date("d-m-Y",  strtotime("+$i days", $givenDateTime + ($intervalDays * $i * 86400)));
                $dates[] = $newDate;
            }

            $datesWithLineBreaks = implode("\n", $dates);
            $bean->datelists_c = $datesWithLineBreaks;
            // $bean->save();
        }
    }



    function createTask($bean, $event, $arguments)
    {
        global $db;

        $staticClosedValue = false;
        $subjectName = 'Due_No';
        $dueDateInterval = 9;

        // Fetch the value of 'due_start_date_c' from the 'accounts' module
        $query = "SELECT due_start_date_c FROM accounts_cstm WHERE id_c = '{$bean->id}'";
        $result = $db->query($query);
        $row = $db->fetchByAssoc($result);
        $dueStartDate = $row['due_start_date_c'];

        $dynamicDueDate = $dueStartDate;

        for ($i = 1; $i <= 10; $i++) { 
            if ($i > 1) {
                $dynamicDueDate = date('Y-m-d', strtotime("+$dueDateInterval days", strtotime($dynamicDueDate)));
            }

            $taskBean = BeanFactory::newBean('Tasks');
            $taskBean->tasksubject_c = $subjectName . ' ' . $i;
            $taskBean->task_duedate_c = $dynamicDueDate;
            $taskBean->closed_c = $staticClosedValue;

            // Save the task record
            if ($taskBean->save()) {
                $query = "INSERT INTO accounts_tasks_1_c (id, date_modified, deleted, accounts_tasks_1accounts_ida, accounts_tasks_1tasks_idb) VALUES (UUID(), NOW(), 0, '{$bean->id}', '{$taskBean->id}')";
                $db->query($query);
            }
        }
    }
}
