<?php
require_once( __DIR__.'/../../../../config.php') ;//makes $DB object available
require_once( __DIR__.'/../../lib.php') ; //for the assign grade item function
//DESCRIPTION : This script will contain the library functions required to perform grade and feedback insertion as well as query feedback for some submission
//RESIDENCE : /moodle/mod/assign/feedback/onlinejudge/

//manually inserts the final grade for a student
//@args array with the following parameters
//@param courseid
//@param assignmentid
//@param studentid
//@param finalgrade
//@param feedback - string of testcase results in 'json format' style
function insert_grade( $args)
{
	global $DB ;

	//add to mdl_grade_grades and mdl_grade_items
	$params = array('moduletype'=>'assign', 'courseid'=>$args['courseid']);
	$sql = 'SELECT a.*, cm.idnumber as cmidnumber, a.course as courseid FROM {assign} a, {course_modules} cm, {modules} m WHERE m.name=:moduletype AND m.id=cm.module AND cm.instance=a.id AND a.course=:courseid';
	$assignment = NULL ;
	if ($assignments = $DB->get_records_sql($sql, $params)) {
	   foreach ($assignments as $amt) {
	   	$assignment = $amt ;
	   }
	}

	$grade = new stdClass() ;
	$grade->assignment = $args['assignmentid']; #< assignment id
	$grade->userid =  $args['studentid']; #< student
	$grade->timecreated = time() ;
	$grade->timemodified = $grade->timecreated ;
	$grade->grader = 2 ; #< admin id
	$grade->grade = $args['finalgrade'] ; #< grade to assign this user
	$grade->locked = 0 ;
	$grade->mailed = 0 ;
	
	//check if grade exists and overwrite if so else add new one
	$checkIfGraded = $DB->get_record( 'assign_grades', array( 'userid' => $grade->userid, 'assignment' => $grade->assignment)) ;
	
	if( $checkIfGraded)
	{
		$grade->id = $checkIfGraded->id ;
		$result = $DB->update_record( 'assign_grades', $grade) ;
	}
	else
	{
	
		$result = $DB->insert_record( 'assign_grades', $grade) ;
	}
	
	if ( $result)
	{
		$grade2 = new stdClass() ;
		$grade2->userid = $grade->userid ;
		$grade2->rawgrade = $grade->grade ;
		$grade2->usermodified = $grade->grader ;
		$grade2->timecreated = $grade->timecreated ;
		$grade2->timemodified = $grade->timemodified ;
		
		add_oj_feedback( $args['feedback'], $grade->userid, $grade->assignment) ;
	
		assign_grade_item_update( $assignment, $grade2) ;
	}
	
}

//we added another table mdl_onlinejudge_submission_feedback( id bigint(10) AUTO_INCREMENT, userid bigint( 10) NOT NULL, assignmentid bigint(10) NOT NULL, feedback longtext, PRIMARY KEY(id)) which will hold the feedback results to be displayed in the details section of the onlinjudge results
//@param $string - feedback to be given to student i.e testcase results
function add_oj_feedback( $string, $studentid, $assignmentid)
{
	global $DB ;
	//we'll add checks to avoid issues with double insertions
	$checkIfGraded = $DB->get_record( 'onlinejudge_submission_feedback', array( 'userid' => $studentid, 'assignmentid' => $assignmentid)) ;
	if( !$checkIfGraded)
	{
		return $DB->insert_record( 'onlinejudge_submission_feedback', array( 'userid' => $studentid, 'assignmentid' => $assignmentid, 'feedback' => $string)) ;
	}
	else
	{
		//added id value as moodle update_record requires this
		$checkIfGraded->feedback = $string ; #<update old feedback with new one
		return $DB->update_record( 'onlinejudge_submission_feedback', $checkIfGraded) ;
	}
}
function get_oj_feedback( $userid, $assignmentid)
{
	global $DB ;

	$checkIfGraded = $DB->get_record( 'assign_grades', array( 'userid' => $userid, 'assignment' => $assignmentid)) ;
	if( $checkIfGraded)
	{
		$params = array( 'userid' => $userid, 'assignmentid' => $assignmentid);
		$record = $DB->get_record( 'onlinejudge_submission_feedback',  array( 'userid' => $userid, 'assignmentid' => $assignmentid));
		if( $record)
		{
			return $record->feedback ;
		}
	}

	return "Feedback not available." ;
}
?>
