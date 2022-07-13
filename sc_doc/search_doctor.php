<?php
session_start();
require '../connect/connect.php';
if(isset($_POST["query"])){
  $output = '';

  $Sql = "SELECT
            doctor.ID,
            CONCAT( doctor.Prefix, doctor.FName, ' ', doctor.LName ) AS NameShow
          FROM
            doctor 
          WHERE 
            (Prefix LIKE '%".$_POST["query"]."%' OR FName LIKE '%".$_POST["query"]."%' OR LName LIKE '%".$_POST["query"]."%')  ";
  $Result = mysqli_query($conn,$Sql);
  $output = '<ul class="list-unstyled">';
  if(mysqli_num_rows($Result) > 0){
    while($row = mysqli_fetch_array($Result)){
      $TitleNamex = $row["TitleName"];
      $output .= '<li id="listhn_3"  value="'.$row["ID"].'">'.$row["NameShow"].'</li>';
    }
  }else{
    $output .= '<li>Doctor Name Not Found'; 
  }
  $output .= '</ul>';
  echo $output;
}
  
?>