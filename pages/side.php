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

  <title>
    <?php echo $array['department'][$language]; ?>
  </title>

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

  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
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

  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />

  <script type="text/javascript">
    var summary = [];

    $(document).ready(function(e) {
      // $("#searchitem").on("keyup", function() 
      // {
      // var value = $(this).val().toLowerCase();
      //     $("#TableItem tbody tr").filter(function() 
      //     {
      //         $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
      //     });
      // });
      $('#rem1').hide();
      $('#rem2').hide();
      $('#rem3').hide();
      $('#rem4').hide();
      $('#rem5').hide();
      $('#rem6').hide();
      $('#rem7').hide();
      $('#rem8').hide();
      $('#rem9').hide();
      $('#rem10').hide();
      $('#rem11').hide();
      getHotpital();
      $('#addhot').show();
      $('#adduser').hide();
      //On create
      Blankinput();
      $('.TagImage').bind('click', {
        imgId: $(this).attr('id')
      }, function(evt) {
        alert(evt.imgId);
      });
      var keyword = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'Keyword': keyword
      };

      console.log(JSON.stringify(data));
      senddata(JSON.stringify(data));
      $('#searchitem').keyup(function(e) {
        if (e.keyCode == 13) {
          ShowItem();
        }
      });

      $('#alertTime').keyup(function(e) {
        if ($(this).val() > 59) {
          $(this).val(59);
        }
      });



      $('.editable').click(function() {
        alert('hi');
      });

      $('.numonly').on('input', function() {
        this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
      });

      $('.charonly').on('input', function() {
        this.value = this.value.replace(/[^a-zA-Z0-9. ]/g, ''); //<-- replace all other than given set of values
      });

      $('.charonlyTH').on('input', function() {
        this.value = this.value.replace(/[^???-????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????0-9. ]/g, ''); //<-- replace all other than given set of values
      });

    }).click(function(e) {
      parent.afk();
    }).keyup(function(e) {
      parent.afk();
    });

    dialog = jqui("#dialog").dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "?????????": function() {
          dialog.dialog("close");
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui("#dialogreq").button().on("click", function() {
      dialog.dialog("open");
    });



    function getHotpital() {
      var data2 = {
        'STATUS': 'getHotpital'
      };
      senddata(JSON.stringify(data2));
    }


    function checkblank2() {
      $('.checkblank2').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).addClass('border-danger');
        } else {
          $(this).removeClass('border-danger');
        }
      });
    }

    function removeClassBorder1() {
      $('#host').removeClass('border-danger');
    }


    function ShowItem() {
      //var dept = $('#Deptsel').val();
      var keyword = $('#searchitem').val();
      var data = {
        'STATUS': 'ShowItem',
        'Keyword': keyword
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
      var sitepath = $('#sitepath').val();
      var PayerCode = $('#PayerCode').val();
      var idcontract = $('#idcontract').val();
      var ContractName = $('#ContractName').val();
      var Position = $('#Position').val();
      var phone = $('#phone').val();
      var HptCode1 = $('#HptCode1').val();
      var HptCode = $('#HptCode').val();
      var HptName = $('#HptName').val();
      var HptNameTH = $('#HptNameTH').val();
      var LabSiteCode = $('#LabSiteCode').val();
      var alertTime = $('#alertTime').val();
      var Signature = 0;
      var stock = 0;
      var money = 0;
      var par = 0;
      if ($('#par').is(':checked')) {
        par = 1
      }
      if ($('#Signature').is(':checked')) {
        Signature = 1
      }
      if ($('#stock').is(':checked')) {
        stock = 1
      };
      if ($('#money').is(':checked')) {
        money = 1
      };
      if (count == 0) {
        if (HptCode != "") {
          swal({
            title: "<?php echo $array['addoredit'][$language]; ?>",
            // text: "<?php echo $array['addoredit'][$language]; ?>",
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
                'HptCode': HptCode,
                'HptCode1': HptCode1,
                'ContractName': ContractName,
                'Position': Position,
                'phone': phone,
                'HptName': HptName,
                'idcontract': idcontract,
                'HptNameTH': HptNameTH,
                'money': money,
                'sitepath': sitepath,
                'PayerCode': PayerCode,
                'Signature': Signature,
                'stock': stock,
                'LabSiteCode': LabSiteCode,
                'alertTime': alertTime,
                'par': par
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
            if (HptCode == "" || HptCode == undefined) {
              $('#rem1').show().css("color", "red");
              $('#form1').removeClass('form-group');
            }
            if (HptName == "" || HptName == undefined) {
              $('#rem2').show().css("color", "red");
              $('#form2').removeClass('form-group');
            }
            if (HptNameTH == "" || HptNameTH == undefined) {
              $('#rem7').show().css("color", "red");
              // $('#form3').removeClass('form-group');
            }
            if (sitepath == "" || sitepath == undefined) {
              $('#rem8').show().css("color", "red");
              // $('#form8').removeClass('form-group');
            }

            if (PayerCode == "" || PayerCode == undefined) {
              $('#rem9').show().css("color", "red");
              // $('#form8').removeClass('form-group');
            }

            if (LabSiteCode == "" || LabSiteCode == undefined) {
              $('#rem10').show().css("color", "red");
              // $('#form8').removeClass('form-group');
            }
            if (alertTime == "" || alertTime == undefined) {
              $('#rem11').show().css("color", "red");
              // $('#form8').removeClass('form-group');
            }

          }
        });
      }
    }

    function resetinput() {

      var sitepath = $('#sitepath').val();
      var ContractName = $('#ContractName').val();
      var Position = $('#Position').val();
      var phone = $('#phone').val();
      var HptCode = $('#HptCode').val();
      var HptName = $('#HptName').val();
      var HptNameTH = $('#HptNameTH').val();
      var host = $('#host').val();
      var LabSiteCode = $('#LabSiteCode').val();
      var alertTime = $('#alertTime').val();
      var PayerCode = $('#PayerCode').val();

      if (PayerCode != "" && PayerCode != undefined) {
        $('#rem9').hide();
        $('#PayerCode').css('border-color', '');
        $('#form8').addClass('form-group');
      }

      if (alertTime != "" && alertTime != undefined) {
        $('#rem11').hide();
        $('#alertTime').css('border-color', '');
        $('#form8').addClass('form-group');
      }

      if (LabSiteCode != "" && LabSiteCode != undefined) {
        $('#rem10').hide();
        $('#LabSiteCode').css('border-color', '');
        $('#form8').addClass('form-group');
      }

      if (sitepath != "" && sitepath != undefined) {
        $('#rem8').hide();
        $('#sitepath').css('border-color', '');
        $('#form8').addClass('form-group');
      }
      if (HptCode != "" && HptCode != undefined) {
        $('#rem1').hide();
        $('#HptCode').css('border-color', '');
        $('#form1').addClass('form-group');
      }
      if (HptName != "" && HptName != undefined) {
        $('#rem2').hide();
        $('#HptName').css('border-color', '');
        $('#form2').addClass('form-group');
      }
      if (ContractName != "" && ContractName != undefined) {
        $('#rem3').hide();
        $('#ContractName').css('border-color', '');
      }
      if (Position != "" && Position != undefined) {
        $('#rem4').hide();
        $('#Position').css('border-color', '');
      }
      if (phone != "" && phone != undefined) {
        $('#rem5').hide();
        $('#phone').css('border-color', '');
      }
      if (host != "" && host != undefined) {
        $('#rem6').hide();
        $('#host').css('border-color', '');
      }
      if (HptNameTH != "" && HptNameTH != undefined) {
        $('#rem7').hide();
        $('#HptNameTH').css('border-color', '');
        $('#form3').addClass('form-group');
      }

    }


    function Adduser() {
      var count = 0;
      var idcontract = $('#idcontract').val();
      var ContractName = $('#ContractName').val();
      var Position = $('#Position').val();
      var phone = $('#phone').val();
      var host = $('#host').val();
      var hosdetail = $('#hosdetail1').val();
      $(".checkblank3").each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          count++;
        }
      });

      if (count == 0) {
        swal({
          title: "<?php echo $array['addoredit'][$language]; ?>",
          text: "<?php echo $array['addoredit'][$language]; ?>",
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
              'STATUS': 'Adduser',
              'ContractName': ContractName,
              'Position': Position,
              'phone': phone,
              'idcontract': idcontract,
              'host': host,
              'hosdetail': hosdetail

            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
          } else if (result.dismiss === 'cancel') {
            swal.close();
          }

        })


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
        $('.checkblank3').each(function() {
          if ($(this).val() == "" || $(this).val() == undefined) {
            $(this).css('border-color', 'red');
            if (ContractName == "" || ContractName == undefined) {
              $('#rem3').show().css("color", "red");
            }
            if (Position == "" || Position == undefined) {
              $('#rem4').show().css("color", "red");
            }
            if (phone == "" || phone == undefined) {
              $('#rem5').show().css("color", "red");
            }
            if (host == "" || host == undefined) {
              $('#rem6').show().css("color", "red");
            }

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
        confirmButtonText: "<?php echo $array['yes'][$language]; ?>",
        cancelButtonText: "<?php echo $array['isno'][$language]; ?>",
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        closeOnConfirm: false,
        closeOnCancel: false,
        showCancelButton: true
      }).then(result => {
        if (result.value) {
          var idcontract = $('#idcontract').val();
          var HptCode = $('#HptCode').val();
          var data = {
            'STATUS': 'CancelItem',
            'HptCode': HptCode,
            'idcontract': idcontract
          }
          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
          // getHotpital();
          // ShowItem();
          // Blankinput();
        } else if (result.dismiss === 'cancel') {
          swal.close();
        }
      })
    }

    function Blankinput() {
      $('#clear').attr('disabled', false);
      $('#btn_clear').removeClass('opacity');
      $('#btn_clear').addClass('mhee');
      $('#profile-tab').attr('hidden', true);
      $('#home-tab').click();
      $('#rem1').hide();
      $('#rem2').hide();
      $('#rem3').hide();
      $('#rem4').hide();
      $('#rem5').hide();
      $('#rem6').hide();
      $('#rem7').hide();
      $('#rem8').hide();
      $('#rem9').hide();
      $('#rem10').hide();
      $('#rem11').hide();
      $('#form1').addClass('form-group');
      $('#form2').addClass('form-group');
      $('#form3').addClass('form-group');
      $('#money').prop('checked', false);
      $('#Signature').prop('checked', false);
      $('#par').prop('checked', false);
      $('#stock').prop('checked', false);
      $('#hostdetail').attr('hidden', true);
      $('#hostdetail55').attr('hidden', false);
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
      $('.checkblank3').each(function() {
        if ($(this).val() == "" || $(this).val() == undefined) {
          $(this).css('border-color', '');
        } else {
          $(this).css('border-color', '');
        }
      });
      $('#idcontract').val("");
      $('#PayerCode').val("");
      $('#ContractName').val("");
      $('#LabSiteCode').val("");
      $('#alertTime').val("");
      $('#Position').val("");
      $('#phone').val("");
      $('#host').val("");
      $('#HptCode').val("");
      $('#HptCode1').val("");
      ShowItem();
      $('#bCancel').attr('disabled', true);
      $('#delete_icon').addClass('opacity');
      $('#delete1').removeClass('mhee');
      $('#btn_clear').attr('hidden', true);
    }

    function getdetail(HptCode, row) {
      $('#profile-tab').attr('hidden', false);
      var id = $('#id_' + row).data('value');
      var previousValue = $('#checkitem_' + row).attr('previousValue');
      var name = $('#checkitem_' + row).attr('name');
      if (previousValue == 'checked') {
        $('#profile-tab').attr('hidden', true);
        $('#home-tab').click();
        $('#checkitem_' + row).removeAttr('checked');
        $('#checkitem_' + row).attr('previousValue', false);
        $('#checkitem_' + row).prop('checked', false);
        $('#clear').attr('disabled', false);
        $('#btn_clear').removeClass('opacity');
        $('#btn_clear').addClass('mhee');
        Blankinput();
      } else {
        $('#clear').attr('disabled', false);
        $('#btn_clear').removeClass('opacity');
        $('#btn_clear').addClass('mhee');
        $("input[name=" + name + "]:radio").attr('previousValue', false);
        $('#checkitem_' + row).attr('previousValue', 'checked');

        if (HptCode != "" && HptCode != undefined) {
          var data = {
            'STATUS': 'getdetail',
            'HptCode': HptCode,
            'id': id
          };

          console.log(JSON.stringify(data));
          senddata(JSON.stringify(data));
        }
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

    function menu_tapShow() {
      $('#chk_tap').val(1);
      $('#addhot').show();
      $('#adduser').hide();
      $('#btn_clear').attr('hidden', true);
    }

    function menu_tapHide() {
      $('#chk_tap').val(2);
      var chk = $('#chk_user').val();
      $('#addhot').hide();
      $('#adduser').show();
      if (chk == 1) {
        $('#btn_clear').attr('hidden', false);
      } else if (chk > 1) {
        $('#btn_clear').attr('hidden', true);
      }
    }

    function clearInput() {
      $('#ContractName').val("");
      $('#Position').val("");
      $('#phone').val("");
      $('#idcontract').val("");
      $('#clear').attr('disabled', true);
      $('#btn_clear').addClass('opacity');
      $('#btn_clear').removeClass('mhee');
    }

    function senddata(data) {
      var form_data = new FormData();
      form_data.append("DATA", data);
      var URL = '../process/side.php';
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
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var rowCount = $('#TableItem >tbody >tr').length;
                var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_" + i + "' value='" + temp[i]['HptCode'] + "' onclick='getdetail(\"" + temp[i]["HptCode"] + "\" , \"" + i + "\")'><span class='checkmark'></span></label>";
                // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                StrTR = "<tr id='tr" + temp[i]['HptCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                  "<td style='width: 5%;'>" + chkDoc + "</td>" +
                  "<td style='width: 10%;'>" + (i + 1) + "</td>" +
                  "<td nowrap style='overflow-x:auto; text-overflow: ellipsis;overflow: hidden;width: 15%;' title='" + temp[i]['HptCode'] + "'>" + temp[i]['HptCode'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden;width: 17%;' title='" + temp[i]['HptName'] + "'>" + temp[i]['HptName'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden;width: 16.5%;' title='" + temp[i]['HptNameTH'] + "'>" + temp[i]['HptNameTH'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden; width: 12.5%;' title='" + temp[i]['contractName'] + "'>" + temp[i]['contractName'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden; width: 10%;' title='" + temp[i]['permission'] + "'>" + temp[i]['permission'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden;width: 11%;' title='" + temp[i]['Number'] + "'>" + temp[i]['Number'] + "</td>" +
                  "<td nowrap style='overflow-x:auto;text-overflow: ellipsis;overflow: hidden;width: 13%;' hidden id='id_" + i + "' data-value='" + temp[i]['id'] + "'></td>" +
                  "</tr>";

                if (rowCount == 0) {
                  $("#TableItem tbody").append(StrTR);
                } else {
                  $('#TableItem tbody:last-child').append(StrTR);
                }
              }
            } else if ((temp["form"] == 'getHotpital')) {
              $("#host").empty();
              $("#hptsel").empty();
              var StrTr = "<option value='' selected>-</option>";
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                StrTr += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                $("#hptsel").append(StrTr);
              }
              $("#host").append(StrTr);

            } else if ((temp["form"] == 'getdetail')) {
              if ((Object.keys(temp).length - 2) > 0) {
                console.log(temp);
                $('#PayerCode').val(temp['PayerCode']);
                $('#sitepath').val(temp['Site_Path']);
                $('#LabSiteCode').val(temp['LabSiteCode']);
                $('#alertTime').val(temp['alertTime']);
                $('#HptCode1').val(temp['HptCode']);
                $('#HptCode').val(temp['HptCode']);
                $('#HptNameTH').val(temp['HptNameTH']);
                $('#HptName').val(temp['HptName']);
                $('#ContractName').val(temp['contractName']);
                $('#Position').val(temp['permission']);
                $('#phone').val(temp['Number']);
                $('#idcontract').val(temp['id']);
                $('#host').val(temp['HptCode']);
                $('#hosdetail1').val(temp['HptName']);
                $('#hostdetail').attr('hidden', false);
                $('#hostdetail55').attr('hidden', true);
                $('#host').removeClass('checkblank3');

                if (temp['Signature'] == 1) {
                  $('#Signature').prop('checked', true);
                } else {
                  $('#Signature').prop('checked', false);
                }
                if (temp['stock'] == 1) {
                  $('#stock').prop('checked', true);
                } else {
                  $('#stock').prop('checked', false);
                }
                if (temp['money'] == 1) {
                  $('#money').prop('checked', true);
                } else {
                  $('#money').prop('checked', false);
                }
                if (temp['par'] == 1) {
                  $('#par').prop('checked', true);
                } else {
                  $('#par').prop('checked', false);
                }


                $('#chk_user').val(temp['cnt']);

              }
              var chk = $('#chk_user').val();
              var chk_tab = $('#chk_tap').val();
              if (chk_tab == 2) {
                if (chk == 1) {
                  $('#btn_clear').attr('hidden', false);
                } else if (chk > 1) {
                  $('#btn_clear').attr('hidden', true);
                }
              }

              $('#bCancel').attr('disabled', false);
              $('#delete_icon').removeClass('opacity');
              $('#delete1').addClass('mhee');
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
                ShowItem();
                Blankinput();
                getHotpital();
                $('#profile-tab').attr('hidden', true);
                $('#home-tab').click();
              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).css('border-color', '');
                });

                $('#HptCode').val("");
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
                ShowItem();
                Blankinput();
              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#HptCode').val("");
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
                getHotpital();

              }, function(dismiss) {
                $('.checkblank').each(function() {
                  $(this).val("");
                });

                $('#HptCode').val("");
                //$('#Dept').val("1");
              })
            } else if ((temp["form"] == 'getSection')) {
              for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                var StrTr = "<option value = '" + temp[i]['DepCode'] + "'> " + temp[i]['DepName'] + " </option>";
                $("#Dept").append(StrTr);
                $("#Deptsel").append(StrTr);
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
              case "adduserfailed":
                temp['msg'] = "<?php echo $array['adduserfailed'][$language]; ?>";
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
      /* overflow :  hidden; */
      overflow-x: hidden;

    }

    .nfont {
      font-family: myFirstFont;
      font-size: 22px;
    }

    .table-scroll {
      overflow: auto;
      height: 355px;
      margin-top: 5px;
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

    .table th,
    .table td {
      border-top: none !important;
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
    <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][1][$language]; ?></li>
  </ol>
  <div id="wrapper"></div>
  <!-- content-wrapper -->
  <div id="content-wrapper">
    <div class="row">
      <div class="col-md-12">
        <!-- tag column 1 -->
        <div class="container-fluid">
          <div class="card-body" style="padding:0px; margin-top:-12px;">
            <div class="row">
              <div class="col-md-9 mt-3">
                <div class="row" style="margin-left:5px;">
                  <input type="text" autocomplete="off" class="form-control" style="width:35%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['SearchHospital'][$language]; ?>">
                  <div class="search_custom col-md-2">
                    <div class="search_1 d-flex justify-content-start">
                      <button class="btn" onclick="ShowItem()" id="bSavesave">
                        <i class="fas fa-search mr-2"></i>
                        <?php echo $array['search'][$language]; ?>
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="table-scroll" id="scroll555">
              <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" cellspacing="0" role="grid">
                <thead id="theadsum" style="font-size:11px;">
                  <tr role="row">
                    <th nowrap style='width: 5%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;'>&nbsp;</th>
                    <th nowrap style='width: 10%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['no'][$language]; ?>"><?php echo $array['no'][$language]; ?></th>
                    <th nowrap style='width: 15%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['hoscode'][$language]; ?>"><?php echo $array['hoscode'][$language]; ?></th>
                    <th nowrap style='width: 17%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['hosnameEN'][$language]; ?>"><?php echo $array['hosnameEN'][$language]; ?></th>
                    <th nowrap style='width: 16%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['hosnameTH'][$language]; ?>"><?php echo $array['hosnameTH'][$language]; ?></th>
                    <th nowrap style='width: 12.5%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['ContractName'][$language]; ?>"><?php echo $array['ContractName'][$language]; ?></th>
                    <th nowrap style='width: 10%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['Position'][$language]; ?>"><?php echo $array['Position'][$language]; ?></th>
                    <th nowrap style='width: 14%;text-overflow: ellipsis;overflow: hidden;overflow-x:auto;' title="<?php echo $array['phone'][$language]; ?>"><?php echo $array['phone'][$language]; ?></th>

                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:250px;">
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div> <!-- tag column 1 -->
    </div>
    <!-- =============================================================================================================================== -->
    <!-- /.content-wrapper -->
    <input type="hidden" id="chk_user">
    <input type="hidden" id="chk_tap">
    <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
      <div class="menu mhee" id="btn_clear" hidden>
        <div class="d-flex justify-content-center">
          <div class="circle12 d-flex justify-content-center">
            <button class="btn" onclick="clearInput()" id="clear">
              <i class="fas fa-user-plus"></i>
              <div>
                <?php echo $array['adduser'][$language]; ?>
              </div>
            </button>
          </div>
        </div>
      </div>
      <div class="menu mhee" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?> id="addhot">
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
      <div class="menu mhee" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?> id="adduser">
        <div class="d-flex justify-content-center">
          <div class="circle4 d-flex justify-content-center">
            <button class="btn" onclick="Adduser()" id="bSave">
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
      <div class="menu" id="delete1" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
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

    <ul class="nav nav-tabs" id="myTab" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" id="home-tab" onclick="menu_tapShow();" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['detail'][$language]; ?></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" id="profile-tab" hidden onclick="menu_tapHide();" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['adduser'][$language]; ?></a>
      </li>
    </ul>
    <!-- =============================================================================================================================== -->
    <div class="tab-content" id="myTabContent">
      <div class="tab-pane show active fade" id="home" role="tabpanel" aria-labelledby="home-tab">
        <!-- /.content-wrapper -->
        <div class="row  m-2">
          <div class="col-md-12">
            <!-- tag column 1 -->
            <div class="container-fluid">
              <div class="card-body" style="padding:0px;">

                <!-- =================================================================== -->
                <div class="row mt-4">
                  <div class="col-md-6">
                    <div class='form-group row' id="form1">
                      <label class="col-sm-4 col-form-label "><?php echo $array['hoscode'][$language]; ?></label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7 checkblank" id="HptCode" maxlength="3" placeholder="<?php echo $array['hoscode'][$language]; ?>">
                      <input type="text" autocomplete="off" class="form-control col-sm-7 " id="HptCode1" hidden placeholder="<?php echo $array['hoscode'][$language]; ?>">
                      <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class='form-group row' id="form1">
                      <label class="col-sm-4 col-form-label ">Sitepath</label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7 checkblank" id="sitepath" placeholder="Sitepath">
                      <label id="rem8" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                </div>
                <!-- =================================================================== -->
                <div class="row">
                  <div class="col-md-6">
                    <div class='form-group row' id="form2">
                      <label class="col-sm-4 col-form-label "><?php echo $array['hosnameEN'][$language]; ?></label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7 checkblank charonly" id="HptName" placeholder="<?php echo $array['hosnameEN'][$language]; ?>">
                      <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class='form-group row' id="form3">
                      <label class="col-sm-4 col-form-label"><?php echo $array['hosnameTH'][$language]; ?></label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7 checkblank charonlyTH" id="HptNameTH" placeholder="<?php echo $array['hosnameTH'][$language]; ?>">
                      <label id="rem7" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class='form-group row' id="form2">
                      <label class="col-sm-4 col-form-label ">PayerCode</label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7  charonly checkblank" id="PayerCode" placeholder="PayerCode">
                      <label id="rem9" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class='form-group row'>
                      <input type="checkbox" id="Signature" style="margin-top: 1.5%;">
                      <label class="col-sm-2 col-form-label "><?php echo $array['Signature'][$language]; ?></label>
                      <input type="checkbox" id="stock" style="margin-top: 1.5%;">
                      <label class="col-sm-2 col-form-label ">????????????????????????</label>
                      <input type="checkbox" id="money" style="margin-top: 1.5%;">
                      <label class="col-sm-2 col-form-label ">????????????????????????</label>
                      <input type="checkbox" id="par" style="margin-top: 1.5%;">
                      <label class="col-sm-2 col-form-label ">Par</label>
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6">
                    <div class='form-group row' id="form2">
                      <label class="col-sm-4 col-form-label ">LabSiteCode</label>
                      <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7  charonly checkblank" id="LabSiteCode" placeholder="LabSiteCode">
                      <label id="rem10" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class='form-group row' id="form2"></div>
                    <label class="col-sm-4 col-form-label ">????????????</label>
                    <input type="text" onkeyup="resetinput()" autocomplete="off" class="form-control col-sm-7 checkblank charonlyTH charonly" id="alertTime" placeholder="????????????">
                    <label id="rem11" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
              </div>
            </div>
            <!-- =============================================================================================== -->
          </div>
        </div>
      </div> <!-- tag column 2 -->
    </div>

    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
      <!-- /.content-wrapper -->
      <div class="row  m-2">
        <div class="col-md-12">
          <!-- tag column 1 -->
          <div class="container-fluid">
            <div class="card-body" style="padding:0px;">
              <div class="row mt-4">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['ContractName'][$language]; ?></label>
                    <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank3" id="ContractName" placeholder="<?php echo $array['ContractName'][$language]; ?>">
                    <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['Position'][$language]; ?></label>
                    <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank3" id="Position" placeholder="<?php echo $array['Position'][$language]; ?>">
                    <label id="rem4" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['phone'][$language]; ?></label>
                    <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 numonly checkblank3" maxlength="10" id="phone" placeholder="<?php echo $array['phone'][$language]; ?>">
                    <label id="rem5" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
                <div class="col-md-6" id="hostdetail55">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label>
                    <select onchange="resetinput()" class="form-control col-sm-7   checkblank3" id="host" onchange="removeClassBorder1();"></select>
                    <label id="rem6" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                  </div>
                </div>
                <div class="col-md-6" hidden id="hostdetail">
                  <div class='form-group row'>
                    <label class="col-sm-4 col-form-label text-right"><?php echo $array['hosname'][$language]; ?></label>
                    <input type="text" autocomplete="off" class="form-control col-sm-7 " disabled="true" id="hosdetail1" placeholder="<?php echo $array['hosname'][$language]; ?>">
                  </div>
                </div>

                <div class="col-md-6" hidden>
                  <div class='form-group row'>
                    <input type="text" class="form-control col-sm-7 " id="idcontract">
                  </div>
                </div>
              </div>
            </div>
          </div> <!-- tag column 2 -->
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

</body>

</html>