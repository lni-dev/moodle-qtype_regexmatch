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
 * Defines the editing form for the regexmatch question type.
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();


/**
 * regexmatch question editing form definition.
 *
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_regexmatch_edit_form extends question_edit_form {

    /**
     * @param MoodleQuickForm $mform
     */
    protected function definition_inner($mform) {
        $this->add_per_answer_fields(
            $mform,
            get_string('regex-number', 'qtype_regexmatch', '{no}'),
            question_bank::fraction_options()
        );

        // Add Help Button to the first to third answer text field
        $mform->addHelpButton('answer[0]', 'regex', 'qtype_regexmatch', '', true);
        $mform->addHelpButton('answer[1]', 'regex', 'qtype_regexmatch', '', true);
        $mform->addHelpButton('answer[2]', 'regex', 'qtype_regexmatch', '', true);

        // TODO: Adds hinting features.
        //  $this->add_interactive_settings(true, true);
    }

    protected function get_per_answer_fields(
        $mform,
        $label,
        $gradeoptions,
        &$repeatedoptions,
        &$answersoption
    ) {
        $repeated = array();

        // Help button added in definition_inner
        $repeated[] = $mform->createElement('text', 'answer', $label, array('size' => 40));
        $repeated[] = $mform->createElement('select', 'fraction', get_string('gradenoun'), $gradeoptions);
        $repeated[] = $mform->createElement('editor', 'feedback', get_string('feedback', 'question'), array('rows' => 5), $this->editoroptions);

        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $repeatedoptions['fraction']['default'] = 0;
        $answersoption = 'answers';
        return $repeated;
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_answers($question);

        return $question;
    }

    public function validation($fromform, $files): array {
        $errors = parent::validation($fromform, $files);

        $answers = $fromform['answer'];

        $answerCount = 0;
        $maxGrade = false;

        foreach ($answers as $key => $answer) {
            if ($answer !== '') {
                $answerCount++;
                if ($fromform['fraction'][$key] == 1) $maxGrade = true;
            } else if ($fromform['fraction'][$key] != 0 || !html_is_blank($fromform['feedback'][$key]['text'])) {
                $errors["answer[$key]"] = get_string('fborgradewithoutregex', 'qtype_regexmatch');
                $answerCount++;
            }
        }

        if ($answerCount==0)
            $errors['answer[0]'] = get_string('notenoughregexes', 'qtype_regexmatch');

        if (!$maxGrade)
            $errors['answer[0]'] = get_string('fractionsnomax', 'question');

        return $errors;
    }

    public function qtype() {
        return 'regexmatch';
    }
}
