<?php
    function santizeText($text){
        return filter_var($text, FILTER_SANITIZE_STRING);
    }

    // function santizeTextSplChar($text){
    //     return filter_var($text, FILTER_SANITIZE_SPECIAL_CHARS);
    // }

    function santizeNumber($num){
        return filter_var($num, FILTER_SANITIZE_NUMBER_INT);
    }
    
    function isValidNumber($num){
        return filter_var($num, FILTER_VALIDATE_INT)? true: false;
    }

    // echo santizeText("<h1    >         1212adaad1223adsa@!@$#@%FSDSV5$%$           </h1>");
    // echo "<br/>";
    // echo santizeTextSplChar("<h1    >     1212adaad1223adsa@!@$#@%FSDSV5$%$        </h1>");
    // echo "<br/>";
    // echo santizeNumber("1212adaad1223adsa@!@$#@%FSDSV5$%$");
    // echo santizeNumber("ass");
    // if (empty(isValidNumber("aasas1212ss")))
        // echo "invalid<br/>";
    // else 
        // echo "Valid<br/>";
    // echo isValidNumber('12asas');
    // echo "<br/>";
?>