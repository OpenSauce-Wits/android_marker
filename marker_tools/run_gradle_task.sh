read GRADLE_TASK

. ./set_env_vars.sh

chmod -R 0777 $MARKER_DATA/marker_server/android_project

cd $MARKER_DATA/marker_server/android_project

./gradlew $GRADLE_TASK > $MARKER_LOGS/GRADLE.log 2> $MARKER_LOGS/GRADLE.log
