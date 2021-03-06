<?php

/**
 * @package    mod_onlinejudge
 * @author     Andrew Nagyeb <andrewnajeeb at fci dot helwan dot edu dot eg>
 * @copyright  2018 Andrew Nagyeb
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_onlinejudge\event;

defined('MOODLE_INTERNAL') || die();

class onlinejudge_task_judged extends \core\event\base {

    /**
     * Returns localised general event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('event_onlinejudge_task_judged', 'local_onlinejudge');
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return get_string('event_onlinejudge_task_judged_description');
    }

    /**
     * Override in subclass.
     *
     * Set all required data properties:
     *  1/ crud - letter [crud]
     *  2/ edulevel - using a constant self::LEVEL_*.
     *  3/ objecttable - name of database table if objectid specified
     *
     * Optionally it can set:
     * a/ fixed system context
     *
     * @return void
     */
    protected function init() {
        $this->data['objecttable'] = 'onlinejudge_tasks';
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_TEACHING;

    }
}