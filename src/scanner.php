<?php

$header = 0;
/*
 * Function to find index of a given word if it's present in instructions array
 * @param $word - given word to find
 * @return int - index of instruction in array, -1 otherwise
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
            // fwrite(STDERR, "Scanner: reached EOF\n");
            array_push($result, array(tEOF));
            return $result;
        }
        if(preg_match("~^\s*#~", $line) or preg_match("~^\s*$~", $line)) continue;

        // $words_n = explode(" ", $line); // Array ( [0] => "WRITE", [1] => "GF@counter")
        // $words_n = array_map('trim', $words_n);

        $words = explode("#", trim($line, "\n"));
        $words = explode(" ", trim($words[0], " "));

        // $w = str_split($words[1]);
        
        
        // foreach ($w as $c) {
        //     print(ord($c)." ");
        // }
        // echo "\n";

        // foreach($words_n as $i => $word){
        //     if(preg_match("~^\s*#~", $word)){
        //         array_splice($words_n, $i);
        //         break;
        //     }
        // }
        
        // echo "\t words:\n";
        // print_r($words);

        // echo "\t words_n:\n";
        // print_r($words_n);

        // print_r($words);

        foreach ($words as $i => $word) {
            // echo $word."\n";
            $word = rtrim($word);
            if(preg_match("~@~", $word)){ // variable or constant (<symb>)
                if(preg_match("~^(LF|TF|GF)@[a-zA-Z_\-$&%*][a-zA-Z0-9_\-$&%*]*$~", $word)){ // found a variable
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
                    $inst = get_instruction($word);
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