<?php
include 'connection.php';
include 'connectionpdo.php';
include 'numtowords.php';

session_start();

if(isset($_POST['approve_all'])){
  $query = "UPDATE voucher SET md = '1', status = 'MD Approved' WHERE ed = 1";
  $result=mysqli_query($conn, $query);
    //exit();
    echo "<script type='text/javascript'>alert('Approved All Successfully');
  window.location='view_voucher.php';
  </script>";
}

if(isset($_POST['endorse_all'])){
  $query = "UPDATE voucher SET ed = '1', status = 'MD Approved' WHERE md = 0";
  $result=mysqli_query($conn, $query);
    //exit();
    echo "<script type='text/javascript'>alert('Endorsed All Successfully');
  window.location='view_voucher.php';
  </script>";
}


if(isset($_POST['edit'])){

$voucher_no = mysqli_real_escape_string($conn, $_POST['voucher_no']);
$username = $_SESSION['user']['id'];
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$paying_bank = mysqli_real_escape_string($conn, $_POST['paying_bank']);
$cheque_no = mysqli_real_escape_string($conn, $_POST['cheque_no']);
$currency = mysqli_real_escape_string($conn, $_POST['currency']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);
$number = $amount;
$locale = 'en_US';
$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
$amount_words = numfmt_format($fmt, $number);

$query = "UPDATE voucher SET client_name = '".$client_name."', paying_bank = '".$paying_bank."', 
cheque_no ='".$cheque_no."', currency = '".$currency."', description = '".$description."',
 amount = '".$amount."', amount_words = '".$amount_words."'
WHERE voucher_no = '".$voucher_no."'";
$result1=mysqli_query($conn, $query );

}


if(isset($_POST['submit'])){



//$voucher_no = date('yhis');
$voucher_no = mysqli_real_escape_string($conn, $_POST['voucher_no']);
$username = $_SESSION['user']['id'];
$client_name = mysqli_real_escape_string($conn, $_POST['client_name']);
$paying_bank = mysqli_real_escape_string($conn, $_POST['paying_bank']);
$cheque_no = mysqli_real_escape_string($conn, $_POST['cheque_no']);
$currency = mysqli_real_escape_string($conn, $_POST['currency']);
$description = mysqli_real_escape_string($conn, $_POST['description']);
$amount = mysqli_real_escape_string($conn, $_POST['amount']);



function numtowords($num){ 
$decones = array( 
            '01' => "One", 
            '02' => "Two", 
            '03' => "Three", 
            '04' => "Four", 
            '05' => "Five", 
            '06' => "Six", 
            '07' => "Seven", 
            '08' => "Eight", 
            '09' => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            );
$ones = array( 
            0 => " ",
            1 => "One",     
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            ); 
$tens = array( 
            0 => "",
            2 => "Twenty", 
            3 => "Thirty", 
            4 => "Forty", 
            5 => "Fifty", 
            6 => "Sixty", 
            7 => "Seventy", 
            8 => "Eighty", 
            9 => "Ninety" 
            ); 
$hundreds = array( 
            "Hundred,", 
            "Thousand,", 
            "Million,", 
            "Billion,", 
            "Trillion,", 
            "Quadrillion," 
            ); //limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
    if($i < 20){ 
        $rettxt .= $ones[$i]; 
    }
    elseif($i < 100){ 
        $rettxt .= $tens[substr($i,0,1)]; 
        $rettxt .= " ".$ones[substr($i,1,1)]; 
    }
    else{ 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
        $rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0){ 
        $rettxt .= " ".$hundreds[$key]." "; 
    } 

} 
$rettxt = $rettxt." Naira";

if($decnum > 0){ 
    $rettxt .= " and "; 
    if($decnum < 20){ 
        $rettxt .= $decones[$decnum]; 
    }
    elseif($decnum < 100){ 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
    }
    $rettxt = $rettxt." Kobo"; 
} 
return $rettxt;
    
}

// $num = 500254.89;
 //$test = convertNumber($num);

 //echo $test;
 
 
$amount_words = numtowords($amount);
/*$number = $amount;
$locale = 'en_US';
$fmt = numfmt_create($locale, NumberFormatter::SPELLOUT);
$amount_words = numfmt_format($fmt, $number);
//$amount_words = mysqli_real_escape_string($conn, $_POST['amount_words']);
//$created_at = mysqli_real_escape_string($conn, $_POST['created_at']);
*/


$query = "INSERT INTO voucher (voucher_no, username, client_name, paying_bank, 
cheque_no, currency, description, amount, amount_words, status, created_at) 
VALUES ('".$voucher_no ."','".$username."', '".$client_name."', '".$paying_bank."',
 '".$cheque_no."', '".$currency."', '".$description."','".$amount."', '".$amount_words."', 'Awaiting Endorsement', '".date('d-m-y')."')";
 


if (mysqli_query($conn, $query)){
        
  echo "<script type='text/javascript'>alert('Voucher Raised Successfully, Voucher No:');
  window.location='create_voucher.php';
  </script>";
    
    
  } else {
    echo "Error: " . $query . "<br>" . mysqli_error($conn);
  }
}
?>