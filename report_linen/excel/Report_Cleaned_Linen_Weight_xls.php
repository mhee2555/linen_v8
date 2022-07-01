<?php
// include composer autoload
include_once('connect.php');
require 'vendor/autoload.php';
require('../report/Class.php');


// $language = $_SESSION['lang'];
// if ($language == "en") {
//     $language = "en";
// } else {
$language = "th";
// }
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
$data = explode(',', $_GET['data']);
// echo "<pre>";
// print_r($data);
// echo "</pre>";
$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
$DepCode = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$DepCode = [];
$DepName = [];
$GroupCode = [];
$GroupName = [];
$DateShow = [];
$sumdayTotalqty = 0;
$sumdayWeight = 0;
$TotaldayTotalqty = 0;
$TotaldayWeight = 0;
if ($language == 'th') {
    $HptName = "HptNameTH";
    $FacName = "FacNameTH";
} else {
    $HptName = "HptName";
    $FacName = "FacName";
}

// header

if ($chk == 'one') {
    if ($format == 1) {
        $where =   "WHERE DATE (clean.Docdate) = DATE('$date1')";
        list($year, $mouth, $day) = explode("-", $date1);
        $datetime = new DatetimeTH();
        if ($language == 'th') {
            $year = $year + 543;
            $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
        } else {
            $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
        }
    } elseif ($format = 3) {
        $where = "WHERE  year (clean.DocDate) LIKE '%$date1%'";
        if ($language == "th") {
            $date1 = $date1 + 543;
            $date_header = $array['year'][$language] . " " . $date1;
        } else {
            $date_header = $array['year'][$language] . $date1;
        }
    }
} elseif ($chk == 'between') {
    $where =   "WHERE clean.Docdate BETWEEN '$date1' AND '$date2'";
    list($year, $mouth, $day) = explode("-", $date1);
    list($year2, $mouth2, $day2) = explode("-", $date2);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
        $year2 = $year2 + 543;
        $year = $year + 543;
        $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year . $array['to'][$language] .
            $array['date'][$language] . $day2 . " " . $datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $year2;
    } else {
        $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year . " " . $array['to'][$language] . " " .
            $day2 . " " . $datetime->getmonthFromnum($mouth2) . " " . $year2;
    }
} elseif ($chk == 'month') {
    $where =   "WHERE month (clean.Docdate) = " . $date1;
    $datetime = new DatetimeTH();
    if ($language == 'th') {
        $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
    } else {
        $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
    }
} elseif ($chk == 'monthbetween') {
    $where =   "WHERE DATE(clean.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
    list($year, $mouth, $day) = explode("-", $betweendate1);
    list($year2, $mouth2, $day2) = explode("-", $betweendate2);
    $datetime = new DatetimeTH();
    if ($language == 'th') {
        $year = $year + 543;
        $year2 = $year2 + 543;
        $date_header = $array['month'][$language] . $datetime->getTHmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getTHmonthFromnum($date2) . " $year2 ";
    } else {
        $date_header = $array['month'][$language] . $datetime->getmonthFromnum($date1) . " $year " . $array['to'][$language] . " " . $datetime->getmonthFromnum($date2) . " $year2 ";
    }
}


if ($language == 'th') {
    $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
    $printdate = date('d') . " " . date('F') . " " . date('Y');
}



// import the PhpobjPHPExcel Class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$objPHPExcel = new Spreadsheet();

// การกำหนดค่า ข้อมูลเกี่ยวกับไฟล์ excel 
$objPHPExcel->getProperties()
    ->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription(
        "Test document for Office 2007 XLSX, generated using PHP classes."
    )
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

$date_cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$date_cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
$round_AZ1 = sizeof($date_cell1);
$round_AZ2 = sizeof($date_cell2);
for ($a = 0; $a < $round_AZ1; $a++) {
    for ($b = 0; $b < $round_AZ2; $b++) {
        array_push($date_cell1, $date_cell1[$a] . $date_cell2[$b]);
    }
}


if ($chk == 'one') {
    if ($format == 1) {
        $count = 1;
        $date[] = $date1;
        list($y, $m, $d) = explode('-', $date1);
        if ($language ==  'th') {
            $y = $y + 543;
        }
        $date1 = $d . '-' . $m . '-' . $y;
        $DateShow[] = $date1;
    } elseif ($format = 3) {
        if ($language == 'th') {
            $date1 = $date1 - 543;
        }
        $year = $date1;
        $monthh = 12;
        for ($i = 1; $i <= $monthh; $i++) {
            $count = 12;
            $datequery =  $year . '-' . $i;
            $dateshow = '-' . $i . '-' . $year;
            $date[] = $datequery;
            $datetime = new DatetimeTH();
            if ($language == 'th') {
                $date_header1 = $datetime->getTHmonthFromnum($i);
            } else {
                $date_header1 =  $datetime->getmonthFromnum($i);
            }
            $DateShow[] = $date_header1;
        }
    }
} elseif ($chk == 'between') {
    $begin = new DateTime($date1);
    $end = new DateTime($date2);
    $end = $end->modify('1 day');

    $interval = new DateInterval('P1D');
    $period = new DatePeriod($begin, $interval, $end);
    foreach ($period as $key => $value) {
        $date[] = $value->format('Y-m-d');
    }
    $count = count($date);

    $count = 0;
    foreach ($date as $key => $value) {
        $date1 = $value;
        list($y, $m, $d) = explode('-', $date1);
        if ($language ==  'th') {
            $y = $y + 543;
        }
        $date1 = $d . '-' . $m . '-' . $y;
        $DateShow[] = $date1;
        $count++;
    }
} elseif ($chk == 'month') {
    $day = 1;
    if ($language ==  'th') {
        $y = $year1 + 543;
    } else {
        $y = $year1;
    }
    $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
    $datequery =  $year1 . '-' . $date1 . '-';
    $dateshow = '-' . $date1 . '-' . $y;
    for ($i = 0; $i < $count; $i++) {
        if ($day < 10) {
            $day = '0' . $day;
        }
        $date[] = $datequery . $day;
        $DateShow[] = $day . $dateshow;
        $day++;
    }
} elseif ($chk == 'monthbetween') {
    list($year, $month, $day) = explode('-', $betweendate2);
    $betweendate2 = $year . "-" . $month . "-" . $day;
    $period = new DatePeriod(
        new DateTime($betweendate1),
        new DateInterval('P1M'),
        new DateTime($betweendate2)
    );
    foreach ($period as $key => $value) {
        $date[] = $value->format('Y-m');
        $datetime = new DatetimeTH();
        if ($language == 'th') {
            $year = $value->format('Y') + 543;
            $date_header1 = $datetime->getTHmonthFromnum($value->format('m')) . "  ($year)  ";
        } else {
            $year = $value->format('Y');
            $date_header1 = $datetime->getTHmonthFromnum($value->format('m')) . "  ($year)  ";
        }
        $DateShow[] = $date_header1;
    }
    $count = sizeof($date);
}

$status_group = 1;
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A8',  $array2['factory'][$language])
    ->setCellValue('B8',  $array2['itemname'][$language]);
// Write data from MySQL result
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r3'][$language]);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'รายละเอียด');
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
// -----------------------------------------------------------------------------------
$query = "  SELECT
              item.ItemCode,
              item.ItemName,
              factory.$FacName,
              clean_detail.RequestName
              FROM
              clean
              INNER JOIN (SELECT DocNo,ItemCode,RequestName FROM clean_detail) AS clean_detail ON clean.DocNo = clean_detail.DocNo
              LEFT JOIN (SELECT ItemCode , ItemName FROM item) AS item ON clean_detail.ItemCode = item.ItemCode
              INNER JOIN factory ON factory.FacCode = clean.FacCode
              $where
              AND clean.isStatus <> 9 AND clean.isStatus <> 0
              AND clean.FacCode = '$FacCode'
              GROUP BY clean_detail.ItemCode,clean_detail.RequestName   ";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  if ($Result['RequestName'] <> null) {
    $Result["ItemName"] = $Result['RequestName'];
  }
  if ($status_group == 1) {
    $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
  }
  $i++;
  $ItemName[] =  $Result["ItemName"];
  $ItemCode[] =  $Result["ItemCode"];
  $status_group = 0;
}
// echo "<pre>";
// print_r($ItemName);
// echo "</pre>";
// echo "<pre>";
// print_r($ItemCode);
// echo "</pre>";
// -----------------------------------------------------------------------------------
$r = 2;
$d = 1;
$rows = 9;
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'จำนวนชิ้น');
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
  $r++;
}
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'จำนวนชิ้น');
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '8', 'นน.(Kg)');
$r++;
// -----------------------------------------------------------------------------------
$r = 2;
$j = 3;
$d = 1;
for ($row = 0; $row < $count; $row++) {
  $objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', $DateShow[$row]);
  $r += 2;
  $j += 2;
  $d++;
}
$objPHPExcel->getActiveSheet()->mergeCells($date_cell1[$r] . '7:' . $date_cell1[$j] . '7');
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . '7', "total");
// -----------------------------------------------------------------------------------
$start_row = 9;
$r = 1;
$j = 3;
$lek = 0;
foreach ($ItemCode as $keyitemCode => $itemcodeloop) {
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ItemName[$keyitemCode]);
  $r++;

  foreach ($date as $key => $val) {
    $aTotalqty[$key] = 0;
    $aWeight[$key] = 0;
    $Date_chk[$key] = 0;
  }
  $cnt = 0;
  if ($chk == 'between' || $chk == 'month') {
    $data = "SELECT   COALESCE(SUM(clean_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(clean_detail.Weight),'0') AS Weight,
                      DATE( clean.DocDate ) AS Date_chk
              FROM clean_detail 
              INNER JOIN (SELECT DocNo,DocDate,FacCode,isStatus,DepCode FROM clean) AS clean ON clean.DocNo = clean_detail.DocNo
              INNER JOIN department ON department.DepCode = clean.DepCode
              INNER JOIN site ON site.HptCode = department.HptCode WHERE  DATE(clean.DocDate) IN (";
    foreach ($date as $key => $dateval) {
      $data .= " '$dateval' ,";
    }
    $data = rtrim($data, ' ,');
    $data .= " )  AND clean.isStatus <> 9 
                  AND clean.isStatus <> 0  
                  AND clean.Faccode = '$FacCode' 
                  AND site.HptCode = '$HptCode' ";
    if ($itemcodeloop == null || $itemcodeloop == '') {
      $data .= " AND clean_detail.RequestName = '$ItemName[$keyitemCode]' ";
    } else {
      $data .= " AND clean_detail.ItemCode = '$itemcodeloop' ";
    }
    $data .= "GROUP BY  DATE( clean.DocDate ) 
              ORDER BY clean.DocDate ASC ";
              // echo "<pre>";
              // echo $data;
              // echo "</pre>";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $aTotalqty[$cnt] =  $Result["Totalqty"];
      $aWeight[$cnt] =  $Result["Weight"];
      $Date_chk[$cnt] =  $Result["Date_chk"];
      $cnt++;
    }
    $sumdayTotalqty = 0;
    $sumdayWeight = 0;
    $x = 0;
    foreach ($date as $key => $val) {
      if ($Date_chk[$x]  == $val) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $aTotalqty[$x]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $aWeight[$x]);
        $r++;
        $sumdayWeight += $aWeight[$x];
        $sumdayTotalqty +=  $aTotalqty[$x];
        $x++;
      } else {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
        $r++;
      }
    }
  } else {
    for ($day = 0; $day < $count; $day++) {
      $data = "SELECT   COALESCE(SUM(clean_detail.Qty),'0') AS Totalqty,
                          COALESCE(SUM(clean_detail.Weight),'0') AS Weight
                        FROM clean_detail 
                        INNER JOIN (SELECT DocNo,DocDate,FacCode,isStatus,DepCode FROM clean) AS clean ON clean.DocNo = clean_detail.DocNo
                        INNER JOIN department ON department.DepCode = clean.DepCode
                        INNER JOIN site ON site.HptCode = department.HptCode";
      if ($chk == 'one') {
        if ($format == 1) {
          $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
        } elseif ($format = 3) {
          list($year, $month) = explode('-', $date[$day]);
          $data .=   " WHERE  YEAR(clean.DocDate)  ='$year'  AND MONTH(clean.DocDate)  ='$month' AND clean.isStatus <> 9 AND clean.isStatus <> 0";
        }
      } elseif ($chk == 'between') {
        $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
      } elseif ($chk == 'month') {
        $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
      } elseif ($chk == 'monthbetween') {
        list($year, $month) = explode('-', $date[$day]);
        $data .=   " WHERE  YEAR(clean.DocDate)  ='$year'  AND MONTH(clean.DocDate)  ='$month' AND clean.isStatus <> 9 AND clean.isStatus <> 0";
      }

      $data .= " AND clean.Faccode = '$FacCode'
                  AND site.HptCode = '$HptCode' ";
      if ($itemcodeloop == null || $itemcodeloop == '') {
        $data .= " AND clean_detail.RequestName = '$ItemName[$keyitemCode]' ";
      } else {
        $data .= " AND clean_detail.ItemCode = '$itemcodeloop' ";
      }


      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
        $r++;
        $sumdayTotalqty += $Result["Totalqty"];
        $sumdayWeight += $Result["Weight"];
      }
    }
  }





  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
  $r++;
  $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
  $sumdayTotalqty = 0;
  $sumdayWeight = 0;
  $r = 1;
  $start_row++;
  $lek++;
}

// -----------------------------------------------------------------------------------
$r = 1;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 'total');
$r++;


foreach ($date as $key => $val) {
  $aTotalqty[$key] = 0;
  $aWeight[$key] = 0;
  $Date_chk[$key] = 0;
}
$cnt = 0;
if ($chk == 'between' || $chk == 'month' ) {
  $data =       "SELECT
                      COALESCE(SUM(clean_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(clean_detail.Weight),'0') AS Weight,
                      DATE( clean.DocDate ) AS Date_chk
  FROM
  clean_detail
  INNER JOIN clean ON clean.DocNo = clean_detail.DocNo
        INNER JOIN department ON department.DepCode = clean.DepCode
        INNER JOIN site ON site.HptCode = department.HptCode
        LEFT JOIN item ON item.itemcode = clean_detail.itemcode WHERE  DATE(clean.DocDate) IN (";
  foreach ($date as $key => $dateval) {
    $data .= " '$dateval' ,";
  }
  $data = rtrim($data, ' ,');
  $data .= ") AND clean.isStatus <> 9 
                AND clean.isStatus <> 0 
                AND clean.Faccode = '$FacCode' 
                AND site.HptCode = '$HptCode' ";
  $data .= "GROUP BY  DATE( clean.DocDate ) 
            ORDER BY clean.DocDate ASC ";
  $meQuery = mysqli_query($conn, $data);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $aTotalqty[$cnt] =  $Result["Totalqty"];
    $aWeight[$cnt] =  $Result["Weight"];
    $Date_chk[$cnt] =  $Result["Date_chk"];
    $cnt++;
  }


  $sumdayTotalqty = 0;
  $sumdayWeight = 0;
  $x = 0;
  foreach ($date as $key => $val) {
    if ($Date_chk[$x]  == $val) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $aTotalqty[$x]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $aWeight[$x]);
      $r++;
      $sumdayWeight += $aWeight[$x];
      $sumdayTotalqty +=  $aTotalqty[$x];
      $x++;
    } else {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
      $r++;
    }
  }
} else {
  for ($day = 0; $day < $count; $day++) {
    $data =       "SELECT
                COALESCE(SUM(clean_detail.Qty),'0') AS Totalqty,
                        COALESCE(SUM(clean_detail.Weight),'0') AS Weight
                FROM
                clean_detail
                INNER JOIN clean ON clean.DocNo = clean_detail.DocNo
                      INNER JOIN department ON department.DepCode = clean.DepCode
                      INNER JOIN site ON site.HptCode = department.HptCode
                      LEFT JOIN item ON item.itemcode = clean_detail.itemcode";

    if ($chk == 'one') {
      if ($format == 1) {
        $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
      } elseif ($format = 3) {
        list($year, $month) = explode('-', $date[$day]);
        $data .=   " WHERE  YEAR(clean.DocDate)  ='$year'  AND MONTH(clean.DocDate)  ='$month' AND clean.isStatus <> 9 AND clean.isStatus <> 0";
      }
    } elseif ($chk == 'between') {
      $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
    } elseif ($chk == 'month') {
      $data .=   " WHERE  DATE(clean.DocDate)  ='$date[$day]'  AND clean.isStatus <> 9 AND clean.isStatus <> 0";
    } elseif ($chk == 'monthbetween') {
      list($year, $month) = explode('-', $date[$day]);
      $data .=   " WHERE  YEAR(clean.DocDate)  ='$year'  AND MONTH(clean.DocDate)  ='$month' AND clean.isStatus <> 9 AND clean.isStatus <> 0";
    }
    $data .= " AND clean.Faccode = '$FacCode'
               AND site.HptCode = '$HptCode' ";
    $meQuery = mysqli_query($conn, $data);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Totalqty"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
      $r++;
      $sumdayTotalqty += $Result["Totalqty"];
      $sumdayWeight += $Result["Weight"];
    }
  }
}




$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
// ----------------------


           
$A5 = array(
    'alignment' => array(
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),

    'font'  => array(
      'bold'  => true,
      'size'  => 20,
      'name'  => 'THSarabun'
    )
  );

  $A7 = array(
    'alignment' => array(
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),

    'font'  => array(
      'bold'  => true,
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );

  $fill = array(
    'alignment' => array(
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 8,
      'name'  => 'THSarabun'
    )
  );


  $styleArray = array(

    'borders' => array(

      'allBorders' => array(

        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
        'color' => array('argb' => '010203'),
      )
    )
  );



  $colorfill = array(
    'fill' => array(
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => array('rgb' => 'B9E3E6')
    )
  );
  
  $r1 = $r - 1;
  $objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($A5);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($fill);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);
  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }

  $objPHPExcel->getActiveSheet()->setTitle($array2['r3']['en']);
  $objPHPExcel->createSheet();
  $objPHPExcel->removeSheetByIndex($objPHPExcel->getIndex($objPHPExcel->getSheetByName('Worksheet')));


//   $objPHPExcel->setActiveSheetIndex(0);

$writer = new Xlsx($objPHPExcel);

// ชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = $array2['r3']['en'] . "_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
