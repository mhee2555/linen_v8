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
$DepCode = $data[5];
$chk = '222';
$where = '';
$start_row = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;

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

$datetime = new DatetimeTH();
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



$x = 0;
if ($DepCode == 'ALL') {
  $Sql = "SELECT
    department.DepName,
    department.DepCode
  FROM
    par_item_stock
  INNER JOIN department ON par_item_stock.Depcode = department.Depcode
  WHERE par_item_stock.HptCode='$HptCode' 
  GROUP BY department.DepCode";
} else {
  $Sql = "SELECT
          department.DepName,
          department.DepCode
          FROM
          par_item_stock
          INNER JOIN department ON par_item_stock.Depcode=department.Depcode
          WHERE par_item_stock.DepCode='$DepCode' AND department.HptCode='$HptCode'
          GROUP BY department.DepCode ";
}
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $depname[$x] = $Result['DepName'];
  $DepCodeALL[$x] = $Result['DepCode'];
  $x++;
}

$sheet_count = sizeof($DepCodeALL);

for ($sheet = 0; $sheet < $sheet_count; $sheet++) {

  $objPHPExcel->setActiveSheetIndex($sheet)
    ->setCellValue('A8',  $array2['no'][$language])
    ->setCellValue('B8',  $array2['itemname'][$language])
    ->setCellValue('C8',  $array2['unit'][$language])
    ->setCellValue('D8',  $array2['par'][$language])
    ->setCellValue('E8',  $array2['order'][$language]);

  $objPHPExcel->getActiveSheet()->setCellValue('D1', $array2['printdate'][$language] . $printdate);
  $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r9'][$language]);
  $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
  $objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['department'][$language] . " : " . $depname[$sheet]);

  $Sql = "SELECT
        item.itemName,
        item_unit.unitname,
        par_item_stock.TotalQty,
        par_item_stock.ParQty
        FROM
        par_item_stock
        INNER JOIN department ON par_item_stock.Depcode=department.Depcode
        INNER JOIN item on item.ItemCode=par_item_stock.ItemCode
        INNER JOIN item_unit on item.unitcode=item_unit.unitcode
        WHERE par_item_stock.DepCode= '$DepCodeALL[$sheet]'
        AND department.HptCode='$HptCode'
        ORDER BY item.itemName ";

  $meQuery = mysqli_query($conn, $Sql);
  while ($Result = mysqli_fetch_assoc($meQuery)) {
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $count);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["itemName"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["unitname"]);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["ParQty"]);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $Result["TotalQty"]);
    $start_row++;
    $count++;
  }
  $cols = array('A', 'B', 'C', 'D', 'E');
  $width = array(10, 40, 10, 10, 15);
  for ($j = 0; $j < count($cols); $j++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
  }
  $start_row--;




  $styleArray = array(

    'borders' => array(

      'allborders' => array(

        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
      )
    )
  );
  $objPHPExcel->getActiveSheet()->getStyle('A8'  . ':E' . $start_row)->applyFromArray($styleArray);
  
  
  $CENTER = array(
    'alignment' => array(
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );


  $objPHPExcel->getActiveSheet()->getStyle('A8' . ':A' . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('A8'  . ':E' . $start_row)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle('C8'  . ':E' . $start_row)->applyFromArray($CENTER);

  $R8 = array(
    'alignment' => array(
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 18,
      'name'  => 'THSarabun'
    )
  );


  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($R8);


  $LEFT = array(
    'alignment' => array(
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
    ),
    'font'  => array(
      'size'  => 10,
      'name'  => 'THSarabun'
    )
  );


  $objPHPExcel->getActiveSheet()->getStyle('B9' . ':B' . $start_row)->applyFromArray($LEFT);




  // Rename worksheet
  $objPHPExcel->getActiveSheet()->setTitle($DepCodeALL[$sheet]);
  $objPHPExcel->createSheet();
  $start_row = 9;
  $count = 1;
}
// Set active sheet index to the first sheet, so Excel opens this as the first sheet

$objPHPExcel->removeSheetByIndex(
  $objPHPExcel->getIndex(
    $objPHPExcel->getSheetByName('Worksheet')
  )
);
$objPHPExcel->setActiveSheetIndex(0);

//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = "Report_Stock_Count_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";
//






$writer = new Xlsx($objPHPExcel);

// ชื่อไฟล์


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
