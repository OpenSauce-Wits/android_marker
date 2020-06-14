read GRADLE_TASK
#success will hold the return status of our script per command, with $SUCCESS=0 representing a pass/no error
SUCCESS=0

. ./set_env_vars.sh

if [ $? != 0 ] ; then
	$SUCCESS=1
fi

chmod -R 777 $MARKER_DATA/marker_server/android_project

if [ $? != 0 ] ; then
	$SUCCESS=1
fi

chmod -R 777 $MARKER_DATA/marker_server/

if [ $? != 0 ] ; then
	$SUCCESS=1
fi

cd $MARKER_DATA/marker_server/android_project

if [ $? != 0 -o $SUCCESS != 0 ] ; then
	$SUCCESS=1
else
	./gradlew $GRADLE_TASK --no-daemon > $MARKER_LOGS/GRADLE.log 2> $MARKER_LOGS/GRADLE.log
fi

if [ $? != 0 -o $SUCCESS != 0 ] ; then
	#report error, remember marker looks for "FAILURE" inside the logs to detect an error
	echo >> $MARKER_LOGS/GRADLE.log
	echo "[[$0 at `date`]]" >> $MARKER_LOGS/GRADLE.log
	echo $'FAILURE: A command failed to execute.' >> $MARKER_LOGS/GRADLE.log
fi
