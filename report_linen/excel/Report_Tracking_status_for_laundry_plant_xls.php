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
$date_query1 = $data[2];
$date_query2 = $data[3];
$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;
$start_data = 9;
$minus = '';
$time = '';
$secord = '';
$hours = '';
$min = '';
$secord = '';
if ($language == 'th')
{
  $HptName = 'HptNameTH';
  $FacName = 'FacNameTH';
}
else
{
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





$header = array($array2['docdate'][$language], $array2['receive_time'][$language], $array2['washing_time'][$language], $array2['packing_time'][$language], $array2['distribute_time'][$language], $array2['total'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('C8:D8');
$objPHPExcel->getActiveSheet()->mergeCells('E8:F8');
$objPHPExcel->getActiveSheet()->mergeCells('G8:H8');
$objPHPExcel->getActiveSheet()->mergeCells('I8:J8');
$objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
$objPHPExcel->getActiveSheet()->mergeCells('B8:B9');
$objPHPExcel->getActiveSheet()->mergeCells('K8:K9');
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A8',  $array2['docno'][$language])
  ->setCellValue('B8',  $array2['docdate'][$language])
  ->setCellValue('C8',  $array2['receive_time'][$language])
  ->setCellValue('E8',  $array2['washing_time'][$language])
  ->setCellValue('G8',  $array2['packing_time'][$language])
  ->setCellValue('I8',  $array2['distribute_time'][$language])
  ->setCellValue('K8',  $array2['total'][$language]);
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('C9',  $array2['start'][$language])
  ->setCellValue('D9',  $array2['finish'][$language])
  ->setCellValue('E9',  $array2['start'][$language])
  ->setCellValue('F9',  $array2['finish'][$language])
  ->setCellValue('G9',  $array2['start'][$language])
  ->setCellValue('H9',  $array2['finish'][$language])
  ->setCellValue('I9',  $array2['start'][$language])
  ->setCellValue('J9',  $array2['finish'][$language]);
// Write data from MySQL result
$Sql = "SELECT
          factory.$FacName
        FROM
          process
        INNER JOIN factory ON factory.FacCode = process.FacCode
        WHERE  process.FacCode = $FacCode ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery))
{
  $Facname = $Result[$FacName];
}
$datetime = new DatetimeTH();
if ($language == 'th')
{
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
}
else
{
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}



$objPHPExcel->getActiveSheet()->setCellValue('K1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r15'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:K5');
$objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['factory'][$language] . " : " . $Facname);
$objPHPExcel->getActiveSheet()->setCellValue('K7', $date_header);
$doc = array('dirty', 'repair_wash', 'newlinentable');
$j = 0;

for ($i = 0; $i < 3; $i++)
{
  if ($chk == 'one')
  {
    if ($format == 1)
    {
      $where =   "WHERE DATE (" . $doc[$i] . ".Docdate) = DATE('$date_query1')";
    }
    elseif ($format = 3)
    {
      $where = "WHERE  year (" . $doc[$i] . ".Docdate) LIKE '%$date_query1%'";
    }
  }
  elseif ($chk == 'between')
  {
    $where =   "WHERE " . $doc[$i] . ".Docdate BETWEEN '$date_query1' AND '$date_query2'";
  }
  elseif ($chk == 'month')
  {
    $where =   "WHERE month (" . $doc[$i] . ".Docdate) = " . $date_query1;
  }
  elseif ($chk == 'monthbetween')
  {
    $where =   "WHERE date(" . $doc[$i] . ".Docdate) BETWEEN '$betweendate1' AND '$betweendate2'";
  }

  $query = "SELECT
                TIME (process.WashStartTime) AS WashStartTime ,
                TIME (process.WashEndTime) AS WashEndTime,
                TIME (process.PackStartTime)AS PackStartTime,
                TIME (process.PackEndTime)AS PackEndTime,
                TIME (process.SendStartTime)AS SendStartTime,
                TIME (process.SendEndTime)AS SendEndTime,
                $doc[$i].FacCode,
                process.DocNo AS  DocNo1 ,
                TIME ($doc[$i].ReceiveDate)AS ReceiveDate1,
                DATE_FORMAT($doc[$i].DocDate,'%d/%m/%Y') AS Date1,
                TIME_FORMAT(TIMEDIFF($doc[$i].ReceiveDate, process.SendEndTime), '%H:%i') AS TIME ,
                TIME_FORMAT(TIMEDIFF(process.WashStartTime ,process.WashEndTime), '%H:%i') AS wash ,
                TIME_FORMAT(TIMEDIFF(process.PackStartTime ,process.PackEndTime), '%H:%i') AS pack ,
                TIME_FORMAT(TIMEDIFF(process.SendStartTime ,process.SendEndTime), '%H:%i') AS send 
              FROM
              process
              LEFT JOIN $doc[$i] ON process.DocNo = $doc[$i].DocNo
              $where 
              AND $FacCode in ($doc[$i].FacCode)
              AND process.isStatus <> 9 "; 

  $meQuery = mysqli_query($conn, $query);
  while ($Result = mysqli_fetch_assoc($meQuery)) 
  {
    $start_data++;
    if ($language == 'th')
    {
      $hour_show = " ชั่วโมง";
      $min_show = " นาที";
    }
    else
    {
      if ($total_hours <= 1)
      {
        $hour_show = " hour ";
        $min_show = " min ";
      }
      else
      {
        $hour_show = " hours ";
        $min_show = " mins ";
      }
    }
    $one = explode(":", $Result["wash"]);
    $two = explode(":", $Result["pack"]);
    $three = explode(":", $Result["send"]);
    $four = explode(":", $Result["TIME"]);
    $one[0] = str_replace("-", '', $one[0]);
    $two[0] = str_replace("-", '', $two[0]);
    $three[0] = str_replace("-", '', $three[0]);
    $four[0] = str_replace("-", '', $four[0]);
    $hous = $one[0] . $two[0] . $three[0] . $four[0];

    $one1 = isset($one[1])?$one[1]:0;
    $two1 = isset($two[1])?$two[1]:0;
    $three1 = isset($three[1])?$three[1]:0;
    $four1 = isset($four[1])?$four[1]:0;

    $mins = $one1 . $two1 . $three1. $four1 ;
    if ($mins >= 60)
    {
      $Ansmin = $mins / 60;
      $Ansmin = (int) ($Ansmin);
      $hous = $hous + $Ansmin;
      $mins = $mins % 60;
    }

    $timeshow = $hous . $hour_show . " " . $mins . $min_show;
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_data, $Result["DocNo1"]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_data, $Result["Date1"]);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_data, substr($Result["ReceiveDate1"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_data, substr($Result["WashStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_data, substr($Result["WashStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_data, substr($Result["WashEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_data, substr($Result["PackStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_data, substr($Result["PackEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_data, substr($Result["SendStartTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('J' . $start_data, substr($Result["SendEndTime"], 0, 5));
    $objPHPExcel->getActiveSheet()->setCellValue('K' . $start_data, $timeshow);
  }
}



$styleArray = array(

    'borders' => array(

      'allborders' => array(

        'style' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
      )
    )
  );
  $objPHPExcel->getActiveSheet()->getStyle('A8:K' . $start_data)->applyFromArray($styleArray);

  $CENTER = array(
    'alignment' => array(
      'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 8,
      'name'  => 'THSarabun'
    )
  );

  $objPHPExcel->getActiveSheet()->getStyle('A8:K' . $start_data)->applyFromArray($CENTER);

  $r = array(
    'alignment' => array(
      'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
      'size'  => 16,
      'name'  => 'THSarabun'
    )
  );


  $objPHPExcel->getActiveSheet()->getStyle('A5')->applyFromArray($r);




$cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');
$width = array(20, 20, 8, 8, 8, 8, 8, 8, 8, 8, 20);
for ($j = 0; $j < count($cols); $j++) {
  $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
}

$writer = new Xlsx($objPHPExcel);

$objPHPExcel->getActiveSheet()->setTitle('Report_Tracking_status');
$objPHPExcel->setActiveSheetIndex(0);


$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = "Report_Tracking_status_for_laundry_plant_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
