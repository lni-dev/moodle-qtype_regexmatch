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
 * regexmatch question type  capability definition
 *
 * @package    qtype_regexmatch
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
defined('MOODLE_INTERNAL') || die();

$addons = [
    "qtype_regexmatch" => [
        "handlers" => [ // Different places where the add-on will display content.
            'regexmatch' => [ // Handler unique name (can be anything).
                'displaydata' => [
                    'title' => 'regexmatch question',
                    'icon' => '/question/type/regexmatch/pix/icon.gif',
                    'class' => '',
                ],
                'delegate' => 'CoreQuestionDelegate', // Delegate (where to display the link to the add-on).
                'method' => 'mobile_get_regexmatch',
                'offlinefunctions' => [
                    'mobile_get_regexmatch' => [],// function in classes/output/mobile.php
                ], // Function needs caching for offline.
                'styles' => []
            ]
        ],
        'lang' => [
                    ['pluginname', 'qtype_regexmatch'], // matching value in  lang/en/qtype_regexmatch
        ],
    ]
];
