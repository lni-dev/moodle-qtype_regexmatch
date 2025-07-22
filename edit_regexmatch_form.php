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

declare(strict_types=1);

/**
 * Defines the editing form for the regexmatch question type.
 *
 * @package    qtype_regexmatch
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
     * Add specific form fields for editing
     * @param MoodleQuickForm $mform
     */
    protected function definition_inner($mform) {
        $this->add_per_answer_fields(
            $mform,
            get_string('regex-number', 'qtype_regexmatch', '{no}'),
            question_bank::fraction_options()
        );

        // Add Help Button to the first to 5th answer text field
        // Add (?) / help button
        for ($i = 0; $i < 10; $i++) {
            $mform->addHelpButton("answer[$i]", 'regex', 'qtype_regexmatch', '', true);
            $mform->addHelpButton("options[$i]", 'options', 'qtype_regexmatch', '', true);
            $mform->addHelpButton("default-options[$i]", 'default_options', 'qtype_regexmatch', '', true);
        }

        $this->add_interactive_settings();
    }

    /**
     * Get the list of form elements to repeat, one for each answer.
     * @param MoodleQuickForm $mform  the form being built.
     * @param mixed $label the label to use for each option.
     * @param mixed $gradeoptions the possible grades for each answer.
     * @param mixed $repeatedoptions reference to array of repeated options to fill
     * @param mixed $answersoption reference to return the name of $question->options
     *      field holding an array of answers
     * @return array of form fields.
     */
    protected function get_per_answer_fields(
        $mform,
        $label,
        $gradeoptions,
        &$repeatedoptions,
        &$answersoption
    ) {
        $repeated = array();

        // Help button added in definition_inner
        $repeated[] = $mform->createElement('textarea',
            'answer',
            $label,
            array('size' => 1000)
        );

        $repeated[] = $mform->createElement('static', 'options', get_string('options', 'qtype_regexmatch'), 'I, D, P, R, O');
        $repeated[] = $mform->createElement('static', 'default-options', get_string('default_options', 'qtype_regexmatch'), 'S, T');

        $repeated[] = $mform->createElement('select',
            'fraction',
            get_string('gradenoun'),
            $gradeoptions
        );

        $repeated[] = $mform->createElement('editor',
            'feedback',
            get_string('feedback', 'question'),
            array('rows' => 5),
            $this->editoroptions
        );

        $repeatedoptions['answer']['type'] = PARAM_RAW;
        $repeatedoptions['fraction']['default'] = 0;
        $answersoption = 'answers';
        return $repeated;
    }

    /**
     * Perform an preprocessing needed on the data passed to set_data()
     * before it is used to initialise the form.
     * @param object $question the data being passed to the form.
     * @return object $question the modified data.
     */
    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_answers($question);
        $question = $this->data_preprocessing_hints($question);

        return $question;
    }

    /**
     * validate regex syntax
     *
     * @param array $fromform array of ("fieldname"=>value) of submitted data
     * @param array $files array of uploaded files "element_name"=>tmp_file_path
     * @return array of "element_name"=>"error_description" if there are errors,
     *         or an empty array if everything is OK (true allowed for backwards compatibility too).
     */
    public function validation($fromform, $files): array {
        $errors = parent::validation($fromform, $files);

        $answers = $fromform['answer'];

        $answercount = 0;
        $maxgrade = false;

        foreach ($answers as $key => $answer) {
            if ($answer !== '') {
                $answercount++;
                if ($fromform['fraction'][$key] == 1) {
                    $maxgrade = true;
                }

                // check syntax
                if (preg_match('/(?<!\\\\)(\\\\\\\\)*[$^]/', $fromform['answer'][$key]) == 1) {
                    $errors["answer[$key]"] = get_string('dollarroofmustbeescaped', 'qtype_regexmatch');
                }

                // check syntax
                if (preg_match('%^(\[\[.*\]\]\\n? *)+/[a-zA-Z]*/.*$%s', $fromform['answer'][$key]) != 1) {
                    $errors["answer[$key]"] = get_string('valerror_illegalsyntax', 'qtype_regexmatch');
                } else {
                    if (preg_match("%]][ \\n]*/[a-zA-Z]*/%", $fromform['answer'][$key], $matches, PREG_OFFSET_CAPTURE)) {
                        $index = intval($matches[0][1]);

                        // Options E.g.: "OPTIONS"
                        $options = substr($matches[0][0], 2); // first remove the "]]" at the beginning
                        $options = trim($options); // Now trim all spaces at the beginning and end
                        $options = substr($options, 1, strlen($options) - 2); // remove first and last "/"

                        foreach (str_split($options) as $option) {
                            $found = false;
                            foreach (QTYPE_REGEXMATCH_ALLOWED_OPTIONS as $allowed) {
                                if ($option == $allowed) {
                                    $found = true;
                                }
                            }

                            if (!$found) {
                                $errors["answer[$key]"] = get_string('valerror_illegaloption', 'qtype_regexmatch', $option);
                            }
                        }

                        // Key Value pairs
                        $keyvaluepairs = substr($fromform['answer'][$key], $index + strlen($matches[0][0]));
                        $nextkey = 0;
                        foreach (preg_split("/\\n/", $keyvaluepairs) as $keyvaluepair) {
                            if (preg_match("/[a-z]+=/", $keyvaluepair, $matches)) {
                                $match = $matches[0];
                                $found = false;
                                for (; $nextkey < count(QTYPE_REGEXMATCH_ALLOWED_KEYS); $nextkey++) {
                                    if ($match == QTYPE_REGEXMATCH_ALLOWED_KEYS[$nextkey]) {
                                        $found = true;
                                        break;
                                    }
                                }

                                if (!$found) {
                                    $isallowed = false;
                                    foreach (QTYPE_REGEXMATCH_ALLOWED_KEYS as $allowed) {
                                        if ($allowed == $match) {
                                            $isallowed = true;
                                            break;
                                        }
                                    }
                                    if ($isallowed) {
                                        $errors["answer[$key]"] = get_string('valerror_illegalkeyorder', 'qtype_regexmatch', implode(', ', QTYPE_REGEXMATCH_ALLOWED_KEYS));
                                    } else {
                                        $errors["answer[$key]"] = get_string('valerror_unkownkey', 'qtype_regexmatch', $match);
                                    }

                                }

                            }
                        }
                    }
                }





            } else if ($fromform['fraction'][$key] != 0 || !html_is_blank($fromform['feedback'][$key]['text'])) {
                $errors["answer[$key]"] = get_string('fborgradewithoutregex', 'qtype_regexmatch');
                $answercount++;
            }


        }

        if ($answercount == 0) {
            $errors['answer[0]'] = get_string('notenoughregexes', 'qtype_regexmatch');
        }

        if (!$maxgrade) {
            $errors['answer[0]'] = get_string('fractionsnomax', 'question');
        }

        return $errors;
    }

    /**
     * question type name
     * @return string
     */
    public function qtype() {
        return 'regexmatch';
    }
}
