<?php
session_start();
require '../connect/connect.php';
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
if ($Userid == "") {
  header("location:../index.html");
}
?>



<html>

<head>
  <!-- <title>Pose Inttelligence</title> -->
</head>

<body>
  <?php
  $timesss = date('l jS \of F Y h:i:s A');
  $Sql = "SELECT
            shelfcount.DocNo,
            shelfcount.SiteCode,
            site.HptName,
            shelfcount.DepCode,
            DATE(shelfcount.Modify_Date) as datesc ,
            TIME(shelfcount.Modify_Date) as timesc 
        FROM
          shelfcount
        INNER JOIN site ON shelfcount.SiteCode = site.HptCode
        WHERE
          shelfcount.checkAlert = 0 
        AND Time( shelfcount.alertTime ) < Time(NOW());";
  $meQuery = mysqli_query($conn, $Sql);
  while ($row = mysqli_fetch_assoc($meQuery)) {
    $DocNo = $row["DocNo"];
    $SiteCode = $row["SiteCode"];
    $HptName = $row["HptName"];
    $DepCode = $row["DepCode"];
    $timesc = $row["timesc"];
    $datesc = explode("-", $row['datesc']);
    $dateen = $datesc[2] . '-' . $datesc[1] . '-' . $datesc[0];
    $dateth = $datesc[2] . '-' . $datesc[1] . '-' . ($datesc[0] + 543);
    $dateen = $dateen . " " . $timesc;
    $dateth = $dateth . " " . $timesc;

    $sqlUpdate = "UPDATE shelfcount  SET shelfcount.checkAlert = 1 WHERE DocNo = '$DocNo' ";
    mysqli_query($conn, $sqlUpdate);

    // $Modify_Date = $row["Modify_Date"];

    $sqldep = "SELECT
                  department.DepName
                FROM
                department
                WHERE  department.HptCode = '$SiteCode' AND department.DepCode = '$DepCode' ";
    $meQuerydep = mysqli_query($conn, $sqldep);
    while ($rowdep = mysqli_fetch_assoc($meQuerydep)) {
      $DepName = $rowdep["DepName"];
    }

    $strSubject = "=?UTF-8?B?" . base64_encode("Notification accept document") . "?=";
    // $strHeader .= "MIME-Version: 1.0'\r\n";
    $strHeader .= "Content-type: text/html; charset=utf-8\r";
    $strHeader .= "From: poseinttelligence@gmail.com>";

    $count = 0;
    $sqlEmail = "SELECT
                    users.email
                  FROM
                    users
                  WHERE  users.HptCode = '$SiteCode' AND  users.PmID = 5

                  UNION

                  SELECT
                    users.email 
                  FROM
                    users 
                  WHERE  users.HptCode = '$SiteCode' AND  users.PmID = 7 ";
    $meQueryEmail = mysqli_query($conn, $sqlEmail);
    while ($rowEmail = mysqli_fetch_assoc($meQueryEmail)) {
      $email[$count] = $rowEmail["email"];
      $count++;
      // $strTo = $email;
    }

    for ($i=0; $i < $count; $i++) { 
      $strMessage = "
                    <br>
                    ___________________________________________________________________<br>
                    <h3>?????????????????????????????????</h3>
                    <b>??????????????????????????? : </b> &nbsp; $HptName ($i) ($timesss) <br>
                    <b>???????????? : </b> $DepName<br>
                    <b>???????????????????????????????????? : </b> $DocNo<br>
                    <b>????????????????????? ?????????????????? : </b> $dateth<br>
                    ___________________________________________________________________<br>
                    <h3>Please reply</h3>
                    <b>Hospital : </b> &nbsp; $HptName<br>
                    <b>Department : </b> $DepName<br>
                    <b>DocumentNo : </b> $DocNo<br>
                    <b>Date Time : </b> $dateen<br>
                    ___________________________________________________________________<br> ";

      $flgSend = @mail($email[$i], $strSubject, $strMessage, $strHeader);  // @ = No Show Error //
      if ($flgSend) {
      echo "Email Sending.";
      } else {
      echo "Email Can Not Send.";
      }
    }
  }

  ?>
</body>

</html>