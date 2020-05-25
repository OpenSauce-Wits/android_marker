<?php

/**
 * @file config.php
 * Global configurations. This file is included in all scripts.
 */
// Error reporting/warning must be off for the web service to work
//  - they interfere with sending JSON strings.
//error_reporting(E_ALL);
//ini_set('display_errors', '1');

/**
 * Statically wraps around global variables
 */
class settings {

    public static $temp;        ///< Prefix for temp folder
    public static $keep_files;  ///< Delete folders when the marker completes
    public static $testcases; ///< Folder to store downloaded test cases
    public static $auth_token; ///< Folder to store downloaded test cases
    public static $source;
    public static $getfiles_url;
    public static $project_dir ;
    public static $source_structure ;
}

settings::$keep_files = true;
settings::$testcases = "/tmp/marker_server/testcases";
settings::$source = "/tmp/marker_server/source" ;
settings::$source_structure = "/tmp/marker_server/source_structure" ;
settings::$auth_token = array("witsoj_token" => "1e6947ac7fb3a9529a9726eb692c8cc5", "witsoj_name" => "marker.ms.wits.ac.za");
settings::$project_dir = "/tmp/marker_server/android_project" ;
settings::$temp = "/tmp/marker_server";
settings::$getfiles_url= "http://192.168.17.128/get_files.php";

//OJ return statuses
define("ONLINEJUDGE_STATUS_PENDING", 0);
define("ONLINEJUDGE_STATUS_ACCEPTED", 1);
define("ONLINEJUDGE_STATUS_ABNORMAL_TERMINATION", 2);
define("ONLINEJUDGE_STATUS_COMPILATION_ERROR", 3);
define("ONLINEJUDGE_STATUS_COMPILATION_OK", 4);
define("ONLINEJUDGE_STATUS_MEMORY_LIMIT_EXCEED", 5);
define("ONLINEJUDGE_STATUS_OUTPUT_LIMIT_EXCEED", 6);
define("ONLINEJUDGE_STATUS_PRESENTATION_ERROR", 7);
define("ONLINEJUDGE_STATUS_RESTRICTED_FUNCTIONS", 8);
define("ONLINEJUDGE_STATUS_RUNTIME_ERROR", 9);
define("ONLINEJUDGE_STATUS_TIME_LIMIT_EXCEED", 10);
define("ONLINEJUDGE_STATUS_WRONG_ANSWER", 11);

define("ONLINEJUDGE_STATUS_INTERNAL_ERROR", 21);
define("ONLINEJUDGE_STATUS_JUDGING", 22);
define("ONLINEJUDGE_STATUS_MULTI_STATUS", 23);

define("ONLINEJUDGE_STATUS_UNSUBMITTED", 255);

?>
