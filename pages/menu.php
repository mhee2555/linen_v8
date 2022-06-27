<?php
session_start();
date_default_timezone_set("Asia/Bangkok");
$Userid = $_SESSION['Userid'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
$TimeOut = $_SESSION['TimeOut'];
$menu = $_SESSION['menu'];

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
<!--  -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>Daily Request</title>
  <?php include_once('../assets/import/css.php'); ?>
</head>

<body id="page-top">
  <input type="hidden" id='countRow'>
  <div id="wrapper">
    <div id="content-wrapper">
      <div style="margin-top:5px;margin-left:15px;width:100%">
        <!-- start row tab -->
        <div class="row" <?php if ($PmID != 1 && $PmID != 2 && $PmID != 3 && $PmID != 6) echo 'hidden'; ?>>
          <div class="col-md-12">
            <div class="row" id="CardView"> </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div id="dd"> </div>
          </div>
        </div>



      </div> <!-- end row tab -->
      <div class="row" <?php if ($PmID != 1 && $PmID != 3 && $PmID != 6 || $menu != 1) echo 'hidden'; ?>>
        <div class="col-md-12 mb-3">
          <div class="row ml-1">
            <select autocomplete="off" style="font-size:22px;" class="form-control  col-sm-4" id="selectSite"></select>
            <div class="menuMini mhee1 ml-3">
              <div class="search_1 d-flex justify-content-start">
                <button class="btn" onclick="showDocAll();">
                  <i class="fas fa-search mr-2 pl-2"></i>
                  <?php echo $array['search'][$language]; ?>
                </button>
              </div>
            </div>
          </div>

        </div>
      </div>

      <ul class="nav nav-tabs" id="myTab" role="tablist" <?php if ($menu != 1) echo 'hidden'; ?>>
        <li class="nav-item">
          <a class="nav-link active" id="tab_head1" data-toggle="tab" href="#tab1" role="tab" aria-controls="tab_head1" aria-selected="true"><?php echo $array2['menu']['general']['sub'][19][$language]; ?> <span class="badge badge-danger badge-counter" id="i_RevealDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head3" data-toggle="tab" href="#tab3" role="tab" aria-controls="tab_head3" aria-selected="false">Par Department<span class="badge badge-danger badge-counter" id="i_RequestPar">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head2" data-toggle="tab" href="#tab2" role="tab" aria-controls="tab_head2" aria-selected="false"><?php echo $array2['menu']['general']['sub'][20][$language]; ?> <span class="badge badge-danger badge-counter" id="i_CallDirtyDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head4" data-toggle="tab" href="#tab4" role="tab" aria-controls="tab_head4" aria-selected="false"><?php echo $array2['menu']['general']['sub'][21][$language]; ?> <span class="badge badge-danger badge-counter" id="i_MoveDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head5" data-toggle="tab" href="#tab5" role="tab" aria-controls="tab_head5" aria-selected="false"><?php echo $array2['menu']['general']['sub'][22][$language]; ?> <span class="badge badge-danger badge-counter" id="i_OtherDep">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head6" data-toggle="tab" href="#tab6" role="tab" aria-controls="tab_head5" aria-selected="false">Chatroom <span class="badge badge-danger badge-counter" id="i_ChatRoom">0</span></a>
        </li>
        <li class="nav-item">
          <a class="nav-link" id="tab_head7" data-toggle="tab" href="#tab7" role="tab" aria-controls="tab_head5" aria-selected="false">แผนกเซ็นเอกสารไม่ครบ <span class="badge badge-danger badge-counter" id="i_Sig">0</span></a>
        </li>
      </ul>

      <div class="tab-content" id="myTabContent" <?php if ($menu != 1) echo 'hidden'; ?>>

        <div class="tab-pane show active fade" id="tab1" role="tabpanel" aria-labelledby="tab1">
          <div class="row" id="row_RevealDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
          <div class="row" id="row_RequestPar">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
          <div class="row" id="row_CallDirtyDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab4" role="tabpanel" aria-labelledby="tab4">
          <div class="row" id="row_MoveDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab5" role="tabpanel" aria-labelledby="tab5">
          <div class="row" id="row_OtherDep">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab6" role="tabpanel" aria-labelledby="tab6">
          <div class="row" id="row_ChatRoom">
          </div>
        </div>

        <div class="tab-pane show fade" id="tab7" role="tabpanel" aria-labelledby="tab7">
          <div class="row" id="row_Sig">
          </div>
        </div>


        <input type="text" id="DocNoHidden" hidden>


      </div>
    </div>





    <!-- Modal -->
    <div class="modal fade" id="alert" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
          <div class="modal-body">
            <div id='price'></div>
            <div id='confac'></div>
            <div id='conhos'></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>


    <div class="modal fade" id="modal_request" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableRequest" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap><?php echo $array['sn'][$language]; ?></th>
                      <th nowrap><?php echo $array['item'][$language]; ?></th>
                      <th nowrap>
                        <center><?php echo $array['parsc'][$language]; ?></center>
                      </th>
                      <th nowrap>
                        <center>ขอแก้ไขยอด Par</center>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:200px;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_reveal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">detailReveal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableReveal" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap><?php echo $array['sn'][$language]; ?></th>
                      <th nowrap><?php echo $array['item'][$language]; ?></th>
                      <th nowrap>
                        <center><?php echo $array['parsc'][$language]; ?></center>
                      </th>
                      <th nowrap>
                        <center>Shelfcount</center>
                      </th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:200px;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_Sig" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">detailsignature</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-fixed table-condensed table-striped mt-3" id="tableSig" width="100%" cellspacing="0" role="grid">
                  <thead id="theadsum" style="font-size:24px;">
                    <tr role="row" id='tr_1'>
                      <th nowrap style="width: 35%;"><?php echo $array['sn'][$language]; ?></th>
                      <th nowrap style="width: 40%;"><?php echo $array['item'][$language]; ?></th>
                      <th nowrap>จำนวน</th>
                    </tr>
                  </thead>
                  <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:200px;"></tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modal_message" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">message</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <input type="text" class="form-control" style="font-size:22px;" disabled id="txtComment">
              <input type="text" class="form-control" style="font-size:22px;" id="txtDocNoHidden" hidden>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="ModalSign" tabindex="-1" role="dialog" aria-hidden='false'>
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="background-color:#fff;">
          <div class="modal-body p-0">

            <div id="maxxx" onselectstart="return false">
              <div id="signature-pad" class="signature-pad">
                <div class="signature-pad--body">
                  <canvas></canvas>
                </div>
                <div class="signature-pad--footer">
                  <div class="signature-pad--actions">
                    <div>
                      <!-- <button type="button" class="button clear btn btn-secondary mr-2" data-action="clear" hidden>ล้าง</button> -->
                      <button type="button" class="button" data-action="change-color" hidden>Change color</button>
                      <button type="button" class="button btn btn-warning" data-action="undo" hidden>ย้อนกลับ</button>

                    </div>
                    <div>
                      <button type="button" class="button save" data-action="save-png" hidden>Save as PNG</button>
                      <button type="button" class="button save" data-action="save-jpg" hidden>Save as JPG</button>

                      <!-- <button type="button" class="button save btn btn-primary" data-action="save-svg">บันทึก</button> -->
                      <button type="button" style="width: 70px;" class="btn btn-danger" id="clear" data-action="clear"><?php echo $array['clear'][$language]; ?></button>
                      <button type="button" style="width: 70px;" class="btn btn-success" id="svg" data-action="save-svg"><?php echo $array['confirm'][$language]; ?></button>

                    </div>
                  </div>
                </div>
              </div>
            </div>

          </div>

        </div>
      </div>
    </div>


</body>


<?php include_once('../assets/import/js.php'); ?>
<script type="text/javascript">
  var summary = [];
  $(document).ready(function(e) {
    $('#ModalSign').on('shown.bs.modal', function() {
      resizeCanvas();
      signaturePad.clear();
    });
    var PmID = '<?php echo $PmID; ?>';
    if (PmID == 8) {
      $("#tab_head1").hide();
      $("#tab_head2").hide();
      $("#tab_head3").hide();
      $("#tab_head4").hide();
      $("#tab_head5").hide();
      $("#tab6").addClass("active");
      $("#tab_head6").addClass("active");
      alert_ChatRoom();
      alert_Sig();
    } else {
      alert_SetPrice();
      alert_RevealDep();
      alert_CallDirtyDep();
      alert_RequestPar();
      alert_MoveDep();
      alert_OtherDep();
      alert_ChatRoom();
      alert_Sig();
    }

    $("#tab_head7").click(function(e) {
      $("#tab6").removeClass("active");
      $("#tab_head6").removeClass("active");
    });

    GetSite();

  }).click(function(e) {
    parent.afk();
    parent.last_move = new Date();
  }).keyup(function(e) {
    parent.last_move = new Date();
  });

  $.ajaxSetup({
    cache: false
  });

  //======= On create =======
  function showDocAll() {
    alert_RevealDep();
    alert_CallDirtyDep();
    alert_RequestPar();
    alert_MoveDep();
    alert_OtherDep();
    alert_ChatRoom();
    alert_Sig();
  }

  function alert_SetPrice() {
    var PmID = '<?php echo $PmID; ?>';
    var Userid = '<?php echo $Userid; ?>';
    var HptCode = '<?php echo $HptCode; ?>';
    var data = {
      'STATUS': 'alert_SetPrice',
      'PmID': PmID,
      'HptCode': HptCode,
      'Userid': Userid
    };
    senddata(JSON.stringify(data));
  }

  function get_last_move() {
    last_move = new Date();
    return last_move;
  }

  function alert_RevealDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_RevealDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_RevealDep = 0;
        $('#row_RevealDep').empty();
        $('#i_RevealDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showDetailReveal(\"" + value.DocNo + "\")'><?php echo $array['detail'][$language]; ?></button><button class='btn btn-success btn-block w-50' onclick='blinkShelfcount(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_RevealDep++;
            $('#row_RevealDep').append(StrTR);
          });
        }
        if (i_RevealDep == 0) {
          $('#i_RevealDep').hide();
        }
        $('#i_RevealDep').text(i_RevealDep);
      }
    });
  }

  function alert_CallDirtyDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_CallDirtyDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        $('#row_CallDirtyDep').empty();
        var i_CallDirtyDep = 0;
        $('#i_CallDirtyDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-success btn-block w-50' onclick='blinkcallDirtyDep(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_CallDirtyDep++;
            $('#row_CallDirtyDep').append(StrTR);
          });
        }
        if (i_CallDirtyDep == 0) {
          $('#i_CallDirtyDep').hide();
        }
        $('#i_CallDirtyDep').text(i_CallDirtyDep);
      }
    });
  }


  function alert_RequestPar() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_RequestPar',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_RequestDep = 0;
        $('#i_RequestPar').show();
        $('#row_RequestPar').empty();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showDetailRequest(\"" + value.DocNo + "\")'><?php echo $array['detail'][$language]; ?></button> <button class='btn btn-success btn-block w-50' onclick='blinkparDep(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_RequestDep++;
            $('#row_RequestPar').append(StrTR);
          });
        }
        if (i_RequestDep == 0) {
          $('#i_RequestPar').hide();
        }
        $('#i_RequestPar').text(i_RequestDep);
      }
    });
  }

  function alert_MoveDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_MoveDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_MoveDep = 0;
        $('#row_MoveDep').empty();
        $('#i_MoveDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<p class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</p>" +
              "<p class='h4'><?php echo $array['movedep'][$language]; ?> : " + value.DepCodeTo + "</p>" +
              "<span class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</span>" +
              "<div class='text-black-50 d-flex justify-content-end'><button class='btn btn-success btn-block w-50' onclick='blinkmoveDepartment(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_MoveDep++;
            $('#row_MoveDep').append(StrTR);
          });
        }
        if (i_MoveDep == 0) {
          $('#i_MoveDep').hide();
        }
        $('#i_MoveDep').text(i_MoveDep);
      }
    });
  }

  function alert_OtherDep() {
    var site = $("#selectSite").val();
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_OtherDep',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        var i_OtherDep = 0;
        $('#row_OtherDep').empty();
        $('#i_OtherDep').show();
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='row px-3 d-flex justify-content-end'> <button class='btn btn-info mr-5' onclick='showMessage(\"" + value.Message + "\")'><?php echo $array['detail'][$language]; ?></button> <button class='btn btn-success btn-block w-50' onclick='blinkotherDepartment(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_OtherDep++;
            $('#row_OtherDep').append(StrTR);
          });
        }
        if (i_OtherDep == 0) {
          $('#i_OtherDep').hide();
        }
        $('#i_OtherDep').text(i_OtherDep);
      }
    });
  }

  function alert_ChatRoom() {
    var site = $("#selectSite").val();
    var PmID = '<?php echo $PmID; ?>';
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_ChatRoom',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        $('#row_ChatRoom').empty();
        $('#i_ChatRoom').show();
        var i_ChatRoom = 0;

        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            if (value.CheckPm != PmID) {
              StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
                "<div class='card bg-light text-black shadow'>" +
                "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
                "<div class='card-body'>" +
                "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
                "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
                "<div class='row px-3 d-flex justify-content-end'> <button class='btn btn-success btn-block w-50' onclick='blinkChatRoom(\"" + value.DocNo + "\")'>Accept</button></div>" +
                "</div>" +
                "</div>" +
                "<div>";

              i_ChatRoom++;

              $('#row_ChatRoom').append(StrTR);
            }


          });
        }

        if (i_ChatRoom == 0) {
          $('#i_ChatRoom').hide();
        }
        $('#i_ChatRoom').text(i_ChatRoom);
      }
    });
  }

  function alert_Sig() {
    var site = $("#selectSite").val();
    var PmID = '<?php echo $PmID; ?>';
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'alert_Sig',
        'site': site,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        $('#row_Sig').empty();
        $('#i_Sig').show();
        var i_Sig = 0;

        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            if (value.pm == '8') {
              var hid = '';
            } else {
              var hid = 'hidden';
            }

            StrTR = "<div class='col-lg-3 mb-4 mt-3'>" +
              "<div class='card bg-light text-black shadow'>" +
              "<div class='card-header font-weight-bold'>" + value.DepName + "</div>" +
              "<div class='card-body'>" +
              "<span class='h4'><?php echo $array['docno'][$language]; ?> : " + value.DocNo + "</span>" +
              "<p class='h4'><?php echo $array['selectdateref'][$language]; ?> : " + value.Modify_Date + "</p>" +
              "<div class='text-black-50 d-flex justify-content-start'> <button class='btn btn-info mr-5' onclick='showDetailSig(\"" + value.DocNo + "\")'><?php echo $array['detail'][$language]; ?></button> " +
              "<button  " + hid + "  class='btn btn-success btn-block w-50' onclick='blinkSig(\"" + value.DocNo + "\")'>Accept</button></div>" +
              "</div>" +
              "</div>" +
              "<div>";

            i_Sig++;

            $('#row_Sig').append(StrTR);


          });
        }

        if (i_Sig == 0) {
          $('#i_Sig').hide();
        }
        $('#i_Sig').text(i_Sig);
      }
    });
  }

  function showMessage(Message) {
    $('#txtComment').val(Message);
    $('#modal_message').modal('toggle');


  }

  function showDetailReveal(DocNo) {
    $('#modal_reveal').modal('toggle');
    $.ajax({
      url: "../process/revealDep.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'showDetailDocument',
        'DocNo': DocNo,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {
            if (value.TotalQty == "0.00") {
              value.TotalQty = "";
            }
            var inputPar = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.ParQty + "' disabled  class='form-control text-right w-50' id='txtSearch'>";
            var inputissu = "<input type='text' autocomplete='off' disabled style='font-size:22px;' placeholder='0' value='" + value.CcQty + "' class='numonly form-control text-right w-50'  id='TotalQty_" + key + "' >";
            var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
            StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
              "<td  >" + (key + 1) + "</td>" +
              "<td  >" + value.ItemName + "</td>" +
              "<td  hidden>" + inputitemCode + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputPar + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputissu + "</td>" +
              "</tr>";
          });
          $('#tableReveal tbody').html(StrTR);
        }

        $('#tableReveal tbody').html(StrTR);

        $('.numonly').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
        });
      }
    });
  }

  function showDetailRequest(DocNo) {
    $('#modal_request').modal('toggle');
    $.ajax({
      url: "../process/parDep.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'showDetailDocument',
        'DocNo': DocNo,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            var inputPar = "<input type='text' autocomplete='off' style='font-size:22px;' value='" + value.ParQty + "' disabled  class='form-control text-right w-50' id='txtSearch'>";
            var inputissu = "<input type='text' autocomplete='off' style='font-size:22px;' disabled value='" + value.Qty + "' class='numonly form-control text-right w-50'  id='TotalQty_" + key + "' >";
            var inputitemCode = "<input type='text' hidden autocomplete='off' style='font-size:22px;' value='" + value.ItemCode + "'  class='form-control text-right w-50 loopitemcode' id='ItemCode_" + key + "'>";
            StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
              "<td  >" + (key + 1) + "</td>" +
              "<td  >" + value.ItemName + "</td>" +
              "<td  hidden>" + inputitemCode + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputPar + "</td>" +
              "<td class='d-flex justify-content-center'>" + inputissu + "</td>" +
              "</tr>";
          });
          $('#tableRequest tbody').html(StrTR);
        }

        $('#tableRequest tbody').html(StrTR);

        $('.numonly').on('input', function() {
          this.value = this.value.replace(/[^0-9]/g, ''); //<-- replace all other than given set of values
        });
      }
    });

  }

  function showDetailSig(DocNo) {
    $('#modal_Sig').modal('toggle');
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'showDetailSig',
        'DocNo': DocNo,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);
        var StrTR = "";
        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(key, value) {

            StrTR += "<tr style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
              "<td  style='width: 40%;'>" + (key + 1) + "</td>" +
              "<td  style='width: 35%;'>" + value.ItemName + "</td>" +
              "<td  >" + value.pay + "</td>" +
              "</tr>";
          });
          $('#tableSig tbody').html(StrTR);
        }

        $('#tableSig tbody').html(StrTR);


      }
    });
  }

  function blinkSig(DocNo) {

    $("#DocNoHidden").val(DocNo);
    $('#ModalSign').modal("show");
    $('#ModalSign').modal({
      backdrop: 'static',
      keyboard: false
    })
  }

  function save_sign(dataURL) {
    var DocNo = $('#DocNoHidden').val();
    $('#chk_sign').val(0);

    $("#ModalSign").modal('hide');
    $.ajax({
      url: "../process/UpdateSign.php",
      method: "POST",
      data: {
        DocNo: DocNo,
        SignSVG: dataURL,
        Table: "shelfcount_menu",
        Column: "signature"
      },
      success: function(data) {
        swal({
          title: '',
          text: '<?php echo $array['savesuccess'][$language]; ?>',
          type: 'success',
          showCancelButton: false,
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => {
          alert_Sig();
        }, 300);
      }
    });
  }

  function blinkotherDepartment(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkotherDepartment',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "otherDep.php?DocNo=" + DocNo + "";
        // alert_OtherDep();
      }
    });
  }

  function blinkmoveDepartment(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkmoveDepartment',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "moveDep.php?DocNo=" + DocNo + "";
        // alert_MoveDep();
      }
    });
  }

  function blinkcallDirtyDep(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkcallDirtyDep',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "callDirtyDep.php?DocNo=" + DocNo + "";
        // alert_CallDirtyDep();
      }
    });
  }

  function blinkShelfcount(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkShelfcount',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "shelfcount.php?DocNo=" + DocNo + "";
      }
    });

  }

  function blinkparDep(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkparDep',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "parDep.php?DocNo=" + DocNo + "";
      }
    });
  }

  function blinkChatRoom(DocNo) {
    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'blinkChatRoom',
        'DocNo': DocNo,
      },
      success: function(result) {
        window.location.href = "chatRoom.php?DocNo=" + DocNo + "";
      }
    });
  }

  function GetSite() {

    var lang = '<?php echo $language; ?>';
    var PmID = '<?php echo $PmID; ?>';

    $.ajax({
      url: "../process/alert_menu.php",
      type: 'POST',
      data: {
        'FUNC_NAME': 'GetSite',
        'lang': lang,
      },
      success: function(result) {
        var ObjData = JSON.parse(result);

        var option = `<option value="0" selected><?php echo $array['selecthospital'][$language]; ?></option>`;

        if (!$.isEmptyObject(ObjData)) {
          $.each(ObjData, function(kay, value) {
            option += `<option value="${value.HptCode}">${value.HptName}</option>`;
          });
        } else {
          option = `<option value="0">Data not found</option>`;
        }

        $("#selectSite").html(option);
      }
    });
  }

  function senddata(data) {
    var form_data = new FormData();
    form_data.append("DATA", data);
    var URL = '../process/menu.php';
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
          if (temp["form"] == 'OnLoadPage') {} else if (temp["form"] == 'alert_SetPrice') {
            $('#countRow').val(temp['countSetprice']);
            var PmID = <?php echo $PmID; ?>;
            var result = '<h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["set"][$language]; ?></h1>';
            if (temp['countSetprice'] > 0) {
              for (var i = 0; i < temp['countSetprice']; i++) {
                result += '<table class="table table-fixed " cellspacing="0" role="grid">';
                result += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['side'][$language]; ?> ' + temp[i]['set_price']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['set_price']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['set_price']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['docno'][$language]; ?>: ' + temp[i]['set_price']['DocNo'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['changprice'][$language]; ?>: ' + temp[i]['set_price']['xDate'] + ' <?php echo $array['Timeleft'][$language]; ?>  ' + temp[i]['set_price']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';



                if (temp[i]['set_price']['cntAcive'] == 0) {
                  for (var m = 0; m < temp['countMail']; m++) {
                    var HptName = temp[i]['set_price']['HptName'];
                    var HptNameTH = temp[i]['set_price']['HptNameTH'];
                    var DocNo = temp[i]['set_price']['DocNo'];
                    var StartDate = temp[i]['set_price']['StartDate'];
                    var EndDate = temp[i]['set_price']['EndDate'];
                    var xDate = temp[i]['set_price']['xDate'];
                    var email = temp[m]['set_price']['email'];
                    var dateDiff = temp[i]['set_price']['dateDiff'];
                    var URL = '../process/sendMail_alertPrice.php';
                    $.ajax({
                      url: URL,
                      method: "POST",
                      data: {
                        HptName: HptName,
                        DocNo: DocNo,
                        StartDate: StartDate,
                        EndDate: EndDate,
                        xDate: xDate,
                        email: email,
                        dateDiff: dateDiff,
                        HptNameTH: HptNameTH
                      },
                      success: function(data) {
                        console.log['success'];
                      }
                    });
                  }

                }
              }
              $("#price").html(result);
              $("#alert").modal('show');
            }
            if (temp['countFac'] > 0) {
              var result2 = ' <h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["confac"][$language]; ?></h1>';
              for (var i = 0; i < temp['countFac']; i++) {
                result2 += '<table class="table table-fixed" cellspacing="0" role="grid">';
                result2 += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['factory'][$language]; ?> ' + temp[i]['contract_fac']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['contract_fac']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['contract_fac']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr >' +
                  '<td style="width:18%;border-top:none!important;"></td>' +
                  '<td nowrap style="width:40%;border-top:none!important;" class="text-left"><?php echo $array['Timeleft'][$language]; ?> ' + temp[i]['contract_fac']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';
                if (temp[i]['countMailFac'] > 0) {
                  for (var j = 0; j < temp[i]['countMailFac']; j++) {
                    var FacName = temp[i]['contract_fac']['FacName'];
                    var FacNameTH = temp[i]['contract_fac']['FacNameTH'];
                    var StartDate = temp[i]['contract_fac']['StartDate'];
                    var EndDate = temp[i]['contract_fac']['EndDate'];
                    var email = temp[j]['contract_fac']['email'];
                    var dateDiff = temp[i]['contract_fac']['dateDiff'];
                    var RowID = temp[i]['contract_fac']['RowID'];
                    if (temp[i]['contract_fac']['cntAcive'] == 0) {
                      var URL = '../process/sendMail_conFac.php';
                      $.ajax({
                        url: URL,
                        method: "POST",
                        data: {
                          FacName: FacName,
                          StartDate: StartDate,
                          EndDate: EndDate,
                          email: email,
                          dateDiff: dateDiff,
                          RowID: RowID,
                          FacNameTH: FacNameTH
                        },
                        success: function(data) {
                          console.log['success'];
                        }
                      });
                    }
                  }
                }
              }
              $("#confac").html(result2);
              $("#alert").modal('show');
            }
            if (temp['countHos'] > 0) {
              var result3 = ' <h1 class="modal-title" style="font-size:30px;color: rgb(0, 51, 141) "><?php echo $array["conhos"][$language]; ?></h1>';
              for (var i = 0; i < temp['countHos']; i++) {
                result3 += '<table class="table table-fixed" cellspacing="0" role="grid">';
                result3 += '<tr style="background-color:#2980b9;color:#ffffff">' +
                  '<td nowrap style="width: 30%;font-size:24px;font-weight:bold;padding-left:30px;"> <?php echo $array['side'][$language]; ?> ' + temp[i]['contract_hos']['hptlang'] + '</td>' +
                  '</tr>' +
                  '<tr>' +
                  '<td style="width:18%"></td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['datestartcontract'][$language]; ?>: ' + temp[i]['contract_hos']['StartDate'] + '</td>' +
                  '<td nowrap style="width:40%" class="text-left"><?php echo $array['dateendcontract'][$language]; ?>: ' + temp[i]['contract_hos']['EndDate'] + '</td>' +
                  '</tr>' +
                  '<tr >' +
                  '<td style="width:18%;border-top:none!important;"></td>' +
                  '<td nowrap style="width:40%;border-top:none!important;" class="text-left"><?php echo $array['Timeleft'][$language]; ?> ' + temp[i]['contract_hos']['dateDiff'] + ' <?php echo $array['day'][$language]; ?></td>' +
                  '</tr></table><hr>';

                if (temp[i]['countMailHos'] > 0) {
                  for (var j = 0; j < temp[i]['countMailHos']; j++) {
                    var HptName = temp[i]['contract_hos']['HptName'];
                    var HptNameTH = temp[i]['contract_hos']['HptNameTH'];
                    var StartDate = temp[i]['contract_hos']['StartDate'];
                    var EndDate = temp[i]['contract_hos']['EndDate'];
                    var email = temp[j]['contract_hos']['email'];
                    var dateDiff = temp[i]['contract_hos']['dateDiff'];
                    var RowID = temp[i]['contract_hos']['RowID'];
                    if (temp[i]['contract_hos']['cntAcive'] == 0) {
                      var URL = '../process/sendMail_conHos.php';
                      $.ajax({
                        url: URL,
                        method: "POST",
                        data: {
                          HptName: HptName,
                          StartDate: StartDate,
                          EndDate: EndDate,
                          email: email,
                          dateDiff: dateDiff,
                          RowID: RowID,
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
              $("#conhos").html(result3);
              $("#alert").modal('show');
            }
            if (temp['countpercent'] > 0) {
              for (var i = 0; i < temp['countpercent']; i++) {
                if (temp[i]['countMailpercent'] > 0) {
                  for (var j = 0; j < temp[i]['countMailpercent']; j++) {
                    var HptName = temp[0]['percent']['HptName'];
                    var HptNameTH = temp[0]['percent']['HptNameTH'];
                    var Total1 = temp[i]['percent']['Total1'];
                    var Total2 = temp[i]['percent']['Total2'];
                    var DocNoC = temp[i]['percent']['DocNoC'];
                    var DocNoD = temp[i]['percent']['DocNoD'];
                    var Precent = temp[i]['percent']['Precent'];
                    var email = temp[j]['percent']['email'];

                    var URL = '../process/sendMail_percent.php';
                    $.ajax({
                      url: URL,
                      method: "POST",
                      data: {
                        HptName: HptName,
                        Total1: Total1,
                        Total2: Total2,
                        Precent: Precent,
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
          }
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
</script>


<script>
  (function(global, factory) {
    typeof exports === 'object' && typeof module !== 'undefined' ? module.exports = factory() :
      typeof define === 'function' && define.amd ? define(factory) :
      (global.SignaturePad = factory());
  }(this, (function() {
    'use strict';

    var Point = (function() {
      function Point(x, y, time) {
        this.x = x;
        this.y = y;
        this.time = time || Date.now();
      }
      Point.prototype.distanceTo = function(start) {
        return Math.sqrt(Math.pow(this.x - start.x, 2) + Math.pow(this.y - start.y, 2));
      };
      Point.prototype.equals = function(other) {
        return this.x === other.x && this.y === other.y && this.time === other.time;
      };
      Point.prototype.velocityFrom = function(start) {
        return this.time !== start.time ?
          this.distanceTo(start) / (this.time - start.time) :
          0;
      };
      return Point;
    }());

    var Bezier = (function() {
      function Bezier(startPoint, control2, control1, endPoint, startWidth, endWidth) {
        this.startPoint = startPoint;
        this.control2 = control2;
        this.control1 = control1;
        this.endPoint = endPoint;
        this.startWidth = startWidth;
        this.endWidth = endWidth;
      }
      Bezier.fromPoints = function(points, widths) {
        var c2 = this.calculateControlPoints(points[0], points[1], points[2]).c2;
        var c3 = this.calculateControlPoints(points[1], points[2], points[3]).c1;
        return new Bezier(points[1], c2, c3, points[2], widths.start, widths.end);
      };
      Bezier.calculateControlPoints = function(s1, s2, s3) {
        var dx1 = s1.x - s2.x;
        var dy1 = s1.y - s2.y;
        var dx2 = s2.x - s3.x;
        var dy2 = s2.y - s3.y;
        var m1 = {
          x: (s1.x + s2.x) / 2.0,
          y: (s1.y + s2.y) / 2.0
        };
        var m2 = {
          x: (s2.x + s3.x) / 2.0,
          y: (s2.y + s3.y) / 2.0
        };
        var l1 = Math.sqrt(dx1 * dx1 + dy1 * dy1);
        var l2 = Math.sqrt(dx2 * dx2 + dy2 * dy2);
        var dxm = m1.x - m2.x;
        var dym = m1.y - m2.y;
        var k = l2 / (l1 + l2);
        var cm = {
          x: m2.x + dxm * k,
          y: m2.y + dym * k
        };
        var tx = s2.x - cm.x;
        var ty = s2.y - cm.y;
        return {
          c1: new Point(m1.x + tx, m1.y + ty),
          c2: new Point(m2.x + tx, m2.y + ty)
        };
      };
      Bezier.prototype.length = function() {
        var steps = 10;
        var length = 0;
        var px;
        var py;
        for (var i = 0; i <= steps; i += 1) {
          var t = i / steps;
          var cx = this.point(t, this.startPoint.x, this.control1.x, this.control2.x, this.endPoint.x);
          var cy = this.point(t, this.startPoint.y, this.control1.y, this.control2.y, this.endPoint.y);
          if (i > 0) {
            var xdiff = cx - px;
            var ydiff = cy - py;
            length += Math.sqrt(xdiff * xdiff + ydiff * ydiff);
          }
          px = cx;
          py = cy;
        }
        return length;
      };
      Bezier.prototype.point = function(t, start, c1, c2, end) {
        return (start * (1.0 - t) * (1.0 - t) * (1.0 - t)) +
          (3.0 * c1 * (1.0 - t) * (1.0 - t) * t) +
          (3.0 * c2 * (1.0 - t) * t * t) +
          (end * t * t * t);
      };
      return Bezier;
    }());

    function throttle(fn, wait) {
      if (wait === void 0) {
        wait = 250;
      }
      var previous = 0;
      var timeout = null;
      var result;
      var storedContext;
      var storedArgs;
      var later = function() {
        previous = Date.now();
        timeout = null;
        result = fn.apply(storedContext, storedArgs);
        if (!timeout) {
          storedContext = null;
          storedArgs = [];
        }
      };
      return function wrapper() {
        var args = [];
        for (var _i = 0; _i < arguments.length; _i++) {
          args[_i] = arguments[_i];
        }
        var now = Date.now();
        var remaining = wait - (now - previous);
        storedContext = this;
        storedArgs = args;
        if (remaining <= 0 || remaining > wait) {
          if (timeout) {
            clearTimeout(timeout);
            timeout = null;
          }
          previous = now;
          result = fn.apply(storedContext, storedArgs);
          if (!timeout) {
            storedContext = null;
            storedArgs = [];
          }
        } else if (!timeout) {
          timeout = window.setTimeout(later, remaining);
        }
        return result;
      };
    }

    var SignaturePad = (function() {
      function SignaturePad(canvas, options) {
        if (options === void 0) {
          options = {};
        }
        var _this = this;
        this.canvas = canvas;
        this.options = options;
        this._handleMouseDown = function(event) {
          if (event.which === 1) {
            _this._mouseButtonDown = true;
            _this._strokeBegin(event);
          }
        };
        this._handleMouseMove = function(event) {
          if (_this._mouseButtonDown) {
            _this._strokeMoveUpdate(event);
          }
        };
        this._handleMouseUp = function(event) {
          if (event.which === 1 && _this._mouseButtonDown) {
            _this._mouseButtonDown = false;
            _this._strokeEnd(event);
          }
        };
        this._handleTouchStart = function(event) {
          event.preventDefault();
          if (event.targetTouches.length === 1) {
            var touch = event.changedTouches[0];
            _this._strokeBegin(touch);
          }
        };
        this._handleTouchMove = function(event) {
          event.preventDefault();
          var touch = event.targetTouches[0];
          _this._strokeMoveUpdate(touch);
        };
        this._handleTouchEnd = function(event) {
          var wasCanvasTouched = event.target === _this.canvas;
          if (wasCanvasTouched) {
            event.preventDefault();
            var touch = event.changedTouches[0];
            _this._strokeEnd(touch);
          }
        };
        this.velocityFilterWeight = options.velocityFilterWeight || 0.7;
        this.minWidth = options.minWidth || 0.5;
        this.maxWidth = options.maxWidth || 2.5;
        this.throttle = ('throttle' in options ? options.throttle : 16);
        this.minDistance = ('minDistance' in options ?
          options.minDistance :
          5);
        if (this.throttle) {
          this._strokeMoveUpdate = throttle(SignaturePad.prototype._strokeUpdate, this.throttle);
        } else {
          this._strokeMoveUpdate = SignaturePad.prototype._strokeUpdate;
        }
        this.dotSize =
          options.dotSize ||
          function dotSize() {
            return (this.minWidth + this.maxWidth) / 2;
          };
        this.penColor = options.penColor || 'black';
        this.backgroundColor = options.backgroundColor || 'rgba(0,0,0,0)';
        this.onBegin = options.onBegin;
        this.onEnd = options.onEnd;
        this._ctx = canvas.getContext('2d');
        this.clear();
        this.on();
      }
      SignaturePad.prototype.clear = function() {
        var ctx = this._ctx;
        var canvas = this.canvas;
        ctx.fillStyle = this.backgroundColor;
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        this._data = [];
        this._reset();
        this._isEmpty = true;
      };
      SignaturePad.prototype.fromDataURL = function(dataUrl, options, callback) {
        var _this = this;
        if (options === void 0) {
          options = {};
        }
        var image = new Image();
        var ratio = options.ratio || window.devicePixelRatio || 1;
        var width = options.width || this.canvas.width / ratio;
        var height = options.height || this.canvas.height / ratio;
        this._reset();
        image.onload = function() {
          _this._ctx.drawImage(image, 0, 0, width, height);
          if (callback) {
            callback();
          }
        };
        image.onerror = function(error) {
          if (callback) {
            callback(error);
          }
        };
        image.src = dataUrl;
        this._isEmpty = false;
      };
      SignaturePad.prototype.toDataURL = function(type, encoderOptions) {
        if (type === void 0) {
          type = 'image/png';
        }
        switch (type) {
          case 'image/svg+xml':
            return this._toSVG();
          default:
            return this.canvas.toDataURL(type, encoderOptions);
        }
      };
      SignaturePad.prototype.on = function() {
        this.canvas.style.touchAction = 'none';
        this.canvas.style.msTouchAction = 'none';
        if (window.PointerEvent) {
          this._handlePointerEvents();
        } else {
          this._handleMouseEvents();
          if ('ontouchstart' in window) {
            this._handleTouchEvents();
          }
        }
      };
      SignaturePad.prototype.off = function() {
        this.canvas.style.touchAction = 'auto';
        this.canvas.style.msTouchAction = 'auto';
        this.canvas.removeEventListener('pointerdown', this._handleMouseDown);
        this.canvas.removeEventListener('pointermove', this._handleMouseMove);
        document.removeEventListener('pointerup', this._handleMouseUp);
        this.canvas.removeEventListener('mousedown', this._handleMouseDown);
        this.canvas.removeEventListener('mousemove', this._handleMouseMove);
        document.removeEventListener('mouseup', this._handleMouseUp);
        this.canvas.removeEventListener('touchstart', this._handleTouchStart);
        this.canvas.removeEventListener('touchmove', this._handleTouchMove);
        this.canvas.removeEventListener('touchend', this._handleTouchEnd);
      };
      SignaturePad.prototype.isEmpty = function() {
        return this._isEmpty;
      };
      SignaturePad.prototype.fromData = function(pointGroups) {
        var _this = this;
        this.clear();
        this._fromData(pointGroups, function(_a) {
          var color = _a.color,
            curve = _a.curve;
          return _this._drawCurve({
            color: color,
            curve: curve
          });
        }, function(_a) {
          var color = _a.color,
            point = _a.point;
          return _this._drawDot({
            color: color,
            point: point
          });
        });
        this._data = pointGroups;
      };
      SignaturePad.prototype.toData = function() {
        return this._data;
      };
      SignaturePad.prototype._strokeBegin = function(event) {
        var newPointGroup = {
          color: this.penColor,
          points: []
        };
        if (typeof this.onBegin === 'function') {
          this.onBegin(event);
        }
        this._data.push(newPointGroup);
        this._reset();
        this._strokeUpdate(event);
      };
      SignaturePad.prototype._strokeUpdate = function(event) {
        var x = event.clientX;
        var y = event.clientY;
        var point = this._createPoint(x, y);
        var lastPointGroup = this._data[this._data.length - 1];
        var lastPoints = lastPointGroup.points;
        var lastPoint = lastPoints.length > 0 && lastPoints[lastPoints.length - 1];
        var isLastPointTooClose = lastPoint ?
          point.distanceTo(lastPoint) <= this.minDistance :
          false;
        var color = lastPointGroup.color;
        if (!lastPoint || !(lastPoint && isLastPointTooClose)) {
          var curve = this._addPoint(point);
          if (!lastPoint) {
            this._drawDot({
              color: color,
              point: point
            });
          } else if (curve) {
            this._drawCurve({
              color: color,
              curve: curve
            });
          }
          lastPoints.push({
            time: point.time,
            x: point.x,
            y: point.y
          });
        }
      };
      SignaturePad.prototype._strokeEnd = function(event) {
        this._strokeUpdate(event);
        if (typeof this.onEnd === 'function') {
          this.onEnd(event);
        }
      };
      SignaturePad.prototype._handlePointerEvents = function() {
        this._mouseButtonDown = false;
        this.canvas.addEventListener('pointerdown', this._handleMouseDown);
        this.canvas.addEventListener('pointermove', this._handleMouseMove);
        document.addEventListener('pointerup', this._handleMouseUp);
      };
      SignaturePad.prototype._handleMouseEvents = function() {
        this._mouseButtonDown = false;
        this.canvas.addEventListener('mousedown', this._handleMouseDown);
        this.canvas.addEventListener('mousemove', this._handleMouseMove);
        document.addEventListener('mouseup', this._handleMouseUp);
      };
      SignaturePad.prototype._handleTouchEvents = function() {
        this.canvas.addEventListener('touchstart', this._handleTouchStart);
        this.canvas.addEventListener('touchmove', this._handleTouchMove);
        this.canvas.addEventListener('touchend', this._handleTouchEnd);
      };
      SignaturePad.prototype._reset = function() {
        this._lastPoints = [];
        this._lastVelocity = 0;
        this._lastWidth = (this.minWidth + this.maxWidth) / 2;
        this._ctx.fillStyle = this.penColor;
      };
      SignaturePad.prototype._createPoint = function(x, y) {
        var rect = this.canvas.getBoundingClientRect();
        return new Point(x - rect.left, y - rect.top, new Date().getTime());
      };
      SignaturePad.prototype._addPoint = function(point) {
        var _lastPoints = this._lastPoints;
        _lastPoints.push(point);
        if (_lastPoints.length > 2) {
          if (_lastPoints.length === 3) {
            _lastPoints.unshift(_lastPoints[0]);
          }
          var widths = this._calculateCurveWidths(_lastPoints[1], _lastPoints[2]);
          var curve = Bezier.fromPoints(_lastPoints, widths);
          _lastPoints.shift();
          return curve;
        }
        return null;
      };
      SignaturePad.prototype._calculateCurveWidths = function(startPoint, endPoint) {
        var velocity = this.velocityFilterWeight * endPoint.velocityFrom(startPoint) +
          (1 - this.velocityFilterWeight) * this._lastVelocity;
        var newWidth = this._strokeWidth(velocity);
        var widths = {
          end: newWidth,
          start: this._lastWidth
        };
        this._lastVelocity = velocity;
        this._lastWidth = newWidth;
        return widths;
      };
      SignaturePad.prototype._strokeWidth = function(velocity) {
        return Math.max(this.maxWidth / (velocity + 1), this.minWidth);
      };
      SignaturePad.prototype._drawCurveSegment = function(x, y, width) {
        var ctx = this._ctx;
        ctx.moveTo(x, y);
        ctx.arc(x, y, width, 0, 2 * Math.PI, false);
        this._isEmpty = false;
      };
      SignaturePad.prototype._drawCurve = function(_a) {
        var color = _a.color,
          curve = _a.curve;
        var ctx = this._ctx;
        var widthDelta = curve.endWidth - curve.startWidth;
        var drawSteps = Math.floor(curve.length()) * 2;
        ctx.beginPath();
        ctx.fillStyle = color;
        for (var i = 0; i < drawSteps; i += 1) {
          var t = i / drawSteps;
          var tt = t * t;
          var ttt = tt * t;
          var u = 1 - t;
          var uu = u * u;
          var uuu = uu * u;
          var x = uuu * curve.startPoint.x;
          x += 3 * uu * t * curve.control1.x;
          x += 3 * u * tt * curve.control2.x;
          x += ttt * curve.endPoint.x;
          var y = uuu * curve.startPoint.y;
          y += 3 * uu * t * curve.control1.y;
          y += 3 * u * tt * curve.control2.y;
          y += ttt * curve.endPoint.y;
          var width = curve.startWidth + ttt * widthDelta;
          this._drawCurveSegment(x, y, width);
        }
        ctx.closePath();
        ctx.fill();
      };
      SignaturePad.prototype._drawDot = function(_a) {
        var color = _a.color,
          point = _a.point;
        var ctx = this._ctx;
        var width = typeof this.dotSize === 'function' ? this.dotSize() : this.dotSize;
        ctx.beginPath();
        this._drawCurveSegment(point.x, point.y, width);
        ctx.closePath();
        ctx.fillStyle = color;
        ctx.fill();
      };
      SignaturePad.prototype._fromData = function(pointGroups, drawCurve, drawDot) {
        for (var _i = 0, pointGroups_1 = pointGroups; _i < pointGroups_1.length; _i++) {
          var group = pointGroups_1[_i];
          var color = group.color,
            points = group.points;
          if (points.length > 1) {
            for (var j = 0; j < points.length; j += 1) {
              var basicPoint = points[j];
              var point = new Point(basicPoint.x, basicPoint.y, basicPoint.time);
              this.penColor = color;
              if (j === 0) {
                this._reset();
              }
              var curve = this._addPoint(point);
              if (curve) {
                drawCurve({
                  color: color,
                  curve: curve
                });
              }
            }
          } else {
            this._reset();
            drawDot({
              color: color,
              point: points[0]
            });
          }
        }
      };
      SignaturePad.prototype._toSVG = function() {
        var _this = this;
        var pointGroups = this._data;
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        var minX = 0;
        var minY = 0;
        var maxX = this.canvas.width / ratio;
        var maxY = this.canvas.height / ratio;
        var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        svg.setAttribute('width', this.canvas.width.toString());
        svg.setAttribute('height', this.canvas.height.toString());
        this._fromData(pointGroups, function(_a) {
          var color = _a.color,
            curve = _a.curve;
          var path = document.createElement('path');
          if (!isNaN(curve.control1.x) &&
            !isNaN(curve.control1.y) &&
            !isNaN(curve.control2.x) &&
            !isNaN(curve.control2.y)) {
            var attr = "M " + curve.startPoint.x.toFixed(3) + "," + curve.startPoint.y.toFixed(3) + " " +
              ("C " + curve.control1.x.toFixed(3) + "," + curve.control1.y.toFixed(3) + " ") +
              (curve.control2.x.toFixed(3) + "," + curve.control2.y.toFixed(3) + " ") +
              (curve.endPoint.x.toFixed(3) + "," + curve.endPoint.y.toFixed(3));
            path.setAttribute('d', attr);
            path.setAttribute('stroke-width', (curve.endWidth * 2.25).toFixed(3));
            path.setAttribute('stroke', color);
            path.setAttribute('fill', 'none');
            path.setAttribute('stroke-linecap', 'round');
            svg.appendChild(path);
          }
        }, function(_a) {
          var color = _a.color,
            point = _a.point;
          var circle = document.createElement('circle');
          var dotSize = typeof _this.dotSize === 'function' ? _this.dotSize() : _this.dotSize;
          circle.setAttribute('r', dotSize.toString());
          circle.setAttribute('cx', point.x.toString());
          circle.setAttribute('cy', point.y.toString());
          circle.setAttribute('fill', color);
          svg.appendChild(circle);
        });
        var prefix = 'data:image/svg+xml;base64,';
        var header = '<svg' +
          ' xmlns="http://www.w3.org/2000/svg"' +
          ' xmlns:xlink="http://www.w3.org/1999/xlink"' +
          (" viewBox=\"" + minX + " " + minY + " " + maxX + " " + maxY + "\"") +
          (" width=\"" + maxX + "\"") +
          (" height=\"" + maxY + "\"") +
          '>';
        var body = svg.innerHTML;
        if (body === undefined) {
          var dummy = document.createElement('dummy');
          var nodes = svg.childNodes;
          dummy.innerHTML = '';
          for (var i = 0; i < nodes.length; i += 1) {
            dummy.appendChild(nodes[i].cloneNode(true));
          }
          body = dummy.innerHTML;
        }
        var footer = '</svg>';
        var data = header + body + footer;
        // alert("SVG : "+data);
        save_sign(data);
        return prefix + btoa(data);
      };
      return SignaturePad;
    }());

    return SignaturePad;

  })));
</script>

<script>
  var wrapper = document.getElementById("signature-pad");
  var clearButton = wrapper.querySelector("[data-action=clear]");
  var changeColorButton = wrapper.querySelector("[data-action=change-color]");
  var undoButton = wrapper.querySelector("[data-action=undo]");
  var savePNGButton = wrapper.querySelector("[data-action=save-png]");
  var saveJPGButton = wrapper.querySelector("[data-action=save-jpg]");
  var saveSVGButton = wrapper.querySelector("[data-action=save-svg]");
  var canvas = wrapper.querySelector("canvas");
  var signaturePad = new SignaturePad(canvas, {
    // It's Necessary to use an opaque color when saving image as JPEG;
    // this option can be omitted if only saving as PNG or SVG
    backgroundColor: 'rgb(255, 255, 255)'
  });

  // Adjust canvas coordinate space taking into account pixel ratio,
  // to make it look crisp on mobile devices.
  // This also causes canvas to be cleared.
  function resizeCanvas() {
    // When zoomed out to less than 100%, for some very strange reason,
    // some browsers report devicePixelRatio as less than 1
    // and only part of the canvas is cleared then.
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    // This part causes the canvas to be cleared
    canvas.width = canvas.offsetWidth * ratio;
    canvas.height = canvas.offsetHeight * ratio;
    canvas.getContext("2d").scale(ratio, ratio);

    // This library does not listen for canvas changes, so after the canvas is automatically
    // cleared by the browser, SignaturePad#isEmpty might still return false, even though the
    // canvas looks empty, because the internal data of this library wasn't cleared. To make sure
    // that the state of this library is consistent with visual state of the canvas, you
    // have to clear it manually.
    signaturePad.clear();
  }

  // On mobile devices it might make more sense to listen to orientation change,
  // rather than window resize events.
  window.onresize = resizeCanvas;
  resizeCanvas();

  function download(dataURL, filename) {
    if (navigator.userAgent.indexOf("Safari") > -1 && navigator.userAgent.indexOf("Chrome") === -1) {
      window.open(dataURL);
    } else {
      var blob = dataURLToBlob(dataURL);
      var url = window.URL.createObjectURL(blob);

      var a = document.createElement("a");
      a.style = "display: none";
      a.href = url;
      a.download = filename;
      // document.body.appendChild(a);
      // a.click();

      // window.URL.revokeObjectURL(url);
    }
  }

  // One could simply use Canvas#toBlob method instead, but it's just to show
  // that it can be done using result of SignaturePad#toDataURL.
  function dataURLToBlob(dataURL) {
    // Code taken from https://github.com/ebidel/filer.js
    var parts = dataURL.split(';base64,');
    var contentType = parts[0].split(":")[1];
    var raw = window.atob(parts[1]);
    var rawLength = raw.length;
    var uInt8Array = new Uint8Array(rawLength);
    var Str = "";
    for (var i = 0; i < rawLength; ++i) {
      uInt8Array[i] = raw.charCodeAt(i);
      Str += uInt8Array[i];
    }

    var bbb = new Blob([uInt8Array], {
      type: contentType
    });
    return new Blob([uInt8Array], {
      type: contentType
    });
  }

  clearButton.addEventListener("click", function(event) {
    signaturePad.clear();
  });

  undoButton.addEventListener("click", function(event) {
    var data = signaturePad.toData();

    if (data) {
      data.pop(); // remove the last dot or line
      signaturePad.fromData(data);
    }
  });

  changeColorButton.addEventListener("click", function(event) {
    var r = Math.round(Math.random() * 255);
    var g = Math.round(Math.random() * 255);
    var b = Math.round(Math.random() * 255);
    var color = "rgb(" + r + "," + g + "," + b + ")";

    signaturePad.penColor = color;
  });

  savePNGButton.addEventListener("click", function(event) {
    if (signaturePad.isEmpty()) {
      alert("Please provide a signature first.");
    } else {
      var dataURL = signaturePad.toDataURL();
      download(dataURL, "signature.png");
    }
  });

  saveJPGButton.addEventListener("click", function(event) {
    if (signaturePad.isEmpty()) {
      alert("Please provide a signature first.");
    } else {
      var dataURL = signaturePad.toDataURL("image/jpeg");
      download(dataURL, "signature.jpg");
    }
  });

  saveSVGButton.addEventListener("click", function(event) {
    if (signaturePad.isEmpty()) {
      alert("Please provide a signature first.");
    } else {
      var dataURL = signaturePad.toDataURL('image/svg+xml');
      download(dataURL, "signature.svg");
    }
  });
</script>




</html>