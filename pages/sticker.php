<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
$HptCode = $_SESSION['HptCode'];
if($Userid==""){
  header("location:../index.html");
}

if(empty($_SESSION['lang'])){
  $language ='th';
}else{
  $language =$_SESSION['lang'];

}
require 'updatemouse.php';

header ('Content-type: text/html; charset=utf-8');
$xml = simplexml_load_file('../xml/general_lang.xml');
$xml2 = simplexml_load_file('../xml/main_lang.xml');
$json = json_encode($xml);
$array = json_decode($json,TRUE);
$json2 = json_encode($xml2);
$array2 = json_decode($json2,TRUE);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $array['Sticker'][$language]; ?></title>

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
  <link href="../css/menu_custom.css" rel="stylesheet">
  <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="../jQuery-ui/jquery-1.12.4.js"></script>
  <script src="../jQuery-ui/jquery-ui.js"></script>
  <script type="text/javascript">
  jqui = jQuery.noConflict(true);
  </script>

  <link href="../dist/css/sweetalert2.min.css" rel="stylesheet">
  <script src="../dist/js/sweetalert2.min.js"></script>
  <script src="../dist/js/jquery-3.3.1.min.js"></script>


  <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
  <script src="../datepicker/dist/js/datepicker.min.js"></script>
  <!-- Include English language -->
  <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

  <script type="text/javascript">
  var summary = [];

  $(document).ready(function(e){
    $(".select2").select2();
    var PmID = <?php echo $PmID;?>;
    if(PmID ==2 || PmID ==3 || PmID ==5 || PmID ==7){
    $('#hotpital').addClass('icon_select');
    $('#hotpital').attr('disabled' , true);
    }
    $('#searchtxt').keyup(function(e){

      if(e.keyCode == 13)
      {
          ShowDocument();
      }
      });
    OnLoadPage();
  }).click(function(e) { parent.afk();
        }).keyup(function(e) { parent.afk();
        });

  jqui(document).ready(function($){

    dialog = jqui( "#dialog" ).dialog({
      autoOpen: false,
      height: 650,
      width: 1200,
      modal: true,
      buttons: {
        "?????????": function() {
          dialog.dialog( "close" );
        }
      },
      close: function() {
        console.log("close");
      }
    });

    jqui( "#dialogItem" ).button().on( "click", function() {
      dialog.dialog( "open" );
    });

  });

  function OpenDialogItem(){
    var docno = $("#docno").val();
    if( docno != "" ) dialog.dialog( "open" );
  }

  //======= On create =======
  //console.log(JSON.stringify(data));
  function OnLoadPage(){
    var lang = '<?php echo $language; ?>';
    var data = {
      'STATUS'  : 'OnLoadPage',
      'lang'	: lang 
    };
    senddata(JSON.stringify(data));
    $('#isStatus').val(0)
  }



  function ShowDocument(selecta){
    var hos = $('#hotpital').val();
    var search = $('#searchtxt').val();
    var data = {
      'STATUS'  	: 'ShowDocument',
      'hos'	: hos,
      'selecta' : selecta,
      'search'	: search
    };
    console.log(JSON.stringify(data));
    senddata(JSON.stringify(data));
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
    }).then(function () {
      window.location.href="../logoff.php";
    }, function (dismiss) {
      window.location.href="../logoff.php";
      if (dismiss === 'cancel') {

      }
    })
  }
  function chkVakue(i){
    var Qty = Number($('#Qty_'+i).val())==null?0:Number($('#Qty_'+i).val());
    if(Qty<=0 || Qty == ""){
      $('#btnPrint_'+i).attr('disabled', true);
    }else if(Qty>0){
      $('#btnPrint_'+i).attr('disabled', false);
    }
  }
  function PrintSticker(ItemCode, ItemName, i){
    var Qty = Number($('#Qty_'+i).val())==null?0:Number($('#Qty_'+i).val());
    $('#sp_ItemCode').text(ItemCode);
    $('#ItemCode').val(ItemCode);
    $('#sp_ItemName').text(ItemName);
    $('#numberSticker').val(Qty);
    $('#maxNumSticker').val(Qty);
    $('#numberModal').modal('show');
  }
  function StickerPrint(){
    var lang      = '<?php echo $language; ?>';
    var ItemCode  = $('#ItemCode').val();
    var TotalQty  =  $('#maxNumSticker').val();
    var sendQty   = $('#numberSticker').val();
    var og        = $('#og').val();
    var of        = $('#of').val();

    if( $('#og').val().length > 10){
      og =  $('#og').val().substr(0,10)+'...';
    }
    
    if( $('#of').val().length > 10){
      of =  $('#of').val().substr(0,10)+'...';
    }
    var url  = "../report/Item_Sticker.php?ItemCode="+ItemCode+"&TotalQty="+TotalQty+"&sendQty="+sendQty+"&lang="+lang+"&og="+og+"&of="+of;
    window.open(url);
  }
  function senddata(data){
    var form_data = new FormData();
    form_data.append("DATA",data);
    var URL = '../process/sticker.php';
    $.ajax({
      url: URL,
      dataType: 'text',
      cache: false,
      contentType: false,
      processData: false,
      data: form_data,
      type: 'post',
      success: function (result) {
        try {
          var temp = $.parseJSON(result);
        } catch (e) {
          console.log('Error#542-decode error');
        }
        if(temp["status"]=='success'){
          if(temp["form"]=='OnLoadPage'){
            var PmID = <?php echo $PmID;?>;
            var HptCode = '<?php echo $HptCode;?>';
            for (var i = 0; i < (Object.keys(temp).length-2); i++) {
              if(PmID != 1 && HptCode == temp[i]['HptCode']){
                var Str = "<option value="+temp[i]['HptCode']+" selected>"+temp[i]['HptName']+"</option>";
              }else{
                var Str = "<option value="+temp[i]['HptCode']+">"+temp[i]['HptName']+"</option>";
              }
              $("#hotpital").append(Str);
            }

          }else if(temp["form"]=='ShowDocument'){
            $( "#TableDocument tbody" ).empty();
            for (var i = 0; i < temp["Row"]; i++) {
              var rowCount = $('#TableDocument >tbody >tr').length;

              var Qty = '<input type="text" class="form-control text-center" placeholder="0" style="font-size:24px;" id="Qty_'+i+'" onkeyup="chkVakue('+i+');">';

              StrTr="<tr id='tr"+temp[i]['DocNo']+"' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>"+
              "<td style='width: 5%;'nowrap>"+(i+1)+"</td>"+
              "<td style='width: 20%;'nowrap>"+temp[i]['ItemCode']+"</td>"+
              "<td style='width: 25%;'nowrap>"+temp[i]['ItemName']+"</td>"+
              "<td style='width: 20%;'nowrap>"+temp[i]['CategoryName']+"</td>"+
              "<td style='width: 15%;'nowrap>"+Qty+"</td>"+
              "<td style='width: 15%;'nowrap class='text-center'><button class='btn btn-info px-2 py-1' id='btnPrint_"+i+"' onclick='PrintSticker(\""+temp[i]['ItemCode']+"\",\""+temp[i]['ItemName']+"\", \""+i+"\");' disabled><i class='fas fa-print'></i></button></td>"+
              "</tr>";

              if(rowCount == 0){
                $("#TableDocument tbody").append( StrTr );
              }else{
                $('#TableDocument tbody:last-child').append(  StrTr );
              }
            }
          }

        }else if (temp['status']=="failed") {
            $( "#TableDocument tbody" ).empty();
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
            text: '<?php echo $array['notfoundmsg'][$language]; ?>',
            type: 'warning',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            showConfirmButton: false,
            timer: 2000,
            confirmButtonText: 'Ok'
          })

          $( "#docnofield" ).val( temp[0]['DocNo'] );
          $( "#TableDocumentSS tbody" ).empty();
          $( "#TableSendSterileDetail tbody" ).empty();

        }else{
          console.log(temp['msg']);
        }
      },
      failure: function (result) {
        alert(result);
      },
      error: function (xhr, status, p3, p4) {
        var err = "Error " + " " + status + " " + p3 + " " + p4;
        if (xhr.responseText && xhr.responseText[0] == "{")
        err = JSON.parse(xhr.responseText).Message;
        console.log(err);
        alert(err);
      }
    });
  }
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
        body{
          font-family: myFirstFont;
          font-size:22px;
        }

        .nfont{
          font-family: myFirstFont;
          font-size:22px;
        }

    button,input[id^='qty'] {
      font-size: 24px!important;
    }
    .table > thead > tr >th {
      background-color: #1659a2;
    }
    .table th, .table td {
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
    a.nav-link{
      width:auto!important;
    }
    .mhee a{
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #818181;
            display: block;
            }
            .mhee a:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
        }
        .mhee button{
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
            color: #818181;
            display: block;
            }
            .mhee button:hover {
            color: #2c3e50;
            font-weight:bold;
            font-size:26px;
        }
    .datepicker{z-index:9999 !important}
    .hidden{visibility: hidden;}
  </style>
</head>

<body id="page-top">
<ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="javascript:void(0)"><?php echo $array2['menu']['general']['title'][$language]; ?></a></li>
      <li class="breadcrumb-item active"><?php echo $array2['menu']['general']['sub'][16][$language]; ?></li>
    </ol>
  <input class='form-control' type="hidden" style="margin-left:-48px;margin-top:10px;font-size:16px;width:100px;height:30px;text-align:right;padding-top: 15px;" id='IsStatus'>

  <div id="wrapper">
    <!-- content-wrapper -->
    <div id="content-wrapper">
      <div class="row" style="margin-top:-15px;"> <!-- start row tab -->
        <div class="col-md-12"> <!-- tag column 1 -->
          <!-- /.content-wrapper -->
                <div class="row">
                  <div class="col-md-12"> <!-- tag column 1 -->
                    <div class="container-fluid">
                      <div class="card-body" >
                        <div class="row col-12">
                          <div class="col-md-4">
                            <div class='form-group row'>
                              <!-- <label class="col-sm-5 col-form-label text-right" style="margin-left: -22%;font-size:24px;"><?php echo $array['side'][$language]; ?></label> -->
                              <select class="form-control col-sm-11 " style="font-size:22px;"  id="hotpital" ></select>
                            </div>
                          </div>
                          <div class="col-md-5">
                            <div class='form-group row'>
                              <input  type="text" class="form-control col-sm-8" style="margin-left: -4%;font-size:22px;" id="searchtxt" name="searchtxt" value="" placeholder="<?php echo $array['Searchitem2'][$language]; ?>" >
                              <div class="search_custom col-md-3">
                              <div class="search_1 d-flex justify-content-start">
                                <button class="btn"  onclick="ShowDocument(0)" >
                                  <i class="fas fa-search mr-2"></i> <?php echo $array['search'][$language]; ?>
                                </button>
                              </div>
                            </div>      
                          </div>
                        </div>
                      </div>                 
                    </div>
                  </div>
                </div>
              </div> <!-- tag column 1 -->
            </div>
          </div>
          <div class="row">
            <div style='width: 98%;margin-top: -25px;'> <!-- tag column 1 -->
              <table style="margin-top:10px;margin-left:15px;" class="table table-fixed table-condensed table-striped" id="TableDocument" width="100%" cellspacing="0" role="grid" style="">
                <thead id="theadsum" style="font-size:24px;">
                  <tr role="row">
                    <th style='width: 5%;'nowrap><?php echo $array['sn'][$language]; ?></th>
                    <th style='width: 20%;'nowrap><?php echo $array['code'][$language]; ?></th>
                    <th style='width: 25%;'nowrap><?php echo $array['item'][$language]; ?></th>
                    <th style='width: 20%;'nowrap><?php echo $array['category'][$language]; ?></th>
                    <th style='width: 15%;'nowrap><center><?php echo $array['totalnum'][$language]; ?></center></th>
                    <th style='width: 15%;'nowrap><center><?php echo $array['Sticker'][$language]; ?></center></th>
                  </tr>
                </thead>
                <tbody id="tbody" class="nicescrolled" style="font-size:23px;height:600px;">
                </tbody>
              </table>
            </div> <!-- tag column 1 -->
          </div>
        </div>
      </div> <!-- end row tab -->
<div class="modal fade" id="numberModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: rgba(64, 64, 64, 0.75)!important;">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content" style="margin-top: 50px;background-color:#fff;">
      <div class="modal-header">
        <h2 class="modal-title"><?php echo $array['pleaseenter'][$language]; ?></h2>
      </div>
      <div class="modal-body">
        <div class="card-body" style="padding:0px;">
          <div class="row">
            <div class="col-3">
              <?php echo $array['code'][$language]; ?>: 
            </div>
            <div class="col-9">
              <span id="sp_ItemCode"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-3">
              <?php echo $array['item'][$language]; ?>: 
            </div>
            <div class="col-9">
              <span id="sp_ItemName"></span>
            </div>
          </div>
          <div class="row">
            <div class="col-3">
              <?php echo $array['qty'][$language]; ?>: 
            </div>
            <div class="col-9">
              <input type="hidden" id="maxNumSticker">
              <input type="hidden" id="ItemCode">
              <input type="text" class="form-control numonly_dot" id="numberSticker" autocomplete="off" onkeyup="chk_numbrtSticker(this.value);" style="font-size: 22px;">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <?php echo $array['Organizer'][$language]; ?>: 
            </div>
            <div class="col-9">
              <input type="text" class="form-control " id="og" autocomplete="off" style="font-size: 22px;">
            </div>
          </div>
          <div class="row mt-2">
            <div class="col-3">
              <?php echo $array['officer'][$language]; ?>: 
            </div>
            <div class="col-9">
              <input type="text" class="form-control " id="of" autocomplete="off"  style="font-size: 22px;">
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" style="width:10%;" onclick="StickerPrint();" class="btn btn-success"><?php echo $array['btnPrint'][$language]; ?></button>
        <button type="button" style="width:10%;" class="btn btn-danger" data-dismiss="modal"><?php echo $array['close'][$language]; ?></button>
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
    <script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <!-- Custom scripts for all pages-->
    <script src="../template/js/sb-admin.min.js"></script>

    <!-- Demo scripts for this page-->
    <script src="../template/js/demo/datatables-demo.js"></script>

  </body>

  </html>
