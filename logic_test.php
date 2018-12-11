<?php 
/** 	
*		Output the numbers from 1 to 100;
*		• Where the number is divisible by three (3) output the word “foo”;
*		• Where the number is divisible by five (5) output the word “bar”;
*		• Where the number is divisible by three (3) and (5) output the word “foobar”; 
*		Example/ 1, 2, foo, 4, bar, foo, 7, 8, foo, bar, 11, foo, 13, 14, foobar
*/
for ($i = 1; $i <= 100; $i++) {
    
	if($i % 3 == 0 && $i % 5 == 0){
		echo "foobar ";	
	}elseif($i % 3  == 0){
		echo "foo, ";
	}elseif($i % 5  == 0){
		echo "bar, ";
	}else{
		echo "$i, ";
	}
} 

?>