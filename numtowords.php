<?php
// Goes in codeigniter/libraries
 
function convert_number($number) {
		if (($number < 0) || ($number > 999999999)) {
			throw new Exception("Number is out of range");
		}
 
		$Gn = floor($number / 1000000);
		/* Millions (giga) */
		$number -= $Gn * 1000000;
		$kn = floor($number / 1000);
		/* Thousands (kilo) */
		$number -= $kn * 1000;
		$Hn = floor($number / 100);
		/* Hundreds (hecto) */
		$number -= $Hn * 100;
		$Dn = floor($number / 10);
		/* Tens (deca) */
		$n = $number % 10;
		/* Ones */
 
		$res = "";
 
		if ($Gn) {
			$res .= convert_number($Gn) .  " million";
		}
 
		if ($kn) {
			$res .= (empty($res) ? "" : " ") .convert_number($kn) . " thousand";
		}
 
		if ($Hn) {
			$res .= (empty($res) ? "" : " ") .convert_number($Hn) . " hundred";
		}
 
		$ones = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine", "ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eightteen", "nineteen");
		$tens = array("", "", "twenty", "thirty", "fourty", "fifty", "sixty", "seventy", "eigthy", "ninety");
 
		if ($Dn || $n) {
			if (!empty($res)) {
				$res .= " and ";
			}
 
			if ($Dn < 2) {
				$res .= $ones[$Dn * 10 + $n];
			} else {
				$res .= $tens[$Dn];
 
				if ($n) {
					$res .= "-" . $ones[$n];
				}
			}
		}
 
		if (empty($res)) {
			$res = "zero";
		}
 
		return $res;
	}
 


 //$num = convert_number(1567654);
 //echo $num;
?>