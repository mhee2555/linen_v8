<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];

if ($Userid == "") {
  header("location:../index.html");
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

  <title><?php echo $array['clean'][$language]; ?></title>

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
  <!-- custome style -->
  <link href="../css/responsive.css" rel="stylesheet">
  <!-- ---------------------------------------------- -->
  <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
    jqui = jQuery.noConflict(true);
  </script>
  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../dist/css/sweetalert2.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>

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

    (function($) {
      $(document).ready(function() {
        $("#bCreate").attr('disabled', true);
        $("#datepickerRef1").datepicker({
          onSelect: function(date, el) {
            var lang = '<?php echo $language; ?>';
            var datepicker1 = $('#datepickerRef1').val();
            var datepicker2 = $('#datepickerRef2').val();
            if (lang == 'th') {
              datepicker1 = datepicker1.substring(6, 10) - 543 + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
              datepicker2 = datepicker2.substring(6, 10) - 543 + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
            } else if (lang == 'en') {
              datepicker1 = datepicker1.substring(6, 10) + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
              datepicker2 = datepicker2.substring(6, 10) + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
            }
            var chk1 = new Date(datepicker1);
            var chk2 = new Date(datepicker2);
            if (chk1 > chk2) {
              swal({
                title: "",
                text: "<?php echo $array['invalid'][$language]; ?>",
                type: "warning",
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000
              });
              $('#datepickerRef1').val('');
            }
          }
        });
        $("#datepickerRef2").datepicker({
          onSelect: function(date, el) {
            var lang = '<?php echo $language; ?>';
            var datepicker1 = $('#datepickerRef1').val();
            var datepicker2 = $('#datepickerRef2').val();
            if (lang == 'th') {
              datepicker1 = datepicker1.substring(6, 10) - 543 + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
              datepicker2 = datepicker2.substring(6, 10) - 543 + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
            } else if (lang == 'en') {
              datepicker1 = datepicker1.substring(6, 10) + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
              datepicker2 = datepicker2.substring(6, 10) + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
            }
            var chk1 = new Date(datepicker1);
            var chk2 = new Date(datepicker2);
            if (chk2 < chk1) {
              swal({
                title: "",
                text: "<?php echo $array['invalid'][$language]; ?>",
                type: "warning",
                showConfirmButton: false,
                showCancelButton: false,
                timer: 2000
              });
              $('#datepickerRef2').val('');
            }
          }
        });
      });
    })(jQuery);


    $(document).ready(function(e) {
      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });

      $('#rem3').hide();
      $('#rem4').hide();

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

      $('#searchitem1').keyup(function(e) {
        if (e.keyCode == 13) {
          get_dirty_doc();
        }
      });

      $('#Dep2').addClass('icon_select');
      $('.only').on('input', function() {
        this.value = this.value.replace(/[^]/g, ''); //<-- replace all other than given set of values
      });

      OnLoadPage();
      getDepartment();
      getfactory();
      // CreateDocument();
      //==============================

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });


    jqui(document).ready(function($) {
      dialogUsageCode = jqui("#dialogUsageCode").dialog({
        autoOpen: false,
        height: 670,
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

    function OpenDialogItem() {
      setTimeout(() => {
        $('#dialogItemCode').modal('show');
      }, 700);
      ShowItem();
    }

    function SaveRequest() {

      var unitrequest = $("#unitrequest").val();
      var NameRequest = $("#NameRequest").val();
      var qtyRequest = $("#qtyRequest").val();
      var weightRequest = $("#weightRequest").val();
      var docno = $("#docno").val();
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
          'STATUS': 'SaveRequest',
          'NameRequest': NameRequest,
          'qtyRequest': qtyRequest,
          'weightRequest': weightRequest,
          'unitrequest': unitrequest,
          'DocNo': docno
        }
        senddata(JSON.stringify(data));
        $("#NameRequest").val("");
        $("#qtyRequest").val("");
        $("#weightRequest").val("");
        // $('#ModalRequest').modal('toggle');
      }, 1000);
    }

    function OpenDialogUsageCode(itemcode) {
      xItemcode = itemcode;
      var docno = $("#docno").val();
      if (docno != "") {
        dialogItemCode.dialog("close");
        dialogUsageCode.dialog("open");
        ShowUsageCode();
      }
    }

    function Blankinput() {
      $('#docno').val("");
      $('#docdate').val("");
      $('#recorder').val("");
      $('#timerec').val("");
      $('#wTotal').val("");
      $('#RefDocNo').val("");
      $('#RefDocNo').attr('disabled', true);
      // OnLoadPage();
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
        // Blankinput();
      } else {
        $('#hover3').addClass('mhee');
        $('#bDelete').attr('disabled', false);
        $('#bDelete2').removeClass('opacity');
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('.checkrow_' + row).attr('previousValue', 'checked');
      }
    }

    function DeleteItem() {
      var docno = $("#docno").val();
      var xrow = $("#checkrow:checked").val();
      xrow = xrow.split(",");
      swal({
        title: "<?php echo $array['confirmdelete'][$language]; ?>",
        // text: "<?php echo $array['confirm1'][$language]; ?>"+xrow[1]+"<?php echo $array['confirm2'][$language]; ?>",
        text: "<?php echo $array['confirm1'][$language]; ?>",
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

    function dis() {
      $('.dis').attr('disabled', false);
    }

    function disRef() {
      $('#bsaveRef').attr('disabled', false);
      $("#bsaveRef").removeClass('opacity');
    }

    function CancelDocument() {
      var docno = $("#docno").val();
      var RefDocNo = $('#RefDocNo').val();
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
            var data = {
              'STATUS': 'CancelBill',
              'DocNo': docno,
              'RefDocNo': RefDocNo
            };
            senddata(JSON.stringify(data));
            $('#profile-tab').tab('show');
            ShowDocument();
            Blankinput();
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }

        })
      }
    }

    //======= On create =======
    //console.log(JSON.stringify(data));
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
      getDepartment(1);
      senddata(JSON.stringify(data));
    }

    function OnLoadPage() {
      var lang = '<?php echo $language; ?>';
      var docno = $("#docno").val();
      var data = {
        'STATUS': 'OnLoadPage',
        'lang': lang
      };
      senddata(JSON.stringify(data));
      $('#isStatus').val(0)
    }

    function savefactory() {
      var docno = $("#docno").val();
      var factory2 = $("#factory2").val();
      var data = {
        'STATUS': 'savefactory',
        'DocNo': docno,
        'factory2': factory2,
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    function open_dirty_doc() {
      $("#dialogRefDocNo").modal({
        backdrop: 'static',
        keyboard: false
      });
      get_dirty_doc();

    }

    function get_factory() {
      $("#dialogfactory").modal({
        backdrop: 'static',
        keyboard: false
      });
    }

    function showRequest() {
      var data = {
        'STATUS': 'showRequest'
      }
      senddata(JSON.stringify(data));
    }

    function get_dirty_doc() {
      var hptcode = $('#hotpital option:selected').attr("value");
      var docno = $("#docno").val();
      var searchitem1 = $('#searchitem1').val();
      var datepicker1 = $('#datepickerRef1').val();
      var datepicker2 = $('#datepickerRef2').val();
      var lang = '<?php echo $language; ?>';
      if (lang == 'th') {
        datepicker1 = datepicker1.substring(6, 10) - 543 + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
        datepicker2 = datepicker2.substring(6, 10) - 543 + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
      } else if (lang == 'en') {
        datepicker1 = datepicker1.substring(6, 10) + "-" + datepicker1.substring(3, 5) + "-" + datepicker1.substring(0, 2);
        datepicker2 = datepicker2.substring(6, 10) + "-" + datepicker2.substring(3, 5) + "-" + datepicker2.substring(0, 2);
      }
      if (datepicker1 == "-543--" || datepicker1 == "--") {
        datepicker1 = "";
      }
      if (datepicker2 == "-543--" || datepicker2 == "--") {
        datepicker2 = "";
      }
      var data = {
        'STATUS': 'get_dirty_doc',
        'DocNo': docno,
        'hptcode': hptcode,
        'searchitem1': searchitem1,
        'datepicker1': datepicker1,
        'datepicker2': datepicker2
      };
      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
    }

    const dirtydoc = async function(dept, cart) {
      const dirty = await swal({
        title: '',
        input: 'select',
        inputOptions: cart,
        inputPlaceholder: '',
        showCancelButton: true,
        allowOutsideClick: false
      }).then(function(docno) {
          if (docno == "") {
            dirtydoc(dept, cart);
          } else {
            // console.log(occid);
            $('#RefDocNo').val(docno);
          }
        },
        function(dismiss) {
          if (dismiss === 'cancel') {

          }
        })

    }

    function getDepartment(chk) {
      if (chk == 1) {
        var Hotp = $('#hotpital option:selected').attr("value");
        $('#Hos2').val(Hotp);
      } else {
        var Hotp = $('#Hos2 option:selected').attr("value");
        $('#hotpital').val(Hotp);
      }
      if (typeof Hotp == 'undefined') {
        Hotp = '<?php echo $HptCode; ?>';
      }
      var data = {
        'STATUS': 'getDepartment',
        'Hotp': Hotp
      };
      senddata(JSON.stringify(data));

    }

    function ShowDocument(selecta) {
      var DocNo = $('#docno').val();
      var process = $('#process').val();
      var Hotp = $('#Hos2 option:selected').attr("value");
      var searchdocument = $('#searchdocument').val();
      if (typeof searchdocument == 'undefined') searchdocument = "";
      var deptCode = $('#Dep2 option:selected').attr("value");
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

      var data = {
        'STATUS': 'ShowDocument',
        'xdocno': searchdocument,
        'selecta': selecta,
        'deptCode': deptCode,
        'Hotp': Hotp,
        'datepicker1': datepicker1,
        'docno': DocNo,
        'process': process
      };
      senddata(JSON.stringify(data));
    }

    function ShowItem() {
      var hotpital = $('#hotpital').val();
      var deptCode = $('#department option:selected').attr("value");
      if (typeof deptCode == 'undefined') deptCode = "1";
      var searchitem = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'xitem': searchitem,
        'deptCode': deptCode,
        'hotpital': hotpital
      };
      senddata(JSON.stringify(data));
    }

    function chk_percent() {
      var RefDocNo = $('#RefDocNo').val();
      var DocNo = $('#docno').val();
      var wTotal = $('#wTotal').val();
      var data = {
        'STATUS': 'chk_percent',
        'wTotal': wTotal,
        'DocNo': DocNo,
        'RefDocNo': RefDocNo
      };
      senddata(JSON.stringify(data));
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
        'STATUS': 'ShowDetail',
        'DocNo': docno
      };
      senddata(JSON.stringify(data));
    }

    function getImport(Sel) {
      var docno = $("#docno").val();
      var RefDocNo = $("#RefDocNo").val();
      /* declare an checkbox array */
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

      var deptCode = $('#department option:selected').attr("value");

      $('#TableDetail tbody').empty();
      var data = {
        'STATUS': 'getImport',
        'xrow': xrow,
        'xqty': xqty,
        'xweight': xweight,
        'xunit': xunit,
        'DocNo': docno,
        'Sel': Sel,
        'deptCode': deptCode
      };

      senddata(JSON.stringify(data));
      ShowItem();
      dialogUsageCode.dialog("close");
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
      // console.log( chkArray[1] );
      var data = {
        'STATUS': 'updataDetail',
        'Rowid': rowid,
        'DocNo': docno,
        'unitcode': chkArray[1],
        'qty': qty
      };
      senddata(JSON.stringify(data));
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

    function checkblank2() {
      $('.checkblank2').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem4').show().css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem4').hide();
        }
      });
    }

    function CreateDocument() {
      var userid = '<?php echo $Userid; ?>';
      var hotpCode = $('#hotpital option:selected').attr("value");
      var deptCode = $('#department option:selected').attr("value");

      $('#TableDetail tbody').empty();
      if (hotpCode == '') {
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
          text: "<?php echo $array['side'][$language]; ?> : " + $('#hotpital option:selected').text() + " <?php echo $array['department'][$language]; ?> : " + $('#department option:selected').text(),
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
              'deptCode': deptCode,
              'userid': userid
            };
            senddata(JSON.stringify(data));
            $('#RefDocNo').attr('disabled', false);
            $('#input_chk').val(0);
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
        confirmButtonText: "<?php echo $array['delete'][$language]; ?>",
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

    function updateDetail(row, rowid) {
      var docno = $("#docno").val();
      var Detail = $("#Detail_" + row).val();
      var isStatus = $("#IsStatus").val();
      $('#input_chk').val(0);
      //alert(rowid+" :: "+docno+" :: "+weight);
      if (isStatus == 0) {
        var data = {
          'STATUS': 'UpdateDetail',
          'Rowid': rowid,
          'DocNo': docno,
          'Detail': Detail
        };
        senddata(JSON.stringify(data));
      }
    }

    function updateWeight(row, rowid) {
      $('#weight_' + row).removeClass('border border-danger');
      var docno = $("#docno").val();
      var wTotal = $("#wTotal").val();
      var weight = $("#weight_" + row).val();
      var price = 0; //$("#price_"+row).val();
      var isStatus = $("#IsStatus").val();
      $('#input_chk').val(0);
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

    function dis2(row) {
      if ($('#checkrow_' + row).prop("checked") == true) {
        var countcheck2 = Number($("#countcheck").val()) + 1;
        $("#countcheck").val(countcheck2);
        $('#bSaveadd').attr('disabled', false);
        $('#bSaveadd2').removeClass('opacity');
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

    function removeClassBorder2() {
      $('#factory1').removeClass('border-danger');
      $('#rem2').attr('hidden', true);
    }

    function checkblank() {
      $('.checkblank').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
          $('#rem2').attr('hidden', false).css("color", "red");
        } else {
          $(this).removeClass('border-danger');
          $('#rem2').attr('hidden', true);
        }
      });
    }

    function SaveBill(chk) {
      var count = 0;
      var count2 = 0;
      var chk_qty = document.getElementsByClassName("chk_qty"); //checkbox items       
      var chk_weight = document.getElementsByClassName("chk_weight"); //checkbox items       
      var docno = $("#docno").val();
      var docno2 = $("#RefDocNo").val();
      var isStatus = $("#IsStatus").val();
      var factory1 = $("#factory1").val();
      var dept = $("#Dep2").val();
      var input_chk = $('#input_chk').val();
      // alert( isStatus );
      if (isStatus == 1 || isStatus == 2 || isStatus == 3 || isStatus == 4) {
        isStatus = 0;
      } else {
        isStatus = 1;
      }
      if (isStatus == 1) {
        if (factory1 == "") {
          checkblank();
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
          if (docno != "") {
            for (i = 0; i < chk_qty.length; i++) {
              var chk = $('#qty1_' + i).val();
              var chk2 = $('#weight_' + i).val();
              // if(chk == 0 || chk == '')
              // {
              //   $('#qty1_'+i).addClass('border border-danger');
              //   count++;
              // }
              // if(chk2 == 0 || chk2 == '')
              // {
              //   $('#weight_'+i).addClass('border border-danger');
              //   count2++;
              // }
            }
            // if(count==0 && count2==0)
            // {
            if (chk == '' || chk == undefined) {
              chk_percent();
            } else {
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
                allowOutsideClick: false,
                allowEscapeKey: false,
                showCancelButton: true
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
                    'xdocno': docno,
                    'xdocno2': docno2,
                    'isStatus': isStatus,
                    'deptCode': dept,
                    'factory1': factory1
                  };

                  senddata(JSON.stringify(data));
                  $('#profile-tab').tab('show');
                  $("#bImport").prop('disabled', true);
                  $("#bDelete").prop('disabled', true);
                  $("#bSave").prop('disabled', false);
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
                  $("#factory1").val('');
                  ShowDocument();
                  if (input_chk == 1) {
                    $('#alert_percent').modal('toggle');
                  }

                } else if (result.dismiss === 'cancel') {
                  swal.close();
                }
              })
            }
            // }
            // else
            // {
            //   swal({
            //   title: " ",
            //   text:  " <?php echo $array['insert_form'][$language]; ?>",
            //   type: "warning",
            //   showCancelButton: false,
            //   showConfirmButton: false,
            //   timer: 1000,
            //   closeOnConfirm: true
            //   });
            // }
          }
        }
      } else {
        $("#bImport2").removeClass('opacity');
        $("#bSave2").removeClass('opacity');
        // $("#bCancel2").removeClass('opacity');
        $("#bImport").prop('disabled', false);
        $("#bSave").prop('disabled', false);
        // $("#bCancel").prop('disabled', false);
        $('#hover2').addClass('mhee');
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
        var rowCount = $('#TableItemDetail >tbody >tr').length;
        for (var i = 0; i < rowCount; i++) {
          $('#qty1_' + i).prop('disabled', false);
          $('#weight_' + i).prop('disabled', false);
          $('#price_' + i).prop('disabled', false);
          $('#Detail_' + i).prop('disabled', false);
          $('#unit' + i).prop('disabled', false);
        }
      }
    }

    function show_btn(DocNo) {
      if (DocNo != undefined || DocNo != '') {
        $(btn_show).attr('disabled', false);
      }
    }

    function updateQty(RowID, i) {
      $('#qty1_' + i).removeClass('border border-danger');
      var newQty = $('#qty1_' + i).val();
      var data = {
        'STATUS': 'updateQty',
        'RowID': RowID,
        'newQty': newQty
      }
      $('#input_chk').val(0);
      senddata(JSON.stringify(data));
    }

    function unlockfactory() {
      $('#factory1').attr('disabled', false);
      $('#factory1').removeClass('icon_select');
    }

    function UpdateRefDocNo() {
      var hptcode = '<?php echo $HptCode ?>';
      var docno = $("#docno").val();
      var deptCode = $('#Dep2 option:selected').attr("value");
      var RefDocNoArray = [];
      var FacCodeArray = [];
      var chk = 0;
      var chkref = 0;
      //get value from radio button
      $("#checkitemDirty:checked").each(function() {
        RefDocNoArray.push($(this).val());
        FacCodeArray.push($(this).data('fac'));
      });
      var ref = RefDocNoArray[0].substr(0, 2);
      var FacCode = FacCodeArray[0];
      for (var j = 0; j < FacCodeArray.length; j++) {
        if (FacCode != FacCodeArray[j]) {
          chk = 1;
        }
        var ref2 = RefDocNoArray[j].substr(0, 2);
        if (ref2 != ref) {
          chkref = 1;
        }
      }
      if (chk == 1 && chkref != 1) {
        swal({
          title: " ",
          text: " <?php echo $array['facerror'][$language]; ?>",
          type: "warning",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1000,
          closeOnConfirm: false
        });
        $(".chkbox").prop('checked', false);
        chk = 0;
      } else if (chk != 1 && chkref == 1) {
        swal({
          title: " ",
          text: " <?php echo $array['referror'][$language]; ?>",
          type: "warning",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1000,
          closeOnConfirm: false
        });
        $(".chkbox").prop('checked', false);
        chkref = 0;
      } else if (chk == 1 && chkref == 1) {
        swal({
          title: " ",
          text: " <?php echo $array['ref_and_fac_error'][$language]; ?>",
          type: "warning",
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1000,
          closeOnConfirm: false
        });
        $(".chkbox").prop('checked', false);
        chk = 0;
        chkref = 0;
      } else {
        var data = {
          'STATUS': 'UpdateRefDocNo',
          'xdocno': docno,
          'RefDocNoArray': RefDocNoArray,
          'selecta': 0,
          'deptCode': deptCode,
          'hptcode': hptcode,
          'FacCode': FacCode
        };
        $('#dialogRefDocNo').modal('toggle')
        senddata(JSON.stringify(data));
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

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/clean.php';
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
              var PmID = <?php echo $PmID; ?>;
              var HptCode = '<?php echo $HptCode; ?>';
              if (temp[0]['PmID'] != 2 && temp[0]['PmID'] != 3 && temp[0]['PmID'] != 7 && temp[0]['PmID'] != 5) {
                var Str1 = "<option value='' selected><?php echo $array['selecthospital'][$language]; ?></option>";
              } else {
                var Str1 = "";
                $('#Hos2').attr('disabled', true);
                $('#Hos2').addClass('icon_select');
                $('#Dep2').addClass('icon_select');
              }
              for (var i = 0; i < temp["Row"]; i++) {
                var Str = "<option value=" + temp[i]['HptCode'] + " id='getHot_" + i + "'>" + temp[i]['HptName'] + "</option>";
                Str1 += "<option value=" + temp[i]['HptCode1'] + ">" + temp[i]['HptName1'] + "</option>";
              }
              $("#hotpital").append(Str1);
              $("#Hos2").append(Str1);
              $("#hotpital").val(HptCode);
            } else if (temp["form"] == 'getfactory') {
              $("#factory1").empty();
              $("#factory2").empty();
              var Str = "<option value='' selected><?php echo $array['selectfactory'][$language]; ?></option>";
              for (var i = 0; i < temp["Rowx"]; i++) {
                Str += "<option value=" + temp[i]['FacCode'] + ">" + temp[i]['FacName'] + "</option>";
              }
              $("#factory1").append(Str);
              $("#factory2").append(Str);
            } else if (temp["form"] == 'getDepartment') {
              $("#department").empty();
              $("#Dep2").empty();
              var Str2 = "<option value=''><?php echo $array['selectdep'][$language]; ?></option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var Str = "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                Str2 += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                $("#department").append(Str);
                $("#Dep2").append(Str);
              }

              $("#bCreate").attr('disabled', false);
            } else if (temp["form"] == 'showRequest') {
              $("#unitrequest").empty();
              for (var i = 0; i < temp['countx']; i++) {
                var Str = "<option value=" + temp[i]['UnitCode'] + ">" + temp[i]['UnitName'] + "</option>";
                $("#unitrequest").append(Str);
              }
              $('#ModalRequest').modal("show");
            } else if ((temp["form"] == 'CreateDocument')) {
              swal({
                title: "<?php echo $array['createdocno'][$language]; ?>",
                text: temp[0]['DocNo'] + " <?php echo $array['success'][$language]; ?>",
                type: "success",
                showCancelButton: false,
                timer: 1000,
                confirmButtonText: 'Ok',
                showConfirmButton: false
              });
              setTimeout(function() {
                open_dirty_doc()
                parent.OnLoadPage();
              }, 1000);
              $("#TableItemDetail tbody").empty();
              $("#wTotal").val(0);
              // $("#bSave").text('<?php echo $array['save'][$language]; ?>');
              $('#bCreate').attr('disabled', true);
              $('#hover1').removeClass('mhee');
              $('#bCreate2').addClass('opacity');
              $("#total").prop('disabled', false);
              $("#docno").val(temp[0]['DocNo']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#RefDocNo").val("");
              $('#docdate').attr('disabled', true);
              $('#bCancel').attr('disabled', false);
              $('#bSave').attr('disabled', false);
              $('#bImport').attr('disabled', false);
              $('#hover2').addClass('mhee');
              $('#hover4').addClass('mhee');
              $('#hover5').addClass('mhee');
              $('#bSave2').removeClass('opacity');
              $('#bImport2').removeClass('opacity');
              $('#bCancel2').removeClass('opacity');
            } else if (temp["form"] == 'ShowDocument') {
              setTimeout(function() {
                parent.OnLoadPage();
              }, 500);
              $("#TableDocument tbody").empty();
              $("#TableItemDetail tbody").empty();
              var st1 = "style='font-size:24px;margin-left:3px;width:203px;'";
              if (temp['Count'] > 0) {
                for (var i = 0; i < temp['Count']; i++) {
                  var rowCount = $('#TableDocument >tbody >tr').length;
                  var chkDoc = "<label class='radio'style='margin-top: 7%;'><input type='radio' name='checkdocno' id='checkdocno'onclick='show_btn(\"" + temp[i]['DocNo'] + "\");' value='" + temp[i]['DocNo'] + "' ><span class='checkmark'></span></label>";
                  var Status = "";
                  var Style = "";
                  if (temp[i]['IsStatus'] == 1 || temp[i]['IsStatus'] == 2 || temp[i]['IsStatus'] == 3 || temp[i]['IsStatus'] == 4) {
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
                  var chkref = "<div class='row' style='margin:auto;'><select " + st1 + " class='form-control' ></div>";
                  for (var j = 0; j < temp['Cnt_' + temp[i]['DocNo']][i]; j++) {
                    chkref += "<option value=' '>" + temp['RefDocNo_' + temp[i]['DocNo'] + '_' + i][j] + "</option>";
                  }
                  chkref += "</select>";


                  $StrTr = "<tr id='tr" + temp[i]['DocNo'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 10%;' nowrap>" + chkDoc + "</td>" +
                    "<td style='width: 9%;' nowrap>" + temp[i]['DocDate'] + "</td>" +
                    "<td style='width: 11%;' nowrap>" + temp[i]['DocNo'] + "</td>" +
                    "<td style='width: 16%;' nowrap>" + chkref + "</td>" +
                    "<td style='width: 14%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['Record'] + "'>" + temp[i]['Record'] + "</td>" +
                    "<td style='width: 9%; overflow: hidden; text-overflow: ellipsis;' nowrap >" + temp[i]['RecNow'] + "</td>" +
                    "<td style='width: 9%;' nowrap>" + temp[i]['Total'] + "</td>" +
                    "<td style='width: 12%; overflow: hidden; text-overflow: ellipsis;' nowrap title='" + temp[i]['FacName'] + "'>" + temp[i]['FacName'] + "</td>" +
                    "<td " + Style + "nowrap>" + Status + "</td>" +
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
              $("#factory1").html(Str);
              $("#RefDocNo").empty();
              for (var i = 0; i < temp["Rowxx"]; i++) {
                var Str = "<option value='0'>" + temp[i]['RefDocNo'] + "</option>";
                $("#RefDocNo").append(Str);
              }
              if (temp["Rowxx"] != 0) {
                $("#RefDocNo").attr('disabled', false);
                $("#RefDocNo").removeClass('icon_select');
              } else {
                $("#RefDocNo").attr('disabled', true);
              }
              $('#bCreate').attr('disabled', true);
              $('#hover1').removeClass('mhee');
              $('#bCreate2').addClass('opacity');
              $('#home-tab').tab('show')
              $("#hotpital").val(temp[0]['HptName']);
              $("#hotpital").prop('disabled', true);
              $('#hotpital').addClass('icon_select');

              $("#TableItemDetail tbody").empty();
              $("#docno").val(temp[0]['DocNo']);
              $("#docdate").val(temp[0]['DocDate']);
              $("#recorder").val(temp[0]['Record']);
              $("#timerec").val(temp[0]['RecNow']);
              $("#wTotal").val(temp[0]['Total']);
              $("#IsStatus").val(temp[0]['IsStatus']);
              if (temp[0]['FacCode2'] == 0) {
                $("#factory1").attr('disabled', false);
                $("#factory1").removeClass('icon_select');
                $("#factory1").val('');
              } else {
                $("#factory1").attr('disabled', true);
                $("#factory1").addClass('icon_select');
                $("#factory1").val(temp[0]['FacCode2']);
              }

              if (temp[0]['IsStatus'] == 0) {
                var word = '<?php echo $array['save'][$language]; ?>';
                var changeBtn = "<i class='fa fa-save'></i>";
                changeBtn += "<div>" + word + "</div>";
                $('#icon_edit').html(changeBtn);
                $("#bImport").prop('disabled', false);
                $("#bSave").prop('disabled', false);
                $("#bCancel").prop('disabled', false);
                $('#hover2').addClass('mhee');
                $('#hover4').addClass('mhee');
                $('#hover5').addClass('mhee');
                $("#bImport2").removeClass('opacity');
                $("#bSave2").removeClass('opacity');
                $("#bCancel2").removeClass('opacity');
                $('#bPrint').attr('disabled', true);
                $('#bPrint2').addClass('opacity');
                $('#hover6').removeClass('mhee');

                $('#bPrintnew').attr('disabled', true);
                $('#bPrintnew2').addClass('opacity');
                $('#hover7').removeClass('mhee');
              } else if (temp[0]['IsStatus'] == 1 || temp[0]['IsStatus'] == 2 || temp[0]['IsStatus'] == 3 || temp[0]['IsStatus'] == 4 || temp[0]['IsStatus'] == 5) {
                var word = '<?php echo $array['edit'][$language]; ?>';
                var changeBtn = "<i class='fas fa-edit'></i>";
                changeBtn += "<div>" + word + "</div>";
                $('#icon_edit').html(changeBtn);
                if (temp[0]['IsStatus'] != 1) {
                  $("#hover5").removeClass('mhee');
                  $("#bCancel").prop('disabled', true);
                  $("#bCancel2").addClass('opacity');
                } else {
                  $("#hover5").addClass('mhee');
                  $("#bCancel").prop('disabled', false);
                  $("#bCancel2").removeClass('opacity');
                }
                $("#bImport").prop('disabled', true);
                $("#bDelete").prop('disabled', true);
                $("#bSave").prop('disabled', false);
                $('#hover4').addClass('mhee');
                $("#bSave2").removeClass('opacity');
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover6').addClass('mhee');
                $('#bPrintnew').attr('disabled', false);
                $('#bPrintnew2').removeClass('opacity');
                $('#hover7').addClass('mhee');
              } else {
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
                $('#bPrint').attr('disabled', false);
                $('#bPrint2').removeClass('opacity');
                $('#hover6').addClass('mhee');
                $('#bPrintnew').attr('disabled', true);
                $('#bPrintnew2').addClass('opacity');
                $('#hover7').removeClass('mhee');
                $('#qty1_' + i).prop('disabled', true);
                $('#weight_' + i).prop('disabled', true);
                $('#price_' + i).prop('disabled', true);

                $('#unit' + i).prop('disabled', true);
              }
              ShowDetail();
            } else if (temp["form"] == 'getImport' || temp["form"] == 'ShowDetail') {
              $("#TableItemDetail tbody").empty();
              if (temp["Row"] > 0)
                $("#wTotal").val(temp[0]['Total']);
              else
                $("#wTotal").val(0);
              var st1 = "style='font-size:24px;margin-left:3px;width:153px;'";
              var isStatus = $("#IsStatus").val();
              for (var i = 0; i < temp["Row"]; i++) {
                var rowCount = $('#TableItem >tbody >tr').length;
                var chkunit = "<select " + st1 + " onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' style='font-size:24px;' id='Unit_" + i + "'>";
                $.each(temp['Unit'], function(key, val) {
                  if (temp[i]['UnitCode'] == val.UnitCode) {
                    chkunit += '<option selected value="' + val.UnitCode + ',' + val.MpCode + ',' + val.Multiply + '">' + val.UnitName + '</option>';
                  } else {
                    chkunit += '<option value="' + val.UnitCode + ',' + val.MpCode + ',' + val.Multiply + '">' + val.UnitName + '</option>';
                  }
                });
                chkunit += "</select>";

                var chkDoc = "<div class='form-inline'><label class='radio'style='margin:0px!important;'><input type='radio' name='checkrow' id='checkrow' class='checkrow_" + i + "' value='" + temp[i]['RowID'] + "," + temp[i]['ItemName'] + "'  onclick='resetradio(\"" + i + "\")'><span class='checkmark'></span><label style='margin-left:27px;'> " + (i + 1) + "</label></label></div>";

                var Qty = "<div class='row' style='margin-left:0px;'><input autocomplete='off' class='form-control numonly chk_qty' style=' width:87px;height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='qty1_" + i + "' onkeyup='updateQty(\"" + temp[i]['RowID'] + "\",\"" + i + "\");'  value='" + temp[i]['Qty'] + "' ></div>";

                var Weight = "<div class='row' style='margin-left:2px;'><input autocomplete='off' class='form-control numonly chk_weight' style=' width:87px;height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='weight_" + i + "' value='" + temp[i]['Weight'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control ' style='height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='price_" + i + "' value='" + temp[i]['Price'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                var Detail = "<div class='row' style='margin-left:2px;'><input class='form-control ' autocomplete='off' style='height:40px; margin-left:3px; margin-right:3px; text-align:center;font-size:24px;' id='Detail_" + i + "' value='" + temp[i]['Detail'] + "' OnBlur='updateDetail(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 9%;' nowrap>" + chkDoc + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 18%;' nowrap>" + temp[i]['ItemCode'] + "</td>" +
                  "<td style='text-overflow: ellipsis;overflow: hidden;width: 17%;' nowrap>" + temp[i]['ItemName'] + "</td>" +
                  "<td style='width: 18%;font-size:24px;' nowrap>" + chkunit + "</td>" +
                  "<td style='width: 12%;' nowrap>" + Qty + "</td>" +
                  "<td style='width: 12%;' nowrap>" + Weight + "</td>" +
                  "<td style='width: 12%;' nowrap>" + Detail + "</td>" +
                  "</tr>";
                if (rowCount == 0) {
                  $('#bSaveadd').attr('disabled', true);
                  $('#bSaveadd2').addClass('opacity');
                  $("#countcheck").val("0");
                  $("#TableItemDetail tbody").append($StrTR);
                } else {
                  $('#bSaveadd').attr('disabled', true);
                  $('#bSaveadd2').addClass('opacity');
                  $("#countcheck").val("0");
                  $('#TableItemDetail tbody:last-child').append($StrTR);
                }
                if (isStatus == 0) {
                  $('#Detail_' + i).prop('disabled', false);
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
                  $('#Detail_' + i).prop('disabled', true);
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
              var st1 = "style='font-size:24px;margin-left:-10px; width:150px;font-size:24px;'";
              var st2 = "style='height:40px;width:60px; font-size: 20px;margin-left:3px; margin-right:3px; text-align:center;'"
              $("#TableItem tbody").empty();
              if (temp["Row"] > 0) {
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableItem >tbody >tr').length;
                  var chkunit = "<div class='row' style='margin:auto;'><select " + st1 + " onchange='convertUnit(\"" + temp[i]['RowID'] + "\",this)' class='form-control' id='iUnit_" + i + "'></div>";
                  for (var j = 0; j < temp['Cnt_' + temp[i]['ItemCode']][i]; j++) {
                    if (temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] == temp[i]['UnitCode'])
                      chkunit += "<option selected value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                    else
                      chkunit += "<option value=" + temp['MpCode_' + temp[i]['ItemCode'] + '_' + i][j] + ">" + temp['UnitName_' + temp[i]['ItemCode'] + '_' + i][j] + "</option>";
                  }
                  chkunit += "</select>";

                  var chkDoc = "<input type='checkbox' id='checkrow_" + i + "'  name='checkitem' onclick='dis2(\"" + i + "\")' class='checkitem' value='" + i + "'><input type='hidden' id='RowID" + i + "' value='" + temp[i]['ItemCode'] + "'>";
                  var Qty = "<div class='row' style='margin-left:2px;'><button class='btn btn-danger numonly' style='height:40px;width:32px;' onclick='subtractnum(\"" + i + "\")'>-</button><input class='form-control numonly' " + st2 + " id='iqty" + i + "' value='1' ><button class='btn btn-success' style='height:40px;width:32px;' onclick='addnum(\"" + i + "\")'>+</button></div>";
                  var Weight = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' autocomplete='off' style='font-size: 20px;height:40px;width:110px; margin-left:3px; margin-right:3px; text-align:center;' id='iweight" + i + "' placeholder='0'></div>";


                  $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 12%;' nowrap>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                    "<td style='width: 43%; overflow-x:auto; text-overflow: ellipsis;overflow: hidden; cursor: pointer;' title='" + temp[i]['ItemCode'] + "' nowrap onclick='OpenDialogUsageCode(\"" + temp[i]['ItemCode'] + "\")''>" + temp[i]['ItemName'] + "</td>" +
                    "<td style='width: 15%;' nowrap>" + chkunit + "</td>" +
                    "<td style='width: 15%;' nowrap align='center'>" + Qty + "</td>" +
                    "<td style='width: 15%;' nowrap align='center'>" + Weight + "</td>" +
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
            } else if ((temp["form"] == 'ShowUsageCode')) {
              var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-size:24px;'";
              var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-size:32px;'"
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
                $StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 10%;'>" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                  "<td style='width: 20%;'>" + temp[i]['UsageCode'] + "</td>" +
                  "<td style='width: 40%;'>" + temp[i]['ItemName'] + " [ " + temp[i]['RowID'] + " ]</td>" +
                  "<td style='width: 15%;'>" + chkunit + "</td>" +
                  "<td style='width: 13%;' align='center'>1</td>" +
                  "</tr>";
                if (rowCount == 0) {
                  $("#TableUsageCode tbody").append($StrTR);
                } else {
                  $('#TableUsageCode tbody:last-child').append($StrTR);
                }
              }
            } else if (temp['form'] == "get_dirty_doc") {
              if (temp["count2"] > 0) {
                var st1 = "style='font-size:18px;margin-left:3px; width:100px;font-size:24px;'";
                var st2 = "style='height:40px;width:60px; margin-left:0px; text-align:center;font-size:32px;'"
                var checkitem = $("#checkitem").val();
                $("#TableRefDocNo tbody").empty();
                for (var i = 0; i < temp["Row"]; i++) {
                  var rowCount = $('#TableRefDocNo >tbody >tr').length;
                  var chkDoc = "<input type='checkbox' class='chkbox'  onclick='disRef()' name='checkitem' id='checkitemDirty'  value='" + temp[i]['RefDocNo'] + "'  data-fac='" + temp[i]['FacCode'] + "' ><input type='hidden' id='RowId" + i + "' value='" + temp[i]['RefDocNo'] + "'>";
                  $StrTR = "<tr id='tr" + temp[i]['RefDocNo'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                    "<td style='width: 15%;' >" + chkDoc + " <label style='margin-left:10px;'> " + (i + 1) + "</label></td>" +
                    "<td style='width: 27%;'>" + temp[i]['RefDocNo'] + "</td>" +
                    "<td style='width: 33%;'>" + temp[i]['DocDate'] + "</td>" +
                    "<td style='width: -4%;'>" + temp[i]['FacName'] + "</td>" +
                    "</tr>";
                  if (rowCount == 0) {
                    $("#TableRefDocNo tbody").append($StrTR);
                  } else {
                    $('#TableRefDocNo tbody:last-child').append($StrTR);
                  }
                }
              } else {
                $("#TableRefDocNo tbody").empty();
                var Str = "<tr width='100%' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'><td style='width:100%' class='text-center'><?php echo $array['notfoundmsg'][$language]; ?></td></tr>";
                $('#TableRefDocNo tbody:last-child').append(Str);
              }
            } else if (temp['form'] == "UpdateDetailWeight") {
              $('#wTotal').val(temp['Weight2']);

            } else if (temp['form'] == "UpdateRefDocNo") {
              $('#factory1').val(temp['FacCode']);
              $('#RefDocNo').removeClass('icon_select');

              $("#RefDocNo").empty();
              for (var i = 0; i < temp["Rowx"]; i++) {
                var Str = "<option value='0'>" + temp[i]['RefDocNo'] + "</option>";
                $("#RefDocNo").append(Str);
              }
              if (temp['FacCode'] != null) {
                OpenDialogItem();
              }
            } else if (temp['form'] == "savefactory") {
              $('#factory1').val(temp['FacCode']);
              $('#factory1').attr('disabled', true);
              $('#factory1').addClass('icon_select');
              $('#dialogfactory').modal('toggle');
              OpenDialogItem();
            } else if (temp['form'] == "SaveBill") {
              if (temp['countpercent'] > 0) {
                for (var i = 0; i < temp['countpercent']; i++) {
                  if (temp[i]['countMailpercent'] > 0) {
                    for (var j = 0; j < temp[i]['countMailpercent']; j++) {
                      var HptName = temp[0]['HptName'];
                      var HptNameTH = temp[0]['HptNameTH'];
                      var Total1 = temp[0]['Total1'];
                      var Total2 = temp[0]['Total2'];
                      var DocNoC = temp[0]['DocNo1'];
                      var DocNoD = temp[0]['DocNo2'];
                      var Percent = temp[0]['Percent'];
                      var email = temp[j]['email'];
                      var URL = '../process/sendMail_percent.php';
                      $.ajax({
                        url: URL,
                        method: "POST",
                        data: {
                          HptName: HptName,
                          Total1: Total1,
                          Total2: Total2,
                          Precent: Percent,
                          email: email,
                          DocNoC: DocNoC,
                          DocNoD: DocNoD,
                          HptNameTH: HptNameTH
                        },
                        success: function(data) {
                          console.log['success'];
                        }
                      });
                    }
                  }
                }
              }
            } else if (temp['form'] == "chk_percent") {
              result = '';
              if (temp["Row"] > 0) {
                for (var i = 0; i < temp['Row']; i++) {
                  result += "<tr>" +
                    '<td nowrap style="width: 30%;" class="text-left">' + temp[0]['DocNo'] + '</td>' +
                    '<td nowrap style="width: 35%;" class="text-left">' + temp[0]['Percent'] + '%' + '</td>' +
                    '<td nowrap style="width: 32%;" class="text-right">' + temp[0]['over'] + '%' + '</td>' +
                    "</tr>";
                }
                $("#detail_percent").html(result);
                $('#alert_percent').modal('show');
                $('#input_chk').val(1);
              } else if (temp["Row"] == "No") {
                SaveBill(1);
              }
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

            $("#docnofield").val(temp[0]['DocNo']);
            $("#TableDocumentSS tbody").empty();
            $("#TableSendSterileDetail tbody").empty();
            $("#TableUsageCode tbody").empty();
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
          alert(err);
        }
      });
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
        var url = "../report/Report_Clean_tc.php?DocNo=" + docno + "&lang=" + lang + "&HptCode=" + HptCode;
        window.open(url);
      } else {
        swal({
          title: '',
          text: '<?php echo $array['docfirst'][$language]; ?>',
          type: 'info',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          showConfirmButton: false,
          timer: 2000,
          confirmButtonText: 'Ok'
        })
      }
    }

    function PrintData2() {
      var docno = $('#docno').val();
      var lang = '<?php echo $language; ?>';
      if (docno != "" && docno != undefined) {
        var url = "../report/Report_Clean2.php?DocNo=" + docno + "&lang=" + lang;
        window.open(url);
      } else {
        swal({
          title: '',
          text: '<?php echo $array['docfirst'][$language]; ?>',
          type: 'info',
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
    /* ======================================== */
    a.nav-link {
      width: auto !important;
    }

    .datepicker {
      z-index: 9999 !important
    }

    .hidden {
      visibility: hidden;
    }

    button,
    input[id^='qty'],
    input[id^='order'],
    input[id^='max'] {
      font-size: 24px !important;
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

    /* top-right border-radius */
    table tr:first-child th:last-child {
      border-top-right-radius: 15px;
    }

    table tr:first-child th:last-child {
      border-bottom-right-radius: 15px;
    }

    /* bottom-right border-radius */
    table tr:last-child td:last-child {
      border-bottom-right-radius: 6px;
    }

    .table th,
    .table td {
      border-top: none !important;
    }

    .table>thead>tr>th {
      background-color: #1659a2;
    }

    .only1:disabled,
    .form-control[readonly] {
      background-color: transparent !important;
      opacity: 1;
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

    .opacity {
      opacity: 0.5;
    }

    .modal-content1 {
      width: 72% !important;
      right: -15% !important;
      position: relative;
      display: -ms-flexbox;
      display: flex;
      -ms-flex-direction: column;
      flex-direction: column;
      width: 100%;
      pointer-events: auto;
      background-color: #fff;
      background-clip: padding-box;
      border: 1px solid rgba(0, 0, 0, .2);
      border-radius: .3rem;
      outline: 0;
    }

    @media (min-width: 992px) and (max-width: 1199.98px) {

      .icon {
        padding-top: 6px;
        padding-left: 23px;
      }

      .sidenav a {
        font-size: 21px;

      }

      .kbw-signature {
        width: 100%;
        height: 240px;
      }
    }

    /* ======================================== */
  </style>

</head>

<body id="page-top">

  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
    <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][2][$language]; ?></li>
  </ol>
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>
  <input type="hidden" id='input_chk' value='0'>
  <div id="wrapper">
    <div id="content-wrapper">
      <div class="row">
        <div class="col-md-12" style='padding-left: 26px;' id='switch_col'>
          <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['titleclean'][$language]; ?></a>
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
                            <select class="form-control col-sm-7 icon_select checkblank3" style="font-size:22px;" id="hotpital" onchange="getfactory();" <?php if ($PmID == 2 || $PmID == 3 || $PmID == 4 || $PmID == 5 || $PmID == 7) echo 'disabled="true" '; ?>>
                            </select>
                            <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
                            <select class="form-control col-sm-7 icon_select" style="font-size:22px;" id="department" disabled="true">
                            </select>
                          </div>
                        </div>
                      </div>
                      <!-- =================================================================== -->
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docdate'][$language]; ?></label>
                            <!-- <input type="text" autocomplete="off"  style="font-size:22px;" disabled="true"  class="form-control col-sm-7 only1"  name="searchitem" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>" > -->
                            <input type="text" autocomplete="off" style="font-size:22px;" class="form-control col-sm-7 datepicker-here numonly charonly only only1 " disabled="true" id="docdate" placeholder="<?php echo $array['docdate'][$language]; ?>">
                            <label id="rem4" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['docno'][$language]; ?></label>
                            <input type="text" autocomplete="off" style="font-size:22px;" disabled="true" class="form-control col-sm-7 only1" name="searchitem" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>">
                          </div>
                        </div>
                      </div>
                      <!-- =================================================================== -->
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['refdocno'][$language]; ?></label>
                            <select class="form-control col-sm-7 icon_select " style="font-size:22px;" disabled="true" autocomplete="off" id='RefDocNo'> </select>
                            <label id="rem1" hidden class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['employee'][$language]; ?></label>
                            <input type="text" autocomplete="off" class="form-control col-sm-7 only1" disabled="true" style="font-size:22px;width:220px;" name="searchitem" id="recorder" placeholder="<?php echo $array['employee'][$language]; ?>">
                          </div>
                        </div>
                      </div>
                      <!-- =================================================================== -->
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label "><?php echo $array['time'][$language]; ?></label>
                            <input type="text" autocomplete="off" class="form-control col-sm-7 only1" disabled="true" class="form-control" style="font-size:24px;width:220px;" name="searchitem" id="timerec" placeholder="<?php echo $array['time'][$language]; ?>">
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label "><?php echo $array['totalweight'][$language]; ?></label>
                            <input class="form-control col-sm-7 only1" autocomplete="off" disabled="true" style="font-size:20px;width:220px;height:40px;padding-top:6px;" id='wTotal' placeholder="0.00">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-6">
                          <div class='form-group row'>
                            <label class="col-sm-4 col-form-label " style="font-size:24px;"><?php echo $array['factory'][$language]; ?></label>
                            <select class="form-control col-sm-7 icon_select checkblank" disabled="true" style="font-size:22px;" onchange="removeClassBorder2();" id="factory1">
                            </select>
                            <label id="rem2" hidden class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
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
                  <div class="menu" id="hover2">
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
                  <div class="menu" id="hover3">
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
                  <div class="menu" id="hover4">
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
                  <div class="menu" id="hover5">
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
                  <div class="menu " id="hover7">
                    <div class="d-flex justify-content-center">
                      <div class="circle9 d-flex justify-content-center opacity" id="bPrintnew2">
                        <button class="btn" onclick="PrintData2()" id="bPrintnew" disabled="true">
                          <i class="fas fa-print"></i>
                          <div>
                            <?php echo $array['print2'][$language]; ?>
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
                  <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItemDetail" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:24px;">
                      <tr role="row">
                        <th style="width: 3%;">&nbsp;</th>
                        <th style='width: 6%;' nowrap><?php echo $array['sn'][$language]; ?></th>
                        <th style='width: 18%;' nowrap><?php echo $array['code'][$language]; ?></th>
                        <th style='width: 9%;' nowrap><?php echo $array['item'][$language]; ?></th>
                        <th style='width: 27%;' nowrap>
                          <center><?php echo $array['unit'][$language]; ?></center>
                        </th>
                        <th style='width: 5%;' nowrap><?php echo $array['qty'][$language]; ?></th>
                        <th style='padding-left: 4%;width: 17%;' nowrap>
                          <center><?php echo $array['weight'][$language]; ?></center>
                        </th>
                        <th style='width: 15%;padding-right: 3%;' nowrap>
                          <center><?php echo $array['detail'][$language]; ?></center>
                        </th>
                      </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled mhee555" style="font-size:23px;height:630px;">
                    </tbody>
                  </table>
                </div> <!-- tag column 1 -->
              </div>
            </div>

            <!-- search document -->
            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
              <div class="row mt-3">
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style='font-size:24px;' id="Hos2" onchange="getDepartment();">
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <select class="form-control" style='font-size:24px;' id="Dep2" disabled="true">
                    </select>
                  </div>
                </div>
                <div class="col-md-2">
                  <div class="row" style="font-size:24px;margin-left:2px;">
                    <input type="text" autocomplete="off" style="font-size:22px;" placeholder="<?php echo $array['selectdate'][$language]; ?>" class="form-control datepicker-here numonly charonly" id="datepicker1" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy'>
                  </div>
                </div>
                <div class="col-md-6 mhee">
                  <div class="row" style="margin-left:2px;">

                    <select class="form-control" autocomplete="off" style="font-size:24px;width:27%;" name="process" id="process">
                      <option value="0"><?php echo $array['processchooce'][$language]; ?></option>
                      <option value="1">on process</option>
                      <option value="2">completed</option>
                      <option value="3">cancel </option>
                    </select>

                    <input type="text" class="form-control" autocomplete="off" style="font-size:24px;width:24%;margin-left: 3%;" name="searchdocument" id="searchdocument" placeholder="<?php echo $array['searchplace'][$language]; ?>">
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
                        <th style='width: 9%;' nowrap><?php echo $array['docdate'][$language]; ?></th>
                        <th style='width: 11%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                        <th style='width: 15%;' nowrap><?php echo $array['refdocno'][$language]; ?></th>
                        <th style='width: 14%;padding-left: 1%;' nowrap><?php echo $array['employee'][$language]; ?></th>
                        <th style='width: 9%;' nowrap><?php echo $array['time'][$language]; ?></th>
                        <th style='width: 9%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                        <th style='width: 12%;' nowrap><?php echo $array['factory'][$language]; ?></th>
                        <th style='width: 11%;' nowrap><?php echo $array['status'][$language]; ?></th>
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


        <!-- </div> -->
      </div>
    </div>
  </div>


  <!-- -----------------------------Custome1------------------------------------ -->
  <div class="modal fade" id="dialogItemCode" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
              <div class="col-md-6">
                <div class='form-group row'>
                  <label class="col-sm-4 col-form-label text-right pr-5" style="margin-left: -5%;"><?php echo $array['Searchitem2'][$language]; ?></label>
                  <input type="text" class="form-control col-sm-9" style="margin-left: -5%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['Searchitem2'][$language]; ?>">
                </div>
              </div>
              <!-- serach----------------------- -->
              <div class="search_custom col-md-2">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="ShowItem()" id="bSave">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>

              <div class="search_custom col-md-2">
                <div class="import_1 d-flex justify-content-start opacity" id="bSaveadd2">
                  <button class="btn dis" onclick="getImport(1)" id="bSaveadd" disabled="true">
                    <i class="fas fa-file-import mr-2 pt-1"></i>
                    <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
              </div>

              <div class="search_custom col-md-2">
                <div class="circle22 d-flex justify-content-start">
                  <button class="btn" onclick="showRequest()">
                    <i class="fas fa-plus mr-2"></i>
                    <?php echo $array['addrequest'][$language]; ?>
                  </button>
                </div>
              </div>
              <!-- end serach----------------------- -->
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;">
              <thead style="font-size:24px;">
                <tr role="row">
                  <input type="text" hidden id="countcheck">
                  <th style='width: 12%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <th style='width: 38%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 23%;' nowrap>
                    <center><?php echo $array['unit'][$language]; ?></center>
                  </th>
                  <th style='width: 15%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                  <th style='width: 12%;' nowrap><?php echo $array['weight'][$language]; ?></th>
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

  <!-- custom modal2 -->
  <div class="modal fade" id="dialogRefDocNo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <?php echo $array['refdocno'][$language]; ?>
          <button type="button" onclick="get_factory();" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
              <div class="col-md-8">
                <div class='form-group row'>
                  <!-- <label class="col-sm-4 col-form-label text-right pr-5" style="margin-left: -6%;"><?php echo $array['serchref'][$language]; ?></label> -->
                  <input type="text" class="form-control col-sm-4" style="margin-left: 3%;font-size: 20px;" name="searchitem1" id="searchitem1" placeholder="<?php echo $array['serchref'][$language]; ?>">
                  <input type="text" class="form-control col-sm-3 datepicker-here" autocomplete="off" style="margin-left: 1%;font-size: 20px;" id="datepickerRef1" name="searchitem1" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' placeholder="<?php echo $array['ddmmyyyy'][$language]; ?>">
                  <input type="text" class="form-control col-sm-3 datepicker-here" autocomplete="off" style="margin-left: 1%;font-size: 20px;" id="datepickerRef2" name="searchitem2" data-language=<?php echo $language ?> data-date-format='dd-mm-yyyy' placeholder="<?php echo $array['ddmmyyyy'][$language]; ?>">
                </div>
              </div>
              <div class="search_custom col-md-2" style="margin-left: -8%;">
                <div class="search_1 d-flex justify-content-start">
                  <button class="btn" onclick="get_dirty_doc()" id="bSave">
                    <i class="fas fa-search mr-2"></i>
                    <?php echo $array['search'][$language]; ?>
                  </button>
                </div>
              </div>
              <div class="search_custom col-md-2" style="margin-left: -5%;">
                <div class="import_1 d-flex justify-content-start opaciy">
                  <button class="btn" onclick="UpdateRefDocNo()" id="bsaveRef" disabled="true">
                    <i class="fas fa-file-import pt-1 mr-2"></i>
                    <?php echo $array['import'][$language]; ?>
                  </button>
                </div>
              </div>
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TableRefDocNo" cellspacing="0" role="grid">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 15%;' nowrap><?php echo $array['no'][$language]; ?></th>
                  <th style='width: 27%;' nowrap><?php echo $array['refdocno'][$language]; ?></th>
                  <th style='width: 33%;' nowrap><?php echo $array['selectdateref'][$language]; ?></th>
                  <th style='width: -4%;' nowrap><?php echo $array['factory'][$language]; ?></th>
                </tr>
              </thead>
              <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:300px;">
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- custom modal3 -->
  <div class="modal fade" id="alert_percent" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['alertPercent'][$language]; ?></h2>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <div class="row">
            </div>
            <table class="table table-fixed table-condensed table-striped" id="TablePar" cellspacing="0" role="grid">
              <thead style="font-size:24px;">
                <tr role="row">
                  <th style='width: 25%;' nowrap class='text-left'><?php echo $array['docno'][$language]; ?></th>
                  <th style='width: 35%;' nowrap class='text-left'><?php echo $array['TotalPercent'][$language]; ?></th>
                  <th style='width: 40%;' nowrap class='text-right'><?php echo $array['overPercent'][$language]; ?></th>
                </tr>
              </thead>
              <tbody id="detail_percent" class="nicescrolled" style="font-size:23px;height:auto;">
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="SaveBill(1)" class="btn btn-success" style="width: 15%;"><?php echo $array['wantsave'][$language]; ?></button>
          <button type="button" class="btn btn-danger" data-dismiss="modal" style="width: 10%;"><?php echo $array['cancel'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="dialogfactory" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content1">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel"><?php echo $array['selectfactory'][$language]; ?></h5>
          <button type="button" onclick="unlockfactory();" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <select class="form-control col-sm-12 " style="font-size:22px;" id="factory2">
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" onclick="savefactory();" class="btn btn-success" style="width: 50%;"><?php echo $array['wantsave'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="ModalSign" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: rgba(64, 64, 64, 0.75)!important;">
    <div class="modal-dialog" role="document">
      <div class="modal-content" style="margin-top: 50px;background-color:#fff;">
        <div class="modal-header">
          <h2 class="modal-title"><?php echo $array['Signature'][$language]; ?></h2>
        </div>
        <div class="modal-body">
          <div id="sig" class="kbw-signature"></div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:10%;" class="btn btn-success" id="svg"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger" id="clear"><?php echo $array['clear'][$language]; ?></button>
        </div>
      </div>
    </div>
  </div>




  <!-- -----------------------------Custome1------------------------------------ -->
  <div class="modal fade" id="ModalRequest" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style=" margin-left: 26%;">
      <div class="modal-content" style="width: 110% !important;">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card-body" style="padding:0px;">
            <table class="table table-fixed table-condensed table-striped" id="TableRequest" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;">
              <thead style="font-size:24px;">
                <tr role="row">
                  <input type="text" hidden id="countcheck">
                  <th style='width: 31%;' nowrap><?php echo $array['item'][$language]; ?></th>
                  <th style='width: 30%;' nowrap>
                    <center><?php echo $array['unit'][$language]; ?></center>
                  </th>
                  <th style='width: 23%;padding-left: 8%;' nowrap><?php echo $array['numofpiece'][$language]; ?></th>
                  <th style='width: 16%;' nowrap><?php echo $array['weight'][$language]; ?></th>
                </tr>

              </thead>
              <tbody id="tbody1_modalRequest" class="nicescrolled" style="font-size:23px;height:70px;">
                <tr>
                  <td style='width: 40%;' nowrap><input type="text" autocomplete="off" class="form-control" style="width: 100%;font-size: 24px;" id="NameRequest" placeholder="กรุณากรอกชื่อรายการ"></td>
                  <td style='width: 27%;' nowrap><select id=unitrequest style=" width: 100%;font-size: 24px;" class="form-control"> </select> </td>
                  <td style='width: 15%;' nowrap><input type="text" autocomplete="off" class="form-control numonly" style="width: 100%;font-size: 24px;text-align: center;" id="qtyRequest" placeholder="0"></td>
                  <td style='width: 15%;' nowrap><input type="text" autocomplete="off" class="form-control numonly" style="width: 100%;font-size: 24px;text-align: center;" id="weightRequest" placeholder="0.00"></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" style="width:10%;" class="btn btn-success" id="saverequest" onclick="SaveRequest()"><?php echo $array['confirm'][$language]; ?></button>
          <button type="button" style="width:10%;" class="btn btn-danger" data-dismiss="modal"><?php echo $array['cancel'][$language]; ?></button>
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
  <script src="../assets-sign/js/jquery-ui.min.js"></script>
  <script src="../assets-sign/js/jquery.signature.js"></script>
  <script>
    $(function() {
      var sig = $('#sig').signature();
      $('#clear').click(function() {
        sig.signature('clear');
      });
      $('#svg').click(function() {
        var SignSVG = sig.signature('toSVG');
        var DocNo = $('#docno').val();
        console.log(SignSVG);
        $.ajax({
          url: '../process/UpdateSign.php',
          dataType: 'text',
          cache: false,
          data: {
            SignSVG: SignSVG,
            DocNo: DocNo,
            Table: "clean",
            Column: "signature"
          },
          type: 'post',
          success: function(data) {
            swal({
              title: '',
              text: '<?php echo $array['savesuccess'][$language]; ?>',
              type: 'success',
              showCancelButton: false,
              showConfirmButton: false,
              timer: 1500,
            });
            $('#ModalSign').modal('toggle');
          }
        });
      });
    });
  </script>
</body>

</html>