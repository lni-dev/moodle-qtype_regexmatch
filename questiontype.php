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
 * Question type class for the regexmatch question type.
 *
 * @package    qtype_regexmatch
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/questionlib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/question/type/regexmatch/question.php');


/**
 * The regexmatch question type.
 *
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class qtype_regexmatch extends question_type {
    /**
     * Response cannot be analyzed, because the method get_possible_responses cannot be implemented.
     * @return false
     */
    public function can_analyse_responses() {
        return false;
    }

    /**
     * Saves question-type specific options
     *
     * @param object $question This holds the information from the editing form,
     *      it is not a standard question object.
     * @return bool|stdClass $result->error or $result->notice
     */
    public function save_question_options($question) {
        parent::save_question_options($question);
        $this->save_question_answers($question);
        $this->save_hints($question);
    }

    /**
     * No extra question fields used
     * @return null
     */
    public function extra_question_fields() {
        return null;
    }

    /**
     * No extra answer fields used
     * @return null
     */
    public function extra_answer_fields() {
        return null;
    }

    /**
     * Create a qtype_regexmatch_common_answer
     * @param object $answer the DB row from the question_answers table plus extra answer fields.
     * @return qtype_regexmatch_common_answer
     */
    protected function make_answer($answer): qtype_regexmatch_common_answer {
        return new qtype_regexmatch_common_answer(
            $answer->id,
            $answer->answer,
            $answer->fraction,
            $answer->feedback,
            $answer->feedbackformat
        );
    }

    /**
     * Calculate the score a monkey would get on a question by clicking randomly.
     *
     * @param stdClass $questiondata data defining a question, as returned by
     *      question_bank::load_question_data().
     * @return number 0
     */
    public function get_random_guess_score($questiondata) {
        return 0;
    }

    /**
     * Move all the files belonging to this question, answers or hints from one context to another.
     * @param int $questionid the question being moved.
     * @param int $oldcontextid the context it is moving from.
     * @param int $newcontextid the context it is moving to.
     */
    public function move_files($questionid, $oldcontextid, $newcontextid) {
        parent::move_files($questionid, $oldcontextid, $newcontextid);
        $this->move_files_in_answers($questionid, $oldcontextid, $newcontextid);
        $this->move_files_in_hints($questionid, $oldcontextid, $newcontextid);
    }

    /**
     * Delete all the files belonging to this question, answers or hints.
     * @param int $questionid the question being deleted.
     * @param int $contextid the context the question is in.
     */
    protected function delete_files($questionid, $contextid) {
        parent::delete_files($questionid, $contextid);
        $this->delete_files_in_answers($questionid, $contextid);
        $this->delete_files_in_hints($questionid, $contextid);
    }

    /**
     * Initialise the common question_definition fields and answers. Also calculates $question->defaultmark
     * @param question_definition $question the question_definition we are creating.
     * @param object $questiondata the question data loaded from the database.
     */
    protected function initialise_question_instance(question_definition $question, $questiondata) {
        parent::initialise_question_instance($question, $questiondata);
        $this->initialise_question_answers($question, $questiondata);
    }

    /**
     * Import from xml
     * @param mixed $data  import data
     * @param mixed $question unused
     * @param qformat_xml $format import format
     * @param mixed $extra unused
     * @return false|object
     */
    public function import_from_xml($data, $question, qformat_xml $format, $extra = null) {
        global $CFG;
        require_once($CFG->dirroot . '/question/type/regexmatch/question.php');

        if (!isset($data['@']['type']) || $data['@']['type'] != 'question_regexmatch') {
            return false;
        }

        $qo = $format->import_headers($data);
        $qo->qtype = $data['@']['type'];

        // Run through the answers.
        $answers = $data['#']['answer'];
        $acount = 0;

        $qo->answer = [];
        $qo->answerformat = [];
        $qo->fraction = [];
        $qo->feedback = [];
        $qo->feedbackformat = [];

        foreach ($answers as $answer) {
            $ans = $format->import_answer($answer, false, $format->get_format($qo->questiontextformat));
            $qo->answer[$acount] = $ans->answer['text'];
            $qo->fraction[$acount] = $ans->fraction;
            $qo->feedback[$acount] = $ans->feedback;
            ++$acount;
        }

        $format->import_hints($qo, $data);
        return $qo;
    }

    /**
     * Export to xml
     * @param qtype_regexmatch_question $question question to export
     * @param qformat_xml $format format to export to
     * @param mixed $extra unused
     * @return string exported
     */
    public function export_to_xml($question, qformat_xml $format, $extra = null) {
        $expout = parent::export_to_xml($question, $format, $extra);

        if (!$expout) {
            $expout = '';
        }

        $extraanswersfields = $this->extra_answer_fields();
        if (is_array($extraanswersfields)) {
            array_shift($extraanswersfields);
        }

        foreach ($question->options->answers as $answer) {
            $extra = '';
            if (is_array($extraanswersfields)) {
                foreach ($extraanswersfields as $field) {
                    if (!isset($answer->$field) || $answer->$field == 0) {
                        continue;
                    }

                    $exportedvalue = $format->xml_escape($answer->$field);
                    $extra .= "      <{$field}>{$exportedvalue}</{$field}>\n";
                }
            }

            $expout .= $format->write_answer($answer, $extra);
        }

        $expout .= $format->write_hints($question);

        return $expout;
    }
}
