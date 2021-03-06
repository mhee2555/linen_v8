<?php
session_start();
$Userid = $_SESSION['Userid'];
$TimeOut = $_SESSION['TimeOut'];
$PmID = $_SESSION['PmID'];
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

    <title>
        <?php echo $array['username'][$language]; ?>
    </title>

    <link rel="icon" type="image/png" href="../img/pose_favicon.png">
    <!-- Bootstrap core CSS-->
    <link href="../template/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="../bootstrap/css/tbody.css" rel="stylesheet">
    <link href="../bootstrap/css/myinput.css" rel="stylesheet">
    <link rel="stylesheet" href="../dropify/dist/css/dropify.min.css">
    <!-- Custom fonts for this template-->
    <link href="../template/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">

    <!-- Page level plugin CSS-->
    <link href="../template/vendor/datatables/dataTables.bootstrap4.css" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../template/css/sb-admin.css" rel="stylesheet">
    <link href="../css/xfont.css" rel="stylesheet">
    <link href="../css/menu_custom.css" rel="stylesheet">
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
    <link href="../select2/dist/css/select2.min.css" rel="stylesheet" type="text/css" />
    <!-- Include English language -->
    <script src="../datepicker/dist/js/i18n/datepicker.en.js"></script>

    <script type="text/javascript">
        var summary = [];

        $(document).ready(function(e) {
            $("#searchitem").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#TableItem tbody tr").filter(function() {
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                });
            });
            // 
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            $('#rem5').hide();
            $('#rem6').hide();
            $('#rem7').hide();
            $('#rem8').hide();
            // getDepartment();
            resetinput();
            //On create
            $('.TagImage').bind('click', {
                imgId: $(this).attr('id')
            }, function(evt) {
                alert(evt.imgId);
            });
            getHotpital();
            getPermission();
            setTimeout(() => {
                getFactory();
                getDep();
                getDepartment2();
            }, 300);
            $('#searchitem').keyup(function(e) {
                if (e.keyCode == 13) {
                    ShowItem();
                }
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

            $('.dropify').dropify();

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
                "<?php echo $array['close'][$language]; ?>": function() {
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
            var lang = '<?php echo $language; ?>';
            var data2 = {
                'STATUS': 'getHotpital',
                'lang': lang
            };
            senddata(JSON.stringify(data2));
        }

        function getDep() {
            var Permission = $('#Permission').val();
            var Hotp = $('#hptsel').val();
            var data = {
                'STATUS': 'getDep',
                'Hotp': Hotp,
                'Permission': Permission
            };
            senddata(JSON.stringify(data));
        }

        function getDepartment2(chk) {
            var Permission = $('#Permission').val();
            if (chk == 1) {
                var Hotp = $('#hptsel').val();
                $('#host').val(Hotp);
                ShowItem(1);
                getFactory(1);
                getDep();
            } else if (chk == 2) {
                var Hotp = $('#host').val();
                $('#hptsel').val(Hotp);
                ShowItem(2);
                getFactory(1);
                getDep();
            }else{
                var Hotp = '';
            }
            // $('#rem1').hide();
            // $('#hptsel').css('border-color', '');
            var data = {
                'STATUS': 'getDepartment2',
                'Hotp': Hotp,
                'Permission': Permission
            };
            senddata(JSON.stringify(data));
        }

        function uncheckAll2() {
            $('input[type=checkbox]').each(function() {
                this.checked = false;
            });
        }


        function getPermission() {
            var data2 = {
                'STATUS': 'getPermission'
            };
            // console.log(JSON.stringify(data2));
            senddata(JSON.stringify(data2));
        }

        function getFactory(chk) {
            
            if (chk == 1) {
                var Hotp = $('#hptsel').val();
                $('#host').val(Hotp);
            } else if (chk == 2) {
                var Hotp = $('#host').val();
                $('#hptsel').val(Hotp);
            }else{
                var Hotp = '';
            }
            var data = {
                'STATUS': 'getFactory',
                'Hotp': Hotp
            };
            senddata(JSON.stringify(data));
        }


        function ShowItem(chk) {
            if (chk == 1) {
                var Hotp = $('#hptsel').val();
                $('#host').val(Hotp);
                $('#department').css('border-color', '');
                $('#host').css('border-color', '');
                $('#rem1').hide();
                $('#rem2').hide();
            } else if (chk == 2) {
                var Hotp = $('#host').val();
                $('#hptsel').val(Hotp);
                $('#department').css('border-color', '');
                $('#host').css('border-color', '');
                $('#rem1').hide();
                $('#rem2').hide();
            }
            var HptCode = $('#hptsel').val();
            var keyword = $('#searchitem').val();
            var department2 = $('#department').val();
            var Dep = $('#selectDep').val();

            var data = {
                'STATUS': 'ShowItem',
                'HptCode': HptCode,
                'Keyword': keyword,
                'department2': department2,
                'Dep': Dep
            };
            senddata(JSON.stringify(data));
        }

        function change_permission() {
            var Permission = $('#Permission').val();
            if (Permission != "" && Permission != undefined) {
                $('#rem7').hide();
                $('#Permission').css('border-color', '');
            }
            if (Permission == 4) {
                getDepartment2(1);
                $('#factory').attr('disabled', false);
            } else {
                getDepartment2(1);
                $('#factory').attr('disabled', true);
            }
        }

        function resetinput2() {
            var UsID = $('#UsID').val();
            var UserName = $('#username').val();
            var Password = $('#Password').val();
            var Enfname = $('#Enfname').val();
            var Enlname = $('#Enlname').val();
            var Thfname = $('#Thfname').val();
            var Thlname = $('#Thlname').val();

            var host = $('#host').val();
            var Permission = $('#Permission').val();
            var facID = $('#factory').val();
            var email = $('#email').val();

            if (Permission == 4) {
                $('#factory').attr('disabled', false);
            } else {
                $('#factory').attr('disabled', true);
            }
            // getDepartment2(1);

            if (host != "" && host != undefined) {
                $('#rem1').hide();
                $('#host').css('border-color', '');
            }
            if (UserName != "" && UserName != undefined) {
                $('#rem3').hide();
                $('#username').css('border-color', '');
            }
            if (Password != "" && Password != undefined) {
                $('#rem4').hide();
                $('#Password').css('border-color', '');
            }
            if (Enfname != "" && Enfname != undefined) {
                $('#rem5').hide();
                $('#Enfname').css('border-color', '');
            }
            if (Enlname != "" && Enlname != undefined) {
                $('#rem5').hide();
                $('#Enlname').css('border-color', '');
            }
            if (Thfname != "" && Thfname != undefined) {
                $('#rem6').hide();
                $('#Thfname').css('border-color', '');
            }
            if (Thlname != "" && Thlname != undefined) {
                $('#rem6').hide();
                $('#Thlname').css('border-color', '');
            }

            if (email != "" && email != undefined) {
                $('#rem8').hide();
                $('#email').css('border-color', '');
            }
        }

        function AddItem() {
            var count = 0;
            $(".checkblank").each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    count++;
                }
            });
            var UsID = $('#UsID').val();
            var UserName = $('#username').val();
            var Password = $('#Password').val();
            if (Password == "") {
                Password = 123;
            }
            var host = $('#host').val();
            var department = $('#department').val();
            var Permission = $('#Permission').val();
            var facID = $('#factory').val();
            var email = $('#email').val();

            var EngPerfix = $('#EnPerfix').val();
            var ThPerfix = $('#ThPerfix').val();
            var EngName = $('#Enfname').val();
            var EngLName = $('#Enlname').val();
            var ThName = $('#Thfname').val();
            var ThLName = $('#Thlname').val();
            var remask = $('#remask').val();

            if (host == "" || host == undefined) {
                $('#rem1').show().css("color", "red");
            }
            if (department == "" || department == undefined) {
                $('#rem2').show().css("color", "red");
            }
            if (UserName == "" || UserName == undefined) {
                $('#rem3').show().css("color", "red");
            }
            if (Permission == "" || Permission == undefined) {
                $('#rem7').show().css("color", "red");
            }
            if (email == "" || email == undefined) {
                $('#rem8').show().css("color", "red");
            }
            if (EngName == "" || EngLName == "") {
                $('#rem5').show().css("color", "red");
            }
            if (ThName == "" || ThName == "") {
                $('#rem6').show().css("color", "red");
            }
            if ($('#xemail').is(':checked')) xemail = 1;
            if (count == 0) {
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    }
                });
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
                        var file_data = $('#image').prop('files')[0];

                        var form_data = new FormData();
                        form_data.append('file', file_data);
                        form_data.append('UsID', UsID);
                        form_data.append('UserName', UserName);
                        form_data.append('Password', Password);
                        form_data.append('host', host);
                        form_data.append('department', department);
                        form_data.append('Permission', Permission);
                        form_data.append('facID', facID);
                        form_data.append('email', email);
                        form_data.append('EngPerfix', EngPerfix);
                        form_data.append('ThPerfix', ThPerfix);
                        form_data.append('EngName', EngName);
                        form_data.append('EngLName', EngLName);
                        form_data.append('ThName', ThName);
                        form_data.append('ThLName', ThLName);
                        form_data.append('remask', remask);
                        var URL = '../process/insertUser.php';
                        $.ajax({
                            url: URL,
                            dataType: 'text',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            success: function(result) {
                                var msg = "";

                                if (result == 1) {
                                    msg = "<?php echo $array['addsuccessmsg'][$language]; ?>";
                                } else if (result == 2) {
                                    msg = "<?php echo $array['adduserfailedmsg'][$language]; ?>";
                                } else if (result == 3) {
                                    msg = "<?php echo $array['editsuccessmsg'][$language]; ?>";
                                } else if (result == 4) {
                                    msg = "<?php echo $array['editfailedmsg'][$language]; ?>";
                                } else if (result == 5) {
                                    msg = "<?php echo $array['editfailDueedmsg'][$language]; ?>";
                                }
                                if (result == 2 || result == 4 || result == 5) {
                                    swal({
                                        title: '',
                                        text: msg,
                                        type: 'warning',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        confirmButtonText: 'Ok'
                                    });
                                } else {
                                    swal({
                                        title: '',
                                        text: msg,
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonColor: '#3085d6',
                                        cancelButtonColor: '#d33',
                                        showConfirmButton: false,
                                        timer: 2000,
                                        confirmButtonText: 'Ok'
                                    });
                                }
                                setTimeout(function() {
                                    $('xemail').prop("checked", false);
                                    Blankinput();
                                    getDepartment2(1);
                                }, 1000);

                            }
                        });
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
                $('.checkblank').each(function() {
                    if ($(this).val() == "" || $(this).val() == undefined) {
                        $(this).css('border-color', 'red');
                    }
                });
            }
        }

        function CancelItem() {
            var UsID = $('#UsID').val();

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
                    var data = {
                        'STATUS': 'CancelItem',
                        'UsID': UsID
                    }
                    senddata(JSON.stringify(data));
                } else if (result.dismiss === 'cancel') {
                    swal.close();
                }
            })
        }

        function resetinput() {
            $('#username').val("");
            $('#Password').val("");
            $('#flname').val("");
            // $('#host tbody').empty();
            $('#department2').val("");
            $('#Permission tbody').empty();
            $('#UsID').empty();
            $('#email').val("");
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            $(".dropify-clear").click();
        }

        function Blankinput() {
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            $('#rem5').hide();
            $('#rem6').hide();
            $('#rem7').hide();
            $('#username').val("");
            $('#Password').val("").attr('disabled', false);
            $('#flname').val("");
            $('.checkblank').each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    $(this).css('border-color', '');
                    $(this).val("");
                }
            });

            $('#remask').val("");
            $('#factory').attr('disabled', true);
            $('#department2').val("");
            $('#department').val("");
            $('#Permission').val("");
            $('#factory').val("0");
            $('#UsID').val("");
            $('#email').val("");
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            $(".dropify-clear").click();
            $(".dropify-clear").click();
            $('#Enfname').val("");
            $('#Enlname').val("");
            $('#Thfname').val("");
            $('#Thlname').val("");
            $('#EnPerfix').val("Mr.");
            $('#ThPerfix').val("?????????");
            getDepartment2(1);
        }

        function Blankinput2() {
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            $('#rem5').hide();
            $('#rem6').hide();
            $('#rem7').hide();
            $('#username').val("");
            $('#Password').val("");
            $('#flname').val("");
            $('.checkblank').each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    $(this).css('border-color', '');
                } else {
                    $(this).css('border-color', '');
                }
            });
            // $('#host tbody').empty();
            $('#factory').attr('disabled', true);
            $('#hptsel').val("");
            $('#host').val("");
            // $('#department2').val("");
            // $('#department').val("");
            $('#Permission').val("");
            $('#factory').val("0");
            $('#UsID').val("");
            $('#email').val("");
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            $(".dropify-clear").click();
            $("#xemail").prop('checked', false);
            // $('#xemail').attr("checked", false);
            // $('.xemail').each(function() {
            //     $(this).val("");
            //     $('.xemail').attr("checked", false);
            // });
            $(".dropify-clear").click();
            // getHotpital();
            // getDepartment();
            // getPermission();
            // ShowItem();
            // uncheckAll2();
            // setTimeout(() => {
            //     getDepartment();
            // }, 0);
        }

        function Blankinput3() {
            $('#rem1').hide();
            $('#rem2').hide();
            $('#rem3').hide();
            $('#rem4').hide();
            $('#rem5').hide();
            $('#rem6').hide();
            $('#rem7').hide();
            $('#username').val("");
            $('#Password').val("");
            $('#flname').val("");
            $('.checkblank').each(function() {
                if ($(this).val() == "" || $(this).val() == undefined) {
                    $(this).css('border-color', '');
                } else {
                    $(this).css('border-color', '');
                }
            });
            // $('#host tbody').empty();
            $('#factory').attr('disabled', true);
            $('#department2').val("");
            $('#department').val("");
            $('#Permission').val("");
            $('#factory').val("0");
            $('#UsID').val("");
            $('#email').val("");
            $('#bCancel').attr('disabled', true);
            $('#delete_icon').addClass('opacity');
            $('#delete1').removeClass('mhee');
            $(".dropify-clear").click();
            $("#xemail").prop('checked', false);
            $(".dropify-clear").click();
        }

        function getdetail(ID, row) {
            var previousValue = $('#checkitem_' + row).attr('previousValue');
            var name = $('#checkitem_' + row).attr('name');
            if (previousValue == 'checked') {
                $('#checkitem_' + row).removeAttr('checked');
                $('#checkitem_' + row).attr('previousValue', false);
                $('#checkitem_' + row).prop('checked', false);
                // $('#Password').attr('disabled' , false);
                // $('#Password').addClass('checkblank');
                Blankinput();
            } else {
                $("input[name=" + name + "]:radio").attr('previousValue', false);
                $('#checkitem_' + row).attr('previousValue', 'checked');
                // $('#Password').attr('disabled' , true);
                // $('#Password').removeClass('checkblank');
                if (ID != "" && ID != undefined) {
                    var data = {
                        'STATUS': 'getdetail',
                        'ID': ID
                    };
                    // console.log(JSON.stringify(data));
                    senddata(JSON.stringify(data));
                }
            }
        }

        function senddata(data) {
            var form_data = new FormData();
            form_data.append("DATA", data);
            var URL = '../process/user.php';
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
                            $('#xemail').attr("checked", false);
                            $("#TableItem tbody").empty();
                            console.log(temp);
                            if (temp['Count'] > 0) {
                                for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                    var email = temp[i]['email'] == null ? '-' : temp[i]['email'];
                                    var active_mail = temp[i]['Active_mail'] == 1 ? '<i class="fas fa-check fa-sm"></i>' : '';
                                    var rowCount = $('#TableItem >tbody >tr').length;
                                    var chkDoc = "<label class='radio'style='margin-top: 50%;'><input type='radio' name='checkitem' id='checkitem_" + i + "' style='margin-top: 24%;' value='" + temp[i]['ID'] + "' onclick='getdetail(\"" + temp[i]["ID"] + "\",\"" + i + "\")'><span class='checkmark'></span></label>";
                                    // var Qty = "<div class='row' style='margin-left:5px;'><button class='btn btn-danger' style='width:35px;' onclick='subtractnum(\""+i+"\")'>-</button><input class='form-control' style='width:50px; margin-left:3px; margin-right:3px; text-align:center;' id='qty"+i+"' value='0' disabled><button class='btn btn-success' style='width:35px;' onclick='addnum(\""+i+"\")'>+</button></div>";
                                    StrTR = "<tr id='tr" + temp[i]['DepCode'] + "' style='border-radius: 15px 15px 15px 15px;margin-top: 6px;margin-bottom: 6px;'>" +
                                        "<td style='width: 3%;' nowrap>" + chkDoc + "</td>" +
                                        "<td style='text-overflow: ellipsis;overflow: hidden; width: 5%;' nowrap >" + (i + 1) + "</td>" +
                                        "<td style=' text-overflow: ellipsis;overflow: hidden; width: 22%;' nowrap title='" + temp[i]['Name'] + "'>" + temp[i]['Name'] + "</td>" +
                                        "<td style='text-overflow: ellipsis;overflow: hidden; width: 20%;' nowrap title='" + temp[i]['UserName'] + "'>" + temp[i]['UserName'] + "</td>" +
                                        "<td style='text-overflow: ellipsis;overflow: hidden; width: 24%;' nowrap title='" + email + "'>" + email + "</td>" +
                                        // "<td style='width: 8%;' nowrap class='text-center'>"+active_mail+"</td>" +
                                        "<td style='text-overflow: ellipsis;overflow: hidden; width: 10%;' nowrap title='" + temp[i]['Permission'] + "'>" + temp[i]['Permission'] + "</td>" +
                                        "<td style='text-overflow: ellipsis;overflow: hidden; width: 16%;' nowrap title='" + temp[i]['HptName'] + "'>" + temp[i]['HptName'] + "</td>" +
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
                            // uncheckAll2();
                            $('#factory').val(0);
                            // ------------------------------------
                            $(".dropify-clear").click();
                            // ------------------------------------

                            if ((Object.keys(temp).length - 2) > 0) {

                                $("#department").empty();
                                var StrTr = "<option value = '0'><?php echo $array['selectdep'][$language]; ?></option>";
                                for (var i = 0; i < temp['DepCnt']; i++) {
                                    StrTr += "<option value = '" + temp['dep' + i]['xDepCode'] + "'> " + temp['dep' + i]['xDepName'] + " </option>";
                                }

                                $("#department").append(StrTr);

                                $('#UsID').val(temp['ID']);
                                $('#username').val(temp['UserName']);
                                $('#department').val(temp['DepCode']);
                                $('#email').val(temp['email']);
                                $('#bCancel').attr('disabled', false);
                                $('#delete_icon').removeClass('opacity');
                                $('#delete1').addClass('mhee');
                                $('#host').val(temp['HptCode']);
                                $('#Permission').val(temp['PmID']);

                                $('#EnPerfix').val(temp['EngPerfix']);
                                $('#ThPerfix').val(temp['ThPerfix']);
                                $('#Enfname').val(temp['EngName']);
                                $('#Enlname').val(temp['EngLName']);
                                $('#Thfname').val(temp['ThName']);
                                $('#Thlname').val(temp['ThLName']);
                                $('#remask').val(temp['remask']);

                                if (temp['PmID'] == 4) {
                                    $('#factory').attr('disabled', false);
                                    $('#factory').val(temp['FacCode']);
                                } else {
                                    $('#factory').attr('disabled', true);
                                }
                                var imageName = "../profile/img/" + temp['pic'];

                                var drEvent = $('.dropify').dropify({
                                    defaultFile: imageName
                                });
                                drEvent = drEvent.data('dropify');
                                drEvent.resetPreview();
                                drEvent.clearElement();
                                drEvent.settings.defaultFile = imageName;
                                drEvent.destroy();
                                drEvent.init();
                            }
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
                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).css('border-color', '');
                                });
                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
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
                                Blankinput();

                            }, function(dismiss) {
                                $('.checkblank').each(function() {
                                    $(this).val("");
                                });

                                $('#DepCode').val("");
                                $('#hptsel2').val("1");
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
                                Blankinput();
                                $(".dropify-clear").click();


                            }, function(dismiss) {
                                Blankinput();
                            })
                        } else if ((temp["form"] == 'getHotpital')) {
                            $("#host").empty();
                            $("#hptsel").empty();
                            if (temp[0]['PmID'] != 5 && temp[0]['PmID'] != 7) {
                                var StrTr = "<option value=''><?php echo $array['selecthospital'][$language]; ?></option>";
                            } else {
                                var StrTr = "";
                                $('#hptsel').attr('disabled', true);
                                $('#hptsel').addClass('icon_select');

                                $('#host').attr('disabled', true);
                                $('#host').addClass('icon_select');
                            }
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value = '" + temp[i]['HptCode'] + "'> " + temp[i]['HptName'] + " </option>";
                            }
                            $("#host").append(StrTr);
                            $("#hptsel").append(StrTr);
                        } else if (temp["form"] == 'getDepartment2') {
                            $("#department").empty();
                            if (temp['count'] == 0) {
                                var Str2 = "<option value=''><?php echo $array['selectdep'][$language]; ?></option>";
                            } else {
                                var Str2 = "";
                            }
                            for (var i = 0; i < temp['count']; i++) {
                                Str2 += "<option value=" + temp[i]['DepCode'] + ">" + temp[i]['DepName'] + "</option>";
                            }
                            $("#department").append(Str2);
                        } else if ((temp["form"] == 'getPermission')) {
                            $("#Permission").empty();
                            var StrTr = "<option value=''><?php echo $array['selectpermission'][$language]; ?></option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value = '" + temp[i]['PmID'] + "'> " + temp[i]['Permission'] + " </option>";
                            }
                            $("#Permission").append(StrTr);
                        } else if ((temp["form"] == 'getFactory')) {
                            $("#factory").empty();
                            var StrTr = "<option value = '0'><?php echo $array['selectfactory'][$language]; ?></option>";
                            for (var i = 0; i < (Object.keys(temp).length - 2); i++) {
                                StrTr += "<option value = '" + temp[i]['FacCode'] + "'> " + temp[i]['FacName'] + " </option>";
                            }
                            $("#factory").append(StrTr);
                        } else if ((temp["form"] == 'getDep')) {
                            $("#selectDep").empty();
                            var StrTr = "<option value = '0'><?php echo $array['selectdep'][$language]; ?></option>";
                            for (var i = 0; i < temp['count']; i++) {
                                StrTr += "<option value = '" + temp[i]['DepCode'] + "'> " + temp[i]['DepName'] + " </option>";
                            }

                            $("#selectDep").append(StrTr);
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
                        swal({
                            title: '',
                            text: "<?php echo $array['notfoundmsg'][$language]; ?>",
                            type: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            showConfirmButton: false,
                            timer: 2000,
                            confirmButtonText: 'Ok'
                        })
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
            width: 100%;
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

        label {
            margin-bottom: 0rem !important;
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

        #Permission:focus {
            background-color: #E7E6E6;
        }

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
        <li class="breadcrumb-item active"><?php echo $array2['menu']['system']['sub'][10][$language]; ?></li>
    </ol>
    <div id="wrapper">
        <a class="scroll-to-down rounded" id="pageDown" href="#page-down">
            <i class="fas fa-angle-down"></i>
        </a>
        <!-- content-wrapper -->
        <!--
                    <!-- tag column 1 -->
        <div class="container-fluid mt-3">
            <div class="card-body" style="padding:0px; margin-top:-12px;">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control  " id="hptsel" onchange="getDepartment2(1);"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select class="form-control  select2 custom-select" id="selectDep"></select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <input type="text" autocomplete="off" class="form-control" name="searchitem" id="searchitem" placeholder="<?php echo $array['searchuser'][$language]; ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="row">
                            <div class="search_custom ">
                                <div class="search_1 d-flex justify-content-start">
                                    <button class="btn" onclick="ShowItem()" id="bSave">
                                        <i class="fas fa-search mr-2"></i>
                                        <?php echo $array['search'][$language]; ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-md-3 " hidden>
                        <div class="row" style="margin-left:5px;">
                            <select class="form-control " id="hptsel" style="visibility:hidden;">

                            </select>
                        </div>
                    </div>
                </div>
                <table style="margin-top:10px;" class="table table-fixed table-condensed table-striped" id="TableItem" width="100%" cellspacing="0" role="grid">
                    <thead id="theadsum" style="font-size:11px;">
                        <tr role="row">
                            <th style='width: 3%;' nowrap>&nbsp;</th>
                            <th style='width: 5%;' nowrap> <?php echo $array['no'][$language]; ?> </th>
                            <th style='width: 22%;' nowrap> <?php echo $array['flname'][$language]; ?> </th>
                            <th style='width: 20%;' nowrap> <?php echo $array['username'][$language]; ?> </th>
                            <th style='width: 24%;' nowrap> <?php echo $array['email'][$language]; ?> </th>
                            <th style='width: 10%;' nowrap> <?php echo $array['permission'][$language]; ?> </th>
                            <th style='width: 16%;' nowrap> <?php echo $array['department'][$language]; ?> </th>
                        </tr>
                    </thead>
                    <tbody id="tbody" class="nicescrolled" style="font-size:11px;height:380px;">
                    </tbody>
                </table>

            </div>
        </div><!-- tag column 1 -->
    </div>
    <!-- =================================================================== -->
    <!-- /.content-wrapper -->
    <div class="row col-12 m-1 mt-4 mb-4 d-flex justify-content-end">
        <div class="menu mhee">
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
        <div class="menu" id="delete1">
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
                <div class="card-body" style="padding:0px; margin-top:10px;">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">
                                <?php echo $array['detail'][$language]; ?></a>
                        </li>
                    </ul>
                    <div class="row" style="margin-top:10px;">
                        <div class="col-md-2">
                            <div class="row" style="margin-left:30px;">

                            </div>
                        </div>
                        <div class="col-md-6" style="margin-left:15px;">
                            <div class="row">
                                <input type="hidden" class="form-control " style="width:90%;" name="UsID" id="UsID">
                            </div>
                        </div>
                    </div>
                    <!-- =================================================================== -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['side'][$language]; ?></label>
                                <select onchange="getDepartment2(2)" class="form-control col-sm-8 checkblank" id="host"></select>
                                <label id="rem1" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label " style="font-size:24px;"><?php echo $array['department'][$language]; ?></label>
                                <select class="form-control col-sm-8 checkblank select2 custom-select" style="font-size:22px;" id="department">
                                </select>
                                <label id="rem2" class="col-sm-1 " style="font-size: 180%;margin-top: -1%;"> * </label>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['username'][$language]; ?></label>
                                <input type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control col-sm-8 checkblank" id="username" placeholder="<?php echo $array['username'][$language]; ?>">
                                <label id="rem3" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['password'][$language]; ?></label>
                                <input <?php if ($PmID != 6 && $PmID  != 1) {
                                            echo "disabled='true'";
                                        } ?> type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control col-sm-8 " id="Password" placeholder="<?php echo $array['password'][$language]; ?>">
                                <label id="rem4" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['engperfix'][$language]; ?></label>
                                <select class="form-control col-sm-8" id="EnPerfix">
                                    <option value="Mr.">Mr.</option>
                                    <option value="Ms.">Ms.</option>
                                    <option value="Mrs.">Mrs.</option>
                                    <option value="Miss">Miss</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['thperfix'][$language]; ?></label>
                                <select class="form-control col-sm-8" id="ThPerfix">
                                    <option value="?????????">?????????</option>
                                    <option value="?????????">?????????</option>
                                    <option value="??????????????????">??????????????????</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['nameeng'][$language]; ?></label>
                                <input type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control charonly col-sm-4 mr-1 checkblank" id="Enfname" placeholder="First name">
                                <input type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control charonly col-sm-4 checkblank" id="Enlname" placeholder="Lastname">
                                <label id="rem5" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['nameth'][$language]; ?></label>
                                <input type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control charonlyTH col-sm-4 mr-1 checkblank" id="Thfname" placeholder="????????????">
                                <input type="text" onkeyup="resetinput2()" autocomplete="off" class="form-control charonlyTH col-sm-4 checkblank" id="Thlname" placeholder="?????????????????????">
                                <label id="rem6" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['permission'][$language]; ?></label>
                                <select onchange="change_permission()" class="form-control col-sm-8 checkblank " id="Permission"></select>
                                <label id="rem7" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['email'][$language]; ?></label>
                                <input type="email" onkeyup="resetinput2()" autocomplete="off" class="form-control col-sm-8 checkblank" id="email" placeholder="<?php echo $array['email'][$language]; ?>">
                                <label id="rem8" style="font-size: 180%;margin-top: -1%;padding-left:5px;"> * </label>
                            </div>
                        </div>
                    </div>
                    <!-- =================================================================== -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['Laundry2'][$language]; ?></label>
                                <select class="form-control col-sm-8" id="factory" disabled="true"></select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['remask'][$language]; ?></label>
                                <input type="text" class="form-control col-sm-8" id="remask">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class='form-group row'>
                                <label class="col-sm-3 col-form-label "><?php echo $array['img'][$language]; ?></label>
                                <div class="col-md-8" style="padding:0px;">
                                    <input type="file" class="dropify" accept="image/x-png,image/gif,image/jpeg" id="image" name="image" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ???????????? -->
                </div>
            </div>
        </div> <!-- tag column 2 -->
        <!-- =============================================================================================== -->

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
        <script src="../dropify/dist/js/dropify.min.js"></script>
        <script src="../select2/dist/js/select2.full.min.js" type="text/javascript"></script>

        <script>
            $(document).ready(function(e) {
                $(".select2").select2();
                $('.dropify').dropify();

                // Used events
                var drEvent = $('#input-file-events').dropify();

                drEvent.on('dropify.beforeClear', function(event, element) {
                    return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
                });

                drEvent.on('dropify.afterClear', function(event, element) {
                    alert('File deleted');
                });

                drEvent.on('dropify.errors', function(event, element) {
                    console.log('Has Errors');
                });

            });
        </script>

</body>

</html>