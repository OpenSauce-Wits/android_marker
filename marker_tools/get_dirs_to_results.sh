#REQUIREMENTS - placed in marker_tools dir
#RETURN - echos path to unit tests and instr tests seperated by & 
. ./set_env_vars.sh

#reads in specified path to android project
read ABS_PATH_TO_PROJECT

##prepare paths
PREFIX_PATH="/app/build/reports/"
UNIT_PATH="tests/testDebugUnitTest/classes/"
INSTRUMENTED_PATH="androidTests/connected/"

UNIT_HTML=`find "$ABS_PATH_TO_PROJECT$PREFIX_PATH$UNIT_PATH" | grep ExampleUnitTest.html`
INSTRUMENTED_HTML=`find "$ABS_PATH_TO_PROJECT$PREFIX_PATH$INSTRUMENTED_PATH" | grep ExampleInstrumentedTest.html`

echo "$UNIT_HTML&$INSTRUMENTED_HTML" > $MARKER_LOGS/FEEDBACK.log
