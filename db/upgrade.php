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
 * Multi-answer question type upgrade code.
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the regexmatch question type.
 * A selection of things you might want to do when upgrading
 * to a new version. This file is generally not needed for
 * the first release of a question type.
 * @param int $oldversion the version we are upgrading from.
 */
function xmldb_qtype_regexmatch_upgrade($oldversion = 0) {
    global $CFG, $DB;

    $dbman = $DB->get_manager();

    if ($oldversion < 2024072500) {

        // Define field trimspaces to be added to question_regexmatch_answers.
        $table = new xmldb_table('question_regexmatch_answers');
        $field = new xmldb_field('trimspaces', XMLDB_TYPE_INTEGER, '2', null, null, null, '0', 'infspace');

        // Conditionally launch add field trimspaces.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field pipesemispace to be added to question_regexmatch_answers.
        $table = new xmldb_table('question_regexmatch_answers');
        $field = new xmldb_field('pipesemispace', XMLDB_TYPE_INTEGER, '2', null, null, null, '0', 'trimspaces');

        // Conditionally launch add field pipesemispace.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define field redictspace to be added to question_regexmatch_answers.
        $table = new xmldb_table('question_regexmatch_answers');
        $field = new xmldb_field('redictspace', XMLDB_TYPE_INTEGER, '2', null, null, null, '0', 'pipesemispace');

        // Conditionally launch add field redictspace.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Regexmatch savepoint reached.
        upgrade_plugin_savepoint(true, 2024072500, 'qtype', 'regexmatch');
    }

    return true;
}
