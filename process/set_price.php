<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function CreateDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $xDate = $DATA['xDate'];

    $Sql = "SELECT CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'-',
          LPAD( (COALESCE(MAX(CONVERT(SUBSTRING(DocNo,12,5),UNSIGNED INTEGER)),0)+1) ,5,0)) AS DocNo,DATE(NOW()) AS DocDate,
          CURRENT_TIME() AS RecNow
          FROM category_price_time
          WHERE DocNo Like CONCAT('CD',lpad('$HptCode', 3, 0),'/',SUBSTRING(YEAR(DATE(NOW())),3,4),LPAD(MONTH(DATE(NOW())),2,0),'%')
          AND HptCode = '$HptCode'
          ORDER BY DocNo DESC LIMIT 1";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DocNo = $Result['DocNo'];
        $return['DocNo'] = $DocNo;
    }

    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price WHERE category_price.HptCode = '$HptCode' AND Price > 0";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }

    if($Cnt == 0){
        $Sql = "SELECT item_category.CategoryCode, DATE(NOW()) as DateNowx
        FROM item_category
        WHERE item_category.IsStatus = 0";
    }else{
        $Sql = "SELECT item_category.CategoryCode,category_price.Price , DATE(NOW()) as DateNowx
        FROM category_price
        INNER JOIN site ON category_price.HptCode = site.HptCode
        INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode
        WHERE item_category.IsStatus = 0 
        AND category_price.HptCode = '$HptCode'";
    }
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $CategoryCode[$count] = $Result['CategoryCode'];
        $Price[$count] = $Result['Price']==null?'0':$Result['Price'];
        $DateNowx      = $Result['DateNowx'];
        $count++;
    }

    for($i=0;$i<$count;$i++){
        $Sql_Insert = "INSERT INTO category_price_time (DocNo,xDate,HptCode,CategoryCode,Price,Cnt) VALUES ('$DocNo','$xDate','$HptCode',".$CategoryCode[$i].",".$Price[$i].",$count)";
        mysqli_query($conn, $Sql_Insert);
    }

    // -------------------------------
    $insert_alert = "INSERT INTO alert_mail_price (DocNo, HptCode, day_30, day_7) VALUES ('$DocNo', '$HptCode', 0, 0) ";
    mysqli_query($conn, $insert_alert);
    // -------------------------------

    $xdate = "SELECT xDate FROM category_price_time WHERE DocNo = '$DocNo'";
    $meQuerydate = mysqli_query($conn, $xdate);
    while ($Resultdate = mysqli_fetch_assoc($meQuerydate)) {
      $xDate = $Resultdate['xDate'];
      if($DateNowx != $xDate){
        $return['checkdis'] = 1;
      }else{
        $return['checkdis'] = 0;

      }
    }

    if($count>0){
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return[0]['RowID'] = "";
        $return[0]['HptName'] = "";
        $return[0]['MainCategoryName'] = "";
        $return[0]['CategoryName'] = "";
        $return[0]['Price'] = "";
        $return['status'] = "success";
        $return['form'] = "CreateDoc";
        $return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowDoc($conn, $DATA)
{
    $count = 0;
    $HptCode = $DATA['HptCode'];
    $lang = $_SESSION['lang'];
    if($HptCode != null){
      $Sql="SELECT category_price_time.DocNo,category_price_time.xDate,site.HptCode,site.HptName , site.HptNameTH
        FROM category_price_time
        INNER JOIN site ON category_price_time.HptCode = site.HptCode
        WHERE site.HptCode = '$HptCode' AND category_price_time.`Status` = 0 
        GROUP BY site.HptCode,category_price_time.xDate,category_price_time.DocNo
        ORDER BY category_price_time.xDate ASC";
    }else{
      $Sql="SELECT category_price_time.DocNo,category_price_time.xDate,site.HptCode,site.HptName ,  site.HptNameTH
        FROM category_price_time
        INNER JOIN site ON category_price_time.HptCode = site.HptCode
        WHERE category_price_time.`Status` = 0 
        GROUP BY site.HptCode,category_price_time.xDate,category_price_time.DocNo
        ORDER BY category_price_time.xDate ASC";
    }
    
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($lang =='en'){
        $hptlang = $Result['HptName'];
        $date2 = explode("-", $Result['xDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.$date2[0];
      }else if ($lang == 'th'){
        $hptlang = $Result['HptNameTH'];
        $date2 = explode("-", $Result['xDate']);
        $newdate = $date2[2].'-'.$date2[1].'-'.($date2[0]+543);
      }
        $return[$count]['DocNo'] = $Result['DocNo'];
        $return[$count]['xDate'] = $newdate;
        $return[$count]['HptName'] = $hptlang;
        $return[$count]['HptCode'] = $Result['HptCode'];
        $count++;
    }
    $return['xCnt'] = $count;
    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowDoc";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "failed";
        $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function ShowItem1($conn, $DATA)
{
  $count = 0;
  $xHptCode = $DATA['HptCode'];
  $CgSubID = $DATA['CgSubID'];
  $lang = $_SESSION['lang'];

  $Sql = "SELECT category_price.RowID,site.HptName,site.HptNameTH,item_category.CategoryName,category_price.Price
  FROM category_price
  INNER JOIN site ON category_price.HptCode = site.HptCode
  INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode ";
  if( $xHptCode != null  && $CgSubID == null ){
    $Sql .= "WHERE site.HptCode = '$xHptCode'";
  }else if($xHptCode != null  && $CgSubID != null ){
    $Sql .= "WHERE site.HptCode = '$xHptCode'  AND category_price.CategoryCode = $CgSubID";
  }else if($xHptCode == null && $CgSubID != null ){
    $Sql .= "WHERE category_price.CategoryCode = $CgSubID";
  }
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    if($lang =='en'){
      $sitePage = $Result['HptName'];
    }else if ($lang == 'th'){
      $sitePage = $Result['HptNameTH'];
    }
    $return[$count]['RowID'] = $Result['RowID'];
    $return[$count]['HptName'] = $sitePage;
	$return[$count]['CategoryName'] = $Result['CategoryName'];
    $return[$count]['Price'] = $Result['Price'];
    $count++;
  }
  $return['Count'] = $count;
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "ShowItem1";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "success";
    $return['form'] = 'ShowItem1';
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function ShowItem2($conn, $DATA)
{
    $count = 0;
    $DocNo = $DATA['DocNo'];
    $lang = $_SESSION['lang'];
    $Keyword = $DATA['Keyword'];
    if($Keyword=="")  $Keyword = "%";

    $Sql = "SELECT
        category_price_time.RowID,
        site.HptName,
        site.HptNameTH,
        item_category.CategoryName,
        category_price_time.Price,
        category_price_time.CategoryCode,
        category_price_time.xDate
        FROM category_price_time
        INNER JOIN item_category ON category_price_time.CategoryCode = item_category.CategoryCode
        INNER JOIN site ON category_price_time.HptCode = site.HptCode 
        WHERE category_price_time.DocNo = '$DocNo' AND item_category.CategoryName LIKE '%$Keyword%'
        ORDER BY item_category.CategoryCode ASC";

    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($lang =='en'){
        $date = explode("-", $Result['xDate']);
        $newdate = $date[2].'-'.$date[1].'-'.$date[0];
        }else if ($lang == 'th'){
        $date = explode("-", $Result['xDate']);
        $newdate = $date[2].'-'.$date[1].'-'.($date[0] +543);
        }
      if($lang =='en'){
        $sitePage = $Result['HptName'];
      }else if ($lang == 'th'){
        $sitePage = $Result['HptNameTH'];
      }
        $return[$count]['RowID'] = $Result['RowID'];
        $return[$count]['HptName'] = $sitePage;
        $return[$count]['CategoryCode'] = $Result['CategoryCode'];
        $return[$count]['CategoryName'] = $Result['CategoryName'];
        $return[$count]['Price'] = $Result['Price']==0?'':$Result['Price'];
        $return[$count]['date'] = $newdate;
        $return[$count]['xDate'] = $Result['xDate'];
        $count++;
    }
    if($count>0){
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "success";
        $return['form'] = "ShowItem2";
        //$return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function getdetail($conn, $DATA)
{
  $count = 0;
  $RowID = $DATA['RowID'];
  //---------------HERE------------------//
  $Sql = "SELECT category_price.RowID,site.HptName,item_category.CategoryName,category_price.Price
    FROM category_price
    INNER JOIN site ON category_price.HptCode = site.HptCode
    INNER JOIN item_category ON category_price.CategoryCode = item_category.CategoryCode
    WHERE category_price.RowID = $RowID";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
      $return['RowID'] = $Result['RowID'];
      $return['HptName'] = $Result['HptName'];
      $return['CategoryName'] = $Result['CategoryName'];
      $return['Price'] = $Result['Price'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getdetail";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "notfound";
    $return['msg'] = "notfound";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function getHotpital($conn, $DATA)
{
  $HptCode1 = $_SESSION['HptCode'];
  $PmID = $_SESSION['PmID'];
  $lang = $DATA["lang"];
  $count = 0;
  if($lang == 'en'){
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }else{
    if($PmID == 5 || $PmID == 7){
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0 AND HptCode = '$HptCode1'";
    }else{
      $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
      FROM site WHERE site.IsStatus = 0";
    }
  }     
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode']  = $Result['HptCode'];
    $return[$count]['HptName']  = $Result['HptName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getHotpital";
  $return[0]['PmID']  = $PmID;
  echo json_encode($return);
  mysqli_close($conn);
  die;
}


function getCategoryMain($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          item_main_category.MainCategoryCode,
          item_main_category.MainCategoryName
          FROM item_main_category
					WHERE IsStatus = 0";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['MainCategoryCode']  = $Result['MainCategoryCode'];
    $return[$count]['MainCategoryName']  = $Result['MainCategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategoryMain";
  echo json_encode($return);
  mysqli_close($conn);
  die;
}

function getCategorySub($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT item_category.CategoryCode,item_category.CategoryName
  FROM item_category
  WHERE item_category.IsStatus = 0 ";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['CategoryCode']  = $Result['CategoryCode'];
    $return[$count]['CategoryName']  = $Result['CategoryName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getCategorySub";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function SavePrice($conn, $DATA)
{
  $RowID = $DATA['RowID'];
  $Price = $DATA['Price'];
  $Userid = $_SESSION['Userid'];

  $Sql = "UPDATE category_price SET Price = $Price , UserID = $Userid , Modify_Date = NOW()  WHERE RowID = $RowID";

  $selectP = "SELECT
                category_price.HptCode,
                category_price.CategoryCode
              FROM
                category_price
              WHERE RowID = $RowID ";
  $meQuery = mysqli_query($conn, $selectP);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
     $_HptCode = $Result['HptCode'];
     $_CategoryCode = $Result['CategoryCode'];


  $insertP = "INSERT INTO category_price_log SET HptCode = '$_HptCode' , CategoryCode = '$_CategoryCode' , Price = $Price , UserID = $Userid , Modify_Date = NOW()  ";
  mysqli_query($conn, $insertP);
  }

  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "SavePrice";
    $return['msg'] = "Edit Success...";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }else{
    $return['status'] = "failed";
    $return['msg'] = "Edit Failed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SavePriceTime($conn, $DATA)
{
    $RowID = $DATA['RowID'];
    $Price = $DATA['Price'];
    $Sel = $DATA['Sel'];
    $DocNo = $DATA['DocNo'];

    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price_time WHERE category_price_time.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }

    $Sql = "UPDATE category_price_time SET Price = $Price WHERE RowID = $RowID";
    $return['sql'] = $Sql;
    if(mysqli_query($conn, $Sql)){
        $return['status'] = "success";
        $return['Cnt'] = $Cnt;
        $return['Sel'] = $Sel;
        $return['form'] = "SavePriceTime";
        $return['msg'] = "Save Success...";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }else{
        $return['status'] = "failed";
        $return['msg'] = "addfailed";
        echo json_encode($return);
        mysqli_close($conn);
        die;
    }
}

function CheckPrice($conn,$HptCode,$CategoryCode)
{
    $Cnt = 0;
    $Sql = "SELECT COUNT(*) AS Cnt FROM category_price WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Cnt = $Result['Cnt'];
    }
    return $Cnt;
}

function UpdatePrice($conn, $DATA)
{
    $DocNo            = $DATA['DocNo'];
    $CategoryCode = explode(',', $DATA['CategoryCode']);
    $Price               = explode(',', $DATA['Price']);
    $RowId             = explode(',', $DATA['RowId']);
    $limit                = sizeof($CategoryCode, 0);
    $limitRow         = sizeof($RowId, 0);
    $count              = 0;
    $Userid = $_SESSION['Userid'];
    
    $Sql = "SELECT category_price_time.HptCode,
                              category_price_time.CategoryCode,
                              category_price_time.Price
            FROM        category_price_time
            WHERE category_price_time.DocNo = '$DocNo'";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $HptCode = $Result['HptCode'];
        // $CategoryCode = $Result['CategoryCode'];
        // $Price = $Result['Price'];

        // if( CheckPrice($conn,$HptCode,$CategoryCode) == 0 ){
        //     $InsertSql = "INSERT INTO category_price (HptCode,CategoryCode,Price) VALUES ('$HptCode',$CategoryCode,$Price)";
        //     mysqli_query($conn, $InsertSql);
        // }else{
        //     $UpdateSql = "UPDATE category_price SET Price = $Price WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode";
        //     mysqli_query($conn, $UpdateSql);
        // }
        // $count++;
    }
    for($i=0; $i < $limit; $i++)
    {
 
        if( CheckPrice($conn,$HptCode,$CategoryCode[$i]) == 0  ){
            $InsertSql = "INSERT INTO category_price (HptCode,CategoryCode,Price) VALUES ('$HptCode',$CategoryCode[$i],$Price[$i])";
            mysqli_query($conn, $InsertSql);

            $insertP = "INSERT INTO category_price_log SET HptCode = '$HptCode' , CategoryCode = '$CategoryCode[$i]' , Price = $Price[$i] , UserID = $Userid , Modify_Date = NOW()  ";
            mysqli_query($conn, $insertP);

        }else{
            $UpdateSql = "UPDATE category_price SET Price = $Price[$i] WHERE HptCode = '$HptCode' AND CategoryCode = $CategoryCode[$i]";
            mysqli_query($conn, $UpdateSql);

            $insertP = "INSERT INTO category_price_log SET HptCode = '$HptCode' , CategoryCode = '$CategoryCode[$i]' , Price = $Price[$i] , UserID = $Userid , Modify_Date = NOW()  ";
            mysqli_query($conn, $insertP);

        }
    }

    for($i=0; $i < $limitRow; $i++)
    {
      $Sql = "UPDATE category_price_time SET Price = $Price[$i] , UserID = $Userid , Modify_Date = NOW() WHERE DocNo ='$DocNo' AND RowID = $RowId[$i]";
      $meQuery = mysqli_query($conn, $Sql);
    }

    $return['xCnt'] = $count;
    $return['xCnt1'] = $limit;

        $return['status'] = "success";
        $return['form'] = "UpdatePrice";
        $return['msg'] = $Sql;
        echo json_encode($return);
        mysqli_close($conn);
        die;
}

function CancelDocNo($conn,$DATA)
{
    $DocNo = $DATA['DocNo'];
    $Sql = "UPDATE category_price_time SET Status = 1 WHERE DocNo = '$DocNo'";
    mysqli_query($conn, $Sql);
    $return['status'] = "success";
    $return['form'] = "CancelDocNo";
    mysqli_close($conn);
    echo json_encode($return);
}

function saveDoc($conn, $DATA)
{
  $DocNo = $DATA['DocNo'];
  $RowId = explode(',', $DATA['RowId']);
  $Price = explode(',', $DATA['Price']);
  $Userid = $_SESSION['Userid'];
  $limit = sizeof($RowId, 0);

  for($i=0; $i < $limit; $i++)
  {
    $Sql = "UPDATE category_price_time SET Price = $Price[$i] , UserID = $Userid , Modify_Date = NOW()  WHERE DocNo ='$DocNo' AND RowID = $RowId[$i]";
    $meQuery = mysqli_query($conn, $Sql);
  }
  // $return['Sql'] = $Sql;
  // $return['docno'] = $DocNo;
  // $return['RowId'] = $RowId;
  // $return['limit'] = $limit;
  // $return['Price'] = $Price;
  // echo json_encode($return);
}

if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);
      if ($DATA['STATUS'] == 'CreateDoc') {
          CreateDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowDoc') {
          ShowDoc($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem1') {
          ShowItem1($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItem2') {
        ShowItem2($conn, $DATA);
      }else if ($DATA['STATUS'] == 'ShowItemPrice') {
        ShowItemPrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'UpdatePrice') {
        UpdatePrice($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getHotpital') {
        getHotpital($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategoryMain') {
        getCategoryMain($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getCategorySub') {
        getCategorySub($conn, $DATA);
      }else if ($DATA['STATUS'] == 'SavePrice') {
        SavePrice($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SavePriceTime') {
          SavePriceTime($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelDocNo') {
          CancelDocNo($conn,$DATA);
      }else if ($DATA['STATUS'] == 'saveDoc') {
        saveDoc($conn,$DATA);
      }


}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
