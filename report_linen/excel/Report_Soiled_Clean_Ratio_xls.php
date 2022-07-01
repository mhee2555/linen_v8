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
$year1 = $data[2];
$year2 = $data[3];
$date1 = $data[4];
$date2 = $data[5];
$betweendate1 = $data[6];
$betweendate2 = $data[7];
$format = $data[8];
$DepCode = $data[9];
$chk = $data[10];

$where = '';
$i = 9;
$check = '';
$Qty = 0;
$Weight = 0;
$count = [];
$date = [];
$start_row = 10;
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


$Sql = "SELECT
        factory.$FacName,
        dirty.DocDate
        FROM
        dirty
        INNER JOIN factory ON factory.FacCode =dirty.FacCode
        INNER JOIN dirty_detail ON dirty.Docno =dirty_detail.Docno
        INNER JOIN department ON department.depcode =dirty_detail.depcode
        INNER JOIN site ON site.hptcode =department.hptcode
        WHERE factory.FacCode = '$FacCode' ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
  $DocDate = $Result['DocDate'];
  $facname = $Result[$FacName];
}
$datetime = new DatetimeTH();
if ($language == 'th') {
  $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
  $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
$objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r8'][$language]);
$objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
$objPHPExcel->getActiveSheet()->setCellValue('A6', $array2['factory'][$language] . " : " . $facname);
$objPHPExcel->getActiveSheet()->setCellValue('I6', $date_header);
$objPHPExcel->getActiveSheet()->mergeCells('A8:A9');
$objPHPExcel->getActiveSheet()->mergeCells('B8:E8');
$objPHPExcel->getActiveSheet()->mergeCells('F8:I8');
$objPHPExcel->setActiveSheetIndex(0)
  ->setCellValue('A8',  $array2['docdate'][$language])
  ->setCellValue('B8',  $array2['soild'][$language])
  ->setCellValue('F8',  $array2['clean1'][$language])

  ->setCellValue('B9',  $array2['dirty'][$language])
  ->setCellValue('C9',  $array2['repair_dirty'][$language])
  ->setCellValue('D9',  $array2['newlinen'][$language])
  ->setCellValue('E9',  $array2['Totaldirty'][$language])
  ->setCellValue('F9',  $array2['clean'][$language])
  ->setCellValue('G9',  $array2['receive_dirty'][$language])
  ->setCellValue('H9',  $array2['receive_newlinen'][$language])
  ->setCellValue('I9',  $array2['Totalclean'][$language]);
  $totalsum1 = 0;
  $totalsum2 = 0;
  $totalsum3 = 0;
  $totalsum4 = 0;
  $totalsum5 = 0;
  $totalsum6 = 0;
  $totalsum7 = 0;
  $totalsum8 = 0;
  if ($chk == 'one') {
    if ($format == 1) { // 1วัน
      $date = $date1;
      $count = sizeof($date);
      for ($i = 1; $i <= $count; $i++) {
        $query = "SELECT 
         DIRTY,
        repair_wash,
         NEWLINEN,
         CLEAN,
         CLEAN_repair_wash,
         CLEAN_NEWLINEN,
        a.DocDate,
        b.DocDate,
        c.DocDate,
        d.DocDate,
        e.DocDate,
        f.DocDate
        FROM (
        SELECT
        COALESCE(sum(dirty.Total),'0') AS DIRTY ,
        COALESCE(dirty.DocDate,0) AS DocDate
        FROM
        dirty
        WHERE DATE (dirty.Docdate) =  '$date'  AND dirty.faccode= '$FacCode' AND dirty.DocNo LIKE '%$HptCode%'
       AND dirty.isstatus <> 9
        )a,
        (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
        COALESCE(repair_wash.DocDate,0) AS DocDate
        FROM  repair_wash
        WHERE DATE (repair_wash.Docdate) = '$date'
        AND repair_wash.FacCode = '$FacCode'
        AND repair_wash.DocNo LIKE '%$HptCode%'
        AND repair_wash.isStatus<>9
        )b,
        (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
        COALESCE(newlinentable.DocDate,0) AS DocDate
        FROM newlinentable
        WHERE DATE (newlinentable.Docdate) = '$date' AND newlinentable.FacCode = '$FacCode' AND newlinentable.DocNo LIKE '%$HptCode%'
        AND newlinentable.isStatus<>9
        )c,
        (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
        COALESCE(clean.DocDate,0) AS DocDate
        FROM clean
        INNER JOIN department ON department.DepCode = clean.DepCode
        INNER JOIN site ON department.HptCode = site.HptCode
        WHERE DATE (clean.Docdate) = '$date' AND clean.IsStatus <>9 AND clean.FacCode = '$FacCode' 
        )d,
        (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
          COALESCE(return_wash.DocDate,0) AS DocDate
          FROM return_wash
          INNER JOIN department ON department.DepCode = return_wash.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
        WHERE DATE (return_wash.Docdate) = '$date' AND return_wash.FacCode = '$FacCode' AND return_wash.DocNo LIKE '%$HptCode%'
        AND return_wash.IsStatus  <> 9
        )e,
      (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
      COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      INNER JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
      INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (clean.Docdate) = '$date'
      AND clean.FacCode = '$FacCode' AND clean.DocNo LIKE '%$HptCode%'AND clean.IsStatus  <> 9 )
      f";
        // echo $query;

        $meQuery = mysqli_query($conn, $query);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $docdate = $Result['DocDate'];
          $dirty = $Result['DIRTY'];
          $repair_wash = $Result['repair_wash'];
          $newlinen = $Result['NEWLINEN'];
          $clean = $Result['CLEAN'];
          $clean_repair_wash = $Result['CLEAN_repair_wash'];
          $clean_newlinen = $Result['CLEAN_NEWLINEN'];
          $total1 = 0;
          $total2 = 0;
          $r = 0;
          if (
            $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
          ) {
            list($year, $month, $day) = explode('-', $date1);
            if ($language == 'th') {
              $year = $year + 543;
              $date1 = $day . "-" . $month . "-" . $year;
            } else {
              $date1 = $day . "-" . $month . "-" . $year;
            }
            $total1 = $dirty + $repair_wash + $newlinen;
            $total2 = $clean + $clean_repair_wash + $clean_newlinen;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $date1);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $dirty);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $repair_wash);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $newlinen);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $total1);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $clean);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $clean_repair_wash);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $clean_newlinen);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $total2);
            $start_row++;
            $totalsum1 += $dirty;
            $totalsum2 += $repair_wash;
            $totalsum3 += $newlinen;
            $totalsum4 += $total1;
            $totalsum5 += $clean;
            $totalsum6 += $clean_repair_wash;
            $totalsum7 += $clean_newlinen;
            $totalsum8 += $total2;
          }
        }
      }
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total'][$language]);
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $totalsum1);
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $totalsum2);
      $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $totalsum3);
      $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $totalsum4);
      $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $totalsum5);
      $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalsum6);
      $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $totalsum7);
      $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalsum8);
      $S2 = $start_row + 2;
      $scr = (($totalsum4 - $totalsum8) / $totalsum4) * 100;
      $objPHPExcel->getActiveSheet()->mergeCells('A' . $S2 . ':E' . $S2);
      $objPHPExcel->getActiveSheet()->mergeCells('F' . $S2 . ':I' . $S2);
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $S2, 'SCR (Soiled-Clean Ratio)');
      $objPHPExcel->getActiveSheet()->setCellValue('F' . $S2, abs($scr));
    } else if ($format = 3) {
      if ($language == 'th') {
        $date1 = $date1 - 543;
      }
      $year = $date1;
      $monthh = 12;
      for ($i = 1; $i <= $monthh; $i++) {
        $day = 1;
        $count = cal_days_in_month(CAL_GREGORIAN, $i, $year);
        $datequery =  $year . '-' . $i . '-';
        $dateshow = '-' . $i . '-' . $year;
        for ($j = 0; $j < $count; $j++) {
          $date[] = $datequery . $day;
          $DateShow[] = $day . $dateshow;
          $day++;
        }
      }
      $count = sizeof($date);
      for ($i = 0; $i < $count; $i++) {
        $query = "SELECT 
         DIRTY,
        repair_wash,
         NEWLINEN,
         CLEAN,
         CLEAN_repair_wash,
         CLEAN_NEWLINEN,
        a.DocDate,
        b.DocDate,
        c.DocDate,
        d.DocDate,
        e.DocDate,
        f.DocDate
        FROM (
        SELECT
         COALESCE(sum(dirty.Total),'0') AS DIRTY ,
        COALESCE(dirty.DocDate,0) AS DocDate
        FROM
        dirty
        WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.DocNo LIKE '%$HptCode%'
        AND dirty.isstatus <> 9
        )a,
        (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
        COALESCE(repair_wash.DocDate,0) AS DocDate
        FROM  repair_wash
        
        WHERE DATE (repair_wash.Docdate) = '$date[$i]'
        AND repair_wash.FacCode = '$FacCode' AND repair_wash.DocNo LIKE '%$HptCode%'
        AND repair_wash.isStatus<>9
        )b,
        (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
        COALESCE(newlinentable.DocDate,0) AS DocDate
        FROM newlinentable
        WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.DocNo LIKE '%$HptCode%'
        AND newlinentable.isStatus<>9
        )c,
        (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
        COALESCE(clean.DocDate,0) AS DocDate
        FROM clean
        INNER JOIN department ON department.DepCode = clean.DepCode
            INNER JOIN site ON department.HptCode = site.HptCode
        WHERE DATE (clean.Docdate) = '$date[$i]' AND clean.IsStatus <>9 AND clean.DocNo= LIKE '%$HptCode%' AND clean.FacCode = '$FacCode'
        )d,
        (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
            COALESCE(return_wash.DocDate,0) AS DocDate
            FROM return_wash
            INNER JOIN department ON department.DepCode = return_wash.DepCode
            INNER JOIN site ON department.HptCode = site.HptCode
        WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND return_wash.DocNo= LIKE '%$HptCode%'
        AND return_wash.IsStatus  <> 9
        )e,
        (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
        COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      INNER JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
      INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
        WHERE DATE (clean.Docdate) = '$date[$i]'
        AND newlinentable.FacCode = '$FacCode' AND clean.DocNo= LIKE '%$HptCode%'
        AND clean.IsStatus  <> 9 )
        f";

        $meQuery = mysqli_query($conn, $query);
        while ($Result = mysqli_fetch_assoc($meQuery)) {
          $docdate = $Result['DocDate'];
          $dirty = $Result['DIRTY'];
          $repair_wash = $Result['repair_wash'];
          $newlinen = $Result['NEWLINEN'];
          $clean = $Result['CLEAN'];
          $clean_repair_wash = $Result['CLEAN_repair_wash'];
          $clean_newlinen = $Result['CLEAN_NEWLINEN'];
          $total1 = 0;
          $total2 = 0;
          $r = 0;
          if (
            $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
          ) {
            list($day, $month, $year) = explode('-', $DateShow[$i]);
            if ($language == 'th') {
              $year = $year + 543;
              $datesh = $day . "-" . $month . "-" . $year;
            } else {
              $datesh = $day . "-" . $month . "-" . $year;
            }
            $total1 = $dirty + $repair_wash + $newlinen;
            $total2 = $clean + $clean_repair_wash + $clean_newlinen;
            $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $datesh);
            $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $dirty);
            $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $repair_wash);
            $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $newlinen);
            $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $total1);
            $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $clean);
            $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $clean_repair_wash);
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $clean_newlinen);
            $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $total2);
            $start_row++;
            $totalsum1 += $dirty;
            $totalsum2 += $repair_wash;
            $totalsum3 += $newlinen;
            $totalsum4 += $total1;
            $totalsum5 += $clean;
            $totalsum6 += $clean_repair_wash;
            $totalsum7 += $clean_newlinen;
            $totalsum8 += $total2;
          }
        }
      }
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total'][$language]);
      $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $totalsum1);
      $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $totalsum2);
      $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $totalsum3);
      $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $totalsum4);
      $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $totalsum5);
      $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalsum6);
      $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $totalsum7);
      $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalsum8);
      $S2 = $start_row + 2;
      $scr = (($totalsum4 - $totalsum8) / $totalsum4) * 100;
      $objPHPExcel->getActiveSheet()->mergeCells('A' . $S2 . ':E' . $S2);
      $objPHPExcel->getActiveSheet()->mergeCells('F' . $S2 . ':I' . $S2);
      $objPHPExcel->getActiveSheet()->setCellValue('A' . $S2, 'SCR (Soiled-Clean Ratio)');
      $objPHPExcel->getActiveSheet()->setCellValue('F' . $S2, abs($scr));
    }
  } elseif ($chk == 'between') {
    $begin = new DateTime( $date1 );
    $end = new DateTime( $date2 );
    $end = $end->modify( '1 day' );
  
    $interval = new DateInterval('P1D');
    $period = new DatePeriod($begin, $interval ,$end);
    foreach ($period as $key => $value) {
      $date[] = $value->format('Y-m-d');
      $dateshow[] = $value->format('d-m-Y');
    }
  
    $count = sizeof($date);
  
    
    for ($i = 0; $i < $count; $i++) {
      $query = "SELECT 
      DIRTY,
      repair_wash,
      NEWLINEN,
      CLEAN,
      CLEAN_repair_wash,
      CLEAN_NEWLINEN,
      a.DocDate,
      b.DocDate,
      c.DocDate,
      d.DocDate,
      e.DocDate,
      f.DocDate
      FROM (
      SELECT
      COALESCE(sum(dirty.Total),'0') AS DIRTY ,
      COALESCE(dirty.DocDate,0) AS DocDate
      FROM
      dirty
      WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.DocNo LIKE '%$HptCode%'
      AND dirty.isstatus <> 9
      )a,
      (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
      COALESCE(repair_wash.DocDate,0) AS DocDate
      FROM  repair_wash
      WHERE DATE (repair_wash.Docdate) = '$date[$i]'
      AND repair_wash.FacCode = '$FacCode' AND repair_wash.DocNo LIKE '%$HptCode%'
      AND repair_wash.isStatus<>9
      )b,
      (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
      COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM newlinentable
      WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.DocNo LIKE '%$HptCode%'
      AND newlinentable.isStatus<>9
      )c,
      (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
      COALESCE(clean.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN department ON department.DepCode = clean.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (clean.Docdate) = '$date[$i]' AND clean.IsStatus <>9 AND clean.DocNo LIKE '%$HptCode%' AND clean.FacCode = '$FacCode'
      )d,
      (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
      COALESCE(return_wash.DocDate,0) AS DocDate
      FROM return_wash
      INNER JOIN department ON department.DepCode = return_wash.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND return_wash.DocNo LIKE '%$HptCode%'
      AND return_wash.IsStatus  <> 9
      )e,
      (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
      COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      INNER JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
      INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
      WHERE DATE (clean.Docdate) = '$date[$i]'
      AND newlinentable.FacCode = '$FacCode' AND clean.DocNo LIKE '%$HptCode%'
      AND clean.IsStatus  <> 9)f";
  
      // echo "<pre>";
      // echo $query;
      // echo "</pre>";
      $meQuery = mysqli_query($conn, $query);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $docdate = $Result['DocDate'];
        $dirty = $Result['DIRTY'];
        $repair_wash = $Result['repair_wash'];
        $newlinen = $Result['NEWLINEN'];
        $clean = $Result['CLEAN'];
        $clean_repair_wash = $Result['CLEAN_repair_wash'];
        $clean_newlinen = $Result['CLEAN_NEWLINEN'];
        $total1 = 0;
        $total2 = 0;
        $r = 0;
        if ($dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0) {
  
          list($day, $month, $year) = explode('-', $dateshow[$i]);
          if ($language == 'th') {
            $year = $year + 543;
            $datesh = $day . "-" . $month . "-" . $year;
          } else {
            $datesh = $day . "-" . $month . "-" . $year;
          }
          $total1 = $dirty + $repair_wash + $newlinen;
          $total2 = $clean + $clean_repair_wash + $clean_newlinen;
          $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $datesh);
          $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $dirty);
          $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $total1);
          $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $clean);
          $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $clean_repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $clean_newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $total2);
          $start_row++;
          $totalsum1 += $dirty;
          $totalsum2 += $repair_wash;
          $totalsum3 += $newlinen;
          $totalsum4 += $total1;
          $totalsum5 += $clean;
          $totalsum6 += $clean_repair_wash;
          $totalsum7 += $clean_newlinen;
          $totalsum8 += $total2;
        }
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $totalsum1);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $totalsum2);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $totalsum3);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $totalsum4);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $totalsum5);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalsum6);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $totalsum7);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalsum8);
    $S2 = $start_row + 2;
    $scr = (($totalsum4 - $totalsum8) / $totalsum4) * 100;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $S2 . ':E' . $S2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $S2 . ':I' . $S2);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $S2, 'SCR (Soiled-Clean Ratio)');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $S2, abs($scr));
  } elseif ($chk == 'month') {
    // 1เดือน
    $day = 1;
    $count = cal_days_in_month(CAL_GREGORIAN, $date1, $year1);
    $datequery =  $year1 . '-' . $date1 . '-';
    $dateshow = '-' . $date1 . '-' . $year1;
    for ($i = 0; $i < $count; $i++) {
      $date[] = $datequery . $day;
      $DateShow[] = $day . $dateshow;
      $day++;
    }
    for ($i = 0; $i <= $count; $i++) {
      $query = "SELECT 
     DIRTY,
     repair_wash,
     NEWLINEN,
     CLEAN,
     CLEAN_repair_wash,
     CLEAN_NEWLINEN,
    a.DocDate,
    b.DocDate,
    c.DocDate,
    d.DocDate,
    e.DocDate,
    f.DocDate
    FROM (
    SELECT
     COALESCE(sum(dirty.Total),'0') AS DIRTY ,
    COALESCE(dirty.DocDate,0) AS DocDate
    FROM
    dirty
    WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.DocNo LIKE '%$HptCode%'
    AND dirty.isstatus <> 9
    )a,
    (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
    COALESCE(repair_wash.DocDate,0) AS DocDate
    FROM  repair_wash
    
    WHERE DATE (repair_wash.Docdate) = '$date[$i]' AND repair_wash.DocNo LIKE '%$HptCode%'
    AND repair_wash.FacCode = '$FacCode'
    AND repair_wash.isStatus<>9
    )b,
    (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM newlinentable
    WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.DocNo LIKE '%$HptCode%'
    AND newlinentable.isStatus<>9
    )c,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
    COALESCE(clean.DocDate,0) AS DocDate
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]' AND clean.IsStatus <>9 AND clean.DocNo LIKE '%$HptCode%' AND clean.FacCode = '$FacCode'
    )d,
    (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
    COALESCE(return_wash.DocDate,0) AS DocDate
    FROM return_wash
    INNER JOIN department ON department.DepCode = return_wash.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND return_wash.DocNo LIKE '%$HptCode%'
    AND return_wash.IsStatus  <> 9
    )e,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
    COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      INNER JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
      INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]'
    AND newlinentable.FacCode = '$FacCode' AND clean.DocNo LIKE '%$HptCode%'
    AND clean.IsStatus  <> 9)
    f";
  
      $meQuery = mysqli_query($conn, $query);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $docdate = $Result['DocDate'];
        $dirty = $Result['DIRTY'];
        $repair_wash = $Result['repair_wash'];
        $newlinen = $Result['NEWLINEN'];
        $clean = $Result['CLEAN'];
        $clean_repair_wash = $Result['CLEAN_repair_wash'];
        $clean_newlinen = $Result['CLEAN_NEWLINEN'];
        $total1 = 0;
        $total2 = 0;
        $r = 0;
        if (
          $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
        ) {
          list($day, $month, $year) = explode('-', $DateShow[$i]);
          if ($language == 'th') {
            $year = $year + 543;
            $datesh = $day . "-" . $month . "-" . $year;
          } else {
            $datesh = $day . "-" . $month . "-" . $year;
          }
  
          $total1 = $dirty + $repair_wash + $newlinen;
          $total2 = $clean + $clean_repair_wash + $clean_newlinen;
  
          $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $datesh);
          $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $dirty);
          $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $total1);
          $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $clean);
          $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $clean_repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $clean_newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $total2);
          $start_row++;
          $totalsum1 += $dirty;
          $totalsum2 += $repair_wash;
          $totalsum3 += $newlinen;
          $totalsum4 += $total1;
          $totalsum5 += $clean;
          $totalsum6 += $clean_repair_wash;
          $totalsum7 += $clean_newlinen;
          $totalsum8 += $total2;
        }
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $totalsum1);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $totalsum2);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $totalsum3);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $totalsum4);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $totalsum5);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalsum6);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $totalsum7);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalsum8);
    $S2 = $start_row + 2;
    $scr = (($totalsum4 - $totalsum8) / $totalsum4) * 100;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $S2 . ':E' . $S2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $S2 . ':I' . $S2);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $S2, 'SCR (Soiled-Clean Ratio)');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $S2, abs($scr));
  } elseif ($chk == 'monthbetween') {
    list($year, $month, $day) = explode('-', $betweendate2);
    if ($day <> 31) {
      $day = $day + 1;
    }
    $betweendate2 = $year . "-" . $month . "-" . $day;
    $period = new DatePeriod(
      new DateTime($betweendate1),
      new DateInterval('P1D'),
      new DateTime($betweendate2)
    );
    foreach ($period as $key => $value) {
      $date[] = $value->format('Y-m-d');
  
      $dateshow[] = $value->format('d-m-Y');
    }
    $date[] = $betweendate2;
    $count = sizeof($date);
    for ($i = 0; $i < $count; $i++) {
      $query = "SELECT 
     DIRTY,
    repair_wash,
     NEWLINEN,
     CLEAN,
     CLEAN_repair_wash,
     CLEAN_NEWLINEN,
    a.DocDate,
    b.DocDate,
    c.DocDate,
    d.DocDate,
    e.DocDate,
    f.DocDate
    FROM (
    SELECT
     COALESCE(sum(dirty.Total),'0') AS DIRTY ,
    COALESCE(dirty.DocDate,0) AS DocDate
    FROM
    dirty
    WHERE DATE (dirty.Docdate) = '$date[$i]' AND dirty.faccode= '$FacCode' AND dirty.DocNo LIKE '%$HptCode%'
   AND dirty.isstatus <> 9
    )a,
    (SELECT  COALESCE(sum(repair_wash.Total),'0') AS repair_wash,
    COALESCE(repair_wash.DocDate,0) AS DocDate
    FROM  repair_wash
    
    WHERE DATE (repair_wash.Docdate) = '$date[$i]'
    AND repair_wash.FacCode = '$FacCode' AND repair_wash.DocNo LIKE '%$HptCode%'
    AND repair_wash.isStatus<>9
    )b,
    (SELECT COALESCE(SUM(newlinentable.Total),'0') AS NEWLINEN ,
    COALESCE(newlinentable.DocDate,0) AS DocDate
    FROM newlinentable
    WHERE DATE (newlinentable.Docdate) = '$date[$i]' AND newlinentable.FacCode = '$FacCode' AND newlinentable.DocNo LIKE '%$HptCode%'
    AND newlinentable.isStatus<>9
    )c,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN , 
    COALESCE(clean.DocDate,0) AS DocDate
    FROM clean
    INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]' AND clean.IsStatus <>9 AND clean.DocNo LIKE '%$HptCode%' AND clean.FacCode = '$FacCode'
    )d,
    (SELECT  COALESCE(SUM(return_wash.Total),'0') AS CLEAN_repair_wash,
    COALESCE(return_wash.DocDate,0) AS DocDate
    FROM return_wash
    INNER JOIN department ON department.DepCode = return_wash.DepCode
      INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (return_wash.Docdate) = '$date[$i]' AND return_wash.FacCode = '$FacCode' AND return_wash.DocNo LIKE '%$HptCode%'
    AND return_wash.IsStatus  <> 9
    )e,
    (SELECT  COALESCE(SUM(clean.Total),'0') AS CLEAN_NEWLINEN,
    COALESCE(newlinentable.DocDate,0) AS DocDate
      FROM clean
      INNER JOIN clean_ref ON clean_ref.DocNo = clean.DocNo
      INNER JOIN newlinentable ON clean_ref.RefDocNo = newlinentable.DocNo
      INNER JOIN department ON department.DepCode = clean.DepCode
          INNER JOIN site ON department.HptCode = site.HptCode
    WHERE DATE (clean.Docdate) = '$date[$i]'
    AND newlinentable.FacCode = '$FacCode' AND clean.DocNo LIKE '%$HptCode%'
    AND clean.IsStatus  <> 9)
    f";
      $meQuery = mysqli_query($conn, $query);
      while ($Result = mysqli_fetch_assoc($meQuery)) {
        $docdate = $Result['DocDate'];
        $dirty = $Result['DIRTY'];
        $repair_wash = $Result['repair_wash'];
        $newlinen = $Result['NEWLINEN'];
        $clean = $Result['CLEAN'];
        $clean_repair_wash = $Result['CLEAN_repair_wash'];
        $clean_newlinen = $Result['CLEAN_NEWLINEN'];
        $total1 = 0;
        $total2 = 0;
        $r = 0;
        if (
          $dirty <> 0 ||  $repair_wash <> 0 || $newlinen <> 0 || $clean <> 0 ||  $clean_repair_wash <> 0 ||  $clean_newlinen <> 0
        ) {
          list($day, $month, $year) = explode('-', $dateshow[$i]);
          if ($language == 'th') {
            $year = $year + 543;
            $datesh = $day . "-" . $month . "-" . $year;
          } else {
            $datesh = $day . "-" . $month . "-" . $year;
          }
          $total1 = $dirty + $repair_wash + $newlinen;
          $total2 = $clean + $clean_repair_wash + $clean_newlinen;
          $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $datesh);
          $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $dirty);
          $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $total1);
          $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $clean);
          $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $clean_repair_wash);
          $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $clean_newlinen);
          $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $total2);
          $start_row++;
          $totalsum1 += $dirty;
          $totalsum2 += $repair_wash;
          $totalsum3 += $newlinen;
          $totalsum4 += $total1;
          $totalsum5 += $clean;
          $totalsum6 += $clean_repair_wash;
          $totalsum7 += $clean_newlinen;
          $totalsum8 += $total2;
        }
      }
    }
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $totalsum1);
    $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $totalsum2);
    $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $totalsum3);
    $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $totalsum4);
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $totalsum5);
    $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalsum6);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $totalsum7);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalsum8);
    $S2 = $start_row + 2;
    $scr = (($totalsum4 - $totalsum8) / $totalsum4) * 100;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $S2 . ':E' . $S2);
    $objPHPExcel->getActiveSheet()->mergeCells('F' . $S2 . ':I' . $S2);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $S2, 'SCR (Soiled-Clean Ratio)');
    $objPHPExcel->getActiveSheet()->setCellValue('F' . $S2, abs($scr));
  }
  
  $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
  $width = array(20, 15, 15, 15, 15, 15, 15, 15, 15);
  for ($j = 0; $j < count($cols); $j++) {
    $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
  }
  
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
$SUBHEAD = array(
    'alignment' => array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
        'size'  => 10,
        'name'  => 'THSarabun'
    )
);
$CENTER = array(
    'alignment' => array(
        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_BOTTOM,
        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
    ),
    'font'  => array(
        'size'  => 8,
        'name'  => 'THSarabun'
    )
);


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
  $objPHPExcel->getActiveSheet()->getStyle("A6:I6")->applyFromArray($SUBHEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($HEAD);
  $objPHPExcel->getActiveSheet()->getStyle("A8:I" . $start_row)->applyFromArray($styleArray);
  $objPHPExcel->getActiveSheet()->getStyle("A" . $S2 . ":I" . $S2)->applyFromArray($styleArray);
  
  $objPHPExcel->getActiveSheet()->getStyle("B10:I" . $S2)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
  $objPHPExcel->getActiveSheet()->getStyle("A8:I" . $S2)->applyFromArray($CENTER);
  $objPHPExcel->getActiveSheet()->getStyle("A8:I" . $S2)->getAlignment()->setIndent(1);

  
  
  // Rename worksheet
  $objPHPExcel->getActiveSheet()->setTitle('Report_Soiled_Clean_Ratio');
  
  // Set active sheet index to the first sheet, so Excel opens this as the first sheet
  $objPHPExcel->setActiveSheetIndex(0);
  
  
  //ตั้งชื่อไฟล์
  $time  = date("H:i:s");
  $date  = date("Y-m-d");
  list($h, $i, $s) = explode(":", $time);
  $file_export = "Report_Soiled_Clean_Ratio_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";


$writer = new Xlsx($objPHPExcel);



header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
