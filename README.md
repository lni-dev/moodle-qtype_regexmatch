# Moodle Question Type Regex Match
Grades student answers based on a single or multiple regular expressions.

## Current capabilities
- Add "Regex Matcher" question type
- Edit question title, text, and feedback
- Add as many regular expressions (patterns) with different grades as needed
  - Pattern must be compatible with [preg_match](https://www.php.net/manual/en/function.preg-match.php). 
    A cheat sheet for preg_match can be found [here](https://courses.cs.washington.edu/courses/cse190m/12sp/cheat-sheets/php-regex-cheat-sheet.pdf)
    - There is a help button (?) next to the first three Regular Expression, which show most of the syntax.
  - The student's answer must completely match the pattern to receive the selected grade. Technically this means: 
    - "^" and "$" are always added to the beginning and end of the pattern respectively.
    - Flag "m" (MULTILINE) is always set
  - CASELESS ("i") and DOTALL ("s") flag can be selected
    - CASELESS: Pattern ignores case when matching
    - DOTALL: All dots (".") inside the pattern also match newlines.
  - Sanity Check:
    - At least one regular expression with a grade of 100% must exist.
- Hints: If the test allows multiple tries (with possible grade reduction), hints can be set for each try
- Start test, grade and get feedback
- Export question to XML (Import not yet working)

## Moodle Installation
References for installation on Windows and Ubuntu are given below.
### Windows
- https://download.moodle.org/windows/ 
  - Download and Extract Zip
  - Start Moodle Server using `Start Moodle.exe`
  - Stop Moodle Server using `Stop Moodle.exe`

### Ubuntu
- Step-by-Step Guide: https://docs.moodle.org/404/en/Step-by-step_Installation_Guide_for_Ubuntu

## Regex Match Installation

Install the Regex Match plugin using any of the methods below. After the installation the moodle administration website
`Website Administration` must be visited.

### Installation Using Git
To install using git for the latest version (the master branch), type this command in the
`<moodle-installation>/question/type` folder of your Moodle install:
```
git clone https://github.com/lni-dev/moodle-qtype_regexmatch.git regexmatch
```
Note: Repository is currently private.

### Installation From Downloaded zip file
Unzip it into the `<moodle-installation>/question/type` folder, and then rename the new folder to `regexmatch`.

## IDE
The following (example) IDEs can be used to edit the code.
### PHPStorm
- Download: https://www.jetbrains.com/phpstorm/download/
  - Note: Student/Teacher license must only be used for non-commercial educational purposes.
    - (including conducting academic research or providing educational services)
    - See https://www.jetbrains.com/legal/docs/toolbox/license_educational/
    - Get license: https://www.jetbrains.com/shop/eform/students
- Select php executable: `Settings` -> `PHP` -> `CLI Interpreter` -> `...` -> `+` -> `Other Local...` -> `PHP executable`
  - If Moodle is already installed, you can use the PHP of the moodle installation
- Check out the complete `<moodle-installation>` folder as project

### Eclipse
- https://docs.moodle.org/dev/Setting_up_Eclipse
- Check out the complete `<moodle-installation>` folder as project

## Development Links with useful information
Additional advice can be found here:
- https://github.com/marcusgreen/moodle-qtype_TEMPLATE/wiki








