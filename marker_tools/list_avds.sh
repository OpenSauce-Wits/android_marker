. ./set_env_vars.sh

echo "[[$0 at `date`]]" > $MARKER_LOGS/AVD.log

avdmanager list avds | grep Name: >> $MARKER_LOGS/AVD.log

if [ $? != 0 ] ; then
	echo "[[$0 at `date`]]" >> $MARKER_LOGS/AVD.log
	echo "FAILURE: A command failed." >> $MARKER_LOGS/AVD.log
fi
