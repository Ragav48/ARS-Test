<?php

$job_strings[] = 'Message';

function Message()
{
    global $db;

    $query = "SELECT task_duedate_c FROM tasks_cstm";
    $result = $db->query($query);

    if ($result) {
        $cutOffDates = [];
        while ($row = $db->fetchByAssoc($result)) {
            $dueDate = date('Y-m-d', strtotime($row['task_duedate_c'] . ' -1 day'));
            $cutOffDates[] = $dueDate;
        }

        $currentDate = date('Y-m-d');
        if (in_array($currentDate, $cutOffDates)) {
            $cutoff = $currentDate;
             scheduleJob($cutoff);

            // Create a new record in the "ARS_SM" module
            $message = "Hello raga, this is overdue";
            $deliver = date('Y-m-d'); // current date
            $closed = 'delievered';

            $smBean = BeanFactory::newBean('ARS_SM');
            $smBean->messagecontent_c = $message;
            $smBean->deliveredon_c = $deliver;
            $smBean->closed_c = $closed;

            if ($smBean->save()) {

                // Get the ID of an account from the database
                $query = "SELECT a.id_c
                FROM accounts_cstm a 
                INNER JOIN accounts_tasks_1_c at ON a.id_c = at.accounts_tasks_1accounts_ida 
                WHERE a.id_c = at.accounts_tasks_1accounts_ida";

                $accountResult = $db->query($query);
                $accountRow = $db->fetchByAssoc($accountResult);
                $accountId = $accountRow['id_c'];

                $Query = "INSERT INTO accounts_ars_sm_1_c (id, date_modified, deleted, accounts_ars_sm_1accounts_ida, accounts_ars_sm_1ars_sm_idb) VALUES (UUID(), NOW(), 0, '{$accountId}', '{$smBean->id}')";
                $db->query($Query);
            }
        }
    }
}

function scheduleJob($cutoff)
{
    echo "Job scheduled for cutoff date: $cutoff";
}


// }
