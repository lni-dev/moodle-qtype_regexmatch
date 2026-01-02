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
 * Mobile output class for qtype_regexmatch
 *
 * @package    qtype_regexmatch
 * @copyright  2025 Linus Andera
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace qtype_regexmatch\output;

defined('MOODLE_INTERNAL') || die();

/**
 * Mobile output class for regexmatch question type
 *
 * @package    qtype_regexmatch
 */
class mobile {
    /**
     * Returns the regexmatch question type for the quiz the mobile app.
     *
     * @return void
     */
    public static function mobile_get_regexmatch() {
        global $CFG;
        return [
            'templates' => [
                [
                    'id' => 'main',
                    'html' => file_get_contents($CFG->dirroot . '/question/type/regexmatch/mobile/qtype-regexmatch.html'),
                    ],
            ],
            'javascript' => file_get_contents($CFG->dirroot . '/question/type/regexmatch/mobile/mobile.js'),
        ];
    }
}
