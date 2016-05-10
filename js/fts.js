//OBSOLETE
function printBarcode(fileNumber) {
    $.ajax({
        url: "?c=fts&m=get_barcode",
        type: 'POST',
        data: {file_number: fileNumber},
        dataType: 'JSON',
        success: function (serverData) {
            var imgUrl = base_url + "bar_codes/" + serverData.barcode_id + ".png";
            window.open(imgUrl).print();
        }
    });
}

function innerHtmlFileTrack(fileNumber) {
    $.ajax({
        url: "?c=fts&m=file_tracking",
        type: 'POST',
        data: {file_number: fileNumber},
        dataType: 'JSON',
        success: function (serverData) {
            //MODEL POP-UP
            $("#file_track_modal").modal('show');

            $('#file_track_modal #file_heading').html(serverData.file_heading);

            var fileTrackingDetails = serverData.file_tracking_details;
            var fileTrackingDetailsLength = fileTrackingDetails.length;
            var fileTrackingInnerHtml = '';

            if (fileTrackingDetailsLength === 0) {
                fileTrackingInnerHtml = '<tr><td style="text-align:center;" colspan="3">No Records!!!</td></tr>';
            }

            for (var i = 0; i < fileTrackingDetailsLength; i++) {
                fileTrackingInnerHtml += '<tr><td>'
                        + fileTrackingDetails[i].current_location
                        + '</td><td>'
                        + fileTrackingDetails[i].created_date
                        + '</td><td>'
                        + fileTrackingDetails[i].status
                        + '</td></tr>';
            }


            $('#file_track_table tbody').html(fileTrackingInnerHtml);
        }
    });
}
function ajaxHtmlFileTrack(fileNumber) {
    $.ajax({
        url: "?c=fts&m=my_file_tracking",
        type: 'POST',
        data: {file_number: fileNumber},
        dataType: 'html',
        success: function (serverData) {
            //MODEL POP-UP
            $("#file_track_modal").modal('show');

            //$('#file_track_modal #file_heading').html(serverData.file_heading);
            
            $('#ajaxFileTrack').html(serverData);
        }
    });
}
function cloneFileDetail(fileID) {


    //MODEL POP-UP
    $("#file_clone_modal").modal('show');

    $.ajax({
        url: "?c=fts&m=file_detail_ajax",
        type: 'POST',
        data: {fileID: fileID},
        dataType: 'JSON',
        success: function (serverData, textStatus, jqXHR) {
            loadAdditionalContactEmail('clone', "#clone_button_pro", serverData.file_additional_contact_email, true);
            $('#clone_file_name').val(serverData.file_name);
            $('#clone_file_no').val(serverData.file_number);
            $('#clone_file_subject').val(serverData.file_subject);
            $('#clone_file_department').val(serverData.department);
            $('#clone_file_subject_hindi').val(serverData.file_subject_hindi);
            $('#clone_file_department_hindi').val(serverData.file_department_hindi);
            $('#clone_file_volume').val(serverData.volume);
            $('#clone_file_part_case').val(serverData.file_part_number);
            $('#clone_file_division').val(serverData.parent_division_id);
            //$('#clone_file_sub_division').val(serverData.parent_sub_division_id);
            loadSubDivisionId(serverData.parent_division_id, serverData.parent_sub_division_id, '#clone_file_sub_division');
            $('#clone_file_date_opened').val(serverData.file_date_opened);
            $('#clone_file_date_closed').val(serverData.file_date_closed);
            $('#clone_file_unit').val(serverData.file_unit);
            $('#clone_file_contact_email').val(serverData.file_contact_email);
            $('#clone_file_contact_mobile').val(serverData.file_contact_mobile);
            $('#clone_file_contact_landline').val(serverData.file_contact_landline);
            $('#clone_file_category').val(serverData.file_category);
            $('#clone_file_tags').val(serverData.file_tags);
            $('#clone_file_pk').val(serverData.file_pk);
            $('#clone_office_purpose').val(serverData.office_purpose);
        }
    });


}

//OBSOLETE
function printExternal(url) {
    var printWindow = window.open(url, 'Print', 'left=200, top=200, width=950, height=500, toolbar=0, resizable=0');
    printWindow.addEventListener('load', function () {
        printWindow.print();
        printWindow.close();
    }, true);
}

function printCoverPageLabel(fileNumber, lbxFilesArray) {
    //printExternal("?c=fts&m=file_cover_page&file_number=" + fileNumber);
    $.ajax({
        url: "?c=fts&m=file_cover_page",
        type: 'POST',
        data: {file_number: fileNumber},
        dataType: 'JSON',
        success: function (serverData) {
//            console.log(serverData.barcode_id);
//            console.log(serverData.file_number);
            brotherPrinterSDK(serverData, lbxFilesArray);
        }
    });
}

//Support only IE
function brotherPrinterSDK(data, templateNameArray) {
    var DATA_FOLDER = base_url + "bar_codes/";//"C:\\Program Files (x86)\\Brother bPAC3 SDK\\Templates\\";

    templateNameArray.map(function (templateName) {

        var strPath = DATA_FOLDER + templateName + '.lbx';

        var objDoc = new ActiveXObject("bpac.Document");
        if (objDoc.Open(strPath) != false)
        {
            if (templateName === 'qr_code') {
                objDoc.SetBarcodeData(0, data.barcode_id);
                objDoc.GetObject("file_number").Text = data.file_number;
            } else if (templateName === 'vol_pc') {
                objDoc.SetBarcodeData(0, data.barcode_id);
                objDoc.GetObject("file_number").Text = data.file_number;

                var fileVolume = 'VOL-' + data.volume;
                var partCase = data.file_part_number ? '/ PC-' + data.file_part_number : '';
                objDoc.GetObject("file_volume").Text = fileVolume + partCase;
            } else if (templateName === 'logo') {
                objDoc.GetObject("barcode_id").Text = data.barcode_id;
            } else if (templateName === 'dept') {
                objDoc.GetObject("EDept").Text = 'DEP: ' + data.department;
                objDoc.GetObject("HDept").Text = 'विभाग: ' + data.file_department_hindi;
            } else if (templateName === 'sub') {
                objDoc.GetObject("ESub").Text = 'SUB: ' + data.file_subject;
                objDoc.GetObject("HSub").Text = 'विषय: ' + data.file_subject_hindi;
            }

            var isSuccess = false;

            //PRINT
            objDoc.StartPrint("", 0);
            objDoc.PrintOut(1, 0);
            objDoc.Close();
            isSuccess = objDoc.EndPrint();

            //BMP
//            var fso = new ActiveXObject("Scripting.FileSystemObject");
//            var TEMP_FOLDER = fso.GetSpecialFolder(2);
//            var strExport = TEMP_FOLDER + '\\' + templateName + '.bmp';
//            objDoc.Export(4, strExport, 180);
//            isSuccess = objDoc.Close();
////        alert(isSuccess);
////        alert(strExport);
//            console.log(strExport);
        }
    });
}

function editFileDetail(fileID) {
    //MODEL POP-UP
    $("#file_edit_modal").modal('show');

    $.ajax({
        url: "?c=fts&m=file_detail_ajax",
        type: 'POST',
        data: {fileID: fileID},
        dataType: 'JSON',
        success: function (serverData, textStatus, jqXHR) {
            loadAdditionalContactEmail('edit', "#edit_button_pro", serverData.file_additional_contact_email, true);
            $('#edit_file_name').val(serverData.file_name);
            $('#edit_barcode_id').val(serverData.barcode_id.replace(/-.*/g, ''));
            $('#edit_file_number').val(serverData.file_number);
            $('#edit_file_subject').val(serverData.file_subject);
            $('#edit_file_department').val(serverData.department);
            $('#edit_file_subject_hindi').val(serverData.file_subject_hindi);
            $('#edit_file_department_hindi').val(serverData.file_department_hindi);
            $('#edit_file_volume').val(serverData.volume);
            $('#edit_file_part_case').val(serverData.file_part_number);
            $('#edit_file_division').val(serverData.parent_division_id);
            //$('#edit_file_sub_division').val(serverData.parent_sub_division_id);
            loadSubDivisionId(serverData.parent_division_id, serverData.parent_sub_division_id, '#edit_file_sub_division');
            $('#edit_file_date_opened').val(serverData.file_date_opened);
            $('#edit_file_date_closed').val(serverData.file_date_closed);
            $('#edit_file_unit').val(serverData.file_unit);
            $('#edit_file_contact_email').val(serverData.file_contact_email);
            $('#edit_file_contact_mobile').val(serverData.file_contact_mobile);
            $('#edit_file_contact_landline').val(serverData.file_contact_landline);
            $('#edit_file_category').val(serverData.file_category);
            $('#edit_file_tags').val(serverData.file_tags);
            $('#edit_file_pk').val(serverData.file_pk);
            $('#edit_office_purpose').val(serverData.office_purpose);
        }
    });
}

function loadSubDivisionId(divisionId, subDivisionId, subDivision_div_id) {

    $.ajax({
        url: "?c=fts&m=get_subdivision",
        type: 'POST',
        data: {division_id: divisionId},
        dataType: 'JSON',
        success: function (serverData) {
            var length = serverData.length;
            var innerHtml =
                    '<option class="select-option" value="">' +
                    'Choose Sub-Division </option>';

            for (var i = 0; i < length; i++) {
                if(subDivisionId == serverData[i].id) {
                    innerHtml +=
                            '<option class="select-option" value="' +
                            serverData[i].id + '" selected>' +
                            serverData[i].subdivision + '</option>';
                } else {
                    innerHtml +=
                            '<option class="select-option" value="' +
                            serverData[i].id + '">' +
                            serverData[i].subdivision + '</option>';
                }
            }

            $(subDivision_div_id).html(innerHtml);
        }
    });

}

$(function () {
    //EDIT FILE DETAIL VALIDATION
    $("#file_edit_form").validate({
        rules: {
            edit_file_number: {
                required: true,
                maxlength: 6
            },
            edit_file_subject: {
                required: true
            },
            edit_file_contact_email: {
                required: true,
                email: true
            },
            'edit_file_additional_contact_email[]': {
                email: true
            },
            edit_file_department: {
                required: true
            }
        },
        messages: {
            edit_file_number: {
                required: "Please enter file number"
            },
            edit_file_subject: {
                required: "Please enter file subject"
            },
            edit_file_contact_email: {
                required: "Please enter the email",
                email: "Please enter valid email"
            },
            edit_file_department: {
                required: "Please enter the department name"
            }
        }
    });

    //Reset modal form
    $('.modal').on('hidden.bs.modal', function (e)
    {
        $(this)
                .find("input,textarea,select")
                .val('')
                .end()
                .find("input[type=checkbox], input[type=radio]")
                .prop("checked", "")
                .end();
    });
});

function deleteFileDetail(fileID) {
    $.ajax({
        url: "?c=fts&m=delete_file_ajax",
        type: 'POST',
        data: {fileID: fileID},
        dataType: 'JSON',
        success: function (serverData) {
            window.location.href = window.location.pathname + window.location.search;
        }
    });
}

function softDeleteFileDetail(fileID, comments) { 
    $.ajax({
        url: "?c=fts&m=soft_delete_file_ajax",
        type: 'POST',
        data: {fileID: fileID, comments: comments},
        dataType: 'JSON',
        success: function (serverData) {
            window.location.href = window.location.pathname + window.location.search;
        }
    });
}

function updateRecordStatus(filePK) {
    $('#record_status_form').unbind('submit').submit(function (e) {
        e.preventDefault();
        var statusOption = $('#record_status_name').val();
        var statusComment = $('#record_status_comment').val();
        var selectedText = $('#record_status_name option:selected').text();

        if ($("#record_status_form").valid()) {
            $.ajax({
                url: "?c=fts&m=update_file_record_status_ajax",
                type: 'POST',
                data: {filePK: filePK, statusType: statusOption, statusComment: statusComment},
                dataType: 'JSON',
                async: false,
                success: function (serverData) {
                    if (serverData.success === 'true') {
                        $('#file_record_status_modal').modal('hide');
                        $('#out_msg_cont').html('Status Successfully Updated.');
                        $('#out_msg').modal('show');

                        $('body').tooltip({
                            selector: '.record_status_text div'
                        });
                        $('#todays_list [data-id="' + filePK + '"]')
                                .siblings('.record_status_text')
                                .html('<div data-original-title="' + statusComment +
                                        '" data-toggle="tooltip">' + selectedText +
                                        '</div>');
                    }
                }
            });
        }
    });
}

$(function () {
    $("#record_status_form").validate({
        rules: {
            record_status_name: {
                required: true
            },
            record_status_comment: {
                maxlength: 150
            }
        },
        messages: {
            record_status_name: {
                required: "Please select status"
            }
        }
    });
});

function viewFileDetail(fileID) {

    $.ajax({
        url: "?c=fts&m=file_detail_ajax",
        type: 'POST',
        data: {fileID: fileID},
        dataType: 'JSON',
        success: function (serverData, textStatus, jqXHR) {
            loadAdditionalContactEmail('view', "#view_button_pro", serverData.file_additional_contact_email, false);
            $('#view_file_name').val(serverData.file_name);
            if(serverData.barcode_id != null) {
                $('#view_barcode_id').val(serverData.barcode_id.replace(/-.*/g, ''));
            }else {
                $('#view_barcode_id').val('');
            }
            $('#view_file_number').val(serverData.file_number);
            $('#view_file_subject').val(serverData.file_subject);
            $('#view_file_department').val(serverData.department);
            $('#view_file_subject_hindi').val(serverData.file_subject_hindi);
            $('#view_file_department_hindi').val(serverData.file_department_hindi);
            $('#view_file_volume').val(serverData.volume);
            $('#view_file_part_case').val(serverData.file_part_number);
            $('#view_file_division').val(serverData.division);
            $('#view_file_sub_division').val(serverData.subdivision);
            $('#view_file_date_opened').val(serverData.file_date_opened);
            $('#view_file_date_closed').val(serverData.file_date_closed);
            $('#view_file_unit').val(serverData.file_unit);
            $('#view_file_contact_email').val(serverData.file_contact_email);
            $('#view_file_contact_mobile').val(serverData.file_contact_mobile);
            $('#view_file_contact_landline').val(serverData.file_contact_landline);
            $('#view_file_category').val(serverData.file_category);
            $('#view_file_tags').val(serverData.file_tags);
            $('#view_office_purpose').val(serverData.office_purpose);

            //MODEL POP-UP
            $("#file_view_modal").modal('show');
        }
    });
}

function setGetParameter(params) {
    var url = window.location.href;
    var hash = location.hash;
    url = url.replace(hash, '');
    url = url.replace(/per_page=\d*/, 'per_page=');

    $.each(params, function (paramName, paramValue) {
        if (url.indexOf(paramName + "=") >= 0)
        {
            var prefix = url.substring(0, url.indexOf(paramName));
            var suffix = url.substring(url.indexOf(paramName));
            suffix = suffix.substring(suffix.indexOf("=") + 1);
            suffix = (suffix.indexOf("&") >= 0) ? suffix.substring(suffix.indexOf("&")) : "";
            url = prefix + paramName + "=" + paramValue + suffix;
        }
        else
        {
            if (url.indexOf("?") < 0)
                url += "?" + paramName + "=" + paramValue;
            else
                url += "&" + paramName + "=" + paramValue;
        }
    });

    window.location.href = url + hash;
}

function fileStatusTwoHide() {
    if ($('#f_status').val() === "archive") {
        $('#f_status_two').addClass('initial_hide');
    } else {
        $('#f_status_two').removeClass('initial_hide');
    }
}

function checkAllPrintOption() {

    $('#file_print_option_modal').on('shown.bs.modal', function () {
        $("#file_print_option_modal .btn-primary").prop('disabled', true);
    });

    $('#label-all').on('click', function () {
        $('.print_label_options input:checkbox').prop('checked', $(this).prop('checked'));
    });

    $('.print_label_options input:checkbox').on('click', function () {
        var notCheckedCount = $('.print_label_options input:checkbox:not(:checked)').length;
        if (notCheckedCount > 0) {
            $('#label-all').prop('checked', false);
        } else if (notCheckedCount === 0) {
            $('#label-all').prop('checked', true);
        }
    });

    $('#label-all, .print_label_options input:checkbox').on('click', function () {
        if ($('.print_label_options input:checkbox:checked').length > 0) {
            $("#file_print_option_modal .btn-primary").prop('disabled', false);
        } else {
            $("#file_print_option_modal .btn-primary").prop('disabled', true);
        }
    });
}

$(function () {
    $("#file_print_option_modal .btn-primary").on('click', function () {
        var lbxFilesArray = new Array();
        var lbxFilesElement = $('.print_label_options input:checkbox:checked');

        $(lbxFilesElement).each(function () {
            lbxFilesArray.push($(this).data('lbx'));
        });

        printCoverPageLabel($(this).data('file_id'), lbxFilesArray);
    });

    fileStatusTwoHide();

    $('#f_status').on('change', function () {
        fileStatusTwoHide();
        setGetParameter({fstatus: $(this).val(), f_status_two: $('#f_status_two option:first').val()});
    });

    $('#f_status_two').on('change', function () {
        setGetParameter({fstatus: $('#f_status').val(), f_status_two: $(this).val()});
    });

    checkAllPrintOption();
});

/* Add More Contact Email */
$(function() {
   //var id = ($("#button_pro .additional_contact_email").length)+2,txt_box;
   //var url = "<?php echo base_url(); ?>";
    $('#button_pro').on('click','.add',function(){
        var id = Math.floor((Math.random() * 100000) + 1), txt_box;
        $(this).remove();
        txt_box='<div id="addMoreEmail_'+id+'"><input class="form-control contact_email additional_contact_email" id="file_additional_contact_email_'+id+'" name="file_additional_contact_email[]" placeholder="Contact Email" type="text"><img src="' + base_url + 'img/remove.png" class="remove"/><img class="add right" src="' + base_url + 'img/add.png" /></div>';
        $("#button_pro").append(txt_box);
        id++;
    });
    $('#button_pro').on('click','.remove',function(){
        var parent = $(this).parent().prev().attr("id");

        var parent_im = $(this).parent().attr("id");
        $("#"+parent_im).slideUp('medium',function(){
            $("#"+parent_im).remove();
            if($('#button_pro .add').length<1){
                $("#"+parent).append('<img src="img/add.png" class="add right"/> ');
            }
        });
   });
   
    //var edit_id = ($("#edit_button_pro .additional_contact_email").length)+2, edit_txt_box;
    //var url = "<?php echo base_url(); ?>";
    $('#edit_button_pro').on('click','.add',function(){
        var edit_id = Math.floor((Math.random() * 100000) + 1), edit_txt_box;
        $(this).remove();
        edit_txt_box = '<div id="edit_addMoreEmail_'+edit_id+'"><input class="form-control contact_email additional_contact_email" id="edit_file_additional_contact_email_'+edit_id+'" name="edit_file_additional_contact_email[]" placeholder="Contact Email" type="text"><img src="' + base_url + 'img/remove.png" class="remove"/><img class="add right" src="' + base_url + 'img/add.png" /></div>';
        $("#edit_button_pro").append(edit_txt_box);
        edit_id++;
    });
    $('#edit_button_pro').on('click','.remove',function(){
        var parent = $(this).parent().prev().attr("id");

        var parent_im = $(this).parent().attr("id");
        $("#"+parent_im).slideUp('medium',function(){
            $("#"+parent_im).remove();
            if($('#edit_button_pro .add').length<1){
                $("#"+parent).append('<img src="img/add.png" class="add right"/> ');
            }
        });
   });
   
    //var clone_id = ($("#clone_button_pro .additional_contact_email").length)+2, clone_txt_box;
    //var url = "<?php echo base_url(); ?>";
    $('#clone_button_pro').on('click','.add',function(){
        var clone_id = Math.floor((Math.random() * 100000) + 1), clone_txt_box;
        $(this).remove();
        clone_txt_box = '<div id="clone_addMoreEmail_'+clone_id+'"><input class="form-control contact_email additional_contact_email" id="clone_file_additional_contact_email_'+clone_id+'" name="clone_file_additional_contact_email[]" placeholder="Contact Email" type="text"><img src="' + base_url + 'img/remove.png" class="remove"/><img class="add right" src="' + base_url + 'img/add.png" /></div>';
        $("#clone_button_pro").append(clone_txt_box);
        clone_id++;
    });
    $('#clone_button_pro').on('click','.remove',function(){
        var parent = $(this).parent().prev().attr("id");

        var parent_im = $(this).parent().attr("id");
        $("#"+parent_im).slideUp('medium',function(){
            $("#"+parent_im).remove();
            if($('#clone_button_pro .add').length<1){
                $("#"+parent).append('<img src="img/add.png" class="add right"/> ');
            }
        });
   });
});

function loadAdditionalContactEmail(page, id, emails, icon) {
    //console.log(emails);
    if(emails != null) {        
        emails = emails.split(',');
        emails = jQuery.grep(emails, function(n, i){
            return (n !== "" && n != null);
          });
    } else {
        emails = new Array();
    }
    //console.log(emails);
    editField = ((page === 'view')?"readonly='readonly'":"");
    parent = "#" + page + "_button_pro";
    removeId = "#" + page + "_button_pro" + " #" + page + "_addMoreEmail_1";
    addIcon = removeId + " .add";
    if($(addIcon).length < 1 && icon) {
        $(removeId).append('<img src="img/add.png" class="add right"/> ');
    }
    $(removeId).nextAll().remove();
    txt_box = "";
    c = 1;
    $.each(emails, function( index, email ) {
        var clone_id = Math.floor((Math.random() * 100000) + 1)
        txt_box += '<div id="'+page+'_addMoreEmail_'+clone_id+'"><input '+editField+' value="'+email+'" class="form-control contact_email additional_contact_email" id="'+page+'_file_additional_contact_email_'+clone_id+'" name="'+page+'_file_additional_contact_email[]" placeholder="Contact Email" type="text">';
        if(icon) {
            txt_box += '<img src="' + base_url + 'img/remove.png" class="remove"/>';
        }
        if(emails.length == c && icon) {
            txt_box += '<img class="add right" src="' + base_url + 'img/add.png" />';
        }
        txt_box += '</div>';
        c++;
    });
    //console.log(txt_box);
    if(emails.length > 0 && icon) {
        $(addIcon).remove();
    }
    $(parent).append(txt_box);
}

//Print Page Div Content
function printContent(divId){
        /*
	var restorepage = document.body.innerHTML;
	var printcontent = document.getElementById(divId).innerHTML;
	document.body.innerHTML = printcontent;
	window.print();
	document.body.innerHTML = restorepage;
        */

        var divToPrint=document.getElementById(divId);
        newWin= window.open("");
        style = "<style>.word_break { word-break: break-all; }";
        //style += "#" + divId + " tbody tr td:last-child {display:none;}";
        //style += "#" + divId + " thead tr th:last-child {display:none;}";        
        //style += "@media print {table td:first-child {display:none} table th:first-child {display:none}}";
        //style += "@media print {table td:last-child {display:none} table th:last-child {display:none}}";
        style += "</style>";
        newWin.document.write('<link id="bs-css" href="'+base_url+'css/bootstrap-cerulean.min.css" rel="stylesheet">');
        newWin.document.write('<link href="'+base_url+'css/charisma-app.css" rel="stylesheet">');
        newWin.document.write('<link href="'+base_url+'css/styles.css" rel="stylesheet">');
        newWin.document.write(divToPrint.outerHTML);
        newWin.document.write(style);
        newWin.print();
        newWin.close();
}