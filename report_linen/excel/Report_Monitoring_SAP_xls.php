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
$DepCode = $data[7];
$chk = $data[8];
$year1 = $data[9];
$year2 = $data[10];
$GroupCodeCome = $data[11];
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






$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', 'Monitoring_SAP');
$objPHPExcel->getActiveSheet()->setCellValue('A6', $date_header);
$objPHPExcel->getActiveSheet()->mergeCells('A5:L5');
$objPHPExcel->getActiveSheet()->mergeCells('A6:L6');
$objPHPExcel->getActiveSheet()->setCellValue('A7', 'SHIP_TO');
$objPHPExcel->getActiveSheet()->setCellValue('B7', 'CUSTOMER');
$objPHPExcel->getActiveSheet()->setCellValue('C7', 'CREATE_DATE');
$objPHPExcel->getActiveSheet()->setCellValue('D7', 'CONFIRM_DATE');
$objPHPExcel->getActiveSheet()->setCellValue('E7', 'DOCUMENT_NO');
$objPHPExcel->getActiveSheet()->setCellValue('F7', 'LABNUMBER');
$objPHPExcel->getActiveSheet()->setCellValue('G7', 'ITEM_CODE');
$objPHPExcel->getActiveSheet()->setCellValue('H7', 'ITEM_NAME');
$objPHPExcel->getActiveSheet()->setCellValue('I7', 'ISSUE_QTY');
$objPHPExcel->getActiveSheet()->setCellValue('J7', 'CATEGORY_PRICE');
$objPHPExcel->getActiveSheet()->setCellValue('K7', 'QUANTITY');
$objPHPExcel->getActiveSheet()->setCellValue('L7', 'AMOUNT');
$objPHPExcel->getActiveSheet()->setCellValue('M7', 'ISSAP');
$objPHPExcel->getActiveSheet()->setCellValue('N7', 'STATUS_DEPARTMENT');


// $sheet = $objPHPExcel->getActiveSheet()->getActiveSheet();


// $sheet->setCellValue('A1', 'Ship_To'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('B1', 'CUSTOMER'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('C1', 'CREATE_DATE'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('D1', 'CONFIRM_DATE'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('E1', 'DOCUMENT_NO'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('F1', 'LABNUMBER'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('G1', 'ITEM_CODE'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('H1', 'ITEM_NAME'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('I1', 'ISSUE_QTY'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('J1', 'CATEGORY_PRICE'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('K1', 'QUANTITY'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('L1', 'AMOUNT'); // กำหนดค่าใน cell B1
// $sheet->setCellValue('M1', 'ISSAP'); // กำหนดค่าใน cell A1
// $sheet->setCellValue('N1', 'STATUS_DEPARTMENT'); // กำหนดค่าใน cell B1




$query = "SELECT
            dp.Ship_To,
            CONCAT(dp.DepCode, '-', dp.DepName) AS CUSTOMER ,
            DATE(shelfcount.DocDate) AS CREATE_DATE ,
            DATE(shelfcount.complete_date) AS CONFIRM_DATE,
            shelfcount.DocNo,
            shelfcount.LabNumber,
            item.ItemCode,
            item.ItemName,
            sc_detail.TotalQty AS ISSUE,
            sc_detail.Weight AS QUANTITY,
            sc_detail.Price AS AMOUNT,
            item.isSAP AS isSAP,
            dp.isActive AS STATUS_DEPARTMENT ,
            (
            SELECT category_price.Price 
            FROM category_price
            WHERE item.CategoryCode = category_price.CategoryCode  
            AND category_price.HptCode = '$HptCode'
            ) Price
            FROM
            shelfcount
            INNER JOIN  (SELECT sc_detail.TotalQty , sc_detail.Weight , sc_detail.Price , sc_detail.DocNo , sc_detail.ItemCode FROM shelfcount_detail sc_detail ) AS sc_detail  ON shelfcount.DocNo = sc_detail.DocNo
            INNER JOIN  (SELECT dp.DepCode , dp.DepName , dp.Ship_To , dp.isActive , dp.HptCode FROM department dp) AS dp ON shelfcount.DepCode = dp.DepCode
            INNER JOIN  (SELECT item.ItemCode , item.ItemName , item.isSAP , item.CategoryCode  FROM item) AS item ON sc_detail.ItemCode = item.ItemCode
            WHERE  DATE(shelfcount.complete_date)  IN ( ";
                    for ($day = 0; $day < $count; $day++) {

                        $query .= " '$date[$day]' ,";

                    }
                    $query = rtrim($query, ' ,');  
            $query .= " )AND ( shelfcount.isStatus = 3  OR shelfcount.isStatus = 4 )
            AND sc_detail.TotalQty <> 0
            AND dp.HptCode = '$HptCode'
            AND shelfcount.SiteCode = '$HptCode'
            GROUP BY shelfcount.LabNumber,item.ItemCode
            ORDER BY shelfcount.DocNo ";

$r = 0;
$start_row = 8;

$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery)) {

    if($Result["STATUS_DEPARTMENT"] == 0){
        $Result["STATUS_DEPARTMENT"] = 'inactive';
      }else{
        $Result["STATUS_DEPARTMENT"] = 'active';
      }


      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Ship_To"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["CUSTOMER"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["CREATE_DATE"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["CONFIRM_DATE"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["DocNo"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["LabNumber"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ItemCode"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ItemName"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["ISSUE"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["Price"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["QUANTITY"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["AMOUNT"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["isSAP"]);
      $r++;
      $objPHPExcel->getActiveSheet()->setCellValue($date_cell1[$r] . $start_row, $Result["STATUS_DEPARTMENT"]);
      $r++;
      $start_row ++;
      $r = 0;
}





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



  $objPHPExcel->getActiveSheet()->getStyle("A5:A6")->applyFromArray($A5);
  $objPHPExcel->getActiveSheet()->getStyle("A7:N7")->applyFromArray($colorfill);
  $objPHPExcel->getActiveSheet()->getStyle("A7:" . "N" . $start_row)->applyFromArray($fill);
  $objPHPExcel->getActiveSheet()->getStyle("A7:N7")->applyFromArray($A7);
  $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
  $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
  $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(45);
  $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
  $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(20);
  $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
  $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
  $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
  $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);


$writer = new Xlsx($objPHPExcel);

// ชื่อไฟล์
$file_export = "Excel-" . date("dmY-Hs");


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
