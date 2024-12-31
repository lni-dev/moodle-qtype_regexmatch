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
     * @var array<qtype_regexmatch_answer> array containing all the allowed regexes
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

    public function constructRegex(string $regex, qtype_regexmatch_answer $options): string {
        $constructedRegex = $regex;

        if($options->infspace)
            $constructedRegex = str_replace(" ", "(?:[ \t]+)", $constructedRegex);

        if($options->pipesemispace)
            $constructedRegex = str_replace(
                array(";", "\|"),
                array("(?:[ \t]*[;\\n][ \t]*)", "(?:[ \t]*\|[ \t]*)"),
                $constructedRegex
            );

        if($options->redictspace)
            $constructedRegex = str_replace(
                array("<", "<<", ">", ">>"),
                array("(?:[ \t]*<[ \t]*)", "(?:[ \t]*<<[ \t]*)", "(?:[ \t]*>[ \t]*)", "(?:[ \t]*>>[ \t]*)"),
                $constructedRegex
            );

        // preg_match requires a delimiter ( we use "/").
        // replace all actual occurrences of "/" in $regex->answer with an escaped version ("//").
        // Add "^(?:" at the start of the regex and ")$" at the end, to match from start to end.
        // and put the regex in a non-capturing-group, so the function of the regex does not change (eg. "^a|b$" vs "^(?:a|b)$")
        $toEscape = array("/");
        $escapeValue = array("\\/");
        $constructedRegex = "/^(?:" . str_replace($toEscape, $escapeValue, $constructedRegex) . ")$/";

        // Set Flags based on enabled options
        if($options->ignorecase)
            $constructedRegex .= "i";

        if($options->dotall)
            $constructedRegex .= "s";

        return $constructedRegex;
    }

    /**
     * @param string $answer answer submitted from a student
     * @return mixed|null regex of {@link self::$answers}, which matches given answer or null if none matches
     */
    public function get_regex_for_answer(string $answer) {
        $ret = null;

        foreach ($this->answers as $regex) {

            // remove \r from the answer, which should not be matched.
            $processedAnswer = str_replace("\r", "", $answer);

            // Remove any \r
            $constructedRegex = str_replace("\r", "", $regex->regex);

            // Trim answer if enabled.
            if($regex->trimspaces) {
                $parts = explode("\n", trim($processedAnswer));
                $processedAnswer = '';
                $first = true;
                foreach ($parts as $part) {
                    if ($first) $first = false;
                    else $processedAnswer .= "\n";
                    $processedAnswer .= trim($part);
                }
            }


            if($regex->matchAnyOrder) {
                $regexLines = explode("\n", $constructedRegex);
                $answerLines = explode("\n", $processedAnswer);

                $answerLineCount = count($answerLines);
                foreach ($regexLines as $line) {
                    $line = $this->constructRegex($line, $regex);

                    $i = 0;
                    for (; $i < $answerLineCount; $i++) {
                        if($answerLines[$i] === null)
                            continue;
                        if(preg_match($line, $answerLines[$i]) == 1) {
                            break;
                        }
                    }

                    if($i !== $answerLineCount) {
                        $answerLines[$i] = null;
                    }
                }

                $wrongAnswerCount = 0;
                foreach ($answerLines as $answerLine) {
                    if($answerLine !== null) $wrongAnswerCount++;
                }

                $maxPoints = count($regexLines);
                $answerCountDif = $maxPoints - $answerLineCount;
                $points = max(0, $maxPoints - abs($answerCountDif) - ($wrongAnswerCount - max(0, -$answerCountDif)));

                $fraction = $regex->fraction * (floatval($points) / floatval($maxPoints));
                $ret = new qtype_regexmatch_answer($regex->id, $regex->regex, $fraction, $regex->feedback, $regex->feedbackformat);
            } else {
                // Construct regex based on enabled options
                $constructedRegex = $this->constructRegex($constructedRegex, $regex);

                // debugging("constructedRegex: $constructedRegex");
                // debugging("processedAnswer: $processedAnswer");
                if(preg_match($constructedRegex, $processedAnswer) == 1) {
                    if($ret == null || $regex->fraction > $ret->fraction) {
                        $ret = $regex;
                    }
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
    /** @var mixed Whether to use the ignore case modifier (0 = false, 1 = true). */
    public $ignorecase;

    /** @var mixed Whether to use the dot all modifier (0 = false, 1 = true). */
    public $dotall;

    /** @var mixed Whether to replcase all spaces with [ \t]+ (0 = false, 1 = true). */
    public $infspace;

    /** @var mixed trim leading and trailing spaces in the answer (0 = false, 1 = true). */
    public $trimspaces;

    /** @var mixed allow infinite trailing and leading spaces around pipes and semicolons (0 = false, 1 = true). */
    public $pipesemispace;

    /** @var mixed Allows infnite trailing and leading spaces around input/output redirections (0 = false, 1 = true). */
    public $redictspace;

    /**
     * @var boolean matches multiple regexes in any order
     */
    public $matchAnyOrder;

    /**
     * @var string The actual regex without any options.
     */
    public $regex;

    public function __construct($id, $answer, $fraction, $feedback, $feedbackformat) {
        parent::__construct($id, $answer, $fraction, $feedback, $feedbackformat);

        $this->ignorecase = false;
        $this->dotall = false;
        $this->pipesemispace = false;
        $this->redictspace = false;
        $this->matchAnyOrder = false;

        // On by default
        $this->infspace = true;
        $this->trimspaces = true;
        $this->parse($answer);
    }

    private function parse($unparsed) {

        // First look for the options "]] /OPTIONS/"

        $index = -1;
        if(preg_match("%]] */[a-zA-Z]*/%", $unparsed, $matches, PREG_OFFSET_CAPTURE)) {
            $index = intval($matches[0][1]);

            $regularExpressions = substr($unparsed, 0, $index); // regexes without the last "]]". E.g.: [[regex1]] [[regex2
        } else {
            //Invalid syntax. Maybe it is an old regex
        }

        //TODO: old cold below:
        //
        if(substr($unparsed, -1) == '/') {
            // remove the '/' at the end
            $unparsed = substr($unparsed, 0, strlen($unparsed) - 1);

            $startOptionIndex = strrpos($unparsed, '/');

            if($startOptionIndex !== false) {
                $options = substr($unparsed, $startOptionIndex + 1);
                $this->regex = substr($unparsed, 0, $startOptionIndex);

                foreach (str_split($options) as $option) {
                    switch ($option) {
                        case 'I': $this->ignorecase = true; break;
                        case 'D': $this->dotall = true; break;
                        case 'P': $this->pipesemispace = true; break;
                        case 'R': $this->redictspace = true; break;
                        case 'O': $this->matchAnyOrder = true; break;

                        // These are on by default, disable them instead.
                        case 'S': $this->infspace = false; break;
                        case 'T': $this->trimspaces = false; break;
                    }
                }
            } else {
                $this->regex = $unparsed;
            }
        } else {
            $this->regex = $unparsed;
        }

        // remove all trailing empty lines from the regex
        while (substr($this->regex, -1) == "\n" || substr($this->regex, -1) == "\r") {
            $this->regex = substr($this->regex, 0, strlen($this->regex) - 1);
        }
    }


}
