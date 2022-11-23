<?
require_once "connectionpdo.php";
error_reporting(E_ALL);
session_start();
ini_set('display_errors', 'On');
$email = 'ogunrindeomotayo11111@gmail.com';
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

//echo $stmt->rowCount();
$branch = explode(";", "LAG;JJC");
$cashFlow = explode(";", "LAG;JJC");
for($t = 0; $t < count($branch); $t++)
          {
              $sql = "INSERT INTO branches (name,admin_id,insert_by,date_created,company_id,email,address) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute([$branch[$t], '1', '1', date('Y-m-d'), '1','','']);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }
          
for($t = 0; $t < count($cashFlow); $t++)
          {
              $sql = "INSERT INTO flows (flowname, approval,level,admin_id,insert_by,date_created,company_id) VALUES (?,?,?,?,?,?,?)";
              $query = $pdo->prepare($sql);
              $query->execute(['Cash',$cashFlow[$t],($t+1), $_SESSION['user']['id'], $_SESSION['user']['id'], date('Y-m-d'), '1']);
              if ($query->rowCount() == 1) { $msg =  true; } else { }
          }          
?>