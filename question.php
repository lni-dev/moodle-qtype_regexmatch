<?php declare(strict_types=1);
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
 * regexmatch question definition class.
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

if (!class_exists('qtype_regexmatch_common_regex')) {
    require_once($CFG->dirroot . '/question/type/regexmatch/common/common.php');
}

const REGEXMATCH_ALLOWED_KEYS = array(QTYPE_REGEXMATCH_SEPARATOR_KEY, QTYPE_REGEXMATCH_COMMENT_KEY);
const REGEXMATCH_ALLOWED_OPTIONS = array('I', 'D', 'P', 'R', 'O', 'S', 'T', 'i', 'd', 'p', 'r', 'o', 's', 't');

/**
*This holds the definition of a particular question of this type.
*If you load three questions from the question bank, then you will get three instances of
*that class. This class is not just the question definition, it can also track the current
*state of a question as a student attempts it through a question_attempt instance.
*/


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
    public $options = array();

    /**
     * Question attempt started
     * @param question_attempt_step $step
     * @param $variant
     * @return void
     */
    public function start_attempt(
        question_attempt_step $step,
        $variant
    ) {
        // probably not needed
    }

    /**
     * Whether given response is a complete answer to this question
     * @param array $response
     * @return bool true if the response is complete
     */
    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) &&
            ($response['answer'] || $response['answer'] === '0');
    }

    /**
     * Validation errors for given response
     * @param array $response
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
     * @param array $prevresponse
     * @param array $newresponse
     * @return bool true if both responses are the same.
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
     * @param array $response
     * @return mixed|null
     */
    public function summarise_response(array $response) {
        return $response['answer'] ?? null;
    }

    /**
     * Get the response from a summary
     * @param string $summary
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
     * @return mixed|null regex of {@link self::$answers}, which matches given answer or null if none matches
     */
    public function get_regex_for_answer(string $answer) {
        $ret = null;

        $answer = str_replace("\r", "", $answer);

        foreach ($this->answers as $correctanswer) {
            $value = qtype_regexmatch_common_try_regex($correctanswer, $correctanswer->regexes[0], $answer);

            if($value > 0.0) {
                $value *= $correctanswer->fraction;
                if($ret == null || ($correctanswer->fraction * $value) > $ret->fraction) {
                    $ret = $value == 1.0 ? $correctanswer : new qtype_regexmatch_common_answer($correctanswer->id, $correctanswer->answer, $correctanswer->fraction * $value, $correctanswer->feedback, $correctanswer->feedbackformat);
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

        if($submittedanswer != null) {
            $regex = $this->get_regex_for_answer($submittedanswer);
            if($regex != null) {
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
     * @param array $response
     * @return array
     */
    public function clear_wrong_from_response(array $response) {
        // We want to keep the previous answer as it is only a single answer field
        return $response;
    }

    /**
     * Checks file access.
     * @param $qa
     * @param $options
     * @param $component
     * @param $filearea
     * @param $args
     * @param $forcedownload
     * @return mixed
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


