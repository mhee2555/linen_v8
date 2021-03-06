<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid=="")
{
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA)
{
  $HptCode  = $_SESSION['HptCode'];
  $PmID     = $_SESSION['PmID'];
  $UserID = $_SESSION['Userid'];
  $count    = 0;
  $boolean  = false;
  $Sql = "SELECT COUNT(*) AS Cnt
  FROM contract_parties_factory
  WHERE IsStatus = 0  
  AND DATEDIFF(DATE(contract_parties_factory.EndDate),DATE(NOW())) < 31";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return['CPF_Cnt'] = $Result['Cnt'];
	  $boolean = true;
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM contract_parties_hospital
  WHERE IsStatus = 0 
  AND DATEDIFF(DATE(contract_parties_hospital.EndDate),DATE(NOW())) < 31";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return['HOS_Cnt'] = $Result['Cnt'];
	  $boolean = true;
  }
  
  if($PmID !=1 || $PmID !=6)
  {
    $Sql = "SELECT COUNT(*) AS Cnt
    FROM shelfcount
    INNER JOIN department ON department.DepCode = shelfcount.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    WHERE site.HptCode = '$HptCode' AND ( shelfcount.IsStatus = 0 OR shelfcount.IsStatus = 1 OR shelfcount.statusDepartment = 1 OR shelfcount.statusDepartment != 0 ) AND NOT ( shelfcount.IsStatus = 3 OR shelfcount.IsStatus = 4  OR shelfcount.IsStatus = 9  )   AND IsMobile = 1 ";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery))
    {
      $return['shelfcount_Cnt'] = $Result['Cnt'];
      $boolean = true;
    }
  }
  else
  {
    $Sql = "SELECT COUNT(*) AS Cnt
    FROM shelfcount
    INNER JOIN department ON department.DepCode = shelfcount.DepCode
    INNER JOIN site ON site.HptCode = department.HptCode
    WHERE ( shelfcount.IsStatus = 0 OR shelfcount.IsStatus = 1 ) AND NOT shelfcount.IsStatus = 3   AND IsMobile = 1  ";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery))
    {
      $return['shelfcount_Cnt'] = $Result['Cnt'];
      $boolean = true;
    }
  }

  $Sql = "SELECT COUNT(*) AS Cnt
  FROM clean
  INNER JOIN department ON department.DepCode = clean.DepCode
  INNER JOIN site ON site.HptCode = department.HptCode
  WHERE site.HptCode = '$HptCode' AND clean.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery))
  {
    $return['clean_Cnt'] = $Result['Cnt'];
	  $boolean = true;
  }
  $Sql = "UPDATE users SET IsActive = 1 WHERE ID = $UserID";
  mysqli_query($conn,$Sql);

  $boolean = true;
  if($boolean)
  {
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SETLANG($conn, $DATA)
{
  $lang = $DATA['lang'];
  $_SESSION['lang'] = $lang;
}

function Active($conn,$DATA)
{
  $Userid = $DATA['Userid'];
  $Sql = "UPDATE users SET users.IsActive = 0 WHERE users.ID = '$Userid'";
  mysqli_query($conn,$Sql);

  $boolean = true;
  if($boolean)
  {
    $return['status'] = "success";
    $return['form'] = "Active";
    $return['Sql'] = $Sql;
    echo json_encode($return);
    mysqli_close($conn);
    session_destroy();
    die;
  }
  else
  {
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function update_logoff($conn,$DATA)
{
  $Userid = $DATA['Userid'];
  $Sql = "UPDATE users SET users.chk_logoff = 1 WHERE users.ID = $Userid";
  mysqli_query($conn,$Sql);
  $_SESSION['chk_logoff']  = 1;
}

function logoff($conn, $DATA)
{
  $Userid = $DATA['Userid'];
  $Sql = "UPDATE users SET users.chk_logoff = 0, users.IsActive = 0 WHERE users.ID = $Userid";
  mysqli_query($conn, $Sql);
  session_destroy();
}

function login_again($conn, $DATA)
{
  $Userid = $DATA['Userid'];
  $Username = $DATA['Username'];
  $Password = md5($DATA['Password']);
  $boolean = false;

  $Sql1 = "SELECT COUNT(ID) AS cnt FROM users WHERE ID = $Userid AND Username = '$Username' AND Password = '$Password' LIMIT 1";
  $meQuery = mysqli_query($conn,$Sql1);
  $return['sq'] = $Sql1;
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $cnt     = $Result['cnt'];
    $boolean = true;
  }
  if($cnt == 1 ){
    $Sql2 = "UPDATE users SET users.chk_logoff = 0 WHERE users.ID = $Userid";
    mysqli_query($conn,$Sql2);
    $_SESSION['chk_logoff']  = 0;
    $return['status'] = "success";
    $return['form'] = "login_again";
    $return['msg'] = "Login Success";
    $return['cnt'] = $cnt;
    echo json_encode($return);
  }else{
    $return['status'] = "success";
    $return['form'] = "login_again";
    $return['msg'] = "Username or Password is Wrong!";
    $return['cnt'] = 0;
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
    

}

function UpdateActive($conn, $DATA)
{
  $UserID = $_SESSION['Userid'];
  $Sql = "UPDATE users SET IsActive = 0, chk_logoff = 0 WHERE ID = $UserID";
  $_SESSION['IsActive'] = 1;
  mysqli_query($conn,$Sql);
}

function checksession($conn, $DATA)
{
  $IsActive = $_SESSION['IsActive'];
  $UserID = $_SESSION['Userid'];
  if($IsActive ==1)
  {
    $Sql = "UPDATE users SET IsActive = 1, chk_logoff = 0 WHERE ID = $UserID";
    mysqli_query($conn,$Sql);
  }
}
 //==========================================================
 //==========================================================
 //==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if($DATA['STATUS']=='OnLoadPage')
      {
        OnLoadPage($conn,$DATA);
      }
      else if($DATA['STATUS']=='SETLANG')
      {
        SETLANG($conn,$DATA);
      }
      else if($DATA['STATUS']=='Active')
      {
        Active($conn,$DATA);
      }
      else if ($DATA['STATUS'] == 'update_logoff')
      {
        update_logoff($conn, $DATA);
      }
      else if ($DATA['STATUS'] == 'logoff')
      {
        logoff($conn, $DATA);
      }
      else if ($DATA['STATUS'] == 'login_again')
      {
        login_again($conn, $DATA);
      }
      else if ($DATA['STATUS'] == 'UpdateActive')
      {
        UpdateActive($conn, $DATA);
      }
      else if ($DATA['STATUS'] == 'checksession')
      {
        checksession($conn, $DATA);
      }

}
else
{
	$return['status'] = "error";
	$return['msg'] = 'ไม่มีข้อมูลนำเข้า';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
