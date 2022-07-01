<?php
// include composer autoload
include_once('connect.php');
require 'vendor/autoload.php';
  
// import the PhpSpreadsheet Class
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
  
$spreadsheet = new Spreadsheet();
  
// การกำหนดค่า ข้อมูลเกี่ยวกับไฟล์ excel 
 $spreadsheet->getProperties()
    ->setCreator("Maarten Balliauw")
    ->setLastModifiedBy("Maarten Balliauw")
    ->setTitle("Office 2007 XLSX Test Document")
    ->setSubject("Office 2007 XLSX Test Document")
    ->setDescription(
        "Test document for Office 2007 XLSX, generated using PHP classes."
    )
    ->setKeywords("office 2007 openxml php")
    ->setCategory("Test result file");

    $cell1 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $cell2 = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $round_AZ1 = sizeof($cell1);
    $round_AZ2 = sizeof($cell2);
    for ($a = 0; $a < $round_AZ1; $a++) {
        for ($b = 0; $b < $round_AZ2; $b++) {
          array_push($cell1, $cell1[$a] . $cell2[$b]);
        }
      }


$sheet = $spreadsheet->getActiveSheet();






  
$sheet->setCellValue('A1', 'Ship_To'); // กำหนดค่าใน cell A1
$sheet->setCellValue('B1', 'CUSTOMER'); // กำหนดค่าใน cell B1
$sheet->setCellValue('C1', 'CREATE_DATE'); // กำหนดค่าใน cell A1
$sheet->setCellValue('D1', 'CONFIRM_DATE'); // กำหนดค่าใน cell B1
$sheet->setCellValue('E1', 'DOCUMENT_NO'); // กำหนดค่าใน cell A1
$sheet->setCellValue('F1', 'LABNUMBER'); // กำหนดค่าใน cell B1
$sheet->setCellValue('G1', 'ITEM_CODE'); // กำหนดค่าใน cell A1
$sheet->setCellValue('H1', 'ITEM_NAME'); // กำหนดค่าใน cell B1
$sheet->setCellValue('I1', 'ISSUE_QTY'); // กำหนดค่าใน cell A1
$sheet->setCellValue('J1', 'CATEGORY_PRICE'); // กำหนดค่าใน cell B1
$sheet->setCellValue('K1', 'QUANTITY'); // กำหนดค่าใน cell A1
$sheet->setCellValue('L1', 'AMOUNT'); // กำหนดค่าใน cell B1
$sheet->setCellValue('M1', 'ISSAP'); // กำหนดค่าใน cell A1
$sheet->setCellValue('N1', 'STATUS_DEPARTMENT'); // กำหนดค่าใน cell B1




$query = "SELECT
            dp.Ship_To,
            CONCAT( dp.DepCode, '-', dp.DepName ) AS CUSTOMER,
            DATE( shelfcount.DocDate ) AS CREATE_DATE,
            DATE( shelfcount.complete_date ) AS CONFIRM_DATE,
            shelfcount.DocNo,
            shelfcount.LabNumber,
            item.ItemCode,
            item.ItemName,
            sc_detail.TotalQty AS ISSUE,
            category_price.Price,
            sc_detail.Weight AS QUANTITY,
            sc_detail.Price AS AMOUNT,
            item.isSAP AS isSAP,
            dp.isActive AS STATUS_DEPARTMENT 
            FROM
            shelfcount
            INNER JOIN ( SELECT sc_detail.TotalQty, sc_detail.Weight, sc_detail.Price, sc_detail.DocNo, sc_detail.ItemCode FROM shelfcount_detail sc_detail ) AS sc_detail ON shelfcount.DocNo = sc_detail.DocNo
            INNER JOIN ( SELECT dp.DepCode, dp.DepName, dp.Ship_To, dp.isActive, dp.HptCode FROM department dp ) AS dp ON shelfcount.DepCode = dp.DepCode
            INNER JOIN ( SELECT item.ItemCode, item.ItemName, item.isSAP, item.CategoryCode FROM item ) AS item ON sc_detail.ItemCode = item.ItemCode
            INNER JOIN ( SELECT category_price.CategoryCode, category_price.Price, category_price.HptCode FROM category_price ) AS category_price ON item.CategoryCode = category_price.CategoryCode 
            WHERE
            DATE( shelfcount.complete_date ) IN (
            '2022-05-01',
            '2022-05-02',
            '2022-05-03',
            '2022-05-04',
            '2022-05-05',
            '2022-05-06',
            '2022-05-07',
            '2022-05-08',
            '2022-05-09',
            '2022-05-10',
            '2022-05-11',
            '2022-05-12',
            '2022-05-13',
            '2022-05-14',
            '2022-05-15',
            '2022-05-16',
            '2022-05-17',
            '2022-05-18',
            '2022-05-19',
            '2022-05-20',
            '2022-05-21',
            '2022-05-22',
            '2022-05-23',
            '2022-05-24',
            '2022-05-25',
            '2022-05-26',
            '2022-05-27',
            '2022-05-28',
            '2022-05-29',
            '2022-05-30',
            '2022-05-31' 
            ) 
            AND ( shelfcount.isStatus = 3 OR shelfcount.isStatus = 4 ) 
            AND sc_detail.TotalQty <> 0 
            AND dp.HptCode = 'BHQ' 
            AND category_price.HptCode = 'BHQ' 
            AND shelfcount.SiteCode = 'BHQ' 
            GROUP BY
            shelfcount.LabNumber,
            item.ItemCode 
            ORDER BY
            shelfcount.DocNo ";

$r = 0;
$start_row = 2;

$meQuery = mysqli_query($conn, $query);
while ($Result = mysqli_fetch_assoc($meQuery))
{
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1
    $sheet->setCellValue($cell1[$r++] . $start_row, $Result['Ship_To']); // กำหนดค่าใน cell A1

    $start_row ++;
    $r = 0;
}












  
$writer = new Xlsx($spreadsheet);
 
// ชื่อไฟล์
$file_export= "Excel-".date("dmY-Hs");
 
 
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="'.$file_export.'.xlsx"');
header("Content-Transfer-Encoding: binary ");
 
$writer->save('php://output');
exit(); 
?>