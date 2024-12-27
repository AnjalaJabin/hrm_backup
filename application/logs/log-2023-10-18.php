<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2023-10-18 09:23:20 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:23:20 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:23:20 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:23:20 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:23:20 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:23:57 --> Severity: Notice --> Trying to get property 'date_of_joining' of non-object /home/emsohrmg4demo/public_html/hrm/application/controllers/employee/Annual_leave.php 405
ERROR - 2023-10-18 09:23:57 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND status = 2 
        AND root_id = 217' at line 10 - Invalid query: 
    SELECT SUM(
        DATEDIFF(
            LEAST(LAST_DAY(CONCAT('2023','-12-01')), end_date),
            GREATEST(CONCAT('2023','-01-01'), start_date)
        ) + 1
    ) AS leave_days
    FROM annual_leave
    WHERE (YEAR(start_date) = 2023 OR YEAR(end_date) = 2023)
        AND employee_id =  
        AND status = 2 
        AND root_id = 217

ERROR - 2023-10-18 09:23:57 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/emsohrmg4demo/public_html/hrm/system/core/Exceptions.php:271) /home/emsohrmg4demo/public_html/hrm/system/core/Common.php 570
ERROR - 2023-10-18 09:24:02 --> Severity: Notice --> Trying to get property 'date_of_joining' of non-object /home/emsohrmg4demo/public_html/hrm/application/controllers/employee/Annual_leave.php 405
ERROR - 2023-10-18 09:24:02 --> Query error: You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near 'AND status = 2 
        AND root_id = 217' at line 10 - Invalid query: 
    SELECT SUM(
        DATEDIFF(
            LEAST(LAST_DAY(CONCAT('2023','-12-01')), end_date),
            GREATEST(CONCAT('2023','-01-01'), start_date)
        ) + 1
    ) AS leave_days
    FROM annual_leave
    WHERE (YEAR(start_date) = 2023 OR YEAR(end_date) = 2023)
        AND employee_id =  
        AND status = 2 
        AND root_id = 217

ERROR - 2023-10-18 09:24:02 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at /home/emsohrmg4demo/public_html/hrm/system/core/Exceptions.php:271) /home/emsohrmg4demo/public_html/hrm/system/core/Common.php 570
ERROR - 2023-10-18 09:24:11 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:24:11 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/user/annual_leave.php 70
ERROR - 2023-10-18 09:24:15 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:24:29 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:24:29 --> Severity: Notice --> Undefined variable: all_leave_types /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:24:29 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:24:29 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:24:29 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:24:35 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:24:45 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:24:46 --> Severity: Notice --> Undefined variable: all_leave_types /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:24:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:24:46 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:24:46 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:26:33 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:26:33 --> Severity: Notice --> Undefined variable: all_leave_types /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:26:33 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:26:33 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:26:33 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:27:02 --> Severity: Notice --> Undefined variable: all_leave_types /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:27:02 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:27:02 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:27:02 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:27:05 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:27:27 --> 404 Page Not Found: /index
ERROR - 2023-10-18 09:27:27 --> Severity: Notice --> Undefined variable: all_leave_types /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:27:27 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 26
ERROR - 2023-10-18 09:27:27 --> Severity: Notice --> Undefined variable: all_employees /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
ERROR - 2023-10-18 09:27:27 --> Severity: Warning --> Invalid argument supplied for foreach() /home/emsohrmg4demo/public_html/hrm/application/views/timesheet/leave.php 51
