<?php
date_default_timezone_set("Asia/Bangkok");
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

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

  <title><?php echo $array['itemdepartment'][$language]; ?></title>

  <link rel="icon" type="image/png" href="../img/pose_favicon.png">
  <!-- Bootstrap core CSS-->
  <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="../bootstrap/css/tbody.css" rel="stylesheet">
  <link href="../bootstrap/css/myinput.css" rel="stylesheet">

  <!-- Custom fonts for this template-->
  <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

  <!-- Page level plugin CSS-->
  <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

  <!-- Custom styles for this template-->
  <link href="../template/css/sb-admin.css" rel="stylesheet">
  <link href="../css/xfont.css" rel="stylesheet">
  <!-- <link href="../css/responsive.css" rel="stylesheet"> -->
  <link href="../css/menu_custom.css" rel="stylesheet">
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      $(".select2").select2();
      $("#xCenter").prop('checked', true);
      $('#rem1').hide();
      $('#rem2').hide();
      //On create
      $('#txtrow').hide();
      $('#txtdpk').hide();
      //On create
      // var userid = '<?php echo $Userid; ?>';
      // if(userid!="" && userid!=null && userid!=undefined){

      //var dept = $('#Deptsel').val();
      // }
      var keyword = $('#searchitem').val();

      $('.borderred').on('input', function() {
        this.value = this.value.replace($('#parnum').removeClass('border-danger'), $('#rem2').hide(), $('#form3').addClass('mt-3'), $('#form4').addClass('form-group')); //<-- replace all other than given set of values
      });
      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
      });
      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z???-????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????. ]/g, ''); //<-- replace all other than given set of values
      });

      $('#searchitem').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowItem();
        }
      });

      $('#searchitemstock').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowItemStock();
        }
      });


      $('.editable').click(function() {
        alert('hi');
      });



      // getDepartment();
      getHospital();
      ShowItem();
      // ShowItemStock();
    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });




    function checkblank2() {
      var par = $('#parnum').val();
      var xCenter = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if (xCenter == 1) {
        var department = $('#HosCenter').val();
        $('#department').removeClass('checkblank2');
      } else {
        var department = $('#department').val();
        $('#department').addClass('checkblank2');
      }
      $('.checkblank2').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          if (department == "" || department == undefined) {
            $('#rem1').show().css("color", "red");
            $('#form1').removeClass('mt-3');
            $('#form2').removeClass('form-group');
          }
          if (par == "" || par == undefined) {
            $('#rem2').show().css("color", "red");
            $('#form3').removeClass('mt-3');
            $('#form4').removeClass('form-group');
          }
        } else {
          $(this).removeClass('border-danger');
        }
      });
    }

    function removeClassBorder1(chk) {
      if (chk == 1) {
        $('#TableItemStock tbody').empty();
      } else {
        $('input[name="checkitem').prop('checked', false);
        $('input[name="txtno').val('');
      }
      var par = $('#parnum').val();
      var department = $('#department').val();
      if (par != "" && par != undefined) {
        $('#rem2').hide();
        $('#parnum').removeClass('border-danger');
        $('#form3').addClass('mt-3');
        $('#form4').addClass('form-group');
      }
      if (department != "" && department != undefined) {
        $('#department').removeClass('border-danger');
        $('#rem1').hide();
        $('#form1').addClass('mt-3');
        $('#form2').addClass('form-group');
      }



    }


    function getDepartment() {
      $('#TableItemStock tbody').empty();
      $('#parnum').val("");
      $('#searchitem').val("");
      var Hotp = $('#hotpital option:selected').attr("value");
      if (typeof Hotp == 'undefined') Hotp = "<?php echo $HptCode; ?>";
      var userid = "<?php echo $_SESSION['Userid']; ?>";
      ShowItem();
      var data = {
        'STATUS': 'getDepartment',
        'Userid': userid,
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));

    }

    function getHospital() {
      var lang = '<?php echo $language; ?>';
      var userid = "<?php echo $_SESSION['Userid']; ?>";
      var HptCode = "<?php echo $_SESSION['HptCode']; ?>";
      var data = {
        'STATUS': 'getHospital',
        'Userid': userid,
        'HptCode': HptCode,
        'lang': lang
      };
      senddata(JSON.stringify(data));
    }

    jqui(document).ready(function($) {

      dialog = jqui("#dialog").dialog({
        autoOpen: false,
        height: 250,
        width: 400,
        modal: true
      });

      jqui("[id^=exp_]").button().on("click", function() {
        dialog.dialog("open");
      });
    });

    function datedialog(RowID) {
      $('#txtrow').val(RowID);
      dialog.dialog("open");
    }



    function SavePar(num, row, rowid) {
      var mypar = $(".mypar_" + row).val();
      var xCenter = 0;
      var xCenter2 = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      var userid = "<?php echo $_SESSION["Userid"]; ?>"
      if (xCenter == 1) {
        var DepCode = $('#HosCenter').val();
      } else {
        var DepCode = $('#department').val();
      }
      var keyword = $('#searchitemstock').val();
      var HptCode = $('#hotpital').val();



      var data = {
        'STATUS': 'SavePar',
        'mypar': mypar,
        'RowID': rowid,
        'num': num,
        'HptCode': HptCode,
        'DepCode': DepCode,
        'xCenter': xCenter,
        'xCenter2': xCenter2
      }
      senddata(JSON.stringify(data));
    }

    function allitem() {
      var par = $('#parnum').val();
      var select_all = document.getElementById('selectAll'); //select all checkbox
      var checkboxes = document.getElementsByClassName("checkall"); //checkbox items
      var selectAll = 0;
      if ($('#selectAll').is(':checked')) selectAll = 1;
      //select all checkboxes
      if (selectAll == 1) {
        select_all.addEventListener("change", function(e) {
          for (i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = select_all.checked
            $('.checktxt').val(par);
            $('#bSave').attr('disabled', false);
            $('#hover1').removeClass('opacity');
            $('#hover1').addClass('mhee1');
          }
        });
      } else {
        select_all.addEventListener("change", function(e) {
          for (i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = select_all.checked
            $('.checktxt').val('');
            $('#bSave').attr('disabled', true);
            $('#hover1').addClass('opacity');
            $('#hover1').removeClass('mhee1');
          }
        });
      }

      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) { //".checkbox" change 
          //uncheck "select all", if one of the listed checkbox item is unchecked
          if (this.checked == false) {
            select_all.checked = false;
          }

        });
      }

    }

    function chkbox(ItemCode) {
      var par = $('#parnum').val();
      var xCenter = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if (xCenter == 1) {
        var department = $('#HosCenter').val();
      } else {
        var department = $('#department').val();
      }
      if (par == '' || department == '') {
        checkblank2();
        $('#checkitem_' + ItemCode).prop('checked', false);
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
        });
      } else {
        $('#bSave').attr('disabled', false);
        $('#hover1').removeClass('opacity');
        $('#hover1').addClass('mhee1');
        console.log($('#checkitem_' + ItemCode));

        if ($('#checkitem_' + ItemCode).is(":checked")) {
          $('#checkitem_' + ItemCode).prop('checked', true);
          $('#txtno_' + ItemCode).val("");
          $('#txtno_' + ItemCode).focus();
          $('#txtno_' + ItemCode).val(par);
        } else {
          $('#checkitem_' + ItemCode).prop('checked', false);
          $('#txtno_' + ItemCode).val("");
        }
      }


    }

    function center() {
      var xCenter = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      $("#xCenter2").prop('checked', false);
      if (xCenter == 1) {
        $('#department').attr('disabled', true);
        $('#department').addClass('icon_select');
        $('#department').val('');
        $('#xCenter').attr('disabled', true);
        $('#xCenter2').attr('disabled', false);
        ShowItem();
      } else {
        $('#department').removeClass('icon_select');
        $('#department').attr('disabled', false);
      }
    }

    function ShowItem(chk) {
      if (chk == 2) {
        $("#xCenter").prop('checked', false);
        $('#department').removeClass('icon_select');
        $('#department').attr('disabled', false);
        $('#xCenter').attr('disabled', false);
        $('#xCenter2').attr('disabled', true);
      }
      var xCenter2 = 0;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      var userid = "<?php echo $_SESSION["Userid"]; ?>"
      var keyword = $('#searchitem').val();
      var HosCenter = $('#HosCenter').val();
      var HptCode = $('#hotpital').val();
      var data = {
        'STATUS': 'ShowItem',
        'Keyword': keyword,
        'HptCode': HptCode,
        'Userid': userid,
        'HosCenter': HosCenter,
        'xCenter2': xCenter2
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function AddItem() {
      var count = 0;
      $(".checkblank").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });
      console.log(count);

      var UnitCode = $('#UnitCode').val();
      var UnitName = $('#UnitName').val();

      if (count == 0) {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
          } else {
            $(this).css('border-color', '');
          }
        });
        if (UnitCode == "") {
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
            var data = {
              'STATUS': 'AddItem',
              'UnitCode': UnitCode,
              'UnitName': UnitName
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
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
            var data = {
              'STATUS': 'EditItem',
              'UnitCode': UnitCode,
              'UnitName': UnitName
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
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
          } else {
            $(this).css('border-color', '');
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
        confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
        cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        var UnitCode = $('#UnitCode').val();
        var data = {
          'STATUS': 'CancelItem',
          'UnitCode': UnitCode
        }
        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      })
    }

    function Blankinput() {
      $('.checkblank').each(function() {
        $(this).val("");
      });
      $('#UnitCode').val("");
      $('#UnitName').val("");
      //$('#Dept').val("1");
      ShowItem();
    }

    function getdetail(UnitCode) {
      if (UnitCode != "" && UnitCode != undefined) {
        var data = {
          'STATUS': 'getdetail',
          'UnitCode': UnitCode
        };

        console.log(JSON.stringify(data));
        senddata(JSON.stringify(data));
      }
    }

    function Addtodoc() {
      var xCenter = 0;
      var hotpital = $('#hotpital').val();
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      if (xCenter == 1) {
        var dept = $('#HosCenter').val();
        $('#showcenter1').attr('hidden', false);
        $('#showcenter2').attr('hidden', true);
      } else {
        var dept = $('#department').val();
        $('#showcenter1').attr('hidden', true);
        $('#showcenter2').attr('hidden', false);
      }
      if (dept == '') {
        checkblank2();
      } else {
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
            var boolean = Chkblank();
            if (boolean) {
              var chkArray1 = [];
              var chkArray2 = [];
              $('input[name="checkitem"]:checked').each(function() {
                chkArray1.push($(this).val());
                // console.log($(this).val());
              });
              $('input[name="txtno"]').each(function() {
                if ($(this).val() != "") {
                  chkArray2.push($(this).val());
                  // console.log($(this).val());
                }
              });
              var par = $('#parnum').val();
              var hotpital = $('#hotpital').val();
              var strchkarray1 = chkArray1.join(',');
              var strchkarray2 = chkArray2.join(',');
              var data = {
                'STATUS': 'additemstock',
                'DeptID': dept,
                'Par': par,
                'hotpital': hotpital,
                'ItemCode': strchkarray1,
                'Number': strchkarray2,
                'xCenter2': xCenter2,
                'hotpital': hotpital

              }
              console.log(JSON.stringify(data));
              senddata(JSON.stringify(data));
            } else if (result.dismiss === 'cancel') {
              swal.close();
            }
          }
        })
      }
    }

    function Chkblank() {
      if ($('#parnum').val() != "") {
        var icheck = 0;
        $('input[name="checkitem"]:checked').each(function() {
          icheck++;
        });
        if (icheck > 0) {
          var icheck2 = 0;
          $('input[name="txtno"]').each(function() {
            if ($(this).val() != "")
              icheck2++;
          });
          if (icheck == icheck2) {
            return true;
          } else {
            swal({
              title: '',
              text: '<?php echo $array['pleasefill'][$language]; ?>',
              type: 'warning',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              showConfirmButton: false,
              timer: 2000,
              confirmButtonText: 'Ok'
            }).then(function() {

            }, function(dismiss) {})
          }
        } else {
          swal({
            title: '',
            text: '<?php echo $array['pleasecheck'][$language]; ?>',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 2000,
            confirmButtonText: 'Ok'
          }).then(function() {

          }, function(dismiss) {})
        }
      } else {
        swal({
          title: '',
          text: '<?php echo $array['pleasepar'][$language]; ?>',
          type: 'warning',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        }).then(function() {

        }, function(dismiss) {})
      }
    }






    function Setdate() {
      var date = $('#datepickermodal').val();
      var row = $('#txtrow').val();
      console.log(date + " " + row);
      $('#exp_' + row).val(date);
      var data = {
        'STATUS': 'setdateitemstock',
        'Exp': date,
        'RowID': row
      }
      senddata(JSON.stringify(data));
    }

    function DeleteItem() {
      var length = $('#TableItemStock >tbody >tr').length;
      var xCenter = 0;
      var xCenter2 = 0;
      var HptCode = $('#hotpital').val();
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      if (xCenter == 1) {
        var DepCode = $('#HosCenter option:selected').val();
      } else {
        var DepCode = $('#department option:selected').val();
      }
      if (length > 0) {
        swal({
          title: "",
          text: "<?php echo $array['confirm'][$language] . " ?"; ?>",
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
            if (xCenter == 1) {
              var count_rowArray = [];
              var chkArray = [];
              var RowArray = [];
              var chkRow = [];

              var chkArray = [];
              $('input[name="myItem1"]:checked').each(function() {
                chkArray.push($(this).data('value'));
              });
              var ItemCode = chkArray.join(',');
              var RowID = RowArray.join(',');
              var ItemArray = $('#itemArray').val();

              var data = {
                'STATUS': 'DeleteItem',
                'DepCode': DepCode,
                'RowID': RowID,
                'ItemCode': ItemCode,
                'ItemArray': ItemArray,
                'xCenter': xCenter,
                'HptCode': HptCode,
                'Number': ''

              };
              senddata(JSON.stringify(data));
            } else {
              var chkArray = [];
              $('input[name="myItem"]:checked').each(function() {
                chkArray.push($(this).data('value'));
              });
              var ItemCode = chkArray.join(',');
              var data = {
                'STATUS': 'DeleteItem',
                'DepCode': DepCode,
                'RowID': RowID,
                'ItemCode': ItemCode,
                'xCenter': xCenter,
                'xCenter2': xCenter2,
                'HptCode': HptCode,
                'Number': ''

              };
              senddata(JSON.stringify(data));
            }
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }
    }

    function addnum(cnt) {
      var add = parseInt($('#qty' + cnt).val()) + 1;
      if ((add >= 0) && (add <= 500)) {
        $('#qty' + cnt).val(add);
      }
    }

    function subtractnum(cnt) {
      var sub = parseInt($('#qty' + cnt).val()) - 1;
      if ((sub >= 0) && (sub <= 500)) {
        $('#qty' + cnt).val(sub);
      }
    }

    function logoff() {
      swal({
        title: '',
        text: '<?php echo $array['logout'][$language]; ?>',
        type: 'success',
        showCancelButton: false,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        showConfirmButton: false,
        timer: 1000,
        confirmButtonText: 'Ok'
      }).then(function() {
        window.location.href = "../logoff.php";
      }, function(dismiss) {
        window.location.href = "../logoff.php";
        if (dismiss === 'cancel') {

        }
      })
    }

    function chkpar(itemCode) {
      var par = $('#parnum').val();
      var qty = $('#txtno_' + itemCode).val();

      // alert(par);
      // alert(qty);
      if (Number(qty) > Number(par)) {
        $('#txtno_' + itemCode).val(par);
      } else if (Number(qty) < Number(par)) {
        $('#txtno_' + itemCode).val(qty);
      }
    }

    function SaveUsageCode(row, Sel) {
      var UsageCode = $('#exp_' + row).val();
      if (UsageCode != "") {
        $('#exp_' + row).css("border", "3px solid green");
      } else {
        $('#exp_' + row).css("border", "");
      }
      var data = {
        'STATUS': 'SaveUsageCode',
        'UsageCode': UsageCode,
        'RowID': row,
        'Sel': Sel,
      }
      senddata(JSON.stringify(data));
    }

    function ShowItemStock() {
      var xCenter = 0;
      var xCenter2 = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      var userid = "<?php echo $_SESSION["Userid"]; ?>"
      if (xCenter == 1) {
        var DepCode = $('#HosCenter').val();
      } else {
        var DepCode = $('#department').val();
      }
      var keyword = $('#searchitemstock').val();
      var HptCode = $('#hotpital').val();
      var data = {
        'STATUS': 'ShowItemStock',
        'Keyword': keyword,
        'Userid': userid,
        'Deptid': DepCode,
        'xCenter2': xCenter2,
        'HptCode': HptCode
      };

      // console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function SelectItemStock(ItemCode, Number) {
      var xCenter = 0;
      var xCenter2 = 0;
      if ($('#xCenter').is(':checked')) xCenter = 1;
      if ($('#xCenter2').is(':checked')) xCenter2 = 1;
      if (xCenter == 1) {
        var DepCode = $('#HosCenter option:selected').val();
      } else {
        var DepCode = $('#department option:selected').val();
      }
      var HptCode = $('#hotpital').val();
      var data = {
        'STATUS': 'SelectItemStock',
        'DepCode': DepCode,
        'ItemCode': ItemCode,
        'xCenter2': xCenter2,
        'Number': Number,
        'HptCode': HptCode
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function showStock(row, num) {
      $('.tr_child_' + row).attr('hidden', false);
      $('#hideStock_' + row).attr('hidden', false);
      $('#showStock_' + row).attr('hidden', true);
      $('#rowCount').val(num);
    }

    function hideStock(row) {
      $('.tr_child_' + row).attr('hidden', true);
      $('#hideStock_' + row).attr('hidden', true);
      $('#showStock_' + row).attr('hidden', false);
    }

    function ChildChecked(row) {
      var select_all = document.getElementById("headChk_" + row); //select all checkbox
      var checkboxes = document.getElementsByClassName("myChild_" + row); //checkbox items

      //select all checkboxes
      select_all.addEventListener("change", function(e) {
        for (i = 0; i < checkboxes.length; i++) {
          checkboxes[i].checked = select_all.checked;
        }
      });


      for (var i = 0; i < checkboxes.length; i++) {
        checkboxes[i].addEventListener('change', function(e) { //".checkbox" change 
          //uncheck "select all", if one of the listed checkbox item is unchecked
          if (this.checked == false) {
            select_all.checked = false;
          }
          //check "select all" if all checkbox items are checked
          if (document.querySelectorAll('.checkbox:checked').length == checkboxes.length) {
            select_all.checked = true;
          }
        });
      }
    }

    function swithChecked(row, i, checkName) {
      $("#headChk_" + row).change(function() {
        var status = this.checked;
        $('.unchk_' + row + i).each(function() {
          this.checked = status;
        });
      });
      $('.unchk_' + row + i).change(function() {
        if (this.checked == false) {
          $("#headChk_" + row)[0].checked = false;
        }
        if ($('.myChild_' + row + ':checked').length == $('.myChild_' + row).length) {
          $("#headChk_" + row)[0].checked = true;
        }
      });
    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/link_item_dept.php';
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
              if (temp['count'] > 0) {
                for (var i = 0; i < temp['count']; i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;
                  var chkDoc = "<input type='checkbox' class='mainchk checkall' name='checkitem' id='checkitem_" + temp[i]['ItemCode'] + "'  value='" + temp[i]['ItemCode'] + "' onclick='chkbox(\"" + temp[i]['ItemCode'] + "\");'>";
                  // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                  var btn = "<center><button class='btn btn-primary' onclick=''>?????????????????????????????????</button></center>";
                  var txtno = "<input type='text' style='text-align:center;' class='form-control numonly checktxt' onkeyup='chkpar(\"" + temp[i]['ItemCode'] + "\")' name='txtno' id='txtno_" + temp[i]['ItemCode'] + "' placeholder='0' maxlength='3' min='0'>";
                  StrTR = "<tr id='tr" + temp[i]['ItemCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;' nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 28%;' nowrap hidden>" + temp[i]['ItemCode'] + "</td>" +
                    "<td style='width: 60%;' nowrap title='" + temp[i]['ItemCode'] + "'>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 25%;' nowrap>" + txtno + "</td>" +
                    "</tr>";


                  if (rowCount == 0) {
                    $("#TableItem tbody").append(StrTR);
                  } else {
                    $('#TableItem tbody:last-child').append(StrTR);
                  }
                }
              } else {
                StrTR = "<tr>" +
                  "<td style='width: 100%;' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td>" +
                  "</tr>";
                $("#TableItem tbody").append(StrTR);
              }
            } else if ((temp["form"] == 'getHospital')) {
              var PmID = <?php echo $PmID; ?>;
              var HptCode = '<?php echo $HptCode; ?>';
              var Str = "<option value=''><?php echo $array['selecthospital'][$language]; ?></option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                Str += "<option value=" + temp[i]['HptCode'] + ">" + temp[i]['HptName'] + "</option>";
                $("#hotpital").prop('checked', true);
              }
              $("#hotpital").append(Str);
              if (PmID != 1 && PmID != 6 && PmID != 3) {
                $("#hotpital").val(HptCode);
                $("#hotpital").attr('disabled', true);
              }
              getDepartment();
            } else if ((temp["form"] == 'getDepartment')) {
              $("#department").empty();
              $("#HosCenter").empty();
              var Str = "<option value=''><?php echo $array['selectdep'][$language]; ?></option>";
              for (var i = 0; i < temp['row']; i++) {
                Str += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
              }
              $("#department").append(Str);

              var StrX = "<option value=" + temp[0]['DepCodeCenter'] + ">" + temp[0]['DepNameCenter'] + "</option>";
              $("#HosCenter").append(StrX);
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

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#UnitCode').val("");
                $('#UnitName').val("");
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

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#UnitCode').val("");
                $('#UnitName').val("");
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

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#UnitCode').val("");
                $('#UnitName').val("");
                //$('#Dept').val("1");
                ShowItem();
              })
            } else if ((temp["form"] == 'getSection')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['DepCode'] + "'> " + temp[i]['DepName'] + " </option>";
                $("#Dept").append(StrTr);
                $("#Deptsel").append(StrTr);
              }

            } else if ((temp["form"] == 'additemstock')) {
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
              })
              $('#itemArray').val(temp['ItemCode']);
              SelectItemStock(temp['ItemCode'], temp['Number']);
              ShowItem();

            } else if (temp['form'] == "ShowItemStock") {
              var chk_row = $('#chk_row').val();
              $("#TableItemStock tbody").empty();
              if (temp['countpar'] == 0) {
                for (var i = 0; i < temp['countx']; i++) {
                  var parnum = '<input tyle="text"  style="text-align:center;"   class="form-control mypar_' + i + ' " onKeyPress="if(event.keyCode==13){SavePar(\'' + 1 + '\',\'' + i + '\',\'' + temp[i]['RowID'] + '\')}" value="' + temp[i]['ParQty'] + '" > ';
                  var chkHeadItem = "<input type='checkbox' name='myItem1' id='headChk_" + chk_row + "' data-value='" + temp[i]['ItemCodeX'] + "'>";
                  var rowCount = $('#TableItemStock >tbody >tr').length;
                  StrTR = "<tr id='tr_mom_" + temp[i]['ItemCodeX'] + "' data-value='" + chk_row + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;padding-left:26px' nowrap>" + chkHeadItem + "</td>" +
                    "<td hidden>" + temp[i]['ItemCodeX'] + "</td>" +
                    "<td style='width: 29%;' nowrap  title='" + temp[i]['ItemCodeX'] + "'>" + temp[i]['ItemNameX'] + "</td>" +
                    "<td style='width: 44%;padding-left: 30%;' nowrap>" + parnum + " </td>" +
                    "<td hidden><input id='count_child_" + temp[i]['ItemCodeX'] + "' value='" + temp[i]['ParQty'] + "'></td>" +
                    "</tr>";
                  $('#TableItemStock tbody').append(StrTR);
                  chk_row++;
                }
              } else {
                for (var i = 0; i < temp['countx']; i++) {
                  var parnum = '<input tyle="text"  style="text-align:center;"   class="form-control mypar_' + i + ' " onKeyPress="if(event.keyCode==13){SavePar(\'' + 2 + '\',\'' + i + '\',\'' + temp[i]['RowID'] + '\')}" value="' + temp[i]['ParQty'] + '" > ';
                  var chkHeadItem = "<input type='checkbox' name='myItem' id='headChk_" + chk_row + "' data-value='" + temp[i]['ItemCodeX'] + "'>";
                  var rowCount = $('#TableItemStock >tbody >tr').length;
                  StrTR = "<tr id='tr_mom_" + temp[i]['ItemCodeX'] + "' data-value='" + chk_row + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;padding-left:26px' nowrap>" + chkHeadItem + "</td>" +
                    "<td hidden>" + temp[i]['ItemCodeX'] + "</td>" +
                    "<td style='width: 29%;' nowrap title='" + temp[i]['ItemCodeX'] + "'>" + temp[i]['ItemNameX'] + "</td>" +
                    "<td style='width: 44%;padding-left: 30%;' nowrap>" + parnum + " </td>" +
                    "<td hidden><input id='count_child_" + temp[i]['ItemCodeX'] + "' value='" + temp[i]['ParQty'] + "'></td>" +
                    "</tr>";
                  $('#TableItemStock tbody').append(StrTR);
                  chk_row++;
                }
              }
              $('#chk_row').val(chk_row);

            } else if (temp['form'] == "setdateitemstock") {
              dialog.dialog("close");
            } else if (temp['form'] == "Submititemstock") {
              swal({
                title: '',
                text: '<?php echo $array['success'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 1500,
                // confirmButtonText: 'Ok'
              });
              setTimeout(function() {
                $("#TableItemStock tbody").empty();
                $("#parnum").val("");
                $("#department").val(1);
                ShowItem();
              }, 1500);

              // ShowItemStock();
            } else if (temp['form'] == "SaveUsageCode") {
              var Sel = temp["Sel"];
              var rowCount = $('#rowCount').val();
              //   if((Sel+1)==rowCount)
              //     $('.txtno_0').focus().select();
              // else
              $('.txtno_' + (Sel + 1)).focus().select();

              swal({
                title: '',
                text: '<?php echo $array['success'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 1000,
                // confirmButtonText: 'Ok'
              })
            } else if (temp['form'] == "SavePar") {
              swal({
                title: '',
                text: '<?php echo $array['success'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                showConfirmButton: false,
                timer: 1500,
                // confirmButtonText: 'Ok'
              })
            } else if (temp['form'] == "SelectItemStock") {
              var chk_row = $('#chk_row').val();
              $('#TableItemStock tbody').empty();
              if (temp['countpar'] == 0) {
                for (var i = 0; i < temp['countx']; i++) {
                  var parnum = '<input tyle="text"  style="text-align:center;"   class="form-control mypar_' + i + ' " onKeyPress="if(event.keyCode==13){SavePar(\'' + 1 + '\',\'' + i + '\',\'' + temp[i]['RowID'] + '\')}" value="' + temp[i]['ParQty'] + '" > ';
                  var chkHeadItem = "<input type='checkbox' name='myItem1' id='headChk_" + chk_row + "' data-value='" + temp[i]['ItemCodeX'] + "'>";
                  var rowCount = $('#TableItemStock >tbody >tr').length;
                  StrTR = "<tr id='tr_mom_" + temp[i]['ItemCodeX'] + "' data-value='" + chk_row + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;padding-left:26px' nowrap>" + chkHeadItem + "</td>" +
                    "<td hidden>" + temp[i]['ItemCodeX'] + "</td>" +
                    "<td style='width: 29%;' nowrap  title='" + temp[i]['ItemCodeX'] + "'>" + temp[i]['ItemNameX'] + "</td>" +
                    "<td style='width: 44%;padding-left: 30%;' nowrap>" + parnum + " </td>" +
                    // "<td style='width: 60%;' nowrap>"+temp[i]['ItemNameX']+"<span  class='ml-3 mr-2'>"+temp[i]['ParQty']+" <?php echo $array['items'][$language]; ?></span></td>"+
                    "<td hidden><input id='count_child_" + temp[i]['ItemCodeX'] + "' value='" + temp[i]['ParQty'] + "'></td>" +
                    "</tr>";
                  $('#TableItemStock tbody').append(StrTR);
                  chk_row++;
                }
              } else {
                for (var i = 0; i < temp['countx']; i++) {
                  var parnum = '<input tyle="text"  style="text-align:center;"   class="form-control mypar_' + i + ' " onKeyPress="if(event.keyCode==13){SavePar(\'' + 2 + '\',\'' + i + '\',\'' + temp[i]['RowID'] + '\')}" value="' + temp[i]['ParQty'] + '" > ';
                  var chkHeadItem = "<input type='checkbox' name='myItem' id='headChk_" + chk_row + "' data-value='" + temp[i]['ItemCodeX'] + "'>";
                  var rowCount = $('#TableItemStock >tbody >tr').length;
                  StrTR = "<tr id='tr_mom_" + temp[i]['ItemCodeX'] + "' data-value='" + chk_row + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;padding-left:26px' nowrap>" + chkHeadItem + "</td>" +
                    "<td hidden>" + temp[i]['ItemCodeX'] + "</td>" +
                    "<td style='width: 29%;' nowrap   title='" + temp[i]['ItemCodeX'] + "'>" + temp[i]['ItemNameX'] + "</td>" +
                    "<td style='width: 44%;padding-left: 30%;' nowrap>" + parnum + " </td>" +
                    "<td hidden><input id='count_child_" + temp[i]['ItemCodeX'] + "' value='" + temp[i]['ParQty'] + "'></td>" +
                    "</tr>";
                  $('#TableItemStock tbody').append(StrTR);
                  chk_row++;
                }
              }
              $('#chk_row').val(chk_row);
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

            if (temp['msg'] != "refresh") {
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
            } else {
              $('#TableItemStock tbody').empty();
            }
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
    //---------------HERE------------------//
    //---------------HERE------------------//
    //---------------HERE------------------//
  </script>
  <style media="screen">
    .select2-container--default .select2-selection--single {
      height: 38px;
      border: 1px solid #aaaaaa85;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
      line-height: 38px;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
      top: 5px;
    }

    @font-face {
      font-family: myFirstFont;
      src: url("../fonts/DB Helvethaica X.ttf");
    }


    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    body,
    #dialog,
    #buttonmodal,
    #datepickermodal {
      font-family: myFirstFont;
      font-size: 22px;
    }

    #buttonmodal,
    #datepickermodal {
      font-family: myFirstFont;
      font-size: 24px !important;
    }

    input,
    select {
      font-size: 24px !important;
    }

    th,
    td {
      font-size: 22px !important;
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
      border-top-left-radius: 6px;
    }

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 6px;
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
      font-size: 21px;
      color: #2c3e50;
      background: none;
      box-shadow: none !important;
      margin-left: -20px;
    }

    .mhee button:hover {
      color: #2c3e50;
      font-weight: bold;
      font-size: 26px;
      outline: none;
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

    .opacity {
      opacity: 0.5;
    }

    @media (min-width: 1200px) {
      #btn_margin {
        max-width: 14.333333%;
        margin-top: 27%;
      }

      #magin_cus {
        margin-top: 23%;
      }
    }

    #size_custom {
      font-size: 20px !important;
      width: 84px;
    }

    #magin_cus {
      margin-top: 204px;
      margin-left: -32px;
    }

    @media (min-width: 700px) and (max-width: 1199.99px) {
      #btn_margin {
        margin-top: 1%;
      }

      #size_custom {
        font-size: 20px !important;
      }

      #rotate_custom {
        transform: rotate(90deg);
      }

      #magin_cus {
        margin-top: -4%;
      }
    }
  </style>
</head>

<body id="page-top">
  <ol class="breadcrumb">

    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['system']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][7][$language]; ?></li>
  </ol>
  <input type="text" value='0' id='chk_row' hidden>
  <input type="hidden" id='itemArray'>
  <div id="wrapper">
    <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
      <i class="fas fa-angle-down"></i>
    </a>
    <div id="content-wrapper">
      <div class="row">
        <div class="container-fluid">
          <div class="card-body p-3" style="margin-top:16px;">

            <div class="row">
              <div class="col-xl-5 col-7">
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom:10px;">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['item'][$language]; ?></a>
                  </li>
                </ul>
                <div class="row">
                  <div class="col-12 mt-3">
                    <div class='form-group form-inline'>
                      <span class='text-left mr-sm-2 col-3'><?php echo $array['side'][$language]; ?></span>
                      <select class="form-control col-8" id="hotpital" onchange="getDepartment();"> </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mt-3">
                    <div class='form-group form-inline'>
                      <input onclick="center();" type="checkbox" disabled="true" id="xCenter" style="margin-top: -1.5%;margin-left: -1%;left: -1%;">
                      <span class='text-left mr-sm-2 col-3' style="margin-left: -1%;"><?php echo $array['xcenter'][$language]; ?></span>
                      <select class="form-control col-8 icon_select" id="HosCenter" disabled="true"> </select>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mt-3" id="form1">
                    <div class='form-group form-inline' id="form2">
                      <input type="checkbox" onclick="ShowItem(2)" id="xCenter2" style="margin-top: -1.5%;margin-left: -1%;left: -1%;">
                      <div class='col-3 mr-sm-2 text-left' style="margin-left: -1%;">
                        <span><?php echo $array['department'][$language]; ?></span>
                      </div>
                      <select disabled="true" class="form-control col-8 checkblank2 border icon_select select2 custom-select" id="department" onchange="removeClassBorder1(1);"> </select>
                      <label id="rem1" style="font-size: 180%;    margin-left: 1%;padding-top: 2%;"> * </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-12 mt-3" id="form3">
                    <div class='form-group form-inline' id="form4">
                      <div class='col-3 mr-sm-2 text-left'>
                        <span><?php echo $array['parnum'][$language]; ?></span>
                      </div>
                      <input type="text" class="form-control numonly col-8 checkblank2 borderred" autocomplete="off" onkeyup="removeClassBorder1()" id="parnum" name="parnum" value="" placeholder="<?php echo $array['parnum'][$language]; ?>">
                      <label id="rem2" style="font-size: 180%;    margin-left: 1%;padding-top: 2%;"> * </label>
                    </div>
                  </div>
                </div>


                <div class="row">
                  <div class="col-12 mt-3">
                    <div class='form-group form-inline'>
                      <div class='col-3 mr-sm-2 text-left'>
                        <span><?php echo $array['Searchitem'][$language]; ?></span>
                      </div>
                      <input type="text" autocomplete="off" class="form-control col-8" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchitem'][$language]; ?>">
                      <div class="menuMini  ml-2" hidden>
                        <div class="search_1 d-flex justify-content-start">
                          <button class="btn" onclick="ShowItem()">
                            <i class="fas fa-search mr-2"></i>
                            <?php echo $array['search'][$language]; ?>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom:14px;margin-top:-17%;margin-right: -135%;margin-left: 136%;">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['itemnew'][$language]; ?></a>
                  </li>
                </ul>
                <div class="row" style="padding-left: 137%;width: 300%;">
                  <div class="col-5">
                    <input type="text" class="form-control" autocomplete="off" name="searchitemstock" id="searchitemstock" placeholder="<?php echo $array['Searchitem'][$language]; ?>">
                  </div>
                  <div class="menuMini" hidden>
                    <div class="search_1 d-flex justify-content-start">
                      <button class="btn" onclick="ShowItemStock()">
                        <i class="fas fa-search mr-2"></i>
                        <?php echo $array['search'][$language]; ?>
                      </button>
                    </div>
                  </div>
                  <div class="menuMini  ml-2 mhee1">
                    <div class="circle5 d-flex justify-content-start">
                      <button class="btn" onclick="DeleteItem()">
                        <i class="fas fa-trash-alt mr-2"></i>
                        <?php echo $array['delete'][$language]; ?>
                      </button>
                    </div>
                  </div>
                  <div class="menuMini  ml-2 mhee1">
                    <div class="search_1 d-flex justify-content-start" style="margin-left: 15%;">
                      <button class="btn" onclick="ShowItemStock()">
                        <i class="fas fa-search mr-2"></i>
                        <?php echo $array['search'][$language]; ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="row p-2">
        <div class="col">
          <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" cellspacing="0" role="grid">
            <thead id="theadsum" style="font-size:11px;">
              <tr role="row">
                <th style='padding-left: 12px;'><input onclick="allitem();" id='selectAll' type="checkbox"></th>
                <th style='width: 28%;' nowrap hidden><?php echo $array['nono'][$language]; ?></th>
                <th style='width: 49%;' nowrap><?php echo $array['item'][$language]; ?></th>
                <th style='width: 26%;' nowrap><?php echo $array['total'][$language]; ?></th>
              </tr>
            </thead>
            <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:420px;">
            </tbody>
          </table>
        </div>
        <div class="col-1 d-flex justify-content-center">
          <div class="menuMini opacity" id="hover1">
            <div class="d-flex justify-content-center">
              <div class="circle4 d-flex justify-content-center">
                <button class="btn" onclick="Addtodoc()" id="bSave" disabled="true">
                  <i class="fas fa-file-import"></i>
                  <div>
                    <?php echo $array['addnewitem'][$language]; ?>
                  </div>
                </button>
              </div>
            </div>
          </div>
        </div>
        <div class="col">
          <!-- <ul class="nav nav-tabs" id="myTab" role="tablist" style="margin-bottom:14px;margin-top:-17%">
                  <li class="nav-item">
                    <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['itemnew'][$language]; ?></a>
                  </li>
                </ul> -->
          <!-- <div class="row">
                  <div class="col-6">
                    <input type="text" class="form-control" autocomplete="off" name="searchitemstock" id="searchitemstock" placeholder="<?php echo $array['searchplace'][$language]; ?>" >
                  </div>
                  <div class="menuMini" hidden>
                    <div class="search_1 d-flex justify-content-start">
                      <button class="btn"  onclick="ShowItemStock()" >
                        <i class="fas fa-search mr-2"></i>
                        <?php echo $array['search'][$language]; ?>
                      </button>
                    </div>
                  </div>
                  <div class="menuMini  ml-2">
                    <div class="circle5 d-flex justify-content-start">
                      <button class="btn"  onclick="DeleteItem()" >
                        <i class="fas fa-trash-alt mr-2"></i>
                        <?php echo $array['delete'][$language]; ?>
                      </button>
                    </div>
                  </div>
                </div> -->

          <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemStock" cellspacing="0" role="grid">
            <input type="hidden" id="rowCount">
            <thead id="theadsum" style="font-size:11px;">
              <tr role="row">
                <th style='width: 10%;' nowrap>&nbsp;</th>
                <th style='width: 24%;' nowrap hidden><?php echo $array['nono'][$language]; ?></th>
                <th style='width:60%;' nowrap><?php echo $array['item'][$language]; ?></th>
                <!-- <th style='width: 11%;' nowrap>Par</th> -->
                <th style='width: 30%;' id="showcenter1" nowrap><?php echo $array['par'][$language]; ?></th>
                <th style='width: 30%;' id="showcenter2" hidden nowrap><?php echo $array['par'][$language]; ?></th>
              </tr>
            </thead>
            <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:420px;">
            </tbody>
          </table>

        </div>
      </div>
    </div>
  </div>
  <!-- Dialog Modal-->
  <div id="dialog" title="" style="z-index:999999 !important;">
    <div class="container">
      <div class="row" style="padding:5px;">
        <div class="col-md-5">
          <?php echo $array['expireday'][$language]; ?>
        </div>
        <div class="col-md-3">
        </div>
        <div class="col-md-4">

        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          <div class="row">
            <div class="input-group">
              <input type="text" style="margin-left:15px;" class="form-control datepicker-here" id="datepickermodal" data-language='en' data-date-format='dd/mm/yyyy' value="<?php echo date('d/m/Y'); ?>" readonly>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <input type="text" id="txtdpk">
          <input type="text" id="txtrow">
          <button type="button" style="margin-top:0; width:100px;" class="btn btn-warning" name="button" id="buttonmodal" onclick="Setdate();"><?php echo $array['save'][$language]; ?></button>

        </div>
      </div>

    </div>
  </div>

  <div id="page-down">
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
  <script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>

</body>

</html>