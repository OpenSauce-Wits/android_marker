Description :
This version of the onlinejudge can only mark c code by I/O comparision.
Requirements :
- LAMP server
- Full moodle installation.

### 1.Setting pre-requisites for OJ
1.  git clone [github.com/hit-moodle/moodle-local_onlinejudge.git](https://github.com/hit-moodle/moodle-local_onlinejudge.git)
2.  rename folder 'moodle-local_onlinejudge' to 'onlinejudge'
3.  move onlinejudge/ to moodle/local/
4.  run onlinejudge/cli/install_assign_feedback to add onlinejudge as one of the assignment feedback types on moodle.
5.  Run "ls /var/www/html/moodle/mod/assign/feedback/" to check if onlinejudge appears.

### 2.Setting online judge v1.0
1. git clone https://github.com/OpenSauce-Wits/android_marker.git or git checkout online_judge && git pull origin online_judge if you have it.
2. `git chekout tags/v1.0` to get the version 1 of the online judge
3. `rm -rf <dir_to_moodle>/local/onlinejudge && rm -rf <dir_to_moodle>/mod/assign/feedback/onlinejudge` to delete the onlinejudge from your server
4. `cd ~/android_marker/completer_onlinejudge/` and copy the 'online judge' directories to their respective paths inside <dir_to_moodle> on your server. `cp -R /moodle_local/onlinejudge <dir_to_moodle>/local/ && cp -R /moodle_mod_assign_feedback/onlinejudge <dir_to_moodle>/mod/assign/feedback/`
NB : You now have v1 of the onlinejudge from opensauce.

Judging :
- Start the judge daemon by running '<PATH_TO_MOODLE_INSTALLATION>/local/onlinejudge/cli/run_judged'
NB : The marker can only mark the submissions through I/O.
- Go to 'test case management' inside the assignment view as the admin/teacher
- Specify input that should be given and the expected output. The student's main function will be run, given input and it's output will be compared to that specified in the output cell under test case management.
- Login as a student or switch role to student and make a submission. Wait for a few seconds and refresh page.

