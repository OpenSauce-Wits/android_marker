AVD_ADDITIONAL_OPTS=""		#< specify any addtional boot options here

AVD_NAME="emulator"

emulator @$AVD_NAME "-show-kernel -no-audio -no-window -no-boot-anim -netdelay none -no-snapshot -wipe-data -gpu swiftshader_indirect -camera-back none -camera-front none $AVD_ADDITIONAL_OPTS"   1> emulatorLog.stdout 2> emulatorLog.stderr &	#< for the time being we'll redirect std streams to these files which will be created in working directory.
