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
 * regexmatch question renderer class.
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * Generates the output for regexmatch questions.
 *
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_regexmatch_renderer extends qtype_renderer {
    public function formulation_and_controls(
        question_attempt $qa,
        question_display_options $options
    ): string {

        // regexmatch question
        $question = $qa->get_question();

        // Text to be displayed for this question (set when creating)
        $questiontext = $question->format_questiontext($qa);

        // The last answer, that the student entered (if any)
        $currentanswer = $qa->get_last_qt_var('answer');

        $result = "";

        // Add question text
        $result .= html_writer::tag('div', $questiontext, array('class' => 'qtext'));

        // Add input field
        $inputname = $qa->get_qt_field_name('answer');
        $inputattributes = array(
            'type' => 'text',
            'name' => $inputname,
            'value' => $currentanswer,
            'id' => $inputname,
            'size' => 80,
            'class' => 'form-control d-inline',
        );

        if ($options->readonly)
            $inputattributes['readonly'] = 'readonly';

        $result .= html_writer::empty_tag('input', $inputattributes);

        /* if ($qa->get_state() == question_state::$invalid) {
            $result .= html_writer::nonempty_tag('div',
                    $question->get_validation_error(array('answer' => $currentanswer)),
                    array('class' => 'validationerror'));
        }*/
        return $result;
    }

    public function specific_feedback(question_attempt $qa): string {
        /* @var qtype_regexmatch_question $question */
        $question = $qa->get_question();

        // The last answer, that the student entered (if any)
        $currentanswer = $qa->get_last_qt_var('answer');

        $feedback = '';
        if($currentanswer != null) {
            $fraction = 0;

            foreach ($question->answers as $regex) {
                if(preg_match("/" . str_replace("/", "\\/", $regex->answer) . "/", $currentanswer) == 1) {
                    if($regex->fraction > $fraction) {
                        $fraction = $regex->fraction;
                        $feedback = $question->format_text($regex->feedback, $regex->feedbackformat, $qa, 'question', 'answerfeedback', $regex->id);
                    }
                }
            }
        }

        return $feedback;
    }

    public function correct_response(question_attempt $qa): string {
        // TODO.
        return '';
    }
}
