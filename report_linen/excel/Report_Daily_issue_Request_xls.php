<?php
// include composer autoload
include_once('connect.php');
require 'vendor/autoload.php';
require('../report/Class.php');



$language = "th";
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/report_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);


$docno = $_GET['Docno'];
$where = '';
$start_row = 14;
$check = '';
$Qty = 0;
$Weight = 0;
$count = 1;



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

$HptCodex = substr($docno, 2, 3);
$Sql = "SELECT
shelfcount.DocNo,
DATE(shelfcount.complete_date) AS DocDate,
TIME(shelfcount.complete_date) AS DocTime,
department.DepName,
time_sc.TimeName AS CycleTime,
site.HptName,
sc_time_2.TimeName AS TIME , 
time_sc.timename AS ENDTIME ,
site.HptCode,
shelfcount.isStatus
FROM
shelfcount
LEFT JOIN department ON shelfcount.DepCode = department.DepCode
LEFT JOIN site ON site.HptCode = department.HptCode
LEFT JOIN time_sc ON time_sc.id = shelfcount.DeliveryTime
LEFT JOIN sc_time_2 ON sc_time_2.id = shelfcount.ScTime
WHERE shelfcount.DocNo='$docno' AND shelfcount.isStatus<> 9 AND site.HptCode ='$HptCodex' ";
$meQuery = mysqli_query($conn, $Sql);
while ($Result = mysqli_fetch_assoc($meQuery)) {
    $DeptName = $Result['DepName'];
    $DocDate = $Result['DocDate'];
    $DocTime = $Result['DocTime'];
    $DocNo = $Result['DocNo'];
    $TIME = $Result['TIME'];
    $ENDTIME = $Result['ENDTIME'];
    $HptName = $Result['HptName'];
    $HptCode = $Result['HptCode'];
    $isStatus = $Result['isStatus'];
}
if ($isStatus == 1 || $isStatus == 0) {
    $Status = 'On Process';
} elseif ($isStatus == 3 || $isStatus == 4) {
    $Status = 'Complete';
}
list($year, $month, $day) = explode('-', $DocDate);
if ($language == 'th') {
    $year = $year + 543;
}
$datetime = new DatetimeTH();
$DocDate = $day . "-" . $month . "-" . $year;
if ($language == 'th') {
    $printdate = date('d') . " " . $datetime->getTHmonth(date('F')) . " พ.ศ. " . $datetime->getTHyear(date('Y'));
} else {
    $printdate = date('d') . " " . date('F') . " " . date('Y');
}
$queryy = "SELECT
site.money
FROM
site
WHERE site.HptCode = '$HptCode' ";
$meQuery = mysqli_query($conn, $queryy);
while ($Result = mysqli_fetch_assoc($meQuery)) {
    $money = $Result['money'];
}
$government = 0;
if ($money == 1) {
    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r4'][$language]);
    $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $array['docno'][$language] . " : " . $docno);
    $objPHPExcel->getActiveSheet()->setCellValue('G6',  'Status :  ' . $Status);
    $objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['hospital'][$language] . " : " . $HptName);
    $objPHPExcel->getActiveSheet()->setCellValue('A8', $array2['ward'][$language] . " : " . $DeptName);
    $objPHPExcel->getActiveSheet()->setCellValue('A9', $array2['date'][$language] . " : " . $DocDate);
    $objPHPExcel->getActiveSheet()->setCellValue('A10', $array2['shelfcounttime'][$language] . " : " . $TIME);
    $objPHPExcel->getActiveSheet()->setCellValue('A11', $array2['deliverytime'][$language] . " : " . $ENDTIME);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A13',  $array2['no'][$language])
        ->setCellValue('B13',  $array2['itemname'][$language])
        ->setCellValue('C13',  $array2['parqty'][$language])
        ->setCellValue('D13',  $array2['shelfcount1'][$language])
        ->setCellValue('E13',  $array2['max'][$language])
        ->setCellValue('F13',  $array2['issue'][$language])
        ->setCellValue('G13',  $array2['weight'][$language])
        ->setCellValue('H13',  $array2['price'][$language]);

    $query = "SELECT
    item.ItemName,
    item.weight,
    IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
    IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
    IFNULL(
      shelfcount_detail.TotalQty,
      0
    ) AS TotalQty,
    IFNULL(shelfcount_detail.Over, 0) AS OverPar,
    IFNULL(shelfcount_detail.Short, 0) AS Short,
    IFNULL(item.Weight, 0) AS Weight,
    category_price.Price,
    shelfcount_detail.Price as PriceSC,
    shelfcount.Totalw AS W ,
    shelfcount.Totalp AS P
    FROM
    shelfcount
    INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
    INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
    INNER JOIN category_price ON category_price.CategoryCode = item.CategoryCode
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE shelfcount.DocNo='$docno'
              AND shelfcount_detail.TotalQty <> 0
              AND shelfcount.isStatus<> 9
              AND category_price.HptCode = '$HptCode'
              AND department.HptCode ='$HptCodex' ";
    $totalprice = 0;
    $price_W = 0;
    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $issue = $Result['ParQty'] - $Result['CcQty'];
        $totalweight = $Result['TotalQty'] * $Result['Weight'];
        $price = $totalweight * $Result['Price'];
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $count);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["ItemName"]);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["CcQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $issue);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $Result["TotalQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $totalweight);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $Result['PriceSC']);
        $start_row++;
        $count++;
        $Weight += $totalweight;
        $totalprice += $price;
        $price_W += $Result['PriceSC'];
        $W = $Result['W'];
        $P = $Result['P'];
    }
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':G' . $start_row);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_weight'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $W);
    $start_row++;
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':G' . $start_row);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_price'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $price_W);



    $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H');
    $width = array(10, 10, 10, 10, 10, 10, 10, 10, 10, 10);
    for ($j = 0; $j < count($cols); $j++) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);




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



    $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($HEAD);
    $objPHPExcel->getActiveSheet()->getStyle("A6:A11")->applyFromArray($SUBHEAD);
    $objPHPExcel->getActiveSheet()->getStyle("A13:H" . $start_row)->applyFromArray($styleArray);

    $objPHPExcel->getActiveSheet()->getStyle("A13:J13")->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("A13:A" . $start_row)->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C14:J" . $start_row)->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C14:H" . $start_row);

    $objPHPExcel->getActiveSheet()->getStyle("G14:H" . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
}
if ($government == 1) {
    $objPHPExcel->getActiveSheet()->setCellValue('E1', $array2['printdate'][$language] . $printdate);
    $objPHPExcel->getActiveSheet()->setCellValue('A5', $array2['r4'][$language]);
    $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
    $objPHPExcel->getActiveSheet()->setCellValue('A6', $array['docno'][$language] . " : " . $docno);
    $objPHPExcel->getActiveSheet()->setCellValue('G6',  'Status :  ' . $Status);
    $objPHPExcel->getActiveSheet()->setCellValue('A7', $array2['hospital'][$language] . " : " . $HptName);
    $objPHPExcel->getActiveSheet()->setCellValue('A8', $array2['ward'][$language] . " : " . $DeptName);
    $objPHPExcel->getActiveSheet()->setCellValue('A9', $array2['date'][$language] . " : " . $DocDate);
    $objPHPExcel->getActiveSheet()->setCellValue('A10', $array2['shelfcounttime'][$language] . " : " . $TIME);
    $objPHPExcel->getActiveSheet()->setCellValue('A11', $array2['deliverytime'][$language] . " : " . $ENDTIME);
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A13',  $array2['no'][$language])
        ->setCellValue('B13',  $array2['itemname'][$language])
        ->setCellValue('C13',  $array2['parqty'][$language])
        ->setCellValue('D13',  $array2['shelfcount1'][$language])
        ->setCellValue('E13',  $array2['max'][$language])
        ->setCellValue('F13',  $array2['issue'][$language])
        ->setCellValue('G13',  $array2['short'][$language])
        ->setCellValue('H13',  $array2['over'][$language])
        ->setCellValue('I13',  $array2['weight'][$language]);

    $query = "SELECT
    item.ItemName,
    item.weight,
    IFNULL(shelfcount_detail.ParQty, 0) AS ParQty,
    IFNULL(shelfcount_detail.CcQty, 0) AS CcQty,
    IFNULL(
      shelfcount_detail.TotalQty,
      0
    ) AS TotalQty,
    IFNULL(shelfcount_detail.Over, 0) AS OverPar,
    IFNULL(shelfcount_detail.Short, 0) AS Short,
    IFNULL(item.Weight, 0) AS Weight,
    category_price.Price,
      shelfcount.Totalw AS W ,
   shelfcount.Totalp AS P
    FROM
    shelfcount
    INNER JOIN shelfcount_detail ON shelfcount.DocNo = shelfcount_detail.DocNo
    INNER JOIN item ON shelfcount_detail.ItemCode = item.ItemCode
    LEFT JOIN category_price ON category_price.CategoryCode = item.CategoryCode
    INNER JOIN department ON shelfcount.DepCode = department.DepCode
              WHERE shelfcount.DocNo='$docno'
              AND shelfcount_detail.TotalQty <> 0
              AND shelfcount.isStatus<> 9
              AND category_price.HptCode = '$HptCode'
    ";
    $issue = $Result['ParQty'] - $Result['CcQty'];
    $totalweight = $Result['TotalQty'] * $Result['Weight'];
    $price = $totalweight * $Result['Price'];

    $meQuery = mysqli_query($conn, $query);
    while ($Result = mysqli_fetch_assoc($meQuery)) {
        $issue = $Result['ParQty'] - $Result['CcQty'];
        $totalweight = $Result['TotalQty'] * $Result['Weight'];
        $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $count);
        $objPHPExcel->getActiveSheet()->setCellValue('B' . $start_row, $Result["ItemName"]);
        $objPHPExcel->getActiveSheet()->setCellValue('C' . $start_row, $Result["ParQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('D' . $start_row, $Result["CcQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('E' . $start_row, $issue);
        $objPHPExcel->getActiveSheet()->setCellValue('F' . $start_row, $Result["TotalQty"]);
        $objPHPExcel->getActiveSheet()->setCellValue('G' . $start_row, $Result["Short"]);
        $objPHPExcel->getActiveSheet()->setCellValue('H' . $start_row, $Result["OverPar"]);
        $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $totalweight);
        $start_row++;
        $count++;
        $Weight += $totalweight;
        $W = $Result['W'];
        $P = $Result['P'];
    }
    $objPHPExcel->getActiveSheet()->mergeCells('A' . $start_row . ':H' . $start_row);
    $objPHPExcel->getActiveSheet()->setCellValue('A' . $start_row, $array2['total_weight'][$language]);
    $objPHPExcel->getActiveSheet()->setCellValue('I' . $start_row, $W);



    $cols = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I');
    $width = array(10, 10, 10, 10, 10, 10, 10, 10, 10);
    for ($j = 0; $j < count($cols); $j++) {
        $objPHPExcel->getActiveSheet()->getColumnDimension($cols[$j])->setWidth($width[$j]);
    }
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);




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

    $objPHPExcel->getActiveSheet()->getStyle("A5")->applyFromArray($HEAD);
    $objPHPExcel->getActiveSheet()->getStyle("A6:A11")->applyFromArray($SUBHEAD);
    $objPHPExcel->getActiveSheet()->getStyle("A13:I" . $start_row)->applyFromArray($styleArray);

    $objPHPExcel->getActiveSheet()->getStyle("A13:I13")->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("A13:A" . $start_row)->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C14:I" . $start_row)->applyFromArray($CENTER);
    $objPHPExcel->getActiveSheet()->getStyle("C14:H" . $start_row)->getNumberFormat()->setFormatCode('#,##0');

    $objPHPExcel->getActiveSheet()->getStyle("I14:I" . $start_row)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);;
    // $objDrawing = new PHPExcel_Worksheet_Drawing();
    // $objDrawing->setName('test_img');
    // $objDrawing->setDescription('test_img');
    // $objDrawing->setPath('Nhealth_linen 4.0.png');
    // $objDrawing->setCoordinates('A1');
    // //setOffsetX works properly
    // $objDrawing->setOffsetX(0);
    // $objDrawing->setOffsetY(0);
    // //set width, height
    // $objDrawing->setWidthAndHeight(150, 75);
    // $objDrawing->setResizeProportional(true);
    // $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
}



$writer = new Xlsx($objPHPExcel);

// Rename worksheet
$objPHPExcel->getActiveSheet()->setTitle('Report_Daily_issue_Request');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$objPHPExcel->setActiveSheetIndex(0);


//ตั้งชื่อไฟล์
$time  = date("H:i:s");
$date  = date("Y-m-d");
list($h, $i, $s) = explode(":", $time);
$file_export = "Report_Daily_issue_Request_xls_" . $date . "_" . $h . "_" . $i . "_" . $s . ")";


header('Content-Type: application/vnd.openxmlformats-officedocument.objPHPExcelml.sheet');
header('Content-Disposition: attachment;filename="' . $file_export . '.xlsx"');
header("Content-Transfer-Encoding: binary ");

$writer->save('php://output');
exit();
