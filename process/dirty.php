    <?php
    session_start();
    require '../connect/connect.php';
    date_default_timezone_set("Asia/Bangkok");
    $xDate = date('Y-m-d');
    $Userid = $_SESSION['Userid'];
    if ($Userid == "") {
      header("location:../index.html");
    }
    function OnLoadPage($conn, $DATA)
    {
      $lang = $DATA["lang"];
      $Hotp = $DATA["Hotp"];
      $HptCode = $_SESSION['HptCode'];
      $PmID = $_SESSION['PmID'];
      $count = 0;
      $countx = 0;

      $boolean = false;
      if ($lang == 'en') {
        $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 ";
      } else {
        $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 ";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$countx]['FacCode'] = $Result['FacCode'];
        $return[$countx]['FacName'] = $Result['FacName'];
        $countx++;
      }
      $return['Rowx'] = $countx;
      if ($lang == 'en') {
        $Sql = "SELECT site.HptCode,site.HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$Hotp'";
        if ($PmID == 2 || $PmID == 3 || $PmID == 5  || $PmID == 7) {
          $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$HptCode'";
        } else {
          $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptName AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
        }
      } else {
        $Sql = "SELECT site.HptCode,site.HptNameTH AS HptName FROM site  WHERE site.IsStatus = 0  AND site.HptCode = '$Hotp'";
        if ($PmID == 2 || $PmID == 3 || $PmID == 5  || $PmID == 7) {
          $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptNameTH AS HptName1 FROM site  WHERE site.IsStatus = 0 AND site.HptCode = '$HptCode'";
        } else {
          $Sql1 = "SELECT site.HptCode AS HptCode1,site.HptNameTH AS HptName1 FROM site  WHERE site.IsStatus = 0 ";
        }
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['HptCode'] = $Result['HptCode'];
        $return[$count]['HptName'] = $Result['HptName'];
        $boolean = true;
      }
      $meQuery1 = mysqli_query($conn, $Sql1);
      while ($Result1 = mysqli_fetch_assoc($meQuery1)) {
        $return[$count]['HptCode1'] = $Result1['HptCode1'];
        $return[$count]['HptName1'] = $Result1['HptName1'];
        $return[0]['PmID'] = $PmID;
        $count++;
        $boolean = true;
      }
      $return['Row'] = $count;
      $boolean = true;
      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "OnLoadPage";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "OnLoadPage";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }
    /**
     * @param $conn
     * @param $DATA
     */
    function getDepartment($conn, $DATA)
    {
      $count = 0;
      $boolean = false;
      $Hotp = $DATA["Hotp"];
      $Sql = "SELECT department.DepCode,department.DepName
      FROM department
      WHERE department.HptCode = '$Hotp'
      -- AND department.IsDefault = 1
      AND department.IsStatus = 0";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DepCode'] = $Result['DepCode'];
        $return[$count]['DepName'] = $Result['DepName'];
        $count++;
        $boolean = true;
      }
      $return['Row'] = $count;
      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "getDepartment";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "success";
        $return['form'] = "getDepartment";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function CreateDocument($conn, $DATA)
    {
      $boolean = false;
      $count = 0;
      $hotpCode   = $DATA["hotpCode"];
      $userid     = $DATA["userid"];
      $FacCode    = $DATA["FacCode"];
      $timedirty    = $DATA["timedirty"];
      $lang       = $_SESSION['lang'];

      //	 $Sql = "INSERT INTO log ( log ) VALUES ('userid : $userid')";
      //     mysqli_query($conn,$Sql);

      $Sql = "SELECT CONCAT( 'DT', lpad('$hotpCode', 3, 0), SUBSTRING(YEAR(DATE(NOW())), 3, 4), LPAD(MONTH(DATE(NOW())), 2, 0), '-' ,  LPAD(
          ( COALESCE ( 	MAX( CONVERT ( SUBSTRING(DocNo, 12, 5), UNSIGNED INTEGER ) ), 0 ) + 1 ), 5, 0 ) ) AS DocNo, DATE(NOW()) AS DocDate ,  CURRENT_TIME () AS RecNow  
          FROM dirty   INNER JOIN site ON site.HptCode = dirty.HptCode WHERE DocNo LIKE CONCAT( 'DT', lpad('$hotpCode', 3, 0), 
          SUBSTRING(YEAR(DATE(NOW())), 3, 4), LPAD(MONTH(DATE(NOW())), 2, 0), '%' ) AND site.HptCode = '$hotpCode'  ORDER BY DocNo DESC LIMIT 1";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {

        if ($lang == 'en') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
        } else if ($lang == 'th') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
        }

        $DocNo = $Result['DocNo'];
        $return[0]['DocNo']   = $Result['DocNo'];
        $return[0]['DocDate'] = $newdate;
        $return[0]['RecNow']  = $Result['RecNow'];
        $count = 1;
        $Sql = "INSERT INTO log ( log ) VALUES ('" . $Result['DocDate'] . " : " . $Result['DocNo'] . " :: $hotpCode ')";
        mysqli_query($conn, $Sql);
      }

      if ($count == 1) {
        $Sql = "INSERT INTO dirty (
                    DocNo,
                    DocDate,
                    HptCode,
                    Total,
                    dirty.Modify_Code,
                    dirty.Modify_Date,
                    dirty.FacCode,
                    dirty.Time_ID
                  )
                  VALUES
                    (
                      '$DocNo',
                      DATE(NOW()),
                      '$hotpCode',
                      0,
                      $userid,
                      NOW(),
                      $FacCode,
                      $timedirty
                    )";
        mysqli_query($conn, $Sql);

        // $Sql = "INSERT INTO daily_request
        //     (DocNo,DocDate,HptCode,RefDocNo,Detail,Modify_Code,Modify_Date)
        //     VALUES
        //     ('$DocNo',DATE(NOW()),'$hotpCode','','Dirty',$userid,DATE(NOW()))";
        // mysqli_query($conn, $Sql);

        $Sql = "SELECT users.EngName , users.EngLName , users.ThName , users.ThLName , users.EngPerfix , users.ThPerfix
            FROM users
            WHERE users.ID = $userid";

        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          if ($lang == "en") {
            $return[0]['Record']  = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
          } else if ($lang == "th") {
            $return[0]['Record']  = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
          }
        }

        $boolean = true;
      } else {
        $boolean = false;
      }

      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "CreateDocument";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "CreateDocument";
        $return['msg'] = 'cantcreate';
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }
    function ShowDocument($conn, $DATA)
    {
      $lang                               = $_SESSION['lang'];
      $boolean                        = false;
      $count                            = 0;
      $Hotp                            = $DATA["Hotp"];
      $DocNo                         = $DATA["docno"];
      $xDocNo                       = str_replace(' ', '%', $DATA["xdocno"]);
      $datepicker                   = $DATA["datepicker1"] == '' ? date('Y-m-d') : $DATA["datepicker1"];
      $process                        = $DATA["process"];

      if ($process == 'chkpro1') {
        $onprocess1 = 0;
        $onprocess3 = 0;
        $onprocess4 = 0;
      } else if ($process == 'chkpro2') {
        $onprocess1 = 1;
        $onprocess3 = 3;
        $onprocess4 = 4;
      } else if ($process == 'chkpro3') {
        $onprocess1 = 9;
        $onprocess3 = 9;
        $onprocess4 = 9;
      }

      $Sql = "SELECT site.HptName,
      dirty.DocNo,
      DATE(dirty.DocDate) AS DocDate,
      dirty.Total,
      users.EngName,
      users.EngLName,
      users.ThName,
      users.ThLName,
      users.EngPerfix,
      users.ThPerfix,
      TIME(dirty.Modify_Date) AS xTime,
      dirty.IsStatus,
      factory.FacName
      FROM dirty
      INNER JOIN factory ON dirty.FacCode = factory.FacCode
      INNER JOIN site ON dirty.HptCode = site.HptCode
      INNER JOIN users ON dirty.Modify_Code = users.ID ";

      if ($Hotp != null  && $datepicker == null && $process == 'chkpro') {
        $Sql .= " WHERE site.HptCode = '$Hotp' AND dirty.DocNo LIKE '%$xDocNo%' ";
      } else if ($Hotp == null  && $datepicker != null && $process == 'chkpro') {
        $Sql .= " WHERE DATE(dirty.DocDate) = '$datepicker' AND dirty.DocNo LIKE '%$xDocNo%'";
      } else if ($Hotp != null  && $datepicker != null && $process == 'chkpro') {
        $Sql .= " WHERE site.HptCode = '$Hotp' AND DATE(dirty.DocDate) = '$datepicker' AND dirty.DocNo LIKE '%$xDocNo%'";
      } else if ($Hotp != null  && $datepicker != null && $process == 'chkpro') {
        $Sql .= " WHERE  DATE(dirty.DocDate) = '$datepicker' AND site.HptCode = '$Hotp' AND dirty.DocNo LIKE '%$xDocNo%'";
      } else if ($Hotp == null  && $datepicker == null && $process == 'chkpro') {
        $Sql .= "WHERE dirty.DocNo LIKE '%$xDocNo%'";
      } else if ($Hotp != null &&  $datepicker == null && $process != 'chkpro') {
        $Sql .= " WHERE  site.HptCode LIKE '%$Hotp%' AND  dirty.DocNo LIKE '%$xDocNo%'  AND (  dirty.IsStatus = $onprocess1 OR dirty.IsStatus = $onprocess3 OR dirty.IsStatus = $onprocess4) ";
      } else if ($Hotp == null  && $datepicker != null && $process != 'chkpro') {
        $Sql .= " WHERE DATE(dirty.DocDate) = '$datepicker' AND dirty.DocNo LIKE '%$xDocNo%'   AND (  dirty.IsStatus = $onprocess1 OR dirty.IsStatus = $onprocess3 OR dirty.IsStatus = $onprocess4) ";
      } else if ($Hotp != null  && $datepicker != null && $process != 'chkpro') {
        $Sql .= " WHERE site.HptCode LIKE '%$Hotp%' AND DATE(dirty.DocDate) = '$datepicker' AND dirty.DocNo LIKE '%$xDocNo%'   AND (  dirty.IsStatus = $onprocess1 OR dirty.IsStatus = $onprocess3 OR dirty.IsStatus = $onprocess4)";
      }

      // if($Hotp == null  && $datepicker == null){
      //   $Sql .= "WHERE dirty.DocNo LIKE '%$xDocNo%'";
      // }
      $return['sql'] = $Sql;
      $Sql .= "ORDER BY dirty.DocNo DESC LIMIT 500";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {

        if ($lang == 'en') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
          $return[$count]['Record']  = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
        } else if ($lang == 'th') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
          $return[$count]['Record']  = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
        }
        $return[$count]['FacName']    = $Result['FacName'];
        $return[$count]['HptName']    = $Result['HptName'];
        $return[$count]['DocNo']      = $Result['DocNo'];
        $return[$count]['DocDate']    = $newdate;
        $return[$count]['RecNow']     = $Result['xTime'];
        $return[$count]['Total']      = $Result['Total'];
        $return[$count]['IsStatus']   = $Result['IsStatus'];
        $boolean = true;
        $count++;
      }
      $return['Count'] = $count;

      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "ShowDocument";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "success";
        $return['form'] = "ShowDocument";
        // $return['msg'] = "notfound";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function SelectDocument($conn, $DATA)
    {
      $lang = $_SESSION['lang'];
      $boolean = false;
      $count = 0;
      $DocNo = $DATA["xdocno"];
      $Sql = "SELECT   site.HptCode,
        dirty.DocNo,
        DATE(dirty.DocDate) AS DocDate ,
        dirty.Total,
        users.EngName ,
         users.EngLName ,
          users.ThName , 
          users.ThLName , 
          users.EngPerfix ,
           users.ThPerfix ,
           dirty.FacCode,
           TIME(dirty.Modify_Date) AS xTime,
           dirty.IsStatus,
           dirty.Time_ID
        FROM dirty
        INNER JOIN site ON dirty.HptCode = site.HptCode
        INNER JOIN users ON dirty.Modify_Code = users.ID
        WHERE dirty.DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {


        if ($lang == 'en') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . $date2[0];
          $return[$count]['Record']  = $Result['EngPerfix'] . $Result['EngName'] . '  ' . $Result['EngLName'];
        } else if ($lang == 'th') {
          $date2 = explode("-", $Result['DocDate']);
          $newdate = $date2[2] . '-' . $date2[1] . '-' . ($date2[0] + 543);
          $return[$count]['Record']  = $Result['ThPerfix'] . ' ' . $Result['ThName'] . '  ' . $Result['ThLName'];
        }

        $Hotp   = $Result['HptCode'];
        $return[$count]['HptName']   = $Result['HptCode'];
        $return[$count]['DocNo']   = $Result['DocNo'];
        $return[$count]['DocDate']   = $newdate;
        $return[$count]['RecNow']   = $Result['xTime'];
        $return[$count]['Total']   = $Result['Total'];
        $return[$count]['IsStatus'] = $Result['IsStatus'];
        $return[$count]['FacCode2'] = $Result['FacCode'];
        $return[$count]['timedirty'] = $Result['Time_ID'];

        $boolean = true;
        $count++;
      }

      $countx = 0;
      if ($lang == 'en') {
        $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$Hotp'";
      } else {
        $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$Hotp'";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {

        $return[$countx]['FacCode'] = $Result['FacCode'];
        $return[$countx]['FacName'] = $Result['FacName'];
        $countx++;
      }
      $boolean = true;
      $return['Rowx'] = $countx;


      $count2 = 0;
      $Sql = "SELECT round_time_dirty.Time_ID,time_dirty.TimeName
      FROM round_time_dirty
      INNER JOIN time_dirty ON round_time_dirty.Time_ID = time_dirty.ID
      WHERE round_time_dirty.HptCode = '$Hotp' ORDER BY time_dirty.TimeName ";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count2]['ID'] = $Result['Time_ID'];
        $return[$count2]['time_value'] = $Result['TimeName'];
        $count2++;
      }
      $return['row'] = $count2;




      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "SelectDocument";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return[$count]['HptName']   = "";
        $return[$count]['DepName']   = "";
        $return[$count]['DocNo']   = "";
        $return[$count]['DocDate']   = "";
        $return[$count]['Record']   = "";
        $return[$count]['RecNow']   = "";
        $return[$count]['Total']   = "0.00";
        $return['status'] = "failed";
        $return['form'] = "SelectDocument";
        $return['msg'] = "notchosen";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function ShowItem($conn, $DATA)
    {
      $count = 0;
      $boolean = false;
      $searchitem = str_replace(' ', '%', $DATA["xitem"]);
      $hotpital = $DATA["hotpital"];
      if ($searchitem != '') {
        $Sql = "SELECT item.ItemCode , item.ItemName , item_unit.UnitCode , item_unit.UnitName 
        FROM item , item_unit 
        WHERE item.UnitCode = item_unit.UnitCode 
        AND IsDirtyBag = 1 
        AND item.HptCode = '$hotpital'
        AND (item.ItemCode LIKE '%$searchitem%' OR item.ItemName LIKE '%$searchitem%') AND item.IsActive = 1";
      } else {
        $Sql = "SELECT item.ItemCode , item.ItemName , item_unit.UnitCode , item_unit.UnitName 
        FROM item , item_unit 
        WHERE item.UnitCode = item_unit.UnitCode 
        AND (IsDirtyBag = 1 OR IsDirtyBag = 2 )
        AND item.HptCode = '0' 
        AND (item.ItemCode LIKE '%$searchitem%' OR item.ItemName LIKE '%$searchitem%') AND item.IsActive = 1 
        ORDER BY item.ItemCode";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['ItemCode'] = $Result['ItemCode'];
        $return[$count]['ItemName'] = $Result['ItemName'];
        $return[$count]['UnitCode'] = $Result['UnitCode'];
        $return[$count]['UnitName'] = $Result['UnitName'];
        $ItemCode = $Result['ItemCode'];
        $UnitCode = $Result['UnitCode'];
        $count2 = 0;

        $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $MQuery = mysqli_query($conn, $countM);
        while ($MResult = mysqli_fetch_assoc($MQuery)) {
          if ($MResult['cnt'] != 0) {
            $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
            FROM item_multiple_unit
            INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
            WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
            $xQuery = mysqli_query($conn, $xSql);
            while ($xResult = mysqli_fetch_assoc($xQuery)) {
              $m1 = "MpCode_" . $ItemCode . "_" . $count;
              $m2 = "UnitCode_" . $ItemCode . "_" . $count;
              $m3 = "UnitName_" . $ItemCode . "_" . $count;
              $m4 = "Multiply_" . $ItemCode . "_" . $count;
              $m5 = "Cnt_" . $ItemCode;

              $return[$m1][$count2] = $xResult['MpCode'];
              $return[$m2][$count2] = $xResult['UnitCode'];
              $return[$m3][$count2] = $xResult['UnitName'];
              $return[$m4][$count2] = $xResult['Multiply'];
              $count2++;
            }
          } else {
            $xSql = "SELECT 
              item.UnitCode,
              item_unit.UnitName
            FROM item
            INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
            WHERE item.ItemCode = '$ItemCode'";
            $xQuery = mysqli_query($conn, $xSql);
            while ($xResult = mysqli_fetch_assoc($xQuery)) {
              $m1 = "MpCode_" . $ItemCode . "_" . $count;
              $m2 = "UnitCode_" . $ItemCode . "_" . $count;
              $m3 = "UnitName_" . $ItemCode . "_" . $count;
              $m4 = "Multiply_" . $ItemCode . "_" . $count;
              $m5 = "Cnt_" . $ItemCode;

              $return[$m1][$count2] = 1;
              $return[$m2][$count2] = $xResult['UnitCode'];
              $return[$m3][$count2] = $xResult['UnitName'];
              $return[$m4][$count2] = 1;
              $count2++;
            }
          }
        }
        $return[$m5][$count] = $count2;
        $count++;
        $boolean = true;
      }

      $return['Row'] = $count;

      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "ShowItem";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "success";
        $return['form'] = "ShowItem";
        $return[$count]['RowID'] = "";
        $return[$count]['UsageCode'] = "";
        $return[$count]['itemname'] = "";
        $return[$count]['UnitName'] = "";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function ShowUsageCode($conn, $DATA)
    {
      $count = 0;
      $boolean = false;
      $searchitem = $DATA["xitem"]; //str_replace(' ', '%', $DATA["xitem"]);

      // $Sqlx = "INSERT INTO log ( log ) VALUES ('item : $item')";
      // mysqli_query($conn,$Sqlx);

      $Sql = "SELECT
          item_stock.RowID,
        site.HptName,
        department.DepName,
        item_category.CategoryName,
        item_stock.UsageCode,
        item.ItemCode,
        item.ItemName,
        item.UnitCode,
        item_unit.UnitName,
        item_stock.ParQty,
        item_stock.CcQty,
        item_stock.TotalQty
        FROM site
        INNER JOIN department ON site.HptCode = department.HptCode
        INNER JOIN item_stock ON department.DepCode = item_stock.DepCode
        INNER JOIN item ON item_stock.ItemCode = item.ItemCode
        INNER JOIN item_category ON item.CategoryCode= item_category.CategoryCode
        INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
        WHERE item.ItemCode = '$searchitem'
            AND item_stock.IsStatus = 7
            LImit 100";
      // (item_stock.IsStatus = 1 OR
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['RowID'] = $Result['RowID'];
        $return[$count]['UsageCode'] = $Result['UsageCode'];
        $return[$count]['ItemCode'] = $Result['ItemCode'];
        $return[$count]['ItemName'] = $Result['ItemName'];
        $return[$count]['UnitCode'] = $Result['UnitCode'];
        $return[$count]['UnitName'] = $Result['UnitName'];
        $ItemCode = $Result['ItemCode'];
        $UnitCode = $Result['UnitCode'];
        $count2 = 0;
        $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
        FROM item_multiple_unit
        INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
        WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $xQuery = mysqli_query($conn, $xSql);
        while ($xResult = mysqli_fetch_assoc($xQuery)) {
          $m1 = "MpCode_" . $ItemCode . "_" . $count;
          $m2 = "UnitCode_" . $ItemCode . "_" . $count;
          $m3 = "UnitName_" . $ItemCode . "_" . $count;
          $m4 = "Multiply_" . $ItemCode . "_" . $count;
          $m5 = "Cnt_" . $ItemCode;

          $return[$m1][$count2] = $xResult['MpCode'];
          $return[$m2][$count2] = $xResult['UnitCode'];
          $return[$m3][$count2] = $xResult['UnitName'];
          $return[$m4][$count2] = $xResult['Multiply'];
          $count2++;
        }
        $return[$m5][$count] = $count2;
        $count++;
        $boolean = true;
      }

      $return['Row'] = $count;

      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "ShowUsageCode";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "ShowUsageCode";
        $return[$count]['RowID'] = "";
        $return[$count]['UsageCode'] = "";
        $return[$count]['itemname'] = "";
        $return[$count]['UnitName'] = "";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function getImport($conn, $DATA)
    {
      $count = 0;
      $count2 = 0;
      $boolean = false;
      $Sel = $DATA["Sel"];
      $Hotp = $DATA["Hotp"];
      $DocNo = $DATA["DocNo"];
      $xItemStockId = $DATA["xrow"];
      $ItemStockId = explode(",", $xItemStockId);
      $xqty = $DATA["xqty"];
      $nqty = explode(",", $xqty);
      $xweight = $DATA["xweight"];
      $nweight = explode(",", $xweight);
      $xunit = $DATA["xunit"];
      $nunit = explode(",", $xunit);

      $max = sizeof($ItemStockId, 0);

      for ($i = 0; $i < $max; $i++) {
        $iItemStockId = $ItemStockId[$i];
        $iqty = $nqty[$i];
        $iweight = $nweight[$i] == null ? 0 : $nweight[$i];
        $iunit1 = 0;
        $iunit2 = $nunit[$i];

        $Sql = "SELECT item.ItemCode,item.UnitCode
          FROM item
          WHERE ItemCode = '$iItemStockId'";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $ItemCode  = $Result['ItemCode'];
          $iunit1    = $Result['UnitCode'];
        }

        $Sql = "SELECT COUNT(*) as Cnt
          FROM dirty_detail
          INNER JOIN item  ON dirty_detail.ItemCode = item.ItemCode
          INNER JOIN dirty ON dirty.DocNo = dirty_detail.DocNo
          WHERE dirty.DocNo = '$DocNo'
          AND item.ItemCode = '$ItemCode'";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $chkUpdate = $Result['Cnt'];
        }
        $iqty2 = $iqty;
        if ($iunit1 != $iunit2) {
          $Sql = "SELECT item_multiple_unit.Multiply
            FROM item_multiple_unit
            WHERE item_multiple_unit.UnitCode = $iunit1
            AND item_multiple_unit.MpCode = $iunit2";
          $meQuery = mysqli_query($conn, $Sql);
          while ($Result = mysqli_fetch_assoc($meQuery)) {
            $Multiply = $Result['Multiply'];
            $iqty2 = $iqty / $Multiply;
          }
        }

        if ($chkUpdate == 0) {
          if ($Sel == 1) {
            $Sql = "INSERT INTO dirty_detail
                (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
                VALUES
                ('$DocNo','$ItemCode',$iunit2,$iqty,$iweight,0)";
            mysqli_query($conn, $Sql);
          } else {
            $Sql = "INSERT INTO dirty_detail_sub
                (DocNo,ItemCode,UsageCode)
                VALUES
                ('$DocNo','$ItemCode','$UsageCode')";
            mysqli_query($conn, $Sql);
            $Sql = "UPDATE item_stock SET IsStatus = 3
                WHERE UsageCode = '$UsageCode'";
            mysqli_query($conn, $Sql);
          }
        } else {
          if ($Sel == 1) {
            $Sql = "UPDATE dirty_detail
              SET Weight = (Weight+$iweight),Qty = (Qty+$iqty2)
              WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
            mysqli_query($conn, $Sql);
          } else {
            $Sql = "INSERT INTO dirty_detail_sub
                  (DocNo,ItemCode,UsageCode)
                  VALUES
                  ('$DocNo','$ItemCode','$UsageCode')";
            mysqli_query($conn, $Sql);
            $Sql = "UPDATE item_stock SET IsStatus = 3
                  WHERE UsageCode = '$UsageCode'";
            mysqli_query($conn, $Sql);
          }
        }
      }
      if ($Sel == 2) {
        $n = 0;
        $Sql = "SELECT COUNT(*) AS Qty FROM dirty_detail_sub WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $Qty[$n] = $Result['Qty'];
          $n++;
        }
        for ($i = 0; $i < $n; $i++) {
          $xQty = $Qty[$i];
          // $Sqlx = "INSERT INTO log ( log ) VALUES ('$n :: $xQty :: $chkUpdate :: $iweight')";
          // mysqli_query($conn,$Sqlx);
          if ($chkUpdate == 0) {
            $Sql = "INSERT INTO dirty_detail
                  (DocNo,ItemCode,UnitCode,Qty,Weight,IsCancel)
                  VALUES
                  ('$DocNo','$ItemCode',$iunit2,$xQty,0,0)";
          } else {
            $Sql = "UPDATE dirty_detail SET Qty = $xQty WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
          }
          mysqli_query($conn, $Sql);
        }
      }

      $n = 0;
      $Sql = "SELECT UsageCode FROM dirty_detail_sub WHERE DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $zUsageCode[$n] = $Result['UsageCode'];
        $n++;
      }
      $DepCode = 1;
      $Sql = "SELECT DepCode FROM department
      WHERE department.HptCode = '$Hotp' AND department.IsDefault = 1
      ORDER BY DepCode ASC LIMIT 1";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DepCode = $Result['DepCode'];
      }
      for ($i = 0; $i < $n; $i++) {
        $xUsageCode = $zUsageCode[$i];
        $Sql = "UPDATE item_stock SET DepCode = '$DepCode' WHERE UsageCode = '$xUsageCode'";
        $meQuery = mysqli_query($conn, $Sql);
      }
      ShowDetail($conn, $DATA);
    }

    function UpdateDetailQty($conn, $DATA)
    {
      $RowID  = $DATA["Rowid"];
      $Qty  =  $DATA["Qty"];
      $OleQty =  $DATA["OleQty"];
      $UnitCode =  $DATA["unitcode"];
      $Sql = "UPDATE dirty_detail
      SET Qty1 = $OleQty,Qty2 = $Qty,UnitCode2 = $UnitCode
      WHERE dirty_detail.Id = $RowID";
      mysqli_query($conn, $Sql);
      // ShowDetail($conn, $DATA);
    }

    function UpdateDetailWeight($conn, $DATA)
    {
      $RowID  = $DATA["Rowid"];
      $Weight  =  $DATA["Weight"];
      $Price  =  $DATA["Price"];
      $isStatus = $DATA["isStatus"];
      $DocNo = $DATA["DocNo"];

      //	$Sqlx = "INSERT INTO log ( log ) VALUES ('$RowID / $Weight')";
      //	mysqli_query($conn,$Sqlx);

      $Sql = "UPDATE dirty_detail
      SET Weight = $Weight
      WHERE dirty_detail.Id = $RowID";
      mysqli_query($conn, $Sql);

      $wTotal = 0;
      $Sql = "SELECT SUM(Weight) AS wTotal
        FROM dirty_detail
        WHERE DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $wTotal    = $Result['wTotal'];
        $return[0]['wTotal'] = $Result['wTotal'];
      }
      $Sql = "UPDATE dirty SET Total = $wTotal WHERE DocNo = '$DocNo'";


      if (mysqli_query($conn, $Sql)) {
        $return['status'] = "success";
        $return['form'] = "UpdateDetailWeight";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "UpdateDetailWeight";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function updataDetail($conn, $DATA)
    {
      $RowID  = $DATA["Rowid"];
      $UnitCode =  $DATA["unitcode"];
      $qty =  $DATA["qty"];
      $Sql = "UPDATE dirty_detail
      SET UnitCode = $UnitCode
      WHERE dirty_detail.Id = $RowID";
      mysqli_query($conn, $Sql);
      ShowDetail($conn, $DATA);
    }

    function DeleteItem($conn, $DATA)
    {
      $RowID  = $DATA["rowid"];
      $DocNo = $DATA["DocNo"];
      $HptCode = $DATA["HptCode"];
      $n = 0;


      $Sql = "SELECT dirty_detail_sub.UsageCode,dirty_detail.ItemCode
      FROM dirty_detail
      INNER JOIN dirty_detail_sub ON dirty_detail.DocNo = dirty_detail_sub.DocNo
      WHERE  dirty_detail.Id = $RowID";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $ItemCode = $Result['ItemCode'];
        $UsageCode[$n] = $Result['UsageCode'];
        $n++;
      }

      for ($i = 0; $i < $n; $i++) {
        $xUsageCode = $UsageCode[$i];
        $Sql = "UPDATE item_stock SET IsStatus = 1 WHERE UsageCode = '$xUsageCode'";
        mysqli_query($conn, $Sql);
      }

      $Sql = "DELETE FROM dirty_detail_sub
      WHERE DocNo = '$DocNo' AND ItemCode = '$ItemCode'";
      mysqli_query($conn, $Sql);

      $Sql = "DELETE FROM dirty_detail
      WHERE dirty_detail.Id = $RowID";
      mysqli_query($conn, $Sql);

      $wTotal = 0;
      $Sql2 = "SELECT SUM(Weight) AS wTotal
      FROM dirty_detail_round
      WHERE DocNo = '$DocNo' AND RowID =  $RowID";
      $meQuery = mysqli_query($conn, $Sql2);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $wTotal    = $Result['wTotal'];
      }

      $Sql3 = "UPDATE dirty SET Total = (Total - $wTotal) WHERE DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql3);
      $Sql4 = "SELECT Total FROM dirty WHERE DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql4);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[0]['wTotal'] = $Result['wTotal'];
      }

      $Sql1 = "DELETE FROM dirty_detail_round WHERE  DocNo = '$DocNo' AND RowID =  $RowID ";
      mysqli_query($conn, $Sql1);

      ShowDetailDoc($conn, $DATA);
    }

    function SaveBill($conn, $DATA)
    {
      $DocNo = $DATA["xdocno"];
      $isStatus = $DATA["isStatus"];

      $Sql = "UPDATE dirty SET IsStatus = 0 WHERE dirty.DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);

      $Sql = "UPDATE daily_request SET IsStatus = 0 WHERE daily_request.DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);
    }

    function UpdateRefDocNo($conn, $DATA)
    {
      $DocNo = $DATA["xdocno"];
      $RefDocNo = $DATA["RefDocNo"];
      $Sql = "UPDATE dirty SET RefDocNo = '$RefDocNo' WHERE dirty.DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);
      ShowDocument($conn, $DATA);
    }

    function ShowDetail($conn, $DATA)
    {
      $count = 0;
      $Total = 0;
      $boolean = false;
      $DocNo = $DATA["DocNo"];
      //==========================================================
      $Sql = "SELECT
      dirty_detail.Id,
      dirty_detail.ItemCode,
      item.ItemName,
      item.UnitCode AS UnitCode1,
      item_unit.UnitName,
      dirty_detail.UnitCode AS UnitCode2,
      dirty_detail.Weight,
      dirty_detail.Qty,
      item.UnitCode
      FROM item
      INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
      INNER JOIN dirty_detail ON dirty_detail.ItemCode = item.ItemCode
      INNER JOIN item_unit ON dirty_detail.UnitCode = item_unit.UnitCode
      WHERE dirty_detail.DocNo = '$DocNo'
      ORDER BY dirty_detail.Id DESC";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['RowID']      = $Result['Id'];
        $return[$count]['ItemCode']   = $Result['ItemCode'];
        $return[$count]['ItemName']   = $Result['ItemName'];
        $return[$count]['UnitCode']   = $Result['UnitCode2'];
        $return[$count]['UnitName']   = $Result['UnitName'];
        $return[$count]['Weight']     = $Result['Weight'];
        $return[$count]['Qty']         = $Result['Qty'];
        $UnitCode                     = $Result['UnitCode1'];
        $ItemCode                     = $Result['ItemCode'];
        $count2 = 0;

        $countM = "SELECT COUNT(*) AS cnt FROM item_multiple_unit  WHERE  item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
        $MQuery = mysqli_query($conn, $countM);


        while ($MResult = mysqli_fetch_assoc($MQuery)) {
          if ($MResult['cnt'] != 0) {
            $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
            FROM item_multiple_unit
            INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
            WHERE item_multiple_unit.UnitCode  = $UnitCode AND item_multiple_unit.ItemCode = '$ItemCode'";
            $xQuery = mysqli_query($conn, $xSql);
            while ($xResult = mysqli_fetch_assoc($xQuery)) {
              $m1 = "MpCode_" . $ItemCode . "_" . $count;
              $m2 = "UnitCode_" . $ItemCode . "_" . $count;
              $m3 = "UnitName_" . $ItemCode . "_" . $count;
              $m4 = "Multiply_" . $ItemCode . "_" . $count;
              $m5 = "Cnt_" . $ItemCode;

              $return[$m1][$count2] = $xResult['MpCode'];
              $return[$m2][$count2] = $xResult['UnitCode'];
              $return[$m3][$count2] = $xResult['UnitName'];
              $return[$m4][$count2] = $xResult['Multiply'];
              $count2++;
            }
          } else {
            $xSql = "SELECT 
              item.UnitCode,
              item_unit.UnitName
            FROM item
            INNER JOIN item_unit ON item.UnitCode = item_unit.UnitCode
            WHERE item.ItemCode = '$ItemCode'";
            $xQuery = mysqli_query($conn, $xSql);
            while ($xResult = mysqli_fetch_assoc($xQuery)) {
              $m1 = "MpCode_" . $ItemCode . "_" . $count;
              $m2 = "UnitCode_" . $ItemCode . "_" . $count;
              $m3 = "UnitName_" . $ItemCode . "_" . $count;
              $m4 = "Multiply_" . $ItemCode . "_" . $count;
              $m5 = "Cnt_" . $ItemCode;

              $return[$m1][$count2] = 1;
              $return[$m2][$count2] = $xResult['UnitCode'];
              $return[$m3][$count2] = $xResult['UnitName'];
              $return[$m4][$count2] = 1;
              $count2++;
            }
          }
        }
        $return[$m5][$count] = $count2;
        //================================================================
        $Total += $Result['Weight'];
        //================================================================
        $count++;
        $boolean = true;
      }

      if ($count == 0) $Total = 0;

      $Sql = "UPDATE dirty SET Total = $Total WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);
      $return[0]['Total']    = round($Total, 2);

      $return['Row'] = $count;
      //==========================================================

      $boolean = true;
      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "ShowDetail";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "ShowDetail";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }

    function CancelBill($conn, $DATA)
    {
      $DocNo = $DATA["DocNo"];
      // $Sql = "INSERT INTO log ( log ) VALUES ('DocNo : $DocNo')";
      // mysqli_query($conn,$Sql);
      $Sql = "UPDATE dirty SET IsStatus = 9  WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Sql);
      ShowDocument($conn, $DATA);
    }

    function updateQty($conn, $DATA)
    {
      $newQty = $DATA['newQty'];
      $RowID = $DATA['RowID'];
      $Sql = "UPDATE dirty_detail SET Qty = $newQty WHERE Id = $RowID";
      mysqli_query($conn, $Sql);
    }

    function showDep($conn, $DATA)
    {
      $count              = 0;
      $ItemCode       =    $DATA['ItemCode'];
      $ItemName       =    $DATA['ItemName'];
      $PmID             = $_SESSION['PmID'];

      if ($PmID == 2 || $PmID == 3 || $PmID == 5 || $PmID == 7) {
        $HptCode        = $_SESSION['HptCode'];
      } else {
        $HptCode        = $DATA['HptCode'];
      }
      if ($ItemCode == 'Dirty4' || $ItemCode == 'Dirty5' || $ItemCode == 'Dirty6') {
        $Sql = "SELECT dep.DepCode, dep.DepName FROM department dep 
        WHERE dep.HptCode = '$HptCode' AND dep.IsStatus = 0 AND dep.IsActive = 1 AND  (dep.DepName ='linen' OR  dep.DepName = 'Linen Processing' )
        ORDER BY dep.DepName ASC ";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $return[$count]['DepCode'] = trim($Result['DepCode']);
          $return[$count]['DepName'] = trim($Result['DepName']);
          $count++;
        }
      } else {
        $Sql = "SELECT dep.DepCode, dep.DepName FROM department dep 
      WHERE dep.HptCode = '$HptCode' AND dep.IsStatus = 0 AND dep.IsActive = 1
      ORDER BY dep.DepName ASC ";
        $meQuery = mysqli_query($conn, $Sql);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $return[$count]['DepCode'] = trim($Result['DepCode']);
          $return[$count]['DepName'] = trim($Result['DepName']);
          $count++;
        }
      }
      $return['CountDep'] = $count;
      $return['ItemCode'] = $DATA['ItemCode'];
      $return['ItemName'] = $DATA['ItemName'];
      $return['status'] = "success";
      $return['form'] = "showDep";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }

    function confirmDep($conn, $DATA)
    {
      $DocNo = $DATA['DocNo'];
      $ItemCode = $DATA['ItemCode'];
      $DepCode = explode(',', $DATA['DepCode']);
      $limit = sizeof($DepCode, 0);
      for ($i = 0; $i < $limit; $i++) {
        $count = "SELECT COUNT(*) as cnt FROM dirty_detail WHERE DocNo = '$DocNo' AND DepCode = '$DepCode[$i]' AND ItemCode = '$ItemCode'";
        $meQuery = mysqli_query($conn, $count);
        $Result = mysqli_fetch_assoc($meQuery);
        if ($Result['cnt'] == 0) {
          $Insert = "INSERT dirty_detail (DocNo, ItemCode, UnitCode, DepCode, Qty)VALUES('$DocNo', '$ItemCode', 1, '$DepCode[$i]', 0)";
          mysqli_query($conn, $Insert);
        }
      }
      ShowDetailDoc($conn, $DATA);
    }
    function ShowDetailDoc($conn, $DATA)
    {
      $count1   = 0;
      $Total     = 0;
      $boolean  = false;
      $DocNo    = $DATA["DocNo"];
      $HptCode = $DATA["HptCode"];
      //==========================================================

      $SqlItem = "SELECT
        dirty_detail.Id,
        dirty_detail.ItemCode,
        item.ItemName,
        item.UnitCode AS UnitCode1,
        item_unit.UnitName,
        dirty_detail.UnitCode AS UnitCode2,
        item.UnitCode,
        department.DepCode,
        department.DepName,
        dirty_detail.RequestName,
        (
          SELECT
            Total
          FROM
            dirty
          WHERE
            DocNo = '$DocNo'
        ) AS Total
      FROM
        item
      INNER JOIN item_category ON item.CategoryCode = item_category.CategoryCode
      RIGHT JOIN dirty_detail ON dirty_detail.ItemCode = item.ItemCode
      INNER JOIN department ON department.DepCode = dirty_detail.DepCode
      INNER JOIN item_unit ON dirty_detail.UnitCode = item_unit.UnitCode
      WHERE
        dirty_detail.DocNo = '$DocNo' AND department.HptCode = '$HptCode'
      ORDER BY
        dirty_detail.DepCode,
        dirty_detail.ItemCode ASC";
      $return['SqlItem'] = $SqlItem;
      $meQuery = mysqli_query($conn, $SqlItem);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $RowID = $Result['Id'];
        $Weight = 0;
        $Qty = 0;
        #-------------------------------------------------------------------------------------------------------------------------
        $Sql = "SELECT SUM(Qty) AS Qty, SUM(Weight) AS Weight FROM dirty_detail_round WHERE DocNo = '$DocNo' AND RowID = $RowID";
        $RoundQuery = mysqli_query($conn, $Sql);
        while ($RoundResult = mysqli_fetch_assoc($RoundQuery)) {
          $Weight = round($RoundResult['Weight'], 2) == null ? 0 : $RoundResult['Weight'];
          $Qty = $RoundResult['Qty'] == null ? 0 : $RoundResult['Qty'];
        }
        $return['sqsqs'] = $Sql;
        #-------------------------------------------------------------------------------------------------------------------------
        $count2 = 0;
        $return[$count1]['RowID']     = $Result['Id'];
        $return[$count1]['ItemCode']  = $Result['ItemCode'] == null ? "RequestName" : $Result['ItemCode'];
        $return[$count1]['ItemName']  = $Result['ItemName'] == null ? $Result['RequestName'] : $Result['ItemName'];
        $return[$count1]['UnitCode']  = $Result['UnitCode2'];
        $return[$count1]['UnitName']  = $Result['UnitName'];
        $return[$count1]['DepCode']   = $Result['DepCode'];
        $return[$count1]['DepName']   = $Result['DepName'];
        $return['Total']   = number_format($Result['Total'], 2);
        $return[$count1]['Weight']    = number_format($Weight, 2);
        $return[$count1]['Qty']       = $Qty;
        $UnitCode                     = $Result['UnitCode'] == 0 ? '0' : $Result['UnitCode'];
        $ItemCode                     = $Result['ItemCode'] == 0 ? '0' : $Result['ItemCode'];
        $count1++;
      }




      $cntUnit = 0;
      $xSql = "SELECT item_multiple_unit.MpCode,item_multiple_unit.UnitCode,item_unit.UnitName,item_multiple_unit.Multiply
          FROM item_multiple_unit
          INNER JOIN item_unit ON item_multiple_unit.MpCode = item_unit.UnitCode
          WHERE item_unit.IsStatus = 0 GROUP BY item_multiple_unit.UnitCode";
      $xQuery = mysqli_query($conn, $xSql);
      while ($xResult = mysqli_fetch_assoc($xQuery)) {
        $return['Unit'][$cntUnit]['MpCode'] = $xResult['MpCode'];
        $return['Unit'][$cntUnit]['UnitCode'] = $xResult['UnitCode'];
        $return['Unit'][$cntUnit]['UnitName'] = $xResult['UnitName'];
        $return['Unit'][$cntUnit]['Multiply'] = $xResult['Multiply'];
        $cntUnit++;
      }

      $return[0]['Total']    = round($Total, 2);
      $return['CountDep'] = $count1;
      $return['status'] = "success";
      $return['form'] = "ShowDetailDoc";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }

    function showDepRequest($conn, $DATA)
    {
      $count = 0;
      $HptCodePAGE = $DATA['HptCode'];
      $PmID = $_SESSION['PmID'];
      $HptCodeSESSION = $_SESSION['HptCode'];

      if ($PmID   == 1 || $PmID == 6) {
        $HptCode = $HptCodePAGE;
      } else {
        $HptCode = $HptCodeSESSION;
      }

      $Sql = "SELECT dep.DepCode, dep.DepName FROM department dep 
      WHERE dep.HptCode = '$HptCode' AND dep.IsStatus = 0 AND dep.IsActive = 1
      ORDER BY dep.DepName ASC ";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count]['DepCode'] = trim($Result['DepCode']);
        $return[$count]['DepName'] = trim($Result['DepName']);
        $count++;
      }
      $return['CountDep'] = $count;
      // $return['ItemCode'] = $DATA['ItemCode'];
      // $return['ItemName'] = $DATA['ItemName'];
      $return['status'] = "success";
      $return['form'] = "showDepRequest";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    function confirmDep2($conn, $DATA)
    {
      $HptCode = $DATA['HptCode'];
      $DocNo = $DATA['DocNo'];
      $RequestName = trim($DATA['RequestName']);
      $DepCode = explode(',', $DATA['DepCode']);
      $limit = sizeof($DepCode, 0);
      for ($i = 0; $i < $limit; $i++) {
        $count = "SELECT COUNT(*) as cnt FROM dirty_detail WHERE DocNo = '$DocNo' AND DepCode = '$DepCode[$i]' AND RequestName = '$RequestName'";
        $meQuery = mysqli_query($conn, $count);
        $Result = mysqli_fetch_assoc($meQuery);
        if ($Result['cnt'] == 0) {
          $Insert = "INSERT dirty_detail (DocNo, RequestName, UnitCode, DepCode, Qty , ItemCode )VALUES('$DocNo', '$RequestName', 1, '$DepCode[$i]', 1 , 'HDL')";
          mysqli_query($conn, $Insert);
        }
      }
      ShowDetailDoc($conn, $DATA);
    }
    function GetRound($conn, $DATA)
    {
      $DocNo = $DATA['DocNo'];
      $RowID = $DATA['RowID'];
      $count = 0;

      $Sql = "SELECT Id, Qty, Weight FROM dirty_detail_round WHERE DocNo = '$DocNo' AND RowID = $RowID";
      mysqli_query($conn, $Sql);

      $Query = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($Query)) {
        $return['ValueObj'][$count]['Id'] = $Result['Id'];
        $return['ValueObj'][$count]['Qty'] = $Result['Qty'];
        $return['ValueObj'][$count]['Weight'] = number_format($Result['Weight'], 2);
        $count++;
      }

      $wTotal = 0;
      $Sql = "SELECT SUM(Weight) AS wTotal
      FROM dirty_detail_round
      WHERE DocNo = '$DocNo'";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $wTotal    = $Result['wTotal'];
        $return[0]['wTotal'] = $Result['wTotal'];
      }

      if($wTotal == ''){
        $wTotal = 0;
      }
      $Sql = "UPDATE dirty SET Total = '$wTotal' WHERE DocNo = '$DocNo' ";
      $meQuery = mysqli_query($conn, $Sql);


      $return['ItemName'] = $DATA['ItemName'];
      $return['ItemCode'] = $DATA['ItemCode'];
      $return['RowID'] = $DATA['RowID'];

      $return['status'] = "success";
      $return['form'] = "GetRound";
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
    function SaveRound($conn, $DATA)
    {
      $DocNo = $DATA['DocNo'];
      $RowID = $DATA['RowID'];
      $ItemCode = $DATA['ItemCode'];
      $DepCode = $DATA['DepCode'];
      $Qty = $DATA['Qty'] == null ? 0 : $DATA['Qty'];
      $Weight = $DATA['Weight'] == null ? 0 : $DATA['Weight'];
      $RequestName = "";
      $count = 0;
      $RequestName = $DATA['ItemName'];

      $Sql = "SELECT DepCode FROM dirty_detail WHERE Id =$RowID  ";
      $meQuery = mysqli_query($conn, $Sql);
      $Result = mysqli_fetch_assoc($meQuery);
      $DepCodex = $Result['DepCode'];
      // ?????? DepCode ???????????????????????????????????????????????????????????? FacCode
      $Sql = "INSERT INTO dirty_detail_round(DocNo, RowID, ItemCode, DepCode, RequestName, Qty, Weight)VALUES
              ('$DocNo', $RowID, '$ItemCode', '$DepCodex', '$RequestName', $Qty, $Weight)";
      mysqli_query($conn, $Sql);

      $Sql = "SELECT SUM(Weight) AS Total FROM dirty_detail_round WHERE dirty_detail_round.DocNo = '$DocNo' LIMIT 1";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Weight = $Result['Total'];
      }

      $Sql = "SELECT SUM(Weight) AS Weight2 , SUM(Qty) AS Qty2 FROM dirty_detail_round WHERE RowID = $RowID";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Weight2 = round($Result['Weight2'], 2);
        $Qty2 = $Result['Qty2'];
      }

      $Update = "UPDATE dirty_detail SET Weight = $Weight2, Qty = $Qty2 WHERE dirty_detail.Id = '$RowID'";
      mysqli_query($conn, $Update);

      $Update = "UPDATE dirty SET Total = $Weight WHERE DocNo = '$DocNo'";
      mysqli_query($conn, $Update);

      GetRound($conn, $DATA);
    }
    function SavEditRound($conn, $DATA)
    {
      $DocNo = $DATA['DocNo'];
      $ID = $DATA['ID'];

      $Qty = $DATA['Qty'] == null ? 0 : $DATA['Qty'];
      $Weight = $DATA['Weight'] == null ? 0 : $DATA['Weight'];

      $Sql = "UPDATE dirty_detail_round SET Qty = $Qty, Weight = $Weight WHERE Id = $ID";
      mysqli_query($conn, $Sql);



      // SELECT ?????? rowid ????????? dirty_Detail
      $Sql = "SELECT RowID FROM dirty_detail_round WHERE Id = $ID";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $RowID = $Result['RowID'];
      }

      //????????????????????? qty , weight  
      $Sql = "SELECT SUM(Weight) AS Weight2 , SUM(Qty) AS Qty2 FROM dirty_detail_round WHERE RowID = $RowID";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Weight2 = $Result['Weight2'];
        $Qty2 = $Result['Qty2'];
      }

      // update ???????????? dirty_detail
      $Update = "UPDATE dirty_detail SET Weight = $Weight2, Qty = $Qty2 WHERE dirty_detail.Id = '$RowID'";
      mysqli_query($conn, $Update);

      GetRound($conn, $DATA);
    }
    function getfactory($conn, $DATA)
    {

      $lang     = $DATA["lang"];
      $hotpital = $DATA["hotpital"] == null ? $_SESSION['HptCode'] : $DATA["hotpital"];
      $boolean  = false;
      $countx = 0;
      if ($lang == 'en') {
        $Sql = "SELECT factory.FacCode,factory.FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$hotpital'";
      } else {
        $Sql = "SELECT factory.FacCode,factory.FacNameTH AS FacName FROM factory WHERE factory.IsCancel = 0 AND HptCode ='$hotpital'";
      }
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {

        $return[$countx]['FacCode'] = $Result['FacCode'];
        $return[$countx]['FacName'] = $Result['FacName'];
        $countx++;
      }
      $boolean = true;
      $return['Rowx'] = $countx;

      if ($boolean) {
        $return['status'] = "success";
        $return['form'] = "getfactory";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "failed";
        $return['form'] = "getfactory";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }
    function timedirty($conn, $DATA)
    {
      $count = 0;
      $count2 = 0;
      $boolean = false;
      $boolean2 = false;
      $Hotp = $DATA["Hotp"] == 'Undefined ' ? $_SESSION['HptCode'] : $DATA["Hotp"];
      $Sql = " SELECT round_time_dirty.Time_ID,time_dirty.TimeName
                FROM round_time_dirty
                INNER JOIN time_dirty ON round_time_dirty.Time_ID = time_dirty.ID
                WHERE round_time_dirty.HptCode = '$Hotp' 
                ORDER BY time_dirty.TimeName ";
      $meQuery = mysqli_query($conn, $Sql);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $return[$count2]['ID'] = $Result['Time_ID'];
        $return[$count2]['time_value'] = $Result['TimeName'];
        $count2++;
        $boolean2 = true;
      }
      $return['row'] = $count2;

      if ($Result = mysqli_fetch_assoc($meQuery)) {
        $return['status'] = "success";
        $return['form'] = "timedirty";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      } else {
        $return['status'] = "success";
        $return['form'] = "timedirty";
        echo json_encode($return);
        mysqli_close($conn);
        die;
      }
    }
    //==========================================================
    //
    //==========================================================
    if (isset($_POST['DATA'])) {
      $data = $_POST['DATA'];
      $DATA = json_decode(str_replace('\"', '"', $data), true);

      if ($DATA['STATUS'] == 'OnLoadPage') {
        OnLoadPage($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'getDepartment') {
        getDepartment($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'ShowItem') {
        ShowItem($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'ShowUsageCode') {
        ShowUsageCode($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'ShowDocument') {
        ShowDocument($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'SelectDocument') {
        SelectDocument($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'CreateDocument') {
        CreateDocument($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'CancelDocNo') {
        CancelDocNo($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'getImport') {
        getImport($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'ShowDetail') {
        ShowDetail($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'UpdateDetailQty') {
        UpdateDetailQty($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'updataDetail') {
        updataDetail($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'UpdateDetailWeight') {
        UpdateDetailWeight($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'DeleteItem') {
        DeleteItem($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'SaveBill') {
        SaveBill($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'CancelBill') {
        CancelBill($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'UpdateRefDocNo') {
        UpdateRefDocNo($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'updateQty') {
        updateQty($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'showDep') {
        showDep($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'confirmDep') {
        confirmDep($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'ShowDetailDoc') {
        ShowDetailDoc($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'showDepRequest') {
        showDepRequest($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'confirmDep2') {
        confirmDep2($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'GetRound') {
        GetRound($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'SaveRound') {
        SaveRound($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'SavEditRound') {
        SavEditRound($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'getfactory') {
        getfactory($conn, $DATA);
      } elseif ($DATA['STATUS'] == 'timedirty') {
        timedirty($conn, $DATA);
      }
    } else {
      $return['status'] = "error";
      $return['msg'] = 'noinput';
      echo json_encode($return);
      mysqli_close($conn);
      die;
    }
