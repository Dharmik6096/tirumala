# Code analysis
## Tirumala 
#### Branch main
#### Version 1.0 

**By: default**

*Date: 2026-07-02*

*Analyzed the: 2026-07-02*

## Introduction
This document contains results of the code analysis of Tirumala



## Configuration

- Quality Profiles
    - Names: Sonar way [C#]; Sonar way [CSS]; Sonar way [PHP]; Sonar way [HTML]; Sonar way [XML]; 
    - Files: 01d5216d-1211-4cb8-8169-d3ad9f6b7d6d.json; 0358e64c-e254-4c47-bc4c-88039a6c373f.json; dae99bb7-5bff-4190-8f41-7eff74913049.json; c3c32fd2-5ccf-4b3a-919a-6b9ecb471dee.json; 5a48830e-6ee2-4d05-93da-5c9b66de6f0f.json; 


 - Quality Gate
    - Name: Sonar way
    - File: Sonar way.xml

## Synthesis

### Analysis Status

Reliability | Security | Security Review | Maintainability |
:---:|:---:|:---:|:---:
E | E | E | A |

### Quality gate status

| Quality Gate Status | OK |
|-|-|



### Metrics

Coverage | Duplications | Comment density | Median number of lines of code per file | Adherence to coding standard |
:---:|:---:|:---:|:---:|:---:
0.0 % | 25.2 % | 12.6 % | 44.0 | 97.3 %

### Tests

Total | Success Rate | Skipped | Errors | Failures |
:---:|:---:|:---:|:---:|:---:
0 | 0 % | 0 | 0 | 0

### Detailed technical debt

Reliability|Security|Maintainability|Total
---|---|---|---
7d 6h 45min|1d 1h 15min|233d 7h 18min|242d 7h 18min


### Metrics Range

\ | Cyclomatic Complexity | Cognitive Complexity | Lines of code per file | Coverage | Comment density (%) | Duplication (%)
:---|:---:|:---:|:---:|:---:|:---:|:---:
Min | 0.0 | 0.0 | 0.0 | 0.0 | 0.0 | 0.0
Max | 47083.0 | 52081.0 | 362339.0 | 0.0 | 100.0 | 96.6

### Volume

Language|Number
---|---
CSS|91
PHP|410142
HTML|1
XML|8
Total|410242


## Issues

### Issues count by severity and types

Type / Severity|INFO|MINOR|MAJOR|CRITICAL|BLOCKER
---|---|---|---|---|---
BUG|0|65|79|24|3
VULNERABILITY|0|2|9|1|0
CODE_SMELL|0|7329|1402|1086|0


### Issues List

Name|Description|Type|Severity|Number
---|---|---|---|---
Class of caught exception should be defined||BUG|BLOCKER|3
HTML elements should have unique "id" attribute values||BUG|CRITICAL|24
"<!DOCTYPE>" declarations should appear before "<html>" tags||BUG|MAJOR|4
"<title>" should be present in all pages||BUG|MAJOR|4
"<html>" element should have a language attribute||BUG|MAJOR|4
Tables should have headers||BUG|MAJOR|4
"<th>" tags should have "id" or "scope" attributes||BUG|MAJOR|8
Useless "if(true) {...}" and "if(false){...}" blocks should be removed||BUG|MAJOR|5
Variables should not be self-assigned||BUG|MAJOR|7
All code should be reachable||BUG|MAJOR|14
Return values from functions without side effects should not be ignored||BUG|MAJOR|1
The output of functions that don't return anything should not be used||BUG|MAJOR|5
All branches in a conditional structure should not have exactly the same implementation||BUG|MAJOR|3
Array values should not be replaced unconditionally||BUG|MAJOR|7
Variables should be initialized before use||BUG|MAJOR|13
"<li>" and "<dt>" item tags should be in "<ul>", "<ol>" or "<dl>" container tags||BUG|MINOR|1
Mouse events should have corresponding keyboard events||BUG|MINOR|5
Function and method parameters' initial values should not be ignored||BUG|MINOR|50
Method visibility should be explicitly declared||BUG|MINOR|7
"require_once" and "include_once" should be used instead of "require" and "include"||BUG|MINOR|2
Methods should not be empty||CODE_SMELL|CRITICAL|11
String literals should not be duplicated||CODE_SMELL|CRITICAL|489
Control structures should use curly braces||CODE_SMELL|CRITICAL|101
"switch" statements should have "default" clauses||CODE_SMELL|CRITICAL|9
Cognitive Complexity of functions should not be too high||CODE_SMELL|CRITICAL|423
Conditionals should start on new lines||CODE_SMELL|CRITICAL|2
`str_replace` should be preferred to `preg_replace`||CODE_SMELL|CRITICAL|1
Constants should not be used as conditions||CODE_SMELL|CRITICAL|8
Unnecessary parentheses should not be used for constructs||CODE_SMELL|CRITICAL|42
Sections of code should not be commented out||CODE_SMELL|MAJOR|38
Attributes deprecated in HTML5 should not be used||CODE_SMELL|MAJOR|31
DOM elements with ARIA role should only have supported properties||CODE_SMELL|MAJOR|25
Prefer tag over ARIA role||CODE_SMELL|MAJOR|47
Focusable elements should not have "aria-hidden" attribute||CODE_SMELL|MAJOR|9
Non-interactive DOM elements should not have an interactive handler||CODE_SMELL|MAJOR|5
Images should have a non-redundant alternate description||CODE_SMELL|MAJOR|2
Label elements should have a text label and an associated control||CODE_SMELL|MAJOR|6
Mergeable "if" statements should be combined||CODE_SMELL|MAJOR|137
Functions should not have too many parameters||CODE_SMELL|MAJOR|7
Nested blocks of code should not be left empty||CODE_SMELL|MAJOR|12
Redundant pairs of parentheses should be removed||CODE_SMELL|MAJOR|31
Generic exceptions ErrorException, RuntimeException and Exception should not be thrown||CODE_SMELL|MAJOR|7
Functions should not contain too many return statements||CODE_SMELL|MAJOR|77
Unused "private" methods should be removed||CODE_SMELL|MAJOR|3
Unused function parameters should be removed||CODE_SMELL|MAJOR|348
Sections of code should not be commented out||CODE_SMELL|MAJOR|311
Functions should not have too many lines of code||CODE_SMELL|MAJOR|28
Classes should not have too many methods||CODE_SMELL|MAJOR|9
Unused assignments should be removed||CODE_SMELL|MAJOR|18
Two branches in a conditional structure should not have exactly the same implementation||CODE_SMELL|MAJOR|10
Reflection should not be used to increase accessibility of classes, methods, or fields||CODE_SMELL|MAJOR|2
Ternary operators should not be nested||CODE_SMELL|MAJOR|218
Functions should use "return" consistently||CODE_SMELL|MAJOR|9
Methods should not have identical implementations||CODE_SMELL|MAJOR|12
Image, area and button with image elements should have an "alt" attribute||CODE_SMELL|MINOR|28
Anchors should contain accessible content||CODE_SMELL|MINOR|1
Function names should comply with a naming convention||CODE_SMELL|MINOR|18
Class names should comply with a naming convention||CODE_SMELL|MINOR|4
Tabulation characters should not be used||CODE_SMELL|MINOR|60
Empty statements should be removed||CODE_SMELL|MINOR|4
Boolean literals should not be redundant||CODE_SMELL|MINOR|65
Return of boolean expressions should not be wrapped into an "if-then-else" statement||CODE_SMELL|MINOR|3
Files should end with a newline||CODE_SMELL|MINOR|660
Lines should not end with trailing whitespaces||CODE_SMELL|MINOR|1342
"empty()" should be used to test for emptiness||CODE_SMELL|MINOR|3
Field names should comply with a naming convention||CODE_SMELL|MINOR|984
Local variable and function parameter names should comply with a naming convention||CODE_SMELL|MINOR|96
Overriding methods should do more than simply call the same method in the super class||CODE_SMELL|MINOR|6
A "while" loop should be used instead of a "for" loop||CODE_SMELL|MINOR|3
"switch" statements should have at least 3 "case" clauses||CODE_SMELL|MINOR|2
IP addresses should not be hardcoded||CODE_SMELL|MINOR|2
Unused local variables should be removed||CODE_SMELL|MINOR|201
Local variables should not be declared and then immediately returned or thrown||CODE_SMELL|MINOR|74
More than one property should not be declared per statement||CODE_SMELL|MINOR|181
Closing tag "?>" should be omitted on files containing only PHP||CODE_SMELL|MINOR|64
PHP keywords and constants "true", "false", "null" should be lower case||CODE_SMELL|MINOR|3155
"elseif" keyword should be used in place of "else if" keywords||CODE_SMELL|MINOR|340
Boolean checks should not be inverted||CODE_SMELL|MINOR|2
"&&" and "&#124&#124" should be used||CODE_SMELL|MINOR|11
Jump statements should not be redundant||CODE_SMELL|MINOR|14
Regular expression quantifiers and character classes should be used concisely||CODE_SMELL|MINOR|6
Encryption algorithms should be used with secure mode and padding scheme||VULNERABILITY|CRITICAL|1
Credentials should not be hard-coded||VULNERABILITY|MAJOR|2
File permissions should not be set to world-accessible values||VULNERABILITY|MAJOR|7
Remote artifacts should not be used without integrity checks||VULNERABILITY|MINOR|2


## Security Hotspots

### Security hotspots count by category and priority

Category / Priority|LOW|MEDIUM|HIGH
---|---|---|---
LDAP Injection|0|0|0
Object Injection|0|0|0
Server-Side Request Forgery (SSRF)|0|0|0
XML External Entity (XXE)|0|0|0
Insecure Configuration|0|0|0
XPath Injection|0|0|0
Authentication|0|0|0
Weak Cryptography|0|28|0
Denial of Service (DoS)|0|0|0
Log Injection|0|0|0
Cross-Site Request Forgery (CSRF)|0|0|0
Open Redirect|0|0|0
Permission|0|0|0
SQL Injection|0|0|0
Encryption of Sensitive Data|10|0|0
Traceability|0|0|0
Buffer Overflow|0|0|0
File Manipulation|0|0|0
Code Injection (RCE)|0|18|0
Cross-Site Scripting (XSS)|0|0|0
Command Injection|0|0|0
Path Traversal Injection|0|0|0
HTTP Response Splitting|0|0|0
Others|2|0|0


### Security hotspots

Category|Name|Priority|Severity|Count
---|---|---|---|---
Code Injection (RCE)|Dynamically executing code is security-sensitive|MEDIUM|CRITICAL|18
Weak Cryptography|Using pseudorandom number generators (PRNGs) is security-sensitive|MEDIUM|CRITICAL|28
Encryption of Sensitive Data|Using clear-text protocols is security-sensitive|LOW|CRITICAL|10
Others|Using weak hashing algorithms is security-sensitive|LOW|CRITICAL|2

