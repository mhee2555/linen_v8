<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
  // header("location:../index.html");
}

if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}
require 'updatemouse.php';

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['categorysub'][$language]; ?></title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <!-- <link href="../bootstrap/css/myinput.css" rel="stylesheet"> -->

  <!-- Custom fonts for this template-->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>

  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      $('#delete_icon').addClass('opacity');
      $('#delete1').removeClass('mhee');


      $('#rem1').hide();
      $('#rem2').hide();
      ShowItem();

      //On create
      GetmainCat();

      //On create
      // var keyword = $('#searchitem').val();
      // var data = {
      //   'STATUS'  : 'ShowItem',
      //   'Keyword' : keyword
      // };

      // console.log(JSON.stringify(data));
      // senddata(JSON.stringify(data));

      $('#searchitem').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowItem();
        }
      });

      $('.editable').click(function() {
        alert('hi');
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Zก-ฮๅภถุึคตจขชๆไำพะัีรนยบลฃฟหกดเ้่าสวงผปแอิืทมใฝ๑๒๓๔ู฿๕๖๗๘๙๐ฎฑธํ๊ณฯญฐฅฤฆฏโฌ็๋ษศซฉฮฺ์ฒฬฦ. ]/g, ''); //<-- replace all other than given set of values
      });

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });



    function removeborder() {
      Blankinput();
      ShowItem();
      $('#maincatagory').css('border-color', '');

    }

    function ShowItem(check) {
      $('.checkblank66').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).css('border-color', 'red');
        } else {
          $(this).css('border-color', '');
        }
      });
      var maincatagory = $('#maincatagory option:selected').attr("value");
      if (typeof maincatagory == 'undefined') maincatagory = "1";
      //var dept = $('#Deptsel').val();
      var keyword = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'maincatagory': maincatagory,
        'Keyword': keyword,
        'check': check
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function resetinput() {
      var CategoryName = $('#CategoryName').val();
      if (CategoryName != "" && CategoryName != undefined) {
        $('#rem2').hide();
        $('#CategoryName').css('border-color', '');
      }
    }

    function AddItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });

      console.log(count);
      var CategoryCode = $('#CategoryCodeReal').val();
      var CategoryName = $('#CategoryName').val();
      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          }
        });
        if (CategoryCode == "") {
          swal({
            title: "<?php echo $array['adddata'][$language]; ?>",
            text: "<?php echo $array['adddata1'][$language]; ?>",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-success",
            confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
            cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            if (result.value) {

              var data = {
                'STATUS': 'AddItem',
                'CategoryCode': CategoryCode,
                'CategoryName': CategoryName,
              };

              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            } else if (result.dismiss === 'cancel') {
              swal.close();
            }
          })

        } else {
          swal({
            title: "<?php echo $array['editdata'][$language]; ?>",
            text: "<?php echo $array['editdata1'][$language]; ?>",
            type: "question",
            showCancelButton: true,
            confirmButtonClass: "btn-warning",
            confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
            cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
            confirmButtonColor: '#6fc864',
            cancelButtonColor: '#3085d6',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            if (result.value) {

              var data = {
                'STATUS': 'EditItem',
                'CategoryCode': CategoryCode,
                'CategoryName': CategoryName
              };

              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            } else if (result.dismiss === 'cancel') {
              swal.close();
            }
          })

        }
      } else {
        swal({
          title: '',
          text: "<?php echo $array['required'][$language]; ?>",
          type: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
            if (CategoryCode == "" || CategoryCode == undefined) {
              $('#rem1').show().css("color", "red");
            }
            if (CategoryName == "" || CategoryName == undefined) {
              $('#rem2').show().css("color", "red");
            }
          }
        });
      }
    }

    function CancelItem() {
      swal({
        title: "<?php echo $array['canceldata'][$language]; ?>",
        text: "<?php echo $array['canceldata1'][$language]; ?>",
        type: "warning",
        showCancelButton: true,
        confirmButtonClass: "btn-danger",
        confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
        cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        if (result.value) {
          var CategoryCode = $('#CategoryCodeReal').val();
          var data = {
            'STATUS': 'CancelItem',
            'CategoryCode': CategoryCode
          }
          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function Blankinput() {
      $('#rem1').hide();
      $('#rem2').hide();
      $('#bCancel').attr('disabled', true);
      $('.checkblank').each(function() {
        $(this).val("");
      });
      $('.checkblank').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).css('border-color', '');
        } else {
          $(this).css('border-color', '');
        }
      });
      $('#CategoryCode').val("");
      $('#CategoryName').val("");
      //$('#Dept').val("1");
      ShowItem();
      $('#delete_icon').addClass('opacity');
      $('#delete1').removeClass('mhee');
    }

    function getdetail(CategoryCode, row) {
      var number = parseInt(row) + 1;
      var previousValue = $('#checkitem_' + row).attr('previousValue');
      var name = $('#checkitem_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#checkitem_' + row).removeAttr('checked');
        $('#checkitem_' + row).attr('previousValue', false);
        $('#checkitem_' + row).prop('checked', false);
        Blankinput();
      } else {
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('#checkitem_' + row).attr('previousValue', 'checked');

        if (CategoryCode != "" && CategoryCode != undefined) {
          var data = {
            'STATUS': 'getdetail',
            'CategoryCode': CategoryCode,
            'number': number
          };

          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }
      }

    }




    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/category_sub.php';
      $.ajax({
        url: URL,
        dataType: 'text',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data,
        type: 'post',
        beforeSend: function() {
          swal({
            title: '<?php echo $array['pleasewait'][$language]; ?>',
            text: '<?php echo $array['processing'][$language]; ?>',
            allowOutsideClick: false
          })
          swal.showLoading();
        },
        success: function(result) {
          try {
            var temp = $.parseJSON(result);
          } catch (e) {
            console.log('Error#542-decode error');
          }
          swal.close();
          if (temp["status"] == 'success') {
            if ((temp["form"] == 'ShowItem')) {
              $("#TableItem tbody").empty();
              console.log(temp);
              if (temp['Count'] > 0) {
                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;
                  var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' class='check1' id='checkitem_" + i + "'  style='margin-top: 24%;'value='" + temp[i]['CategoryCode'] + "' onclick='getdetail(\"" + temp[i]["CategoryCode"] + "\",\"" + i + "\")'><span class='checkmark'></span></label>";
                  // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                  StrTR = "<tr id='tr" + temp[i]['CategoryCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                    "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                    "<td style='width: 15%;' hidden>" + temp[i]['CategoryCode'] + "</td>" +
                    "<td style='width: 85%;'>" + temp[i]['CategoryName'] + "</td>" +
                    "</tr>";

                  if (rowCount == 0) {
                    $("#TableItem tbody").append(StrTR);
                  } else {
                    $('#TableItem tbody:last-child').append(StrTR);
                  }
                }
              } else {
                $('#TableItem tbody').empty();
                var Str = "<tr width='100%' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                $('#TableItem tbody:last-child').append(Str);
                // swal({
                //     title: '',
                //     text: '<?php echo $array['notfoundmsg'][$language]; ?>',
                //     type: 'warning',
                //     showCancelButton: false,
                //     showConfirmButton: false,
                //     timer: 2000,
                // });
              }
            } else if ((temp["form"] == 'getdetail')) {
              if ((Object.keys(temp).length - 2) > 0) {
                console.log(temp);

                $('#CategoryCodeReal').val(temp['CategoryCodeReal']);
                $('#CategoryCode').val(temp['CategoryCode']);
                $('#CategoryName').val(temp['CategoryName']);
                $('#bCancel').attr('disabled', false);
                $('#delete_icon').removeClass('opacity');
                $('#delete1').addClass('mhee');
              }
            } else if ((temp["form"] == 'GetmainCat')) {
              var StrTr = "<option value=''> <?php echo $array['Pleasechoosemaincategory'][$language]; ?></option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                StrTr += "<option value = '" + temp[i]['MainCategoryCode'] + "'> " + temp[i]['MainCategoryName'] + " </option>";
              }
              $("#maincatagory").append(StrTr);
            } else if ((temp["form"] == 'AddItem')) {
              switch (temp['msg']) {
                case "notchosen":
                  temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                  break;
                case "cantcreate":
                  temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                  break;
                case "noinput":
                  temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                  break;
                case "notfound":
                  temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                  break;
                case "addsuccess":
                  temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                  break;
                case "addfailed":
                  temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                  break;
                case "editsuccess":
                  temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                  break;
                case "editfailed":
                  temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                  break;
                case "cancelsuccessmsg":
                  temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                  break;
              }
              swal({
                title: '',
                text: temp['msg'],
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {
                ShowItem();
                Blankinput();
              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).css('border-color', '');
                });

                $('#CategoryCode').val("");
                $('#CategoryName').val("");
                //$('#Dept').val("1");
                ShowItem();
              })
            } else if ((temp["form"] == 'EditItem')) {
              switch (temp['msg']) {
                case "notchosen":
                  temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                  break;
                case "cantcreate":
                  temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                  break;
                case "noinput":
                  temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                  break;
                case "notfound":
                  temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                  break;
                case "addsuccess":
                  temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                  break;
                case "addfailed":
                  temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                  break;
                case "editsuccess":
                  temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                  break;
                case "editfailed":
                  temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                  break;
                case "cancelsuccessmsg":
                  temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                  break;
              }
              swal({
                title: '',
                text: temp['msg'],
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {
                ShowItem();
                Blankinput();
              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#CategoryCode').val("");
                $('#CategoryName').val("");
                //$('#Dept').val("1");
                ShowItem();
              })
            } else if ((temp["form"] == 'CancelItem')) {
              switch (temp['msg']) {
                case "notchosen":
                  temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                  break;
                case "cantcreate":
                  temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                  break;
                case "noinput":
                  temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                  break;
                case "notfound":
                  temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                  break;
                case "addsuccess":
                  temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                  break;
                case "addfailed":
                  temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                  break;
                case "editsuccess":
                  temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                  break;
                case "editfailed":
                  temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                  break;
                case "cancelsuccess":
                  temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                  break;

              }
              swal({
                title: '',
                text: temp['msg'],
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 2000,
                confirmButtonText: 'Ok'
              }).then(function() {
                ShowItem();
                Blankinput();
              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#CategoryCode').val("");
                $('#CategoryName').val("");
                //$('#Dept').val("1");
                ShowItem();
              })
            } else if ((temp["form"] == 'getSection')) {
              var Strtr = "<option value=''>-</option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                StrTr += "<option value = '" + temp[i]['DepCode'] + "'> " + temp[i]['DepName'] + " </option>";
              }
              $("#Dept").append(StrTr);
              $("#Deptsel").append(StrTr);

            }
          } else if (temp['status'] == "failed") {
            switch (temp['msg']) {
              case "notchosen":
                temp['msg'] = "<?php echo $array['choosemsg'][$language]; ?>";
                break;
              case "cantcreate":
                temp['msg'] = "<?php echo $array['cantcreatemsg'][$language]; ?>";
                break;
              case "noinput":
                temp['msg'] = "<?php echo $array['noinputmsg'][$language]; ?>";
                break;
              case "notfound":
                temp['msg'] = "<?php echo $array['notfoundmsg'][$language]; ?>";
                break;
              case "addsuccess":
                temp['msg'] = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                break;
              case "addfailed":
                temp['msg'] = "<?php echo $array['addfailedmsg'][$language]; ?>";
                break;
              case "editsuccess":
                temp['msg'] = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                break;
              case "editfailed":
                temp['msg'] = "<?php echo $array['editfailedmsg'][$language]; ?>";
                break;
              case "cancelsuccess":
                temp['msg'] = "<?php echo $array['cancelsuccessmsg'][$language]; ?>";
                break;

            }
            swal({
              title: '',
              text: temp['msg'],
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            })

          } else if (temp['status'] == "notfound") {
            // swal({
            //   title: '',
            //   text: temp['msg'],
            //   type: 'info',
            //   showCancelButton: false,
            //   confirmButtonColor: '#3085d6',
            //   cancelButtonColor: '#d33',
            //   showConfirmButton: false,
            //   timer: 2000,
            //   confirmButtonText: 'Ok'
            // })
            $("#TableItem tbody").empty();

          } else {
            console.log(temp['msg']);
          }
        },
        failure: function(result) {
          alert(result);
        },
        error: function(xhr, status, p3, p4) {
          var err = "Error " + " " + status + " " + p3 + " " + p4;
          if (xhr.responseText && xhr.responseText[0] == "{")
            err = JSON.parse(xhr.responseText).Message;
          console.log(err);
        }
      });
    }
  </script>
  <style media="screen">
    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }

    body {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 24px !important;
    }

    .table>thead>tr>th {
      background-color: #1659a2;
    }

    table tr th,
    table tr td {
      border-right: 0px solid #bbb;
      border-bottom: 0px solid #bbb;
      padding: 5px;
    }

    table tr th:first-child,
    table tr td:first-child {
      border-left: 0px solid #bbb;
    }

    table tr th {
      background: #eee;
      border-top: 0px solid #bbb;
      text-align: left;
    }

    /* top-left border-radius */
    table tr:first-child th:first-child {
      border-top-left-radius: 15px;
    }

    table tr:first-child th:first-child {
      border-bottom-left-radius: 15px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 15px;
    }

    table tr:first-child th:last-child {
      border-bottom-right-radius: 15px;
    }

    /* bottom-left border-radius */
    table tr:last-child td:first-child {
      border-bottom-left-radius: 6px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }

    button {
      font-size: 24px !important;
    }

    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    .sidenav {
      height: 100%;
      overflow-x: hidden;
      /* padding-top: 20px; */
      border-left: 2px solid #bdc3c7;
    }

    .mhee a {
      /* padding: 6px 8px 6px 16px; */
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .mhee a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .mhee button {
      /* padding: 6px 8px 6px 16px; */
      font-size: 25px;
      color: #2c3e50;
      background: none;
      box-shadow: none !important;
    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
      outline: none;
    }

    .sidenav a {
      padding: 6px 8px 6px 16px;
      text-decoration: none;
      font-size: 25px;
      color: #818181;
      display: block;
    }

    .white {
      color: #fff;
    }

    .white:hover {
      color: #fff;
    }

    .black {
      color: #818181;
    }

    .black:hover {
      color: #818181;
    }

    .sidenav a:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
    }

    .icon {
      padding-top: 6px;
      padding-left: 33px;
    }

    .opacity {
      opacity: 0.5;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav {
        margin-left: 30px;
      }

      .sidenav a {
        font-size: 20px;

      }
    }
  </style>
</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][5][$language]; ?></li>
  </ol>
  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <!--
          <div class="mycheckbox">
            <input type="checkbox" name='useful' id='useful' onclick='setTag()'/><label for='useful' style='color:#FFFFFF'> </label>
          </div>
-->
      <div class="row">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
              <div class="row">
                <div class="col-md-12">
                  <div class="row" style="margin-left: 9px;">
                    <input type="text" autocomplete="off" class="form-control" style="width:35%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchsubcategory'][$language]; ?>">
                    <div class="search_custom col-md-2">
                      <div class="search_1 d-flex justify-content-start">
                        <button class="btn" onclick="ShowItem()" id="bSave">
                          <i class="fas fa-search mr-2"></i>
                          <?php echo $array['search'][$language]; ?>
                        </button>
                      </div>
                    </div>

                  </div>
                </div>

              </div>
              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped " id="TableItem" width="100%" cellspacing="0" role="grid">
                <thead id="theadsum" style="font-size:11px;">
                  <tr role="row">
                    <th style='width: 5%;'>&nbsp;</th>
                    <th style='width: 10%;'><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 15%;' hidden><?php echo $array['categorysubcode'][$language]; ?></th>
                    <th style='width: 85%;'><?php echo $array['category'][$language]; ?></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:250px;">
                </tbody>
              </table>

            </div>
          </div>
        </div> <!-- tag column 1 -->
      </div>
      <!-- =============================================================================================================================== -->
      <!-- /.content-wrapper -->
      <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
        <div class="menu mhee" <?php if ($PmID == 3 || $PmID == 2 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
          <div class="d-flex justify-content-center">
            <div class="circle4 d-flex justify-content-center">
              <button class="btn" onclick="AddItem()" id="bSave">
                <i class="fas fa-save"></i>
                <div>
                  <?php echo $array['save'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
        <div class="menu mhee">
          <div class="d-flex justify-content-center">
            <div class="circle6 d-flex justify-content-center">
              <button class="btn" onclick="Blankinput()" id="bDelete">
                <i class="fas fa-redo-alt"></i>
                <div>
                  <?php echo $array['clear'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
        <div class="menu " id="delete1" <?php if ($PmID == 3 || $PmID == 2 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
          <div class="d-flex justify-content-center">
            <div class="circle3 d-flex justify-content-center" id="delete_icon">
              <button class="btn" onclick="CancelItem()" id="bCancel" disabled="true">
                <i class="fas fa-trash-alt"></i>
                <div>
                  <?php echo $array['cancel'][$language]; ?>
                </div>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- =============================================================================================================================== -->
      <div class="row m-2">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
              </li>
            </ul>
            <div class="card-body" style="padding:0px; margin-top:10px;">
              <!-- =================================================================== -->
              <div class="row mt-4">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['no'][$language]; ?></label>
                    <input type="text" class="form-control col-sm-7 " id="CategoryCode" placeholder="<?php echo $array['categorycode'][$language]; ?>" readonly>
                  </div>
                </div>
                <div class="col-md-6" hidden>
                  <div class='form-group row'>
                    <input type="text" class="form-control col-sm-7 " id="CategoryCodeReal">
                  </div>
                </div>
              </div>


              <!-- =================================================================== -->
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-3 col-form-label "><?php echo $array['category'][$language]; ?></label>
                    <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank" id="CategoryName" placeholder="<?php echo $array['category'][$language]; ?>">
                    <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
              </div>
              <!-- =================================================================== -->
            </div>
          </div>
        </div> <!-- tag column 2 -->
        <!-- =============================================================================================== -->

      </div>


      <!-- /#wrapper -->
      <!-- Scroll to Top Button-->
      <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
      </a>


      <!-- Bootstrap core JavaScript-->
      <script src="../template/vendor/jquery/jquery.min.js"></script>
      <script src="../template/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

      <!-- Core plugin JavaScript-->
      <script src="../template/vendor/jquery-easing/jquery.easing.min.js"></script>

      <!-- Page level plugin JavaScript-->
      <script src="../template/vendor/datatables/jquery.dataTables.js"></script>
      <script src="../template/vendor/datatables/dataTables.bootstrap4.js"></script>

      <!-- Custom scripts for all pages-->
      <script src="../template/js/sb-admin.min.js"></script>

      <!-- Demo scripts for this page-->
      <script src="../template/js/demo/datatables-demo.js"></script>

</body>

</html>