# Code parser in IPPcode23 

## Project 1 from [IPP](https://www.fit.vut.cz/study/course/IPP/.en) university course 2022/23

A script of type filter reads the source code in IPPcode23 from standard input, checks the code for lexical and syntactic correctness and prints it to standard XML representation of the program according to the specification.

## Requirements

- PHP installed  

Check by running:
```bash
$ php -v

PHP 7.4.3-4ubuntu2.17 (cli) (built: Jan 10 2023 15:37:44) ( NTS )
Copyright (c) The PHP Group
Zend Engine v3.4.0, Copyright (c) Zend Technologies
with Zend OPcache v7.4.3-4ubuntu2.17, Copyright (c), by Zend Technologies
```
## Usage

To run the script, use:
```bash
$ php parse.php <input
```
Where input is the file containing the source **IPPcode23**.

You can also get usage information by running:
```bash
$ php parse.php [-h|--help]
```

## Examples
Given file: 
```bash
$ cat example.ippcode23
```
```
.IPPcode23
DEFVAR GF@counter
MOVE GF@counter string@ #Inicializace proměnné na prázdný řetězec
#Jednoduchá iterace , dokud nebude splněna zadaná podmínka
LABEL while
JUMPIFEQ end GF@counter string@aaa
WRITE string@Proměnná\032GF@counter\032obsahuje\032
WRITE GF@counter
WRITE string@\010
CONCAT GF@counter GF@counter string@a
JUMP while
LABEL end
```
Then running:
```bash
$ php parse.php <example.ippcode23  
```
Output:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<program language="IPPcode23">
    <instruction order="1" opcode="DEFVAR">
        <arg1 type="var">GF@counter</arg1>
    </instruction>
    <instruction order="2" opcode="MOVE">
        <arg1 type="var">GF@counter</arg1>
        <arg2 type="string"></arg2>
    </instruction>
    <instruction order="3" opcode="LABEL">
        <arg1 type="label">while</arg1>
    </instruction>
    <instruction order="4" opcode="JUMPIFEQ">
        <arg1 type="label">end</arg1>
        <arg2 type="var">GF@counter</arg2>
        <arg3 type="string">aaa</arg3>
    </instruction>
    <instruction order="5" opcode="WRITE">
        <arg1 type="string">Proměnná\032GF@counter\032obsahuje\032</arg1>
    </instruction>
    <instruction order="6" opcode="WRITE">
        <arg1 type="var">GF@counter</arg1>
    </instruction>
    <instruction order="7" opcode="WRITE">
        <arg1 type="string">\010</arg1>
    </instruction>
    <instruction order="8" opcode="CONCAT">
        <arg1 type="var">GF@counter</arg1>
        <arg2 type="var">GF@counter</arg2>
        <arg3 type="string">a</arg3>
    </instruction>
    <instruction order="9" opcode="JUMP">
        <arg1 type="label">while</arg1>
    </instruction>
    <instruction order="10" opcode="LABEL">
        <arg1 type="label">end</arg1>
    </instruction>
</program>
```