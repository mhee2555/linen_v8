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
        <?php echo $array['group'][$language]; ?>
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
    <script type="text/javascript">
        var summary = [];

        $(document).ready(function(e) {
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            getSection();
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
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
                this.value = this.value.replace(/[^a-zA-Z???-????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????????. ]/g, ''); //<-- replace all other than given set of values
            });

        }).click(function(e) {
            parent.afk();
        }).keyup(function(e) {
            parent.afk();
        });

        function getSection() {
            var lang = '<?php echo $language; ?>';
            var data2 = {
                'STATUS': 'getSection',
                'lang': lang
            };
            console.log(JSON.stringify(data2));
            senddata(JSON.stringify(data2));
        }



        function removeborder() {
            Blankinput();
            ShowItem();
            var hptsel = $('#hptsel').val();
            $('#hptsel').css('border-color', '');
            $('#hptsel2').val(hptsel);
        }

        function ShowItem() {
            $('.checkblank66').each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    $(this).css('border-color', 'red');
                } else {
                    $(this).css('border-color', '');
                }
            });
            var HptCode = $('#hptsel').val();
            var keyword = $('#searchitem').val();
            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': keyword
            };
            // Blankinput();
            console.log(JSON.stringify(data));
            senddata(JSON.stringify(data));
        }

        function resetinputuser() {
            var hptsel = $('#hptsel2').val();
            $('#hptsel').val(hptsel);
            ShowItem();
            $('#DepName').val('');
            $('#DepCode').val('');
            $('#rem1').hide();
            $("#xCenter").prop('checked', false);
            $('#hptsel2').css('border-color', '');
            $('#hptsel').css('border-color', '');
        }

        function resetinput(chk) {
            if (chk == 2) {
                var DepCode = $('#DepCode').val();
                if (DepCode != "" && DepCode != undefined) {
                    $('#rem3').hide();
                    $('#DepCode').css('border-color', '');
                }
            }
            var DepName = $('#DepName').val();
            if (DepName != "" && DepName != undefined) {
                $('#rem2').hide();
                $('#DepName').css('border-color', '');
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

            var DepCode1 = $('#DepCodeReal').val();
            var DepCode = $('#DepCode').val();
            var DepName = $('#DepName').val();
            var HptCode = $('#hptsel2').val();

            if ($('#xCenter').is(':checked')) xCenter = 1;

            if (count == 0) {
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    }
                });
                if (DepCode != "") {
                    swal({
                        title: "<?php echo $array['addoredit'][$language]; ?>",
                        // text: "<?php echo $array['adddata1'][$language]; ?>",
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
                                'DepName': DepName,
                                'DepCode': DepCode,
                                'DepCode1': DepCode1
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
                        }
                        if (DepName == "" || DepName == undefined) {
                            $('#rem2').show().css("color", "red");
                        }
                        if (DepCode == "" || DepCode == undefined) {
                            $('#rem3').show().css("color", "red");
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
                confirmButtonText: "<?php echo $array['confirm'][$language]; ?>",
                cancelButtonText: "<?php echo $array['cancel'][$language]; ?>",
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                closeOnConfirm: false,
                closeOnCancel: false,
                showCancelButton: true
            }).then(result => {
                if (result.value) {

                    var DepCode = $('#DepCode').val();
                    var data = {
                        'STATUS': 'CancelItem',
                        'DepCode': DepCode
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
            $('#rem3').hide();
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
            $('#DepCode').val("");
            $('#hptsel2').val("");
            $('#IsActive').val("1");
            $('#DepCodeReal').val("");
            $("#xCenter").prop('checked', false);
            ShowItem();
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
        }

        function getdetail(DepCode, row) {
            var HptCode = $('#hptsel').val();
            var number = parseInt(row) + 1;
            var previousValue = $('#checkitem_' + row).attr('previousValue');
            var name = $('#checkitem_' + row).attr('name');
            if (previousValue == 'checked') {
                $('#checkitem_' + row).removeAttr('checked');
                $('#checkitem_' + row).attr('previousValue', false);
                $('#checkitem_' + row).prop('checked', false);
                $("#xCenter").prop('checked', false);
                Blankinput();
            } else {
                $("input[name=" + name + "]:radio").attr('previousValue', false);
                $('#checkitem_' + row).attr('previousValue', 'checked');
                if (DepCode != "" && DepCode != undefined) {
                    var data = {
                        'STATUS': 'getdetail',
                        'DepCode': DepCode,
                        'number': number,
                        'HptCode': HptCode
                    };

                    console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
            }
        }


        function senddata(data) {
            var form_data = new FormData();
             form_data.append("DATA", data);
            var URL = '../process/group.php';
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
                    swal.close();
                    if (temp["status"] == 'success') {
                        if ((temp["form"] == 'ShowItem')) {
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            if (temp['Count'] > 0) {
                                var Active;
                                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                    if (temp[i]['IsActive'] == 1) {
                                        Active = 'active';
                                    } else {
                                        Active = 'inactive';
                                    }
                                    var rowCount = $('#TableItem >tbody >tr').length;
                                    var DefaultName = temp[i]['DefaultName'] == 1 ? '<i class="fas fa-check fa-sm"></i>' : '';
                                    var chkDoc = "<label class='radio'style='margin-top: 20%;'><input type='radio' name='checkitem' id='checkitem_" + i + "' value='" + temp[i]['DepCode'] + "' onclick='getdetail(\"" + temp[i]["DepCode"] + "\", \"" + i + "\")'><span class='checkmark'></span></label>";
                                    // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                    StrTR = "<tr id='tr" + temp[i]['DepCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                                        "<td style='width: 5%;'>" + chkDoc + "</td>" +
                                        "<td style='width: 20%;'>" + (i + 1) + "</td>" +
                                        "<td style='width: 75%;'>" + temp[i]['DepName'] + "</td>" +
                                        "</tr>";

                                    if (rowCount == 0) {
                                        // Blankinput();
                                        $("#TableItem tbody").append(StrTR);
                                    } else {
                                        // Blankinput();
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
                                $('#DepCodeReal').val(temp['DepCodeReal']);
                                $('#DepCode').val(temp['DepCodeReal']);
                                $('#DepName').val(temp['DepName']);
                                $('#hptsel2').val(temp['HptCode']);
                                $('#IsActive').val(temp['IsActive']);
                                if (temp['IsDefault'] == 1)
                                    $('#xCenter').prop("checked", true);
                                else
                                    $('#xCenter').prop("checked", false);
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
                                $('#DepCode').val("");
                                $('#DepName').val("");

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).css('border-color', '');
                                });
                                $('#DepCode').val("");

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

                                $('#DepCode').val("");
                                $('#hptsel2').val("BHQ");
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

                                $('#DepCode').val("");
                                $('#hptsel2').val("BHQ");
                                ShowItem();
                            })
                        } else if ((temp["form"] == 'getSection')) {
                            if (temp[0]['PmID'] != 5 && temp[0]['PmID'] != 7) {
                                var StrTr = "<option value=''><?php echo $array['selecthospital'][$language]; ?></option>";
                            } else {
                                var StrTr = "";
                                $('#hptsel').attr('disabled', true);
                                $('#hptsel').addClass('icon_select');
                                var Str = "";
                                $('#hptsel2').attr('disabled', true);
                                $('#hptsel2').addClass('icon_select');
                            }
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                                var Str = "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                            }

                            $("#hptsel").append(StrTr);
                            $("#hptsel2").append(StrTr);
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
                            case "editcenterfailedmsg":
                                temp['msg'] = "<?php echo $array['editcenterfailedmsg'][$language]; ?>";
                                break;
                            case "Repeatmsg":
                                temp['msg'] = "<?php echo $array['Repeatmsg'][$language]; ?>";
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

        .sidenav a {
            padding: 6px 8px 6px 16px;
            text-decoration: none;
            font-size: 25px;
            color: #818181;
            display: block;
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
        <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][18][$language]; ?></li>
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
                                <div class="col-md-4">
                                    <div class="row" style="margin-left:5px;">
                                        <!-- <label class="col-sm-4 col-form-label text-right"><?php echo $array['side'][$language]; ?></label> -->
                                        <select class="form-control col-md-8 " id="hptsel" onchange="removeborder();">
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="row" style="margin-left:15px;">
                                        <input type="text" autocomplete="off" class="form-control" style="width:35%;margin-left: -18%;" name="searchitem" id="searchitem" placeholder="<?php echo $array['SearchDepartment'][$language]; ?>">
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
                                <div class="col-md-2">

                                </div>
                            </div>
                            <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                                <thead id="theadsum" style="font-size:11px;">
                                    <tr role="row">
                                        <th style='width: 5%;'>&nbsp;</th>
                                        <th style='width: 20%;'>
                                            <?php echo $array['no'][$language]; ?>
                                        </th>
                                        <th style='width: 75%; '>
                                            <?php echo $array['group'][$language]; ?>
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
            <!-- /.content-wrapper -->
            <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
                <div class="menu mhee" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
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
                <div class="menu mhee" id="delete1" <?php if ($PmID == 3 || $PmID == 7 || $PmID == 5) echo 'hidden'; ?>>
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
                            <!-- =================================================================== -->
                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                        <label class="col-sm-3 col-form-label "><?php echo $array['side'][$language]; ?></label>
                                        <select onchange="resetinputuser()" class="form-control col-sm-7 checkblank" id="hptsel2">
                                        </select>
                                        <label id="rem1" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                        <label class="col-sm-3 col-form-label "><?php echo $array['Groupcode'][$language]; ?></label>
                                        <input type="text" autocomplete="off" onkeyup="resetinput(2)" maxlength="6" class="form-control col-sm-7 checkblank" id="DepCode" placeholder="<?php echo $array['codecode'][$language]; ?>">
                                        <input type="text" autocomplete="off" hidden class="form-control col-sm-7 " id="DepCodeReal" placeholder="<?php echo $array['Groupcode'][$language]; ?>" readonly>
                                        <label id="rem3" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                </div>
                            </div>
                            <!-- =================================================================== -->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class='form-group row'>
                                        <label class="col-sm-3 col-form-label "><?php echo $array['group'][$language]; ?></label>
                                        <input type="text" autocomplete="off" onkeyup="resetinput()" class="form-control col-sm-7 checkblank" id="DepName" placeholder="<?php echo $array['group'][$language]; ?>">
                                        <label id="rem2" class="col-sm-1 " style="font-size: 40%;margin-top: 1%;"> <i class="fas fa-asterisk"></i> </label>
                                    </div>
                                </div>
                            </div>
                            <!-- =================================================================== -->

                        </div>
                    </div>
                </div> <!-- tag column 2 -->
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