echo "STARTING EMULATOR"
AVD_ADDITIONAL_OPTS=""		#< specify any addtional boot options here
AVD_NAME="emulator"
AVD_OPTIONS="-show-kernel -no-audio -no-window -no-boot-anim -netdelay none -no-snapshot -wipe-data -gpu swiftshader_indirect -camera-back none -camera-front none $AVD_ADDITIONAL_OPTS" 

START_COMMAND="emulator @${AVD_NAME} ${AVD_OPTIONS}"
$START_COMMAND 2> /dev/null 1>/dev/null & #2> /tmp/emulator.stderr 1> /tmp/emulator.stdout &	#< for the time being we'll redirect std streams to these files which will be created in temporary directories.
