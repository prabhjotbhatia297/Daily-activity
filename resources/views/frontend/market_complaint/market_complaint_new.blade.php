@extends('frontend.layout.main')
@section('container')
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
       

    </style>

@php
        $users = DB::table('users')->get();
    @endphp
    <div class="form-field-head">
        {{-- <div class="pr-id">
            New Child
        </div> --}}
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName(session()->get('division')) }}/ Complaint Management
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#Monitor_Information').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="date" name="date[]"></td>' +
                        ' <td><input type="text" name="Responsible[]"></td>' +
                        '<td><input type="text" name="ItemDescription[]"></td>' +
                        '<td><input type="date" name="SentDate[]"></td>' +
                        '<td><input type="date" name="ReturnDate[]"></td>' +
                        '<td><input type="text" name="Comment[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' +

                    //     '</tr>';

                    return html;
                }

                var tableBody = $('#Monitor_Information_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#Product_Material').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="ProductName[]"></td>' +
                        '<td><input type="number" name="ReBatchNumber[]"></td>' +
                        '<td><input type="date" name="ExpiryDate[]"></td>' +
                        '<td><input type="date" name="ManufacturedDate[]"></td>' +
                        '<td><input type="text" name="Disposition[]"></td>' +
                        '<td><input type="text" name="Comment[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' +

                    //     '</tr>';

                    return html;
                }

                var tableBody = $('#Product_Material_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#Equipment').click(function(e) {
                function generateTableRow(serialNumber) {


                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber+
                        '"></td>' +
                        '<td><input type="text" name="ProductName[]"></td>' +
                        '<td><input type="number" name="BatchNumber[]"></td>' +
                        '<td><input type="date" name="ExpiryDate[]"></td>' +
                        '<td><input type="date" name="ManufacturedDate[]"></td>' +
                        '<td><input type="number" name="NumberOfItemsNeeded[]"></td>' +
                        '<td><input type="text" name="Exist[]"></td>' +
                        '<td><input type="text" name="Comment[]"></td>' +


                        '</tr>';

                    // for (var i = 0; i < users.length; i++) {
                    //     html += '<option value="' + users[i].id + '">' + users[i].name + '</option>';
                    // }

                    // html += '</select></td>' +

                    //     '</tr>';

                    return html;
                }

                var tableBody = $('#Equipment_details tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <script>
        function handleDateInput(input, targetId) {
            const target = document.getElementById(targetId);
            const date = new Date(input.value);
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            const formattedDate = date.toLocaleDateString('en-US', options).replace(/ /g, '-');
            target.value = formattedDate;
        }
    </script>












    {{-- ! ========================================= --}}
    {{-- !               DATA FIELDS                 --}}
    {{-- ! ========================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">

            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">HOD/Supervisor Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Complaint Acknowledgement</button>

                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Closure</button>

                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">Signature</button>

            </div>

            

            <script type="text/javascript">
                    function googleTranslateElementInit() {
                        new google.translate.TranslateElement({
                            pageLanguage: 'en',
                            includedLanguages: 'en,es,fr,de,zh,hi,ar,pt,ja,ru',
                            layout: google.translate.TranslateElement.InlineLayout.SIMPLE
                        }, 'google_translate_element');
                    }
                </script>                                            
                <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
                <script>
                    $(document).ready(function() {
                        setTimeout(() => {
                            $('body').css('top', '0');
                        }, 5000);
                    })
                </script>

            <form action="{{ route('marketcomplaint.mcstore') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="step-form">
                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                    @endif
                    <!-- Tab content -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                             <!-- RECORD NUMBER -->
                            <div class="row">
                                <div class="sub-head">
                                    General Information
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number"><b>Record Number</b></label>
                                        {{-- <input disabled type="text" name="record" value=""> --}}
                                        {{-- <input disabled type="text" name="record" value=" {{ Helpers::getDivisionName(session()->get('division')) }}/LI/{{ date('Y') }}/{{ $record}}"> --}}
                                        <input disabled type="text" name="record" id="record" 
                                        value="---/MC/{{ date('y') }}/{{ $record }}">
                                    </div>
                                </div>
                               

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="priority_data Department">Priority</label>
                                        <select name="priority_data" id="priority_data">
                                            <option value="">--Select--</option>
                                            <option value="High" >High</option>
                                            <option value="Medium" >Medium</option>
                                            <option value="Low" >Low</option>
                                        </select>
                                    </div>
                                </div>

                                

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label disabled for="Short Description">Division Code<span class="text-danger"></span></label>
                                        <input disabled type="text" name="division_code"
                                                value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                            <input type="hidden" name="division_id" value="{{ session()->get('division') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="originator">Initiator</label>
                                        <input disabled type="text" name="initiator" value="{{ Auth::user()->name }}" />
                                    </div>
                                </div>

                                <div class="col-md-6 ">
                                    <div class="group-input ">
                                        <label for="due-date"> Date Of Initiation<span class="text-danger"></span></label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date">
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date">
                                    </div>
                                </div>
                                {{-- <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date">Due Date <span class="text-danger"></span></label>
                                        <p class="text-primary"> last date this record should be closed by</p>

                                        <div class="calenderauditee">
                                            <input type="text" id="due_date" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" name="due_date_gi"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                class="hide-input" oninput="handleDateInput(this, 'due_date')" />
                                        </div>
                                    </div>
                                </div> --}}

                                <div class="col-md-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="due-date">Due Date <span class="text-danger">*</span></label>
                                        <div class="calenderauditee">
                                            <!-- Display the formatted date in a readonly input -->
                                            <input type="text" id="due_date_display" readonly placeholder="DD-MMM-YYYY" value="{{ Helpers::getDueDate(30, true) }}" />
                                           
                                            <input type="date" name="due_date_gi" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="{{ Helpers::getDueDate(30, false) }}" class="hide-input" readonly />
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    function handleDateInput(dateInput, displayId) {
                                        const date = new Date(dateInput.value);
                                        const options = { day: '2-digit', month: 'short', year: 'numeric' };
                                        document.getElementById(displayId).value = date.toLocaleDateString('en-GB', options).replace(/ /g, '-');
                                    }
                                    
                                    // Call this function initially to ensure the correct format is shown on page load
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const dateInput = document.querySelector('input[name="due_date"]');
                                        handleDateInput(dateInput, 'due_date_display');
                                    });
                                    </script>
                                    
                                    <style>
                                    .hide-input {
                                        display: none;
                                    }
                                    </style>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                            class="text-danger">*</span></label>
                                            <span id="rchars">255</span>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <input  name="description_gi" id="docname" maxlength="255" required >
                                    
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group"><b>Initiator Group</b></label>
                                        <select name="initiator_group" id="initiator_group">
                                            <option value="">-- Select --</option>
                                            <option value="CQA" @if (old('initiator_Group') == 'CQA') selected @endif>
                                                Corporate Quality Assurance</option>
                                            <option value="QAB" @if (old('initiator_Group') == 'QAB') selected @endif>
                                                Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC" @if (old('initiator_Group') == 'CQA') selected @endif>
                                                Central
                                                Quality Control</option>
                                            <option value="MANU" @if (old('initiator_Group') == 'MANU') selected @endif>
                                                Manufacturing</option>
                                            <option value="PSG" @if (old('initiator_Group') == 'PSG') selected @endif>Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if (old('initiator_Group') == 'CS') selected @endif>
                                                Central
                                                Stores</option>
                                            <option value="ITG" @if (old('initiator_Group') == 'ITG') selected @endif>
                                                Information Technology Group</option>
                                            <option value="MM" @if (old('initiator_Group') == 'MM') selected @endif>
                                                Molecular Medicine</option>
                                            <option value="CL" @if (old('initiator_Group') == 'CL') selected @endif>
                                                Central Laboratory</option>

                                            <option value="TT" @if (old('initiator_Group') == 'TT') selected @endif>Tech
                                                team</option>
                                            <option value="QA" @if (old('initiator_Group') == 'QA') selected @endif>
                                                Quality Assurance</option>
                                            <option value="QM" @if (old('initiator_Group') == 'QM') selected @endif>
                                                Quality Management</option>
                                            <option value="IA" @if (old('initiator_Group') == 'IA') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if (old('initiator_Group') == 'ACC') selected @endif>
                                                Accounting</option>
                                            <option value="LOG" @if (old('initiator_Group') == 'LOG') selected @endif>
                                                Logistics</option>
                                            <option value="SM" @if (old('initiator_Group') == 'SM') selected @endif>
                                                Senior Management</option>
                                            <option value="BA" @if (old('initiator_Group') == 'BA') selected @endif>
                                                Business Administration</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Initiator Group Code</label>
                                        <input type="text" name="initiator_group_code_gi" id="initiator_group_code" value="" readonly>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group">Initiated Through</label>
                                        <div><small class="text-primary">Please select related information</small></div>
                                        <select name="initiated_through_gi" onchange="">
                                            <option value="">-- select --</option>
                                            <option value="recall">Recall</option>
                                            <option value="return">Return</option>
                                            <option value="deviation">Deviation</option>
                                            <option value="complaint">Complaint</option>
                                            <option value="regulatory">Regulatory</option>
                                            <option value="lab-incident">Lab Incident</option>
                                            <option value="improvement">Improvement</option>
                                            <option value="others">Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="If Other">If Other </label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="if_other_gi" ></textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group">Is Repeat</label>
                                        <select name="is_repeat_gi" onchange="">
                                            <option value="">-- select --</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>


                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Repeat Nature">Repeat Nature</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="repeat_nature_gi"></textarea>
                                            {{-- <textarea class="summernote" name="repeat_nature_gi" id="summernote-1"> --}}

                                    </div>
                                </div>



                                


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">Information Attachment</label>
                                        <div>
                                            <small class="text-primary">
                                                Please Attach all relevant or supporting documents
                                            </small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="initial_attachment_gi">

                                              
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="initial_attachment_gi" name="initial_attachment_gi[]" oninput="addMultipleFiles(this,'initial_attachment_gi')"
                                                    multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group">Complainant</label>
                                        <select id="select-state" placeholder="Select..." name="complainant_gi">
                                            <option value="">Select a value</option>
                                            @foreach ($users as $value)
                                                <option value="{{ $value->id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('assign_to')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-lg-6 new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="complaint_reported_on">Complaint Reported On</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="complaint_dat" readonly placeholder="DD-MMM-YYYY" />
                                            <input type="date" id="complaint_date_picker" name="complaint_reported_on_gi"
                                                value=""
                                                class="hide-input" oninput="handleDateInput(this, 'complaint_dat')" />
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', (event) => {
                                        const dateInput = document.getElementById('complaint_date_picker');
                                        const today = new Date().toISOString().split('T')[0];
                                        dateInput.setAttribute('max', today);
                                
                                        // Show the date picker when clicking on the readonly input
                                        const readonlyInput = document.getElementById('complaint_dat');
                                        readonlyInput.addEventListener('click', () => {
                                            dateInput.style.display = 'block';
                                            dateInput.focus();
                                        });
                                
                                        // Update the readonly input when a date is selected
                                        dateInput.addEventListener('change', () => {
                                            const selectedDate = new Date(dateInput.value);
                                            const options = { day: '2-digit', month: 'short', year: 'numeric' };
                                            readonlyInput.value = selectedDate.toLocaleDateString('en-GB', options).replace(/ /g, '-');
                                            dateInput.style.display = 'none';
                                        });
                                    });
                                
                                    function handleDateInput(dateInput, readonlyInputId) {
                                        const readonlyInput = document.getElementById(readonlyInputId);
                                        const selectedDate = new Date(dateInput.value);
                                        const options = { day: '2-digit', month: 'short', year: 'numeric' };
                                        readonlyInput.value = selectedDate.toLocaleDateString('en-GB', options).replace(/ /g, '-');
                                    }
                                </script>
                                
                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Details Of Nature Market Complaint">Details Of Nature Market
                                            Complaint</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="details_of_nature_market_complaint_gi" id="summernote-1">
                                        </textarea>
                                        {{-- <textarea class="summernote" name="details_of_nature_market_complaint_gi" id="summernote-1"> --}}
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="root_cause">
                                            Product Details
                                            <button type="button" id="product_details">+</button>
                                            <span class="text-primary" data-bs-toggle="modal"
                                                data-bs-target="#document-details-field-instruction-modal"
                                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="product_details_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Product Name</th>
                                                        <th>Batch No.</th>
                                                        <th>Mfg. Date</th>
                                                        <th>Exp. Date</th>
                                                        <th>Batch Size</th>
                                                        <th>Pack Size</th>
                                                        <th>Dispatch Quantity</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input disabled type="text" name="serial_number_gi[0][serial]" value="1"></td>
                                                        <td><input type="text" name="serial_number_gi[0][info_product_name]"></td>
                                                        <td><input type="text" name="serial_number_gi[0][info_batch_no]"></td>
                                                        <td>
                                                            <div class="new-date-data-field">
                                                                <div class="group-input input-date">
                                                                    <div class="calenderauditee">
                                                                        <input
                                                                            class="click_date"
                                                                            id="date_0_mfg_date"
                                                                            type="text"
                                                                            name="serial_number_gi[0][info_mfg_date]"
                                                                            placeholder="DD-MMM-YYYY"
                                                                        />
                                                                        <input
                                                                            type="date"
                                                                            name="serial_number_gi[0][info_mfg_date]"
                                                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                            id="date_0_mfg_date"
                                                                            class="hide-input show_date"
                                                                            style="position: absolute; top: 0; left: 0; opacity: 0;"
                                                                            oninput="handleDateInput(this, 'date_0_mfg_date')"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="new-date-data-field">
                                                                <div class="group-input input-date">
                                                                    <div class="calenderauditee">
                                                                        <input
                                                                            class="click_date"
                                                                            id="date_0_expiry_date"
                                                                            type="text"
                                                                            name="serial_number_gi[0][info_expiry_date]"
                                                                            placeholder="DD-MMM-YYYY"
                                                                        />
                                                                        <input
                                                                            type="date"
                                                                            name="serial_number_gi[0][info_expiry_date]"
                                                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                            id="date_0_expiry_date"
                                                                            class="hide-input show_date"
                                                                            style="position: absolute; top: 0; left: 0; opacity: 0;"
                                                                            oninput="handleDateInput(this, 'date_0_expiry_date')"
                                                                        />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><input type="text" name="serial_number_gi[0][info_batch_size]"></td>
                                                        <td><input type="text" name="serial_number_gi[0][info_pack_size]"></td>
                                                        <td><input type="text" name="serial_number_gi[0][info_dispatch_quantity]"></td>
                                                        <td><input type="text" name="serial_number_gi[0][info_remarks]"></td>
                                                        <td><button type="text" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                    
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).on('click', '.removeRowBtn', function() {
                                        $(this).closest('tr').remove();
                                    })
                                </script>

                                <script>
                                    $(document).ready(function() {
                                        $('#product_details').click(function(e) {
                                            function generateTableRow(serialNumber) {
                                                var html =
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="serial_number_gi[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_product_name]"></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_batch_no]"></td>' +
                                                    '<td>   <div class="new-date-data-field"><div class="group-input input-date"><div class="calenderauditee"><input id="date_'+ serialNumber +'_mfg_date" type="text" name="serial_number_gi[' + serialNumber + '][info_mfg_date]" placeholder="DD-MMM-YYYY" /> <input type="date" name="serial_number_gi[' + serialNumber + '][info_mfg_date]" min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" id="date_'+ serialNumber +'_mfg_date" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, \'date_'+ serialNumber +'_mfg_date\')" /> </div></div></div> </td>' +
                                                    '<td>   <div class="new-date-data-field"><div class="group-input input-date"><div class="calenderauditee"><input id="date_'+ serialNumber +'_expiry_date" type="text" name="serial_number_gi[' + serialNumber + '][info_expiry_date]" placeholder="DD-MMM-YYYY" /> <input type="date" name="serial_number_gi[' + serialNumber + '][info_expiry_date]" min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" id="date_'+ serialNumber +'_expiry_date" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, \'date_'+ serialNumber +'_expiry_date\')" /> </div> </div></div></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_batch_size]"></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_pack_size]"></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_dispatch_quantity]"></td>' +
                                                    '<td><input type="text" name="serial_number_gi[' + serialNumber + '][info_remarks]"></td>' +
                                                    '<td><button type="text" class="removeRowBtn" ">Remove</button></td>' +

                                                    '</tr>';
                                                return html;
                                            }

                                            var tableBody = $('#product_details_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                    });
                                </script>


                                                                {{-- {{ ---end s code }} --}}
                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Traceability
                                        <button type="button" id="traceblity_add">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="traceblity" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;">Row #</th>
                                                    <th>Product Name</th>
                                                    <th>Batch No.</th>
                                                    <th>Manufacturing Location</th>
                                                    <th>Remarks</th>
                                                    <th>Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input disabled type="text" name="trace_ability[0][serial]" value="1"></td>
                                                    <td><input type="text" name="trace_ability[0][product_name_tr]"></td>
                                                    <td><input type="text" name="trace_ability[0][batch_no_tr]"></td>
                                                    <td><input type="text" name="trace_ability[0][manufacturing_location_tr]"></td>
                                                    <td><input type="text" name="trace_ability[0][remarks_tr]"></td>
                                                    <td><button type="text" class="removeRowBtn" >Remove</button></td>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <script>
                                $(document).on('click', '.removeRowBtn', function() {
                                    $(this).closest('tr').remove();
                                })
                            </script>


                            <script>
                                $(document).ready(function() {
                                    $('#traceblity_add').click(function(e) {
                                        e.preventDefault();

                                        function generateTableRow(serialNumber) {
                                            var html =
                                                '<tr>' +
                                                '<td><input disabled type="text" name="trace_ability[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                '<td><input type="text" name="trace_ability[' + serialNumber + '][product_name_tr]"></td>' +
                                                '<td><input type="text" name="trace_ability[' + serialNumber + '][batch_no_tr]"></td>' +
                                                '<td><input type="text" name="trace_ability[' + serialNumber + '][manufacturing_location_tr]"></td>' +
                                                '<td><input type="text" name="trace_ability[' + serialNumber + '][remarks_tr]"></td>' +
                                                '<td><button type="text" class="removeRowBtn" >Remove</button></td>' +

                                                '</tr>';
                                            return html;
                                        }

                                        var tableBody = $('#traceblity tbody');
                                        var rowCount = tableBody.children('tr').length;
                                        var newRow = generateTableRow(rowCount);
                                        tableBody.append(newRow);
                                    });
                                });
                            </script>
                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initiator Group">Categorization of Complaint</label>
                                        <select name="categorization_of_complaint_gi" onchange="">
                                            <option value="">-- select --</option>
                                            <option value="Critical">Critical</option>
                                            <option value="Critical">Major</option>
                                            <option value="Critical">Minor</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Complaint Sample">Review of Complaint Sample</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_complaint_sample_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_complaint_sample_gi" id="summernote-1"> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Control Sample">Review of Control Sample</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                                <textarea  name="review_of_control_sample_gi" id="summernote-1"></textarea>
                                                {{-- <textarea class="summernote" name="review_of_control_sample_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="root_cause">
                                            Investigation Team
                                            <button type="button" id="investigation_team_add">+</button>
                                            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="Investing_team" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Name</th>
                                                        <th>Department</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input disabled type="text" name="Investing_team[0][serial]" value="1"></td>
                                                        <td><input type="text" name="Investing_team[0][name_inv_tem]"></td>
                                                        <td><input type="text" name="Investing_team[0][department_inv_tem]"></td>
                                                        <td><input type="text" name="Investing_team[0][remarks_inv_tem]"></td>
                                                         <td><button type="text" class="removeRowBtn" >Remove</button></td>

                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    $(document).on('click', '.removeRowBtn', function() {
                                        $(this).closest('tr').remove();
                                    })
                                </script>
                                <script>
                                    $(document).ready(function() {
                                        $('#investigation_team_add').click(function(e) {
                                            e.preventDefault();

                                            function generateTableRow(serialNumber) {
                                                var html =
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="Investing_team[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="Investing_team[' + serialNumber + '][name_inv_tem]"></td>' +
                                                    '<td><input type="text" name="Investing_team[' + serialNumber + '][department_inv_tem]"></td>' +
                                                    '<td><input type="text" name="Investing_team[' + serialNumber + '][remarks_inv_tem]"></td>' +
                                                    '<td><button type="text" class="removeRowBtn" >Remove</button></td>' +

                                                    '</tr>';
                                                return html;
                                            }

                                            var tableBody = $('#Investing_team tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                    });
                                </script>


                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Batch manufacturing Record (BMR)">Review
                                            of Batch manufacturing
                                            record (BMR)</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea name="review_of_batch_manufacturing_record_BMR_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_batch_manufacturing_record_BMR_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label
                                            for="Review of Raw materials used in batch
                                        manufacturing">Review
                                            of Raw Materials Used in Batch
                                            Manufacturing</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_raw_materials_used_in_batch_manufacturing_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_raw_materials_used_in_batch_manufacturing_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Batch Packing record (BPR)">Review of Batch Packing Record
                                            (BPR)</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_Batch_Packing_record_bpr_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_Batch_Packing_record_bpr_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of packing materials used in batch packing">Review of Packing
                                            Materials Used in Batch
                                            Packing</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_packing_materials_used_in_batch_packing_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_packing_materials_used_in_batch_packing_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Analytical Data">Review of Analytical Data</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea name="review_of_analytical_data_gi" id="summernote-1"></textarea>
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of training record of Concern Persons">Review of Training Record
                                            of Concern Persons</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_training_record_of_concern_persons_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_training_record_of_concern_persons_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Equipment/Instrument qualification/Calibration record">Review
                                            of Equipment/Instrument qualification/Calibration Record</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="rev_eq_inst_qual_calib_record_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="rev_eq_inst_qual_calib_record_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Equipment Break-down and Maintainance Record">Review of
                                            Equipment Break-down and Maintainance Record</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="review_of_equipment_break_down_and_maintainance_record_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_equipment_break_down_and_maintainance_record_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Review of Past history of product">Review of Past History of Product</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div>
                                        <textarea name="review_of_past_history_of_product_gi" id="summernote-1"></textarea>
                                        {{-- <textarea class="summernote" name="review_of_past_history_of_product_gi" id="summernote-1"></textarea> --}}
                                    </div>
                                </div>
                                
                                

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="root_cause">
                                            Brain Storming Session/Discussion with Concerned Person
                                            <button type="button" id="brain_storming_add">+</button>
                                            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="brain_stroming_details" style="width: 100%;">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Possibility</th>
                                                        <th>Facts/Controls</th>
                                                        <th>Probable Cause</th>
                                                        <th>Remarks</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><input disabled type="text" name="brain_stroming_details[0][serial]" value="1"></td>
                                                        <td><input type="text" name="brain_stroming_details[0][possibility_bssd]"></td>
                                                        <td><input type="text" name="brain_stroming_details[0][factscontrols_bssd]"></td>
                                                        <td><input type="text" name="brain_stroming_details[0][probable_cause_bssd]"></td>
                                                        <td><input type="text" name="brain_stroming_details[0][remarks_bssd]"></td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    $(document).on('click', '.removeRowBtn', function() {
                                        $(this).closest('tr').remove();
                                    });

                                    $(document).ready(function() {
                                        $('#brain_storming_add').click(function(e) {
                                            e.preventDefault();

                                            function generateTableRow(serialNumber) {
                                                var html =
                                                    '<tr>' +
                                                    '<td><input disabled type="text" name="brain_stroming_details[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                    '<td><input type="text" name="brain_stroming_details[' + serialNumber + '][possibility_bssd]"></td>' +
                                                    '<td><input type="text" name="brain_stroming_details[' + serialNumber + '][factscontrols_bssd]"></td>' +
                                                    '<td><input type="text" name="brain_stroming_details[' + serialNumber + '][probable_cause_bssd]"></td>' +
                                                    '<td><input type="text" name="brain_stroming_details[' + serialNumber + '][remarks_bssd]"></td>' +
                                                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                    '</tr>';
                                                return html;
                                            }

                                            var tableBody = $('#brain_stroming_details tbody');
                                            var rowCount = tableBody.children('tr').length;
                                            var newRow = generateTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                    });
                                </script>


                                <div class="button-block">
                                    <button type="submit" class="saveButton">Save</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>

                                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                            Exit </a> </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head col-12">HOD/Supervisor Review</div>



                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Conclusion">Conclusion</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="conclusion_hodsr" id="summernote-1">
                                    </textarea>
                                    {{-- <textarea class="summernote" name="conclusion_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Root Cause Analysis">Root Cause Analysis</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="root_cause_analysis_hodsr" id="summernote-1">
                                    </textarea>

                                    {{-- <textarea class="summernote" name="root_cause_analysis_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label
                                            for="The most probable root causes identified of the complaint are as below">The Most Probable Root Causes Identified of the Complaint Are as Below</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="probable_root_causes_complaint_hodsr" id="summernote-1">
                                    </textarea>

                                    {{-- <textarea class="summernote" name="probable_root_causes_complaint_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Impact Assessment">Impact Assessment :</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="impact_assessment_hodsr" id="summernote-1">
                                    </textarea>
                                    {{-- <textarea class="summernote" name="impact_assessment_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Corrective Action">Corrective Action :</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="corrective_action_hodsr" id="summernote-1">
                                    </textarea>
                                    {{-- <textarea class="summernote" name="corrective_action_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>


                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Preventive Action">Preventive Action :</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="preventive_action_hodsr" id="summernote-1">
                                    </textarea>

                                    {{-- <textarea class="summernote" name="preventive_action_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>

                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Summary and Conclusion">Summary and Conclusion</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="summary_and_conclusion_hodsr" id="summernote-1">
                                    </textarea>

                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="root_cause">
                                            Team Members
                                            <button type="button" id="add_team_member">+</button>
                                            <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal"
                                                style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                (Launch Instruction)
                                            </span>
                                        </label>
                                        <div class="table-responsive">
                                            <table class="table table-bordered" id="team_members_table">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 100px;">Row #</th>
                                                        <th>Names</th>
                                                        <th>Department</th>
                                                        <th>Sign</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!-- Initial row -->
                                                    <tr>
                                                        <td><input disabled type="text" name="Team_Members[0][serial]" value="1"></td>
                                                        <td><input type="text" name="Team_Members[0][names_tm]"></td>
                                                        <td><input type="text" name="Team_Members[0][department_tm]"></td>
                                                        <td><input type="text" name="Team_Members[0][sign_tm]"></td>
                                                        <td>
                                                            <div class="new-date-data-field">
                                                                <div class="group-input input-date">
                                                                    <div class="calenderauditee">
                                                                        <input id="date_0_date_tm" type="text" name="Team_Members[0][date_tm]" placeholder="DD-MMM-YYYY" />
                                                                        <input type="date" name="Team_Members[0][date_tm]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                            value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="date_0_date_tm" class="hide-input show_date"
                                                                            style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, 'date_0_date_tm')" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    $(document).ready(function() {
                                        // Event handler for adding new rows
                                        $('#add_team_member').click(function() {
                                            // Get the number of rows currently in the table body
                                            var tableBody = $('#team_members_table tbody');
                                            var rowCount = tableBody.children('tr').length;
                                
                                            // Generate new row HTML with correct serial number
                                            var newRow = generateTableRow(rowCount);
                                            tableBody.append(newRow);
                                        });
                                
                                        // Event handler for removing rows
                                        $('#team_members_table').on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                            reindexRows();
                                        });
                                
                                        // Function to generate the row HTML
                                        function generateTableRow(serialNumber) {
                                            return '<tr>' +
                                                '<td><input disabled type="text" name="Team_Members[' + serialNumber + '][serial]" value="' + (serialNumber + 1) + '"></td>' +
                                                '<td><input type="text" name="Team_Members[' + serialNumber + '][names_tm]"></td>' +
                                                '<td><input type="text" name="Team_Members[' + serialNumber + '][department_tm]"></td>' +
                                                '<td><input type="text" name="Team_Members[' + serialNumber + '][sign_tm]"></td>' +
                                                '<td><div class="new-date-data-field"><div class="group-input input-date"> <div class="calenderauditee"><input id="date_' + serialNumber + '_date_tm" type="text" name="Team_Members[' + serialNumber + '][date_tm]" placeholder="DD-MMM-YYYY" /> <input type="date" name="Team_Members[' + serialNumber + '][date_tm]" min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" id="date_' + serialNumber + '_date_tm" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, \'date_' + serialNumber + '_date_tm\')" /> </div> </div></div></td>' +
                                                '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                '</tr>';
                                        }
                                
                                        // Function to reindex rows after removal
                                        function reindexRows() {
                                            $('#team_members_table tbody tr').each(function(index) {
                                                $(this).find('input[name^="Team_Members"]').each(function() {
                                                    var name = $(this).attr('name');
                                                    var updatedName = name.replace(/\[\d+\]\[/, '[' + index + '][');  // Reindex name attributes
                                                    $(this).attr('name', updatedName);
                                
                                                    // Also update the value of the serial number input field
                                                    if (name.includes('serial')) {
                                                        $(this).val(index + 1); // Update serial number value
                                                    }
                                                });
                                            });
                                        }
                                    });
                                </script>
                                


                                    <div class="col-12">
                                        <div class="group-input">
                                            <label for="root_cause">
                                                Report Approval
                                                <button type="button" id="add_report_approval_row">+</button>
                                                <span class="text-primary" data-bs-toggle="modal"
                                                    data-bs-target="#document-details-field-instruction-modal"
                                                    style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                                    (Launch Instruction)
                                                </span>
                                            </label>
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="report_approval_table">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 100px;">Row #</th>
                                                            <th>Names</th>
                                                            <th>Department</th>
                                                            <th>Sign</th>
                                                            <th>Date</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input disabled type="text" name="Report_Approval[0][serial]" value="1"></td>
                                                            <td><input type="text" name="Report_Approval[0][names_rrv]"></td>
                                                            <td><input type="text" name="Report_Approval[0][department_rrv]"></td>
                                                            <td><input type="text" name="Report_Approval[0][sign_rrv]"></td>
                                                            <td>
                                                                <div class="new-date-data-field">
                                                                    <div class="group-input input-date">
                                                                        <div class="calenderauditee">
                                                                            <input id="date_0_date_rrv"
                                                                             type="text"
                                                                              name="Report_Approval[0][date_rrv]"
                                                                               placeholder="DD-MMM-YYYY" />
                                                                            <input type="date" name="Report_Approval[0][date_rrv]"
                                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" 
                                                                                id="date_0_date_rrv"
                                                                                class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, 'date_0_date_rrv')" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <script>
                                        $(document).on('click', '.removeRowBtn', function() {
                                            $(this).closest('tr').remove();
                                        });

                                        $(document).ready(function() {
                                            $('#add_report_approval_row').click(function(e) {
                                                e.preventDefault();

                                                function generateTableRow(reportNumber) {
                                                    var html =
                                                        '<tr>' +
                                                        '<td><input disabled type="text" name="Report_Approval[' + reportNumber + '][serial]" value="' + (reportNumber + 1) + '"></td>' +
                                                        '<td><input type="text" name="Report_Approval[' + reportNumber + '][names_rrv]"></td>' +
                                                        '<td><input type="text" name="Report_Approval[' + reportNumber + '][department_rrv]"></td>' +
                                                        '<td><input type="text" name="Report_Approval[' + reportNumber + '][sign_rrv]"></td>' +
                                                        '<td><div class="new-date-data-field"><div class="group-input input-date"><div class="calenderauditee"><input id="date_'+ reportNumber +'_date_rrv" type="text" name="Report_Approval[' + reportNumber + '][date_rrv]" placeholder="DD-MMM-YYYY" /> <input type="date" name="Report_Approval[' + reportNumber + '][date_rrv]" min="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" value="{{ \Carbon\Carbon::now()->format("Y-m-d") }}" id="date_'+ reportNumber +'_date_rrv" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" oninput="handleDateInput(this, \'date_'+ reportNumber +'_date_rrv\')" /> </div> </div></div></td>' +
                                                        '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                        '</tr>';
                                                    return html;
                                                }

                                                var tableBody = $('#report_approval_table tbody');
                                                var rowCount = tableBody.children('tr').length;
                                                var newRow = generateTableRow(rowCount);
                                                tableBody.append(newRow);
                                            });
                                        });
                                    </script>

 

                                                           <div class="col-12">
                                    <div class="group-input">
                                        <label for="Inv Attachments">HOD Attachment</label>
                                        <div>
                                            <small class="text-primary">
                                                Please Attach all relevant or supporting documents
                                            </small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="initial_attachment_hodsr"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="initial_attachment_hodsr" name="initial_attachment_hodsr[]" oninput="addMultipleFiles(this,'initial_attachment_hodsr')"
                                                    multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-3">
                                    <div class="group-input">
                                        <label for="Comments">Comments(if Any)</label>
                                        <div><small class="text-primary">Please insert "NA" in the data field if it does
                                                not require completion</small></div>
                                        <textarea  name="comments_if_any_hodsr" id="summernote-1">
                                    </textarea>
                                    {{-- <textarea class="summernote" name="comments_if_any_hodsr" id="summernote-1">
                                    </textarea> --}}
                                    </div>
                                </div>

                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Support_doc">Supporting Documents</label>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Support_doc"></div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Support_doc[]"
                                                    oninput="addMultipleFiles(this, 'Support_doc')" multiple>
                                            </div>
                                        </div>

                                    </div>
                                </div> --}}
                            </div>
                            <div class="button-block">
                                <button type="submit" class="saveButton">Save</button>
                                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>

                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">


                            <div class="sub-head">Acknowledgement</div>



                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Manufacturer name & Address">Manufacturer Name & Address</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does not
                                            require completion</small></div>
                                    <textarea  name="manufacturer_name_address_ca" id="summernote-1">
                                    </textarea>

                                    {{-- <textarea class="summernote" name="manufacturer_name_address_ca" id="summernote-1">
                                    </textarea> --}}
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="root_cause">
                                        Product/Material Details
                                        <button type="button" id="promate_add">+</button>
                                        <span class="text-primary" data-bs-toggle="modal" data-bs-target="#document-details-field-instruction-modal" style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            (Launch Instruction)
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="prod_mate_details" style="width: 100%;">
                                            <thead>
                                                <tr>
                                                    <th style="width: 100px;">Row #</th>
                                                    <th>Product Name</th>
                                                    <th>Batch No.</th>
                                                    <th>Mfg. Date</th>
                                                    <th>Exp. Date</th>
                                                    <th>Batch Size</th>
                                                    <th>Pack Profile</th>
                                                    <th>Released Quantity</th>
                                                    <th>Remarks</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><input disabled type="text" name="Product_MaterialDetails[0][serial]" value="1"></td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][product_name_ca]"></td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][batch_no_pmd_ca]"></td>
                                                    <td>
                                                        <div class="new-date-data-field">
                                                            <div class="group-input input-date">
                                                                <div class="calenderauditee">
                                                                    <input id="date_0_mfg_date_pmd_ca" type="text" name="Product_MaterialDetails[0][mfg_date_pmd_ca]" placeholder="DD-MMM-YYYY" />
                                                                    <input type="date" name="Product_MaterialDetails[0][mfg_date_pmd_ca]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="date_0_mfg_date_pmd_ca" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="new-date-data-field">
                                                            <div class="group-input input-date">
                                                                <div class="calenderauditee">
                                                                    <input id="date_0_expiry_date_pmd_ca" type="text" name="Product_MaterialDetails[0][expiry_date_pmd_ca]" placeholder="DD-MMM-YYYY" />
                                                                    <input type="date" name="Product_MaterialDetails[0][expiry_date_pmd_ca]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" id="date_0_expiry_date_pmd_ca" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][batch_size_pmd_ca]"></td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][pack_profile_pmd_ca]"></td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][released_quantity_pmd_ca]"></td>
                                                    <td><input type="text" name="Product_MaterialDetails[0][remarks_ca]"></td>
                                                    <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            
                            <script>
                                $(document).ready(function() {
                                    $('#promate_add').click(function(e) {
                                        e.preventDefault();
                            
                                        // Function to generate a new row with the given serial number
                                        function generateTableRow(productserialno) {
                                            var html =
                                                '<tr>' +
                                                '<td><input disabled type="text" name="Product_MaterialDetails[' + productserialno + '][serial]" value="' + (productserialno + 1) + '"></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][product_name_ca]"></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][batch_no_pmd_ca]"></td>' +
                                                '<td><div class="new-date-data-field"><div class="group-input input-date"><div class="calenderauditee">' +
                                                '<input id="date_' + productserialno + '_mfg_date_pmd_ca" type="text" name="Product_MaterialDetails[' + productserialno + '][mfg_date_pmd_ca]" placeholder="DD-MMM-YYYY" />' +
                                                '<input type="date" name="Product_MaterialDetails[' + productserialno + '][mfg_date_pmd_ca]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" id="date_' + productserialno + '_mfg_date_pmd_ca" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" /> </div></div></div></td>' +
                                                '<td><div class="new-date-data-field"><div class="group-input input-date"><div class="calenderauditee">' +
                                                '<input id="date_' + productserialno + '_expiry_date_pmd_ca" type="text" name="Product_MaterialDetails[' + productserialno + '][expiry_date_pmd_ca]" placeholder="DD-MMM-YYYY" />' +
                                                '<input type="date" name="Product_MaterialDetails[' + productserialno + '][expiry_date_pmd_ca]" min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" id="date_' + productserialno + '_expiry_date_pmd_ca" class="hide-input show_date" style="position: absolute; top: 0; left: 0; opacity: 0;" /> </div></div></div></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][batch_size_pmd_ca]"></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][pack_profile_pmd_ca]"></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][released_quantity_pmd_ca]"></td>' +
                                                '<td><input type="text" name="Product_MaterialDetails[' + productserialno + '][remarks_ca]"></td>' +
                                                '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                                                '</tr>';
                                            return html;
                                        }
                            
                                        // Get the table body and row count
                                        var tableBody = $('#prod_mate_details tbody');
                                        var rowCount = tableBody.children('tr').length;
                                        var newRow = generateTableRow(rowCount); // Pass the current row count to the row generator
                                        tableBody.append(newRow); // Add the new row to the table body
                                    });
                            
                                    // Event listener for removing rows
                                    $(document).on('click', '.removeRowBtn', function() {
                                        $(this).closest('tr').remove();
                                    });
                                });
                            </script>
                            





                            






                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Complaint Sample Required">Complaint Sample Required</label>
                                    <select name="complaint_sample_required_ca" onchange="">
                                        <option value="">-- select --</option>
                                        <option value="yes">Yes</option>
                                        <option value="no">No</option>
                                        <option value="na">NA</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="Complaint Sample Status">Complaint Sample Status</label>
                                    <input type="text" name="complaint_sample_status_ca" id="date_of_initiation">
                                </div>
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Brief Description of complaint">Brief Description of Complaint:</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="brief_description_of_complaint_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="brief_description_of_complaint_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Batch Record review observation">Batch Record Review Observation</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="batch_record_review_observation_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="batch_record_review_observation_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Analytical Data review observation">Analytical Data Review Observation</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="analytical_data_review_observation_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="analytical_data_review_observation_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Retention sample review observation">Retention Sample Review Observation</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="retention_sample_review_observation_ca" id="summernote-1"></textarea>
                                {{-- <textarea class="summernote" name="retention_sample_review_observation_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Stablity study data review">Stability Study Data Review</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="stability_study_data_review_ca" id="summernote-1">
                                </textarea>

                                {{-- <textarea class="summernote" name="stability_study_data_review_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="QMS Events(if any) review Observation">QMS Events (If Any) Review Observation</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="qms_events_ifany_review_observation_ca" id="summernote-1">
                                </textarea>

                                {{-- <textarea class="summernote" name="qms_events_ifany_review_observation_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Repeated complaints/queries for product">Repeated Complaints/Queries for Product:</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="repeated_complaints_queries_for_product_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="repeated_complaints_queries_for_product_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Interpretation on compalint sample">Interpretation on Complaint Sample (If Received)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="interpretation_on_complaint_sample_ifrecieved_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="interpretation_on_complaint_sample_ifrecieved_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="group-input">
                                    <label for="Comments">Comments (If Any)</label>
                                    <div><small class="text-primary">Please insert "NA" in the data field if it does
                                            not require completion</small></div>
                                    <textarea  name="comments_ifany_ca" id="summernote-1">
                                </textarea>
                                {{-- <textarea class="summernote" name="comments_ifany_ca" id="summernote-1">
                                </textarea> --}}
                                </div>
                            </div>

                            <div class="sub-head">
                                Proposal to accomplish investigation:
                            </div>
                            <div class="col-12">
                                {{-- <label for="Audit Attachments">PHASE- I B INVESTIGATION REPORT</label> --}}
                                <div class="group-input">
                                    <div class="why-why-chart">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%;">Sr. No.</th>
                                                    <th style="width: 40%;">Requirements</th>
                                                    <th style="width: 20%;">Expected date of investigation completion
                                                    </th>
                                                    <th>Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td class="flex text-center" name="">1</td>
                                                    <td>Complaint sample Required</td>
                                                    <td>

                                                        <div style="margin: auto; display: flex; justify-content: center;">
                                                            <textarea name="csr1" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                        </div>


                                                    </td>
                                                    </td>

                                                    <td style="vertical-align: middle;">
                                                        <div style="margin: auto; display: flex; justify-content: center;">
                                                            <textarea name="csr2" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                        </div>
                                                    </td>



                                                </tr>
                                                <tr>
                                                    <td class="flex text-center">2</td>
                                                    <td>Additional info. From Complainant</td>
                                                    <td>
                                                        <div style="margin: auto; display: flex; justify-content: center;">
                                                            <textarea name="afc1" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                                        </div>
                                    </div>
                                    </td>

                                    <td style="vertical-align: middle;">
                                        <div style="margin: auto; display: flex; justify-content: center;">
                                            <textarea name="afc2" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                        </div>
                                    </td>



                                    </tr>
                                    <tr>
                                        <td class="flex text-center">3</td>
                                        <td>Analysis of complaint Sample</td>
                                        <td>
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="acs1" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle;">
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="acs2" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>




                                    </tr>
                                    <tr>
                                        <td class="flex text-center">4</td>
                                        <td>QRM Approach</td>
                                        <td>
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="qrm1" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle;">
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="qrm2" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>



                                    </tr>
                                    <tr>
                                        <td class="flex text-center">5</td>
                                        <td>Others</td>
                                        <td>
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="oth1" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>

                                        <td style="vertical-align: middle;">
                                            <div style="margin: auto; display: flex; justify-content: center;">
                                                <textarea name="oth2" style="border-radius: 7px; border: 1.5px solid black;"></textarea>
                                            </div>
                                        </td>



                                    </tr>

                                    </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>


                        <div class="col-12">
                            <div class="group-input">
                                <label for="Inv Attachments">Acknowledgement Attachment</label>
                                <div>
                                    <small class="text-primary">
                                        Please Attach all relevant or supporting documents
                                    </small>
                                </div>
                                <div class="file-attachment-field">
                                    <div class="file-attachment-list" id="initial_attachment_ca"></div>
                                    <div class="add-btn">
                                        <div>Add</div>
                                        <input type="file" id="initial_attachment_ca" name="initial_attachment_ca[]" oninput="addMultipleFiles(this,'initial_attachment_ca')" multiple>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton">Save</button>
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button" class="nextButton" onclick="nextStep()">Next</button>

                        <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                Exit </a> </button>
                    </div>
                </div>
        </div>





        <div id="CCForm4" class="inner-block cctabcontent">
            <div class="inner-block-content">
                <div class="sub-head">
                    Closure
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="group-input">
                            <label for="Closure Comment">Closure Comment</label>
                            <div><small class="text-primary">Please insert "NA" in the data field if it does not
                                    require completion</small></div>
                            <textarea  name="closure_comment_c" id="summernote-1">
                                    </textarea>
                                     {{-- <textarea class="summernote" name="closure_comment_c" id="summernote-1">
                                     </textarea> --}}
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="group-input">
                            <label for="Inv Attachments">Closure Attachment</label>
                            <div>
                                <small class="text-primary">
                                    Please Attach all relevant or supporting documents
                                </small>
                            </div>
                            <div class="file-attachment-field">
                                <div class="file-attachment-list" id="initial_attachment_c"></div>
                                <div class="add-btn">
                                    <div>Add</div>
                                    <input type="file" id="initial_attachment_c" name="initial_attachment_c[]" oninput="addMultipleFiles(this,'initial_attachment_c')" multiple>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="button-block">
                    <button type="submit" class="saveButton">Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>

                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                        </a> </button>
                </div>
            </div>
        </div>

        <div id="CCForm5" class="inner-block cctabcontent">
            <div class="inner-block-content">

                <div class="row">



                    <div class="sub-head">
                        Activity Log
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Submit By : </label>

                        </div>
                    </div>

                    <div class="col-lg-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="OOC Logged On">Submit On : </label>




                        </div>
                    </div>



                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">HOD Review Completed By : </label>

                        </div>
                    </div>

                    <div class="col-lg-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="OOC Logged On">HOD Review Completed On :</label>




                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">QA Initial Review Completed By :</label>

                        </div>
                    </div>

                    <div class="col-lg-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="OOC Logged On">QA Initial Review Completed On : </label>




                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">QA Final Review Completed By : </label>

                        </div>
                    </div>

                    <div class="col-lg-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="OOC Logged On">QA Final Review Completed On : </label>




                        </div>
                    </div>


                    <div class="col-lg-6">
                        <div class="group-input">
                            <label for="Initiator Group">Closure Done By : </label>

                        </div>
                    </div>

                    <div class="col-lg-6 new-date-data-field">
                        <div class="group-input input-date">
                            <label for="OOC Logged On">Closure Done On : </label>




                        </div>
                    </div>





                </div>
                <div class="button-block">
                    <button type="submit" class="saveButton">Save</button>
                    <button type="button" class="backButton" onclick="previousStep()">Back</button>

                    <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">Exit
                        </a> </button>
                </div>
            </div>
        </div>
    </div>
    </form>

    </div>
    </div>

    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }
    </style>

    <script>
        VirtualSelect.init({
            ele: '#related_records, #hod'
        });


        function openCity(evt, cityName) {
            var i, cctabcontent, cctablinks;
            cctabcontent = document.getElementsByClassName("cctabcontent");
            for (i = 0; i < cctabcontent.length; i++) {
                cctabcontent[i].style.display = "none";
            }
            cctablinks = document.getElementsByClassName("cctablinks");
            for (i = 0; i < cctablinks.length; i++) {
                cctablinks[i].className = cctablinks[i].className.replace(" active", "");
            }
            document.getElementById(cityName).style.display = "block";
            evt.currentTarget.className += " active";

            // Find the index of the clicked tab button
            const index = Array.from(cctablinks).findIndex(button => button === evt.currentTarget);

            // Update the currentStep to the index of the clicked tab
            currentStep = index;
        }

        const saveButtons = document.querySelectorAll(".saveButton");
        const nextButtons = document.querySelectorAll(".nextButton");
        const form = document.getElementById("step-form");
        const stepButtons = document.querySelectorAll(".cctablinks");
        const steps = document.querySelectorAll(".cctabcontent");
        let currentStep = 0;

        function nextStep() {
            // Check if there is a next step
            if (currentStep < steps.length - 1) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show next step
                steps[currentStep + 1].style.display = "block";

                // Add active class to next button
                stepButtons[currentStep + 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current step
                currentStep++;
            }
        }

        function previousStep() {
            // Check if there is a previous step
            if (currentStep > 0) {
                // Hide current step
                steps[currentStep].style.display = "none";

                // Show previous step
                steps[currentStep - 1].style.display = "block";

                // Add active class to previous button
                stepButtons[currentStep - 1].classList.add("active");

                // Remove active class from current button
                stepButtons[currentStep].classList.remove("active");

                // Update current stepkmlkmlmkmklm
                currentStep--;
            }
        }
    </script>
    <script>
        VirtualSelect.init({
            ele: '#reference_record, #notify_to'
        });

        $('#summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear', 'italic']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture', 'video']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });

        let referenceCount = 1;

        function addReference() {
            referenceCount++;
            let newReference = document.createElement('div');
            newReference.classList.add('row', 'reference-data-' + referenceCount);
            newReference.innerHTML = `
            <div class="col-lg-6">
                <input type="text" name="reference-text">
            </div>
            <div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div><div class="col-lg-6">
                <input type="file" name="references" class="myclassname">
            </div>
        `;
            let referenceContainer = document.querySelector('.reference-data');
            referenceContainer.parentNode.insertBefore(newReference, referenceContainer.nextSibling);
        }
    </script>
    <script>
        var maxLength = 255;
        $('#docname').keyup(function() {
            var textlen = maxLength - $(this).val().length;
            $('#rchars').text(textlen);
        });
    </script>

<script>
    document.getElementById('initiator_group').addEventListener('change', function() {
        var selectedValue = this.value;
        document.getElementById('initiator_group_code').value = selectedValue;
    });

    function setCurrentDate(item){
        if(item == 'yes'){
            $('#effect_check_date').val('{{ date('d-M-Y')}}');
        }
        else{
            $('#effect_check_date').val('');
        }
    }
</script>




{{-- ---------------------======================record number script  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
var originalRecordNumber = document.getElementById('record').value;
var initialPlaceholder = '---';

document.getElementById('initiator_group').addEventListener('change', function() {
    var selectedValue = this.value;
    var recordNumberElement = document.getElementById('record');
    var initiatorGroupCodeElement = document.getElementById('initiator_group_code');

    // Update the initiator group code
    initiatorGroupCodeElement.value = selectedValue;

    // Update the record number by replacing the initial placeholder with the selected initiator group code
    var newRecordNumber = originalRecordNumber.replace(initialPlaceholder, selectedValue);
    recordNumberElement.value = newRecordNumber;

    // Update the original record number to keep track of changes
    originalRecordNumber = newRecordNumber;
    initialPlaceholder = selectedValue;
});
});

</script>
@endsection
