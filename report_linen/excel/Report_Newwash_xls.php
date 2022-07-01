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
$status_group = 1;
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
    $HptName = 'HptNameTH';
    $FacName = 'FacNameTH';
} else {
    $HptName = 'HptName';
    $FacName = 'FacName';
}
// header

if ($chk == 'one') {
    if ($format == 1) {
      $where =   "WHERE DATE (newlinentable.Docdate) = DATE('$date1')";
      list($year, $mouth, $day) = explode("-", $date1);
      $datetime = new DatetimeTH();
      if ($language == 'th') {
        $year = $year + 543;
        $date_header = $array['date'][$language] . $day . " " . $datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $year;
      } else {
        $date_header = $array['date'][$language] . $day . " " . $datetime->getmonthFromnum($mouth) . " " . $year;
      }
    } elseif ($format = 3) {
      $where = "WHERE  year (newlinentable.DocDate) LIKE '%$date1%'";
      if ($language == "th") {
        $date1 = $date1 + 543;
        $date_header = $array['year'][$language] . " " . $date1;
      } else {
        $date_header = $array['year'][$language] . $date1;
      }
    }
  } elseif ($chk == 'between') {
    $where =   "WHERE newlinentable.Docdate BETWEEN '$date1' AND '$date2'";
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
    $where =   "WHERE month (newlinentable.Docdate) = " . $date1;
    $datetime = new DatetimeTH();
    if ($language == 'th') {
      $date_header = $array['month'][$language]  . " " . $datetime->getTHmonthFromnum($date1);
    } else {
      $date_header = $array['month'][$language] . " " . $datetime->getmonthFromnum($date1);
    }
  } elseif ($chk == 'monthbetween') {
    $where =   "WHERE DATE(newlinentable.DocDate) BETWEEN '$betweendate1' AND '$betweendate2'";
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

// -----------------------------------------------------------------------------------
$status_group = 1;
// -----------------------------------------------------------------------------------
$objPHPExcel->setActiveSheetIndex(0)
    ->setCellValue('A8',  $array2['factory'][$language])
    ->setCellValue('B8',  $array2['itemname'][$language]);
// Write data from MySQL result
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A4', $array2['r22'][$language]);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $date_header);
$objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'รายละเอียด');
$objPHPExcel->getActiveSheet()->mergeCells('A4:J4');
$objPHPExcel->getActiveSheet()->mergeCells('A5:J5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:J6');
$objPHPExcel->getActiveSheet()->mergeCells('A7:B7');
// -----------------------------------------------------------------------------------
$query = "  SELECT
              item.ItemCode,
              item.ItemName,
              factory.$FacName,
              site.$HptName
              FROM
              newlinentable
              INNER JOIN newlinentable_detail ON newlinentable.DocNo = newlinentable_detail.DocNo
              LEFT JOIN item ON newlinentable_detail.ItemCode = item.ItemCode
              INNER JOIN factory ON factory.FacCode = newlinentable.FacCode
              INNER JOIN site ON newlinentable.HptCode = newlinentable.HptCode
              $where
              AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0
              AND newlinentable.FacCode = '$FacCode'
              GROUP BY newlinentable_detail.ItemCode
            ";
$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {
    if ($status_group == 1) {
        $objPHPExcel->getActiveSheet()->setCellValue('A9', $Result[$FacName]);
        $objPHPExcel->getActiveSheet()->setCellValue('A5', $Result[$HptName]);
    }
    $i++;
    $ItemName[] =  $Result["ItemName"];
    $ItemCode[] =  $Result["ItemCode"];
    $status_group = 0;
}

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
$COUNT_item = SIZEOF($ItemName);
for ($q = 0; $q < $COUNT_item; $q++) {
    $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $ItemName[$lek]);
    $r++;
    for ($day = 0; $day < $count; $day++) {
        $data = "SELECT   COALESCE(SUM(newlinentable_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(newlinentable_detail.Weight),'0') AS Weight
                    FROM newlinentable_detail 
                    INNER JOIN newlinentable ON newlinentable.DocNo = newlinentable_detail.DocNo
                    INNER JOIN factory ON factory.Faccode = newlinentable.Faccode
                    INNER JOIN site ON site.HptCode = newlinentable.HptCode";
        if ($chk == 'one') {
            if ($format == 1) {
                $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
            } elseif ($format = 3) {
                list($year, $month) = explode('-', $date[$day]);
                $data .=   " WHERE  YEAR(newlinentable.DocDate)  ='$year'  AND MONTH(newlinentable.DocDate)  ='$month' AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
            }
        } elseif ($chk == 'between') {
            $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
        } elseif ($chk == 'month') {
            $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
        } elseif ($chk == 'monthbetween') {
            list($year, $month) = explode('-', $date[$day]);
            $data .=   " WHERE  YEAR(newlinentable.DocDate)  ='$year'  AND MONTH(newlinentable.DocDate)  ='$month' AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
        }

        $data .= "   AND newlinentable.Faccode = '$FacCode'
                 AND site.HptCode = '$HptCode' ";
        $data .= "  AND newlinentable_detail.ItemCode = '$ItemCode[$lek]' ";

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
for ($day = 0; $day < $count; $day++) {
    $data =       "SELECT
              COALESCE(SUM(newlinentable_detail.Qty),'0') AS Totalqty,
                      COALESCE(SUM(newlinentable_detail.Weight),'0') AS Weight
              FROM
              newlinentable_detail
              INNER JOIN newlinentable ON newlinentable.DocNo = newlinentable_detail.DocNo
                    INNER JOIN factory ON factory.Faccode = newlinentable.Faccode
                    INNER JOIN site ON site.HptCode = newlinentable.HptCode
                    LEFT JOIN item ON item.itemcode = newlinentable_detail.itemcode";

    if ($chk == 'one') {
        if ($format == 1) {
            $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
        } elseif ($format = 3) {
            list($year, $month) = explode('-', $date[$day]);
            $data .=   " WHERE  YEAR(newlinentable.DocDate)  ='$year'  AND MONTH(newlinentable.DocDate)  ='$month' AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
        }
    } elseif ($chk == 'between') {
        $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
    } elseif ($chk == 'month') {
        $data .=   " WHERE  DATE(newlinentable.DocDate)  ='$date[$day]'  AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
    } elseif ($chk == 'monthbetween') {
        list($year, $month) = explode('-', $date[$day]);
        $data .=   " WHERE  YEAR(newlinentable.DocDate)  ='$year'  AND MONTH(newlinentable.DocDate)  ='$month' AND newlinentable.isStatus <> 9 AND newlinentable.isStatus <> 0";
    }
    $data .= " AND newlinentable.Faccode = '$FacCode'
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
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayTotalqty);
$r++;
$objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $sumdayWeight);
// -----------------------------------------------------------------------------------


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

      'allborders' => array(

        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
      )
    )
  );
  $colorfill = array(
    'fill' => array(
      'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
      'startColor' => array('argb' => 'FF6600')
    )
  );

  $r1 = $r - 1;
  $objPHPExcel->getActiveSheet()->getStyle("A4:A6")->applyFromArray($A5);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . $start_row)->applyFromArray($fill);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . $date_cell1[$r] . "8")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A" . $start_row . ":" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle($date_cell1[$r1] . "9:" . $date_cell1[$r] . $start_row)->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("C9:" . $date_cell1[$r] . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  $objPHPExcel->getActiveSheet()->getStyle('A1:' . $date_cell1[$r] . $start_row)->getAlignment()->setIndent(1);
  // $objPHPExcel->getActiveSheet()->getColumnDimension("A:D")->setAutoSize(true);
  foreach (range('A', 'B') as $columnID) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($columnID)
      ->setAutoSize(true);
  }


$writer = new Xlsx($objPHPExcel);

// ชื่อไฟล์
$objPHPExcel->getActiveSheet()->setTitle($array2['r22']['en']);
$objPHPExcel->createSheet();
//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = $array2['r22']['en'] . "_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
//
$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
