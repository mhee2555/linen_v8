<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$HptName = $_SESSION['HptName'];

require 'updatemouse.php';

if ($Userid == "") {
  header("location:../index.html");
}
if (empty($_SESSION['lang'])) {
  $language = 'th';
} else {
  $language = $_SESSION['lang'];
}

header('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json, TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2, TRUE);

// var_dump(); die;
?>

<!DOCTYPE html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?php echo $array['newwash'][$language]; ?></title>

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

    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <script type="text/javascript"> jqui = jQuery.noConflict(true);</script>

    <link href="../dist/css/sweetalert2.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
    <!-- custome style -->
    <link href="../css/responsive.css" rel="stylesheet">
    <link href="../css/menu_custom.css" rel="stylesheet">
    <!-- =================================== -->
    <?php if ($language == 'th') { ?>
      <script src="../datepicker/dist/js/datepicker.js"></script>
    <?php } else if ($language == 'en') { ?>
      <script src="../datepicker/dist/js/datepicker-en.js"></script>
    <?php } ?>
    <!-- =================================== -->

    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
    <script src="../datepicker/dist/js/datepicker.th.js"></script>

    <script type="text/javascript">
      var summary = [];
      var xItemcode;
      var RowCnt = 0;

      $(document).ready(function(e) {
        parent.OnLoadPage();
        var PmID = <?php echo $PmID; ?>;
        if (PmID == 1 || PmID == 6) {
          $('#hotpital').removeClass('icon_select');
        }

        $('#searchdocument').keyup(function(e) {
          if (e.keyCode == 13) {
            ShowDocument(1);
          }
        });

        $('#searchitem').keyup(function(e) {
          if (e.keyCode == 13) {
            ShowItem();
          }
        });

        $('#rem1').hide();
        $('#rem2').hide();
        $('#rem3').hide();

        $('.only').on('input', function() {
          this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
        });
        //  console.log(window.parent.location.href);
        OnLoadPage();
        getfactory();
        // CreateDocument();
        //==============================
        $('.TagImage').bind('click', {
          imgId: $(this).attr('id')
        }, function(evt) {
          alert(evt.imgId);
        });
        var userid = '<?php echo $Userid; ?>';
        if (userid != "" && userid != null && userid != undefined) {
          var dept = '';
          var data = {
            'STATUS': 'getDocument',
            'DEPT': dept
          };

          // console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }

      }).click(function(e) {
        parent.afk();
      }).keyup(function(e) {
        parent.afk();
      });

      jqui(document).ready(function($) {
        dialogUsageCode = jqui("#dialogUsageCode").dialog({
          autoOpen: false,
          height: 680,
          width: 1200,
          modal: true,
          buttons: {
            "<?php echo $array['close'][$language]; ?>": function() {
              dialogUsageCode.dialog("close");
            }
          },

          close: function() {
            console.log("close");
          }
        });

      });

      //function 
      function OpenDialogItem() {
        var docno = $("#docno").val();
        if (docno != "") {
          $("#TableItem tbody").empty();
          // dialogItemCode.dialog( "open" );
          $('#dialogItemCode').modal('show');

        }
        ShowItem();
      }

      function OpenDialogUsageCode(itemcode) {
        xItemcode = itemcode;
        var docno = $("#docno").val();
        if (docno != "") {
          dialogItemCode.dialog("close");
          dialogUsageCode.dialog("open");
          $("#TableItem tbody").empty();
          ShowUsageCode();
        }
      }

      function ShowUsageCode() {
        var docno = $("#docno").val();
        var data = {
          'STATUS': 'ShowUsageCode',
          'docno': docno,
          'xitem': xItemcode
        };
        senddata(JSON.stringify(data));
      }

      function resetradio(row) {
        var previousValue = $('.checkrow_' + row).attr('previousValue');
        var name = $('.checkrow_' + row).attr('name');
        if (previousValue == 'checked') {
          $('#bDelete').attr('disabled', true);
          $('#bDelete2').addClass('opacity');
          $('#hover3').removeClass('mhee');
          $('.checkrow_' + row).removeAttr('checked');
          $('.checkrow_' + row).attr('previousValue', false);
          $('.checkrow_' + row).prop('checked', false);
        } else {
          $('#bDelete').attr('disabled', false);
          $('#hover3').addClass('mhee');
          $('#bDelete2').removeClass('opacity');
          $("input[name=" + name + "]:radio").attr('previousValue', false);
          $('.checkrow_' + row).attr('previousValue', 'checked');
        }
      }

      function dis2(row) {
        if ($('#checkrow_' + row).prop("checked") == true) {
          var countcheck2 = Number($("#countcheck").val()) + 1;
          $("#countcheck").val(countcheck2);
          $('#bSaveadd').attr('disabled', false);
          $('#bSaveadd2').removeClass('opacity');
          // $('#checkrow_'+row).prop('checked', true);
          $('#checkrow_' + row).attr('previousValue', 'checked');
        } else if ($('#checkrow_' + row).prop("checked") == false) {
          var countcheck3 = Number($("#countcheck").val()) - 1;
          $("#countcheck").val(countcheck3);
          if (countcheck3 == 0) {
            $('#bSaveadd').attr('disabled', true);
            $('#bSaveadd2').addClass('opacity');
            $('.checkrow_' + row).removeAttr('checked');
            $("#countcheck").val(countcheck3);
          }
        }

      }

      function DeleteItem() {
        var docno = $("#docno").val();
        var xrow = $("#checkrow:checked").val();
        xrow = xrow.split(",");
        swal({
          title: "<?php echo $array['confirmdelete'][$language];?>",
          text: "<?php echo $array['confirm1'][$language];?>",
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
            var data = {
              'STATUS': 'DeleteItem',
              'rowid': xrow[0],
              'DocNo': docno
            };
            senddata(JSON.stringify(data));
            $('#bDelete').attr('disabled', true);
            $('#bDelete2').addClass('opacity');
            $('#hover3').removeClass('mhee');
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }
        })
      }

      function selectAll() {
        var select_all = document.getElementById('selectAll'); //select all checkbox
        var checkboxes = document.getElementsByClassName("myDepName"); //checkbox items
        select_all.addEventListener("change", function(e) {
          for (i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = select_all.checked;
            $('#btn_confirm').attr('disabled', false);
          }
        });
        for (var i = 0; i < checkboxes.length; i++) {
          checkboxes[i].addEventListener('change', function(e) { //".checkbox" change 
            if (this.checked == false) {
              select_all.checked = false;
            }
            if (document.querySelectorAll('.checkbox:checked').length == checkboxes.length) {
              select_all.checked = true;
            }
          });
        }
        var numRow = $("#countcheck").val();
        if (numRow == i) {
          $("#countcheck").val(0);
          $('#btn_confirm').attr('disabled', true);
        } else {
          $("#countcheck").val(i);
          $('#btn_confirm').attr('disabled', false);
        }
      }

      function Blankinput() {
        $('#docno').val("");
        $('#docdate').val("");
        $('#recorder').val("");
        $('#timerec').val("");
        $('#wTotal').val("");
        getDepartment();
        OnLoadPage();
      }

      function CancelDocument() {
        var docno = $("#docno").val();
        if (docno != "") {
          swal({
            title: "<?php echo $array['confirmcancel'][$language]; ?>",
            text: " " + docno + " ",
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
              CancelBill();
            } else if (result.dismiss === 'cancel') {
              swal.close();
            }
          })
        }
      }

      //======= On create =======
      function getfactory() {
        $('#hotpital').removeClass('border-danger');
        $('#rem3').hide();
        var lang = '<?php echo $language; ?>';
        var hotpital = $('#hotpital').val();
        var data = {
          'STATUS': 'getfactory',
          'hotpital': hotpital,
          'lang': lang
        };
        senddata(JSON.stringify(data));
      }

      function OnLoadPage() {
        var lang = '<?php echo $language; ?>';
        Hotp = "<?php echo $HptCode; ?>";
        var data = {
          'STATUS': 'OnLoadPage',
          'Hotp': Hotp,
          'lang': lang,
          'hotpital': hotpital
        };
        senddata(JSON.stringify(data));
        $('#isStatus').val(0)
      }

      function getDepartment() {
        $('#hotpital').removeClass('border-danger');
        $('#rem3').hide();
        var Hotp = $('#Hos2 option:selected').attr("value");
        if (Hotp == '' || Hotp == undefined) {
          Hotp = $('#getHot').val();
        };
        var data = {
          'STATUS': 'getDepartment',
          'Hotp': Hotp
        };

        senddata(JSON.stringify(data));
      }

      function confirmDep(ItemCode, ItemName) {
        var DocNo = $('#docno').val();
        var HptCode = $('#hotpital').val();
        swal({
          title: " ",
          text: " <?php echo $array['save'][$language]; ?>",
          type: "success",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 500,
          closeOnConfirm: false
        });
        setTimeout(() => {
          var data = {
            'STATUS': 'confirmDep',
            'DocNo': DocNo,
            'HptCode': HptCode,
            'ItemCode': ItemCode
          }
          senddata(JSON.stringify(data));
          // $('#ModalDepartment').modal('toggle');
        }, 500);
      }

      function ShowDocument(selecta) {
        var DocNo = $('#docno').val();
        var process = $('#process').val();
        var Hotp = $('#Hos2 option:selected').attr("value");
        if(Hotp == ""){
          Hotp = $('#hotpital option:selected').attr("value");
        }
        var searchdocument = $('#searchdocument').val();
        var datepicker1 = $('#datepicker1').val();
        var lang = '<?php echo $language; ?>';
        if (datepicker1 != "") {
          if (lang == 'th') {
            datepicker1 = datepicker1.substring(6, 10) - 543 + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
          } else if (lang == 'en') {
            datepicker1 = datepicker1.substring(6, 10) + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
          }
        } else {
          datepicker1 = "";
        }
        if (process == 0) {
          process = 'chkpro';
        } else if (process == 1) {
          process = 'chkpro1';
        } else if (process == 2) {
          process = 'chkpro2';
        } else if (process == 3) {
          process = 'chkpro3';
        }
        if (typeof searchdocument == 'undefined') searchdocument = "";
        var data = {
          'STATUS': 'ShowDocument',
          'xdocno': searchdocument,
          'selecta': selecta,
          'Hotp': Hotp,
          'datepicker1': datepicker1,
          'DocNo': DocNo,
          'process': process
        };
        senddata(JSON.stringify(data));
      }

      function ShowDocument_sub() {
        var searchdocument = $('#searchdocument').val();
        if (typeof searchdocument == 'undefined') searchdocument = "";
        var deptCode = $('#Dep2 option:selected').attr("value");
        if (typeof deptCode == 'undefined') deptCode = "1";
        var data = {
          'STATUS': 'ShowDocument',
          'xdocno': searchdocument,
          'deptCode': deptCode
        };
        senddata(JSON.stringify(data));
      }

      function ShowItem() {
        var Hotp = $('#hotpital option:selected').attr("value");
        var deptCode = $('#department option:selected').attr("value");
        if (typeof deptCode == 'undefined') deptCode = "1";
        var searchitem = $('#searchitem').val();
        var data = {
          'STATUS': 'ShowItem',
          'xitem': searchitem,
          'deptCode': deptCode,
          'Hotp': Hotp
        };
        senddata(JSON.stringify(data));
      }

      function SelectDocument() {
        var selectdocument = "";
        $("#checkdocno:checked").each(function() {
          selectdocument = $(this).val();
        });
        var deptCode = $('#Dep2 option:selected').attr("value");
        if (typeof deptCode == 'undefined') deptCode = "1";
        var data = {
          'STATUS': 'SelectDocument',
          'xdocno': selectdocument
        };
        senddata(JSON.stringify(data));
      }

      function unCheckDocDetail() {
        if ($('input[name="checkdocdetail"]:checked').length == $('input[name="checkdocdetail"]').length) {
          $('input[name="checkAllDetail').prop('checked', true);
        } else {
          $('input[name="checkAllDetail').prop('checked', false);
        }
      }

      function ShowDetail() {
        var docno = $("#docno").val();
        var data = {
          'STATUS': 'ShowDetailDoc',
          'DocNo': docno
        };
        senddata(JSON.stringify(data));
      }

      function CancelBill() {
        var docno = $("#docno").val();
        var data = {
          'STATUS': 'CancelBill',
          'DocNo': docno,
          'selecta': '0'
        };
        senddata(JSON.stringify(data));
        $('#profile-tab').tab('show');
        ShowDocument(3);
        Blankinput();
        $('#factory').attr('disabled', false);
      }

      function swithChecked(i) {
        $('#btn_confirm').attr('disabled', false);
        $("#selectAll").change(function() {
          var status = this.checked;
          $('.myDepName').each(function() {
            this.checked = status;
          });
        });
        $('.unchk').change(function() {
          if (this.checked == false) {
            $("#selectAll")[0].checked = false;
          }
          if ($('.myDepName:checked').length == $('.myDepName').length) {
            $("#selectAll")[0].checked = true;
            $('#btn_confirm').attr('disabled', false);
          }
        });

        if ($('#checkDep_' + i).prop("checked") == true) {
          var countcheck2 = Number($("#countcheck").val()) + 1;
          $("#countcheck").val(countcheck2);
          $('#btn_confirm').attr('disabled', false);
          $('#checkDep_' + i).attr('previousValue', 'checked');
        } else if ($('#checkDep_' + i).prop("checked") == false) {
          var countcheck3 = Number($("#countcheck").val()) - 1;
          $("#countcheck").val(countcheck3);
          if (countcheck3 == 0) {
            $('#btn_confirm').attr('disabled', true);
            $('.checkDep_' + i).removeAttr('checked');
            $("#countcheck").val(countcheck3);
          }
        }
      }

      function showDep(ItemCode, ItemName) {
        var data = {
          'STATUS': 'showDep',
          'ItemCode': ItemCode,
          'ItemName': ItemName
        }
        senddata(JSON.stringify(data));
      }

      function getImport(Sel) {
        var docno = $("#docno").val();
        var iArray = [];
        var qtyArray = [];
        var chkArray = [];
        var weightArray = [];
        var unitArray = [];
        var i = 0;
        if (Sel == 1) {
          $(".checkitem:checked").each(function() {
            iArray.push($(this).val());
          });
        } else {
          $("#checkitemSub:checked").each(function() {
            iArray.push($(this).val());
          });
        }
        for (var j = 0; j < iArray.length; j++) {
          if (Sel == 1)
            chkArray.push($("#RowID" + iArray[j]).val());
          else
            chkArray.push($("#RowIDSub" + iArray[j]).val());
          qtyArray.push($("#iqty" + iArray[j]).val());
          weightArray.push($("#iweight" + iArray[j]).val());
          unitArray.push($("#iUnit_" + iArray[j]).val());
        }
        var xrow = chkArray.join(',');
        var xqty = qtyArray.join(',');
        var xweight = weightArray.join(',');
        var xunit = unitArray.join(',');
        var Hotp = $('#hotpital option:selected').attr("value");
        if (typeof Hotp == 'undefined') Hotp = "<?php echo $HptCode; ?>";
        $('#TableDetail tbody').empty();
        var data = {
          'STATUS': 'getImport',
          'xrow': xrow,
          'xqty': xqty,
          'xweight': xweight,
          'xunit': xunit,
          'DocNo': docno,
          'Sel': Sel,
          'Hotp': Hotp
        };
        console.log(data);
        senddata(JSON.stringify(data));
        ShowItem();
      }

      var isChecked1 = false;
      var isChecked2 = false;

      function getCheckAll(sel) {
        if (sel == 0) {
          isChecked1 = !isChecked1;
          $('input[name="checkdocno"]').each(function() {
            this.checked = isChecked1;
          });
          getDocDetail();
        } else {
          isChecked2 = !isChecked2;
          $('input[name="checkdocdetail"]').each(function() {
            this.checked = isChecked2;
          });
        }
      }

      function convertUnit(rowid, selectObject) {
        var docno = $("#docno").val();
        var data = selectObject.value;
        var chkArray = data.split(",");
        var weight = $('#weight_' + chkArray[0]).val();
        var qty = $('#qty1_' + chkArray[0]).val();
        var oleqty = $('#OleQty_' + chkArray[0]).val();
        qty = oleqty * chkArray[2];
        $('#qty1_' + chkArray[0]).val(qty);
        var data = {
          'STATUS': 'updataDetail',
          'Rowid': rowid,
          'DocNo': docno,
          'unitcode': chkArray[1],
          'qty': qty
        };
        senddata(JSON.stringify(data));
      }

      function checkblank() {
        $('.checkblank').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).addClass('border-danger');
            $('#rem1').show().css("color", "red");
          } else {
            $(this).removeClass('border-danger');
            $('#rem1').hide();
          }
        });
      }

      function checkblank2() {
        $('.checkblank2').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).addClass('border-danger');
            $('#rem2').show().css("color", "red");
          } else {
            $(this).removeClass('border-danger');
            $('#rem2').hide();
          }
        });
      }

      function checkblank3() {
        $('.checkblank3').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).addClass('border-danger');
            $('#rem3').show().css("color", "red");
          } else {
            $(this).removeClass('border-danger');
            $('#rem3').hide();
          }
        });
      }

      function removeClassBorder1() {
        $('#department').removeClass('border-danger');
        $('#rem1').hide();
      }

      function removeClassBorder2() {
        $('#factory').removeClass('border-danger');
        $('#rem2').hide();
      }

      function CreateDocument() {
        var userid = '<?php echo $Userid; ?>';
        var hotpCode = $('#hotpital option:selected').attr("value");
        var FacCode = $('#factory option:selected').attr("value");
        if (FacCode == '' || hotpCode == '') {
          checkblank2();
          checkblank3();
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
          swal({
            title: "<?php echo $array['confirmdoc'][$language]; ?>",
            text: "<?php echo $array['side'][$language]; ?> : " + $('#hotpital option:selected').text(),
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: "btn-danger",
            confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
            cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            closeOnConfirm: false,
            closeOnCancel: false,
            showCancelButton: true
          }).then(result => {
            if (result.value) {
              var data = {
                'STATUS': 'CreateDocument',
                'hotpCode': hotpCode,
                'userid': userid,
                'FacCode': FacCode
              };
              senddata(JSON.stringify(data));
              var word = '<?php echo $array['save'][$language]; ?>';
              var changeBtn = "<i class='fa fa-save'></i>";
              changeBtn += "<div>" + word + "</div>";
              $('#icon_edit').html(changeBtn);
            } else if (result.dismiss === 'cancel') {
              swal.close();
            }
          })
        }
      }

      function canceldocno(docno) {
        swal({
          title: "<?php echo $array['confirmdelete'][$language]; ?>",
          text: "<?php echo $array['confirmdelete1'][$language]; ?>" + docno + "<?php echo $array['confirmdelete2'][$language]; ?>",
          type: "warning",
          showCancelButton: true,
          confirmButtonClass: "btn-danger",
          confirmButtonText: "<?php echo $array['cancel'][$language]; ?>",
          cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          closeOnConfirm: false,
          closeOnCancel: false,
          showCancelButton: true
        }).then(result => {
          var data = {
            'STATUS': 'CancelDocNo',
            'DocNo': docno
          };
          senddata(JSON.stringify(data));
          getSearchDocNo();
        })
      }

      function addnum(cnt) {
        var add = parseInt($('#iqty' + cnt).val()) + 1;
        if ((add > 0) && (add <= 500)) {
          $('#iqty' + cnt).val(add);
        }
      }

      function subtractnum(cnt) {
        var sub = parseInt($('#iqty' + cnt).val()) - 1;
        if ((sub > 0) && (sub <= 500)) {
          $('#iqty' + cnt).val(sub);
        }
      }

      function addnum1(rowid, cnt, unitcode) {
        var Dep = $("#Dep_").val();
        var docno = $("#docno").val();
        var add = parseInt($('#qty1_' + cnt).val()) + 1;
        var newQty = parseInt($('#OleQty_' + cnt).val()) + 1;
        var isStatus = $("#IsStatus").val();
        if (isStatus == 0) {
          if ((add >= 0) && (add <= 500)) {
            $('#qty1_' + cnt).val(add);
            $('#OleQty_' + cnt).val(newQty);
          }
          var data = {
            'STATUS': 'UpdateDetailQty',
            'Rowid': rowid,
            'DocNo': docno,
            'Qty': add,
            'OleQty': newQty,
            'unitcode': unitcode
          };
          senddata(JSON.stringify(data));
        }
      }

      function subtractnum1(rowid, cnt, unitcode) {
        var Dep = $("#Dep_").val();
        var docno = $("#docno").val();
        var sub = parseInt($('#qty1_' + cnt).val()) - 1;
        var newQty = parseInt($('#OleQty_' + cnt).val()) - 1;
        var isStatus = $("#IsStatus").val();
        if (isStatus == 0) {
          if ((sub >= 0) && (sub <= 500)) {
            $('#qty1_' + cnt).val(sub);
            $('#OleQty_' + cnt).val(newQty);
          }
          var data = {
            'STATUS': 'UpdateDetailQty',
            'Rowid': rowid,
            'DocNo': docno,
            'Qty': sub,
            'OleQty': newQty,
            'unitcode': unitcode
          };
          senddata(JSON.stringify(data));
        }
      }

      function updateWeight(row, rowid) {
        var docno = $("#docno").val();
        var weight = $("#myweight_" + row).val();
        var price = 0;
        var isStatus = $("#IsStatus").val();
        $('#myweight_' + row).removeClass('border border-danger');
        if (isStatus == 0) {
          var data = {
            'STATUS': 'UpdateDetailWeight',
            'Rowid': rowid,
            'DocNo': docno,
            'Weight': weight,
            'Price': price
          };
          senddata(JSON.stringify(data));
        }
      }

      function SaveBill() {
        var count = 0;
        var chk_weight = document.getElementsByClassName("chk_weight"); //checkbox items
        var docno = $("#docno").val();
        var isStatus = $("#IsStatus").val();
        var dept = $("#Dep2").val();
        var DepCode = $("#department").val();
        var FacCode = $("#factory").val();

        // alert( isStatus );
        if (isStatus == 1 || isStatus == 3 || isStatus == 4) {
          isStatus = 0;
        } else {
          isStatus = 1;
        }
        if (isStatus == 1) {
          if (docno != "") {
            // for(i=0;i<chk_weight.length; i++)
            // {
            //   var chk = $('#myweight_'+i).val();
            //   if(chk == "" || chk==0)
            //   {
            //     $('#myweight_'+i).addClass('border border-danger');
            //     count++;
            //   }
            // }
            // if(count==0)
            // {
            swal({
              title: "<?php echo $array['confirmsave'][$language]; ?>",
              text: "<?php echo $array['docno'][$language]; ?>: " + docno + "",
              type: "warning",
              showCancelButton: true,
              confirmButtonClass: "btn-danger",
              confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
              cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
              confirmButtonColor: '#d33',
              cancelButtonColor: '#3085d6',
              closeOnConfirm: false,
              closeOnCancel: false,
              showCancelButton: true,
              allowOutsideClick: false,
              allowEscapeKey: false
            }).then(result => {
              swal({
                title: '',
                text: '<?php echo $array['savesuccess'][$language]; ?>',
                type: 'success',
                showCancelButton: false,
                showConfirmButton: false,
                timer: 1500,
              });
              if (result.value) {
                var data = {
                  'STATUS': 'SaveBill',
                  'DocNo': docno,
                  'isStatus': isStatus,
                  'deptCode': dept,
                  'FacCode': FacCode

                };
                senddata(JSON.stringify(data));
                $('#profile-tab').tab('show');
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', true);
                $("#bCancel").prop('disabled', true);
                Blankinput();
                ShowDocument();
                $('#factory').attr('disabled', false);
              } else if (result.dismiss === 'cancel') {
                swal.close();
              }
            })
            // }
            // else
            // {
            //   swal({
            //     title: " ",
            //     text:  " <?php echo $array['insert_form'][$language]; ?>",
            //     type: "warning",
            //     showCancelButton: false,
            //     showConfirmButton: false,
            //     timer: 1000,
            //     closeOnConfirm: true
            //   });
            // }
          }
        } else {
          $("#bImport").prop('disabled', false);
          $("#bSave").prop('disabled', false);
          // $("#bCancel").prop('disabled', false);
          $("#bImport2").removeClass('opacity');
          $("#bSave2").removeClass('opacity');
          // $("#bCancel2").removeClass('opacity');
          var word = '<?php echo $array['save'][$language]; ?>';
          var changeBtn = "<i class='fa fa-save'></i>";
          changeBtn += "<div>" + word + "</div>";
          $('#icon_edit').html(changeBtn);
          $("#IsStatus").val("0");
          $("#docno").prop('disabled', false);
          $("#docdate").prop('disabled', false);
          $("#recorder").prop('disabled', false);
          $("#timerec").prop('disabled', false);
          $("#total").prop('disabled', false);
          $('.chk_edit').attr('disabled', false);
        }
      }

      function UpdateRefDocNo() {
        var docno = $("#docno").val();
        var RefDocNo = $("#RefDocNo").val();
        var data = {
          'STATUS': 'UpdateRefDocNo',
          'xdocno': docno,
          'RefDocNo': RefDocNo
        };
        senddata(JSON.stringify(data));
      }

      function show_btn(DocNo) {
        if (DocNo != undefined || DocNo != '') {
          $(btn_show).attr('disabled', false);
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
          if (dismiss === 'cancel') {}
        })
      }

      function updateQty(RowID, i, Weight) {
        var newQty = $('#qty1_' + i).val();
        var DocNo = $('#docno').val();

        if (newQty == '' || newQty <= 0) {
          $('#qty1_' + i).val(0);
          newQty = 0;
        }

        var totalweight = parseFloat(newQty * Weight).toFixed(2);

        var data = {
          'STATUS': 'updateQty',
          'RowID': RowID,
          'newQty': newQty,
          'DocNo': DocNo
        }
        senddata(JSON.stringify(data));
      }

      function dis() {
        $('.dis').attr('disabled', false);
      }
      //end function 


      function senddata(data) {
        var form_data = new FormData();
        form_data.append("DATA", data);
        var URL = '../process/newwash.php';
        $.ajax({
          url: URL,
          dataType: 'text',
          cache: false,
          contentType: false,
          processData: false,
          data: form_data,
          type: 'post',
          success: function(result) {
            try {
              var temp = $.parseJSON(result);
            } catch (e) {
              console.log('Error#542-decode error');
            }
            if (temp["status"] == 'success') {
              if (temp["form"] == 'OnLoadPage') {
                $("#Hos2").empty();
                $("#hotpital").empty();
                var PmID = <?php echo $PmID; ?>;
                var HptCode = '<?php echo $HptCode; ?>';
                $('#getHot').val(temp[0]['HptCode']);

                if (temp[0]['PmID'] != 2 && temp[0]['PmID'] != 3 && temp[0]['PmID'] != 7 && temp[0]['PmID'] != 5) {
                  var Str1 = "<option value='' selected><?php echo $array['selecthospital'][$language]; ?></option>";
                } else {
                  var Str1 = "";
                  $('#Hos2').attr('disabled', true);
                  $('#Hos2').addClass('icon_select');
                }

                for (var i = 0; i < temp["Row"]; i++) {
                  var Str = "<option value=" + temp[i]['HptCode'] + " id='getHot_" + i + "'>" + temp[i]['HptName'] + "</option>";
                  Str1 += "<option value=" + temp[i]['HptCode1'] + ">" + temp[i]['HptName1'] + "</option>";
                }

                $("#hotpital").append(Str1);
                $("#Hos2").append(Str1);
                $("#hotpital").val(HptCode);
                // getDepartment();
              } else if (temp["form"] == 'getfactory') {
                $("#factory").empty();
                var Str = "<option value='' selected><?php echo $array['selectfactory'][$language]; ?></option>";
                for (var i = 0; i < temp["Rowx"]; i++) {
                  Str += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
                }
                $("#factory").append(Str);
              } else if (temp["form"] == 'getDepartment') {
                $("#department").empty();
                $("#Dep2").empty();
                var Str2 = "<option value='' selected><?php echo $array['selectdep'][$language]; ?></option>";

                for (var i = 0; i < temp["Row"]; i++) {
                  Str2 += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                }

                $("#Dep2").append(Str2);
                $("#department").append(Str2);
              } else if ((temp["form"] == 'CreateDocument')) {
                swal({
                  title: "<?php echo $array['createdocno'][$language]; ?>",
                  text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                  type: "success",
                  showCancelButton: false,
                  showConfirmButton: false,
                  timer: 1000,
                  confirmButtonText: 'Ok',
                  closeOnConfirm: false
                });
                setTimeout(() => {
                  OpenDialogItem();
                }, 1200);
                $('#bCreate').attr('disabled', true);
                $('#hover1').removeClass('mhee');
                $('#bCreate2').addClass('opacity');
                $("#docno").val(temp[0]['DocNo']);
                $("#docdate").val(temp[0]['DocDate']);
                $("#recorder").val(temp[0]['Record']);
                $("#timerec").val(temp[0]['RecNow']);
                $("#TableItemDetail tbody").empty();
                $("#wTotal").val(0);
                $('#bSave').attr('disabled', false);
                $('#bImport').attr('disabled', false);
                $('#bCancel').attr('disabled', false);
                $('#factory').attr('disabled', true);
                $('#docdate').attr('disabled', true);
                $('#factory').addClass('icon_select');
                $('#bSave2').removeClass('opacity');
                $('#bImport2').removeClass('opacity');
                $('#bCancel2').removeClass('opacity');
                $('#hover2').addClass('mhee');
                $('#hover4').addClass('mhee');
                $('#hover5').addClass('mhee');
              } else if (temp["form"] == 'ShowDocument') {
                $("#TableDocument tbody").empty();
                $("#TableItemDetail tbody").empty();
                if (temp['Count'] > 0) {
                  $("#department").val(temp[0]['DepCode']);
                  for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                    var rowCount = $('#TableDocument >tbody >tr').length;
                    var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno' onclick='show_btn(\"" + temp[i]['DocNo'] + "\");' value='" + temp[i]['DocNo'] + "' ><span class='checkmark'></span></label>";
                    var Status = "";
                    var Style = "";
                    if (temp[i]['IsStatus'] == 1 || temp[i]['IsStatus'] == 3 || temp[i]['IsStatus'] == 4) {
                      Status = "completed";
                      Style = "style='width: 10%;color: #20B80E;'";
                    } else {
                      Status = "on process";
                      Style = "style='width: 10%;color: #3399ff;'";
                    }
                    if (temp[i]['IsStatus'] == 9) {
                      Status = "cancel";
                      Style = "style='width: 10%;color: #ff0000;'";
                    }
                    $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                      "<td style='width: 10%;' nowrap>" + chkDoc + "</td>" +
                      "<td style='width: 8%;' nowrap>" + temp[i]['DocDate'] + "</td>" +
                      "<td style='width: 19%;padding-left: 5%;' nowrap>" + temp[i]['DocNo'] + "</td>" +
                      "<td style='width: 15%; overflow: hidden; text-overflow: ellipsis;' nowrap>" + temp[i]['Record'] + "</td>" +
                      "<td style='width: 10%;' nowrap>" + temp[i]['RecNow'] + "</td>" +
                      "<td style='width: 8%;' nowrap>" + temp[i]['Total'] + "</td>" +
                      "<td  style='width: 16%;overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['FacName'] + "' nowrap>" + temp[i]['FacName'] + "</td>" +
                      "<td " + Style + " nowrap>" + Status + "</td>" +
                      "</tr>";
                    if (rowCount == 0) {
                      $("#TableDocument tbody").append($StrTr);
                    } else {
                      $('#TableDocument tbody:last-child').append($StrTr);
                    }
                  }
                } else {
                  var Str = "<tr width='100%'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                  swal({
                    title: '',
                    text: '<?php echo $array['notfoundmsg'][$language]; ?>',
                    type: 'warning',
                    showCancelButton: false,
                    showConfirmButton: false,
                    timer: 700,
                  });
                  $("#TableDocument tbody").html(Str);
                }
              } else if (temp["form"] == 'SelectDocument') {
                var Str = "<option value='' selected><?php echo $array['selectfactory'][$language]; ?></option>";
                for (var i = 0; i < temp["Rowx"]; i++) {
                  Str += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
                }
                $("#factory").html(Str);
                $('#bCreate').attr('disabled', true);
                $('#hover1').removeClass('mhee');
                $('#bCreate2').addClass('opacity');
                $('#home-tab').tab('show')
                $("#TableItemDetail tbody").empty();
                $("#hotpital").val(temp[0]['HptName']);
                $("#hotpital").prop('disabled', true);
                $('#hotpital').addClass('icon_select');
                $('#factory').addClass('icon_select');
                $("#factory").val(temp[0]['FacCode2']);
                $("#docno").val(temp[0]['DocNo']);
                $("#docdate").val(temp[0]['DocDate']);
                $("#recorder").val(temp[0]['Record']);
                $("#timerec").val(temp[0]['RecNow']);
                $("#wTotal").val(temp[0]['Total']);
                $("#IsStatus").val(temp[0]['IsStatus']);
                $('#factory').attr('disabled', true);
                if (temp[0]['IsStatus'] == 0) {
                  var word = '<?php echo $array['save'][$language]; ?>';
                  var changeBtn = "<i class='fa fa-save'></i>";
                  changeBtn += "<div>" + word + "</div>";
                  $('#icon_edit').html(changeBtn);
                  $("#bImport").prop('disabled', false);
                  $("#bSave").prop('disabled', false);
                  $("#bCancel").prop('disabled', false);

                  $("#hover2").addClass('mhee');
                  $("#hover4").addClass('mhee');
                  $("#hover5").addClass('mhee');

                  $("#bImport2").removeClass('opacity');
                  $("#bSave2").removeClass('opacity');
                  $("#bCancel2").removeClass('opacity');
                  $('#bPrint').attr('disabled', true);
                  $('#bPrint2').addClass('opacity');
                  $('#hover6').removeClass('mhee');
                } else if (temp[0]['IsStatus'] == 1) {
                  $("#hover5").addClass('mhee');
                  $("#bCancel").prop('disabled', false);
                  $("#bCancel2").removeClass('opacity');
                  $('.chk_edit').attr('disabled', true);
                  var word = '<?php echo $array['edit'][$language]; ?>';
                  var changeBtn = "<i class='fas fa-edit'></i>";
                  changeBtn += "<div>" + word + "</div>";
                  $('#icon_edit').html(changeBtn);
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', false);
                  $("#bSave2").removeClass('opacity');
                  $('#hover4').addClass('mhee');
                  $('#bPrint').attr('disabled', false);
                  $('#bPrint2').removeClass('opacity');
                  $('#hover6').addClass('mhee');
                } else if (temp[0]['IsStatus'] == 2 || temp[0]['IsStatus'] == 3 || temp[0]['IsStatus'] == 4) {
                  $('.chk_edit').attr('disabled', true);
                  $("#hover5").removeClass('mhee');
                  $("#bCancel").prop('disabled', true);
                  $("#bCancel2").addClass('opacity');
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', true);
                  $("#bCancel").prop('disabled', true);
                  $("#bSave2").addClass('opacity');
                  $("#factory").prop('disabled', true);
                  $('#hover4').removeClass('mhee');
                  $('#bPrint').attr('disabled', false);
                  $('#bPrint2').removeClass('opacity');
                  $('#hover6').addClass('mhee');
                } else {
                  $('#bPrint').attr('disabled', true);
                  $('#bPrint2').addClass('opacity');
                  $('#hover6').removeClass('mhee');
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', true);
                  $("#bCancel").prop('disabled', true);
                  $("#bImport2").addClass('opacity');
                  $("#bDelete2").addClass('opacity');
                  $("#bSave2").addClass('opacity');
                  $("#bCancel2").addClass('opacity');
                  $('#hover2').removeClass('mhee');
                  $('#hover4').removeClass('mhee');
                  $('#hover5').removeClass('mhee');
                  $('#hover3').removeClass('mhee');
                  $("#docno").prop('disabled', true);
                  $("#docdate").prop('disabled', true);
                  $("#recorder").prop('disabled', true);
                  $("#timerec").prop('disabled', true);
                  $("#total").prop('disabled', true);

                  $('#qty1_' + i).prop('disabled', true);
                  $('#weight_' + i).prop('disabled', true);
                  $('#price_' + i).prop('disabled', true);
                  $('#unit' + i).prop('disabled', true);
                }

                if (temp[0]['IsStatus'] == 9) {
                  $('.chk_edit').attr('disabled', true);
                  $('#bPrint').attr('disabled', false);
                  $('#bPrint2').removeClass('opacity');
                  $('#hover6').addClass('mhee');
                }
                $("#IsStatus").val(temp[0]['IsStatus']);
                ShowDetail();
              } else if (temp["form"] == 'ShowDocument_sub') {
                $("#TableDocument tbody").empty();
                $("#TableItemDetail tbody").empty();
                $("#hotpital").val("1");
                $("#department").val("");
                $("#docdate").val("");
                $("#docno").val("");
                $("#recorder").val("");
                $("#timerec").val("");

                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                  var rowCount = $('#TableDocument >tbody >tr').length;
                  var chkDoc = "<input type='radio' name='checkdocno' id='checkdocno' value='" + temp[i]['DocNo'] + "' >";
                  var Status = "";
                  var Style = "";
                  if (temp[i]['IsStatus'] == 0) {
                    Status = "<?php echo $array['draft'][$language]; ?>";
                    Style = "style='width: 10%;color: #3399ff;'";
                  } else if (temp[i]['IsStatus'] == 1) {
                    Status = "<?php echo $array['savesuccess'][$language]; ?>";
                    Style = "style='width: 10%;color: #20B80E;'";
                  } else if (temp[i]['IsStatus'] == 2) {
                    Status = "<?php echo $array['cancelbill'][$language]; ?>";
                    Style = "style='width: 10%;color: #ff0000;'";
                  } else if (temp[i]['IsStatus'] == 3) {
                    Status = "<?php echo $array['savesuccess'][$language]; ?>";
                    Style = "style='width: 10%;color: #20B80E;'";
                  }

                  $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;' nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 15%;' nowrap>" + temp[i]['DocDate'] + "</td>" +
                    "<td style='width: 30%;' nowrap>" + temp[i]['DocNo'] + "</td>" +
                    "<td style='width: 15%;' nowrap>" + temp[i]['Record'] + "</td>" +
                    "<td style='width: 10%;' nowrap>" + temp[i]['RecNow'] + "</td>" +
                    "<td style='width: 10%;' nowrap>" + temp[i]['Total'] + "</td>" +
                    "<td " + Style + ">" + Status + "</td>" +
                    "</tr>";

                  if (rowCount == 0) {
                    $("#TableDocument tbody").append($StrTr);
                  } else {
                    $('#TableDocument tbody:last-child').append($StrTr);
                  }
                }

              } else if (temp["form"] == 'getImport' || temp["form"] == 'ShowDetail') {
                $("#TableItemDetail tbody").empty();
                if (temp["Row"] > 0)
                  $("#wTotal").val(temp[0]['Total']);
                else
                  $("#wTotal").val(0);

                var isStatus = $("#IsStatus").val();
                var st1 = "style='font-size:24px;margin-left:20px;width:153px;'";
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableItemDetail >tbody >tr').length;

                  var chkunit = "<select " + st1 + " disabled='true' onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' style='font-size:24px;' id='Unit_" + i + "'>";
                  var nUnit = temp[i]['UnitName'];
                  for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                    if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode']) {
                      chkunit += "<option selected value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    } else {
                      chkunit += "<option value=" + i + "," + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + "," + temp['Multiply_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    }
                  }

                  chkunit += "</select>";

                  var chkDoc = "<div class='form-inline'><label class='radio' style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkrow_" + i + "' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'  onclick='resetradio(\"" + i + "\")'><span class='checkmark'></span><label style='margin-left:27px; '> " + (i + 1) + "</label></label></div>";

                  var Qty = "<div class='row' style='margin-left:0px;'><input class='form-control numonly'  style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_" + i + "' OnBlur='updateQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\");' value='" + temp[i]['Qty'] + "'></div>";

                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='weight_" + i + "' value='" + temp[i]['Weight'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                  var Price = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_" + i + "' value='" + temp[i]['Price'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                  $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 9%;' nowrap>" + chkDoc + "</td>" +
                    "<td style='text-overflow: ellipsis;overflow: hidden;width: 18%;' nowrap>" + temp[i]['ItemCode'] + "</td>" +
                    "<td style='text-overflow: ellipsis;overflow: hidden;width: 29%;' nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 18%;' nowrap>" + chkunit + "</td>" +
                    "<td style='width: 12%;' nowrap>" + Qty + "</td>" +
                    "<td style='width: 12%;' nowrap>" + Weight + "</td>" +
                    "</tr>";
                  if (rowCount == 0) {
                    $('#bSaveadd').attr('disabled', true);
                    $('#bSaveadd2').addClass('opacity');
                    $("#countcheck").val("0");
                    $("#TableItemDetail tbody").append($StrTR);
                  } else {
                    $('#TableItemDetail tbody:last-child').append($StrTR);
                  }
                  if (isStatus == 0) {
                    $('#qty1_' + i).prop('disabled', false);
                    $('#weight_' + i).prop('disabled', false);
                    $('#price_' + i).prop('disabled', false);
                    $('#price_' + i).prop('disabled', false);
                    $('#unit' + i).prop('disabled', false);
                  } else {
                    $("#docno").prop('disabled', true);
                    $("#docdate").prop('disabled', true);
                    $("#recorder").prop('disabled', true);
                    $("#timerec").prop('disabled', true);
                    $("#total").prop('disabled', true);
                    $('#qty1_' + i).prop('disabled', true);
                    $('#weight_' + i).prop('disabled', true);
                    $('#price_' + i).prop('disabled', true);
                    $('#unit' + i).prop('disabled', true);
                  }
                }

                $('.numonly').on('input', function() {
                  this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                });
              } else if ((temp["form"] == 'ShowItem')) {
                var st1 = "style='font-size:24px;margin-left: -10px; width:150px;";
                var st2 = "style='height:40px;width:60px;font-size: 20px;margin-left:3px; margin-right:3px; text-align:center;"
                $("#TableItem tbody").empty();
                if (temp["Row"] > 0) {
                  for (var i = 0; i < temp["Row"]; i++) {
                    var rowCount = $('#TableItem >tbody >tr').length;

                    // var chkDoc = "<input type='checkbox' id='checkrow_" + i + "'  name='checkitem' onclick='dis2(\"" + i + "\")' class='checkitem' value='" + i + "'><input type='hidden' id='RowID" + i + "' value='" + temp[i]['ItemCode'] + "'>";
                    // var Qty = "<div  class='row' style='margin-left:2px;'><button class='btn btn-danger numonly' style='height:40px;width:32px;' onclick='subtractnum(\"" + i + "\")'>-</button><input class='form-control numonly' " + st2 + " id='iqty" + i + "' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\"" + i + "\")'>+</button></div>";
                    // var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly'  style='font-size: 20px;height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" + i + "' placeholder='0' ></div>";
                    $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                      "<td style='width: 12%;'><label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                      "<td style='width: 38%;cursor: pointer;' title='" + temp[i]['ItemCode'] + "'>" + temp[i]['ItemName'] + "</td>" +
                      "<td style='width: 50%;text-align:center;color:#307FE2;'><i class='btn fas fa-plus-circle' style='width: 29px;height:28px;padding-top:4px;font-size:28px;' id='icon_" + temp[i]['ItemCode'] + "' onclick='confirmDep(\"" + temp[i]['ItemCode'] + "\",\"" + temp[i]['ItemName'] + "\")'></i></td>" +
                      "</tr>";
                    if (rowCount == 0) {
                      $("#TableItem tbody").append($StrTR);
                    } else {
                      $('#TableItem tbody:last-child').append($StrTR);
                    }
                  }
                  $('.numonly').on('input', function() {
                    this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                  });
                } else {
                  $('#TableItem tbody').empty();
                  var Str = "<tr width='100%'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                  $('#TableItem tbody:last-child').append(Str);
                }
              } else if ((temp["form"] == 'showDep')) {
                if (temp['CountDep'] > 0) {
                  var myDATA = "";
                  $('#Dep').empty();
                  $("input:checked").each(function() {
                    $(this).prop('checked', false);
                  });

                  for (var i = 0; i < temp['CountDep']; i++) {
                    var DepName = "<span class='ml-4' style=''>" + temp[i]['DepName'] + "</span>";
                    var chkDep = "<input type='checkbox' id='checkDep_" + i + "' title='" + temp[i]['DepName'] + "' name='checkDep' style='top:-10%;' class='checkbox myDepName checkDep_" + i + " unchk' data-DepCode='" + temp[i]['DepCode'] + "' onclick='swithChecked(\"" + i + "\")'>";
                    myDATA += "<div class='col-12'style= 'text-overflow: ellipsis;overflow: hidden;' nowrap>" + chkDep + DepName + "</div>";
                  }

                  $('#Dep').html(myDATA);
                  $('#HItemName').html(temp['ItemName']);
                  $('#ModalDepartment').modal('toggle');
                  var DocNoHide = $('#docno').val();
                  $('#ItemCodeHide').val(temp['ItemCode']);
                  $('#DocNoHide').val(DocNoHide);
                }
              } else if ((temp["form"] == 'updateQty')) {
                $('#wTotal').val(temp['Total']);
              } else if ((temp["form"] == 'UpdateDetailWeight')) {
                if (temp[0]['wTotal'] > 0)
                  $("#wTotal").val(temp[0]['wTotal']);
                else
                  $("#wTotal").val(0);
              } else if ((temp["form"] == 'ShowDetailDoc')) {
                var st1 = "style='font-size:24px;margin-left: -10px; width:150px;'";
                var st2 = "style='height:40px;width:60px;font-size: 20px;margin-left:3px; margin-right:3px; text-align:center;'"
                $("#TableItemDetail tbody").empty();
                var DataRow = '';
                var isStatus = $("#IsStatus").val();
                $('#wTotal').val(temp[0]['Total'].toFixed(2));
                for (var i = 0; i < temp["CountDep"]; i++) {
                  var chkunit = "<select " + st1 + " class='form-control chk_edit' id='iUnit_" + i + "'>";
                  var nUnit = "";

                  for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                    if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode']) {
                      chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                      nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                    } else {
                      chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                      nUnit = temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j];
                    }
                  }
                  chkunit += "</select>";

                  var chkDoc = "<div class='form-inline'><label class='radio' style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkrow_" + i + "' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'  onclick='resetradio(\"" + i + "\")'><span class='checkmark'></span><label style='margin-left:27px; '> " + (i + 1) + "</label></label></div>";

                  var Qty = "<input class='form-control numonly chk_edit'  style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_" + i + "' onchange='updateQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\",\"" + temp[i]['Weight2'] + "\");' value='" + temp[i]['Qty'] + "' autocomplete='off' placeholder='0'>";

                  var Weight = "<input class='form-control numonly chk_edit chk_weight weight_" + i + "'  style='width:87px;height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='myweight_" + i + "' value='" + temp[i]['Weight'] + "' onkeyup='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")' autocomplete='off' placeholder='0'>";

                  var Price = "<input class='form-control chk_edit' style='height:40px;margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_" + i + "' value='" + temp[i]['Price'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'>";

                  var chkItem = "<label class='radio ' style='margin-top: 20%;'><input type='radio' name='checkitem' onclick='resetradio(\"" + i + "\")' id='checkrow' class='checkrow_" + i + " chk_edit' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'><span class='checkmark'></span></label>";
                  DataRow += "<tr><td style='width:3%;' nowrap>" + chkItem + "</td>";
                  DataRow += "<td style='width:6%;text-overflow: ellipsis;overflow: hidden;' nowrap>" + (i + 1) + "</td>";
                  DataRow += "<td style='width:18%;text-overflow:  ellipsis;overflow: hidden;' class='tooltip-test' nowrap title='" + temp[i]['DepName'] + "'>" + temp[i]['DepName'] + "</td>";
                  DataRow += "<td style='width:21%;text-overflow: ellipsis;overflow: hidden;' nowrap>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width:22%;text-overflow: ellipsis;overflow: hidden;' nowrap><center>" + chkunit + "</center></td>" +
                    "<td style='width:10%;text-overflow: ellipsis;overflow: hidden;' nowrap><center>" + Qty + "</center></td>" +
                    "<td style='width:20%;text-overflow: ellipsis;overflow: hidden;' nowrap><center>" + Weight + "</center></td></<tr>";
                }
                $("#TableItemDetail tbody").html(DataRow);
                $('.numonly').on('input', function() {
                  this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                });
                if (isStatus == 1 || isStatus == 9 || isStatus == 2 || isStatus == 3 || isStatus == 4) {
                  $('.chk_edit').attr('disabled', true);
                }
              } else if ((temp["form"] == 'ShowUsageCode')) {
                var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-family:THSarabunNew;font-size:24px;'";
                var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-family:THSarabunNew;font-size:32px;'"
                $("#TableUsageCode tbody").empty();
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableUsageCode >tbody >tr').length;
                  var chkunit = "<select " + st1 + " onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' style='font-size:32px;' id='iUnit_" + i + "'>";
                  for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                    if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode'])
                      chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    else
                      chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  }
                  chkunit += "</select>";
                  var chkDoc = "<input type='checkbox' name='checkitemSub' id='checkitemSub' value='" + i + "'><input type='hidden' id='RowIDSub" + i + "' value='" + temp[i]['RowID'] + "'>";
                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control' style='height:40px;width:134px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" + i + "' value='0' ></div>";
                  $StrTR = "<tr id='tr" + temp[i]['RowID'] + "'>" +
                    "<td style='width: 10%;'>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                    "<td style='width: 20%;'>" + temp[i]['UsageCode'] + "</td>" +
                    "<td style='width: 40%;'>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 15%;'>" + chkunit + "</td>" +
                    "<td style='width: 13%;' align='center'>1</td>" +
                    "</tr>";
                  if (rowCount == 0) {
                    $("#TableUsageCode tbody").append($StrTR);
                  } else {
                    $('#TableUsageCode tbody:last-child').append($StrTR);
                  }
                }
              } else if (temp['status'] == "failed") {

    
      
              }
            }
          }
        })
      }

        
      //===============================================
      function switch_tap1() {
        $('#tab2').attr('hidden', false);
        $('#switch_col').removeClass('col-md-12');
        $('#switch_col').addClass('col-md-10');
      }

      function switch_tap2() {
        $('#tab2').attr('hidden', true);
        $('#switch_col').removeClass('col-md-10');
        $('#switch_col').addClass('col-md-12');
      }

      //===============================================
      function PrintData() {
        var docno = $('#docno').val();
        var HptCode = $("#hotpital").val();
        var lang = '<?php echo $language; ?>';
        if (docno != "" && docno != undefined) {
          var url = "../report/Report_newwash_tc.php?DocNo=" + docno + "&lang=" + lang + "&HptCode=" + HptCode;
          window.open(url);
        } else {
          swal({
            title: '',
            text: '<?php echo $array['docfirst'][$language]; ?>',
            type: "info",
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 2000,
            confirmButtonText: 'Ok'
          })
        }
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
        overflow: scroll;
        overflow-x: hidden;
        width: 100%;
      }

      ::-webkit-scrollbar {
        width: 0px;
        /* Remove scrollbar space */
        background: transparent;
        /* Optional: just make scrollbar invisible */
      }

      ::-webkit-scrollbar-thumb {
        background: none;
      }

      .nfont {
        font-family: myFirstFont;
        font-size: 22px;
      }

      button,
      input[id^='qty'],
      input[id^='order'],
      input[id^='max'] {
        font-size: 24px !important;
      }

      .table th,
      .table td {
        border-top: none !important;
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

      table tr:first-child th:first-child {
        border-top-left-radius: 15px;
      }

      table tr:first-child th:first-child {
        border-bottom-left-radius: 15px;
      }

      table tr:first-child th:last-child {
        border-top-right-radius: 15px;
      }

      table tr:first-child th:last-child {
        border-bottom-right-radius: 15px;
      }

      table tr:last-child td:last-child {
        border-bottom-right-radius: 6px;
      }

      .opacity {
        opacity: 0.5;
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
        border-left: 2px solid #bdc3c7;
      }

      .mhee a {
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
        text-decoration: none;
        font-size: 23px;
        color: #2c3e50;
        display: block;
        background: none;
        box-shadow: none !important;
      }

      .mhee button:hover {
        color: #2c3e50;
        font-weight: bold;
        font-size: 26px;

      }

      .only1:disabled,
      .form-control[readonly] {
        background-color: transparent !important;
        opacity: 1;
      }

      .sidenav a {
        padding: 6px 8px 6px 16px;
        text-decoration: none;
        font-size: 25px;
        color: #818181;
        display: block;
      }

      .sidenav a:hover {
        color: #2c3e50;
        font-weight: bold;
        font-size: 26px;
      }

      .icon {
        padding-top: 6px;
        padding-left: 44px;
      }

      @media (min-width: 992px) and (max-width: 1199.98px) {

        .icon {
          padding-top: 6px;
          padding-left: 23px;
        }

        .sidenav a {
          font-size: 21px;

        }
      }

      #ModalDepartment .modal-content {
        width: 70% !important;
        right: 0% !important;
        top: 136px;
      }

      #ModalDepartment .card-body {
        overflow-y: auto;
        max-height: 328px;
      }

      .mhee555 {
        overflow-y: auto;
        max-height: 600px;
      }
    </style>
  </head>

  <body id="page-top">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
      <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][12][$language]; ?></li>
    </ol>

    <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
    <input type="hidden" id="getHot">
    <div id="wrapper">
      <div id="content-wrapper">
        <div class="row">
          <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['newwash'][$language]; ?></a>
              </li>
              <li class="nav-item">
                <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" onclick=" ShowDocument()" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
              </li>
            </ul>

            <div class="tab-content" id="myTabContent">
              <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- /.content-wrapper -->
                <div class="row">
                  <div class="col-md-11">
                    <!-- tag column 1 -->
                    <div class="container-fluid">
                      <div class="card-body mt-3">

                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['side'][$language]; ?></label>
                              <select class="form-control form-control col-sm-7 icon_select checkblank3" style="font-size:22px;" id="hotpital" onchange="getfactory();" <?php if ($PmID == 2 || $PmID == 3 || $PmID == 4 || $PmID == 5 || $PmID == 7) echo 'disabled="true" '; ?>> </select>
                              <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['factory'][$language]; ?></label>
                              <select class="form-control form-control col-sm-7 checkblank2" style="font-size:22px;" id="factory" onchange="removeClassBorder2();"> </select>
                              <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                            </div>
                          </div>
                          <!-- <div class="col-md-6">
                                        <div class='form-group row'>
                                          <label class="col-sm-4 col-form-label "  style="font-size:24px;" ><?php echo $array['department'][$language]; ?></label>
                                            <select class="form-control col-sm-7 checkblank border"  style="font-size:22px;"  id="department" onchange="removeClassBorder1();"> </select>
                                            <label id="rem1" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                                        </div>
                                      </div> -->
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docdate'][$language]; ?></label>
                              <!-- <input type="text" autocomplete="off"  style="font-size:22px;"  class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" > -->
                              <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7  numonly charonly only only1 " id="docdate" disabled="true" placeholder="<?php echo $array['docdate'][$language]; ?>">
                              <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docno'][$language]; ?></label>
                              <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['employee'][$language]; ?></label>
                              <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>">
                            </div>
                          </div>
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['time'][$language]; ?></label>
                              <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 only only1" disabled="true" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>">
                            </div>
                          </div>
                        </div>

                        <div class="row">
                          <div class="col-md-6">
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['totalweight'][$language]; ?></label>
                              <input class='form-control col-sm-7 only only1' disabled="true" autocomplete="off" style="font-size:22px; " id='wTotal' placeholder="0.00">
                            </div>
                          </div>
                          <div class="col-md-6" hidden>
                            <div class='form-group row'>
                              <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['refdocno'][$language]; ?></label>
                              <input type="text" class="form-control col-sm-9 only" style="font-size:22px;" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>">
                              <input class='form-control col-sm-7 only"' style="font-size:22px;" id='RefDocNo' placeholder="<?php echo $array['refdocno'][$language]; ?>" OnBlur='UpdateRefDocNo()'>
                            </div>
                          </div>
                        </div>

                      </div>
                    </div>
                  </div> <!-- tag column 1 -->
                  <!-- row btn -->
                  <div class="row m-1 mt-4 d-flex justify-content-end col-12">
                    <div class="menu mhee" id="hover1">
                      <div class="d-flex justify-content-center">
                        <div class="circle1 d-flex justify-content-center" id="bCreate2">
                          <button class="btn" onclick="CreateDocument()" id="bCreate">
                            <i class="fas fa-file-medical"></i>
                            <div>
                              <?php echo $array['createdocno'][$language]; ?>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="menu " id="hover2">
                      <div class="d-flex justify-content-center">
                        <div class="circle2 d-flex justify-content-center opacity" id="bImport2">
                          <button class="btn" onclick="OpenDialogItem()" id="bImport" disabled="true">
                            <i class="fas fa-file-import"></i>
                            <div>
                              <?php echo $array['import'][$language]; ?>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="menu " id="hover3">
                      <div class="d-flex justify-content-center">
                        <div class="circle3 d-flex justify-content-center opacity" id="bDelete2">
                          <button class="btn" onclick="DeleteItem()" id="bDelete" disabled="true">
                            <i class="fas fa-trash-alt"></i>
                            <div>
                              <?php echo $array['delitem'][$language]; ?>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="menu " id="hover4">
                      <div class="d-flex justify-content-center">
                        <div class="circle4 d-flex justify-content-center opacity" id="bSave2">
                          <button class="btn" onclick="SaveBill()" id="bSave" disabled="true">
                            <div id="icon_edit">
                              <i class="fas fa-save"></i>
                              <div>
                                <?php echo $array['save'][$language]; ?>
                              </div>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="menu " id="hover5">
                      <div class="d-flex justify-content-center">
                        <div class="circle5 d-flex justify-content-center opacity" id="bCancel2">
                          <button class="btn" onclick="CancelDocument()" id="bCancel" disabled="true">
                            <i class="fas fa-times"></i>
                            <div>
                              <?php echo $array['Canceldoc'][$language]; ?>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="menu " id="hover6">
                      <div class="d-flex justify-content-center">
                        <div class="circle9 d-flex justify-content-center opacity" id="bPrint2">
                          <button class="btn" onclick="PrintData()" id="bPrint" disabled="true">
                            <i class="fas fa-print"></i>
                            <div>
                              <?php echo $array['print'][$language]; ?>
                            </div>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!-- end row btn -->
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid" style="">
                      <thead id="theadsum" style="font-size:24px;">
                        <tr role="row" id='tr_1'>
                          <th style="width: 3%;">&nbsp;</th>
                          <th style='width: 6%;' nowrap><?php echo $array['sn'][$language]; ?></th>
                          <th style='width: 18%;' nowrap><?php echo $array['department'][$language]; ?></th>
                          <th style='width: 16%;' nowrap><?php echo $array['item'][$language]; ?></th>
                          <th style='width: 30%;' nowrap>
                            <center><?php echo $array['unit'][$language]; ?></center>
                          </th>
                          <th style='width: 7%;' nowrap><?php echo $array['qty'][$language]; ?></th>
                          <th style='width: 20%;' nowrap>
                            <center><?php echo $array['weight'][$language]; ?></center>
                          </th>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:630px;">
                      </tbody>
                    </table>
                  </div> <!-- tag column 1 -->
                </div>
              </div>

              <!-- search document -->
              <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                <div class="row" style="margin-top:10px;">
                  <div class="col-md-2">
                    <div class="row" style="font-size:24px;margin-left:2px;">
                      <select class="form-control" style='font-size:24px;' id="Hos2" onchange="getDepartment();">
                      </select>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="row" style="font-size:24px;margin-left:2px;">
                      <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here numonly charonly" id="datepicker1" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                    </div>
                  </div>
                  <div class="col-md-2">
                    <div class="row" style="font-size:24px;margin-left:2px;">
                      <select class="form-control" style='font-size:24px;' id="process">
                        <option value="0"><?php echo $array['processchooce'][$language]; ?></option>
                        <option value="1">on process</option>
                        <option value="2">completed</option>
                        <option value="3">cancel</option>

                      </select>
                    </div>
                  </div>
                  <div class="col-md-6 mhee">
                    <div class="row" style="margin-left:2px;">
                      <input type="text" autocomplete="off" class="form-control" style="font-size:24px;width:50%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
                      <div class="search_custom col-md-2">
                        <div class="search_1 d-flex justify-content-start">
                          <button class="btn" onclick="ShowDocument(1)">
                            <i class="fas fa-search mr-2"></i>
                            <?php echo $array['search'][$language]; ?>
                          </button>
                        </div>
                      </div>
                      <div class="search_custom col-md-2">
                        <div class="circle11 d-flex justify-content-start">
                          <button class="btn" onclick="SelectDocument()" id="btn_show">
                            <i class="fas fa-paste mr-2 pt-1"></i>
                            <?php echo $array['show'][$language]; ?>
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12">
                    <!-- tag column 1 -->
                    <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid">
                      <thead id="theadsum" style="font-size:24px;">
                        <tr role="row">
                          <th style='width: 10%;' nowrap>&nbsp;</th>
                          <th style='width: 8%;' nowrap><?php echo $array['docdate'][$language]; ?></th>
                          <th style='width: 19%;padding-left: 5%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                          <th style='width: 15%;' nowrap><?php echo $array['employee'][$language]; ?></th>
                          <th style='width: 10%;' nowrap><?php echo $array['time'][$language]; ?></th>
                          <th style='width: 8%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                          <th style='width: 17%;' nowrap><?php echo $array['factory'][$language]; ?></th>
                          <th style='width: 13%;' nowrap><?php echo $array['status'][$language]; ?></th>
                        </tr>
                      </thead>
                      <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:400px;">
                      </tbody>
                    </table>
                  </div> <!-- tag column 1 -->
                </div>

              </div>
              <!-- end row tab -->
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- -----------------------------Custom1------------------------------------ -->
    <div class="modal" id="dialogItemCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="card-body" style="padding:0px;">
              <div class="row">
                <div class="col-md-8">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right pr-5" style="margin-left: -11%;"><?php echo $array['Searchitem2'][$language]; ?></label>
                    <input type="text" autocomplete="off" style="margin-left: -3%;" class="form-control col-sm-7" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchitem2'][$language]; ?>">
                  </div>
                </div>
                <!-- serach----------------------- -->
                <div class="search_custom col-md-2" style="margin-left: -14%;">
                  <div class="search_1 d-flex justify-content-start">
                    <button class="btn" onclick="ShowItem()" id="bSave">
                      <i class="fas fa-search mr-2"></i>
                      <?php echo $array['search'][$language]; ?>
                    </button>
                  </div>
                </div>

                <div class="search_custom col-md-2" hidden>
                  <div class="import_1 d-flex justify-content-start opacity" id="bSaveadd2">
                    <button class="btn dis" onclick="getImport(1)" id="bSaveadd" disabled="true">
                      <i class="fas fa-file-import mr-2 pt-1"></i>
                      <?php echo $array['import'][$language]; ?>
                    </button>
                  </div>
                </div>
                <!-- end serach----------------------- -->
              </div>
              <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;">
                <thead style="font-size:24px;">
                  <tr role="row">
                    <th style='width: 12%;' nowrap><?php echo $array['no'][$language]; ?></th>
                    <th style='width: 38%;' nowrap><?php echo $array['item'][$language]; ?></th>
                    <th style='width: 50%;' nowrap>
                      <center><?php echo $array['additem'][$language]; ?></center>
                    </th>
                  </tr>
                </thead>
                <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:300px;">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ModalDepartment" tabindex="-1" style='background-color: #00000061!important;' role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header" id="modalHead">
            <h4 id="HItemName"></h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <input type="text" id="countcheck" value="0" hidden>
            <input type="text" id="DocNoHide" hidden>
            <input type="text" id="ItemCodeHide" hidden>
            <div class="card-body" style="padding:0px;">
              <input type='checkbox' id='selectAll' onclick='selectAll()' style="top:-4px;"><span style="font-size:30px; " class="ml-4"><?php echo $array['selectall'][$language]; ?></span>
              <div id='Dep' class='row'></div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" id="btn_confirm" style="width:15%;" disabled class="btn btn-success px-2" onclick="confirmDep()"><?php echo $array['confirm'][$language]; ?></button>
            <button type="button" style="width:15%;" class="btn btn-danger px-2" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
          </div>
        </div>
      </div>
    </div>

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