<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
if ($Userid == "") {
    header("location:../index.html");
}

$language = $_GET['lang'];
if ($language == "en") {
    $language = "en";
} else {
    $language = "th";
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
        <?php echo $array['setprice'][$language]; ?>
    </title>

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

    <!-- <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"> -->
    <script src="../jQuery-ui/jquery-1.12.4.js"></script>
    <script src="../jQuery-ui/jquery-ui.js"></script>
    <link href="../css/responsive.css" rel="stylesheet">

    <script type="text/javascript">
        jqui = jQuery.noConflict(true);
    </script>

    <link href="../dist/css/sweetalert2.css" rel="stylesheet">
    <script src="../dist/js/sweetalert2.min.js"></script>
    <script src="../dist/js/jquery-3.3.1.min.js"></script>
    <?php if ($language == 'th') { ?>
        <script src="../datepicker/dist/js/datepicker.js"></script>
    <?php } else if ($language == 'en') { ?>
        <script src="../datepicker/dist/js/datepicker-en.js"></script>
    <?php } ?>

    <link href="../datepicker/dist/css/datepicker.min.css" rel="stylesheet" type="text/css">
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>
    <script src="../datepicker/dist/js/i18n/datepicker.th.js"></script>

    <link href="../css/menu_custom.css" rel="stylesheet">

    <script type="text/javascript">
        var summary = [];

        $(function() {
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            $('#rem').hide();
            $('#rem1').hide();
            getHotpital();
            getCategoryMain();
            getDate_price();
            var HptCode = $('#hptsel').val();
            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode
            };

            $('#datepicker').val(twoDigit(d.getDate()) + "/" + (twoDigit(d.getMonth() + 1)) + "/" + d.getFullYear());

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));

            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem1();
                }
            });

            $('.editable').click(function() {
                alert('hi');
            });

            $('.numonly').on('input', function() {
                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
            });
            $('.charonly').on('input', function() {
                this.value = this.value.replace(/[^a-zA-Z???-????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????. ]/g, ''); //<-- replace all other than given set of values
            });
        }).click(function(e) {
            parent.afk();
        }).keyup(function(e) {
            parent.afk();
        });
        // ---------------------------------------------------
        (function($) {
            $(document).ready(function() {
                $("#datepicker").datepicker({
                    onSelect: function(date, el) {
                        resetinput();
                    }
                });
            });
        })(jQuery);
        // ---------------------------------------------------
        function getHotpital() {
            getCategorySub();
            var lang = '<?php echo $language; ?>';
            var data2 = {
                'STATUS': 'getHotpital',
                'lang': lang
            };
            // console.log(JSON.stringify(data2));
            senddata(JSON.stringify(data2));
        }

        function getItemPrice() {
            var data = {
                'STATUS': 'ShowItemPrice'
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }



        function shownow() {
            ShowItem1();
        }

        function getCheckAll(sel) {
            if (sel == 0) {
                isChecked1 = !isChecked1;
                // $( "div #aa" )
                //   .text( "For this isChecked " + isChecked1 + "." )
                //   .css( "color", "red" );

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

        function getCategorySub() {
            var data = {
                'STATUS': 'getCategorySub'
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));

        }



        function resetinput() {
            var xDate = $('#datepicker').val();
            var HptCode = $('#hptselModal').val();

            if (xDate != "" && xDate != undefined) {
                $('#rem').hide();
                $('#datepicker').removeClass('border-danger');
            }
            if (HptCode != "" && HptCode != undefined) {
                $('#rem1').hide();
                $('#hptselModal').removeClass('border-danger');
            }
        }

        function onCreate() {
            var xDate = $('#datepicker').val();
            var HptCode = $("#hptselModal").val();
            var lang = '<?php echo $language; ?>';
            $('.checkblank').each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    $(this).addClass('border-danger');
                } else {
                    $(this).removeClass('border-danger');
                }
            });
            if (xDate == "" || HptCode == "") {
                if (HptCode == "") {
                    $('#rem1').show(5).css("color", "red");
                } else {
                    $('#rem1').hide();
                }

                $('#rem').show(5).css("color", "red");
            } else {
                /* we join the array separated by the comma */
                if (lang == 'th') {
                    xDate = xDate.substr(6, 4) - 543 + "-" + xDate.substr(3, 2) + "-" + xDate.substr(0, 2);
                } else if (lang == 'en') {
                    xDate = xDate.substr(6, 4) + "-" + xDate.substr(3, 2) + "-" + xDate.substr(0, 2);
                }
                var fullDate = new Date()
                var day = fullDate.getDate();
                var month = fullDate.getMonth() < 9 ? '0' + (fullDate.getMonth() + 1) : (fullDate.getMonth() + 1);
                var year = fullDate.getFullYear();

                var current = year + '-' + month + '-' + day;

                var chk1 = Number(Date.parse(current));
                var chk2 = Number(Date.parse(xDate));
                if (chk1 > chk2) {
                    swal({
                        title: "",
                        text: "<?php echo $array['invalid'][$language]; ?>",
                        type: "warning",
                        showConfirmButton: false,
                        showCancelButton: false,
                        timer: 2000
                    });
                } else {
                    var data = {
                        'STATUS': 'CreateDoc',
                        'Price': Price,
                        'HptCode': HptCode,
                        'xDate': xDate
                    };
                    // console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
            }
        }

        function ShowDoc() {
            var HptCode = $('#hptsel2').val();
            var Keyword = $('#search2').val();

            var data = {
                'STATUS': 'ShowDoc',
                'HptCode': HptCode,
                'Keyword': Keyword
            };

            senddata(JSON.stringify(data));
        }

        function ShowItem1(Sel) {
            var HptCode = $('#hptsel').val();
            var CgSubID = $('#Category_Sub').val();
            $('#bSave').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');


            var data = {
                'STATUS': 'ShowItem1',
                'HptCode': HptCode,
                'CgSubID': CgSubID
            };

            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function ShowItem2() {
            var DocNo = $('#docno').val();
            var Keyword = $('#search1').val();

            var data = {
                'STATUS': 'ShowItem2',
                'DocNo': DocNo,
                'Keyword': Keyword
            };

            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function SavePrice(Sel) {
            var RowID = $('#RowID').val();
            var Price = $('#Price').val();
            swal({
                title: "<?php echo $array['editdata'][$language] ?>",
                text: "<?php echo $array['editdata1'][$language] ?>",
                type: "question",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: "<?php echo $array['yes'][$language] ?>",
                cancelButtonText: "<?php echo $array['isno'][$language] ?>",
                confirmButtonColor: '#6fc864',
                cancelButtonColor: '#3085d6',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {
                    var data = {
                        'STATUS': 'SavePrice',
                        'RowID': RowID,
                        'Price': Price
                    };
                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
            })

        }

        function SavePriceTime(Sel) {
            var RowID = $('#RowID_' + Sel).val();
            var Price = $('#price_' + Sel).val();
            var DocNo = $('#docno').val();

            var data = {
                'STATUS': 'SavePriceTime',
                'RowID': RowID,
                'Price': Price,
                'Sel': Sel,
                'DocNo': DocNo
            };
            // console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function saveDoc() {
            var DocNo = $('#docno').val();

            var chkArray = [];
            var chkPriceArray = [];

            $(".checkPrice").each(function() {
                chkArray.push($(this).val());
            });

            $(".price_array").each(function() {
                chkPriceArray.push($(this).val());

            });
            var RowId = chkArray.join(',');
            var Price = chkPriceArray.join(',');

            // alert(RowId);
            // alert(Price);
            swal({
                title: "<?php echo $array['confirmsave'][$language]; ?>",
                text: "<?php echo $array['docno'][$language]; ?>: " + DocNo + "",
                type: "warning",
                showCancelButton: true,
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {

                    swal({
                        title: "<?php echo $array['savedoc'][$language]; ?>",
                        text: DocNo + " <?php echo $array['success'][$language]; ?>",
                        type: "success",
                        showCancelButton: false,
                        timer: 1000,
                        confirmButtonText: 'Ok',
                        showConfirmButton: false
                    });
                    setTimeout(function() {
                        $('#dialog').modal('toggle');
                        var data = {
                            'STATUS': 'saveDoc',
                            'DocNo': DocNo,
                            'RowId': RowId,
                            'Price': Price
                        };
                        senddata(JSON.stringify(data));

                    }, 1000);
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
            });
        }

        function UpdatePrice() {
            var DocNo = $('#docno').val();
            var chkArray = [];
            var chkPriceArray = [];
            var chkCategoryCode = [];

            $(".checkPrice").each(function() {
                chkArray.push($(this).val());
            });

            $(".price_array").each(function() {

                if ($(this).val() == '') {
                    var pricezero = 0;
                } else {
                    var pricezero = $(this).val();
                }
                chkPriceArray.push(pricezero);

            });

            $(".chkCategoryCode").each(function() {
                chkCategoryCode.push($(this).val());
            });
            var RowId = chkArray.join(',');
            var Price = chkPriceArray.join(',');
            var CategoryCode = chkCategoryCode.join(',');
            swal({
                title: "<?php echo $array['save'][$language]; ?>",
                text: "<?php echo $array['updateprice'][$language]; ?>",
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#008000',
                cancelButtonColor: '#e60000',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {

                    var data = {
                        'STATUS': 'UpdatePrice',
                        'DocNo': DocNo,
                        'Price': Price,
                        'CategoryCode': CategoryCode,
                        'RowId': RowId
                    };
                    // console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
            })

        }

        function Blankinput() {

            $('.checkblank').each(function() {
                $(this).val("");
            });
            $('#DepCode').val("");
            $('#hptsel2').val("1");
            $('#Category_Sub2').val("");
            $('#Category_Main2').val("");
            $('#Price').val("");
            ShowItem();
        }

        function getdetail(RowID, row) {
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
                if (RowID != "" && RowID != undefined) {
                    var data = {
                        'STATUS': 'getdetail',
                        'RowID': RowID
                    };

                    // console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
            }
        }

        function cancelDoc(DocNo, row) {
            $('.btn_cancel').each(function() {
                $(".btn_cancel").attr("disabled", true);
            });
            var DocNo = DocNo;
            var row = row;
            $('#cancel').val(DocNo);
            $('#show_btn').attr('disabled', false);
            $('#cancel_btn' + row + '').attr('disabled', false);
        }


        function OpenDialog(Sel) {
            $("#datepicker").attr('disabled', false);
            $("#hptselModal").attr('disabled', false);
            var selectdocument = "";
            var lang = '<?php echo $language; ?>';

            if (Sel == 1) {
                $("#checkdocno:checked").each(function() {
                    selectdocument = $(this).val();
                });
            }

            $("#docno").val("");
            $("#datepicker").val("");

            if (selectdocument != "") {

                var aData = selectdocument.split(",");
                var newdate = aData[1].split("-");
                if (lang == 'en') {
                    var date = newdate[2] + "-" + newdate[1] + "-" + newdate[0];
                } else if (lang == 'th') {
                    var date = newdate[2] + "-" + newdate[1] + "-" + (Number(newdate[0]) + 543);

                }
                $("#docno").val(aData[0]);
                $("#datepicker").val(date);
                $("#create1").hide();

                $("#hptsel1").empty();
                var StrTr = "<option selected value = '" + aData[2] + "'> " + aData[3] + " </option>";
                $("#hptsel1").append(StrTr);
                ShowItem2();
            } else {
                getHotpital();
                $("#create1").show();
                $('#btn_save').attr('hidden', true);
                $('#btn_saveDoc').attr('hidden', true);
            }
            $("#search1").hide();
            $("#TableItemPrice tbody").empty();

            // dialog.dialog( "open" );
            $('#dialog').modal('show');
        }

        function shownow() {
            ShowItem1();
        }

        function CancelDocNo(docno) {
            swal({
                title: "<?php echo $array['cancel'][$language]; ?>",
                text: "<?php echo $array['canceldata4'][$language]; ?> " + docno,
                type: "info",
                showCancelButton: true,
                confirmButtonClass: "btn-primary",
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#008000',
                cancelButtonColor: '#e60000',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                var data = {
                    'STATUS': 'CancelDocNo',
                    'DocNo': docno
                };
                senddata(JSON.stringify(data));
            })
        }

        function getDate_price() {
            var HptCode = $('#hptsel1').val();
            var data = {
                'STATUS': 'getDate_price',
                'HptCode': HptCode
            }
            senddata(JSON.stringify(data));

        }

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/set_price.php';
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
                        title: 'pleasewait',
                        text: 'processing',
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

                        if ((temp["form"] == 'CreateDoc')) {
                            if (temp["checkdis"] == 1) {
                                $("#btn_saveDoc").attr('hidden', false);
                                $("#updateprice").attr('disabled', true);
                                $("#delete_icon2").addClass('opacity');
                            } else {
                                $("#btn_saveDoc").attr('hidden', true);
                                $("#updateprice").attr('disabled', false);
                                $("#delete_icon2").removeClass('opacity');
                            }
                            $("#datepicker").attr('disabled', true);
                            $("#hptselModal").attr('disabled', true);
                            $("#docno").val(temp["DocNo"]);
                            $("#create1").hide(300);
                            setTimeout(function() {
                                $("#btn_save").attr('hidden', false);
                                // $("#btn_saveDoc").attr('hidden', false);
                            }, 200);

                            ShowItem2();
                        } else if ((temp["form"] == 'UpdatePrice')) {
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                // confirmButtonText: 'Ok'
                            });
                            setTimeout(function() {
                                $('#dialog').modal('toggle');
                            }, 2000);

                        } else if ((temp["form"] == 'CancelDocNo')) {
                            ShowDoc();
                        } else if ((temp["form"] == 'ShowDoc')) {
                            $("#TableDoc tbody").empty();
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                var rowCount = $('#TableDoc >tbody >tr').length;
                                var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' class='checkblank' data-value='" + i + "' name='checkdocno' id='checkdocno' " + "value='" + temp[i]['DocNo'] + "," + temp[i]['xDate'] + "," + temp[i]['HptCode'] + "," + temp[i]['HptName'] + "' onclick='cancelDoc(\"" + temp[i]["DocNo"] + "\"," + i + ")'><span class='checkmark'></span></label>";
                                StrTR = "<tr id='tr" + temp[i]['DocNo'] + "'>" +
                                    "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['HptName'] + "</td>" +
                                    "<td style='width: 26%;'>" + temp[i]['DocNo'] + "</td>" +
                                    "<td style='width: 25%;'>" + temp[i]['xDate'] + "</td>" +
                                    "<td style='width: 19%;'><button class='btn btn_cancel' style='background: none;' onclick='CancelDocNo(\"" + temp[i]["DocNo"] + "\");' id='cancel_btn" + i + "' disabled='true'><i class='fas fa-trash'></i></button></td>" +
                                    "</tr>";
                                if (rowCount == 0) {
                                    $("#TableDoc tbody").append(StrTR);
                                } else {
                                    $('#TableDoc tbody:last-child').append(StrTR);
                                }
                            }

                        } else if ((temp["form"] == 'ShowItem1')) {
                            $("#TableItem tbody").empty();
                            // console.log(temp);
                            if (temp['Count'] > 0) {
                                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                    var rowCount = $('#TableItem >tbody >tr').length;
                                    var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem'  id='checkitem_" + i + "' style='margin-top: 24%;' value='" + temp[i]['RowID'] + "' onclick='getdetail(\"" + temp[i]["RowID"] + "\",\"" + i + "\")'><span class='checkmark'></span></label>";
                                    var Price = "<div class='row' style='margin-left:2px;'><input class='form-control numonly' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_" + i + "' value='" + temp[i]['Price'] + "' OnBlur='updateWeight(\"" + i + "\",\"" + temp[i]['RowID'] + "\")'></div>";

                                    StrTR = "<tr id='tr" + temp[i]['RowID'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                                        "<td style='width: 5%;' nowrap>" + chkDoc + "</td>" +
                                        "<td style='width: 35%;' nowrap>" + temp[i]['HptName'] + "</td>" +
                                        "<td style='width: 35%;' nowrap>" + temp[i]['CategoryName'] + "</td>" +
                                        "<td style='width: 19%;' nowrap>" + temp[i]['Price'] + " </td>" +
                                        "</tr>";

                                    if (rowCount == 0) {
                                        $("#TableItem tbody").append(StrTR);
                                    } else {
                                        $('#TableItem tbody:last-child').append(StrTR);
                                    }
                                }
                                $('.numonly').on('input', function() {
                                    this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                                });
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
                        } else if ((temp["form"] == 'ShowItem2')) {
                            $("#TableItemPrice tbody").empty();



                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                $("#datepicker").val(temp[i]['date']);
                                var rowCount = $('#TableItem >tbody >tr').length;
                                var RowID = "<input type='hidden' name='RowID_" + i + "' id='RowID_" + i + "' value='" + temp[i]['RowID'] + "'>";
                                var Price = "<div class='row' style='margin-left:2px;'><input class='form-control price_array numonly decimal ' style='height:40px;width:150px; margin-left:3px; margin-right:3px; text-align:center;' id='price_" + i + "' value='" + temp[i]['Price'] + "' placeholder='0' onKeyPress='if(event.keyCode==13){SavePriceTime(" + i + ")}'></div>";
                                var chkPrice = "<input type='radio' name='checkPrice' class='checkPrice' value='" + temp[i]['RowID'] + "'>";
                                var chkCategoryCode = "<input type='radio' name='chkCategoryCode' class='chkCategoryCode' value='" + temp[i]['CategoryCode'] + "'>";
                                StrTR = "<tr id='tr" + RowID + "'>" +
                                    "<td style='width: 5%;' nowrap>" + RowID + "</td>" +
                                    "<td hidden>" + chkPrice + "</td>" +
                                    "<td hidden>" + chkCategoryCode + "</td>" +
                                    "<td style='width: 35%;' nowrap>" + temp[i]['HptName'] + "</td>" +
                                    "<td style='width: 35%;' nowrap>" + temp[i]['CategoryName'] + "</td>" +
                                    "<td style='width: 19%;' nowrap>" + Price + " </td>" +
                                    "</tr>";

                                if (rowCount == 0) {
                                    $("#TableItemPrice tbody").append(StrTR);
                                } else {
                                    $('#TableItemPrice tbody:last-child').append(StrTR);
                                }
                                var rowCount = i;
                            }
                            $('.numonly').on('input', function() {
                                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                            });

                            $('.decimal').keypress(function(e) {
                                var character = String.fromCharCode(e.keyCode)
                                var newValue = this.value + character;
                                if (isNaN(newValue) || hasDecimalPlace(newValue, 3)) {
                                    e.preventDefault();
                                    return false;
                                }
                            });

                            function hasDecimalPlace(value, x) {
                                var pointIndex = value.indexOf('.');
                                return pointIndex >= 0 && pointIndex < value.length - x;
                            }
                            $('#rowCount').val(rowCount + 1);
                            $("#datepicker").val(date);
                            for (var i = 0; i < 1; i++) {
                                var StrTr = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                $("#hptselModal").append(StrTr);
                            }
                            $('.numonly').on('input', function() {
                                this.value = this.value.replace(/[^0-9.]/g, ''); //<-- replace all other than given set of values
                            });
                        } else if ((temp["form"] == 'getdetail')) {
                            if ((Object.keys(temp).length - 2) > 0) {
                                $('#RowID').val(temp['RowID']);
                                $('#HotName').val(temp['HptName']);
                                $('#Category_Sub2').val(temp['CategoryName']);
                                $('#Price').val(temp['Price']);
                                $('#delete_icon').removeClass('opacity');
                                $('#delete1').addClass('mhee');
                            }
                            $('#bSave').attr('disabled', false);
                        } else if ((temp["form"] == 'SavePrice')) {
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 2000,
                                confirmButtonText: 'Ok'
                            }).then(function() {
                                ShowItem1();
                            });

                            $('#RowID').val("");
                            $('#HotName').val("");
                            $('#CategoryMain').val("");
                            $('#CategorySub').val(temp['CategoryName']);
                            $('#Price').val(temp['Price']);
                            $('#Category_Sub2').val("");
                            $('#Category_Main2').val("");




                        } else if ((temp["form"] == 'SavePriceTime')) {
                            $('#RowID').val("");
                            $('#HotName').val("");
                            $('#CategoryMain').val("");
                            $('#CategorySub').val(temp['CategoryName']);
                            $('#Price').val(temp['Price']);
                            var rowCount = $('#TableDoc >tbody >tr').length;
                            var Sel = temp["Sel"];
                            var cn = temp["Cnt"];
                            var sv = "<?php echo $array['save'][$language]; ?>";
                            var svs = "<?php echo $array['savesuccess'][$language]; ?>";
                            var rowCount = $('#rowCount').val();
                            if ((Sel + 1) == rowCount)
                                $('#price_0').focus().select();
                            else
                                $('#price_' + (Sel + 1)).focus().select();

                            swal({
                                title: sv,
                                text: svs,
                                type: 'success',
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                showConfirmButton: false,
                                timer: 1000,
                                confirmButtonText: 'Ok'
                            })
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#hptsel").empty();
                            $("#hptsel1").empty();
                            $("#hptsel2").empty();
                            $("#hptselModal").empty();
                            if (temp[0]['PmID'] != 5 && temp[0]['PmID'] != 7) {

                                var hotValue1 = '<?php echo $array['selecthospital'][$language]; ?>';
                                var hotValue0 = '<?php echo $array['selecthospital'][$language]; ?>';
                                var StrTr = "<option value=''>" + hotValue0 + "</option>";
                                var StrTr2 = "<option value=''>" + hotValue1 + "</option>";
                            } else {
                                var StrTr = "";
                                $('#hptselModal').attr('disabled', true);
                                $('#hptselModal').addClass('icon_select');

                                $('#hptsel1').attr('disabled', true);
                                $('#hptsel1').addClass('icon_select');

                                var StrTr2 = "";

                                $('#hptsel2').attr('disabled', true);
                                $('#hptsel2').addClass('icon_select');

                                $('#hptsel').attr('disabled', true);
                                $('#hptsel').addClass('icon_select');

                            }
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value=" + temp[i]['HptCode'] + ">" + temp[i]['HptName'] + "</option>";
                                StrTr2 += "<option value=" + temp[i]['HptCode'] + ">" + temp[i]['HptName'] + "</option>";
                            }
                            $("#hptselModal").append(StrTr2);
                            $("#hptsel1").append(StrTr);
                            $("#hptsel2").append(StrTr);
                            $("#hptsel").append(StrTr);

                        } else if ((temp["form"] == 'getCategoryMain')) {
                            $("#Category_Main").empty();
                            $("#Category_Main1").empty();
                            var hotValue0 = '<?php echo $array['Pleasechoosemaincategory'][$language]; ?>';
                            var StrTr = "<option value=''>" + hotValue0 + "</option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value=" + temp[i]['MainCategoryCode'] + ">" + temp[i]['MainCategoryName'] + "</option>";
                            }
                            $("#Category_Main").append(StrTr);
                            $("#Category_Main1").append(StrTr);

                        } else if ((temp["form"] == 'getCategorySub')) {
                            $("#Category_Sub").empty();
                            $("#Category_Sub1").empty();
                            var hotValue0 = '<?php echo $array['Pleaseselectasubcategory'][$language]; ?>';
                            var StrTr = "<option value=''>" + hotValue0 + "</option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value=" + temp[i]['CategoryCode'] + ">" + temp[i]['CategoryName'] + "</option>";
                            }
                            $("#Category_Sub").append(StrTr);
                            $("#Category_Sub1").append(StrTr);
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
            font-family: myFirstFont !important;
            src: url("../fonts/DB Helvethaica X.ttf");
        }

        body {
            font-family: myFirstFont !important;
            font-size: 22px;
        }

        .nfont {
            font-family: myFirstFont !important;
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

        .mhee a {
            /* padding: 6px 8px 6px 16px; */
            text-decoration: none;
            font-size: 23px;
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
            color: #818181;
            background: none;
            box-shadow: none !important;
            display: block;
        }

        .mhee button:hover {
            color: #2c3e50;
            font-weight: bold;
            font-size: 26px;
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
        <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][11][$language]; ?></li>
    </ol>
    <div id="wrapper">
        <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
            <i class="fas fa-angle-down"></i>
        </a>
        <!-- content-wrapper -->
        <div id="content-wrapper">
            <div class="container-fluid">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true"><?php echo $array['setprice'][$language]; ?></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo $array['search'][$language]; ?></a>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                        <div class="row">
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:12px;margin-left: -2%;">
                                        <div class="row col-md-12">
                                            <div class="col-md-3">
                                                <div class="row" style="margin-left:5px;">
                                                    <!-- <label class="col-sm-7 col-form-label text-right"style="margin-left: -90px;"><?php echo $array['side'][$language]; ?></label> -->
                                                    <select class="form-control col-md-12" id="hptsel" onchange="shownow()">
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-3">
                                              <div class="row" style="margin-left:-6px;">
                                              <label class="col-sm-6 col-form-label text-right"style="margin-left: -111px;"><?php echo $array['categorymain'][$language]; ?></label>
                                                  <select class="form-control col-md-12" id="Category_Main" onchange="getCategorySub(1);"></select>
                                              </div>
                                          </div> -->
                                            <div class="col-md-3">
                                                <div class="row" style="margin-left: -6px;">
                                                    <!-- <label class="col-sm-3 col-form-label text-right"style="margin-left: -65px;"><?php echo $array['categorysub'][$language]; ?></label> -->
                                                    <select class="form-control col-md-12" id="Category_Sub" onchange="shownow();"></select>
                                                </div>
                                            </div>
                                            <div class="col-md-1 text-right">
                                                <div class="row" style="margin-left:-6px;">
                                                    <div class="search_custom col-md-2">
                                                        <div class="search_1 d-flex justify-content-start">
                                                            <button class="btn" onclick="ShowItem1()">
                                                                <i class="fas fa-search mr-2"></i>
                                                                <?php echo $array['search'][$language]; ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                            <thead id="theadsum" style="font-size:11px;">
                                                <tr role="row">
                                                    <th style='width: 5%;'>&nbsp;</th>
                                                    <th style='width: 35%;' nowrap>
                                                        <?php echo $array['side'][$language]; ?>
                                                    </th>
                                                    <th style='width: 34%;' nowrap>
                                                        <?php echo $array['categorysub'][$language]; ?>
                                                    </th>
                                                    <th style='width: 26%;' nowrap>
                                                        <?php echo $array['price'][$language]; ?>
                                                    </th>
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
                            <div class="menu" id="delete1" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
                                <div class="d-flex justify-content-center">
                                    <div class="circle4 d-flex justify-content-center" id="delete_icon">
                                        <button class="btn" onclick="SavePrice()" id="bSave" disabled="true">
                                            <i class="fas fa-save"></i>
                                            <div>
                                                <?php echo $array['save'][$language]; ?>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="menu mhee" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
                                <div class="d-flex justify-content-center">
                                    <div class="circle10 d-flex justify-content-center">
                                        <button class="btn" onclick="OpenDialog(2)" id="bDelete">
                                            <i class="fas fa-money-check"></i>
                                            <div>
                                                <?php echo $array['setprice'][$language]; ?>
                                            </div>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>


                        <!-- =============================================================================================================================== -->

                        <div class="row">
                            <div class="col-md-12">
                                <!-- tag column 1 -->
                                <div class="container-fluid">
                                    <div class="card-body" style="padding:0px; margin-top:10px;">
                                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                                            <li class="nav-item">
                                                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                                    <?php echo $array['detail'][$language]; ?></a>
                                            </li>
                                        </ul>

                                        <div class="row" style="margin-top:10px;">
                                            <div class="col-md-6" style="margin-left:15px;">
                                                <div class="row">
                                                    <input type="hidden" class="form-control" style="width:90%;" name="RowID" id="RowID" readonly>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- =================================================================== -->
                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                    <label class="col-sm-3 col-form-label "><?php echo $array['side'][$language]; ?></label>
                                                    <input type="text" autocomplete="off" class="form-control col-sm-7 " id="HotName" placeholder="<?php echo $array['side'][$language]; ?>">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                    <label class="col-sm-3 col-form-label "><?php echo $array['price'][$language]; ?></label>
                                                    <input type="text" autocomplete="off" class="form-control col-sm-7 numonly" id="Price" placeholder="<?php echo $array['price'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>

                                        <!-- =================================================================== -->
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class='form-group row'>
                                                    <label class="col-sm-3 col-form-label "><?php echo $array['categorysub'][$language]; ?></label>
                                                    <input type="text" autocomplete="off" class="form-control col-sm-7 " id="Category_Sub2" placeholder="<?php echo $array['categorysub'][$language]; ?>">
                                                </div>
                                            </div>
                                        </div>


                                        <!-- =================================================================== -->

                                    </div>
                                </div>
                            </div> <!-- tag column 2 -->

                            <!-- =============================================================================================== -->


                        </div>
                    </div>
                    <!-- search document -->
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="card-body" style="padding:0px; margin-top:12px;margin-left:2px;">
                            <div class="row">
                                <div class="col-md-11">
                                    <div class="row">
                                        <select class="form-control" style="margin-left:20px; font-size:22px;width:250px;" id="hptsel2"></select>
                                        <div class="search_custom col-md-2">
                                            <div class="search_1 d-flex justify-content-start">
                                                <button class="btn" onclick="ShowDoc()" id="bSavex">
                                                    <i class="fas fa-search mr-2"></i>
                                                    <?php echo $array['search'][$language]; ?>
                                                </button>
                                            </div>
                                        </div>

                                        <div class="search_custom col-md-2" style="margin-left:-8%;">
                                            <div class="circle6 d-flex justify-content-start">
                                                <button class="btn" onclick="OpenDialog(1)" id="show_btn" disabled='true'>
                                                    <i class="fas fa-paste mr-2 pt-1"></i>
                                                    <?php echo $array['show'][$language]; ?>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:20px; margin-bottom:20px;"></div>

                            <div class="row">
                                <div class="card-body" style="padding:0px;">
                                    <table class="table table-fixed table-condensed table-striped" id="TableDoc" cellspacing="0" role="grid" style="font-size:24px;width:98%;">
                                        <thead style="font-size:24px;">
                                            <tr role="row">
                                                <th style='width: 5%;' nowrap>&nbsp;</th>
                                                <th style='width: 25%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                                <th style='width: 25%;' nowrap><?php echo $array['docno'][$language]; ?></th>
                                                <th style='width: 45%;' nowrap><?php echo $array['dateP'][$language]; ?></th>
                                            </tr>
                                        </thead>
                                        <tbody id="tbody1_modal" class="nicescrolled" style="font-size:23px;height:450px;" />
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



            <!-- -----------------------------Custom1------------------------------------ -->
            <div class="modal" id="dialog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <input type="hidden" id="rowCount">
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
                                    <div class="col-md-12 mhee">
                                        <div class="row mb-3">
                                            <select class="form-control ml-2 checkblank" onchange="resetinput()" style=" font-size:22px;width:250px;" id="hptselModal" onchange="getDate_price();"></select>
                                            <label id="rem1" style="margin-left: 1%; font-size: 180%;margin-top: -0.5%;"> * </label>
                                            <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control datepicker-here numonly checkblank" style="margin-left:20px; font-size:22px;width:168px;" id="datepicker" data-language=<?php echo $language ?> data-date-format='dd/mm/yyyy' placeholder="<?php echo $array['datepicker'][$language]; ?>">
                                            <label id="rem" style=" margin-left: 1%; font-size: 180%;margin-top: -0.5%;"> * </label>
                                            <!-- <input type="text" class="form-control datepicker-here" style="margin-left:20px; font-size:22px;width:150px;" id="datepicker"> -->
                                            <input type="text" autocomplete="off" disabled="true" class="form-control " style="margin-left:20px; font-size:22px;width:200px;" name="docno" id="docno" placeholder="<?php echo $array['docno'][$language]; ?>">


                                            <div class="search_custom col-md-2" id="create1">
                                                <div class="circle1 d-flex justify-content-start">
                                                    <button class="btn" onclick="onCreate()">
                                                        <i class="fas fa-file-medical mr-2"></i>
                                                        <?php echo $array['createdocno'][$language]; ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <input type="text" class="form-control" style="margin-left:20px; font-size:22px;width:210px;" name="search1" id="search1" onKeyPress='if(event.keyCode==13){ShowItem2()}' placeholder="<?php echo $array['search'][$language]; ?>">
                                            <div class="search_custom col-md-2" id="btn_save" hidden="true">
                                                <div class="import_1 d-flex justify-content-start" id="delete_icon2">
                                                    <button class="btn" onclick="UpdatePrice()" id="updateprice">
                                                        <i class="fas fa-file-import mr-2 pt-1"></i>
                                                        <?php echo $array['updateprice'][$language]; ?>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="search_custom col-md-2" id="btn_saveDoc" hidden="true">
                                                <div class="circle4 d-flex justify-content-start">
                                                    <button class="btn" onclick="saveDoc()">
                                                        <i class="fas fa-save" style="padding-left: 16%;"></i>
                                                        <?php echo $array['savedoc'][$language]; ?>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <table class="table table-fixed table-condensed table-striped" id="TableItemPrice" width="100%" cellspacing="0" role="grid" style="font-size:24px;width:1100px;">
                                    <thead style="font-size:24px;">
                                        <tr role="row">
                                            <th style='width: 5%;'>&nbsp;</th>
                                            <th style='width: 35%;' nowrap><?php echo $array['side'][$language]; ?></th>
                                            <th style='width: 35%;' nowrap><?php echo $array['categorysub'][$language]; ?></th>
                                            <th style='width: 25%;' nowrap><?php echo $array['price'][$language]; ?></th>
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
            <div id="page-down" style="height:50px"></div>
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