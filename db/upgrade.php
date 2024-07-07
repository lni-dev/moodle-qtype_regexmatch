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

    if ($oldversion < 2024290502) {

        // Define table question_regexmatch_answers to be created.
        $table = new xmldb_table('question_regexmatch_answers');

        // Adding fields to table question_regexmatch_answers.
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('answerid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('ignorecase', XMLDB_TYPE_INTEGER, '2', null, null, null, null);
        $table->add_field('dotall', XMLDB_TYPE_INTEGER, '2', null, null, null, null);

        // Adding keys to table question_regexmatch_answers.
        $table->add_key('primary', XMLDB_KEY_PRIMARY, ['id']);
        $table->add_key('answerid', XMLDB_KEY_FOREIGN, ['answerid'], 'question_answers', ['id']);

        // Conditionally launch create table for question_regexmatch_answers.
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Regexmatch savepoint reached.
        upgrade_plugin_savepoint(true, 2024290502, 'qtype', 'regexmatch');

    }

    if ($oldversion < 3000000002) {

        // Define field infspace to be added to question_regexmatch_answers.
        $table = new xmldb_table('question_regexmatch_answers');
        $field = new xmldb_field('infspace', XMLDB_TYPE_INTEGER, '2', null, null, null, '0', 'dotall');

        // Conditionally launch add field infspace.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Regexmatch savepoint reached.
        upgrade_plugin_savepoint(true, 3000000002, 'qtype', 'regexmatch');
    }

    return true;
}
