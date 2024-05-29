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
 * regexmatch question definition class.
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

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

    public string $regex = "";
    /**
     * @var array array containing all the allowed regexes
     */
    public $answers = array();
    public $options = array();

    public function start_attempt(
        question_attempt_step $step,
        $variant
    ) {
        // probably not needed
    }

    public function is_complete_response(array $response) {
        return array_key_exists('answer', $response) &&
            ($response['answer'] || $response['answer'] === '0');
    }

    public function get_validation_error(array $response) {
        if ($this->is_gradable_response($response)) {
            return '';
        }
        return get_string('pleaseenterananswer', 'qtype_regexmatch');
    }

    public function is_same_response(array $prevresponse, array $newresponse) {
        return question_utils::arrays_same_at_key(
            $prevresponse, $newresponse, 'answer');
    }

    public function get_expected_data() {
        return array('answer' => PARAM_RAW);
    }

    public function summarise_response(array $response) {
        return $response['answer'] ?? null;
    }

    public function un_summarise_response(string $summary): array {
        if (!empty($summary)) {
            return ['answer' => $summary];
        } else {
            return [];
        }
    }

    /**
     * @param string $answer answer submitted from a student
     * @return mixed|null regex of {@link self::$answers}, which matches given answer or null if none matches
     */
    public function get_regex_for_answer(string $answer) : object|null {
        $ret = null;

        foreach ($this->answers as $regex) {
            // preg_match requires a delimiter ( we use "/").
            // replace all actual occurrences of "/" in $regex->answer with an escaped version ("//").
            // Add "^" at the start of the regex and "$" at the end, to match from start to end.
            // Add Modifier m, to make "^" and "$" ignore new lines.
            // Also remove any \r
            $constructedRegex = str_replace("\r", "", $regex->answer);
            $constructedRegex = "/^" . str_replace("/", "\\/", $constructedRegex) . "$/m";

            if($regex->ignorecase == 1)
                $constructedRegex .= "i";

            if($regex->dotall == 1)
                $constructedRegex .= "s";

            // remove \r from the answer, which should not be matched.
            $processedAnswer = str_replace("\r", "", $answer);

            if(preg_match($constructedRegex, $processedAnswer) == 1) {
                if($ret == null || $regex->fraction > $ret->fraction) {
                    $ret = $regex;
                }
            }
        }

        return $ret;
    }

    public function grade_response(array $response): array {
        $submittedAnswer = $response['answer'] ?? null;
        $fraction = 0;

        if($submittedAnswer != null) {
            $regex = $this->get_regex_for_answer($submittedAnswer);
            if($regex != null) {
                $fraction = $regex->fraction;
            }
        }

        return array($fraction, question_state::graded_state_for_fraction($fraction));
    }

    public function get_correct_response() {
        return null;
    }

    public function clear_wrong_from_response(array $response) {
        // We want to keep the previous answer as it is only a single answer field
        return $response;
    }

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

class qtype_regexmatch_answer extends question_answer {
    /** @var int|null Whether to use the ignore case modifier (0 = false, 1 = true). */
    public int|null $ignorecase;

    /** @var int|null Whether to use the dot all modifier (0 = false, 1 = true). */
    public int|null $dotall;

    public function __construct($id, $answer, $fraction, $feedback, $feedbackformat, $ignorecase, $dotall) {
        parent::__construct($id, $answer, $fraction, $feedback, $feedbackformat);
        $this->ignorecase = $ignorecase;
        $this->dotall = $dotall;
    }

}
