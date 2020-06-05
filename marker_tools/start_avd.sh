. ./set_env_vars.sh

read AVD_NAME

AVD_ADDITIONAL_OPTS=""		#< specify any addtional boot options here
AVD_OPTIONS="-no-audio -no-window -no-boot-anim -netdelay none -no-snapshot -wipe-data -gpu swiftshader_indirect -camera-back none -camera-front none $AVD_ADDITIONAL_OPTS" 

START_COMMAND="emulator @${AVD_NAME} ${AVD_OPTIONS}"
$START_COMMAND > $MARKER_LOGS/AVD_ONLINE.log 2> $MARKER_LOGS/AVD_ONLINE.log
