# Regexmatch Syntax
This file contains information used for both the regexmatch question type and the (future) regexmatch cloze question type.

## Syntax
Legend:
- `<options>`: Options specified by a single letter
- `<regex>`: The regular expression
- `<separator-char>`: separator 
- `<comment-text>`: comment. Only visible to the editor. Must not contain `/` as last char.
- spaces: All spaces in the syntax are optional

**Syntax for a single regular expression**:
```
[[<regex>]] /<options>/
comment=<comment-text>
```
A regular expression can also contain new lines:
```
[[
r
e
g
e
x
]]
/<options>/
comment=<comment-text>
```
Additionally, the `<options>` must be present even if they are empty (`//`).

**Syntax for multiple regular expression (Option `O` enabled)**:
```
[[<regex-1>]] [[<regex-2>]] [[<regex-3>]] /O<options>/
separator=<separator-char>
comment=<comment-text>
```
The different regular expressions can also be written on different lines:
```
[[<regex-1>]] 
[[<regex-2>]]
[[<regex-3>]]
/O<options>/
separator=<seperator-char>
comment=<comment-text>
```



### Examples
**Example 1**
```
[[this is a test]]/I/
comment=This is some comment
```

This regular expression matches the regex `this is a test` with the ignore case
option enabled. Note that the default options
(infinite space and trim spaces) are also enabled. Example matches are:
- `this is a test`
- `This IS a TEst` due to the ignore case option
- `this is     a test` due to the infinite space option. (There are multiple spaces between `is` and `a`, which is not visible on some browsers)

**Example 2**
```
[[cat]] [[dog]] [[alpaca]] /O/
seperator=,
comment=Some test comment!
```
This regular expression matches the regexes `cat`, `dog` and `alpaca` in
any order, because the option match any order (`O`) is enabled.
Note that the default options
(infinite space and trim spaces) are also enabled. Example matches are:
- `cat,dog,alpaca`
- `dog,cat,alpaca`
- `alpaca,dog,cat`
- `alpaca, dog, cat` due to trim spaces option
Partial matches are:
- `cat,mouse,alpaca`
- `cat,dog`
- `cat,dog,alpaca,mouse`

For a description on how the rating is calculated,
please see [usage-examples.md](usage-examples.md#o-match-any-order)

## Options (`<options>`)
Each option is enabled or disabled by a single letter. A capital letter enables
an option and a lower case letter disables an option. Default options are
enabled by default and can be disabled by specifying the lower case letter.
The following options exist:

| Letter | Name                 | Default |
|:------:|----------------------|:-------:|
|   I    | Ignore Case          |         |
|   D    | Dot All              |         |
|   P    | Pipes and Semicolons |         |
|   R    | Redirects            |         |
|   O    | Match Any Order      |         |
|   s    | Infinite Space       |    x    |
|   t    | Trim Spaces          |    x    |

## Regex (`<regex>`, `<regex-x>`)
Regular expression in the PHP-PCRE syntax.
It is important, that all `[` and `]` must be escaped if used as literal, even
if PCRE would not force it to be escaped. For example the
regular expression `[[]` would be valid in PCRE , but in Regexmatch it must
be `[\[]`.
<br><br>
Examples can be found in [usage-examples.md](usage-examples.md).