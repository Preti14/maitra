<script src="<?= base_url(); ?>js/jquery.validate.min-1.11.1.js"></script>
<div class="box col-md-5">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i> 
                Create New File</h2>
            <div class="box-icon"> <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a> </div>
        </div>
        <div class="box-content">
            <form id="add_file_data" role="form" method="post" action="?c=fts&m=add_file_data">
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_name" name="file_name" placeholder="File Name" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_no" name="file_no" placeholder="File No." type="text">
                </div>
                <!--                <div class="form-group col-md-6"> 
                                    <input class="form-control" id="file_part_no" name="file_part_no" placeholder="File Part No." type="text">
                                </div>-->
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_subject" name="file_subject" placeholder="File Subject" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="department" name="department" placeholder="Department" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <select class="form-control" id="volume" name="volume">
                        <option class="select-option" value="">Choose Volume </option>
                        <?php
                        foreach (array(1 => 'I', 'II', 'III', 'IV', 'V', 'VI', 'VII',
                    'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV') as $romanLetter):
                            ?>
                            <option class="select-option" value="<?php echo $romanLetter ?>"><?php echo $romanLetter ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6"> 
                    <select class="form-control" id="part_case" name="part_case">
                        <option class="select-option" value="">Choose Part Case </option>
                        <?php
                        foreach (range(1, 15) as $unit):
                            ?>
                            <option class="select-option" value="<?php echo $unit ?>"><?php echo $unit ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6"> 
                    <select class="form-control" id="division" name="division">
                        <option class="select-option" value="">Choose Division </option>
                        <?php foreach ($divisions as $division): ?>
                            <option class="select-option" value="<?php echo $division['id'] ?>"><?php echo $division['division'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6"> 
                    <select class="form-control" id="sub_division" name="sub_division">
                        <option class="select-option" value="">Choose Sub-Division </option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <input class="form-control" id="file_date_opened" name="file_date_opened" placeholder="File Date Opened" type="text" style="background: url(http://localhost/maitra_nov_jai/img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_date_closed" name="file_date_closed" placeholder="File Date Closed" type="text" style="background: url(http://localhost/maitra_nov_jai/img/calendar.gif) no-repeat scroll 10px 10px; cursor:pointer; background-position:right 10px top 10px;background-color:#ffffff !important; opacity:1 !important;">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_unit" name="file_unit" placeholder="File Unit" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_classification" name="file_classification" placeholder="File Classification" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_submitted_by" name="file_submitted_by" placeholder="File Submitted By" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_contact_email" name="file_contact_email" placeholder="Contact Email" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_contact_mobile" name="file_contact_mobile" placeholder="Contact Mobile" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_contact_landline" name="file_contact_landline" placeholder="Contact Landline" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_retention_in_months" 
                           name="file_retention_in_months" 
                           placeholder="Retention In Months" type="number" min="0">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_reminder" 
                           name="file_reminder" placeholder="File Reminder" 
                           type="number" min="0">
                </div>
                <div class="form-group col-md-6"> 
                    <input class="form-control" id="file_category" name="file_category" placeholder="File Category" type="text">
                </div>
                <div class="form-group col-md-6"> 
                    <textarea class="form-control" id="file_tags" name="file_tags" placeholder="File Tags" ></textarea>
                </div>
                <!--                <div class="form-group col-md-6"> 
                                    <input class="form-control" id="file_owner" readonly="readonly"
                                           value ="<?php // echo $userDetails['username']                         ?>"
                                           name="file_owner" placeholder="File Owner" type="text">
                                </div>-->
                <button name="entry_submit" type="submit" class="btn btn-default" id="entry_submit">Save</button>
                <button name="entry_submit_barcode" type="submit" class="btn btn-default" id="entry_submit_barcode" data-toggle="modal">Save and Generate Bar Code</button>
            </form>
        </div>
    </div>
</div>

<div class="box col-md-7">
    <div class="box-inner">
        <div class="box-header well" data-original-title="">
            <h2><i class=""></i> File List</h2>

            <div class="box-icon">
                <a href="#" class="btn btn-minimize btn-round btn-default"><i class="glyphicon glyphicon-chevron-up"></i></a>
            </div>
        </div>

        <div class="box-content">
            <table id="staging_list" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                <thead>
                    <tr>
                        <!--<th><input value="" id="checkall" class="checkall" type="checkbox"></th>-->
                        <th>File No.</th>
                        <th>File Name</th>	
                        <th>Subject</th>
                        <th>Created On</th>
                        <th>Track</th>
                        <th>Print</th>        

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($fileRecordSet as $record): ?>
                        <tr>
                            <!--<td><input id="<?= $record->barcode_id ?>" class="checkbox_option" name="checkbox" value="<?= $record->barcode_id ?>" type="checkbox"></td>-->
                            <td><?= $record->file_number ?></td>
                            <td><?= $record->file_name ?></td>
                            <td><?= $record->file_subject ?></td>
                            <td><?=
                                date('jS,M y',
                                        strtotime($record->file_created_date))
                                ?></td>
                            <td class="file_track"><i class="glyphicon glyphicon-map-marker"></i></td>
                            <td><button class="btn btn-default print_barcode">Print</button></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <div class="col-md-12">
                <?= $this->pagination->create_links(); ?>
            </div>
            <div class="clearfix"></div>
        </div>

    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="barcode_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">Barcode</h3>
            </div>
            <div class="modal-body">
                <img> <!-- Don't remove it src loaded through js -->
                <button name="print_barcode" class="btn btn-default" id="print_barcode">Print</button>
            </div>   
        </div>
    </div>
</div>

<div class="modal fade bs-example-modal-lg" id="file_track_modal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title text-center" id="myModalLabel">File Tracking</h3>
            </div>
            <div class="modal-body">
                <table id="file_track_table" class="table table-striped table-bordered bootstrap-datatable datatable responsive">
                    <thead>
                        <tr>
                            <th>Parrent Location</th>	
                            <th>Current Location</th>    
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>   
        </div>
    </div>
</div>

<script>
    var base_url = '<?php echo base_url(); ?>';
    var inputFileReminder = document.getElementById('file_reminder');
    var inputFileRetention = document.getElementById('file_retention_in_months');

    inputFileReminder.addEventListener('input', function (e) {
        if (inputFileRetention.value.length === 0) {
            inputFileReminder.setAttribute("max", 0);
        } else {
            inputFileReminder.setAttribute("max", inputFileRetention.value);
        }
    });

    inputFileRetention.addEventListener('input', function (e) {
        if (inputFileRetention.value < inputFileReminder.value) {
            inputFileReminder.value = inputFileRetention.value;
        }

        inputFileReminder.setAttribute("max", inputFileRetention.value);
    });

    var submitSave = document.getElementById('entry_submit_barcode');

    submitSave.addEventListener('click', function (e) {
        //location.reload();
    });

    $(function () {
        //DATE PICKER
        $("#file_date_opened, #file_date_closed").datepicker({dateFormat: 'dd-mm-yy'});

        //FORM
        $("#add_file_data").validate({
            rules: {
                file_name: {
                    required: true
                },
                file_no: {
                    required: true,
                    remote: {
                        url: "?c=fts&m=check_file_number_exists",
                        type: "post"
                    }
                },
                file_subject: {
                    required: true
                },
                file_contact_email: {
                    required: true,
                    email: true
                },
                department: {
                    required: true
                },
                volume: {
                    required: true
                }
            },
            messages: {
                file_name: {
                    required: "Please enter file name"
                },
                file_no: {
                    required: "Please enter file number",
                    remote: "File number already in use"
                },
                file_subject: {
                    required: "Please enter file subject"
                },
                file_contact_email: {
                    required: "Please enter the email",
                    email: "Please enter valid email"
                },
                department: {
                    required: "Please enter the department name"
                },
                volume: {
                    required: "Please select a volume"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                $.ajax({
                    url: $('#add_file_data').attr('action'),
                    type: 'post',
                    data: $('#add_file_data').serialize(),
                    success: function (serverData) {

                        var data = JSON.parse(serverData);

                        if (data.hasOwnProperty('base_url')) {
                            window.location.href = data.base_url;
                        }

                        if (data.submit_button_name === 'entry_submit') {
                            document.getElementById('add_file_data').reset();
                            location.reload();
                        } else if (data.submit_button_name === 'entry_submit_barcode') {
                            var imgURL = base_url + "bar_codes/" + data.barcode + ".png";
                            $('#barcode_modal .modal-body img').prop('src', imgURL);
                            $("#barcode_modal").modal('show');
                        }
                    }
                });

            }
        });

        //BARCODE PRINT
        var popup;

        function closePrint() {
            if (popup) {
                popup.close();
            }
        }

        $('#print_barcode').on('click', function () {
            popup = window.open($('#barcode_modal .modal-body img').prop('src'));
            popup.onbeforeunload = closePrint;
            popup.onafterprint = closePrint;
            popup.focus(); // Required for IE
            popup.print();
            document.getElementById('add_file_data').reset();
            location.reload();
        });

        //BARCODE MODAL CLOSE RELOAD PAGE
        $('#barcode_modal').on('hidden.bs.modal', function () {
            document.getElementById('add_file_data').reset();
            location.reload();
        });

        //SUB-DIVISION
        $(document).on('change', '#division', function () {
            $.ajax({
                url: "?c=fts&m=get_subdivision",
                type: 'POST',
                data: {division_id: $(this).val()},
                dataType: 'JSON',
                success: function (serverData) {
                    var length = serverData.length;
                    var innerHtml =
                            '<option class="select-option" value="">' +
                            'Choose Sub-Division </option>';

                    for (var i = 0; i < length; i++) {
                        innerHtml +=
                                '<option class="select-option" value="' +
                                serverData[i].id + '">' +
                                serverData[i].subdivision + '</option>';
                    }

                    $('#sub_division').html(innerHtml);
                }
            });
        });

        //FILE TRACK
        $('.file_track').on('click', function () {

            var file_number = $(this).siblings(':first').text().trim();

            $.ajax({
                url: "?c=fts&m=file_tracking",
                type: 'POST',
                data: {file_number: file_number},
                dataType: 'JSON',
                success: function (serverData) {
                    //MODEL POP-UP
                    $("#file_track_modal").modal('show');
                    
                    $('#file_track_table td:first').html(serverData.parrent_location);
                    $('#file_track_table td:last').html(serverData.current_location);
                }
            });
        });

    });
</script>