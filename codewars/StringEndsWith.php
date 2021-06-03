<?
/*
Task:

Complete the solution so that it returns true if the first argument(string) passed in ends with the 2nd argument (also a string).

Examples:

strEndsWith('abc', 'bc') -- returns true
strEndsWith('abc', 'd') -- returns false
*/

function solution($str, $ending) {
   // TODO: complete
   $result=strncmp(strrev($str), strrev($ending), strlen($ending));
   $result==0 ? $result=true : $result=false;
   return  $result;
 }

?>