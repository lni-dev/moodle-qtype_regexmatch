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
Deswegen muss [dieser](https://www.php.net/manual/en/reference.pcre.pattern.syntax.php) Syntax eingehalten werden:

|        |                Strukturen                |
|:------:|:----------------------------------------:|
|  abc   |               Findet "abc"               |
| [abc]  |    Findet ein Zeichen aus der Klammer    |
| [^abc] | Findet ein Zeichen nicht aus der Klammer |
| ab\|cd |          Findet "ab" oder "cd"           |
| (abc)  |       Findet das Untermuster "abc"       |
|   \    |   Escape Zeichen für .^$*+-?()[]{}\\\|   |

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
| \s |  Leerzeichen (space, tab, leerzeile)  |
| \S |  Irgendein Zeichen außer Leerzeichen  |
| \d |             Ziffern (0-9)             |
| \D |    Irgendein Zeichen außer Ziffern    |
| .  | Irgendein Zeichen außer Zeilenumbruch |
| \b |              Wortgrenze               |
| \B |           Keine Wortgrenze            |

Die Regex Anker "$" und "^" können nicht verwendet werden. Falls diese als Literal gesucht werden
sollen, können sie escaped werden: "\$", "\^". 
Falls der Schräger (`/`) als letztes Zeichen in dem Regulären Ausdruck verwendet wird, müssen die Optionen angebeben werden.
Auch wenn keine Optionen verändert werden (In diesem fall `//` hinzufügen).
';
$string['options'] = "Options";
$string['default_options'] = "Default Options";
$string['options_help'] = /** @lang Markdown */
    'Einige Optionen können aktiviert werden. Optionen müssen am Ende des Regulären Ausdrucks stehen.
Weiterhin, müssen diese mit einem Schräger (`/`) anfangen und enden. Zum Beispiel: `/PI/`. Jede Option wird durch einen
einzigen Buchstaben aktiviert oder deaktiviert.

**I: Ignoriere Groß-/Kleinschreibung**<br>
Der reguläre Ausdruck wird Groß- und Kleinschreibung ignorieren.

**D: Punkt findet alles**<br>
Alle Punkte (`.`) in dem regulären Ausdruck werden auch Zeilenumbrüche finden.

**P: Semikolons und Pipes**<br>
Shell spezifisch: Alle Semikolons `;` und maskierte Pipes `\|` werden jeweils
durch `([ \t]*[;\n][ \t]*)` und `([ \t]*\|[ \t]*)` ersetzt.
Dadurch finden diese unendlich Leerzeichen vor und nach dem Semikolon oder der Pipe.
Zusätzlich kann das Semikolon auch eine Leerzeile finden. Note: Alle Leerzeichen vor und nach
der Pipe / dem Semikolon im Regulären Ausdruck müssen auch innerhalb der Antwort vorkommen.

**R: Umleitungen**<br>
Shell spezifisch: Alle Umleitungen (`<`, `>`, `<<`, `>>`) werden durch z.B. `([ \t]*<[ \t]*)` ersetzt.
Wenn aktiviert können diese Zeichen nicht mehr in anderen Regex-Funktionen verwendet werden (Z.B.: Lookbehind:
`(?<=...)`). Note: Alle Leerzeichen vor und nach der Umleitung im Regulären Ausdruck müssen auch innerhalb
der Antwort vorkommen.

**O: Beliebige Reihenfolge**<br>
Der reguläre Ausdruck muss aus mehreren regulären Ausdrucken bestehen (Auf jeder Zeile einer).
Die Antworten (Auch auf jeder Zeile eine antwort) müssen von einem der regulären Ausdrücke gefunden werden, die 
Reihenfolge ist allerdings egal. Jeder regulärer Ausdruck kann nur einmal gefunden werden.
Falsche, zu viele oder zu wenige Antworten geben Punktabzug.
';
$string['default_options_help'] = /** @lang Markdown */
    'Die folgenden Optionen sind standardmäßig aktiviert und können durch Angabe des jeweiligen Buchstaben deaktiviert werden.

**S: Unendlich Leerzeichen**<br>
Alle Leerzeichen innerhalb des Ausdrucks werden mit `([ \t]+)` ersetzt. Dadurch finden sie 1 oder mehr Whitespace Charakter.

**T: Leerzeichen Trimmen**<br>
Leerzeilen zu Beginn und am Ende der Antwort, sowie Leerzeichen zu Beginn und am Ende jeder Zeile 
der Antwort, werden ignoriert. Leerzeilen am Ende der Antwort werden immer ignoriert, egal ob diese
Option aktiviert ist oder nicht.';

$string['pleaseenterananswer'] = 'Bitte geben Sie eine Antwort ein.';
$string['notenoughregexes'] = 'Mindestens ein regulärer Ausdruck sollte angegeben werden';
$string['fborgradewithoutregex'] = 'Wenn ein Feedback oder eine Bewertung angegeben ist, muss auch ein regulärer Ausdruck angegeben werden';
$string['regex-number'] = 'Regulärer Ausdruck {$a}';
$string['pluginname_help'] = 'Erstelle einen Fragetyp "RegEx Vergleicher", wobei die Antwort mithilfe eines regulären Ausdrucks überprüft wird.';
$string['pluginname_link'] = 'question/type/regexmatch';
$string['pluginnameadding'] = '"RegEx Vergleicher" Frage hinzufügen';
$string['pluginnameediting'] = '"RegEx Vergleicher" Frage editieren';
$string['pluginnamesummary'] = '"RegEx Vergleicher" Fragetyp: Kann die Antwort von Studierenden mithilfe eines regulären Ausdrucks überprüfen';
$string['dollarroofmustbeescaped'] = 'Die Regex Anker "$" und "^" können nicht verwendet werden. Falls diese als Literal gesucht werden sollen, können sie escaped werden: "\\$", "\\^"';