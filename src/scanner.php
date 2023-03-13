<?php
/**
 * @author Alina Vinogradova (xvinog00@stud.fit.vutbr.cz)
 * scanner.php - lexical analysis for IPPcode23 language
 * Project 1 for IPP university course 2022/23
 */


$header = 0;
/**
 * @brief Function to get a specific index of opcode in $instructions array
 * @param $word - given opcode
 * @return $i as index in $instructions array, -1 if opcode doesn't exist
 */
function get_instruction($word){
    global $instructions;
    $i = 0;

    foreach($instructions as $inst){
        if(!strcmp($word, $inst)){
            return $i;
        }
        $i++;
    }

    return -1;
}

function get_token(){
    global $input;
    $result = array();
    
    while(true){
        if(($line = fgets($input)) == false){
            array_push($result, array(tEOF));
            return $result;
        }
        if(preg_match("~^\s*#~", $line) or preg_match("~^\s*$~", $line)) continue;

        $words = explode("#", trim($line, "\n"));
        $words = explode(" ", trim($words[0], " "));

        foreach ($words as $i => $word) {
            $word = rtrim($word);
            $w = str_split($word);
            if(sizeof($w) == 1 && $w[0] == 0) continue;
            // echo $word."\n";
            if(preg_match("~@~", $word)){ // variable or constant (<symb>)
                if(preg_match("~^(GF|LF|TF)@[_a-zA-Z-$&%*!?][a-zA-Z-$&%*!?]*~", $word)){ // found a variable
                    array_push($result, array(tVar, $word));
                } elseif(preg_match("~^(int|string|bool|nil)@~", $word)){ // found a constant
                    array_push($result, array(tConst, $word));
                } else {
                    fwrite(STDERR, "Scanner: lexical error\n");
                    exit(eOther);
                }
            } else { // otherwise it's anything else other than symbol
                if(preg_match("~\.IPPcode23~", $word)){ // header
                    if(sizeof($words) > 1) exit(eHeader);
                    if(preg_match("~\.IPPcode23(?:\s*#.+)?$~", $word)){
                        $header = 1;
                        array_push($result, array(tHeader));
                    } else exit(eHeader);
                } elseif(preg_match("~^(int|string|bool)$~", $word)) { // could be type
                    array_push($result, array(tType, $word));
                } 
                else { // label or instruction
                    if($i == 0) $inst = get_instruction(strtoupper($word));
                    else $inst = -1;
                    if($inst != -1){
                        array_push($result, array(tOpcode, $inst));
                    } else {
                        array_push($result, array(tLabel, $word));
                    }
                }   
            }
        }
        return $result;
    }
}