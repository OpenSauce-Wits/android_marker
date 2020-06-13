. ./set_env_vars.sh

adb devices | grep emulator > $MARKER_LOGS/AVD.log 2> $MARKER_LOGS/AVD.log

if [ $? != 0 ] ; then
	echo >> $MARKER_LOGS/AVD.log
	echo "[[$0 at `date`]]" >> $MARKER_LOGS/AVD.log
	echo "FAILURE: No device found." >> $MARKER_LOGS/AVD.log
fi
