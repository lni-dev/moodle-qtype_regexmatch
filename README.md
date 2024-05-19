# Moodle Question Type Regex Match
Grades student answers based on a single or multiple regular expressions.

## Current capabilities
- Add "Regex Matcher" question type
- Edit question title, text, and feedback
- Add as many regular expressions with different grades as needed
- Start test, grade and get feedback

## Problems
- It is currently possible to create a question of this type, which can never be answered correctly:
  - If no answer is added
  - If only regular expressions with grade "none" ("keine") are added

## Moodle Installation

### Windows
- https://download.moodle.org/windows/ 
  - Download and Extract Zip
  - Start Moodle Server using `Start Moodle.exe`
  - Stop Moodle Server using `Stop Moodle.exe`

### Ubuntu
- Step-by-Step Guide: https://docs.moodle.org/404/en/Step-by-step_Installation_Guide_for_Ubuntu

## Regex Match Installation

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

## Development Notes
Additional advice can be found here:
- https://github.com/marcusgreen/moodle-qtype_TEMPLATE/wiki
