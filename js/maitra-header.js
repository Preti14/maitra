$(function () {
    //Reset modal form
    $('.modal').on('show.bs.modal', function (e)
    {
        $(this).find('input,textarea,select').val('').end().find('input[type=checkbox], input[type=radio]').prop('checked', '').end();
        $(this).find('label.error').remove();
    });
    $('#role_switching_modal_trigger').on('click', function () {
        $('#role_switching_modal').modal('show');
    });
    $('#role_switching_division').on('change', function () {
        $.ajax({
            url: '?c=fts&m=get_subdivision',
            type: 'POST',
            data: {
                division_id: $(this).val()
            },
            dataType: 'JSON',
            success: function (serverData) {
                var length = serverData.length;
                var innerHtml =
                        '<option class="select-option" value="">' +
                        'Select Subdivision</option>';
                for (var i = 0; i < length; i++) {
                    innerHtml +=
                            '<option class="select-option" value="' +
                            serverData[i].id + '">' +
                            serverData[i].subdivision + '</option>';
                }
                $('#role_switching_sub_division').html(innerHtml);
            }
        });
    });
    $('#role_switching_form_submit').on('click', function () {
        $('#role_switching_form').submit(function (e) {
            var divisionTxt = $.trim($('#role_switching_division option:selected').text());
            $('<input />').attr('type', 'hidden').attr('name', 'role_switching_division_txt').attr('value', divisionTxt).appendTo(this);
            if ($('#role_switching_sub_division option:selected').val() !== '') {
                var subDivisionTxt = $.trim($('#role_switching_sub_division option:selected').text());
                $('<input />').attr('type', 'hidden').attr('name', 'role_switching_sub_division_txt').attr('value', subDivisionTxt).appendTo(this);
            }
        });
        if ($('#role_switching_form').valid()) {
            $('#role_switching_modal').modal('close');
        }
    });
    $('#role_switching_form').validate({
        rules: {
            role_switching_roles: {
                required: true
            }
        }
    });
    $('#role_assigning_modal.modal').on('show.bs.modal', function (e) {
        $(this).find('#division_part, #subdivision_part').hide();
        $('#role_assigning_modal #subdivision_id').html('');
    });
    $('#role_assigning_form').validate({
        rules: {
            user_type: {
                required: true
            },
            division_id: {
                required: true
            },
            subdivision_id: {
                required: true
            }
        }
    });
    $('#role_assigning_form_submit').on('click', function () {
        var roleObj = new Object();
        var isPassed = $('#role_assigning_form #user_type').valid();
        var userSelectedRole;
        if (isPassed) {
            roleObj.user_type = new Object();
            roleObj.user_type.role_text = $.trim($('#role_assigning_form #user_type option:selected').text());
            roleObj.user_type.role_id = $('#role_assigning_form #user_type').val();
            userSelectedRole = roleObj.user_type.role_id;
        }
        if (userSelectedRole == ROLE_DIVISION || userSelectedRole == ROLE_SUB_DIVISION) {
            isPassed = $('#role_assigning_form #division_id').valid();
            if (isPassed) {
                //roleObj.division = $('#role_assigning_form #division_id').val();
                roleObj.division = new Array();
                var divisionSelectedObj = $('#role_assigning_form #division_id option:selected');
                divisionSelectedObj.each(function () {
                    var divTempObj = new Object();
                    divTempObj.division_id = $(this).val();
                    divTempObj.division_text = $.trim($(this).text());
                    roleObj.division.push(divTempObj);
                })
            }
        }
        if (userSelectedRole == ROLE_SUB_DIVISION &&
                $('#role_assigning_form #subdivision_id option').length > 0) {
            isPassed = $('#role_assigning_form #subdivision_id').valid();
            if (isPassed) {
                roleObj.sub_division = new Array();
                var subDivSelectedObj = $('#role_assigning_form #subdivision_id option:selected');
                subDivSelectedObj.each(function () {
                    var subDivTempObj = new Object();
                    subDivTempObj.division_id = $(this).data('div_id');
                    subDivTempObj.sub_division_id = $(this).val();
                    subDivTempObj.sub_division_text = $.trim($(this).text());
                    roleObj.sub_division.push(subDivTempObj);
                });
            }
        }
        if (isPassed) {
            generateRoleTableHtml(roleObj);
            $('#role_assigning_modal').modal('hide');
        }
    });
    function generateRoleTableHtml(roleObj) {
        var formattedRoleArray = generateRoleTableArray(roleObj);
        var trHtml = '';
        for (var i = 0; i < formattedRoleArray.length; i++) {
            trHtml += '<tr data-id="">' +
                    '<td data-role_id="' + formattedRoleArray[i].role_id + '">' +
                    formattedRoleArray[i].role_text + '</td>';
            trHtml += '<td data-division_id="' + formattedRoleArray[i].division_id +
                    '" data-sub_division_id="' + formattedRoleArray[i].sub_division_id +
                    '">' +
                    formattedRoleArray[i].division_text;
            if (formattedRoleArray[i].sub_division_id !== '') {
                trHtml += ' / '
                        + formattedRoleArray[i].sub_division_text;
            }
            trHtml += '</td>';
            trHtml += '<td class="text-center"><input type="radio" name="default"></td>' +
                    '<td class="text-center"><a href="#" class="delete"><i class="glyphicon glyphicon-remove-circle" data-original-title="Delete" data-toggle="tooltip"></i></a></td>' +
                    '</tr>';
        }
        if ($('#role_table tr').hasClass('no_records')) {
            $('#role_table tbody').html(trHtml);
        } else {
            $('#role_table tbody').append(trHtml);
        }
    }
    function generateRoleTableArray(roleObj) {
        var roleTableArray;
        if (roleObj.user_type != undefined &&
                roleObj.division == undefined &&
                roleObj.sub_division == undefined) {
            roleTableArray = new Array();
            var roleDetailTemp = {
                role_id: roleObj.user_type.role_id,
                role_text: roleObj.user_type.role_text,
                division_id: '',
                division_text: '--',
                sub_division_id: '',
                sub_division_text: '--'
            };
            roleTableArray.push(roleDetailTemp);
        } else if (roleObj.user_type != undefined &&
                roleObj.division != undefined &&
                roleObj.sub_division == undefined) {
            roleTableArray = new Array();
            for (var i = 0; i < roleObj.division.length; i++) {
                var roleDetailTemp = {
                    role_id: roleObj.user_type.role_id,
                    role_text: roleObj.user_type.role_text,
                    division_id: roleObj.division[i].division_id,
                    division_text: roleObj.division[i].division_text,
                    sub_division_id: '',
                    sub_division_text: '--'
                };
                roleTableArray.push(roleDetailTemp);
            }
        } else if (roleObj.user_type != undefined &&
                roleObj.division != undefined &&
                roleObj.sub_division != undefined) {
            roleTableArray = new Array();
            for (var i = 0; i < roleObj.sub_division.length; i++) {
                var roleDetailTemp = {
                    role_id: roleObj.user_type.role_id,
                    role_text: roleObj.user_type.role_text,
                    division_id: roleObj.sub_division[i].division_id,
                    division_text: '',
                    sub_division_id: roleObj.sub_division[i].sub_division_id,
                    sub_division_text: roleObj.sub_division[i].sub_division_text
                };
                for (var j = 0; j < roleObj.division.length; j++) {
                    if (roleObj.division[j].division_id == roleObj.sub_division[i].division_id) {
                        roleDetailTemp.division_text = roleObj.division[j].division_text;
                        break;
                    }
                }
                roleTableArray.push(roleDetailTemp);
            }
        }
        return roleTableArray;
    }
    $('#role_assigning_form #user_type').on('change', function () {
        var roleId = $(this).val();
        if (roleId == ROLE_SUB_DIVISION) {
            $.ajax({
                url: '?c=admin&m=ajax_division_having_sub_division',
                dataType: 'JSON',
                success: function (data, textStatus, jqXHR) {
                    var divisionHtml = '';
                    $.each(data, function (key, value) {
                        divisionHtml += '<option class="select-option"' +
                                ' value="' + value.div_id + '">' + value.div + '</option>';
                    });
                    $('#role_assigning_form #division_id').html(divisionHtml);
                }
            });
        } else if (roleId == ROLE_DIVISION) {
            $.ajax({
                url: '?c=admin&m=ajax_all_active_division',
                dataType: 'JSON',
                success: function (data, textStatus, jqXHR) {
                    var allDivisionHtml = '';
                    $.each(data, function (key, value) {
                        allDivisionHtml += '<option class="select-option"' +
                                ' value="' + value.id + '">' + value.division + '</option>';
                    });
                    $('#role_assigning_form #division_id').html(allDivisionHtml);
                }
            });
        }
    });
    function deleteRole(roleId) {
    }
    $('#role_table').on('click', '.delete', function (e) {
        e.preventDefault();
        var roleTrDOM = $(this).parents('tr');
        var roleId = roleTrDOM.data('id');
        if (roleId !== '') {
            deleteRole(roleId);
        } else {
            roleTrDOM.remove();
            if ($('#role_table tr').length === 1) {
                var noRecordsTrHTML = '<tr class="no_records">' +
                        '<td colspan="5" style="text-align:center">' +
                        'No Roles are found</td></tr>';
                $('#role_table tbody').html(noRecordsTrHTML);
            }
        }
    });
    $('#user_form #user_submit').on('click', function () {

        if ($('#role_table tr').hasClass('no_records')
                && $('#user_form').valid()) {
            $('#user_form').submit(function (e) {
                //e.preventDefault();
            });
            $('#role_assigning_modal').modal('show');
            $('#role_assigning_form #user_type').valid();
        }
        var roleData = new Array();
        $('#role_table tbody tr').each(function (index, value) {
            var roleElements = new Object();
            roleElements.id = $(this).data('id');
            roleElements.role_id = $(this).find('[data-role_id]').data('role_id');
            roleElements.division_id = $(this).find('[data-division_id]').data('division_id');
            roleElements.sub_division_id = $(this).find('[data-sub_division_id]').data('sub_division_id');

            var defaultDOM = $(this).find('[name="default"]');

            roleElements.is_default = false;
            if (defaultDOM.is(':checked')) {
                roleElements.is_default = true;
            }

            roleData.push(roleElements);
        });


        roleData = JSON.stringify(roleData);

        $('<input />').attr('type', 'hidden').attr('name', 'role_data').attr('value', roleData).appendTo('#user_form');
    });

    //AD get employee profile details
    $('#ad_employee_search_button').on('click', function () {
        
        if ($('#ad_employee_search_form').valid()) {
            $.ajax({
                url: '?c=admin&m=ajax_ad_employee_search',
                dataType: 'JSON',
                type: 'POST',
                data: {
                    employee_id: $('#ad_employee_search').val()
                },
                success: function (data, textStatus, jqXHR) {

                    if (data.count == 1) {
                        var userDetails = data[0];
                        
                        $('#user_form #firstname')
                                .val(userDetails['givenname'][0])
                                .prop('readonly', true);
                        
                        $('#user_form #lastname')
                                .val(userDetails['sn'][0])
                                .prop('readonly', true);
                        
                        $('#user_form #email')
                                .val(userDetails['mail'][0])
                                .prop('readonly', true);
                        
                        $('#user_form #mobile_no')
                                .val(userDetails['telephonenumber'][0])
                                .prop('readonly', true);
                        
                        $('#user_form #username')
                                .val(userDetails['cn'][0])
                                .prop('readonly', true);
                        
                        $('#user_form #input_password, #user_form #confirm_password')
                                .val(userDetails['userpassword'][0])
                                .prop('readonly', true);
                        
                    }
                    
                }
                
            });
        }
    });

    $('#ad_employee_search_form').validate({
        rules: {
            ad_employee_search: {
                required: true
            }
        }
    });
    
    $('#user_form #user_cancel').on('click', function() {
       $('#user_form input').prop('disabled', false);
    });
});
