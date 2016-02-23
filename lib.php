<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * QR links library.
 *
 * @package    local_qrlinks
 * @copyright  2016 Nicholas Hoobin <nicholashoobin@catalyst-au.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.'); // It must be included from a Moodle page.
}

/**
 * Extends the navigation block and adds a new node 'Create QR link'. This is visible if the current
 * user has the local/qrlinks:create capability.
 *
 * @param global_navigation $nav
 */
function local_qrlinks_extend_navigation(global_navigation $nav) {
    global $CFG, $PAGE, $FULLME, $SESSION;

    $courseid = $PAGE->course->id;

    // Only add this settings item on non-site course pages.
    /*
    if (!$PAGE->course or $courseid == 1) {
        return;
    }
    */

    if (has_capability('local/qrlinks:create', context_system::instance())) {
        $str = get_string('nagivationlink', 'local_qrlinks');
        $url = new moodle_url('/local/qrlinks/qrlinks_edit.php');
        $linknode = $nav->add($str, $url);
    }

}

/**
 * Extends the settings navigation and adds a new node 'Create QR link'. This is visible if the current
 * user has the local/qrlinks:create capability.
 *
 * @param settings_navigation $nav
 * @param context $context
 */
function local_qrlinks_extend_settings_navigation(settings_navigation $nav, context $context) {
    global $CFG, $PAGE, $FULLME, $SESSION;

    $courseid = $PAGE->course->id;
    $linkparams = array('cid' => $courseid);

    if ($PAGE->cm) {
        $module = $PAGE->cm->id;
        $linkparams = array('cid' => $courseid, 'cmid' => $module);
    }

    // Only add this settings item on non-site course pages.
    if (!$PAGE->course or $courseid == 1) {
        return;
    }

    // https://docs.moodle.org/dev/Local_plugins
    // if ($settingsnode = $nav->find('siteadministration', navigation_node::TYPE_SITE_ADMIN)) {
    if ($settingsnode = $nav->find('courseadmin', navigation_node::TYPE_COURSE)) {
        $str = get_string('nagivationlink', 'local_qrlinks');
        $url = new moodle_url('/local/qrlinks/qrlinks_edit.php', $linkparams);
        $node = navigation_node::create(
                $str,
                $url,
                navigation_node::NODETYPE_LEAF,
                'qrlinks',
                'qrlinks',
                new pix_icon('t/edit', $str)
        );

        if ($PAGE->url->compare($url, URL_MATCH_BASE)) {
            $node->make_active();
        }

        if (has_capability('local/qrlinks:create', context_system::instance())) {
            $settingsnode->add_node($node);
        }

    }

}

