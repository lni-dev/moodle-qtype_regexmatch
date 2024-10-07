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
 * Strings for component 'qtype_regexmatch', language 'en'
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'RegEx Matcher';
$string['regex'] = 'Regular Expression';
$string['regex_help'] = /** @lang Markdown */
'The regular expression must be compatible with the *preg_match* php function.
Thus, [this](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php) syntax is allowed:

|        |                    Structures                     |
|:------:|:-------------------------------------------------:|
|  abc   |                   Matches "abc"                   |
| [abc]  | Matches any of the characters inside the brackets |
| [^abc] |   Matches any character NOT inside the brackets   |
| ab\|cd |                Match "ab" or "cd"                 |
| (abc)  |           Matches the subpattern "abc"            |
|   \    |         Escape character for []\|()/ etc.         |

|        |        Quantifiers        |
|:------:|:-------------------------:|
|   a*   |    Zero or more of "a"    |
|   a+   |    One or more of "a"     |
|   a?   |    Zero or one of "a"     |
|  a{n}  |    Exactly n times "a"    |
| a{n,}  |     n or more of "a"      |
| a{,m}  |     m or less of "a"      |
| a{n,m} | Between n and m times "a" |

|    |      Characters & Boundaries      |
|:--:|:---------------------------------:|
| \w |  Any word character (a-z 0-9 _)   |
| \W |      Any non word character       |
| \s | Whitespace (space, tab, new line) |
| \S |   Any non whitespace character    |
| \d |           Digits (0-9)            |
| \D |      Any non digit character      |
| .  |   Any character except newline    |
| \b |           Word boundary           |
| \B |        Not a word boundary        |

The regex anchors "$" and "^" cannot be used. If they should be matched as literals, they can be escaped: "\\$", "\\^"
';
$string['checkbox_ignorecase_name'] = 'Ignore case';
$string['checkbox_ignorecase_description'] = 'The regular expression will ignore case if this is ticked';
$string['checkbox_dotall_name'] = 'Dot all';
$string['checkbox_dotall_description'] = 'All Dots (.) in the regular expression will also match new lines if this is ticked';
$string['checkbox_infspace_name'] = 'Infinite spaces';
$string['checkbox_infspace_description'] = 'All Spaces in the expression will be replaced with "([ \t]+)" if this is ticked. Thereby they match 1 or more whitespace characters.';
$string['checkbox_trimspaces_name'] = 'Trim spaces';
$string['checkbox_trimspaces_description'] = 'All trailing and leading spaces in the students answer will be ignored if this is ticked.';
$string['checkbox_pipesemispace_name'] = 'Infinite spaces around semicolons and pipes';
$string['checkbox_pipesemispace_description'] = 'Shell specific: All semicolons ";" and pipes "|" will be replaced with "([ \t]*[;\n][ \t]*)" and "([ \t]*\|[ \t]*)" respectively if this is ticked. ';
$string['checkbox_pipesemispace_name_help'] ='Thereby infinite spaces are allowed around these and the semicolon will also match a new line. That also means, that the regex function "|" (OR) cannot be used anymore. Note: Any spaces in front and after the pipe inside the regex, must also be contained in the answer.';
$string['checkbox_redictspace_name'] = 'Infinite spaces around redirections';
$string['checkbox_redictspace_description'] = 'Shell specific: All redirections (<,>,<<,>>) will be replaced with "([ \t]*<[ \t]*)".';
$string['checkbox_redictspace_name_help'] = 'If enabled redirections cannot be used in other regex-functions (eg.: lookbehind "(?<=...)"). Note: Any spaces in front and after the redirect inside the regex, must also be contained in the answer.';
$string['pleaseenterananswer'] = 'Please enter a answer.';
$string['notenoughregexes'] = 'At least one regular expression is required';
$string['fborgradewithoutregex'] = 'If a feedback or a grade is set a regular expression must be entered';
$string['regex-number'] = 'Regular Expression {$a}';
$string['pluginname_help'] = 'Creates a question of type "regular expression matcher", which allows the answer to be checked using a regular expression.';
$string['pluginname_link'] = 'question/type/regexmatch';
$string['pluginnameadding'] = 'Adding a regular expression matcher question';
$string['pluginnameediting'] = 'Editing a regular expression matcher question';
$string['pluginnamesummary'] = 'A regular expression matcher question type that allows checking question answers using regular expressions.';
$string['dollarroofmustbeescaped'] = 'The regex anchors "$" and "^" cannot be used. If they should be matched as literals, they can be escaped: "\\$", "\\^"';