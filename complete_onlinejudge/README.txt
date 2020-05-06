Description :
This version of the onlinejudge can only mark c code by I/O comparision.
Requirements :
- LAMP server
- Full moodle installation.

Setup :
- Copy the folder onlinejudge/ from moodle_local to <PATH_TO_MOODLE_INSTALLATION>/local/
- Copy the folder onlinejudge/ from moodle_mod_... to <PATH_TO_MOODLE_INSTALLATION>/mod/assign/feedback/

Use :
Assignment creation :
- Admin/Teacher creates an assignment
- Selects onlinejudge under feedback types
- Choose 'c (run locally)' under language options
- Change other settings and click 'save and display'

Judging :
- Start the judge daemon by running '<PATH_TO_MOODLE_INSTALLATION>/local/onlinejudge/cli/run_judged'
NB : The marker can only mark the submissions through I/O.
- Go to 'test case management' inside the assignment view as the admin/teacher
- Specify input that should be given and the expected output. The student's main function will be run, given input and it's output will be compared to that specified in the output cell under test case management.
- Login as a student or switch role to student and make a submission. Wait for a few seconds and refresh page.

