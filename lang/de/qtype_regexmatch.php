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
 * Strings for component 'qtype_regexmatch', language 'de'
 *
 * @package    qtype
 * @subpackage regexmatch
 * @copyright  2024 Linus Andera (linus@linusdev.de)

 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$string['pluginname'] = 'RegEx Vergleicher';
$string['regex'] = 'Regulärer Ausdruck';
$string['regex_help'] = /** @lang Markdown */
'Der reguläre Ausdruck muss mit der PHP-Funktion *preg_match* kompatibel sein.
Deswegen muss der folgende Syntax eingehalten werden:

|        |                Strukturen                |
|:------:|:----------------------------------------:|
|  abc   |               Findet "abc"               |
| [abc]  |    Findet ein Zeichen aus der Klammer    |
| [^abc] | Findet ein Zeichen nicht aus der Klammer |
| ab\|cd |          Findet "ab" oder "cd"           |
| (abc)  |       Findet das Untermuster "abc"       |
|   \    |      Escape Zeichen für []\|()/ usw      |

|          |    Wiederholungen    |
|:--------:|:--------------------:|
|    a*    |  Null oder mehr "a"  |
|    a+    |  Ein oder mehr "a"   |
|    a?    |  Null oder Ein "a"   |
|   a{n}   |     Genau n "a"      |
|  a{n,}   |   n oder mehr "a"    |
|  a{,m}   |  m oder weniger "a"  |
|  a{n,m}  | Zwischen n und m "a" |

|    |           Zeichen & Grenzen           |
|:--:|:-------------------------------------:|
| \w |  Irgendein Wort-Zeichen (a-z 0-9 _)   |
| \W |     Irgendein nicht Wort-Zeichen      |
| \s |       Leerzeichen (space, tab)        |
| \S |  Irgendein Zeichen außer Leerzeichen  |
| \d |             Ziffern (0-9)             |
| \D |    Irgendein Zeichen außer Ziffern    |
| .  | Irgendein Zeichen außer Zeilenumbruch |
| \b |              Wortgrenze               |
| \B |           Keine Wortgrenze            |
';
$string['pleaseenterananswer'] = 'Bitte geben Sie eine Antwort ein.';
$string['notenoughregexes'] = 'Mindestens ein regulärer Ausdruck sollte angegeben werden';
$string['fborgradewithoutregex'] = 'Wenn ein Feedback oder eine Bewertung angegeben ist, muss auch ein regulärer Ausdruck angegeben werden';
$string['regex-number'] = 'Regulärer Ausdruck {$a}';
$string['pluginname_help'] = 'Erstelle einen Fragetyp "RegEx Vergleicher", wobei die Antwort mithilfe eines regulären Ausdrucks überprüft wird.';
$string['pluginname_link'] = 'question/type/regexmatch';
$string['pluginnameadding'] = '"RegEx Vergleicher" Frage hinzufügen';
$string['pluginnameediting'] = '"RegEx Vergleicher" Frage editieren';
$string['pluginnamesummary'] = '"RegEx Vergleicher" Fragetyp: Kann die Antwort von Studierenden mithilfe eines regulären Ausdrucks überprüfen';
