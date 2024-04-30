# Moodle Question Type Regex Match

## Moodle Installation

### Windows
- https://download.moodle.org/windows/ 
  - Download and Extract Zip
  - Start Moodle Server using `Start Moodle.exe`
  - Stop Moodle Server using `Stop Moodle.exe`

### Ubuntu
- Step-by-Step Guide: https://docs.moodle.org/404/en/Step-by-step_Installation_Guide_for_Ubuntu

## Installation

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
- Check out the moodle folder as project

### Eclipse
- https://docs.moodle.org/dev/Setting_up_Eclipse
- Check out the `<moodle-installation>` folder as project

## Development Notes
Additional advice can be found here:
- https://github.com/marcusgreen/moodle-qtype_TEMPLATE/wiki
