### Description :
This version of the onlinejudge can mark android project and return a grade from 0% to 100%
Requirements :
- LAMP server
- Full moodle installation.
- OpenSauce onlinejudge plugin

### Setup :
- Copy the folder onlinejudge/ from moodle_oj_local to <PATH_TO_MOODLE_INSTALLATION>/local/
- Copy the folder onlinejudge/ from moodle_oj_mod_... to <PATH_TO_MOODLE_INSTALLATION>/mod/assign/feedback/

### Usage :
Assignment creation :
- Admin/Teacher creates an assignment
- Selects onlinejudge under feedback types
- Choose ' JavaZip( OpenSauce)' under language options
- Change other settings and click 'save and display'

### Judging :
- Start the judge daemon by running '<PATH_TO_MOODLE_INSTALLATION>/local/onlinejudge/cli/run_judged'
- Go to 'test case management' inside the assignment view as the admin/teacher
- The testcases are in the form of files :
	- submit a input.json file that contains directories to to be retrieved from student submission in the format. This should be submitted under input files.
	- the format of the input.json is `[ 
						{ "name_of_file": [ "dir/to/file/in/student/submission", "dest/of/file/in/final/project"]} ,
						{ "" : "dir/to/folder/in/student/submission", "dest/of/folder/in/final/project"}
					   ]`
	- submit a zip file containing the android project contents without the parts to be submitted by the student. This should be submitted under output files.
- Login as a student or switch role to student and make a submission. Wait for a few seconds and refresh page.
- You overall grade should be displayed along with any additional info.

#### Coming soon :
- html view of testcases.
### Build Status
[![Build Status](https://travis-ci.org/OpenSauce-Wits/android_marker.svg?branch=master)](https://travis-ci.org/OpenSauce-Wits/android_marker)
### Code coverage
[![Coverage Status](https://coveralls.io/repos/github/OpenSauce-Wits/android_marker/badge.svg?branch=master)](https://coveralls.io/github/OpenSauce-Wits/android_marker?branch=master)
