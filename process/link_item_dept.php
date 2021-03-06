<?php
session_start();
require '../connect/connect.php';
$Userid = $_SESSION['Userid'];
if($Userid==""){
  header("location:../index.html");
}
function getHospital($conn, $DATA)
{
  $count = 0;
  $lang = $DATA["lang"];
  $userid = $DATA['Userid'];
  $HptCode = $DATA['HptCode'];
  if($lang == 'en'){
    $Sql = "SELECT site.HptCode,site.HptName
    FROM site WHERE site.IsStatus = 0";
  }else{
    $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName
    FROM site WHERE site.IsStatus = 0";
  }
  
  //var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['HptCode'] = $Result['HptCode'];
    $return[$count]['HptName'] = $Result['HptName'];
    $count++;
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "getHospital";
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

function getDepartment($conn, $DATA)
{

  $count = 0;
  $boolean = false;
  $Hotp = $DATA["Hotp"];
  $Sql = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsStatus = 0 AND NOT department.IsDefault = 1     
  AND department.IsActive = 1
  ORDER BY department.DepName ASC";


  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode'] = $Result['DepCode']==null?0:$Result['DepCode'];
    $return[$count]['DepName'] = $Result['DepName']==null?0:$Result['DepName'];
    $count++;
    $boolean = true;
  }
  $return['row'] = $count;
  $Sql2 = "SELECT department.DepCode,department.DepName
  FROM department
  WHERE department.HptCode = '$Hotp'
  AND department.IsDefault = 1";
  $meQuery1 = mysqli_query($conn, $Sql2);
  $Result1 = mysqli_fetch_assoc($meQuery1);
  if($Result1['DepCode'] == null){
    $Result1['DepCode'] = "";
  }
  if($Result1['DepName'] == null){
    $Result1['DepName'] = "";
  }
  $return[0]['DepCodeCenter'] = $Result1['DepCode'];
  $return[0]['DepNameCenter'] = $Result1['DepName'];


  if ($meQuery = mysqli_query($conn, $Sql)) {
    $return['status'] = "success";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  } else {
    $return['status'] = "failed";
    $return['form'] = "getDepartment";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  // $count = 0;
  // $userid = $DATA['Userid'];
  // $Sql = "SELECT
  //         department.DepCode,
  //         department.DepName
  //         FROM
  //         department
  //         WHERE department.DepCode = (
  //         SELECT
  //         department.DepCode
  //         FROM
  //         users
  //         INNER JOIN employee ON users.ID = employee.EmpCode
  //         INNER JOIN department ON employee.DepCode = department.DepCode
  //         WHERE users.ID = $userid ) AND department.IsStatus = 0
  //         ORDER BY department.DepCode DESC
  //         ";
  // // var_dump($Sql); die;
  // $meQuery = mysqli_query($conn, $Sql);
  // while ($Result = mysqli_fetch_assoc($meQuery)) {
  //   $return[$count]['DepCode'] = $Result['DepCode'];
  //   $return[$count]['DepName'] = $Result['DepName'];
  //   $count++;
  // }

  // if($count>0){
  //   $return['status'] = "success";
  //   $return['form'] = "getDepartment";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }else{
  //   $return['status'] = "notfound";
  //   $return['msg'] = "notfound";
  //   echo json_encode($return);
  //   mysqli_close($conn);
  //   die;
  // }
}

function ShowItem($conn, $DATA)
{
  $count = 0;
  $xCenter2 = $DATA['xCenter2'];
  $HosCenter = $DATA['HosCenter'];
  $Keyword = str_replace(' ' ,'%' ,$DATA['Keyword']);
  $HptCode = $DATA['HptCode'];
  $userid = $DATA['Userid'];
  if($xCenter2 <> 1){
  $Sql = "SELECT
          item.ItemCode,
          item.ItemName
          FROM
          item
          WHERE   IsActive = 1 AND HptCode = '$HptCode'  AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%') AND item.IsActive = 1 AND NOT IsClean = 1 AND NOT IsDirtyBag  =1 
          ORDER BY item.Modify_Date ASC
          ";
  }else{
  $Sql ="SELECT item.ItemCode , item.ItemName FROM item 
          INNER JOIN par_item_stock ON par_item_stock.ItemCode = item.ItemCode
          INNER JOIN site ON site.HptCode = item.HptCode
          INNER JOIN department ON department.DepCode = par_item_stock.DepCode
          WHERE item.IsActive = 1 AND site.HptCode = '$HptCode' AND department.IsDefault =1 AND (item.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%') AND NOT IsClean = 1 AND NOT IsDirtyBag  =1
          GROUP BY ItemCode ";    
  }
  
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['ItemCode'] = $Result['ItemCode'];
    $return[$count]['ItemName'] = $Result['ItemName'];
    $count++;
  }
  $return['count'] = $count;
  $return['status'] = "success";
  $return['form'] = "ShowItem";
  echo json_encode($return);
  mysqli_close($conn);
  die;
 

}

function getdetail($conn, $DATA)
{
  $count = 0;
  $UnitCode = $DATA['UnitCode'];
  //---------------HERE------------------//
    //---------------HERE------------------//
  //---------------HERE------------------//
  //---------------HERE------------------//

  $Sql = "SELECT
          item_Unit.UnitCode,
          item_Unit.UnitName,
          CASE item_Unit.IsStatus WHEN 0 THEN '0' WHEN 1 THEN '1' END AS IsStatus
          FROM
          item_Unit
          WHERE item_Unit.IsStatus = 0
          AND item_Unit.UnitCode = $UnitCode LIMIT 1
          ";
  // var_dump($Sql); die;
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return['UnitCode'] = $Result['UnitCode'];
    $return['UnitName'] = $Result['UnitName'];
    //$return['IsStatus'] = $Result['IsStatus'];
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

function getSection($conn, $DATA)
{
  $count = 0;
  $Sql = "SELECT
          department.DepCode,
          department.UnitCode,
          department.DepName,
          department.IsStatus
          FROM
          department";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $return[$count]['DepCode']       = $Result['DepCode'];
    $return[$count]['DepName']  = $Result['DepName'];
    $count++;
  }

  $return['status'] = "success";
  $return['form'] = "getSection";
  echo json_encode($return);
  mysqli_close($conn);
  die;

}

function AddItem($conn, $DATA)
{
  $count = 0;
  $Sql = "INSERT INTO item_Unit(
          UnitName,
          IsStatus
         )
          VALUES
          (
            '".$DATA['UnitName']."',
            0
          )
  ";
  // var_dump($Sql); die;
  if(mysqli_query($conn, $Sql)){
    $return['status'] = "success";
    $return['form'] = "AddItem";
    $return['msg'] = "addsuccess";
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

function EditItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            UnitCode = '".$DATA['UnitCode']."',
            UnitName = '".$DATA['UnitName']."'
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "EditItem";
      $return['msg'] = "editsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "editfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "editfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function CancelItem($conn, $DATA)
{
  $count = 0;
  if($DATA["UnitCode"]!=""){
    $Sql = "UPDATE item_Unit SET
            IsStatus = 1
            WHERE UnitCode = ".$DATA['UnitCode']."
    ";
    // var_dump($Sql); die;
    if(mysqli_query($conn, $Sql)){
      $return['status'] = "success";
      $return['form'] = "CancelItem";
      $return['msg'] = "cancelsuccess";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }else{
      $return['status'] = "failed";
      $return['msg'] = "cancelfailed";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
  }else{
    $return['status'] = "failed";
    $return['msg'] = "cancelfailed";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }

}

function additemstock($conn, $DATA)
{
  $boolean = 0;
  $count = 0;
  $hotpital = $DATA['hotpital'];
  $HptCode = $_SESSION['HptCode'];
  $Userid = $_SESSION['Userid'];
  $xCenter2 = $DATA['xCenter2'];
  $Deptid = $DATA['DeptID'];
  $ParQty = $DATA['Par'];
  $Itemcode = explode(",",$DATA['ItemCode']);
  $Number = explode(",",$DATA['Number']);

  $Sql1="SELECT  COUNT(department.DepCode) AS cnt  FROM department WHERE DepCode = '$Deptid' AND IsDefault = 1";
  $query = mysqli_query($conn,$Sql1);
  $Resultquery = mysqli_fetch_assoc($query);
  $cnt = $Resultquery['cnt']==null?0:$Resultquery['cnt'];
  // var_dump($Number[0]); die;
  for ($i=0; $i < sizeof($Itemcode,0) ; $i++) 
  {
      // =====================================================================
    $SqlCount3 = "SELECT COUNT(ItemCode) AS ParCount , ParQty , TotalQty FROM par_item_stock WHERE ItemCode = '$Itemcode[$i]' AND DepCode = '$Deptid'";
    $meQuery3 = mysqli_query($conn,$SqlCount3);
    while ($Result3 = mysqli_fetch_assoc($meQuery3)) 
    {
      $ParCount = $Result3['ParCount'];
      $ParQty3 =  $Result3['ParQty'] + $ParQty;
      $TotalQty8 = $Number[$i] + $Result3['TotalQty'] ;
    }
    if($xCenter2 != 1)
    {
      if($ParCount == 0)
      {
        $Sqlpar = "INSERT INTO par_item_stock (ItemCode , DepCode , ParQty , TotalQty , HptCode) VALUES ('$Itemcode[$i]' , '$Deptid' , $ParQty , $Number[$i] , '$hotpital')";
        mysqli_query($conn,$Sqlpar);
        $boolean++;
      }
      else
      {
        $Sqlpar = "UPDATE par_item_stock SET ItemCode = '$Itemcode[$i]' , ParQty = $ParQty3 ,TotalQty = $TotalQty8
        WHERE DepCode = '$Deptid' AND ItemCode = '$Itemcode[$i]'";
        mysqli_query($conn,$Sqlpar); 
        $boolean++; 
      }
            // ???????????? log par
            $Sqlparlog = "INSERT INTO log_par_item_stock (ItemCode , DepCode , ParQty , TotalQty , UserID , xDate , HptCode ) VALUES ('$Itemcode[$i]' , '$Deptid' , $ParQty , $Number[$i] , $Userid , NOW(), '$hotpital' )";
            mysqli_query($conn,$Sqlparlog);

    }
    else
    {
      if($ParCount == 0)
      {
        $Sqlpar = "INSERT INTO par_item_stock (ItemCode , DepCode , ParQty , TotalQty , HptCode) VALUES ('$Itemcode[$i]' , '$Deptid' , $ParQty , 0 , '$hotpital')";
        mysqli_query($conn,$Sqlpar);
        $boolean++;
      }
      else
      {
        $Sqlpar = "UPDATE par_item_stock SET ItemCode = '$Itemcode[$i]' , ParQty = $ParQty3 ,TotalQty = 0
        WHERE DepCode = '$Deptid' AND ItemCode = '$Itemcode[$i]'";
        mysqli_query($conn,$Sqlpar);
        $boolean++;
      }
                  // ???????????? log par
        $Sqlparlog = "INSERT INTO log_par_item_stock (ItemCode , DepCode , ParQty , TotalQty , UserID , xDate , HptCode) VALUES ('$Itemcode[$i]' , '$Deptid' , $ParQty , 0 , $Userid , NOW() , '$hotpital')";
        mysqli_query($conn,$Sqlparlog);
    }
  }
    // =====================================================================

    # add ????????????????????????
  for ($i=0; $i < sizeof($Itemcode,0) ; $i++) 
  {
    $Sqlzz = "SELECT
            item.UnitCode
            FROM item
            INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
            INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
            INNER JOIN item_unit AS item_unit2 ON item.SizeCode = item_unit2.UnitCode
            LEFT JOIN item_multiple_unit ON item_multiple_unit.ItemCode = item.ItemCode
            LEFT JOIN item_unit AS U1 ON item_multiple_unit.UnitCode = U1.UnitCode
        LEFT JOIN item_unit AS U2 ON item_multiple_unit.MpCode = U2.UnitCode
            WHERE item.ItemCode = '$Itemcode[$i]'";
      $meQueryx = mysqli_query($conn, $Sqlzz);
      $Resultx = mysqli_fetch_assoc($meQueryx);
      $unitCode = $Resultx['UnitCode']==null?0:$Resultx['UnitCode'];
    // ====================================================================================
    $countM = "SELECT COUNT(*) as cnt FROM item_multiple_unit WHERE ItemCode = '$Itemcode[$i]' AND  MpCode = $unitCode ";
    $MQuery = mysqli_query($conn, $countM);
    // echo json_encode($return);
    while ($MResult = mysqli_fetch_assoc($MQuery)) 
    {
      if ($MResult['cnt'] == 0) 
      {
        $Sql2 = "INSERT INTO item_multiple_unit( MpCode, UnitCode, Multiply, ItemCode , PriceUnit ) VALUES
                 ($unitCode, $unitCode, 1, '$Itemcode[$i]' , 1) ";
        mysqli_query($conn, $Sql2);
      }
    }
  }
    // ====================================================================================
  if($boolean>0)
  {
    $return['status'] = "success";
    $return['msg'] = "addsuccess";
    $return['form'] = "additemstock";
    $return['ItemCode'] = $DATA['ItemCode'];
    $return['Number'] = $DATA['Number'];
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
    $return['status'] = "failed";
    $return['msg'] = "addfailmsg";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function SelectItemStock($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $countx = 0;
  $countpar = 0;
  $DepCode = $DATA['DepCode'];
  $HptCode = $DATA['HptCode'];
  $xCenter2 = $DATA['xCenter2']==null?0:$DATA['xCenter2'];
  $ItemCode = explode(",", $DATA['ItemCode']);
  $Number = explode(",",$DATA['Number']);
  $return['num'] = $Number;
  $i = 0;
  if($xCenter2 == 0 )
  {
    for ($j=0; $j < sizeof($ItemCode,0) ; $j++)
    {
      $Find = " SELECT 
                        COUNT(par_item_stock.ItemCode) AS FindItem
                      FROM par_item_stock
                      INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                      WHERE   item.IsActive = 1 
                      AND par_item_stock.ItemCode = '$ItemCode[$j]'  
                      AND par_item_stock.DepCode = '$DepCode' ";
        $FindQuery = mysqli_query($conn, $Find);
        while ($FindResult = mysqli_fetch_assoc($FindQuery))
        {
          if($FindResult['FindItem']>0)
          {
            $count2 = 0;
            $SqlItem = " SELECT 
                                  par_item_stock.ItemCode,
                                  par_item_stock.ParQty,
                                  item.ItemName,
                                  par_item_stock.RowID
                                FROM par_item_stock
                                INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                                WHERE  item.IsActive = 1 
                                AND  par_item_stock.ItemCode = '$ItemCode[$j]'  
                                AND par_item_stock.DepCode = '$DepCode' 
                                GROUP BY par_item_stock.ItemCode
                                ORDER BY par_item_stock.RowID DESC ";
              $ItemQuery = mysqli_query($conn, $SqlItem);
              while ($IResult = mysqli_fetch_assoc($ItemQuery)) 
              {
                $return[$countx]['RowID']     = $IResult['RowID'];
                $return[$countx]['ParQty']    = $IResult['ParQty'];
                $return[$countx]['ItemCodeX'] = $IResult['ItemCode'];
                $return[$countx]['ItemNameX'] = $IResult['ItemName'];
                $xItemCode = $IResult['ItemCode'];
                $index = 'ItemCode_'.$xItemCode.'_'.$countx;

                  $Sql = "SELECT
                                par_item_stock.RowID, 
                                par_item_stock.ItemCode,
                                item.ItemName,
                                par_item_stock.ParQty
                              FROM par_item_stock
                              INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                              WHERE  item.IsActive = 1 
                              AND par_item_stock.ItemCode = '$ItemCode[$j]'  
                              AND par_item_stock.DepCode = '$DepCode' 
                              ORDER BY par_item_stock.RowID DESC ";

                  $meQuery = mysqli_query($conn,$Sql);
                  while ($Result = mysqli_fetch_assoc($meQuery)) 
                  {
                    $return[$index][$count2]['RowID'] = $Result['RowID'];
                    $return[$index][$count2]['ItemCode'] = $Result['ItemCode'];
                    $return[$index][$count2]['ItemName'] = $Result['ItemName'];
                    // $return[$index][$count2]['DepCode'] = $Result['DepCode'];
                    $return[$index][$count2]['ParQty'] = $Result['ParQty'];
                    $count2++;
                  }
                  $countx++;
              }
              $return[$i]['num'] = $count2;
              $boolean = true;
              $i++;
          }
        }
    }
  }
  else
  {
    // for ($i=0; $i < sizeof($ItemCode,0) ; $i++) {
      $count2 = 0;
      $SqlItem = " SELECT 
      par_item_stock.ItemCode,
      item.ItemName ,
      par_item_stock.ParQty,
      par_item_stock.RowID
      FROM par_item_stock
      INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
      WHERE  item.IsActive = 1 
      AND par_item_stock.DepCode = '$DepCode' AND par_item_stock.HptCode =  '$HptCode'   ";
      $ItemQuery = mysqli_query($conn, $SqlItem);
      while ($IResult = mysqli_fetch_assoc($ItemQuery)) 
      {
        $return[$countx]['ItemCodeX'] = $IResult['ItemCode'];
        $return[$countx]['ItemNameX'] = $IResult['ItemName'];
        $return[$countx]['ParQty'] = $IResult['ParQty'];
        $return[$countx]['RowID'] = $IResult['RowID'];
        $countx++;
        $countpar++;
      }
      $boolean = true;
    // }
  }


  $return['countpar'] = $countpar;
  $return['countx'] = $countx;
  if($boolean==true)
  {
    $return['status'] = "success";
    $return['form'] = "SelectItemStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
    $return['status'] = "failed";
    $return['msg'] = "refresh";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}


function ShowItemStock($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $countx = 0;
  $count5 = 0;
  $countpar = 0;
  $DepCode = $DATA['Deptid'];
  $Keyword = $DATA['Keyword'];
  $xCenter2 = $DATA['xCenter2'];
  $HptCode = $DATA['HptCode'];
  if($xCenter2 == 0)
  {
    $Sql="SELECT par_item_stock.ItemCode 
    FROM par_item_stock 
    INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
    WHERE par_item_stock.DepCode = '$DepCode' AND (item.ItemName LIKE '%$Keyword%')  GROUP BY ItemCode";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) 
    {
      $ItemCode[$count5] = $Result['ItemCode'];
      $count5++;
    }
    for ($i=0; $i < $count5 ; $i++) 
    {
      $count2 = 0;
      $SqlItem = " SELECT
                            par_item_stock.ItemCode,
                            par_item_stock.ParQty,
                            item.ItemName,
                            par_item_stock.RowID
                          FROM par_item_stock
                          INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                          WHERE  item.IsActive = 1 AND  par_item_stock.ItemCode = '$ItemCode[$i]'   AND par_item_stock.DepCode = '$DepCode' AND  (par_item_stock.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%')
                          GROUP BY par_item_stock.ItemCode
                          ORDER BY par_item_stock.RowID DESC";
        $ItemQuery = mysqli_query($conn, $SqlItem);
        while ($IResult = mysqli_fetch_assoc($ItemQuery)) 
        {
          $return[$countx]['RowID'] = $IResult['RowID'];
          $return[$countx]['ParQty'] = $IResult['ParQty'];
          $return[$countx]['ItemCodeX'] = $IResult['ItemCode'];
          $return[$countx]['ItemNameX'] = $IResult['ItemName'];
          $xItemCode = $IResult['ItemCode'];
          $index = 'ItemCode_'.$xItemCode.'_'.$countx;
            $Sql = "SELECT
                        par_item_stock.RowID, 
                        par_item_stock.ItemCode,
                        item.ItemName,
                        par_item_stock.ParQty
                      FROM par_item_stock
                      INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                      WHERE  item.IsActive = 1 AND  par_item_stock.ItemCode = '$ItemCode[$i]'   AND par_item_stock.DepCode = '$DepCode' AND  (par_item_stock.ItemCode LIKE '%$Keyword%' OR item.ItemName LIKE '%$Keyword%')
                      ORDER BY par_item_stock.RowID DESC ";
            $meQuery = mysqli_query($conn,$Sql);
            while ($Result = mysqli_fetch_assoc($meQuery)) 
            {
              $return[$index][$count2]['RowID'] = $Result['RowID'];
              $return[$index][$count2]['ItemCode'] = $Result['ItemCode'];
              $return[$index][$count2]['ItemName'] = $Result['ItemName'];
              $return[$index][$count2]['ParQty'] = $Result['ParQty'];
              $count2++;
            }
            $countx++;
        }
      $return[$i]['num'] = $count2;
      $boolean = true;
    }
  }
  else
  {
    $Sql="SELECT par_item_stock.ItemCode 
    FROM par_item_stock 
    INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
    WHERE item.IsActive = 1 AND  par_item_stock.DepCode = '$DepCode' AND (item.ItemName LIKE '%$Keyword%') AND par_item_stock.HptCode = '$HptCode' ";
    $meQuery = mysqli_query($conn, $Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) 
    {
      $ItemCode[$count5] = $Result['ItemCode'];
      $count5++;
    }
    for ($i=0; $i < $count5 ; $i++) 
    {
      $count2 = 0;
      $SqlItem = " SELECT 
                            par_item_stock.ItemCode,
                            item.ItemName ,
                            par_item_stock.ParQty,
                            par_item_stock.RowID
                          FROM par_item_stock
                          INNER JOIN item ON par_item_stock.ItemCode = item.ItemCode
                          WHERE par_item_stock.ItemCode = '$ItemCode[$i]' 
                          AND par_item_stock.DepCode = '$DepCode' ";
        $ItemQuery = mysqli_query($conn, $SqlItem);
        while ($IResult = mysqli_fetch_assoc($ItemQuery)) 
        {
          $return[$countx]['ItemCodeX'] = $IResult['ItemCode'];
          $return[$countx]['ItemNameX'] = $IResult['ItemName'];
          $return[$countx]['ParQty'] = $IResult['ParQty'];
          $return[$countx]['RowID'] = $IResult['RowID'];
          $countpar++;
          $countx++;
        }
      $return[$i]['num'] = $count2;
      $boolean = true;
    }
  }
  $return['countpar'] = $countpar;
  $return['countx'] = $count5;
  if($boolean==true)
  {
    $return['status'] = "success";
    $return['form'] = "ShowItemStock";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
  else
  {
    $return['status'] = "failed";
    $return['msg'] = "refresh";
    echo json_encode($return);
    mysqli_close($conn);
    die;
  }
}

function setdateitemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Exp = $DATA['Exp'];
  $Exp = explode("/",$Exp);
  $Exp = $Exp[2]."-".$Exp[1]."-".$Exp[0];
  $RowID = $DATA['RowID'];
  $Sql = "UPDATE item_stock SET ExpireDate = DATE('$Exp') WHERE RowID = $RowID";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "setdateitemstock";
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
function SaveUsageCode($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $UsageCode = $DATA['UsageCode'];
  $RowID = $DATA['RowID'];
  $Sel = $DATA['Sel'];
  $Sql = "UPDATE item_stock SET UsageCode = '$UsageCode' WHERE RowID = $RowID";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['form'] = "SaveUsageCode";
    $return['Sel'] = $Sel;
    $return['RowID'] = $RowID;
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

function Submititemstock($conn, $DATA)
{
  // var_dump($DATA); die;
  $boolean = 0;
  $count = 0;
  $Deptid = $DATA['Deptid'];

  $Sqlsearch = "SELECT
                item_stock.RowID,
                item_stock.ItemCode,
                item.ItemName,
                item_stock.ParQty,
                item_stock.ExpireDate
                FROM
                item_stock
                INNER JOIN item ON item_stock.ItemCode = item.ItemCode
                WHERE item_stock.IsStatus = 0 AND item_stock.DepCode = '$Deptid' AND item_stock.ExpireDate IS NOT NULL
                GROUP BY item_stock.ItemCode
                ORDER BY item_stock.RowID DESC";
  $meQuery2 = mysqli_query($conn,$Sqlsearch);
  while ($row = mysqli_fetch_assoc($meQuery2)) {

    $Sql = "SELECT
            COUNT(*) AS Cnt
            FROM
            item_stock_detail
            WHERE item_stock_detail.DepCode = '$Deptid' AND item_stock_detail.ItemCode = '".$row['ItemCode']."'";
    $meQuery = mysqli_query($conn,$Sql);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      if($Result['Cnt']==0){
        $Sqlinsert = "INSERT INTO item_stock_detail(ItemCode,ExpireDate,DepCode,ParQty,Qty)
          VALUES(
            '".$row['ItemCode']."',
            '".$row['ExpireDate']."',
            '$Deptid',
            ".$row['ParQty'].",
            0
          )";
          if(mysqli_query($conn,$Sqlinsert)){
            $count++;
          }
      }
    }
  }

  $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE DepCode = '$Deptid' AND ExpireDate <> '' AND ExpireDate IS NOT NULL";
  // var_dump($Sql); die;
  if(mysqli_query($conn,$Sql)){
    $count++;
  }
  if($count>0){
    $return['status'] = "success";
    $return['msg'] = "success";
    $return['form'] = "Submititemstock";
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

function DeleteItem($conn, $DATA)
{
  $boolean = false;
  $count = 0;
  $DepCode = $DATA['DepCode'];
  $xCenter = $DATA['xCenter'];
  $ItemCode = explode(',' , $DATA['ItemCode']);
  $limit = sizeof($ItemCode, 0);
  if($xCenter==1){
    for ($i = 0; $i < $limit; $i++) {
      $Sql = "DELETE FROM par_item_stock WHERE ItemCode = '$ItemCode[$i]' AND DepCode = '$DepCode'";
      mysqli_query($conn,$Sql);

      $Sql = "DELETE FROM item_stock WHERE ItemCode = '$ItemCode[$i]' AND DepCode = '$DepCode'";
      mysqli_query($conn,$Sql);
    }
  }else{
    for ($i = 0; $i < $limit; $i++) {
      $Sql = "DELETE FROM par_item_stock WHERE ItemCode = '$ItemCode[$i]' AND DepCode = '$DepCode'";
      mysqli_query($conn,$Sql);
    }
  }
  

  SelectItemStock($conn, $DATA);
}

function SavePar($conn, $DATA){
  $boolean = 0;
  $count = 0;
  $num = $DATA['num'];
  $mypar = $DATA['mypar'];
  $HptCode = $DATA['HptCode'];
  $xCenter = $DATA['xCenter'];
  $RowID = $DATA['RowID'];
  $Userid = $_SESSION['Userid'];

  if($num == 1 )
  {
    $Sql3 = "SELECT ItemCode , DepCode , ParQty FROM par_item_stock WHERE RowID = $RowID";
    $meQuery    = mysqli_query($conn,$Sql3);
    $Result     =  mysqli_fetch_assoc($meQuery);
    $ParQty  	  = $Result['ParQty'] + $mypar;
    $ItemCode  	= $Result['ItemCode'] ;
    $DepCode  	= $Result['DepCode'] ;
    $Sql4 = "UPDATE par_item_stock SET ParQty = '$mypar' WHERE ItemCode = '$ItemCode' AND DepCode = '$DepCode' ";
    mysqli_query($conn,$Sql4);
    
    if(mysqli_query($conn,$Sql4))
    {
      $count++;
    }

    $Sqlparlog = "INSERT INTO log_par_item_stock (ItemCode , DepCode , ParQty  , UserID , xDate , HptCode , TotalQty ) VALUES ( '$ItemCode' , '$DepCode' , '$mypar' , $Userid  , NOW() , '$HptCode' , 0 )";
    mysqli_query($conn,$Sqlparlog);
  }
  else
  {
    $Sql2 = "SELECT ParQty , ItemCode , DepCode  FROM par_item_stock WHERE RowID = $RowID";
    $meQuery = mysqli_query($conn,$Sql2);

    $Result =  mysqli_fetch_assoc($meQuery);
    $ParQty  	    = $Result['ParQty'] + $mypar;
    $ItemCode  	= $Result['ItemCode'] ;
    $DepCode  	= $Result['DepCode'] ;
    $Sql = "UPDATE par_item_stock SET ParQty = '$mypar' WHERE RowID = $RowID";
    // var_dump($Sql); die;
    if(mysqli_query($conn,$Sql))
    {
      $count++;
    }
    $Sqlparlog = "INSERT INTO log_par_item_stock (ItemCode , DepCode , ParQty  , UserID , xDate , HptCode , TotalQty ) VALUES ( '$ItemCode' , '$DepCode' , '$mypar' , $Userid  , NOW() , '$HptCode' , 0 )";
    mysqli_query($conn,$Sqlparlog);
  }

  if($count>0){
    $return['status'] = "success";
    $return['form'] = "SavePar";
    $return['RowID'] = $RowID;
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



if(isset($_POST['DATA']))
{
  $data = $_POST['DATA'];
  $DATA = json_decode(str_replace ('\"','"', $data), true);

      if ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      }else if ($DATA['STATUS'] == 'getSection') {
        getSection($conn, $DATA);
      }else if ($DATA['STATUS'] == 'AddItem') {
        AddItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'EditItem') {
        EditItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'CancelItem') {
        CancelItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getdetail') {
        getdetail($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getDepartment') {
        getDepartment($conn,$DATA);
      }else if ($DATA['STATUS'] == 'getHospital') {
        getHospital($conn,$DATA);
      }else if ($DATA['STATUS'] == 'additemstock') {
        additemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'ShowItemStock') {
        ShowItemStock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'setdateitemstock') {
        setdateitemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'Submititemstock') {
        Submititemstock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SaveUsageCode') {
        SaveUsageCode($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SelectItemStock') {
        SelectItemStock($conn,$DATA);
      }else if ($DATA['STATUS'] == 'DeleteItem') {
        DeleteItem($conn,$DATA);
      }else if ($DATA['STATUS'] == 'SavePar') {
        SavePar($conn,$DATA);
      }

}else{
	$return['status'] = "error";
	$return['msg'] = 'noinput';
	echo json_encode($return);
	mysqli_close($conn);
  die;
}
