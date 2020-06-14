#REQUIREMENTS - placed in marker_tools dir
#DEFINE PATH TO SCRAPPY
#RETURN - echos results to a json file in the logs
. ./set_env_vars.sh

#reads in specified path to android project
read ABS_PATH_TO_PROJECT

##prepare paths
PREFIX_PATH="/app/build/reports/"
UNIT_PATH="tests/testDebugUnitTest/classes/"
INSTRUMENTED_PATH="androidTests/connected/"

UNIT_HTML=`find "$ABS_PATH_TO_PROJECT$PREFIX_PATH$UNIT_PATH" | grep ExampleUnitTest.html`
INSTRUMENTED_HTML=`find "$ABS_PATH_TO_PROJECT$PREFIX_PATH$INSTRUMENTED_PATH" | grep ExampleInstrumentedTest.html`

echo $UNIT_HTML

echo ""

echo $INSTRUMENTED_HTML

#move to scrappy dir
cd $PATH_TO_SCRAPPY

##launch scrappy
#scrapy crawl gradle -a abs="$UNIT_HTML&$INSTRUMENTED_HTML" -o $MARKER_LOGS/SCRAPPY_OUT.json 2> $MARKER_LOGS/GRADLE.log > $MARKER_LOGS/GRADLE.log
