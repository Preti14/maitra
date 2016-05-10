$(function () {

    $("#save_template_txt").on('keyup click', function () {
        var save_template_txt = $.trim($(this).val());

        if (save_template_txt !== '') {
            $('#save_template_btn, #delete_template_btn').prop('disabled', false);
        } else {
            $('#save_template_btn, #delete_template_btn').prop('disabled', true);
        }
    });

    $('#template_id').on('change', function () {
        var templateId = $(this).val();
        if (templateId !== '') {
            $('#save_template_txt').hide();
            $('#save_template_btn, #delete_template_btn').prop('disabled', false);

            $('#adv_search_form div.search_by_form_group:not(:first)').remove();

            $.ajax({
                url: "?c=fts&m=ajax_load_template_options",
                type: "POST",
                data: {id: templateId},
                dataType: 'JSON',
                success: function (data) {
                    //console.log(data);

                    $('#division').val(data.division).change();

                    $(document).ajaxComplete(function () {
                        $('#subdivision').val(data.subdivision);
                    });

                    $('#from_datepicker').val(data.from_datepicker);
                    $('#to_datepicker').val(data.to_datepicker);
					$('#with_comm').val(data.with_comm);
					$('#m_status').val(data.m_status);
                    $('#deleted_file').prop('checked', data.deleted_file);

                    var parentElement = $(".search_by_form_group");
                    
                    count = 1;
                    $('#adv_search_form div.search_by_form_group:first .search_by_select').val('');
                    $('#adv_search_form div.search_by_form_group:first #search_by_txt').val('');
                    $.each(data.search_by, function(index, value) {
                        //console.log(index, '-->', value[0]);
                        if(count==1) {
                            $('#adv_search_form div.search_by_form_group:first .search_by_select').val(index);
                            $('#adv_search_form div.search_by_form_group:first #search_by_txt').val(value[0]);
                        }else {
                            htmlString = searchByBox(index, value[0]);

                            parentElement.after(htmlString);
                        }
                        count++;
                    });
                    if(count>1) {
                        htmlString = searchByBox('', '');

                        $('#adv_search_form div.search_by_form_group:last').after(htmlString);
                    }
                }
            });
        } else {
            $('#save_template_txt').show();
            $('#save_template_btn, #delete_template_btn').prop('disabled', true);
        }
    });
    
    $('#myModal_search').on('hidden.bs.modal', function () {
        $('#adv_search_form div.search_by_form_group:not(:first)').remove();
        document.getElementById('adv_search_form').reset();
        //location.reload();
    });

    $('#adv_search_form').on('change', '.search_by_select', function () {
        var selectedOptionValue = $(this).val();
        var parentElement = $(this).closest('.form-group');

        if (selectedOptionValue !== '' && !parentElement.next().hasClass('search_by_form_group')) {
            htmlString = searchByBox('', '');

            parentElement.after(htmlString);

        }
    });
    
    function searchByBox(search_by, search_by_txt) {
        var htmlString = "";
        var searchBy = new Array();
        searchBy["file_number"] = "File Number";
        searchBy["file_subject"] = "File Subject";
        searchBy["department"] = "Department";
        searchBy["volume"] = "Volume";
        searchBy["file_contact_email"] = "Contact Email";
        searchBy["barcode_id"] = "Barcode";
     
        htmlString += "<div class=\"form-group search_by_form_group\">";
        htmlString += "    <label class=\"col-sm-2\"><\/label>";
        htmlString += "    <div class=\"col-sm-4\">";
        htmlString += "        <select name=\"search_by[]\" id=\"search_by\" class=\"form-control search_by_select\">";
        htmlString += "            <option value=\"\">Select<\/option>";
        //htmlString += "            <option value=\"file_number\">File Number<\/option>";
        //htmlString += "            <option value=\"file_subject\">File Subject<\/option>";
        //htmlString += "            <option value=\"department\">Department<\/option>";
        //htmlString += "            <option value=\"volume\">Volume<\/option>";
        //htmlString += "            <option value=\"file_contact_email\">Contact Email<\/option>";
        //htmlString += "            <option value=\"barcode_id\">Barcode</option>";
        for(var key in searchBy) {
            if(search_by == key) {
                htmlString += "            <option value='"+ key + "' selected>"+ searchBy[key] + "</option>";
            } else {
                htmlString += "            <option value='"+ key + "'>"+ searchBy[key] + "</option>";
            }
        }
        htmlString += "        <\/select>";
        htmlString += "    <\/div>";
        htmlString += "    <div class=\"col-sm-4\">";
        htmlString += "        <input value = '" + search_by_txt + "' type=\"text\" id=\"search_by_txt\" name=\"search_by_txt[]\" class=\"form-control\"\/>";
        htmlString += "    <\/div>";
        htmlString += "    <div class=\"col-sm-1\">";
        htmlString += "        <img src=\"" + base_url + "\/img\/remove.png\" class=\"remove\">";
        htmlString += "    <\/div>";
        htmlString += "<\/div>";
        
        return htmlString;
    }

    $('#adv_search_form').on('click', '.search_by_form_group .remove', function () {
        $(this).closest('.search_by_form_group').remove();
    });

    $('#adv_search_form').on('click', '#delete_template_btn', function (e) {
        e.preventDefault();

        if ($('#save_template_txt').val()) {
            $('#save_template_txt').val('');
        } else {
            //MODEL POP-UP
            $("#template_delete_confirmation_modal").modal('show');
        }
    });

    $("#template_delete_confirmation_modal .btn-primary").on('click', function () {
        var templateId = $('#template_id').val(); //Note: place this before form reset.
        $('#save_template_btn, #delete_template_btn').prop('disabled', true);
        $('#save_template_txt').show();
        $('#adv_search_form')[0].reset();
        $('.search_by_form_group').slice(1).remove();

        $.ajax({
            url: "?c=fts&m=ajax_delete_template",
            type: "POST",
            data: {id: templateId},
            dataType: 'JSON',
            success: function (data) {
                $('#template_id option[value=' + templateId + ']').remove();
            }
        });
    });
});