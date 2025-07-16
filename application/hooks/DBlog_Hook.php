<?php 
class DBlog_Hook {

    //SETUP HOOK:
    // 1. Copy this file to the directory
    // 2. Enable Hooks In config.php
    // 3. Add Post Controller in hooks.php
    // 4. Done.


    function logQueries() {
        $CI = & get_instance();
        $filepath = APPPATH . 'logs/Query-log-timestamp-' . date('Y-m-d') . '.csv'; // Creating Query Log file with today's date in application/logs folder
        $dt= date('Y-m-d H:m:s');
        $handle = fopen($filepath, "a+");                 // Opening file with pointer at the end of the file
        $times = $CI->db->query_times;                   // Get execution time of all the queries executed by controller
        foreach ($CI->db->queries as $key => $query) { 
            $sql = $dt.','.$CI->uri->uri_string.',"'.$query.'",Execution Time:' . $times[$key]."\n"; // Generating SQL file alongwith execution time
            fwrite($handle, $sql);              // Writing it in the log file
        }
        fclose($handle);      // Close the file
    }
}
?>