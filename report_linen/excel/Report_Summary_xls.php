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

$HptCode = $data[0];
$FacCode = $data[1];
$date1 = $data[2];
$date2 = $data[3];
$betweendate1 = $data[4];
$betweendate2 = $data[5];
$format = $data[6];
// $DepCode[] = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$itemfromweb = $data[11];
$time_express = $data[12];

if ($data[7] == "0") {
    if ($itemfromweb == '0') {
        $DepCode = explode(',', $_GET['Dep10']);
    }else{
           $DepCode[0] = 0;
    }
//   if(isset($_GET['Dep10'])){
//   }else{
//     $DepCode[0] = 0;
//   }
} else {
  $DepCode[0] = $data[7] == null ? '0' : $data[7];
}

$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$itemCode = [];
$itemName = [];
$Weight = [];
$DateShow = [];
$ISSUE = 0;
$TOTAL_LASTWEIGHT = 0;
$TotalISSUE = 0;
$status = '1';
if ($language == 'th') {
  $HptName = 'HptNameTH';
  $FacName = 'FacNameTH';
} else {
  $HptName = 'HptName';
  $FacName = 'FacName';
}


// header

if ($chk == 'one') {
    if ($format == 1) {
        list($year, $mouth, $day) = explode("-", $date1);
        $datetime = new DatetimeTH();
        if ($language == 'th') {
            $year = $year + 543;
            $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
        } else {
            $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
        }
    } elseif ($format = 3) {
        if ($language == "th") {
            $date1 = $date1 + 543;
            $date_header = $array['year'][$language] . " " . $date1;
        } else {
            $date_header = $array['year'][$language] . $date1;
        }
    }
} elseif ($chk == 'between') {
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
    $datetime = new DatetimeTH();
    if ($language == 'th') {
        $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
    } else {
        $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
    }
} elseif ($chk == 'monthbetween') {
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
    for ($i = 0; $i < $count; $i++) {
        $date1 = $date[$i];
        list($y, $m, $d) = explode('-', $date1);
        if ($language ==  'th') {
            $y = $y + 543;
        }
        $date1 = $d . '-' . $m . '-' . $y;
        $DateShow[] = $date1;
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
}

if ($itemfromweb == '0') {
    $deletesheet = 0;
  // ==== SELCT รอบส่งผ้า
  if ($time_express == 'ALL') {
    $TimeName = 'ทุกรอบ';
    $where_time_express = '';
  } else {
    $timeex = "SELECT
                    time_sc.TimeName 
                  FROM
                    time_sc
                    LEFT JOIN time_express ON time_express.Time_ID = time_sc.ID 
                  WHERE
                    time_sc.ID =  '$time_express' ";
    $meQuery = mysqli_query($conn, $timeex);
    $Result  = mysqli_fetch_assoc($meQuery);
    $TimeName = $Result['TimeName'];
    $where_time_express = " AND sc.DeliveryTime = '$time_express' ";
  }

  foreach ($DepCode as $keyDep => $DepCodeloop) {

    $objPHPExcel->setActiveSheetIndex($keyDep);
    $objPHPExcel->getActiveSheet()->setCellValue('A8',  'CusName');
    $objPHPExcel->getActiveSheet()->setCellValue('B8',  'ItemName');
    $objPHPExcel->getActiveSheet()->setCellValue('C8',  'ParQty');
    $objPHPExcel->getActiveSheet()->setCellValue('D8',  'ItemWeight');
    $objPHPExcel->getActiveSheet()->setCellValue('E8',  'Price');

    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r29'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['department'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('A7', $date_header);
    $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
    $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
    $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');



    $query = "SELECT
                      department.DepName
                      FROM
                      department
                      WHERE
                      department.DepCode = '$DepCodeloop' AND HptCode = '$HptCode' ";
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $DepName = $Result["DepName"];
      $objPHPExcel->getActiveSheet()->setCellValue('A6', $DepName . ' ' . 'รอบส่งผ้า' . ' ' . $TimeName);
      $objPHPExcel->getActiveSheet()->setCellValue('A9', $DepName);
      $DepName = str_replace("/", " ", $DepName);
      $DepName = str_replace(":", " ", $DepName);
    }

    $itemName = [];
    $itemCode = [];
    $item = "SELECT
                item.ItemCode,
                item.ItemName 
            FROM
                shelfcount AS sc
                INNER JOIN shelfcount_detail ON sc.DocNo = shelfcount_detail.DocNo
                INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode 
                WHERE
                    ( sc.IsStatus = 3 OR sc.IsStatus = 4 ) 
                    $where
                    $where_time_express
                    AND sc.SiteCode = '$HptCode' 
                    AND sc.DepCode = '$DepCodeloop' 
                    AND shelfcount_detail.TotalQty != 0 
                    GROUP BY
                        item.ItemCode 
                    ORDER BY
                        item.ItemName ASC ";
    // echo "<pre>";
    // print_r($item);
    // echo "</pre>";          

    $meQuery = mysqli_query($conn, $item);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $itemName[] =  $Result["ItemName"];
      $itemCode[] =  $Result["ItemCode"];
    }

    // -----------------------------------------------------------------------------------
        $start_row = 9;
        $start_col = 5;
    // -----------------------------------------------------------------------------------

    for ($j = 0; $j < $count; $j++) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", $DateShow[$j]);
        $start_col++;
    }
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Qty');
    $start_col++;
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Weight ');
    // -----------------------------------------------------------------------------------

    $item2 = "SELECT
                      shelfcount_detail.ParQty AS  ParQty,
                      item.Weight AS Weight,
                      category_price.Price AS Price
                      FROM
                      shelfcount sc
                      INNER JOIN shelfcount_detail ON sc.DocNo = shelfcount_detail.DocNo
	                    INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
                      INNER JOIN department dpm ON dpm.DepCode = sc.DepCode
                      LEFT JOIN category_price ON category_price.CategoryCode = item.CategoryCode
                      WHERE
                       item.ItemCode IN (  ";
    foreach ($itemCode as $keyitemcode => $itemcodeloop) {
      $item2 .= "  '$itemcodeloop' ,";
    }
    $item2 = rtrim($item2, ' ,');
    $item2 .= " )  AND sc.DepCode = '$DepCodeloop'
                                      AND category_price.HptCode = '$HptCode'
                                      AND dpm.HptCode = '$HptCode'
                                      $where_time_express
                                      GROUP BY  item.ItemCode  ORDER BY item.ItemName ASC";


    foreach ($itemName as $keyitemName => $itemNameloop) {
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $itemNameloop);
      $start_row++;
    }


    $start_row = 9;
    $meQuery = mysqli_query($conn, $item2);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
      $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["Weight"]);
      $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["Price"]);
      $start_row++;
      $Weight[] = $Result["Weight"];
    }
    $start_row = 9;
    $r = 5;
    $w = 0;
    foreach ($itemCode as $keyitemcode => $itemcodeloop) {

        foreach ($date as $key => $val) {
          $Weight[$key] = 0;
          $ISSUEx[$key] = 0;
          $Date_chk[$key] = 0;
        }
    
    
    
        $cnt = 0;
        $data = "SELECT  COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
                                      COALESCE(sum(report_sc.Weight),'0') as  Weight ,
                                      report_sc.DocDate AS Date_chk
                        FROM report_sc 
                        INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
                        INNER JOIN (SELECT DeliveryTime,isStatus,DocNo FROM shelfcount) AS sc ON sc.DocNo = report_sc.DocNo 
                        WHERE  DATE(report_sc.DocDate) IN (";
        foreach ($date as $key => $val) {
          $data .= " '$val' ,";
        }
        $data = rtrim($data, ' ,');
        $data .= " )    AND ( sc.IsStatus = 3 OR sc.IsStatus = 4 ) 
                        AND dpm.HptCode = '$HptCode' 
                        AND report_sc.DepCode = '$DepCodeloop'  
                        AND report_sc.itemcode = '$itemcodeloop' 
                        $where_time_express
                        GROUP BY DATE(report_sc.DocDate) ";

    
        $meQuery = mysqli_query($conn, $data);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $Weight[$cnt] =  $Result["Weight"];
          $ISSUEx[$cnt] =  $Result["ISSUE"];
          $Date_chk[$cnt] =  $Result["Date_chk"];
          $cnt++;
        }
  
    
        $ISSUE = 0;
        $TOTAL_WEIGHT = 0;
        $x = 0;
        foreach ($date as $key => $val) {
          if ($Date_chk[$x]  == $val) {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUEx[$x]);
            $r++;
            $ISSUE += $ISSUEx[$x];
            $TOTAL_WEIGHT +=  $Weight[$x];
            $x++;
          } else {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
            $r++;
          }
        }
    
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_WEIGHT);
        $ISSUE = 0;
        $TOTAL_WEIGHT = 0;
        $r = 5;
        $w++;
        $start_row++;
    
    
    }

    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[0] . $start_row, $DepName);
    $r = 5;

    foreach ($date as $key => $val) {
        $Weight[$key] = 0;
        $ISSUEx[$key] = 0;
        $Date_chk[$key] = 0;
      }
      $cnt = 0;

      $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
                        COALESCE(SUM(report_sc.Weight),'0') as  Weight,
                         report_sc.DocDate AS Date_chk
                FROM report_sc 
                INNER JOIN (SELECT DeliveryTime,isStatus,DocNo FROM shelfcount) AS sc ON sc.DocNo = report_sc.DocNo 
                WHERE  DATE(report_sc.DocDate) IN (";
      foreach ($date as $key => $val) {
        $data .= " '$val' ,";
      }
      $data = rtrim($data, ' ,');
      $data .= " )  AND report_sc.DepCode = '$DepCodeloop'  
                              AND report_sc.IsStatus <> 9
                              AND report_sc.IsStatus <> 1
                              $where_time_express
                              AND report_sc.IsStatus <> 0
                              GROUP BY DATE(report_sc.DocDate) ";
  
  
      $meQuery = mysqli_query($conn, $data);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $Weight[$cnt] =  $Result["Weight"];
        $ISSUEx[$cnt] =  $Result["ISSUE"];
        $Date_chk[$cnt] =  $Result["Date_chk"];
        $cnt++;
      }

      $TotalISSUE = 0;
      $TOTAL_LASTWEIGHT = 0;
      $x = 0;
      foreach ($date as $key => $val) {
        if ($Date_chk[$x]  == $val) {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUEx[$x]);
          $r++;
          $TotalISSUE += $ISSUEx[$x];
          $TOTAL_LASTWEIGHT +=  $Weight[$x];
          $x++;
        } else {
          $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
          $r++;
        }
      }
  
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalISSUE);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_LASTWEIGHT);
      $TotalISSUE = 0;
      $TOTAL_LASTWEIGHT = 0;



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

      $HEAD = array(
        'alignment' => array(
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
          'size'  => 16,
          'name'  => 'THSarabun'
        )
      );


      $colorfill = array(
        'fill' => array(
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => array('rgb' => 'B9E3E6')
        )
      );



      $r1 = $r - 1;
      $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle("A5:A7")->applyFromArray($HEAD);
      $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle("D9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->applyFromArray($fill)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);


      $r2 = $r + 2;
      $countcell = sizeof($date_cell1);
      for ($i = 0; $i < $r2; $i++) {
  
        if ($i == 4) {
          $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
            ->setAutoSize(false);
        } else {
          $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
            ->setAutoSize(true);
        }
      }






      $start_row = 9;
      $start_col = 5;
      $itemName = [];
      $itemCode = [];
    $objPHPExcel->getActiveSheet()->setTitle($DepCodeloop);
    $objPHPExcel->createSheet();
    $deletesheet++;
    $ISSUE = 0;
    $TOTAL_LASTWEIGHT = 0;
    $TotalISSUE = 0;
  }

  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->removeSheetByIndex($deletesheet);
  
}else if ($itemfromweb <> '0') {

    $deletesheet = 0;
    if ($time_express == 'ALL') {
        $TimeName = 'ทุกรอบ';
        $where_time_express = '';
      } else {
        $timeex = "SELECT
                        time_sc.TimeName 
                      FROM
                        time_sc
                        LEFT JOIN time_express ON time_express.Time_ID = time_sc.ID 
                      WHERE
                        time_sc.ID =  '$time_express' ";
        $meQuery = mysqli_query($conn, $timeex);
        $Result  = mysqli_fetch_assoc($meQuery);
        $TimeName = $Result['TimeName'];
        $where_time_express = " AND sc.DeliveryTime = '$time_express' ";
      }


        // SELECT แผนก
  if ($DepCode[0] <> 0) {
    $wheredep = "AND report_sc.DepCode = '$DepCode[0]' ";
  } else {
    $wheredep = "";
  }
  $DepCode = [];
  $Sql = "  SELECT   department.DepName ,department.DepCode
                     FROM      report_sc  
                    INNER JOIN department ON department.DepCode = report_sc.DepCode  
                    $where
                    AND department.HptCode = '$HptCode' 
                    AND report_sc.isStatus <> 9
                    AND report_sc.itemCode = '$itemfromweb'
                    $wheredep
                    GROUP BY department.DepCode 
                    ORDER BY department.DepName ASC";
  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DepCode[] = $Result['DepCode'];
    $DepName[] = $Result['DepName'];
  }




  $objPHPExcel->setActiveSheetIndex(0);
  $objPHPExcel->getActiveSheet()->setCellValue('A8',  'CusName');
  $objPHPExcel->getActiveSheet()->setCellValue('B8',  'ItemName');
  $objPHPExcel->getActiveSheet()->setCellValue('C8',  'ParQty');
  $objPHPExcel->getActiveSheet()->setCellValue('D8',  'ItemWeight');
  $objPHPExcel->getActiveSheet()->setCellValue('E8',  'Price');
  
  $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r29'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['department'][$language]);
  $objPHPExcel->getActiveSheet()->setCellValue('A7', $date_header);
  $objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
  $objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
  $objPHPExcel->getActiveSheet()->mergeCells('A7:J7');



  $countDep = sizeof($DepCode);
  $start_row = 9;
  $start_col = 5;


 // -----------------------------------------------------------------------------------

    for ($j = 0; $j < $count; $j++) {
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", $DateShow[$j]);
        $start_col++;
    }
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Qty');
        $start_col++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$start_col] . "8", 'Total Weight ');
    // -----------------------------------------------------------------------------------
        for ($i = 0; $i < $countDep; $i++) {
            $item = "SELECT
                            report_sc.ParQty AS  ParQty,
                            report_sc.WeightPerQty AS Weight ,
                            category_price.Price AS Price,
                            department.DepName AS DepName,
                            item.itemname AS itemname 
                            FROM
                            report_sc
                            INNER JOIN item ON item.ItemCode = report_sc.ItemCode
                            INNER JOIN department ON department.DepCode = report_sc.DepCode  
                            LEFT JOIN  category_price ON category_price.CategoryCode = report_sc.CategoryCode
                            INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo 
                                            WHERE
                                            item.itemcode = '$itemfromweb'
                                            AND department.HptCode = '$HptCode' 
                                            AND report_sc.DepCode = '$DepCode[$i]'
                                            AND report_sc.isStatus <> 9
                                            AND report_sc.isStatus <> 1
                                            $where_time_express
                                            GROUP BY  report_sc.itemcode ";
            // echo "<pre>";
            // ECHO $item;
            // echo "</pre>";
            $meQuery = mysqli_query($conn, $item);
            while ($Result = mysqli_fetch_assoc($meQuery)) {
            $objPHPExcel->getActiveSheet()->setCellValue('A6', $Result["itemname"] . ' ' . 'รอบส่งผ้า' . ' ' . $TimeName);
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $Result["DepName"]);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["itemname"]);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["Weight"]);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["Price"]);
            $start_row++;
            $iname = $Result["itemname"];
            }
        }
        $start_row = 9;
        $r = 5;
        $w = 0;
        for ($q = 0; $q < $countDep; $q++) {
            $cnt = 0;
            for ($dayx = 0; $dayx < $count; $dayx++) {
            $Weight[$dayx] = 0;
            $ISSUEx[$dayx] = 0;
            $Date_chk[$dayx] = 0;
            }
            $data = "SELECT  COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
                                            COALESCE(sum(report_sc.Weight),'0') as  Weight ,
                                            report_sc.DocDate AS Date_chk
                            FROM report_sc 
                            INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
                            INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo 
                            WHERE  DATE(report_sc.DocDate) IN (";
            for ($day = 0; $day < $count; $day++) {
        
            $data .= " '$date[$day]' ,";
            }
            $data = rtrim($data, ' ,');
            $data .= " )  AND report_sc.isStatus <> 9
                            AND report_sc.isStatus <> 0
                            AND report_sc.isStatus <> 1
                            AND dpm.HptCode = '$HptCode' 
                            AND report_sc.DepCode = '$DepCode[$q]'  
                            AND report_sc.itemcode = '$itemfromweb'
                            $where_time_express
                            GROUP BY DATE(report_sc.DocDate) ";
        
            $meQuery = mysqli_query($conn, $data);
            while ($Result = mysqli_fetch_assoc($meQuery)) {
            $Weight[$cnt] =  $Result["Weight"];
            $ISSUEx[$cnt] =  $Result["ISSUE"];
            $Date_chk[$cnt] =  $Result["Date_chk"];
            $cnt++;
            }
        
            $ISSUE = 0;
            $TOTAL_WEIGHT = 0;
            $x = 0;
            foreach ($date as $key => $val) {
            if ($Date_chk[$x]  == $val) {
                $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Weight[$x]);
                $r++;
                $ISSUE += $ISSUEx[$x];
                $TOTAL_WEIGHT +=  $Weight[$x];
                $x++;
            } else {
                $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, 0);
                $r++;
            }
            }
        
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ISSUE);
            $r++;
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TOTAL_WEIGHT);
            $TOTAL_LASTWEIGHT += $TOTAL_WEIGHT;
            $ISSUE = 0;
            $TOTAL_WEIGHT = 0;
            $r = 5;
            $w++;
            $start_row++;
        }
        
        $r = 5;
        for ($day = 0; $day < $count; $day++) {
            $data = "SELECT COALESCE(SUM(report_sc.TotalQty),'0') as  ISSUE, 
            COALESCE(sum(report_sc.Weight),'0') as  Weight
            FROM report_sc 
            INNER JOIN department dpm ON dpm.DepCode = report_sc.DepCode
            INNER JOIN shelfcount sc ON sc.DocNo = report_sc.DocNo 
            WHERE  DATE(report_sc.DocDate)  ='$date[$day]'  
            AND report_sc.isStatus <> 9
            AND report_sc.isStatus <> 1
            AND dpm.HptCode = '$HptCode' 
            AND report_sc.isStatus <> 0
            $where_time_express
            $wheredep
            AND report_sc.itemcode = '$itemfromweb'
            ";
            $meQuery = mysqli_query($conn, $data);
            while ($Result = mysqli_fetch_assoc($meQuery)) {
            $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Weight"]);
            $r++;
            $TotalISSUE += $Result["ISSUE"];
            }
        }
    

        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $TotalISSUE);
        $r++;
        $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row,  $TOTAL_LASTWEIGHT);
        


        
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

      $HEAD = array(
        'alignment' => array(
          'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
          'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
        ),
        'font'  => array(
          'size'  => 16,
          'name'  => 'THSarabun'
        )
      );


      $colorfill = array(
        'fill' => array(
          'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
          'startColor' => array('rgb' => 'B9E3E6')
        )
      );



      $r1 = $r - 1;
      $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
      $objPHPExcel->getActiveSheet()->getStyle("A5:A7")->applyFromArray($HEAD);
      $objPHPExcel->getActiveSheet()->getStyle("A8:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
      $objPHPExcel->getActiveSheet()->getStyle("D9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->applyFromArray($fill)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
      $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);


      $r2 = $r + 2;
      $countcell = sizeof($date_cell1);
      for ($i = 0; $i < $r2; $i++) {
  
        if ($i == 4) {
          $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
            ->setAutoSize(false);
        } else {
          $objPHPExcel->getActiveSheet()->getColumnDimension($date_cell1[$i])
            ->setAutoSize(true);
        }
      }

      $objPHPExcel->getActiveSheet()->setTitle('1212');
      $start_row = 9;
      $start_col = 5;
      $itemName = [];
      $itemCode = [];
      // Create a new worksheet, after the default sheet
      $objPHPExcel->createSheet();
      $deletesheet++;
      $ISSUE = 0;
      $TOTAL_LASTWEIGHT = 0;
      $TotalISSUE = 0;
      $objPHPExcel->removeSheetByIndex($deletesheet);

}

    // $objPHPExcel->setActiveSheetIndex(0);
    // $objPHPExcel->removeSheetByIndex($deletesheet);



$writer = new Xlsx($objPHPExcel);

// ชื่อไฟล์
$file_export = "Excel-" . date("dmY-Hs");
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = "Report_Summary_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";

header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
