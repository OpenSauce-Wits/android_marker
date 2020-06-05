. ./set_env_vars.sh

adb devices | grep emulator > $MARKER_LOGS/AVD.log 2>$MARKER_LOGS/AVD.log
