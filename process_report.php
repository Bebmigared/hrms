<?php

include 'connection.php';
session_start();

   function get_days($start_date,$end_date){
    	$counter = 0;
     $no_included = ['Mon','Tue','Wed','Thu','Fri'];
     //echo date('N',strtotime('2019-03-31'));
     while(strtotime($start_date) <= strtotime($end_date)){
        if(date("N",strtotime($start_date))<=5) {
        	//echo date("N",strtotime($start_date));
            $counter++;
        }
        $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
    
      }
      return $counter;
  }
   function resumptionDate($end_date){
    	$counter = 0;
    	$resumptionday = '';
     //echo date("N",strtotime($end_date));
     $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
     while($counter == 0){
         if(date("N",strtotime($end_date))<=5) {
        	
        	$resumptionday = date('d M Y',strtotime($end_date));
            $counter++;
            //echo $resumptionday;
        }
        if(counter == 0) $end_date = date ("Y-m-d", strtotime("+1 day", strtotime($end_date)));
     }
     return $resumptionday;
  }
  if(isset($_POST['report'])){
     report($conn);
  }
  function report($conn){
      $month = (int)$_POST['month'];
    //echo $_SESSION['user']['leave_processing_permission'];
    if($_SESSION['user']['leave_processing_permission'] == '1'){
        
         $query = "SELECT proposed_leave.staff_id, proposed_leave.year,
proposed_leave.leave_start, proposed_leave.leave_end, users.name, users.fname,  users.company_name FROM proposed_leave INNER JOIN users ON 
proposed_leave.staff_id = users.id WHERE year = '".date('Y')."'ORDER BY staff_id";
    }

        $productResult = mysqli_query($conn, $query);
        
        foreach ($productResult as $row) {
          $reportleaves[] = $row;
         }
    //print_r($reportleaves);
   
        /*if(count($reportleaves) > 0){
            $thismonthreport[0]['name'] = 'Surname';
            $thismonthreport[0]['fname'] = 'Firstname';
            $thismonthreport[0]['mname'] = 'Middlename';
            $thismonthreport[0]['branch'] = 'Branch';
            $thismonthreport[0]['department'] = 'Department';
            $thismonthreport[0]['start_date'] = 'Start Date';
            $thismonthreport[0]['end_date'] = 'End Date';
            $thismonthreport[0]['date_created'] = 'Application Date';
            $thismonthreport[0]['leave_type'] = 'Leave Type';
            $thismonthreport[0]['reliever_name'] = 'Reliever Name';
        }*/
        for($x = 0; $x < count($reportleaves); $x++){
            $thismonth = explode('-',$reportleaves[$x]['date_created'])[1];
            $thismonth = (int)$thismonth;
            if($thismonth == $month){
                $reportleaves[$x]['resumptionDate'] = resumptionDate($reportleaves[$x]['end_date']);
                $reportleaves[$x]['leave_days'] = get_days($reportleaves[$x]['start_date'],$reportleaves[$x]['end_date']);
                $thismonthreport[] = $reportleaves[$x];
            }
        }
        
        
        $filename = "Export_excel_report.xls";
        header("Content-Type: application/vnd.ms-excel");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        $isPrintHeader = false;
        if(count($thismonthreport)> 0){
          foreach ($thismonthreport as $row) {
            if (! $isPrintHeader) {
                echo implode("\t", array_keys($row)) . "\n";
                $isPrintHeader = true;
            }
            echo implode("\t", array_values($row)) . "\n";
           }  
        }else {}
  }
  function directory($conn){
    if($_SESSION['user']['category'] == 'staff') $admin_id = $_SESSION['user']['admin_id'];
    if($_SESSION['user']['category'] == 'admin') $admin_id = $_SESSION['user']['id'];
    //print_r($admin_id);
    $query = "SELECT users.name, users.fname,users.mname,users.email, users.branch, users.department, leaves.start_date,leaves.end_date,leaves.date_created,users.employee_ID,leaves.leave_type,leaves.reliever_name,leaves.processed FROM leaves INNER JOIN users ON users.id = leaves.staff_id  WHERE leaves.processed = 'Treated' AND leaves.year = '".date('Y')."'";
    //$query = "SELECT users.name,users.fname,users.mname,users.employee_ID, users.user_company, users.phone_number, users.department,users.branch, users.role,users.email,users.position, users.gender, users.state_of_origin, users.marital_status FROM users WHERE admin_id = '".$admin_id."'";
    $productResult = mysqli_query($conn, $query);
    $filename = "Export_excel_directory.xls";
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    $isPrintHeader = false;
    if(mysqli_num_rows($productResult)> 0){
      foreach ($productResult as $row) {
        if (! $isPrintHeader) {
            echo implode("\t", array_keys($row)) . "\n";
            $isPrintHeader = true;
        }
        echo implode("\t", array_values($row)) . "\n";
       }  
    }else {}
}
        
?>