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

    <div class="form-field-head">
        {{-- <div class="pr-id">
            New Child
        </div> --}}
        <div class="division-bar">
            <strong>Site Division/Project</strong> : {{ Helpers::getDivisionName(session()->get('division')) }}/ERRATA
        </div>
    </div>


    <style>
        .progress-bars div {
            flex: 1 1 auto;
            border: 1px solid grey;
            padding: 5px;
            /* border-radius: 20px; */
            text-align: center;
            position: relative;
            /* border-right: none; */
            background: white;
        }

        .state-block {
            padding: 20px;
            margin-bottom: 20px;
        }

        .progress-bars div.active {
            background: green;
            font-weight: bold;
        }

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(1) {
            border-radius: 20px 0px 0px 20px;
        }

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(6) {
            border-radius: 0px 20px 20px 0px;

        }
    </style>
    {{-- ! ========================================= --}}
    {{-- !               DATA FIELDS                 --}}
    {{-- ! ========================================= --}}
    <div id="change-control-fields">
        <div class="container-fluid">


            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => auth()->id(), 'q_m_s_divisions_id' => $showdata->division_id])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        @endphp
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('errataaudittrail', $showdata->id) }}">
                                Audit Trail </a> </button>

                        @if ($showdata->stage == 1 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($showdata->stage == 2 && (in_array(7, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#reject-modal">
                                Reject
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#review-modal">
                                Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($showdata->stage == 3 && (in_array(3, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#more-info-required-modal">
                                Request More Info.
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#correction-modal">
                                Correction Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                        @elseif($showdata->stage == 4 && (in_array([4, 14], $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#more-inform-required-modal">
                                Request More Info.
                            </button>

                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#hod-rewieve-modal">
                                HOD Review Completed
                            </button>
                        @elseif($showdata->stage == 5 && (in_array(9, $userRoleIds) || in_array(18, $userRoleIds)))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#send-to-opened-modal">
                                Send to Opened State
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#qa-head-approval-model">
                                QA Head Approval Completed
                            </button>
                        @endif
                        <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                            </a> </button>
                    </div>

                </div>


                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($showdata->stage == 0)
                        <div class="progress-bars ">
                            <div class="bg-danger" style="border-radius: 20px;">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars d-flex" style="font-size: 15px;">
                            @if ($showdata->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif
                            @if ($showdata->stage >= 2)
                                <div class="active">Pending Review</div>
                            @else
                                <div class="">Pending Review</div>
                            @endif
                            @if ($showdata->stage >= 3)
                                <div class="active">Pending Correction</div>
                            @else
                                <div class="">Pending Correction</div>
                            @endif
                            @if ($showdata->stage >= 4)
                                <div class="active">Pending HOD Review</div>
                            @else
                                <div class="">Pending HOD Review</div>
                            @endif
                            @if ($showdata->stage >= 5)
                                <div class="active">Pending QA Head Approval</div>
                            @else
                                <div class="">Pending QA Head Approval</div>
                            @endif
                            @if ($showdata->stage >= 6)
                                <div class="bg-danger">Closed-Done</div>
                            @else
                                <div class="">Closed-Done</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General Information</button>
                <button class="cctablinks " onclick="openCity(event, 'CCForm2')">HOD Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">QA Review</button>
                {{-- <button class="cctablinks" onclick="openCity(event, 'CCForm3')">CFT</button> --}}
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Head Designee Approval</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Activity Log</button>
            </div>

            <form action="{{ route('errata.update', $showdata->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div id="step-form">
                    @if (!empty($parent_id))
                        <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                        <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                    @endif
                    <!-- -----------Tab-1------------ -->
                    <div id="CCForm1" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="sub-head">Parent Record Information</div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number">Record Number</label>
                                        <input disabled type="text" name="record_no"
                                        value="{{  Helpers::getDivisionName(session()->get('division')) }}/ERRATA/{{ Helpers::year($showdata->created_at) }}/{{ str_pad($showdata->record, 4, '0', STR_PAD_LEFT) }}">
                                        {{-- <input disabled type="text" name="record"> --}}
                                        {{-- value="{{ Helpers::getDivisionName(session()->get('division')) }}/ERRATA/{{ date('Y') }}/{{ $record }}"> --}}
                                        {{-- <div class="static">QMS-EMEA/CAPA/{{ date('Y') }}/{{ $record_number }}</div> --}}
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code">Site/Location Code</label>
                                        <input readonly type="text" name="division_id"
                                            value="{{ Helpers::getDivisionName(session()->get('division')) }}">
                                        <input type="hidden" name="division_id"
                                            value="{{ session()->get('division') }}">
                                        {{-- <div class="static">{{ Helpers::getDivisionName(session()->get('division')) }}</div> --}}
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator">Initiator</label>
                                        {{-- <div class="static">{{ Auth::user()->name }}</div> --}}
                                        <input disabled type="text" name="initiator_by"
                                            value="{{ Auth::user()->name }}"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Date Due">Date of Initiation</label>
                                        <input disabled type="text" value="{{ date('d-M-Y') }}" name="intiation_date"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                        <input type="hidden" value="{{ date('Y-m-d') }}" name="intiation_date"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Initiated Through">
                                            Initiated Through <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="initiated_by"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <option value="">--Select--</option>
                                            <option value="Recall "{{ $showdata->initiated_by == 'Recall' ? 'selected' : '' }}>Recall </option>
                                            <option value="Return "{{ $showdata->initiated_by == 'Return' ? 'selected' : '' }}>Return </option>
                                            <option value="Deviation"{{ $showdata->initiated_by == 'Deviation' ? 'selected' : '' }}>Deviation</option>
                                            <option value="Complaint"{{ $showdata->initiated_by == 'Complaint' ? 'selected' : '' }}>Complaint</option>
                                            <option value="Regulatory"{{ $showdata->initiated_by == 'Regulatory' ? 'selected' : '' }}>Regulatory</option>
                                            <option value="Lab Incident"{{ $showdata->initiated_by == 'Lab Incident' ? 'selected' : '' }}>Lab Incident</option>
                                            <option value="Improvement"{{ $showdata->initiated_by == 'Improvement' ? 'selected' : '' }}>Improvement</option>
                                            <option value="Others"{{ $showdata->initiated_by == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Department">
                                            Department<span class="text-danger"></span>
                                        </label>
                                        <select name="Department"
                                            {{ $showdata->stage == 0 || $showdata->stage == 6 ? 'disabled' : '' }}
                                            id="selectedOptions" {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <option value="">--Select--</option>
                                            <option value="CQA" @if ($showdata->Department == 'Corporate Quality Assurance') selected @endif>
                                                Corporate
                                                Quality Assurance</option>
                                            <option value="QAB" @if ($showdata->Department == 'Quality Assurance Biopharma') selected @endif>
                                                Quality
                                                Assurance Biopharma</option>
                                            <option value="CQC" @if ($showdata->Department == 'Central Quality Control') selected @endif>
                                                Central
                                                Quality Control</option>
                                            <option value="CQC" @if ($showdata->Department == 'Manufacturing') selected @endif>
                                                Manufacturing
                                            </option>
                                            <option value="PSG" @if ($showdata->Department == ' Plasma Sourcing Group') selected @endif>
                                                Plasma
                                                Sourcing Group</option>
                                            <option value="CS" @if ($showdata->Department == 'Central Stores') selected @endif>
                                                Central
                                                Stores</option>
                                            <option value="ITG" @if ($showdata->Department == 'Information Technology Group') selected @endif>
                                                Information
                                                Technology Group</option>
                                            <option value="MM" @if ($showdata->Department == 'Molecular Medicine') selected @endif>
                                                Molecular
                                                Medicine</option>
                                            <option value="CL" @if ($showdata->Department == 'Central Laboratory') selected @endif>
                                                Central
                                                Laboratory</option>
                                            <option value="TT" @if ($showdata->Department == 'Tech Team') selected @endif>Tech
                                                Team</option>
                                            <option value="QA" @if ($showdata->Department == 'Quality Assurance') selected @endif>
                                                Quality
                                                Assurance</option>
                                            <option value="QM" @if ($showdata->Department == 'Quality Management') selected @endif>
                                                Quality
                                                Management</option>
                                            <option value="IA" @if ($showdata->Department == 'IT Administration') selected @endif>IT
                                                Administration</option>
                                            <option value="ACC" @if ($showdata->Department == 'Accounting') selected @endif>
                                                Accounting
                                            </option>
                                            <option value="LOG" @if ($showdata->Department == 'Logistics') selected @endif>
                                                Logistics
                                            </option>
                                            <option value="SM" @if ($showdata->Department == 'Senior Management') selected @endif>
                                                Senior
                                                Management</option>
                                            <option value="BA" @if ($showdata->Department == 'Business Administration') selected @endif>
                                                Business
                                                Administration</option>

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Initiator Group Code">Department Code</label>
                                        <input readonly type="text"
                                            name="department_code"{{ $showdata->stage == 0 || $showdata->stage == 6 ? 'disabled' : '' }}
                                            value="{{ $showdata->department_code }}" id="initiator_group_code" readonly
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                    </div>
                                </div>

                                <script>
                                    document.getElementById('selectedOptions').addEventListener('change', function() {
                                        var selectedValue = this.value;
                                        document.getElementById('initiator_group_code').value = selectedValue;
                                    });

                                    function setCurrentDate(item) {
                                        if (item == 'yes') {
                                            $('#effect_check_date').val('{{ date('d-M-Y') }}');
                                        } else {
                                            $('#effect_check_date').val('');
                                        }
                                    }
                                </script>
                                {{-- <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Department Code">
                                            Department Code<span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="department_code">
                                            <option value="{{ $showdata->department_code }}">
                                                {{ $showdata->department_code }}</option>
                                            <option value="DC01">DC01</option>
                                            <option value="DC02">DC02</option>
                                            <option value="DC03">DC03</option>
                                        </select>
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="Document Type">
                                            Document Type<span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="document_type"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <option value="">--Select--</option>
                                            <option value="Procedure Document"@if ($showdata->document_type == 'Procedure Document') selected @endif>Procedure Document</option>
                                            <option value="Work Instruction"@if ($showdata->document_type == 'Work Instruction') selected @endif>Work Instruction</option>
                                            <option value="Form"@if ($showdata->document_type == 'Form') selected @endif>Form</option>
                                            <option value="Template"@if ($showdata->document_type == 'Template') selected @endif>Template</option>
                                            <option value="Policy Document"@if ($showdata->document_type == 'Policy Document') selected @endif>Policy Document</option>
                                            <option value="Quality Record"@if ($showdata->document_type == 'Quality Record') selected @endif>Quality Record</option>
                                            <option value="Specification Document"@if ($showdata->document_type == 'Specification Document') selected @endif>Specification Document</option>
                                            <option value="Training Material"@if ($showdata->document_type == 'Training Material') selected @endif>Training Material</option>
                                            <option value="Other"@if ($showdata->document_type == 'Other') selected @endif>Other</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="Short Description">Short Description<span
                                                class="text-danger">*</span></label><span id="rchars">255</span>
                                        characters remaining
                                        <input id="docname" type="text" name="short_description" maxlength="255"
                                            value="{{ $showdata->short_description }}"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }} required>
                                    </div>
                                </div>
                                @php
                                    $old_record = DB::table('erratas')->get();
                                    $reference_documents = is_array($showdata->reference_document)
                                        ? $showdata->reference_document
                                        : explode(',', $showdata->reference_document);
                                @endphp
                                <div class="">
                                    <div class="group-input">
                                        <label for="reference_record">Reference Documents</label>
                                        <select multiple id="reference_record" name="reference_document[]"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            @foreach ($old_record as $new)
                                                @php
                                                    $value = Helpers::getDivisionName($new->division_id) . '/ERRATA/' . date('Y') . '/' . str_pad($new->id, 4, '0', STR_PAD_LEFT);
                                                    $selected = in_array($value, $reference_documents) ? 'selected' : '';
                                                @endphp
                                                <option value="{{ $value }}" {{ $selected }}>
                                                    {{ $value }}
                                                </option>
                                            @endforeach

                                            {{-- <option value="{{ $referenceValue }}"
                                                @if (in_array($referenceValue, $showdata->reference_document)) selected @endif>
                                                {{ $referenceValue }}
                                            </option> --}}
                                            {{-- Uncomment and add more options as needed --}}
                                            {{-- <option value="RD02" @if (in_array('RD02', $showdata->reference_document)) selected @endif>RD02</option> --}}
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="Observation on Page No.">Error Observed on Page
                                            No.</label>
                                        <textarea class="summernote" name="Observation_on_Page_No" id="summernote-16"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>{{ $showdata->Observation_on_Page_No }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="Brief Description">Brief Description</label>
                                        <textarea class="summernote" name="brief_description" id="summernote-16"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>{{ $showdata->brief_description }}</textarea>
                                    </div>
                                </div>

                                <div class="">
                                    <div class="group-input">
                                        <label for="Type Of Error">
                                            Type Of Error<span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..." name="type_of_error"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <option value="">--Select--</option>
                                            <option value="Typographical Error (TE)" @if ($showdata->type_of_error == 'Typographical Error (TE)') selected @endif>Typographical Error (TE)
                                            </option>
                                            <option value="Calculation Error (CE)" @if ($showdata->type_of_error == 'Calculation Error (CE)') selected @endif>Calculation Error (CE)</option>
                                            <option value="Grammatical Error (GE)" @if ($showdata->type_of_error == 'Grammatical Error (GE)') selected @endif>Grammatical Error (GE)</option>
                                            <option value="Missing Word Error (ME)" @if ($showdata->type_of_error == 'Missing Word Error (ME)') selected @endif>Missing Word Error (ME)
                                            </option>
                                        </select>
                                    </div>
                                </div>

                                <div class="group-input">
                                    <label for="audit-agenda-grid">
                                        Details
                                        <button type="button" name="details" id="Details-add">+</button>
                                        <span class="text-primary" data-bs-toggle="modal"
                                            data-bs-target="#observation-field-instruction-modal"
                                            style="font-size: 0.8rem; font-weight: 400; cursor: pointer;">
                                            Launch Deviation
                                        </span>
                                    </label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="Details-table"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <thead>
                                                <tr>
                                                    <th style="width: 5%">Row#</th>
                                                    <th style="width: 12%">List Of Impacting Document (If Any)</th>
                                                    <th style="width: 16%"> Prepared By</th>
                                                    <th style="width: 15%">Checked By</th>
                                                    <th style="width: 15%">Approved By</th>
                                                    <th style="width: 15%">Action</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if ($grid_Data && is_array($grid_Data->data))
                                                    @foreach ($grid_Data->data as $grid_Data)
                                                        <tr>
                                                            <td><input disabled type="text"
                                                                    name="details[{{ $loop->index }}][serial]"
                                                                    value="{{ $loop->index + 1 }}"
                                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                                            </td>
                                                            <td><input type="text"
                                                                    name="details[{{ $loop->index }}][ListOfImpactingDocument]"
                                                                    value="{{ isset($grid_Data['ListOfImpactingDocument']) ? $grid_Data['ListOfImpactingDocument'] : '' }}"
                                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                                            </td>
                                                            <td><input type="text"
                                                                    name="details[{{ $loop->index }}][PreparedBy]"
                                                                    value="{{ isset($grid_Data['PreparedBy']) ? $grid_Data['PreparedBy'] : '' }}"
                                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                                            </td>
                                                            <td><input type="text"
                                                                    name="details[{{ $loop->index }}][CheckedBy]"
                                                                    value="{{ isset($grid_Data['CheckedBy']) ? $grid_Data['CheckedBy'] : '' }}"
                                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                                            </td>
                                                            <td><input type="text"
                                                                    name="details[{{ $loop->index }}][ApprovedBy]"
                                                                    value="{{ isset($grid_Data['ApprovedBy']) ? $grid_Data['ApprovedBy'] : '' }}"
                                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                                            </td>
                                                            <td><button type="text"
                                                                    class="removeRowBtn">Remove</button></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>

                                        </table>
                                    </div>
                                </div>

                                {{-- <div class="">
                                    <div class="group-input">
                                        <label for="Date And Time of Correction"><b>Date And Time of Correction
                                            </b></label>
                                        <input type="datetime-local" name="Date_and_time_of_correction"
                                            value="{{ $showdata->Date_and_time_of_correction }}"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>

                                    </div>
                                </div> --}}
                                {{-- <div class="new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Errata_date">Date And Time of Correction</label>
                                        <div class="calenderauditee">

                                            <input type="text" id="displayErrataDate" nmae="Date_and_time_of_correction" readonly placeholder="DD-MM-YYYY HH:MM" value="{{ $showdata->Date_and_time_of_correction }}"/>

                                            <input type="datetime-local" id="Errata_date" name="Date_and_time_of_correction" max="{{ \Carbon\Carbon::now()->format('Y-m-d\TH:i') }}" onchange="updateDisplayDateTime(this)" class="hide-input" />
                                        </div>
                                    </div>
                                    @error('Errata_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div> --}}

                                <div class="new-date-data-field">
                                    <div class="group-input input-date">
                                        <label for="Errata_date">Date And Time of Correction</label>
                                        <div class="calenderauditee">
                                            <input type="text" id="displayErrataDate" name="Date_and_time_of_correction" readonly placeholder="DD-MM-YYYY HH:MM" value="{{ old('Date_and_time_of_correction', $showdata->Date_and_time_of_correction) }}"/>
                                            <input type="datetime-local" id="Errata_date" name="Date_and_time_of_correction" onchange="updateDisplayDateTime(this)" class="hide-input" />
                                        </div>
                                    </div>
                                    @error('Errata_date')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <script>
                                    function updateDisplayDateTime(input) {
                                        const selectedDateTime = new Date(input.value);
                                        const formattedDateTime = formatDate(selectedDateTime);
                                        document.getElementById('displayErrataDate').value = formattedDateTime;
                                    }

                                    function formatDate(date) {
                                        const day = String(date.getDate()).padStart(2, '0');
                                        const monthIndex = date.getMonth();
                                        const monthNames = ["Jan", "Feb", "March", "April", "May", "June",
                                            "July", "Aug", "Sep", "Oct", "Nov", "Dec"];
                                        const month = monthNames[monthIndex];
                                        const year = date.getFullYear();
                                        let hours = date.getHours();
                                        let minutes = date.getMinutes();

                                        hours = String(hours).padStart(2, '0');
                                        minutes = String(minutes).padStart(2, '0');

                                        const formattedDateTime = `${day}-${month}-${year} ${hours}:${minutes}`;

                                        return formattedDateTime;
                                    }
                                </script>







                            </div>
                            <div class="button-block">
                                @if ($showdata->stage >= 6)
                                <button type="submit" class="saveButton" disabled>Save</button>
                                @else
                                <button type="submit" class="saveButton">Save</button>
                                @endif
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                <button type="button"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}">
                                        Exit </a> </button>
                            </div>
                        </div>
                    </div>
                    <!-- -----------Tab-2------------ -->
                    <div id="CCForm2" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="HOD Remarks">HOD Remarks</label>
                                        <textarea class="summernote" name="HOD_Remarks" id="summernote-16"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>{{ $showdata->HOD_Remarks }}</textarea>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="HOD Attachments">HOD Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div disabled class="file-attachment-list" id="HOD_Attachments">
                                                @if ($showdata->HOD_Attachments)
                                                    @foreach (json_decode($showdata->HOD_Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgba(255, 255, 255, 0);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="HOD_Attachments[]"
                                                    oninput="addMultipleFiles(this, 'HOD_Attachments')"
                                                    {{ Helpers::disabledErrataFields($showdata->stage) }} multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const removeButtons = document.querySelectorAll('.remove-file');

                                        removeButtons.forEach(button => {
                                            button.addEventListener('click', function () {
                                                const fileName = this.getAttribute('data-file-name');
                                                const fileContainer = this.closest('.file-container');

                                                // Hide the file container
                                                if (fileContainer) {
                                                    fileContainer.style.display = 'none';
                                                }
                                            });
                                        });
                                    });
                                </script> --}}
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const removeButtons = document.querySelectorAll('.remove-file');

                                        removeButtons.forEach(button => {
                                            button.addEventListener('click', function () {
                                                const fileName = this.getAttribute('data-file-name');
                                                const fileContainer = this.closest('.file-container');

                                                // Hide the file container
                                                if (fileContainer) {
                                                    fileContainer.style.display = 'none';
                                                }
                                            });
                                        });
                                    });

                                    function addMultipleFiles(input, id) {
                                        const fileListContainer = document.getElementById(id);
                                        const files = input.files;

                                        for (let i = 0; i < files.length; i++) {
                                            const file = files[i];
                                            const fileName = file.name;
                                            const fileContainer = document.createElement('h6');
                                            fileContainer.classList.add('file-container', 'text-dark');
                                            fileContainer.style.backgroundColor = 'rgb(243, 242, 240)';

                                            const fileText = document.createElement('b');
                                            fileText.textContent = fileName;

                                            const viewLink = document.createElement('a');
                                            viewLink.href = '#'; // You might need to adjust this to handle local previews
                                            viewLink.target = '_blank';
                                            viewLink.innerHTML = '<i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i>';

                                            const removeLink = document.createElement('a');
                                            removeLink.classList.add('remove-file');
                                            removeLink.dataset.fileName = fileName;
                                            removeLink.innerHTML = '<i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i>';
                                            removeLink.addEventListener('click', function () {
                                                fileContainer.style.display = 'none';
                                            });

                                            fileContainer.appendChild(fileText);
                                            fileContainer.appendChild(viewLink);
                                            fileContainer.appendChild(removeLink);

                                            fileListContainer.appendChild(fileContainer);
                                        }
                                    }
                                </script>


                                <div class="button-block">
                                    @if ($showdata->stage >= 6)
                                    <button type="submit" class="saveButton" disabled>Save</button>
                                    @else
                                    <button type="submit" class="saveButton">Save</button>
                                    @endif
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>


                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit
                                        </a> </button>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- -----------Tab-3------------ -->
                    {{-- <div id="CCForm3" class="inner-block cctabcontent">
                                <div class="inner-block-content">
                                    <div class="row">
                                        <div class="sub-head">Production</div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Production Review Required </label>
                                                <select name="production_review_required">
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Production Person </label>
                                                <select name="">
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By
                                                    Production)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Production Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Production Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Production Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Production Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Warehouse</div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Warehouse Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Warehouse Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By
                                                    Warehouse)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Warehouse Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Warehouse Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Warehouse Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Warehouse Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Quality Control</div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Quality Control Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Quality Control Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Quality
                                                    Control)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Quality Control Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Quality Control Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Quality Control Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Quality Control Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Engineering </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Engineering Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Engineering Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By
                                                    Engineering)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Engineering Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Engineering Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Engineering Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Engineering Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Analytical Development Laboratry </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Analytical Development Laboratry Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Analytical Development Laboratry Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Analytical
                                                    Development Laboratry)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Analytical Development Laboratry
                                                    Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Analytical Development Laboratry Attachments
                                                </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Analytical Development Laboratry Review Completed
                                                    By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Analytical Development Laboratry Review Completed
                                                    On</label>
                                                <input type="date" />
                                            </div>
                                        </div>



                                        <div class="sub-head">Process Development Laboratry </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Process Development Laboratry Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Process Development Laboratry Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Process
                                                    Development Laboratry)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Process Development Laboratry
                                                    Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Process Development Laboratry Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Process Development Laboratory / Kilo Lab Review Completed
                                                    By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Process Development Laboratory / Kilo Lab Review Completed
                                                    On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Technology Transfer Design </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Technology Transfer Design Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Technology Transfer Design Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Technology
                                                    Transfer Design)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Technology Transfer Design
                                                    Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Technology Transfer Design Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Technology Transfer / Design Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Technology Transfer / Design Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>




                                        <div class="sub-head">Environment Health & Safety </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Environment Health & Safety Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Environment Health & Safety Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Environment
                                                    Health & Safety)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Environment Health & Safety
                                                    Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Environment Health & Safety Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Environment, Health & Safety Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Environment, Health & Safety Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head"> Human Resource & Administration </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by"> Human Resource & Administration Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by"> Human Resource & Administration Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Human Resource &
                                                    Administration)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments"> Human Resource & Administration
                                                    Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment"> Human Resource & Administration Attachments
                                                </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Human Resource & Administration Review Completed
                                                    By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Human Resource & Administration Review Completed
                                                    On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Information Technology </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Information Technology Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Information Technology Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Information
                                                    Technology)</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Information Technology Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Information Technology Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Information Technology Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Information Technology Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>


                                        <div class="sub-head">Project Management </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Project Management Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Project Management Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Project
                                                    Management )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Project Management Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Project Management Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Other's 1 Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Other's 1 Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="sub-head">Other's 1 (Additional Person Review From Departments If Required)
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 1 Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 1 Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 1 Department </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Manufacturing</option>
                                                    <option>Production</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Other's 1
                                                    )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Other's 1 Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Other's 1 Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Project Management Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Project Management Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="sub-head">Other's 2 (Additional Person Review From Departments If Required)
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 2 Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 2 Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 2 Department </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Manufacturing</option>
                                                    <option>Production</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Other's 2
                                                    )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Other's 2 Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Other's 2 Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Other's 3 Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Other's 2 Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="sub-head">Other's 3 (Additional Person Review From Departments If Required)
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 3 Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 3 Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 3 Department </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Manufacturing</option>
                                                    <option>Production</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Other's 3
                                                    )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Other's 3 Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Other's 3 Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Other's 3 Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Other's 3 Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="sub-head">Other's 4 (Additional Person Review From Departments If Required)
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 4 Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 4 Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 4 Department </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Manufacturing</option>
                                                    <option>Production</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Other's 4
                                                    )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Other's 4 Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Other's 4 Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Other's 4 Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Other's 4 Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="sub-head">Other's 5 (Additional Person Review From Departments If Required)
                                        </div>


                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 5 Review Required </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Yes</option>
                                                    <option>No</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 5 Person </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Pankaj</option>
                                                    <option>Manish</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="submitted by">Other's 5 Department </label>
                                                <select>
                                                    <option>--select--</option>
                                                    <option>Manufacturing</option>
                                                    <option>Production</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Impact Assessment (By Other's 5
                                                    )</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-12">
                                            <div class="group-input">
                                                <label class="mt-4" for="Audit Comments">Other's 5 Feedback</label>
                                                <textarea class="summernote" name="Disposition_Batch" id="summernote-16"></textarea>
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="closure attachment">Other's 5 Attachments </label>
                                                <div><small class="text-primary">
                                                    </small>
                                                </div>
                                                <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="File_Attachment"></div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="Attachment[]"
                                                            oninput="addMultipleFiles(this, 'Attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Reviewed by">Other's 5 Review Completed By</label>
                                                <input type="text" />
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="group-input">
                                                <label for="Approved on">Other's 5 Review Completed On</label>
                                                <input type="date" />
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">Exit
                                                </a> </button>
                                        </div>
                                    </div>
                                </div>
                            </div> --}}
                    <!-- -----------Tab-4------------ -->
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="QA Feedbacks">QA Feedbacks</label>
                                        <textarea class="summernote" name="QA_Feedbacks" id="summernote-16"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>{{ $showdata->QA_Feedbacks }}</textarea>
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="group-input">
                                        <label for="QA Attachment">QA Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div disabled class="file-attachment-list" id="QA_Attachments">
                                                @if ($showdata->QA_Attachments)
                                                    @foreach (json_decode($showdata->QA_Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgba(255, 255, 255, 0);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="QA_Attachments[]"
                                                    oninput="addMultipleFiles(this, 'QA_Attachments')"
                                                    {{ Helpers::disabledErrataFields($showdata->stage) }} multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="button-block">
                                    @if ($showdata->stage >= 6)
                                    <button type="submit" class="saveButton" disabled>Save</button>
                                    @else
                                    <button type="submit" class="saveButton">Save</button>
                                    @endif
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>


                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit
                                        </a> </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- -----------Tab-5------------ -->

                    <div id="CCForm5" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">

                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="Audit Comments">Closure Comments</label>
                                        <textarea class="summernote" name="Closure_Comments" id="summernote-16">{{$showdata->Closure_Comments}}</textarea>
                                    </div>
                                </div>


                                {{-- <div class="col-6">
                                    <div class="group-input">
                                        <label class="" for="Closure Comments">Closure Comments</label>
                                        <input type="text" name="Closure_Comments"
                                            value="{{ $showdata->Closure_Comments }}"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }} />
                                    </div>
                                </div> --}}

                                <div class="col-md-6">
                                    <div class="group-input">
                                        <label for="All Impacting Documents Corrected">
                                            All Impacting Documents Corrected <span class="text-danger"></span>
                                        </label>
                                        <select id="select-state" placeholder="Select..."
                                            name="All_Impacting_Documents_Corrected"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>
                                            <option value="">--Select--</option>
                                            <option value="Yes" @if ($showdata->All_Impacting_Documents_Corrected == 'Yes') selected @endif>Yes</option>
                                            <option value="No" @if ($showdata->All_Impacting_Documents_Corrected == 'No') selected @endif>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="group-input">
                                        <label class="mt-4" for="Remarks"> Remarks (If Any)</label>
                                        <textarea class="summernote" name="Remarks" id="summernote-16"
                                            {{ Helpers::disabledErrataFields($showdata->stage) }}>{{ $showdata->Remarks }}</textarea>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="group-input">
                                        <label for="Initial Attachments">Closure Attachment</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting documents</small></div>
                                        <div class="file-attachment-field">
                                            <div class="file-attachment-list" id="Closure_Attachments">
                                                @if ($showdata->Closure_Attachments)
                                                    @foreach (json_decode($showdata->Closure_Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark" style="background-color: rgb(243, 242, 240);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank">
                                                                <i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i>
                                                            </a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}">
                                                                <i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i>
                                                            </a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input {{ $showdata->stage == 0 || $showdata->stage == 8 ? "disabled" : "" }} type="file" id="Closure_Attachments" name="Closure_Attachments[]" oninput="addMultipleFiles(this, 'Closure_Attachments')" multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- <div class="col-12">
                                    <div class="group-input">
                                        <label for="Closure Attachments">Closure Attachments</label>
                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                documents</small>
                                        </div>
                                        <div class="file-attachment-field">
                                            <div disabled class="file-attachment-list" id="Closure_Attachments">
                                                @if ($showdata->Closure_Attachments)
                                                    @foreach (json_decode($showdata->Closure_Attachments) as $file)
                                                        <h6 type="button" class="file-container text-dark"
                                                            style="background-color: rgba(255, 255, 255, 0);">
                                                            <b>{{ $file }}</b>
                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                            <a type="button" class="remove-file" data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                        </h6>
                                                    @endforeach
                                                @endif
                                            </div>
                                            <div class="add-btn">
                                                <div>Add</div>
                                                <input type="file" id="myfile" name="Closure_Attachments[]"
                                                    oninput="addMultipleFiles(this, 'Closure_Attachments')"
                                                    {{ Helpers::disabledErrataFields($showdata->stage) }}multiple>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}




                                <div class="button-block">
                                    @if ($showdata->stage >= 6)
                                    <button type="submit" class="saveButton" disabled>Save</button>
                                    @else
                                    <button type="submit" class="saveButton">Save</button>
                                    @endif
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>


                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit
                                        </a> </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- -----------Tab-6------------ -->
                    <div id="CCForm6" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted by">Submitted By</label>
                                        <div class="static">{{ $showdata->submitted_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Submitted On</label>
                                        <div class="Date">{{ $showdata->submitted_on }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->comment }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Reviewed by">Review Completed By</label>
                                        <div class="static">{{ $showdata->review_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Approved on">Review Completed On</label>
                                        <div class="Date">{{ $showdata->review_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->review_completed_comment }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Correction Completed by">Correction Completed By</label>
                                        <div class="static">{{ $showdata->correction_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Correction Completed on">Correction Completed On</label>
                                        <div class="Date">{{ $showdata->correction_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->correction_completed_comment }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="HOD Review Complete By">HOD Review Complete By</label>
                                        <div class="static">{{ $showdata->hod_review_complete_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="HOD Review Complete By on">HOD Review Complete By On</label>
                                        <div class="Date">{{ $showdata->hod_review_complete_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->hod_review_complete_comment }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="QA Head Aproval Completed by">QA Head Aproval Completed
                                            By</label>
                                        <div class="static">{{ $showdata->qa_head_approval_completed_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="QA Head Aproval Completed on">QA Head Aproval Completed
                                            On</label>
                                        <div class="Date">{{ $showdata->qa_head_approval_completed_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->qa_head_approval_completed_comment }}</div>
                                    </div>
                                </div>



                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Sent to Opened State BY">Sent to Opened State By</label>
                                        <div class="static">{{ $showdata->sent_to_open_state_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="QA Head Aproval Completed on">Sent to Opened State
                                            On</label>
                                        <div class="Date">{{ $showdata->sent_to_open_state_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->sent_to_open_state_comment }}</div>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Reject BY">Reject By</label>
                                        <div class="static">{{ $showdata->reject_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Reject On">Reject On</label>
                                        <div class="Date">{{ $showdata->reject_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->reject_comment }}</div>
                                    </div>
                                </div>


                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Cancel BY">Cancel By</label>
                                        <div class="static">{{ $showdata->cancel_by }}</div>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Cancel On">Cancel On</label>
                                        <div class="Date">{{ $showdata->cancel_on }}</div>
                                    </div>
                                </div>

                                <div class="col-lg-4">
                                    <div class="group-input">
                                        <label for="Submitted on">Comment</label>
                                        <div class="Date">{{ $showdata->cancel_comment }}</div>
                                    </div>
                                </div>


                                <div class="button-block">
                                    @if ($showdata->stage >= 6)
                                    <button type="submit" class="saveButton" disabled>Save</button>
                                    @else
                                    <button type="submit" class="saveButton">Save</button>
                                    @endif
                                    <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                    <button type="button" class="nextButton" onclick="nextStep()">Next</button>


                                    <button type="button"> <a class="text-white"
                                            href="{{ url('rcms/qms-dashboard') }}">Exit
                                        </a> </button>
                                </div>
                            </div>
                        </div>
                    </div>
            </form>

        </div>
    </div>

    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stage', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input type="comment" name="comment" >
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="review-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stage', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="input_width" type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="correction-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stage', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="input_width" type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="hod-rewieve-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stage', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="input_width" type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="qa-head-approval-model">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stage', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment</label>
                            <input class="input_width" type="comment" name="comment">
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.cancel', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="reject-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stagereject', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input class="input_width" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="more-info-required-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stagereject', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input class="input_width" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="more-inform-required-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stagereject', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input class="input_width" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
            </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="send-to-opened-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('errata.stagereject', $errata_id) }}" method="POST">
                    @csrf
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">E-Signature</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="mb-3 text-justify">
                            Please select a meaning and a outcome for this task and enter your username
                            and password for this task. You are performing an electronic signature,
                            which is legally binding equivalent of a hand written signature.
                        </div>
                        <div class="group-input">
                            <label for="username">Username <span class="text-danger">*</span></label>
                            <input class="input_width" type="text" name="username" required>
                        </div>
                        <div class="group-input">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input class="input_width" type="password" name="password" required>
                        </div>
                        <div class="group-input">
                            <label for="comment">Comment<span class="text-danger">*</span></label>
                            <input class="input_width" type="comment" name="comment" required>
                        </div>
                    </div>

                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="submit" data-bs-dismiss="modal">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
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

        .input_width {
            width: 100%;
            border-radius: 5px;
            margin-bottom: 11px;
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

                // Update current step
                currentStep--;
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#Details-add').click(function(e) {
                function generateTableRow(serialNumber) {
                    var data = @json($grid_Data);
                    var html = '';
                    html += '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        '<td><input type="text" name="details[' + serialNumber +
                        '][ListOfImpactingDocument]"></td>' +
                        '<td><input type="text" name="details[' + serialNumber + '][PreparedBy]"></td>' +
                        '<td><input type="text" name="details[' + serialNumber + '][CheckedBy]"></td>' +
                        '<td><input type="text" name="details[' + serialNumber + '][ApprovedBy]"></td>' +
                        '<td><button type="text" class="removeRowBtn" ">Remove</button></td>' +

                        '</tr>';
                    for (var i = 0; i < data.length; i++) {
                        html += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                    }

                    html += '</select></td>' +
                        '</tr>';

                    return html;
                }

                var tableBody = $('#Details-table tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script>

    <script>
        $(document).on('click', '.removeRowBtn', function() {
            $(this).closest('tr').remove();
        })
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
@endsection
