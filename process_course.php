<?php
  include "connection.php";
  //include "connection.php";
  include "process_email.php";
  session_start();


  if(!isset($_SESSION['user'])) header("Location: login.php");


  if(isset($_POST['submitAnswer']))
  {
    $responses = $_POST['responses'];
    $coursetestid = mysqli_real_escape_string($conn, $_POST['mycoursetestid']);
    $res = json_decode($_POST['responses']);
    $correct = 0;
    $total = count($res);
    foreach ($res as $r) {
       if(isset($r->answer) && isset($r->myanswer) && strtolower($r->answer) == strtolower($r->myanswer))
       {
         $correct = $correct + 1;
       }
    }
    $score = ($correct / $total) * 100;


    $query = "SELECT * FROM test_participant where userId ='".$_SESSION['user']['id']."' AND coursetestid = '".$coursetestid."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
         $attempts = 0;
         while($row = mysqli_fetch_assoc($result)) { $attempts = (int)$row['attempts'] + 1; }
         $sql = "UPDATE test_participant SET testdata = '".$responses."', attempts = '".$attempts."', scores = '".$score."' WHERE userId='".$_SESSION['user']['id']."' AND  coursetestid = '".$coursetestid."'";
        if(mysqli_query($conn, $sql)){
              
            $_SESSION['msg'] = "You Score ".$score." % in the Just Concluded Test";
        } else{
        }

        if($_SERVER['SERVER_NAME'] != 'localhost')
         header("location: /mycourse_details.php");
        else
          header("location: /newhrcore/mycourse_details.php"); 

        exit();
    }

    $sql = "INSERT INTO test_participant (coursetestId,scores,userId, testdata, date_created, companyId, attempts)
      VALUES ('".trim($coursetestid)."','".$score."', '".$_SESSION['user']['id']."', '".$responses."' , '".date('Y-m-d')."','".$_SESSION['user']['companyId']."', '1')";
        if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "You Score ".$score." % in the Just Concluded Test";
        }else {
           echo "Error: " . $sql . "<br>" . mysqli_error($conn);
           $_SESSION['msg'] = "Error while update account, please try again later";
        }

      if($_SERVER['SERVER_NAME'] != 'localhost')
         header("location: /mycourse_details.php");
      else
          header("location: /newhrcore/mycourse_details.php"); 

  }

  if(isset($_GET['courseid']) && isset($_GET['view']))
  {
      $courseid = base64_decode($_GET['courseid']);
      $_SESSION['courseid'] = $courseid;
      if(!isset($_SESSION['courseid'])){
         if($_SERVER['SERVER_NAME'] != 'localhost')
            header("location: /view_mycourses.php");
         else
            header("location: /newhrcore/view_mycourses.php");
      }
      if($_SERVER['SERVER_NAME'] != 'localhost')
         header("location: /mycourse_details.php");
      else
          header("location: /newhrcore/mycourse_details.php"); 
  }

  if(isset($_GET['courseid']) && !isset($_GET['view']))
  {
    $courseid = base64_decode($_GET['courseid']);
    $_SESSION['courseid'] = $courseid;
    if(!isset($_SESSION['courseid'])){
       if($_SERVER['SERVER_NAME'] != 'localhost')
          header("location: /view_courses.php");
       else
          header("location: /newhrcore/view_courses.php");
    }
    if($_SERVER['SERVER_NAME'] != 'localhost')
       header("location: /course_details.php");
    else
        header("location: /newhrcore/course_details.php");
  }

  if(isset($_POST['testsubmit']))
  {
     $testname = mysqli_real_escape_string($conn, $_POST['testname']);
     $testexpirationdate = mysqli_real_escape_string($conn, $_POST['testexpirationdate']);
     $course_test = mysqli_real_escape_string($conn, $_POST['course_test']);
     $courseId = $_SESSION['courseid'];
      $sql = "INSERT INTO coursestest (courseId,questions,testname, testexpirationdate, date_created, companyId, createdBy)
      VALUES ('".trim($courseId)."','".$course_test."', '".trim($testname)."', '".trim($testexpirationdate)."' , '".date('Y-m-d')."','".$_SESSION['user']['companyId']."', '".$_SESSION['user']['id']."')";
        if (mysqli_query($conn, $sql)) {
          $_SESSION['msg'] = "Course Test Created Successfully";
        }else {
           echo "Error: " . $sql . "<br>" . mysqli_error($conn);
           exit();
           $_SESSION['msg'] = "Error while update account, please try again later";
      }

      if($_SERVER['SERVER_NAME'] != 'localhost')
         header("location: /course_details.php");
      else
         header("location: /newhrcore/course_details.php");
  }

  if(isset($_GET['deletetest']))
  {

    $id = base64_decode($_GET['deletetest']);
    $sql = "DELETE FROM coursestest WHERE id='".$id."'";
    if(mysqli_query($conn, $sql)){
        $_SESSION['msg'] = "Test Deleted Successfully";
    } else{
        //echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    header("Location: course_details.php");
  }

  if(isset($_GET['deleteassign']))
  {

    $id = base64_decode($_GET['deleteassign']);
    $sql = "DELETE FROM assigncourse WHERE id='".$id."'";
    if(mysqli_query($conn, $sql)){
        $_SESSION['msg'] = "Course Assignment Deleted";
    } else{
        //echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
    }
    header("Location: assigned_courses.php");
  }

  if(isset($_POST['assign_course_to_department']))
  {
    $department = mysqli_real_escape_string($conn, $_POST['department']);
    $courseId = mysqli_real_escape_string($conn, $_POST['courseId']);

  $query = "SELECT * FROM assigncourse where department ='".$department."'";
  $result = mysqli_query($conn, $query);
  if(mysqli_num_rows($result)> 0){
      $_SESSION['msg'] = "Course Already Assign to  Department";
      header("Location: assign_courses.php");
      exit();
  }
   $sql = "INSERT INTO assigncourse (courseId,department, date_created, companyId, createdBy)
          VALUES ('".trim($courseId)."','".$department."', '".date('Y-m-d')."','".$_SESSION['user']['companyId']."', '".$_SESSION['user']['id']."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "Course Assigned to  department";
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
    header("Location: assign_courses.php");
  }

  if(isset($_POST['assign_course_to_user']))
  {
    $userId = mysqli_real_escape_string($conn, $_POST['userId']);
    $courseId = mysqli_real_escape_string($conn, $_POST['courseId']);

    $query = "SELECT * FROM assigncourse where userId ='".$userId."'";
    $result = mysqli_query($conn, $query);
    if(mysqli_num_rows($result)> 0){
        $_SESSION['msg'] = "Course Already Assign to  Employee";
        header("Location: assign_courses.php");
        exit();
    }
   $sql = "INSERT INTO assigncourse (courseId,userId, date_created, companyId, createdBy)
          VALUES ('".trim($courseId)."','".$userId."', '".date('Y-m-d')."','".$_SESSION['user']['companyId']."', '".$_SESSION['user']['id']."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "Course Assigned to  Employee";
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
    header("Location: assign_courses.php");
  }

  if(isset($_POST['create_category']))
  {
  	$category = mysqli_real_escape_string($conn, $_POST['category']);
  	if($category == '')
  	{
  		$_SESSION['msg'] = "Category is required";
  		header("Location: create_course.php");
  		exit();
  	}
  	$sql = "INSERT INTO coursecategory (category, date_created, companyId)
          VALUES ('".trim($category)."', '".date('Y-m-d')."','".$_SESSION['user']['companyId']."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "New Course Category Created";
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
          header("Location: create_course.php");
  }

   if(isset($_POST['create_course']))
  {
  	$categoryid = mysqli_real_escape_string($conn, $_POST['categoryId']);
  	$title = mysqli_real_escape_string($conn, $_POST['title']);
  	$link = mysqli_real_escape_string($conn, $_POST['link']);
  	if($categoryid == '')
  	{
  		$_SESSION['msg'] = "Category is required";
  		header("Location: create_course.php");
  		exit();
  	}
  	if($title == '')
  	{
  		$_SESSION['msg'] = "title is required";
  		header("Location: create_course.php");
  		exit();
  	}
  	$sql = "INSERT INTO courses (categoryId, title,link, date_created, companyId)
          VALUES ('".trim($categoryid)."', '".trim($title)."', '".trim($link)."', '".date('Y-m-d')."','".$_SESSION['user']['companyId']."')";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "New Course Created";
              $last_id = $conn->insert_id;
              processattach_document($conn, $_FILES['attach_document'],$last_id);
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
          header("Location: create_course.php");
  }

  if(isset($_POST['update_course']))
  {
  	$categoryid = mysqli_real_escape_string($conn, $_POST['categoryId']);
  	$title = mysqli_real_escape_string($conn, $_POST['title']);
  	$link = mysqli_real_escape_string($conn, $_POST['link']);
  	$id = mysqli_real_escape_string($conn, $_POST['id']);
  	if($categoryid == '')
  	{
  		$_SESSION['msg'] = "Category is required";
  		header("Location: view_courses.php");
  		exit();
  	}
  	if($title == '')
  	{
  		$_SESSION['msg'] = "title is required";
  		header("Location: view_courses.php");
  		exit();
  	}
  	$sql = "UPDATE courses SET categoryId = '".$categoryid."', title = '".$title."', link = '".$link."' WHERE id = '".$id."'";
            if (mysqli_query($conn, $sql)) {
              $_SESSION['msg'] = "Course Updated Successfully";
              $last_id = $conn->insert_id;
              processattach_document($conn, $_FILES['attach_document'],$id);
            }else {
               $_SESSION['msg'] = "Error while update account, please try again later";
          }
          header("Location: view_courses.php");
  }

  function processattach_document($conn,$attach_document,$last_id){
  	if(isset($attach_document)){
       if($attach_document['name'] == null) {
              //$_SESSION['msg'] = 'Update null';
       	return false;
       		  //header("Location: create_course");
       }else {

       $error = array(); 
       $file_ext = explode('.',$attach_document['name'])[1];
       $img = explode('.',$attach_document['name'])[0].'_'.strtotime(date('Y-m-d'));
       $file_name = $img.'.'.$file_ext;
       $file_size = $attach_document['size'];
       $file_tmp = $attach_document['tmp_name'];
       $file_type = $attach_document['type'];
       //$file_ext = explode('.',$_FILES['attach_document']['name'])[1];
       $extensions = array('jpeg','jpg','png','doc','docx','pdf','txt','csv','xlsx');
       if(in_array($file_ext,$extensions) === false){
        $errors[] = "extension not allowed, please select a JPEG, JPG, DOC, DOCX,PDF,XLSX,CSV or PNG file.";
       }
       if($file_size > 2009752){
        $errors[] = "File size too large";
       }
       if(empty($errors)==true){
         move_uploaded_file($file_tmp,"document/".$file_name);
         //$last_id = $conn->insert_id;
          $sql = "UPDATE courses SET filename = '".$file_name."' Where id = '".$last_id."'";
          if (mysqli_query($conn, $sql)) {
              //$_SESSION['msg'] = $last_id;
          	//header("Location: create_course");
          	return true;
          }else {$_SESSION['msg'] = 'Error updating data'; return false;}
       }else {
         $msg = $errors[0];
         $_SESSION['msg'] = $msg;
         return false;
         //header("Location: create_course");
       }
       }
    }
  }

?>