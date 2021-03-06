<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$xDate = date('Y-m-d');
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function OnLoadPage($conn,$DATA){
  $HptCode = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $DATA["lang"];
  $count = 0;
  $boolean = false;
  if($lang == 'en'){
    if($PmID !=1 && $PmID!=6){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND site.HptCode = '$HptCode'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0 "; 
    }
  }else{
    if($PmID !=1 && $PmID!=6){
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND site.HptCode = '$HptCode'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0 "; 
    }
  }
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
	$boolean = true;
  }
$boolean = true;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "OnLoadPage";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function getDepartment($conn,$DATA){
  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
		  FROM department
		  WHERE department.HptCode = '$Hotp'
      AND department.IsDefault = 1
		  AND department.IsStatus = 0";
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName'];
    $count++;
	$boolean = true;
  }

  if($boolean){
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}
// $Sqlx = "INSERT INTO log ( log ) VALUES ('$DocNo : ".$xUsageCode[$i]."')";
// mysqli_query($conn,$Sqlx);

function ShowDocument($conn,$DATA){
  $lang = $_SESSION['lang'];
  $boolean = false;
  $count = 0;
  $deptCode = $DATA["deptCode"];
  $sDate = $DATA["sDate"];
  $eDate = $DATA["eDate"];
  $Hotp = $DATA["Hotp"];

//	 $Sql = "INSERT INTO log ( log ) VALUES ('$deptCode :: $sDate : $eDate')";
//     mysqli_query($conn,$Sql);

  $Sql = "SELECT
  site.HptName,
  department.DepName,
  dirty.DocNo AS DocNo1,
  DATE(dirty.DocDate) AS DocDate1,
  dirty.Total AS Total1,
  clean.DocNo AS DocNo2,
  DATE(clean.DocDate) AS DocDate2,
  clean.Total AS Total2,
  ROUND( (((dirty.Total - clean.Total )/dirty.Total)*100), 2)  AS Precent
  FROM clean
  INNER JOIN dirty ON clean.RefDocNo = dirty.DocNo
  INNER JOIN department ON clean.DepCode = department.DepCode
  INNER JOIN site ON department.HptCode = site.HptCode
  WHERE DATE(dirty.DocDate) BETWEEN '$sDate' AND '$eDate'
  AND department.DepCode = $deptCode
  AND site.HptCode = '$Hotp'
  ORDER BY clean.DocNo DESC LIMIT 100";
  // $return['sql'] = $Sql;
  $meQuery = mysqli_query($conn,$Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {

    
if($lang =='en'){
  $date2 = explode("-", $Result['DocDate1']);
  $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];

  $date2 = explode("-", $Result['DocDate2']);
  $newdate2 = $date2[2].'-'.$date2[1].'-'.$date2[0];
}else if ($lang == 'th'){
  $date2 = explode("-", $Result['DocDate1']);
  $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);

  $date2 = explode("-", $Result['DocDate2']);
  $newdate2 = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
}

	$return[$count]['HptName'] 	= $Result['HptName'];
	$return[$count]['DepName'] 	= $Result['DepName'];
    $return[$count]['DocNo1'] 	= $Result['DocNo1'];
    $return[$count]['DocDate1'] = $newdate;
	$return[$count]['Total1'] 	= $Result['Total1'];
	$return[$count]['DocNo2'] 	= $Result['DocNo2'];
    $return[$count]['DocDate2'] = $newdate2;
	$return[$count]['Total2'] 	= $Result['Total2'];
	$return[$count]['Precent'] 	= $Result['Precent'];
	$DepName = $Result['DepName'];
    $boolean = true;
    $count++;
  }
	$return['Count'] 	= $count;
  if($boolean){
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "success";
    $return['form'] = "ShowDocument";
	  // $return['msg'] = "nodetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

//==========================================================
//
//==========================================================
if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if($DATA['STATUS']=='OnLoadPage'){
        OnLoadPage($conn,$DATA);
	  }elseif ($DATA['STATUS']=='getDepartment') {
        getDepartment($conn, $DATA);
	  }elseif($DATA['STATUS']=='ShowDocument'){
        ShowDocument($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
?>
