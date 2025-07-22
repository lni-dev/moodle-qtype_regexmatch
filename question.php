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
 * regexmatch question definition class.
 *
 * @package    qtype_regexmatch
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

if (!class_exists('qtype_regexmatch_common_regex')) {
    require_once($CFG->dirroot . '/question/type/regexmatch/common/common.php');
}

/**
 * @var array Allowed keys for regexmatch
 */
const QTYPE_REGEXMATCH_ALLOWED_KEYS = array(QTYPE_REGEXMATCH_COMMON_SEPARATOR_KEY, QTYPE_REGEXMATCH_COMMON_COMMENT_KEY);
/**
 * @var array Allowed options for regexmatch
 */
const QTYPE_REGEXMATCH_ALLOWED_OPTIONS = array('I', 'D', 'P', 'R', 'O', 'S', 'T', 'i', 'd', 'p', 'r', 'o', 's', 't');


/**
 * Represents a regexmatch question.
 *
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_regexmatch_question extends question_graded_automatically {

    /**
     * @var array<qtype_regexmatch_common_answer> array containing all the allowed regexes
     */
    public $answers = array();
    /**
     * @var mixed options
     */
    public $options = array();

    /**
     * Whether given response is a complete answer to this question
     * @param array $response responses, as returned by
     *       question_attempt_step::get_qt_data().
     * @return bool true if the response is complete
     */
    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) &&
            ($response['answer'] || $response['answer'] === '0');
    }

    /**
     * Validation errors for given response
     * @param array $response the response
     * @return string empty string (no error) or error string
     */
    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_regexmatch');
    }

    /**
     * Checks whether given responses are the same response.
     * @param array $prevresponse the responses previously recorded for this question,
     *      as returned by question_attempt_step::get_qt_data()
     * @param array $newresponse the new responses, in the same format.
     * @return bool whether the two sets of responses are the same - that is
     *      whether the new set of responses can safely be discarded.
     */
    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key(
            $prevresponse, $newresponse, 'answer');
    }

    /**
     * Data which is expected to be retrieved from the front end inputs
     * @return array
     */
    public function get_expected_data() {
        return array('answer' => PARAM_RAW);
    }

    /**
     * Summarise a response into a single string
     * @param array $response a response, as might be passed to grade_response().
     * @return mixed|null
     */
    public function summarise_response(array $response) {
        return $response['answer'] ?? null;
    }

    /**
     * Get the response from a summary
     * @param string $summary a string, which might have come from summarise_response
     * @return array|string[]
     */
    public function un_summarise_response(string $summary): array {
        if (!empty($summary)) {
            return ['answer' => $summary];
        } else {
            return [];
        }
    }

    /**
     * Get the regex with the highest fraction for given answer
     * @param string $answer answer submitted from a student
     * @return mixed|null regex of $answers, which matches given answer or null if none matches
     */
    public function get_regex_for_answer(string $answer) {
        $ret = null;

        $answer = str_replace("\r", "", $answer);

        foreach ($this->answers as $correctanswer) {
            $value = qtype_regexmatch_common_try_regex($correctanswer, $correctanswer->regexes[0], $answer);

            if ($value > 0.0) {
                $value *= $correctanswer->fraction;
                if ($ret == null || ($correctanswer->fraction * $value) > $ret->fraction) {
                    $ret = $value == 1.0 ? $correctanswer : new qtype_regexmatch_common_answer(
                        $correctanswer->id,
                        $correctanswer->answer,
                        $correctanswer->fraction * $value,
                        $correctanswer->feedback,
                        $correctanswer->feedbackformat
                    );
                }
            }
        }

        return $ret;
    }

    /**
     * Get the fraction and graded state for given response
     * @param array $response
     * @return array fraction and graded state
     */
    public function grade_response(array $response): array {
        $submittedanswer = $response['answer'] ?? null;
        $fraction = 0;

        if ($submittedanswer != null) {
            $regex = $this->get_regex_for_answer($submittedanswer);
            if ($regex != null) {
                $fraction = $regex->fraction;
            }
        }

        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    /**
     * Not possible.
     * @return null
     */
    public function get_correct_response() {
        return null;
    }

    /**
     * Does nothing.
     * @param array $response a response
     * @return array a cleaned up response with the wrong bits reset.
     */
    public function clear_wrong_from_response(array $response) {
        // We want to keep the previous answer as it is only a single answer field
        return $response;
    }

    /**
     * Checks whether the user is allow to be served a particular file.
     * @param question_attempt $qa the question attempt being displayed.
     * @param question_display_options $options the options that control display of the question.
     * @param string $component the name of the component we are serving files for.
     * @param string $filearea the name of the file area.
     * @param array $args the remaining bits of the file path.
     * @param bool $forcedownload whether the user must be forced to download the file.
     * @return bool true if the user can access this file.
     */
    public function check_file_access($qa, $options, $component, $filearea,
            $args, $forcedownload) {
        if ($component == 'question' && $filearea == 'hint') {
            return $this->check_hint_file_access($qa, $options, $args);
        } else {
            return parent::check_file_access($qa, $options, $component, $filearea,
                    $args, $forcedownload);
        }
    }
}


