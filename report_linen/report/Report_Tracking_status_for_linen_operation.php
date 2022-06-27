<?php
require('fpdf.php');
require('connect.php');
require('Class.php');
header('Content-Type: text/html; charset=utf-8');
date_default_timezone_set("Asia/Bangkok");
session_start();
$data=$_SESSION['data_send'];
$HptCode=$data['HptCode'];
$FacCode=$data['FacCode'];
$date1=$data['date1'];
$date2=$data['date2'];
$chk=$data['chk'];
$year=$data['year'];
$depcode=$data['DepCode'];
$format=$data['Format'];
$where='';

//print_r($data);
if($chk == 'one'){
  if ($format == 1) {
    $where =   "WHERE DATE (shelfcount.Docdate) = DATE('$date1')";
    list($year,$mouth,$day) = explode("-", $date1);
    $datetime = new DatetimeTH();
    $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year);
  }
  elseif ($format = 3) {
      $where = "WHERE  year (shelfcount.DocDate) LIKE '%$date1%'";
      $date_header= "ประจำปี : $date1";
    }
}
elseif($chk == 'between'){
  $where =   "WHERE shelfcount.Docdate BETWEEN '$date1' AND '$date2'";
  list($year,$mouth,$day) = explode("-", $date1);
  list($year2,$mouth2,$day2) = explode("-", $date2);
  $datetime = new DatetimeTH();
  $date_header ="วันที่ ".$day." ".$datetime->getTHmonthFromnum($mouth) . " พ.ศ. " . $datetime->getTHyear($year)." ถึง ".
                "วันที่ ".$day2." ".$datetime->getTHmonthFromnum($mouth2) . " พ.ศ. " . $datetime->getTHyear($year2);

}
elseif($chk == 'month'){
    $where =   "WHERE month (shelfcount.Docdate) = ".$date1;
    $datetime = new DatetimeTH();
    $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1) ;

}
elseif ($chk == 'monthbetween') {
  $where =   "WHERE month(shelfcount.Docdate) BETWEEN $date1 AND $date2";
  $datetime = new DatetimeTH();
  $date_header ="ประจำเดือน : ".$datetime->getTHmonthFromnum($date1)." ถึง ".$datetime->getTHmonthFromnum($date2) ;
}

$language = $_GET['lang'];
if ($language == "en") {
  $language = "en";
} else {
  $language = "th";
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);

class PDF extends FPDF
{
  function Header()
  {
    $datetime = new DatetimeTH();
    $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
    $edate = $eDate[0] . " " . $datetime->getTHmonthFromnum($eDate[1]) . " พ.ศ. " . $datetime->getTHyear($eDate[2]);

    if ($this->page == 1) {
      // Move to the right
      $this->SetFont('THSarabun', '', 10);
      $this->Cell(190, 10, iconv("UTF-8", "TIS-620", "วันที่พิมพ์รายงาน " . $printdate), 0, 0, 'R');
      $this->Ln(5);
      // Title
      $this->SetFont('THSarabun', 'b', 14);
      $this->Cell(80);
      $this->Cell(30, 10, iconv("UTF-8", "TIS-620", "Tracking Status for linen operation Report "), 0, 0, 'C');

      $this->Ln(10);

    } else {
      // Line break
      $this->Ln(7);
    }
  }
  function setTable($pdf, $header, $data, $width, $numfield, $field)
  {
    $field = explode(",", $field);
    // Column widths
    $w = $width;
    // Header
    $this->SetFont('THSarabun', 'b', 14);
    for ($i = 0; $i < count($header); $i++)
      $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
    $this->Ln();

    // set Data Details
    $count = 0;
    $rows = 1;
    $totalsum1 = 0;
    $totalsum2 = 0;
    $this->SetFont('THSarabun', '', 12);
    if (is_array($data)) {
      foreach ($data as $data => $inner_array) {
        if ($rows > 22) {
          $count++;
          if ($count % 25 == 1) {
            $this->SetFont('THSarabun', 'b', 14);
            for ($i = 0; $i < count($header); $i++)
              $this->Cell($w[$i], 10, iconv("UTF-8", "TIS-620", $header[$i]), 1, 0, 'C');
            $this->Ln();
          }
        }
        $pdf->SetFont('THSarabun', '', 12);
        list($hours,$min,$secord)=explode(":",$inner_array[$field[2]]);
        list($hours2,$min2,$secord2)=explode(":",$inner_array[$field[3]]);
        list($hours3,$min3,$secord3)=explode(":",$inner_array[$field[4]]);
        $total_hours = abs($hours+$hours2+$hours3);
        $total_min = abs($min+$min2+$min3);
        if ($total_min/60>=1) {
          $total_hours+=$total_min/60;
          $total_min=$total_min%60;
        }
        $this->Cell($w[0], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[0]],8)), 1, 0, 'C');
        $this->Cell($w[1], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[1]],0,5)), 1, 0, 'C');
        $this->Cell($w[2], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[2]],0,5)), 1, 0, 'C');
        $this->Cell($w[3], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[3]],0,5)), 1, 0, 'C');
        $this->Cell($w[4], 10, iconv("UTF-8", "TIS-620", substr($inner_array[$field[4]],1,5)), 1, 0, 'C');
        $this->Cell($w[5], 10, iconv("UTF-8", "TIS-620", number_format($total_hours)." ชั่วโมง ".$total_min." นาที"), 1, 0, 'C');
        $this->Ln();

      }
    }



    if ($count % 25 >= 22) {
      $pdf->AddPage("P", "A4");
    }

    // Closing line
    $pdf->Cell(array_sum($w), 0, '', 'T');
  }


  // Page footer
  function Footer()
  {
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('THSarabun', 'i', 9);
    // Page number
    $this->Cell(0, 10, iconv("UTF-8", "TIS-620", '') . $this->PageNo() . '/{nb}', 0, 0, 'R');
  }
}

// *** Prepare Data Resource *** //
// Instanciation of inherited class
$pdf = new PDF();
$font = new Font($pdf);
$data = new Data();
$pdf->AddPage("P", "A4");

$Sql = "SELECT
department.DepName
FROM
shelfcount
INNER JOIN department on department.DepCode = shelfcount.DepCode
$where
AND department.DepCode = $depcode ";
$meQuery = mysqli_query($conn,$Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
        $DepName = $Result['DepName'];
}
$pdf->SetFont('THSarabun','b',11);
$pdf->Cell(1);
$pdf->Cell(165,10,iconv("UTF-8","TIS-620","หน่วยงาน : ".$DepName),0,0,'L');
$pdf->Cell(ุ60,10,iconv("UTF-8","TIS-620",$date_header),0,0,'R');
$pdf->Ln(10);

$query = "SELECT
shelfcount.DocDate,
shelfcount.CycleTime,
TIMEDIFF(shelfcount.ScStartTime,shelfcount.ScEndTime)AS SC ,
TIMEDIFF(shelfcount.PkEndTime,shelfcount.PkStartTime)AS PK ,
TIMEDIFF(shelfcount.DvStartTime,shelfcount.DvEndTime)AS DV
FROM
shelfcount
INNER JOIN department on department.DepCode = shelfcount.DepCode
$where
AND shelfcount.DepCode = $depcode";
// var_dump($query); die;
// Number of column
$numfield = 6;
// Field data (Must match with Query)
$field = "DocDate,CycleTime,SC,PK,DV,";
// Table header
$header = array('DATE','CycleTime','shelfcount_TIME','PACKING_TIME','DELIVERY TIME','TOTAL');
// width of column table
$width = array(20,34,34,34,34,34);
// Get Data and store in Result
$result = $data->getdata($conn,$query,$numfield,$field);
// Set Table
$pdf->SetFont('THSarabun','b',10);
$pdf->setTable($pdf,$header,$result,$width,$numfield,$field);
$pdf->Ln();

// Footer Table

$ddate = date('d_m_Y');
$pdf->Output('I', 'Report_shelfcount_' . $ddate . '.pdf');
