@extends('frontend.rcms.layout.main_rcms')
@section('rcms_container')
    @php
        $users = DB::table('users')->select('id', 'name')->get();
    @endphp

    <style>
        #step-form>div {
            display: none
        }

        #step-form>div:nth-child(1) {
            display: block;
        }

        .hide-input {
            display: none !important;
        }

        .remove-file {
            cursor: pointer;
        }
    </style>
    <style>
        header .header_rcms_bottom {
            display: none;
        }

        .calenderauditee {
            position: relative;
        }

        .new-date-data-field .input-date input.hide-input {
            position: absolute;
            top: 0;
            left: 0;
            opacity: 0;
        }

        .new-date-data-field input {
            border: 1px solid grey;
            border-radius: 5px;
            padding: 5px 15px;
            display: block;
            width: 100%;
            background: white;
        }

        .calenderauditee input::-webkit-calendar-picker-indicator {
            width: 100%;
        }

        .form-control {
            margin-bottom: 20px;
        }

        
        iframe#\:2\.container {
        /* display: none; */
        height: 0px !important;
        background: #4274da !important;
    }
    img.goog-te-gadget-icon {
        display: none;
    }
    .skiptranslate.goog-te-gadget {
        margin-bottom: 0px;
    }
    div#google_translate_element {
        border: none;
    }
    .VIpgJd-ZVi9od-aZ2wEe-wOHMyf.VIpgJd-ZVi9od-aZ2wEe-wOHMyf-ti6hGc {
        display: none;
    }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('swal'))
        <script>
            swal("{{ Session::get('swal')['title'] }}", "{{ Session::get('swal')['message'] }}",
                "{{ Session::get('swal')['type'] }}")
        </script>
    @endif
    <script>
        function otherController(value, checkValue, blockID) {
            let block = document.getElementById(blockID)
            let blockTextarea = block.getElementsByTagName('textarea')[0];
            let blockLabel = block.querySelector('label span.text-danger');
            if (value === checkValue) {
                blockLabel.classList.remove('d-none');
                blockTextarea.setAttribute('required', 'required');
            } else {
                blockLabel.classList.add('d-none');
                blockTextarea.removeAttribute('required');
            }
        }
    </script>

    <div id="rcms_form-head">
        <div class="container-fluid">
            <div class="inner-block">
                <div class="slogan">
                    <strong>Site Division / Project </strong>:
                    {{ Helpers::getDivisionName($data->division_id) }} / Query Management
                </div>
            </div>
        </div>
    </div>

    <!-- /* Change Control View Data Fields */ -->

    <div id="change-control-view">
        <div class="container-fluid">

            <div class="inner-block state-block">
                <div class="language-sleect d-flex" style="align-items: center; gap: 20px; margin-left: 20px;">
                    <div>Select Language </div>
                <div class="main-head" id="google_translate_element"></div>
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
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">

                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => $data->division_id])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                            $cftRolesAssignUsers = collect($userRoleIds); //->contains(fn ($roleId) => $roleId >= 22 && $roleId <= 33);
                            $cftUsers = DB::table('query_management_cfts')
                                ->where(['query_management_id' => $data->id])
                                ->first();

                            // Define the column names
                            $columns = [
                                'Production_Table_Person',
                                'Production_Injection_Person',
                                'ResearchDevelopment_person',
                                'Store_person',
                                'Quality_Control_Person',
                                'QualityAssurance_person',
                                'RegulatoryAffair_person',
                                'ProductionLiquid_person',
                                'Microbiology_person',
                                'Engineering_person',
                                'ContractGiver_person',
                                'Environment_Health_Safety_person',
                                'Human_Resource_person',
                                'CorporateQualityAssurance_person',
                            ];

                            // Initialize an array to store the values
                            $valuesArray = [];

                            // Iterate over the columns and retrieve the values
                            foreach ($columns as $column) {
                                $value = $cftUsers->$column;
                                // Check if the value is not null and not equal to 0
                                if ($value !== null && $value != 0) {
                                    $valuesArray[] = $value;
                                }
                            }
                            $cftCompleteUser = DB::table('query_management_cft_responses')
                                ->whereIn('status', ['In-progress', 'Completed'])
                                ->where('query_management_id', $data->id)
                                ->where('cft_user_id', Auth::user()->id)
                                ->whereNull('deleted_at')
                                ->first();
                        @endphp

                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/query-managements-audit-trail', $data->id) }}"> Audit Trail </a> </button>

                        @if ($data->stage == 1 && Helpers::check_roles($data->division_id, 'Change Control', 3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Submit
                            </button>
                        @elseif($data->stage == 2 && Helpers::check_roles($data->division_id, 'Change Control', 4))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Query Review Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information Required
                            </button>
                        @elseif($data->stage == 3 && Helpers::check_roles($data->division_id, 'Change Control', 7))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Acknowledgement Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 4 && Helpers::check_roles($data->division_id, 'Change Control', 7))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Admin 2 Review Complete
                            </button>
                        @elseif($data->stage == 5 && Helpers::check_roles($data->division_id, 'Change Control', 7))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                CFT Review Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                CFT Review Not Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Information Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button>
                        @elseif($data->stage == 6 && (in_array(5, $userRoleIds) || in_array(18, $userRoleIds) || in_array(Auth::user()->id, $valuesArray)))
                            @if (!$cftCompleteUser)
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                    More Information Required
                                </button>
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                    CFT Review Complete
                                </button>
                            @endif
                        @elseif($data->stage == 7 && Helpers::check_roles($data->division_id, 'Change Control', 39))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Admin 2 Update Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#HOD-modal">
                                Send to HOD
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#Initiator-modal">
                                Send to Initiator
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#">
                                Send to Admin 1
                            </button>
                        @elseif ($data->stage == 8 && Helpers::check_roles($data->division_id, 'Change Control', 3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#rejection-modal">
                                More Info Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Admin 1 Update Complete                                           
                            </button>
                        @elseif ($data->stage == 9 && Helpers::check_roles($data->division_id, 'Change Control', 3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal" >
                                Response Sent                                            
                            </button>
                        @elseif ($data->stage == 10 && Helpers::check_roles($data->division_id, 'Change Control', 3))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Pending Acknowledgement Complete
                            </button>
                        @else
                    @endif
                        <a class="button_theme1 text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit
                        </a>
                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Pending Query Review</div>
                            @else
                                <div class="">Pending Query Review</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Acknowledgement from Admin 2</div>
                            @else
                                <div class="">Pending Acknowledgement from Admin 2</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Admin 2 Review</div>
                            @else
                                <div class="">Pending Admin 2 Review</div>
                            @endif
                                        
                            @if ($data->stage >= 5)
                                <div class="active">Pending HOD Review</div>
                            @else
                                <div class="">Pending HOD Review</div>
                            @endif
                                        
                            @if ($data->stage >= 6)
                                <div class="active">Pending CFT Review</div>
                            @else
                                <div class="">Pending CFT Review</div>
                            @endif

                            @if ($data->stage >= 7)
                                <div class="active">Pending Admin 2 Update</div>
                            @else
                                <div class="">Pending Admin 2 Update</div>
                            @endif

                            @if ($data->stage >= 8)
                                <div class="active">Pending Admin 1 Update</div>
                            @else
                                <div class="">Pending Admin 1 Update</div>
                            @endif

                            @if ($data->stage >= 9)
                                <div class="active">Pending Response for Stakeholders</div>
                            @else
                                <div class="">Pending Response for Stakeholders</div>
                            @endif

                            @if ($data->stage >= 10)
                                <div class="active">Pending Acknowledgement from Stakeholders</div>
                            @else
                                <div class="">Pending Acknowledgement from Stakeholders</div>
                            @endif
                            
                            @if ($data->stage >= 11)
                                <div class="active bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <div class="control-list">
                @php
                    $users = DB::table('users')->get();
                @endphp
                <div id="change-control-fields">
                    <div class="container-fluid">
                        <!-- Tab links -->
                        <div class="cctab">
                            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">General
                                Information</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm2')">Admin 1 Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Admin 2 Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm4')">HOD Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm5')">CFT Review</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Outcome</button>
                            <button class="cctablinks" onclick="openCity(event, 'CCForm7')">Activity Log</button>
                        </div>

                        <form action="{{ route('query-managements-update', $data->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf

                            <div id="step-form">
                                <div id="CCForm1" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="sub-head">
                                                Query Identification
                                            </div>
                                            <div class="col-6">
                                                <div class="group-input">
                                                    <label for="RLS Record Number"><b>Query ID</b></label>
                                                    <input type="text" disabled
                                                        value="{{ Helpers::getDivisionName($data->division_id) }}/QM/{{ date('Y') }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Division Code"><b>Division Code</b></label>
                                                    <input type="text" disabled
                                                        value="{{ Helpers::getDivisionName($data->division_id) }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Initiator"><b>Submitter Name</b></label>
                                                    <input disabled type="text"
                                                        value="{{ Helpers::getInitiatorName($data->initiator_id) }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input ">
                                                    <label for="Date Due"><b>Submission Date</b></label>
                                                    <input type="text" disabled
                                                        value="{{ Helpers::getdateFormat($data->initiation_date) }}">
                                                </div>
                                            </div>

                                            <div class="col-lg-6 new-date-data-field">
                                                <div class="group-input input-date">
                                                    <label for="Due Date"> Due Date <span
                                                            class="text-danger">*</span></label>
                                                    <div><small class="text-primary">If revising Due Date, kindly mention
                                                            revision
                                                            reason in "Due Date Extension Justification" data field.</small>
                                                    </div>
                                                    <div class="calenderauditee">
                                                        <input disabled type="text" id="due_date" readonly
                                                            placeholder="DD-MMM-YYYY"
                                                            value="{{ Helpers::getdateFormat($data->due_date) }}" />
                                                        <input type="date" name="due_date"
                                                            value="{{ $data->due_date }}"
                                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                            class="hide-input"
                                                            oninput="handleDateInput(this, 'due_date')" />
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                $(document).ready(function() {
                                                    function toggleRiskAssessmentAndJustification() {
                                                        var riskAssessmentRequired = $('#risk_assessment_required').val();

                                                        // Toggle Risk Assessment Button
                                                        if (riskAssessmentRequired === 'yes') {
                                                            $('#riskAssessmentButton').show();
                                                            $('#justification_div').hide(); // Hide justification when "Yes" is selected
                                                        } else if (riskAssessmentRequired === 'no') {
                                                            $('#riskAssessmentButton').hide();
                                                            $('#justification_div').show(); // Show justification when "No" is selected
                                                        } else {
                                                            $('#riskAssessmentButton').hide();
                                                            $('#justification_div').hide(); // Hide everything if nothing is selected
                                                        }
                                                    }

                                                    toggleRiskAssessmentAndJustification(); // Initial call to set the correct state

                                                    // Call the function on dropdown change
                                                    $('#risk_assessment_required').change(function() {
                                                        toggleRiskAssessmentAndJustification();
                                                    });
                                                });
                                            </script>

                                            <div class="sub-head">
                                                Query Details
                                            </div>

                                            <div class="col-12">
                                                <div class="group-input">
                                                    <label for="Short Description">Short Description<span
                                                            class="text-danger">*</span></label><span id="rchars"
                                                        class="text-primary">255 </span><span class="text-primary">
                                                        characters
                                                        remaining</span>
                                                    <div class="relative-container">
                                                        <input id="docname" type="text" name="short_description"
                                                            maxlength="255" value="{{ $data->short_description }}"
                                                            required>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Related Records">Reference Document</label>
                                                    <select multiple id="refrenece_document" name="reference_document[]"
                                                        placeholder="Select Reference Records">

                                                        @if (!empty($preItem))
                                                            @foreach ($preItem as $new)
                                                                @php
                                                                    $recordValue =
                                                                        Helpers::getDivisionName($new->division_id) .
                                                                        '/QM/' .
                                                                        date('Y') .
                                                                        '/' .
                                                                        Helpers::recordFormat($new->record);
                                                                    $selected = in_array(
                                                                        $recordValue,
                                                                        explode(',', $data->reference_document),
                                                                    )
                                                                        ? 'selected'
                                                                        : '';
                                                                @endphp
                                                                <option value="{{ $recordValue }}" {{ $selected }}>
                                                                    {{ $recordValue }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Volume</label>
                                                    <div class="relative-container">
                                                        <select id="query_volume" name="query_volume">
                                                            <option value="">Select Volume</option>
                                                            <option value="1"
                                                                @if ($data->query_volume == 1) selected @endif>1
                                                            </option>
                                                            <option value="2"
                                                                @if ($data->query_volume == 2) selected @endif>2
                                                            </option>
                                                            <option value="3"
                                                                @if ($data->query_volume == 3) selected @endif>3
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Query Medium</label>
                                                    <div class="relative-container">
                                                        <select id="query_medium" name="query_medium">
                                                            <option value="">Select Query Medium</option>
                                                            <option value="Mail"
                                                                @if ($data->query_medium == 'Mail') selected @endif>Mail
                                                            </option>
                                                            <option value="Courier"
                                                                @if ($data->query_medium == 'Courier') selected @endif>Courier
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="others">Mail attachment</label>
                                                    <div><small class="text-primary">Please Attach all relevant or
                                                            supporting documents</small></div>
                                                    <div class="file-attachment-field">
                                                        <div disabled class="file-attachment-list" id="mail_attachment">
                                                            @if ($data->mail_attachment)
                                                                @foreach (json_decode($data->mail_attachment) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">

                                                            <div>Add</div>
                                                            <input type="file" id="myfile" name="mail_attachment[]"
                                                                oninput="addMultipleFiles(this, 'mail_attachment')"
                                                                multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Assigned Reviewer</label>
                                                    <div class="relative-container">
                                                        <select id="assign_to" name="assign_to">
                                                            <option value="">Select Assigned Reviewer</option>
                                                            @if (!empty($users))
                                                                @foreach ($users as $item)
                                                                    <option value="{{ $item->id }}"
                                                                        @if ($data->assign_to == $item->id) selected @endif>
                                                                        {{ $item->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="sub-head">
                                                Contact Information
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Contact Person Mail ID</label>
                                                    <div class="relative-container">
                                                        <input id="contact_mailId" type="text" name="contact_mailId"
                                                            value="{{ $data->contact_mailId }}">
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Contact Person Phone No.</label>
                                                    <div class="relative-container">
                                                        <input id="contact_mobile" type="text" name="contact_mobile"
                                                            value="{{ $data->contact_mobile }}">
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Affiliation</label>
                                                    <div class="relative-container">
                                                        <input id="affiliation" type="text" name="affiliation"
                                                            value="{{ $data->affiliation }}">
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="others">Initial attachment</label>
                                                    <div><small class="text-primary">Please Attach all relevant or
                                                            supporting documents</small></div>
                                                    <div class="file-attachment-field">
                                                        <div disabled class="file-attachment-list"
                                                            id="initial_attachment">
                                                            @if ($data->initial_attachment)
                                                                @foreach (json_decode($data->initial_attachment) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">

                                                            <div>Add</div>
                                                            <input type="file" id="myfile"
                                                                name="initial_attachment[]"
                                                                oninput="addMultipleFiles(this, 'initial_attachment')"
                                                                multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">Exit</a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm2" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Admin 1 Review
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-comments">Admin 1 Comments</label>
                                            <div class="relative-container">
                                                <textarea name="reviewer_comment">{{ $data->reviewer_comment }}</textarea>
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="others">Admin 1 attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting
                                                        documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="reviewer_attachment">
                                                        @if ($data->reviewer_attachment)
                                                            @foreach (json_decode($data->reviewer_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-file-name="{{ $file }}"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="reviewer_attachment[]"
                                                            oninput="addMultipleFiles(this, 'reviewer_attachment')"
                                                            multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">
                                                    Exit </a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm3" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Admin 2 Review
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-comments">Admin 2 Comments</label>
                                            <div class="relative-container">
                                                <textarea name="admin1_comment">{{ $data->admin1_comment }}</textarea>
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="others">Admin 2 attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting
                                                        documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="admin1_attachment">
                                                        @if ($data->admin1_attachment)
                                                            @foreach (json_decode($data->admin1_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-file-name="{{ $file }}"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="admin1_attachment[]"
                                                            oninput="addMultipleFiles(this, 'admin1_attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">
                                                    Exit </a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm4" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            HOD Review
                                        </div>
                                        <div class="group-input">
                                            <label for="qa-eval-comments">HOD Comments</label>
                                            <div class="relative-container">
                                                <textarea name="HOD_comment">{{ $data->HOD_comment }}</textarea>
                                                @component('frontend.forms.language-model')
                                                @endcomponent
                                            </div>
                                        </div>

                                        <div class="col-lg-12">
                                            <div class="group-input">
                                                <label for="others">HOD attachment</label>
                                                <div><small class="text-primary">Please Attach all relevant or supporting
                                                        documents</small></div>
                                                <div class="file-attachment-field">
                                                    <div disabled class="file-attachment-list" id="HOD_attachment">
                                                        @if ($data->HOD_attachment)
                                                            @foreach (json_decode($data->HOD_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-file-name="{{ $file }}"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input type="file" id="myfile" name="HOD_attachment[]"
                                                            oninput="addMultipleFiles(this, 'HOD_attachment')" multiple>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">
                                                    Exit </a> </button>
                                        </div>
                                    </div>
                                </div>



                                <div id="CCForm5" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">

                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @php
                                                $userRoles = DB::table('user_roles')
                                                    ->where([
                                                        'q_m_s_roles_id' => 50,
                                                        'q_m_s_divisions_id' => $data->division_id,
                                                    ])
                                                    ->get();
                                                $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                            @endphp

                                            <div class="sub-head">
                                                Quality Assurance
                                            </div>

                                            <script>
                                                $(document).ready(function() {

                                                    @if ($data1->Quality_Assurance_Review !== 'yes')
                                                        $('.QualityAssurance').hide();

                                                        $('[name="Quality_Assurance_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.QualityAssurance').show();
                                                                $('.QualityAssurance span').show();
                                                            } else {
                                                                $('.QualityAssurance').hide();
                                                                $('.QualityAssurance span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance"> Quality Assurance Review Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Quality_Assurance_Review" id="Quality_Assurance_Review" @if($data->stage == 6) disabled @endif>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 26,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance notification">Quality Assurance Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Quality_Assurance_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="QualityAssurance_person"
                                                            class="QualityAssurance_person" id="QualityAssurance_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}" @if ($user->name == $data1->QualityAssurance_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance assessment">Impact Assessment (By Quality Assurance) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Quality_Assurance_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Quality_Assurance_Review == 'yes' && $data->stage == 6) required @endif class="summernote QualityAssurance_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->QualityAssurance_person) && Auth::user()->name != $data1->QualityAssurance_person)) readonly @endif name="QualityAssurance_assessment" id="summernote-17">{{ $data1->QualityAssurance_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance feedback">Quality Assurance Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Quality_Assurance_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote QualityAssurance_feedback" @if (
                                                            $data->stage == 5 || (isset($data1->QualityAssurance_person) && Auth::user()->name != $data1->QualityAssurance_person)) readonly @endif
                                                            name="QualityAssurance_feedback" id="summernote-18" @if ($data1->Quality_Assurance_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->QualityAssurance_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance attachment">Quality Assurance Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Quality_Assurance_attachment">
                                                                @if ($data1->Quality_Assurance_attachment)
                                                                    @foreach (json_decode($data1->Quality_Assurance_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Quality_Assurance_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Quality_Assurance_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance Completed By">Quality Assurance Review Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->QualityAssurance_by }}"
                                                            name="QualityAssurance_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="QualityAssurance_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 QualityAssurance">
                                                    <div class="group-input input-date">
                                                        <label for="Quality Assurance Completed On">Quality Assurance Review Completed On</label>
                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date -->
                                                            <input type="text" id="QualityAssurance_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->QualityAssurance_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input readonly type="date" name="QualityAssurance_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'QualityAssurance_on')" />
                                                        </div>
                                                        @error('QualityAssurance_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Quality_Assurance_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('QualityAssurance_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance">Quality Assurance Review Required ?</label>
                                                        <select name="Quality_Assurance_Review" id="Quality_Assurance_Review" disabled>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Quality_Assurance_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 26,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance notification">Quality Assurance Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="QualityAssurance_person" disabled id="QualityAssurance_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->QualityAssurance_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 QualityAssurance">
                                                        <div class="group-input">
                                                            <label for="Quality Assurance assessment">Impact Assessment (By Quality Assurance)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="QualityAssurance_assessment" id="summernote-17">{{ $data1->QualityAssurance_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 QualityAssurance">
                                                        <div class="group-input">
                                                            <label for="Quality Assurance feedback">Quality Assurance Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="QualityAssurance_feedback" id="summernote-18">{{ $data1->QualityAssurance_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 QualityAssurance">
                                                        <div class="group-input">
                                                            <label for="Quality Assurance assessment">Impact Assessment (By Quality Assurance)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="QualityAssurance_assessment" id="summernote-17">{{ $data1->QualityAssurance_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 QualityAssurance">
                                                        <div class="group-input">
                                                            <label for="Quality Assurance feedback">Quality Assurance Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="QualityAssurance_feedback" id="summernote-18">{{ $data1->QualityAssurance_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance attachment">Quality Assurance Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Quality_Assurance_attachment">
                                                                @if ($data1->Quality_Assurance_attachment)
                                                                    @foreach (json_decode($data1->Quality_Assurance_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Quality_Assurance_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Quality_Assurance_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 QualityAssurance">
                                                    <div class="group-input">
                                                        <label for="Quality Assurance Completed By">Quality Assurance Review Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->QualityAssurance_by }}"
                                                            name="QualityAssurance_by" id="QualityAssurance_by">


                                                    </div>
                                                </div>



                                                <div class="col-lg-6 QualityAssurance">
                                                    <div class="group-input input-date">
                                                        <label for="Quality Assurance Completed On">Quality Assurance Review Completed On</label>
                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date -->
                                                            <input type="text" id="QualityAssurance_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->QualityAssurance_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input readonly type="date" name="QualityAssurance_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'QualityAssurance_on')" />
                                                        </div>
                                                        @error('QualityAssurance_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                            @endif


                                            <div class="sub-head">
                                                Production (Tablet/Capsule/Powder)
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Production_Table_Review !== 'yes')
                                                        $('.productionTable').hide();

                                                        $('[name="Production_Table_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.productionTable').show();
                                                                $('.productionTable span').show();
                                                            } else {
                                                                $('.productionTable').hide();
                                                                $('.productionTable span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Tablet"> Production Tablet/Capsule/Powder Required? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Production_Table_Review" id="Production_Table_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Production_Table_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Production_Table_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Production_Table_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 51,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet notification">Production Tablet/Capsule/Powder Person<span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->Production_Table_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Production_Table_Person"
                                                            class="Production_Table_Person" id="Production_Table_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Production_Table_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet assessment">Impact Assessment(By Production
                                                            (Tablet/Capsule/Powder))<span id="asteriskPT1"
                                                                style="display: {{ $data1->Production_Table_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Production_Table_Review == 'yes' && $data->stage == 6) required @endif class="summernote Production_Table_Assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->Production_Table_Person) && Auth::user()->name != $data1->Production_Table_Person)) readonly @endif name="Production_Table_Assessment" id="summernote-17">{{ $data1->Production_Table_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet feedback">Production Tablet/Capsule/Powder Feedback<span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->Production_Table_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Production_Table_Feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->Production_Table_Person) && Auth::user()->name != $data1->Production_Table_Person)) readonly @endif
                                                            name="Production_Table_Feedback" id="summernote-18" @if ($data1->Production_Table_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Production_Table_Feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet attachment">Production Tablet/Capsule/Powder Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Production_Table_Attachment">
                                                                @if ($data1->Production_Table_Attachment)
                                                                    @foreach (json_decode($data1->Production_Table_Attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Production_Table_Attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Production_Table_Attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet Completed By">Production Tablet/Capsule/Powder Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Production_Table_By }}"
                                                            name="Production_Table_By"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Production_Table_By">


                                                    </div>
                                                </div>

                                                <div class="col-6 mb-3 productionTable new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Production Tablet Completed On">Production Tablet/Capsule/Powder Completed
                                                            On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Production_Table_On" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Production_Table_On) }}" />
                                                            <input readonly type="date" name="Production_Table_On"
                                                                min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'Production_Table_On')" />
                                                        </div>
                                                        @error('Production_Table_On')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Production_Table_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Production_Table_Person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Table_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Table_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Tablet">Production Tablet/Capsule/Powder Required?</label>
                                                        <select name="Production_Table_Review" disabled id="Production_Table_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Production_Table_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Production_Table_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Production_Table_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 51,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet notification">Production Tablet/Capsule/Powder Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="Production_Table_Person" disabled id="Production_Table_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Production_Table_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 productionTable">
                                                        <div class="group-input">
                                                            <label for="Production Tablet assessment">Impact Assessment(By Production
                                                                (Tablet/Capsule/Powder))
                                                                <!-- <span
                                                                                                                                                                            id="asteriskInvi12" style="display: none"
                                                                                                                                                                            class="text-danger">*</span> -->
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Production_Table_Assessment" id="summernote-17">{{ $data1->Production_Table_Assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionTable">
                                                        <div class="group-input">
                                                            <label for="Production Tablet feedback">Production Tablet/Capsule/Powder Feedback
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Production_Table_Feedback" id="summernote-18">{{ $data1->Production_Table_Feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 productionTable">
                                                        <div class="group-input">
                                                            <label for="Production Tablet assessment">Impact Assessment(By Production
                                                                (Tablet/Capsule/Powder))
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Production_Table_Assessment" id="summernote-17">{{ $data1->Production_Table_Assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionTable">
                                                        <div class="group-input">
                                                            <label for="Production Tablet feedback">Production Tablet/Capsule/Powder Feedback
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Production_Table_Feedback" id="summernote-18">{{ $data1->Production_Table_Feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet attachment">Production Tablet/Capsule/Powder Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Production_Table_Attachment">
                                                                @if ($data1->Production_Table_Attachment)
                                                                    @foreach (json_decode($data1->Production_Table_Attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Production_Table_Attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Production_Table_Attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 productionTable">
                                                    <div class="group-input">
                                                        <label for="Production Tablet Completed By">Production Tablet/Capsule/Powder Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Production_Table_By }}"
                                                            name="Production_Table_By" id="Production_Table_By">


                                                    </div>
                                                </div>
                                                <div class="col-6 mb-3 productionTable new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Production Tablet Completed On">Production Tablet/Capsule/Powder Completed
                                                            On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Production_Table_On" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Production_Table_On) }}" />
                                                            <input readonly type="date" name="Production_Table_On"
                                                                min="{{ \Carbon\Carbon::now()->format('d-M-Y') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'Production_Table_On')" />
                                                        </div>
                                                        @error('Production_Table_On')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif


                                            <div class="sub-head">
                                                Production (Liquid/Ointment)
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->ProductionLiquid_Review !== 'yes')
                                                        $('.productionLiquid').hide();

                                                        $('[name="ProductionLiquid_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.productionLiquid').show();
                                                                $('.productionLiquid span').show();
                                                            } else {
                                                                $('.productionLiquid').hide();
                                                                $('.productionLiquid span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Liquid"> Production Liquid/Ointment Required? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="ProductionLiquid_Review" id="ProductionLiquid_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 52,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid notification">Production Liquid/Ointment Person <span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->ProductionLiquid_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="ProductionLiquid_person"
                                                            class="ProductionLiquid_person" id="ProductionLiquid_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ProductionLiquid_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid assessment">Impact Assessment(By Production Liquid/Ointment)
                                                            <span id="asteriskPT1"
                                                                style="display: {{ $data1->ProductionLiquid_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->ProductionLiquid_Review == 'yes' && $data->stage == 6) required @endif class="summernote ProductionLiquid_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->ProductionLiquid_person) && Auth::user()->name != $data1->ProductionLiquid_person)) readonly @endif name="ProductionLiquid_assessment" id="summernote-17">{{ $data1->ProductionLiquid_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid feedback">Production Liquid/Ointment Feedback <span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->ProductionLiquid_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote ProductionLiquid_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->ProductionLiquid_person) && Auth::user()->name != $data1->ProductionLiquid_person)) readonly @endif
                                                            name="ProductionLiquid_feedback" id="summernote-18" @if ($data1->ProductionLiquid_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->ProductionLiquid_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid attachment">Production Liquid/Ointment Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ProductionLiquid_attachment">
                                                                @if ($data1->ProductionLiquid_attachment)
                                                                    @foreach (json_decode($data1->ProductionLiquid_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="ProductionLiquid_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'ProductionLiquid_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="col-md-6 mb-3 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid Completed By">Production Liquid/Ointment Completed By</label>
                                                        <input readonly type="text" value="{{ $data1->ProductionLiquid_by }}"
                                                            name="ProductionLiquid_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="ProductionLiquid_by">


                                                    </div>
                                                </div>

                                                <div class="col-lg-6 productionLiquid new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Production Liquid Completed On">Production Liquid/Ointment Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="ProductionLiquid_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ProductionLiquid_on) }}" />
                                                            <input readonly type="date" name="ProductionLiquid_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'ProductionLiquid_on')" />
                                                        </div>
                                                        @error('ProductionLiquid_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('ProductionLiquid_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('ProductionLiquid_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Liquid">Production Liquid/Ointment Required?</label>
                                                        <select name="ProductionLiquid_Review" disabled id="ProductionLiquid_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ProductionLiquid_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 52,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid notification">Production Liquid/Ointment Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="ProductionLiquid_person" disabled id="ProductionLiquid_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ProductionLiquid_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 productionLiquid">
                                                        <div class="group-input">
                                                            <label for="Production Liquid assessment">Impact Assessment(By Production Liquid/Ointment)
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ProductionLiquid_assessment" id="summernote-17">{{ $data1->ProductionLiquid_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionLiquid">
                                                        <div class="group-input">
                                                            <label for="Production Liquid feedback">Impact Assessment(By Production
                                                                Liquid/Ointment)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ProductionLiquid_feedback" id="summernote-18">{{ $data1->ProductionLiquid_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 productionLiquid">
                                                        <div class="group-input">
                                                            <label for="Production Liquid assessment">Impact Assessment(By Production
                                                                Liquid/Ointment)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ProductionLiquid_assessment" id="summernote-17">{{ $data1->ProductionLiquid_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionLiquid">
                                                        <div class="group-input">
                                                            <label for="Production Liquid feedback">Production Liquid/Ointment Feedback </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ProductionLiquid_feedback" id="summernote-18">{{ $data1->ProductionLiquid_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid attachment">Production Liquid/Ointment Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ProductionLiquid_attachment">
                                                                @if ($data1->ProductionLiquid_attachment)
                                                                    @foreach (json_decode($data1->ProductionLiquid_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="ProductionLiquid_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'ProductionLiquid_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 productionLiquid">
                                                    <div class="group-input">
                                                        <label for="Production Liquid Completed By">Production Liquid/Ointment Completed By</label>
                                                        <input readonly type="text" value="{{ $data1->ProductionLiquid_by }}"
                                                            name="ProductionLiquid_by" id="ProductionLiquid_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 productionLiquid new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Production Liquid Completed On">
                                                            Production Liquid/Ointment Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="ProductionLiquid_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ProductionLiquid_on) }}" />
                                                            <input readonly type="date" name="ProductionLiquid_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value="" class="hide-input"
                                                                oninput="handleDateInput(this, 'ProductionLiquid_on')" />
                                                        </div>
                                                        @error('ProductionLiquid_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif




                                            <div class="sub-head">
                                                Production Injection
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Production_Injection_Review !== 'yes')
                                                        $('.productionInjection').hide();

                                                        $('[name="Production_Injection_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.productionInjection').show();
                                                                $('.productionInjection span').show();
                                                            } else {
                                                                $('.productionInjection').hide();
                                                                $('.productionInjection span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Injection"> Production Injection Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Production_Injection_Review" id="Production_Injection_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Production_Injection_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Production_Injection_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Production_Injection_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 53,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection notification">Production Injection Person <span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->Production_Injection_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Production_Injection_Person"
                                                            class="Production_Injection_Person" id="Production_Injection_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Production_Injection_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection assessment">Impact Assessment (By Production Injection) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Production_Injection_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Production_Injection_Review == 'yes' && $data->stage == 6) required @endif class="summernote Production_Injection_Assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->Production_Injection_Person) && Auth::user()->name != $data1->Production_Injection_Person)) readonly @endif name="Production_Injection_Assessment" id="summernote-17">{{ $data1->Production_Injection_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection feedback">Production Injection Feedback <span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->Production_Injection_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Production_Injection_Feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->Production_Injection_Person) && Auth::user()->name != $data1->Production_Injection_Person)) readonly @endif
                                                            name="Production_Injection_Feedback" id="summernote-18" @if ($data1->Production_Injection_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Production_Injection_Feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection attachment">Production Injection Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Production_Injection_Attachment">
                                                                @if ($data1->Production_Injection_Attachment)
                                                                    @foreach (json_decode($data1->Production_Injection_Attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Production_Injection_Attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Production_Injection_Attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection Completed By">Production Injection Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Production_Injection_By }}"
                                                            name="Production_Injection_By"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Production_Injection_By">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production_Injection_On">Production Injection Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Production_Injection_On_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Production_Injection_On) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Production_Injection_On" name="Production_Injection_On"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Production_Injection_On)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Production_Injection_On_display')" />
                                                        </div>

                                                        @error('Production_Injection_On')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Production_Injection_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Production_Injection_Person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Production Injection">Production Injection Required ?</label>
                                                        <select name="Production_Injection_Review" disabled id="Production_Injection_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Production_Injection_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Production_Injection_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Production_Injection_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 53,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection notification">Production Injection Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="Production_Injection_Person" disabled id="Production_Injection_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Production_Injection_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 productionInjection">
                                                        <div class="group-input">
                                                            <label for="Production Injection assessment">Impact Assessment (By Production Injection)
                                                                <!-- <span
                                                                                                                                                                            id="asteriskInvi12" style="display: none"
                                                                                                                                                                            class="text-danger">*</span> -->
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Production_Injection_Assessment" id="summernote-17">{{ $data1->Production_Injection_Assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionInjection">
                                                        <div class="group-input">
                                                            <label for="Production Injection feedback">Production Injection Feedback
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Production_Injection_Feedback" id="summernote-18">{{ $data1->Production_Injection_Feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 productionInjection">
                                                        <div class="group-input">
                                                            <label for="Production Injection assessment">Impact Assessment (By Production Injection)
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Production_Injection_Assessment" id="summernote-17">{{ $data1->Production_Injection_Assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 productionInjection">
                                                        <div class="group-input">
                                                            <label for="Production Injection feedback">Production Injection Feedback
                                                            </label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Production_Injection_Feedback" id="summernote-18">{{ $data1->Production_Injection_Feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection attachment">Production Injection Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Production_Injection_Attachment">
                                                                @if ($data1->Production_Injection_Attachment)
                                                                    @foreach (json_decode($data1->Production_Injection_Attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Production_Injection_Attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Production_Injection_Attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production Injection Completed By">Production Injection Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Production_Injection_By }}"
                                                            name="Production_Injection_By" id="Production_Injection_By">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 productionInjection">
                                                    <div class="group-input">
                                                        <label for="Production_Injection_On">Production Injection Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Production_Injection_On_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Production_Injection_On) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Production_Injection_On" name="Production_Injection_On"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Production_Injection_On)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Production_Injection_On_display')" />
                                                        </div>

                                                        @error('Production_Injection_On')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif


                                            <div class="sub-head">
                                                Stores
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Store_Review !== 'yes')
                                                        $('.store').hide();

                                                        $('[name="Store_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.store').show();
                                                                $('.store span').show();
                                                            } else {
                                                                $('.store').hide();
                                                                $('.store span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Store"> Store Required ? <span class="text-danger">*</span></label>
                                                        <select name="Store_Review" id="Store_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Store_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Store_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Store_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 54,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 store">
                                                    <div class="group-input">
                                                        <label for="Store notification">Store Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Store_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Store_person"
                                                            class="Store_person" id="Store_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Store_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 store">
                                                    <div class="group-input">
                                                        <label for="Store assessment">Impact Assessment (By Store) <span id="asteriskPT1"
                                                                style="display: {{ $data1->Store_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Store_Review == 'yes' && $data->stage == 6) required @endif class="summernote Store_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->Store_person) && Auth::user()->name != $data1->Store_person)) readonly @endif name="Store_assessment" id="summernote-17">{{ $data1->Store_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 store">
                                                    <div class="group-input">
                                                        <label for="store feedback">store Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Store_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Store_feedback" @if ($data->stage == 5 || (isset($data1->Store_person) && Auth::user()->name != $data1->Store_person)) readonly @endif name="Store_feedback"
                                                            id="summernote-18" @if ($data1->Store_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Store_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 store">
                                                    <div class="group-input">
                                                        <label for="Store attachment">Store Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Store_attachment">
                                                                @if ($data1->Store_attachment)
                                                                    @foreach (json_decode($data1->Store_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Store_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Store_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 store">
                                                    <div class="group-input">
                                                        <label for="Store Completed By">Store Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Store_by }}"
                                                            name="Store_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Store_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 store">
                                                    <div class="group-input">
                                                        <label for="Store_on">Store Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Store_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Store_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Store_on" name="Store_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Store_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Store_on_display')" />
                                                        </div>

                                                        @error('Store_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Store_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Store_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Store">Store Required ?</label>
                                                        <select name="Store_Review" disabled id="Store_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Store_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Store_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Store_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 54,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 store">
                                                    <div class="group-input">
                                                        <label for="Store notification">Store Person <span id="asteriskInvi11" style="display: none"
                                                                class="text-danger">*</span></label>
                                                        <select name="Store_person" disabled id="Store_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Store_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 store">
                                                        <div class="group-input">
                                                            <label for="Store assessment">Impact Assessment (By Store)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Store_assessment" id="summernote-17">{{ $data1->Store_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 store">
                                                        <div class="group-input">
                                                            <label for="Store feedback">Store Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Store_feedback" id="summernote-18">{{ $data1->Store_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 store">
                                                        <div class="group-input">
                                                            <label for="Store assessment">Impact Assessment (By Store)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Store_assessment" id="summernote-17">{{ $data1->Store_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 store">
                                                        <div class="group-input">
                                                            <label for="Store feedback">Store Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Store_feedback" id="summernote-18">{{ $data1->Store_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 store">
                                                    <div class="group-input">
                                                        <label for="Store attachment">Store Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Store_attachment">
                                                                @if ($data1->Store_attachment)
                                                                    @foreach (json_decode($data1->Store_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Store_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Store_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 store">
                                                    <div class="group-input">
                                                        <label for="Store Completed By">Store Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Store_by }}" name="Store_by"
                                                            id="Store_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 store">
                                                    <div class="group-input">
                                                        <label for="Store_on">Store Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Store_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Store_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Store_on" name="Store_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Store_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Store_on_display')" />
                                                        </div>

                                                        @error('Store_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif




                                            <div class="sub-head">
                                                Quality Control
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Quality_review !== 'yes')
                                                        $('.qualityControl').hide();

                                                        $('[name="Quality_review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.qualityControl').show();
                                                                $('.qualityControl span').show();
                                                            } else {
                                                                $('.qualityControl').hide();
                                                                $('.qualityControl span').hide();
                                                            }
                                                        });
                                                    @endif

                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Quality Control"> Quality Control Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Quality_review" id="Quality_review_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Quality_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Quality_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Quality_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 24,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control notification">Quality Control Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Quality_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Quality_Control_Person"
                                                            class="Quality_Control_Person" id="Quality_Control_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Quality_Control_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control assessment">Impact Assessment (By Quality Control) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Quality_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Quality_review == 'yes' && $data->stage == 6) required @endif class="summernote Quality_Control_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->Quality_Control_Person) && Auth::user()->name != $data1->Quality_Control_Person)) readonly @endif name="Quality_Control_assessment" id="summernote-17">{{ $data1->Quality_Control_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control feedback">Quality Control Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Quality_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Quality_Control_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->Quality_Control_Person) && Auth::user()->name != $data1->Quality_Control_Person)) readonly @endif
                                                            name="Quality_Control_feedback" id="summernote-18" @if ($data1->Quality_review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Quality_Control_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control attachment">Quality Control Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Quality_Control_attachment">
                                                                @if ($data1->Quality_Control_attachment)
                                                                    @foreach (json_decode($data1->Quality_Control_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Quality_Control_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Quality_Control_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control Completed By">Quality Control Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Quality_Control_by }}"
                                                            name="Quality_Control_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Quality_Control_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality_Control_on">Quality Control Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Quality_Control_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Quality_Control_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Quality_Control_on" name="Quality_Control_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Quality_Control_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Quality_Control_on_display')" />
                                                        </div>

                                                        @error('Quality_Control_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Quality_review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Quality_Control_Person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Quality Control">Quality Control Required ?</label>
                                                        <select name="Quality_review" disabled id="Quality_review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Quality_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Quality_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Quality_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 24,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control notification">Quality Control Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="Quality_Control_Person" disabled id="Quality_Control_Person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Quality_Control_Person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 qualityControl">
                                                        <div class="group-input">
                                                            <label for="Quality Control assessment">Impact Assessment (By Quality Control)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Quality_Control_assessment" id="summernote-17">{{ $data1->Quality_Control_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 qualityControl">
                                                        <div class="group-input">
                                                            <label for="Quality Control feedback">Quality Control Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Quality_Control_feedback" id="summernote-18">{{ $data1->Quality_Control_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 qualityControl">
                                                        <div class="group-input">
                                                            <label for="Quality Control assessment">Impact Assessment (By Quality Control)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Quality_Control_assessment" id="summernote-17">{{ $data1->Quality_Control_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 qualityControl">
                                                        <div class="group-input">
                                                            <label for="Quality Control feedback">Quality Control Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Quality_Control_feedback" id="summernote-18">{{ $data1->Quality_Control_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control attachment">Quality Control Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Quality_Control_attachment">
                                                                @if ($data1->Quality_Control_attachment)
                                                                    @foreach (json_decode($data1->Quality_Control_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Store_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Quality_Control_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality Control Completed By">Quality Control Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Quality_Control_by }}"
                                                            name="Quality_Control_by" id="Quality_Control_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 qualityControl">
                                                    <div class="group-input">
                                                        <label for="Quality_Control_on">Quality Control Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Quality_Control_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Quality_Control_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Quality_Control_on" name="Quality_Control_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Quality_Control_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Quality_Control_on_display')" />
                                                        </div>

                                                        @error('Quality_Control_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif

                                            <div class="sub-head">
                                                Research & Development
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->ResearchDevelopment_Review !== 'yes')
                                                        $('.researchDevelopment').hide();

                                                        $('[name="ResearchDevelopment_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.researchDevelopment').show();
                                                                $('.researchDevelopment span').show();
                                                            } else {
                                                                $('.researchDevelopment').hide();
                                                                $('.researchDevelopment span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Research Development"> Research & Development Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="ResearchDevelopment_Review" id="ResearchDevelopment_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 55,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development notification">Research & Development Person <span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->ResearchDevelopment_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="ResearchDevelopment_person"
                                                            class="ResearchDevelopment_person" id="ResearchDevelopment_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ResearchDevelopment_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development assessment">Impact Assessment (By Research &
                                                            Development) <span id="asteriskPT1"
                                                                style="display: {{ $data1->ResearchDevelopment_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->ResearchDevelopment_Review == 'yes' && $data->stage == 6) required @endif class="summernote ResearchDevelopment_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->ResearchDevelopment_person) && Auth::user()->name != $data1->ResearchDevelopment_person)) readonly @endif name="ResearchDevelopment_assessment" id="summernote-17">{{ $data1->ResearchDevelopment_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development feedback">Research & Development Feedback <span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->ResearchDevelopment_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote ResearchDevelopment_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->ResearchDevelopment_person) && Auth::user()->name != $data1->ResearchDevelopment_person)) readonly @endif
                                                            name="ResearchDevelopment_feedback" id="summernote-18" @if ($data1->ResearchDevelopment_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->ResearchDevelopment_feedback }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development attachment">Research & Development Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ResearchDevelopment_attachment">
                                                                @if ($data1->ResearchDevelopment_attachment)
                                                                    @foreach (json_decode($data1->ResearchDevelopment_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="ResearchDevelopment_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'ResearchDevelopment_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development Completed By">Research & Development Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->ResearchDevelopment_by }}"
                                                            name="ResearchDevelopment_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="ResearchDevelopment_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="ResearchDevelopment_on">Research & Development Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="ResearchDevelopment_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ResearchDevelopment_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="ResearchDevelopment_on" name="ResearchDevelopment_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->ResearchDevelopment_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'ResearchDevelopment_on_display')" />
                                                        </div>

                                                        @error('ResearchDevelopment_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('ResearchDevelopment_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('ResearchDevelopment_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Research Development">Research & Development Required ?</label>
                                                        <select name="ResearchDevelopment_Review" disabled id="ResearchDevelopment_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ResearchDevelopment_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 55,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development notification">Research & Development Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="ResearchDevelopment_person" disabled id="ResearchDevelopment_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ResearchDevelopment_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 researchDevelopment">
                                                        <div class="group-input">
                                                            <label for="Research Development assessment">Impact Assessment (By Research &
                                                                Development)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ResearchDevelopment_assessment" id="summernote-17">{{ $data1->ResearchDevelopment_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 researchDevelopment">
                                                        <div class="group-input">
                                                            <label for="Research Development feedback">Research & Development Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ResearchDevelopment_feedback" id="summernote-18">{{ $data1->ResearchDevelopment_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 researchDevelopment">
                                                        <div class="group-input">
                                                            <label for="Research Development assessment">Impact Assessment (By Research &
                                                                Development)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ResearchDevelopment_assessment" id="summernote-17">{{ $data1->ResearchDevelopment_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 researchDevelopment">
                                                        <div class="group-input">
                                                            <label for="Research Development feedback">Research & Development Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ResearchDevelopment_feedback" id="summernote-18">{{ $data1->ResearchDevelopment_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development attachment">Research & Development Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ResearchDevelopment_attachment">
                                                                @if ($data1->ResearchDevelopment_attachment)
                                                                    @foreach (json_decode($data1->ResearchDevelopment_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="ResearchDevelopment_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'ResearchDevelopment_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="Research Development Completed By">Research & Development Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->ResearchDevelopment_by }}"
                                                            name="ResearchDevelopment_by" id="StorResearchDevelopment_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 researchDevelopment">
                                                    <div class="group-input">
                                                        <label for="ResearchDevelopment_on">Research & Development Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="ResearchDevelopment_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ResearchDevelopment_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="ResearchDevelopment_on" name="ResearchDevelopment_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->ResearchDevelopment_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'ResearchDevelopment_on_display')" />
                                                        </div>

                                                        @error('ResearchDevelopment_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif



                                            <div class="sub-head">
                                                Engineering
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Engineering_review !== 'yes')
                                                        $('.Engineering').hide();

                                                        $('[name="Engineering_review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.Engineering').show();
                                                                $('.Engineering span').show();
                                                            } else {
                                                                $('.Engineering').hide();
                                                                $('.Engineering span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Engineering"> Engineering Required ? <span class="text-danger">*</span></label>
                                                        <select name="Engineering_review" id="Engineering_review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Engineering_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Engineering_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Engineering_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 25,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering notification">Engineering Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Engineering_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Engineering_person"
                                                            class="Engineering_person" id="Engineering_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Engineering_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering assessment">Impact Assessment (By Engineering) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Engineering_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Engineering_review == 'yes' && $data->stage == 6) required @endif class="summernote Engineering_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->Engineering_person) && Auth::user()->name != $data1->Engineering_person)) readonly @endif name="Engineering_assessment" id="summernote-17">{{ $data1->Engineering_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering feedback">Engineering Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Engineering_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Engineering_feedback" @if ($data->stage == 5 || (isset($data1->Engineering_person) && Auth::user()->name != $data1->Engineering_person)) readonly @endif
                                                            name="Engineering_feedback" id="summernote-18" @if ($data1->Engineering_review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Engineering_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering attachment">Engineering Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Engineering_attachment">
                                                                @if ($data1->Engineering_attachment)
                                                                    @foreach (json_decode($data1->Engineering_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Engineering_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Engineering_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering Completed By">Engineering Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Engineering_by }}"
                                                            name="Engineering_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Engineering_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering_on">Engineering Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Engineering_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Engineering_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Engineering_on" name="Engineering_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Engineering_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Engineering_on_display')" />
                                                        </div>

                                                        @error('Engineering_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Engineering_review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Engineering_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Engineering">Engineering Required ?</label>
                                                        <select name="Engineering_review" disabled id="Engineering_review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Engineering_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Engineering_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Engineering_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 25,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering notification">Engineering Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="Engineering_person" disabled id="Engineering_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Engineering_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 Engineering">
                                                        <div class="group-input">
                                                            <label for="Engineering assessment">Impact Assessment (By Engineering)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Engineering_assessment" id="summernote-17">{{ $data1->Engineering_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Engineering">
                                                        <div class="group-input">
                                                            <label for="Engineering feedback">Engineering Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Engineering_feedback" id="summernote-18">{{ $data1->Engineering_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 Engineering">
                                                        <div class="group-input">
                                                            <label for="Engineering assessment">Impact Assessment (By Engineering)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Engineering_assessment" id="summernote-17">{{ $data1->Engineering_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Engineering">
                                                        <div class="group-input">
                                                            <label for="Engineering feedback">Engineering Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Engineering_feedback" id="summernote-18">{{ $data1->Engineering_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering attachment">Engineering Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Engineering_attachment">
                                                                @if ($data1->Engineering_attachment)
                                                                    @foreach (json_decode($data1->Engineering_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Engineering_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Engineering_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering Completed By">Engineering Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Engineering_by }}"
                                                            name="Engineering_by" id="Engineering_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Engineering">
                                                    <div class="group-input">
                                                        <label for="Engineering_on">Engineering Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Engineering_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Engineering_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Engineering_on" name="Engineering_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Engineering_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Engineering_on_display')" />
                                                        </div>

                                                        @error('Engineering_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif




                                            <div class="sub-head">
                                                Human Resource
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Human_Resource_review !== 'yes')
                                                        $('.Human_Resource').hide();

                                                        $('[name="Human_Resource_review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.Human_Resource').show();
                                                                $('.Human_Resource span').show();
                                                            } else {
                                                                $('.Human_Resource').hide();
                                                                $('.Human_Resource span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Human Resource"> Human Resource Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Human_Resource_review" id="Human_Resource_review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Human_Resource_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Human_Resource_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Human_Resource_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 31,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource notification">Human Resource Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Human_Resource_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Human_Resource_person"
                                                            class="Human_Resource_person" id="Human_Resource_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Human_Resource_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource assessment">Impact Assessment (By Human Resource) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Human_Resource_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Human_Resource_review == 'yes' && $data->stage == 6) required @endif class="summernote Human_Resource_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->Human_Resource_person) && Auth::user()->name != $data1->Human_Resource_person)) readonly @endif name="Human_Resource_assessment" id="summernote-17">{{ $data1->Human_Resource_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource feedback">Human Resource Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Human_Resource_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Human_Resource_feedback" @if ($data->stage == 5 || (isset($data1->Human_Resource_person) && Auth::user()->name != $data1->Human_Resource_person)) readonly @endif
                                                            name="Human_Resource_feedback" id="summernote-18" @if ($data1->Human_Resource_review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Human_Resource_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource attachment">Human Resource Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Human_Resource_attachment">
                                                                @if ($data1->Human_Resource_attachment)
                                                                    @foreach (json_decode($data1->Human_Resource_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Human_Resource_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Human_Resource_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource Completed By">Human Resource Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Human_Resource_by }}"
                                                            name="Human_Resource_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Human_Resource_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human_Resource_on">Human Resource Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Human_Resource_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Human_Resource_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Human_Resource_on" name="Human_Resource_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Human_Resource_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Human_Resource_on_display')" />
                                                        </div>

                                                        @error('Human_Resource_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Human_Resource_review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Human_Resource_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Human Resource">Human Resource Required ?</label>
                                                        <select name="Human_Resource_review" disabled id="Human_Resource_review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Human_Resource_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Human_Resource_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Human_Resource_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 31,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource notification">Human Resource Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="Human_Resource_person" disabled id="Human_Resource_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Human_Resource_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 Human_Resource">
                                                        <div class="group-input">
                                                            <label for="Human Resource assessment">Impact Assessment (By Human Resource)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Human_Resource_assessment" id="summernote-17">{{ $data1->Human_Resource_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Human_Resource">
                                                        <div class="group-input">
                                                            <label for="Human Resource feedback">Human Resource Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Human_Resource_feedback" id="summernote-18">{{ $data1->Human_Resource_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 Human_Resource">
                                                        <div class="group-input">
                                                            <label for="Human Resource assessment">Impact Assessment (By Human Resource)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Human_Resource_assessment" id="summernote-17">{{ $data1->Human_Resource_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Human_Resource">
                                                        <div class="group-input">
                                                            <label for="Human Resource feedback">Human Resource Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Human_Resource_feedback" id="summernote-18">{{ $data1->Human_Resource_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource attachment">Human Resource Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Human_Resource_attachment">
                                                                @if ($data1->Human_Resource_attachment)
                                                                    @foreach (json_decode($data1->Human_Resource_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Human_Resource_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Human_Resource_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human Resource Completed By">Human Resource Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Human_Resource_by }}"
                                                            name="Human_Resource_by" id="Human_Resource_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Human_Resource">
                                                    <div class="group-input">
                                                        <label for="Human_Resource_on">Human Resource Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Human_Resource_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Human_Resource_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Human_Resource_on" name="Human_Resource_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Human_Resource_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Human_Resource_on_display')" />
                                                        </div>

                                                        @error('Human_Resource_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif


                                            <div class="sub-head">
                                                Microbiology
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Microbiology_Review !== 'yes')
                                                        $('.Microbiology').hide();

                                                        $('[name="Microbiology_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.Microbiology').show();
                                                                $('.Microbiology span').show();
                                                            } else {
                                                                $('.Microbiology').hide();
                                                                $('.Microbiology span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Microbiology"> Microbiology Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Microbiology_Review" id="Microbiology_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Microbiology_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Microbiology_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Microbiology_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 56,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology notification">Microbiology Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Microbiology_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="Microbiology_person"
                                                            class="Microbiology_person" id="Microbiology_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Microbiology_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology assessment">Impact Assessment (By Microbiology) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->Microbiology_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Microbiology_Review == 'yes' && $data->stage == 6) required @endif class="summernote Microbiology_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->Microbiology_person) && Auth::user()->name != $data1->Microbiology_person)) readonly @endif name="Microbiology_assessment" id="summernote-17">{{ $data1->Microbiology_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology feedback">Microbiology Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Microbiology_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Microbiology_feedback" @if ($data->stage == 5 || (isset($data1->Microbiology_person) && Auth::user()->name != $data1->Microbiology_person)) readonly @endif
                                                            name="Microbiology_feedback" id="summernote-18" @if ($data1->Microbiology_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Microbiology_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology attachment">Microbiology Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Microbiology_attachment">
                                                                @if ($data1->Microbiology_attachment)
                                                                    @foreach (json_decode($data1->Microbiology_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Microbiology_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Microbiology_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology Completed By">Microbiology Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Microbiology_by }}"
                                                            name="Microbiology_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Microbiology_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology_on">Microbiology Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Microbiology_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Microbiology_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Microbiology_on" name="Microbiology_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Microbiology_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Microbiology_on_display')" />
                                                        </div>

                                                        @error('Microbiology_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Microbiology_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Microbiology_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Microbiology">Microbiology Required ?</label>
                                                        <select name="Microbiology_Review" disabled id="Microbiology_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Microbiology_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Microbiology_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Microbiology_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 56,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology notification">Microbiology Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="Microbiology_person" disabled id="Microbiology_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Microbiology_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 Microbiology">
                                                        <div class="group-input">
                                                            <label for="Microbiology assessment">Impact Assessment (By Microbiology)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Microbiology_assessment" id="summernote-17">{{ $data1->Microbiology_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Microbiology">
                                                        <div class="group-input">
                                                            <label for="Microbiology feedback">Microbiology Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Microbiology_feedback" id="summernote-18">{{ $data1->Microbiology_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 Microbiology">
                                                        <div class="group-input">
                                                            <label for="Microbiology assessment">Impact Assessment (By Microbiology)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Microbiology_assessment" id="summernote-17">{{ $data1->Microbiology_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Microbiology">
                                                        <div class="group-input">
                                                            <label for="Microbiology feedback">Microbiology Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Microbiology_feedback" id="summernote-18">{{ $data1->Microbiology_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology attachment">Microbiology Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Microbiology_attachment">
                                                                @if ($data1->Microbiology_attachment)
                                                                    @foreach (json_decode($data1->Microbiology_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Microbiology_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Microbiology_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology Completed By">Microbiology Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Microbiology_by }}"
                                                            name="Microbiology_by" id="Microbiology_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Microbiology">
                                                    <div class="group-input">
                                                        <label for="Microbiology_on">Microbiology Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Microbiology_on_display" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Microbiology_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Microbiology_on" name="Microbiology_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Microbiology_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Microbiology_on_display')" />
                                                        </div>

                                                        @error('Microbiology_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif



                                            <div class="sub-head">
                                                Regulatory Affair
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->RegulatoryAffair_Review !== 'yes')
                                                        $('.RegulatoryAffair').hide();

                                                        $('[name="RegulatoryAffair_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.RegulatoryAffair').show();
                                                                $('.RegulatoryAffair span').show();
                                                            } else {
                                                                $('.RegulatoryAffair').hide();
                                                                $('.RegulatoryAffair span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="RegulatoryAffair"> Regulatory Affair Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="RegulatoryAffair_Review" id="RegulatoryAffair_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 57,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair notification">Regulatory Affair Person <span id="asteriskPT"
                                                                style="display: {{ $data1->RegulatoryAffair_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif name="RegulatoryAffair_person"
                                                            class="RegulatoryAffair_person" id="RegulatoryAffair_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->RegulatoryAffair_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair assessment">Impact Assessment (By Regulatory Affair) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->RegulatoryAffair_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->RegulatoryAffair_Review == 'yes' && $data->stage == 6) required @endif class="summernote RegulatoryAffair_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->RegulatoryAffair_person) && Auth::user()->name != $data1->RegulatoryAffair_person)) readonly @endif name="RegulatoryAffair_assessment" id="summernote-17">{{ $data1->RegulatoryAffair_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair feedback">Regulatory Affair Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->RegulatoryAffair_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote RegulatoryAffair_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->RegulatoryAffair_person) && Auth::user()->name != $data1->RegulatoryAffair_person)) readonly @endif
                                                            name="RegulatoryAffair_feedback" id="summernote-18" @if ($data1->RegulatoryAffair_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->RegulatoryAffair_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair attachment">Regulatory Affair Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="RegulatoryAffair_attachment">
                                                                @if ($data1->RegulatoryAffair_attachment)
                                                                    @foreach (json_decode($data1->RegulatoryAffair_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="RegulatoryAffair_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'RegulatoryAffair_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair Completed By">Regulatory Affair Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->RegulatoryAffair_by }}"
                                                            name="RegulatoryAffair_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="RegulatoryAffair_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="RegulatoryAffair_on">Regulatory Affair Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="RegulatoryAffair_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->RegulatoryAffair_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="RegulatoryAffair_on" name="RegulatoryAffair_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->RegulatoryAffair_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'RegulatoryAffair_on_display')" />
                                                        </div>

                                                        @error('RegulatoryAffair_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('RegulatoryAffair_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('RegulatoryAffair_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair">Regulatory Affair Required ?</label>
                                                        <select name="RegulatoryAffair_Review" disabled id="RegulatoryAffair_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->RegulatoryAffair_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 57,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair notification">Regulatory Affair Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="RegulatoryAffair_person" disabled id="RegulatoryAffair_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->RegulatoryAffair_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 RegulatoryAffair">
                                                        <div class="group-input">
                                                            <label for="Regulatory Affair assessment">Impact Assessment (By Regulatory Affair)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="RegulatoryAffair_assessment" id="summernote-17">{{ $data1->RegulatoryAffair_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 RegulatoryAffair">
                                                        <div class="group-input">
                                                            <label for="Regulatory Affair feedback">Regulatory Affair Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="RegulatoryAffair_feedback" id="summernote-18">{{ $data1->RegulatoryAffair_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 RegulatoryAffair">
                                                        <div class="group-input">
                                                            <label for="Regulatory Affair assessment">Impact Assessment (By Regulatory Affair)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="RegulatoryAffair_assessment" id="summernote-17">{{ $data1->RegulatoryAffair_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 RegulatoryAffair">
                                                        <div class="group-input">
                                                            <label for="Regulatory Affair feedback">Regulatory Affair Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="RegulatoryAffair_feedback" id="summernote-18">{{ $data1->RegulatoryAffair_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair attachment">Regulatory Affair Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="RegulatoryAffair_attachment">
                                                                @if ($data1->RegulatoryAffair_attachment)
                                                                    @foreach (json_decode($data1->RegulatoryAffair_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="RegulatoryAffair_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'RegulatoryAffair_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="Regulatory Affair Completed By">Regulatory Affair Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->RegulatoryAffair_by }}"
                                                            name="RegulatoryAffair_by" id="RegulatoryAffair_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 RegulatoryAffair">
                                                    <div class="group-input">
                                                        <label for="RegulatoryAffair_on">Regulatory Affair Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="RegulatoryAffair_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->RegulatoryAffair_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="RegulatoryAffair_on" name="RegulatoryAffair_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->RegulatoryAffair_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'RegulatoryAffair_on_display')" />
                                                        </div>

                                                        @error('RegulatoryAffair_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif



                                            <div class="sub-head">
                                                Corporate Quality Assurance
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->CorporateQualityAssurance_Review !== 'yes')
                                                        $('.CQA').hide();

                                                        $('[name="CorporateQualityAssurance_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.CQA').show();
                                                                $('.CQA span').show();
                                                            } else {
                                                                $('.CQA').hide();
                                                                $('.CQA span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance"> Corporate Quality Assurance Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="CorporateQualityAssurance_Review" id="CorporateQualityAssurance_Review"
                                                            required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 58,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance notification">Corporate Quality Assurance Person <span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->CorporateQualityAssurance_Review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif
                                                            name="CorporateQualityAssurance_person" class="CorporateQualityAssurance_person"
                                                            id="CorporateQualityAssurance_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->CorporateQualityAssurance_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance assessment">Impact Assessment (By Corporate Quality
                                                            Assurance) <span id="asteriskPT1"
                                                                style="display: {{ $data1->CorporateQualityAssurance_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->CorporateQualityAssurance_Review == 'yes' && $data->stage == 6) required @endif
                                                            class="summernote CorporateQualityAssurance_assessment" @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->CorporateQualityAssurance_person) &&
                                                                        Auth::user()->name != $data1->CorporateQualityAssurance_person)) readonly @endif
                                                            name="CorporateQualityAssurance_assessment" id="summernote-17">{{ $data1->CorporateQualityAssurance_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance feedback">Corporate Quality Assurance Feedback <span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->CorporateQualityAssurance_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote CorporateQualityAssurance_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->CorporateQualityAssurance_person) &&
                                                                    Auth::user()->name != $data1->CorporateQualityAssurance_person)) readonly @endif
                                                            name="CorporateQualityAssurance_feedback" id="summernote-18"
                                                            @if ($data1->CorporateQualityAssurance_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->CorporateQualityAssurance_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance attachment">Corporate Quality Assurance
                                                            Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="CorporateQualityAssurance_attachment">
                                                                @if ($data1->CorporateQualityAssurance_attachment)
                                                                    @foreach (json_decode($data1->CorporateQualityAssurance_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="CorporateQualityAssurance_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'CorporateQualityAssurance_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance Completed By">Corporate Quality Assurance Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->CorporateQualityAssurance_by }}"
                                                            name="CorporateQualityAssurance_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="CorporateQualityAssurance_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 CQA">
                                                    <div class="group-input">
                                                        <label for="CorporateQualityAssurance_on">Corporate Quality Assurance Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="CorporateQualityAssurance_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->CorporateQualityAssurance_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="CorporateQualityAssurance_on"
                                                                name="CorporateQualityAssurance_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->CorporateQualityAssurance_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'CorporateQualityAssurance_on_display')" />
                                                        </div>

                                                        @error('CorporateQualityAssurance_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('CorporateQualityAssurance_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('CorporateQualityAssurance_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance">Corporate Quality Assurance Required ?</label>
                                                        <select name="CorporateQualityAssurance_Review" disabled
                                                            id="CorporateQualityAssurance_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->CorporateQualityAssurance_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 58,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance notification">Corporate Quality Assurance Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="CorporateQualityAssurance_person" disabled
                                                            id="CorporateQualityAssurance_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->CorporateQualityAssurance_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 CQA">
                                                        <div class="group-input">
                                                            <label for="Corporate Quality Assurance assessment">Impact Assessment (By Corporate
                                                                Quality Assurance)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="CorporateQualityAssurance_assessment" id="summernote-17">{{ $data1->CorporateQualityAssurance_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 CQA">
                                                        <div class="group-input">
                                                            <label for="Corporate Quality Assurance feedback">Corporate Quality Assurance
                                                                Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="CorporateQualityAssurance_feedback" id="summernote-18">{{ $data1->CorporateQualityAssurance_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 CQA">
                                                        <div class="group-input">
                                                            <label for="Corporate Quality Assurance assessment">Impact Assessment (By Corporate
                                                                Quality Assurance)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="CorporateQualityAssurance_assessment" id="summernote-17">{{ $data1->CorporateQualityAssurance_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 CQA">
                                                        <div class="group-input">
                                                            <label for="Corporate Quality Assurance feedback">Corporate Quality Assurance
                                                                Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="CorporateQualityAssurance_feedback" id="summernote-18">{{ $data1->CorporateQualityAssurance_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance attachment">Corporate Quality Assurance
                                                            Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="CorporateQualityAssurance_attachment">
                                                                @if ($data1->CorporateQualityAssurance_attachment)
                                                                    @foreach (json_decode($data1->CorporateQualityAssurance_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Microbiology_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Microbiology_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 CQA">
                                                    <div class="group-input">
                                                        <label for="Corporate Quality Assurance Completed By">Corporate Quality Assurance Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->CorporateQualityAssurance_by }}"
                                                            name="CorporateQualityAssurance_by" id="CorporateQualityAssurance_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 CQA">
                                                    <div class="group-input">
                                                        <label for="CorporateQualityAssurance_on">Corporate Quality Assurance Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="CorporateQualityAssurance_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->CorporateQualityAssurance_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="CorporateQualityAssurance_on"
                                                                name="CorporateQualityAssurance_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->CorporateQualityAssurance_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'CorporateQualityAssurance_on_display')" />
                                                        </div>

                                                        @error('CorporateQualityAssurance_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif



                                            <div class="sub-head">
                                                Safety
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Environment_Health_review !== 'yes')
                                                        $('.safety').hide();

                                                        $('[name="Environment_Health_review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.safety').show();
                                                                $('.safety span').show();
                                                            } else {
                                                                $('.safety').hide();
                                                                $('.safety span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Safety"> Safety Review Required ? <span class="text-danger">*</span></label>
                                                        <select name="Environment_Health_review" id="Environment_Health_review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Environment_Health_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Environment_Health_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Environment_Health_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 59,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 safety">
                                                    <div class="group-input">
                                                        <label for="Safety notification">Safety Person <span id="asteriskPT"
                                                                style="display: {{ $data1->Environment_Health_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif
                                                            name="Environment_Health_Safety_person" class="Environment_Health_Safety_person"
                                                            id="Environment_Health_Safety_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Environment_Health_Safety_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 safety">
                                                    <div class="group-input">
                                                        <label for="Safety assessment">Impact Assessment (By Safety) <span id="asteriskPT1"
                                                                style="display: {{ $data1->Environment_Health_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Environment_Health_review == 'yes' && $data->stage == 6) required @endif class="summernote Health_Safety_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->Environment_Health_Safety_person) &&
                                                                        Auth::user()->name != $data1->Environment_Health_Safety_person)) readonly @endif name="Health_Safety_assessment" id="summernote-17">{{ $data1->Health_Safety_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 safety">
                                                    <div class="group-input">
                                                        <label for="Safety feedback">Safety Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->Environment_Health_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Health_Safety_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->Environment_Health_Safety_person) &&
                                                                    Auth::user()->name != $data1->Environment_Health_Safety_person)) readonly @endif
                                                            name="Health_Safety_feedback" id="summernote-18" @if ($data1->Environment_Health_review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Health_Safety_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 safety">
                                                    <div class="group-input">
                                                        <label for="Safety attachment">Safety Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Environment_Health_Safety_attachment">
                                                                @if ($data1->Environment_Health_Safety_attachment)
                                                                    @foreach (json_decode($data1->Environment_Health_Safety_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Environment_Health_Safety_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Environment_Health_Safety_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 safety">
                                                    <div class="group-input">
                                                        <label for="Safety Completed By">Safety Review Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Environment_Health_Safety_by }}"
                                                            name="Environment_Health_Safety_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Environment_Health_Safety_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 safety">
                                                    <div class="group-input">
                                                        <label for="Environment_Health_Safety_on">Safety Review Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Environment_Health_Safety_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Environment_Health_Safety_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Environment_Health_Safety_on"
                                                                name="Environment_Health_Safety_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Environment_Health_Safety_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Environment_Health_Safety_on_display')" />
                                                        </div>

                                                        @error('Environment_Health_Safety_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Environment_Health_review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Environment_Health_Safety_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Safety">Safety Review Required ?</label>
                                                        <select name="Environment_Health_review" disabled id="Environment_Health_review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Environment_Health_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Environment_Health_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Environment_Health_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 59,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 safety">
                                                    <div class="group-input">
                                                        <label for="Safety notification">Safety Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select name="Environment_Health_Safety_person" disabled
                                                            id="Environment_Health_Safety_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Environment_Health_Safety_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 safety">
                                                        <div class="group-input">
                                                            <label for="Safety assessment">Impact Assessment (By Safety)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Health_Safety_assessment" id="summernote-17">{{ $data1->Health_Safety_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 safety">
                                                        <div class="group-input">
                                                            <label for="Safety feedback">Safety Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Health_Safety_feedback" id="summernote-18">{{ $data1->Health_Safety_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 safety">
                                                        <div class="group-input">
                                                            <label for="Safety assessment">Impact Assessment (By Safety)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Health_Safety_assessment" id="summernote-17">{{ $data1->Health_Safety_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 safety">
                                                        <div class="group-input">
                                                            <label for="Safety feedback">Safety Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Health_Safety_feedback" id="summernote-18">{{ $data1->Health_Safety_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 safety">
                                                    <div class="group-input">
                                                        <label for="Safety attachment">Safety Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Environment_Health_Safety_attachment">
                                                                @if ($data1->Environment_Health_Safety_attachment)
                                                                    @foreach (json_decode($data1->Environment_Health_Safety_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Environment_Health_Safety_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Environment_Health_Safety_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 safety">
                                                    <div class="group-input">
                                                        <label for="Safety Completed By">Safety Review Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Environment_Health_Safety_by }}"
                                                            name="Environment_Health_Safety_by" id="Environment_Health_Safety_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 safety">
                                                    <div class="group-input">
                                                        <label for="Environment_Health_Safety_on">Safety Review Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Environment_Health_Safety_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Environment_Health_Safety_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Environment_Health_Safety_on"
                                                                name="Environment_Health_Safety_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Environment_Health_Safety_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Environment_Health_Safety_on_display')" />
                                                        </div>

                                                        @error('Environment_Health_Safety_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif




                                            <div class="sub-head">
                                                Information Technology
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->Information_Technology_review !== 'yes')
                                                        $('.Information_Technology').hide();

                                                        $('[name="Information_Technology_review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.Information_Technology').show();
                                                                $('.Information_Technology span').show();
                                                            } else {
                                                                $('.Information_Technology').hide();
                                                                $('.Information_Technology span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Information_Technology"> Information Technology Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="Information_Technology_review" id="Information_Technology_review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Information_Technology_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Information_Technology_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Information_Technology_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 32,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology notification">Information Technology Person <span
                                                                id="asteriskPT"
                                                                style="display: {{ $data1->Information_Technology_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select @if ($data->stage == 6) disabled @endif
                                                            name="Information_Technology_person" class="Information_Technology_person"
                                                            id="Information_Technology_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Information_Technology_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology assessment">Impact Assessment (By Information Technology)
                                                            <span id="asteriskPT1"
                                                                style="display: {{ $data1->Information_Technology_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->Information_Technology_review == 'yes' && $data->stage == 6) required @endif class="summernote Information_Technology_assessment"
                                                            @if (
                                                                $data->stage == 5 ||
                                                                    (isset($data1->Information_Technology_person) && Auth::user()->name != $data1->Information_Technology_person)) readonly @endif name="Information_Technology_assessment"
                                                            id="summernote-17">{{ $data1->Information_Technology_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology feedback">Information Technology Feedback <span
                                                                id="asteriskPT2"
                                                                style="display: {{ $data1->Information_Technology_review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote Information_Technology_feedback" @if (
                                                            $data->stage == 5 ||
                                                                (isset($data1->Information_Technology_person) && Auth::user()->name != $data1->Information_Technology_person)) readonly @endif
                                                            name="Information_Technology_feedback" id="summernote-18" @if ($data1->Information_Technology_review == 'yes' && $data->stage == 6) required @endif>{{ $data1->Information_Technology_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology attachment">Information Technology Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Information_Technology_attachment">
                                                                @if ($data1->Information_Technology_attachment)
                                                                    @foreach (json_decode($data1->Information_Technology_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="Information_Technology_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'Information_Technology_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology Completed By">Information Technology Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Information_Technology_by }}"
                                                            name="Information_Technology_by"{{ $data->stage == 0 || $data->stage == 7 ? 'readonly' : '' }}
                                                            id="Information_Technology_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information_Technology_on">Information Technology Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Information_Technology_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Information_Technology_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Information_Technology_on" name="Information_Technology_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Information_Technology_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Information_Technology_on_display')" />
                                                        </div>

                                                        @error('Information_Technology_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Information_Technology_review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('Information_Technology_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Information Technology">Information Technology Required ?</label>
                                                        <select name="Information_Technology_review" disabled id="Information_Technology_review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Information_Technology_review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->Information_Technology_review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->Information_Technology_review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 32,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology notification">Information Technology Person <span
                                                                id="asteriskInvi11" style="display: none" class="text-danger">*</span></label>
                                                        <select name="Information_Technology_person" disabled id="Information_Technology_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->Information_Technology_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 Information_Technology">
                                                        <div class="group-input">
                                                            <label for="Information Technology assessment">Impact Assessment (By Information
                                                                Technology)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Information_Technology_assessment" id="summernote-17">{{ $data1->Information_Technology_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Information_Technology">
                                                        <div class="group-input">
                                                            <label for="Information Technology feedback">Information Technology Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="Information_Technology_feedback" id="summernote-18">{{ $data1->Information_Technology_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 Information_Technology">
                                                        <div class="group-input">
                                                            <label for="Information Technology assessment">Impact Assessment (By Information
                                                                Technology)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Information_Technology_assessment" id="summernote-17">{{ $data1->Information_Technology_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 Information_Technology">
                                                        <div class="group-input">
                                                            <label for="Information Technology feedback">Information Technology Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="Information_Technology_feedback" id="summernote-18">{{ $data1->Information_Technology_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology attachment">Information Technology Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Information_Technology_attachment">
                                                                @if ($data1->Information_Technology_attachment)
                                                                    @foreach (json_decode($data1->Information_Technology_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="Information_Technology_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Information_Technology_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information Technology Completed By">Information Technology Completed
                                                            By</label>
                                                        <input readonly type="text" value="{{ $data1->Information_Technology_by }}"
                                                            name="Information_Technology_by" id="Information_Technology_by">


                                                    </div>
                                                </div>
                                                <div class="col-lg-6 Information_Technology">
                                                    <div class="group-input">
                                                        <label for="Information_Technology_on">Information Technology Completed On</label>

                                                        <div class="calenderauditee">
                                                            <!-- Read-only text input to display formatted date (e.g., DD-MMM-YYYY) -->
                                                            <input type="text" id="Information_Technology_on_display" readonly
                                                                placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Information_Technology_on) }}" />

                                                            <!-- Hidden date input for date selection -->
                                                            <input type="date" id="Information_Technology_on" name="Information_Technology_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                value="{{ \Carbon\Carbon::parse($data1->Information_Technology_on)->format('Y-m-d') }}"
                                                                class="hide-input" {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                oninput="handleDateInput(this, 'Information_Technology_on_display')" />
                                                        </div>

                                                        @error('Information_Technology_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif



                                            <div class="sub-head">
                                                Contract Giver
                                            </div>
                                            <script>
                                                $(document).ready(function() {
                                                    @if ($data1->ContractGiver_Review !== 'yes')
                                                        $('.ContractGiver').hide();

                                                        $('[name="ContractGiver_Review"]').change(function() {
                                                            if ($(this).val() === 'yes') {

                                                                $('.ContractGiver').show();
                                                                $('.ContractGiver span').show();
                                                            } else {
                                                                $('.ContractGiver').hide();
                                                                $('.ContractGiver span').hide();
                                                            }
                                                        });
                                                    @endif
                                                });
                                            </script>
                                            @php
                                                $data1 = DB::table('query_management_cfts')
                                                    ->where('query_management_id', $data->id)
                                                    ->first();
                                            @endphp

                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Contract Giver"> Contract Giver Required ? <span
                                                                class="text-danger">*</span></label>
                                                        <select name="ContractGiver_Review" id="ContractGiver_Review" required>
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ContractGiver_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ContractGiver_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ContractGiver_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 60,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver notification">Contract Giver Person <span id="asteriskPT"
                                                                class="text-danger">*</span></label>
                                                        <select @if ($data->stage == 6) disabled @endif name="ContractGiver_person"
                                                            id="ContractGiver_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ContractGiver_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-md-12 mb-3 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver assessment">Impact Assessment (By Contract Giver) <span
                                                                id="asteriskPT1"
                                                                style="display: {{ $data1->ContractGiver_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea @if ($data1->ContractGiver_Review == 'yes' && $data->stage == 6) required @endif class="summernote ContractGiver_assessment"
                                                            @if ($data->stage == 5 || (isset($data1->ContractGiver_person) && Auth::user()->name != $data1->ContractGiver_person)) readonly @endif name="ContractGiver_assessment" id="summernote-17">{{ $data1->ContractGiver_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver feedback">Contract Giver Feedback <span id="asteriskPT2"
                                                                style="display: {{ $data1->ContractGiver_Review == 'yes' && $data->stage == 6 ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                does not require completion</small></div>
                                                        <textarea class="summernote ContractGiver_feedback" @if ($data->stage == 5 || (isset($data1->ContractGiver_person) && Auth::user()->name != $data1->ContractGiver_person)) readonly @endif
                                                            name="ContractGiver_feedback" id="summernote-18" @if ($data1->ContractGiver_Review == 'yes' && $data->stage == 6) required @endif>{{ $data1->ContractGiver_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver attachment">Contract Giver Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ContractGiver_attachment">
                                                                @if ($data1->ContractGiver_attachment)
                                                                    @foreach (json_decode($data1->ContractGiver_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile"
                                                                    name="ContractGiver_attachment[]"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                                    oninput="addMultipleFiles(this, 'ContractGiver_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver Completed By">Contract Giver Completed By</label>
                                                        <input readonly type="text" value="{{ $data1->ContractGiver_by }}"
                                                            name="ContractGiver_by" id="ContractGiver_by">
                                                    </div>
                                                </div>


                                                <div class="col-6 ContractGiver new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Contract Giver Completed On">Contract Giver
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="ContractGiver_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ContractGiver_on) }}" />
                                                            <input readonly type="date" name="ContractGiver_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'ContractGiver_on')" />
                                                        </div>
                                                        @error('ContractGiver_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('ContractGiver_Review');
                                                        var inputsToToggle = [];

                                                        // Add elements with class 'facility-name' to inputsToToggle
                                                        var facilityNameInputs = document.getElementsByClassName('ContractGiver_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Assessment');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }
                                                        // var facilityNameInputs = document.getElementsByClassName('Production_Injection_Feedback');
                                                        // for (var i = 0; i < facilityNameInputs.length; i++) {
                                                        //     inputsToToggle.push(facilityNameInputs[i]);
                                                        // }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';
                                                            console.log(this.value, isRequired, 'value');

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                                console.log(input.required, isRequired, 'input req');
                                                            });

                                                            // Show or hide the asterisk icon based on the selected value
                                                            var asteriskIcon = document.getElementById('asteriskPT');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                            @else
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Contract Giver">Contract Giver Required ?</label>
                                                        <select name="ContractGiver_Review" disabled id="ContractGiver_Review">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->ContractGiver_Review == 'yes') selected @endif value='yes'>
                                                                Yes</option>
                                                            <option @if ($data1->ContractGiver_Review == 'no') selected @endif value='no'>
                                                                No</option>
                                                            <option @if ($data1->ContractGiver_Review == 'na') selected @endif value='na'>
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where([
                                                            'q_m_s_roles_id' => 60,
                                                            'q_m_s_divisions_id' => $data->division_id,
                                                        ])
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver notification">Contract Giver Person <span id="asteriskInvi11"
                                                                style="display: none" class="text-danger">*</span></label>
                                                        <select @if ($data->stage == 6) disabled @endif name="ContractGiver_person"
                                                            id="ContractGiver_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option value="{{ $user->name }}"
                                                                    @if ($user->name == $data1->ContractGiver_person) selected @endif>
                                                                    {{ $user->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                @if ($data->stage == 6)
                                                    <div class="col-md-12 mb-3 ContractGiver">
                                                        <div class="group-input">
                                                            <label for="Contract Giver assessment">Impact Assessment (By Contract Giver)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ContractGiver_assessment" id="summernote-17">{{ $data1->ContractGiver_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 ContractGiver">
                                                        <div class="group-input">
                                                            <label for="Contract Giver feedback">Contract Giver Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea class="tiny" name="ContractGiver_feedback" id="summernote-18">{{ $data1->ContractGiver_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="col-md-12 mb-3 ContractGiver">
                                                        <div class="group-input">
                                                            <label for="Contract Giver assessment">Impact Assessment (By Contract Giver)</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ContractGiver_assessment" id="summernote-17">{{ $data1->ContractGiver_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 ContractGiver">
                                                        <div class="group-input">
                                                            <label for="Contract Giver feedback">Contract Giver Feedback</label>
                                                            <div><small class="text-primary">Please insert "NA" in the data field if it
                                                                    does not require completion</small></div>
                                                            <textarea disabled class="tiny" name="ContractGiver_feedback" id="summernote-18">{{ $data1->ContractGiver_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver attachment">Contract Giver Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="ContractGiver_attachment">
                                                                @if ($data1->ContractGiver_attachment)
                                                                    @foreach (json_decode($data1->ContractGiver_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input disabled {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                    type="file" id="myfile" name="ContractGiver_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'ContractGiver_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 ContractGiver">
                                                    <div class="group-input">
                                                        <label for="Contract Giver Completed By">Contract Giver Completed By</label>
                                                        <input readonly type="text" value="{{ $data1->ContractGiver_by }}"
                                                            name="ContractGiver_by" id="ContractGiver_by">
                                                    </div>
                                                </div>


                                                <div class="col-6 ContractGiver new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Contract Giver Completed On">Contract Giver
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="ContractGiver_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->ContractGiver_on) }}" />
                                                            <input readonly type="date" name="ContractGiver_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'ContractGiver_on')" />
                                                        </div>
                                                        @error('ContractGiver_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                            @endif


                                            @if ($data->stage == 5 || $data->stage == 6)
                                                <div class="sub-head">
                                                    Other's 1 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <script>
                                                    $(document).ready(function() {
                                                        @if ($data1->Other1_review !== 'yes')
                                                            $('.other1_reviews').hide();

                                                            $('[name="Other1_review"]').change(function() {
                                                                if ($(this).val() === 'yes') {
                                                                    $('.other1_reviews').show();
                                                                    $('.other1_reviews span').show();
                                                                } else {
                                                                    $('.other1_reviews').hide();
                                                                    $('.other1_reviews span').hide();
                                                                }
                                                            });
                                                        @endif
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Review Required1"> Other's 1 Review Required? </label>
                                                        <select name="Other1_review" @if ($data->stage == 6) disabled @endif
                                                            id="Other1_review" value="{{ $data1->Other1_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other1_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other1_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other1_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 other1_reviews ">
                                                    <div class="group-input">
                                                        <label for="Person1"> Other's 1 Person <span id="asterisko1"
                                                                style="display: {{ $data1->Other1_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <select name="Other1_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other1_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other1_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>





                                                <div class="col-12 other1_reviews">
                                                    <div class="col-lg-12 Other1_reviews">

                                                        <div class="group-input">
                                                            <label for="Department1">Other's 1 Department
                                                                <span id="asteriskod5"
                                                                    style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                    class="text-danger">*</span>
                                                            </label>
                                                            <select name="Other1_Department_person"
                                                                @if ($data->stage == 6) disabled @endif id="Other1_Department_person"
                                                                {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                                <option value="">-- Select --</option>
                                                                @foreach (Helpers::getDepartments() as $key => $name)
                                                                    <option value="{{ $key }}"
                                                                        @if ($data1->Other1_Department_person == $key) selected @endif>
                                                                        {{ $name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>



                                                    <div class="col-md-12 mb-3 other1_reviews ">
                                                        <div class="group-input">
                                                            <label for="Impact Assessment12">Impact Assessment (By Other's 1)
                                                            </label>
                                                            <textarea @if ($data1->Other1_review == 'yes' && $data->stage == 6) required @endif class="tiny" name="Other1_assessment"
                                                                @if ($data->stage == 5 || Auth::user()->name != $data1->Other1_person) readonly @endif id="summernote-41">{{ $data1->Other1_assessment }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 mb-3 other1_reviews ">
                                                        <div class="group-input">
                                                            <label for="Feedback1"> Other's 1 Feedback
                                                            </label>
                                                            <textarea @if ($data1->Other1_review == 'yes' && $data->stage == 6) required @endif class="tiny" name="Other1_feedback"
                                                                @if ($data->stage == 5 || Auth::user()->name != $data1->Other1_person) readonly @endif id="summernote-42">{{ $data1->Other1_feedback }}</textarea>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            var selectField = document.getElementById('Other1_review');
                                                            var inputsToToggle = [];

                                                            var facilityNameInputs = document.getElementsByClassName('Other1_person');
                                                            for (var i = 0; i < facilityNameInputs.length; i++) {
                                                                inputsToToggle.push(facilityNameInputs[i]);
                                                            }
                                                            var facilityNameInputs = document.getElementsByClassName('Other1_Department_person');
                                                            for (var i = 0; i < facilityNameInputs.length; i++) {
                                                                inputsToToggle.push(facilityNameInputs[i]);
                                                            }

                                                            selectField.addEventListener('change', function() {
                                                                var isRequired = this.value === 'yes';

                                                                inputsToToggle.forEach(function(input) {
                                                                    input.required = isRequired;
                                                                });

                                                                var asteriskIcon = document.getElementById('asterisko1');
                                                                var asteriskIcon1 = document.getElementById('asteriskod1');
                                                                asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                                asteriskIcon1.style.display = isRequired ? 'inline' : 'none';
                                                            });
                                                        });
                                                    </script>
                                                    <div class="col-12 other1_reviews ">
                                                        <div class="group-input">
                                                            <label for="Audit Attachments">Other's 1 Attachments</label>
                                                            <div><small class="text-primary">Please Attach all relevant or supporting
                                                                    documents</small></div>
                                                            <div class="file-attachment-field">
                                                                <div disabled class="file-attachment-list" id="Other1_attachment">
                                                                    @if ($data1->Other1_attachment)
                                                                        @foreach (json_decode($data1->Other1_attachment) as $file)
                                                                            <h6 type="button" class="file-container text-dark"
                                                                                style="background-color: rgb(243, 242, 240);">
                                                                                <b>{{ $file }}</b>
                                                                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                        class="fa fa-eye text-primary"
                                                                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                                                                <a type="button" class="remove-file"
                                                                                    data-file-name="{{ $file }}"><i
                                                                                        class="fa-solid fa-circle-xmark"
                                                                                        style="color:red; font-size:20px;"></i></a>
                                                                            </h6>
                                                                        @endforeach
                                                                    @endif
                                                                </div>
                                                                <div class="add-btn">
                                                                    <div>Add</div>
                                                                    <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }}
                                                                        type="file" id="myfile" name="Other1_attachment[]"
                                                                        oninput="addMultipleFiles(this, 'Other1_attachment')" multiple>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 mb-3 other1_reviews ">
                                                        <div class="group-input">
                                                            <label for="Review Completed By1"> Other's 1 Review Completed By</label>
                                                            <input disabled type="text" value="{{ $data1->Other1_by }}" name="Other1_by"
                                                                id="Other1_by">

                                                        </div>
                                                    </div>

                                                    <div class="col-6 other1_reviews new-date-data-field">
                                                        <div class="group-input input-date">
                                                            <label for="Others 1 Completed On">Others 1
                                                                Review Completed On</label>
                                                            <div class="calenderauditee">
                                                                <input type="text" id="Other1_on" readonly placeholder="DD-MMM-YYYY"
                                                                    value="{{ Helpers::getdateFormat($data1->Other1_on) }}" />
                                                                <input readonly type="date" name="Other1_on"
                                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                    class="hide-input" oninput="handleDateInput(this, 'Other1_on')" />
                                                            </div>
                                                            @error('Other1_on')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="sub-head">
                                                    Other's 2 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <script>
                                                    $(document).ready(function() {
                                                        @if ($data1->Other2_review !== 'yes')
                                                            $('.Other2_reviews').hide();

                                                            $('[name="Other2_review"]').change(function() {
                                                                if ($(this).val() === 'yes') {
                                                                    $('.Other2_reviews').show();
                                                                    $('.Other2_reviews span').show();
                                                                } else {
                                                                    $('.Other2_reviews').hide();
                                                                    $('.Other2_reviews span').hide();
                                                                }
                                                            });
                                                        @endif
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review2"> Other's 2 Review Required ?</label>
                                                        <select name="Other2_review" @if ($data->stage == 6) disabled @endif
                                                            id="Other2_review" value="{{ $data1->Other2_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other2_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other2_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other2_review == 'na') selected @endif value="na">
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Other2_reviews">
                                                    <div class="group-input">
                                                        <label for="Person2"> Other's 2 Person <span id="asterisko2"
                                                                style="display: {{ $data1->Other2_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <select name="Other2_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other2_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other2_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="col-lg-12 Other2_reviews">

                                                    <div class="group-input">
                                                        <label for="Department2">Other's 2 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other2_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other2_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other2_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>



                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Other2_review');
                                                        var inputsToToggle = [];

                                                        var facilityNameInputs = document.getElementsByClassName('Other2_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        var facilityNameInputs = document.getElementsByClassName('Other2_Department_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                            });

                                                            var asteriskIcon = document.getElementById('asterisko2');
                                                            var asteriskIcon1 = document.getElementById('asteriskod2');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                            asteriskIcon1.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 mb-3 Other2_reviews">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment13">Impact Assessment (By Other's 2)
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other2_person) readonly @endif class="tiny" name="Other2_Assessment"
                                                            @if ($data1->Other2_review == 'yes' && $data->stage == 6) required @endif id="summernote-43">{{ $data1->Other2_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Other2_reviews">
                                                    <div class="group-input">
                                                        <label for="Feedback2"> Other's 2 Feedback
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other2_person) readonly @endif class="tiny" name="Other2_feedback"
                                                            @if ($data1->Other2_review == 'yes' && $data->stage == 6) required @endif id="summernote-44">{{ $data1->Other2_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Other2_reviews">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 2 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other2_attachment">
                                                                @if ($data1->Other2_attachment)
                                                                    @foreach (json_decode($data1->Other2_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other2_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other2_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Other2_reviews">
                                                    <div class="group-input">
                                                        <label for="Review Completed By2"> Other's 2 Review Completed By</label>
                                                        <input type="text" name="Other2_by" id="Other2_by" value="{{ $data1->Other2_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                <div class="col-6 Other2_reviews new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Others 2 Completed On">Others 2 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other2_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other2_on) }}" />
                                                            <input readonly type="date" name="Other2_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other2_on')" />
                                                        </div>
                                                        @error('Other2_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="sub-head">
                                                    Other's 3 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <script>
                                                    $(document).ready(function() {
                                                        @if ($data1->Other3_review !== 'yes')
                                                            $('.Other3_reviews').hide();

                                                            $('[name="Other3_review"]').change(function() {
                                                                if ($(this).val() === 'yes') {
                                                                    $('.Other3_reviews').show();
                                                                    $('.Other3_reviews span').show();
                                                                } else {
                                                                    $('.Other3_reviews').hide();
                                                                    $('.Other3_reviews span').hide();
                                                                }
                                                            });
                                                        @endif
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review3"> Other's 3 Review Required ?</label>
                                                        <select name="Other3_review" @if ($data->stage == 6) disabled @endif
                                                            id="Other3_review" value="{{ $data1->Other3_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other3_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other3_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other3_review == 'na') selected @endif value="na">
                                                                NA</option>
                                                        </select>

                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Other3_reviews">
                                                    <div class="group-input">
                                                        <label for="Person3">Other's 3 Person <span id="asterisko3"
                                                                style="display: {{ $data1->Other3_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <select name="Other3_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other3_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other3_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>



                                                <div class="col-lg-12 Other3_reviews">

                                                    <div class="group-input">
                                                        <label for="Department3">Other's 3 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other3_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other3_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other3_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Other3_review');
                                                        var inputsToToggle = [];

                                                        var facilityNameInputs = document.getElementsByClassName('Other3_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        var facilityNameInputs = document.getElementsByClassName('Other3_Department_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                            });

                                                            var asteriskIcon = document.getElementById('asterisko3');
                                                            var asteriskIcon1 = document.getElementById('asteriskod3');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                            asteriskIcon1.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 mb-3 Other3_reviews">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment14">Impact Assessment (By Other's 3)
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other3_person) readonly @endif class="tiny" name="Other3_Assessment"
                                                            @if ($data1->Other3_review == 'yes' && $data->stage == 6) required @endif id="summernote-45">{{ $data1->Other3_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Other3_reviews">
                                                    <div class="group-input">
                                                        <label for="feedback3"> Other's 3 Feedback
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other3_person) readonly @endif class="tiny" name="Other3_feedback"
                                                            @if ($data1->Other3_review == 'yes' && $data->stage == 6) required @endif id="summernote-46">{{ $data1->Other3_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Other3_reviews">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 3 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other3_attachment">
                                                                @if ($data1->Other3_attachment)
                                                                    @foreach (json_decode($data1->Other3_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other3_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other3_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Other3_reviews">
                                                    <div class="group-input">
                                                        <label for="productionfeedback"> Other's 3 Review Completed By</label>
                                                        <input type="text" name="Other3_by" id="Other3_by" value="{{ $data1->Other3_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6 mb-3 Other3_reviews">
                                                                        <div class="group-input">
                                                                            <label for="productionfeedback">Other's 3 Review Completed On</label>
                                                                            <input disabled type="date" name="Other3_on" id="Other3_on"
                                                                                value="{{ $data1->Other3_on }}">
                                                                        </div>
                                                                    </div> --}}
                                                <div class="col-6  new-date-data-field Other3_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 3 Completed On">Others 3 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other3_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other3_on) }}" />
                                                            <input readonly type="date" name="Other3_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other3_on')" />
                                                        </div>
                                                        @error('Other3_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="sub-head">
                                                    Other's 4 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <script>
                                                    $(document).ready(function() {
                                                        @if ($data1->Other4_review !== 'yes')
                                                            $('.Other4_reviews').hide();

                                                            $('[name="Other4_review"]').change(function() {
                                                                if ($(this).val() === 'yes') {
                                                                    $('.Other4_reviews').show();
                                                                    $('.Other4_reviews span').show();
                                                                } else {
                                                                    $('.Other4_reviews').hide();
                                                                    $('.Other4_reviews span').hide();
                                                                }
                                                            });
                                                        @endif
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review4">Other's 4 Review Required ?</label>
                                                        <select name="Other4_review" @if ($data->stage == 6) disabled @endif
                                                            id="Other4_review" value="{{ $data1->Other4_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other4_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other4_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other4_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Other4_reviews">
                                                    <div class="group-input">
                                                        <label for="Person4"> Other's 4 Person <span id="asterisko4"
                                                                style="display: {{ $data1->Other4_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span></label>
                                                        <select name="Other4_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other4_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other4_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-lg-12 Other4_reviews">

                                                    <div class="group-input">
                                                        <label for="Department4">Other's 4 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other4_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other4_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other4_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-12 Other4_reviews">
                                                                        <div class="group-input">
                                                                            <label for="Department4"> Other's 4 Department <span id="asteriskod4"
                                                                                    style="display: {{ $data1->Other4_review == 'yes' ? 'inline' : 'none' }}"
                                                                                    class="text-danger">*</span></label>
                                                                            <select name="Other4_Department_person"
                                                                                @if ($data->stage == 6) disabled @endif
                                                                                id="Other4_Department_person">
                                                                                <option value="">-- Select --</option>
                                                                                <option value="CQA"
                                                                                    @if ($data1->Other4_Department_person == 'CQA') selected @endif>Corporate
                                                                                    Quality Assurance</option>
                                                                                <option value="QA"
                                                                                    @if ($data1->Other4_Department_person == 'QA') selected @endif>Quality
                                                                                    Assurance</option>
                                                                                <option value="QC"
                                                                                    @if ($data1->Other4_Department_person == 'QC') selected @endif>Quality
                                                                                    Control</option>
                                                                                <option value="QM"
                                                                                    @if ($data1->Other4_Department_person == 'QM') selected @endif>Quality
                                                                                    Control (Microbiology department)
                                                                                </option>
                                                                                <option value="PG"
                                                                                    @if ($data1->Other4_Department_person == 'PG') selected @endif>Production
                                                                                    General</option>
                                                                                <option value="PL"
                                                                                    @if ($data1->Other4_Department_person == 'PL') selected @endif>Production
                                                                                    Liquid Orals</option>
                                                                                <option value="PT"
                                                                                    @if ($data1->Other4_Department_person == 'PT') selected @endif>Production
                                                                                    Tablet and Powder</option>
                                                                                <option value="PE"
                                                                                    @if ($data1->Other4_Department_person == 'PE') selected @endif>Production
                                                                                    External (Ointment, Gels, Creams and
                                                                                    Liquid)</option>
                                                                                <option value="PC"
                                                                                    @if ($data1->Other4_Department_person == 'PC') selected @endif>Production
                                                                                    Capsules</option>
                                                                                <option value="PI"
                                                                                    @if ($data1->Other4_Department_person == 'PI') selected @endif>Production
                                                                                    Injectable</option>
                                                                                <option value="EN"
                                                                                    @if ($data1->Other4_Department_person == 'EN') selected @endif>Engineering
                                                                                </option>
                                                                                <option value="HR"
                                                                                    @if ($data1->Other4_Department_person == 'HR') selected @endif>Human
                                                                                    Resource</option>
                                                                                <option value="ST"
                                                                                    @if ($data1->Other4_Department_person == 'ST') selected @endif>Store
                                                                                </option>
                                                                                <option value="IT"
                                                                                    @if ($data1->Other4_Department_person == 'IT') selected @endif>Electronic
                                                                                    Data Processing
                                                                                </option>
                                                                                <option value="FD"
                                                                                    @if ($data1->Other4_Department_person == 'FD') selected @endif>Formulation
                                                                                    Development
                                                                                </option>
                                                                                <option value="AL"
                                                                                    @if ($data1->Other4_Department_person == 'AL') selected @endif>Analytical
                                                                                    research and Development
                                                                                    Laboratory
                                                                                </option>
                                                                                <option value="PD"
                                                                                    @if ($data1->Other4_Department_person == 'PD') selected @endif>Packaging
                                                                                    Development
                                                                                </option>
                                                                                <option value="PU"
                                                                                    @if ($data1->Other4_Department_person == 'PU') selected @endif>Purchase
                                                                                    Department
                                                                                </option>
                                                                                <option value="DC"
                                                                                    @if ($data1->Other4_Department_person == 'DC') selected @endif>Document Cell
                                                                                </option>
                                                                                <option value="RA"
                                                                                    @if ($data1->Other4_Department_person == 'RA') selected @endif>Regulatory
                                                                                    Affairs
                                                                                </option>
                                                                                <option value="PV"
                                                                                    @if ($data1->Other4_Department_person == 'PV') selected @endif>
                                                                                    Pharmacovigilance
                                                                                </option>

                                                                            </select>

                                                                        </div>
                                                                    </div> -->
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Other4_review');
                                                        var inputsToToggle = [];

                                                        var facilityNameInputs = document.getElementsByClassName('Other4_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        var facilityNameInputs = document.getElementsByClassName('Other4_Department_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                            });

                                                            var asteriskIcon = document.getElementById('asterisko4');
                                                            var asteriskIcon1 = document.getElementById('asteriskod4');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                            asteriskIcon1.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 mb-3 Other4_reviews">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment15">Impact Assessment (By Other's 4)
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other4_person) readonly @endif class="tiny" name="Other4_Assessment"
                                                            @if ($data1->Other4_review == 'yes' && $data->stage == 6) required @endif id="summernote-47">{{ $data1->Other4_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Other4_reviews">
                                                    <div class="group-input">
                                                        <label for="feedback4"> Other's 4 Feedback
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other4_person) readonly @endif class="tiny" name="Other4_feedback"
                                                            @if ($data1->Other4_review == 'yes' && $data->stage == 6) required @endif id="summernote-48">{{ $data1->Other4_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12 Other4_reviews">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 4 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other4_attachment">
                                                                @if ($data1->Other4_attachment)
                                                                    @foreach (json_decode($data1->Other4_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other4_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other4_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Other4_reviews">
                                                    <div class="group-input">
                                                        <label for="Review Completed By4"> Other's 4 Review Completed By</label>
                                                        <input type="text" name="Other4_by" id="Other4_by" value="{{ $data1->Other4_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                <div class="col-6  new-date-data-field Other3_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 4 Completed On">Others 4 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other4_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other4_on) }}" />
                                                            <input readonly type="date" name="Other4_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other4_on')" />
                                                        </div>
                                                        @error('Other4_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>



                                                <div class="sub-head">
                                                    Other's 5 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <script>
                                                    $(document).ready(function() {
                                                        @if ($data1->Other5_review !== 'yes')
                                                            $('.Other5_reviews').hide();

                                                            $('[name="Other5_review"]').change(function() {
                                                                if ($(this).val() === 'yes') {
                                                                    $('.Other5_reviews').show();
                                                                    $('.Other5_reviews span').show();
                                                                } else {
                                                                    $('.Other5_reviews').hide();
                                                                    $('.Other5_reviews span').hide();
                                                                }
                                                            });
                                                        @endif
                                                    });
                                                </script>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review5">Other's 5 Review Required ?</label>
                                                        <select name="Other5_review" @if ($data->stage == 6) disabled @endif
                                                            id="Other5_review" value="{{ $data1->Other5_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other5_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other5_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other5_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="Person5">Other's 5 Person
                                                            <span id="asterisko5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other5_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other5_person">
                                                            <option value="0">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other5_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>


                                                <div class="col-lg-12 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="Department5">Other's 5 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other5_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other5_Department_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other5_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>

                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        var selectField = document.getElementById('Other5_review');
                                                        var inputsToToggle = [];

                                                        var facilityNameInputs = document.getElementsByClassName('Other5_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }
                                                        var facilityNameInputs = document.getElementsByClassName('Other5_Department_person');
                                                        for (var i = 0; i < facilityNameInputs.length; i++) {
                                                            inputsToToggle.push(facilityNameInputs[i]);
                                                        }

                                                        selectField.addEventListener('change', function() {
                                                            var isRequired = this.value === 'yes';

                                                            inputsToToggle.forEach(function(input) {
                                                                input.required = isRequired;
                                                            });

                                                            var asteriskIcon = document.getElementById('asterisko5');
                                                            var asteriskIcon1 = document.getElementById('asteriskod5');
                                                            asteriskIcon.style.display = isRequired ? 'inline' : 'none';
                                                            asteriskIcon1.style.display = isRequired ? 'inline' : 'none';
                                                        });
                                                    });
                                                </script>
                                                <div class="col-md-12 mb-3 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment16">Impact Assessment (By Other's 5)
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other5_person) readonly @endif class="tiny"
                                                            name="Other5_Assessment"@if ($data1->Other5_review == 'yes' && $data->stage == 6) required @endif id="summernote-49">{{ $data1->Other5_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="productionfeedback"> Other's 5 Feedback
                                                        </label>
                                                        <textarea @if ($data->stage == 5 || Auth::user()->name != $data1->Other5_person) readonly @endif class="tiny"
                                                            name="Other5_feedback"@if ($data1->Other5_review == 'yes' && $data->stage == 6) required @endif id="summernote-50">{{ $data1->Other5_feedback }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 5 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other5_attachment">
                                                                @if ($data1->Other5_attachment)
                                                                    @foreach (json_decode($data1->Other5_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other5_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other5_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3 Other5_reviews">
                                                    <div class="group-input">
                                                        <label for="Review Completed By5"> Other's 5 Review Completed By</label>
                                                        <input type="text" name="Other5_by" id="Other5_by" value="{{ $data1->Other5_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6 mb-3 Other5_reviews">
                                                                        <div class="group-input">
                                                                            <label for="Review Completed On5">Other's 5 Review Completed On</label>
                                                                            <input disabled type="date" name="Other5_on" id="Other5_on"
                                                                                value="{{ $data1->Other5_on }}">
                                                                        </div>
                                                                    </div> --}}
                                                <div class="col-6  new-date-data-field Other5_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 5 Completed On">Others 5 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other5_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other5_on) }}" />
                                                            <input readonly type="date" name="Other5_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other5_on')" />
                                                        </div>
                                                        @error('Other5_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @else
                                                <div class="sub-head">
                                                    Other's 1 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Review Required1"> Other's 1 Review Required? </label>
                                                        <select disabled
                                                            name="Other1_review"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other1_review" value="{{ $data1->Other1_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other1_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other1_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other1_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Person1"> Other's 1 Person </label>
                                                        <select disabled
                                                            name="Other1_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other1_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other1_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-lg-12">

                                                    <div class="group-input">
                                                        <label for="Department1">Other's 1 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other1_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other1_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other1_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>



                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment12">Impact Assessment (By Other's 1)</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other1_assessment"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-41">{{ $data1->Other1_assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Feedback1"> Other's 1 Feedback</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other1_feedback"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-42">{{ $data1->Other1_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 1 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other1_attachment">
                                                                @if ($data1->Other1_attachment)
                                                                    @foreach (json_decode($data1->Other1_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other1_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other1_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="group-input">
                                                        <label for="Review Completed By1"> Other's 1 Review Completed By</label>
                                                        <input disabled type="text" value="{{ $data1->Other1_by }}" name="Other1_by"
                                                            id="Other1_by">

                                                    </div>
                                                </div>
                                                <div class="col-6 other1_reviews new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Others 1 Completed On">Others 1 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other1_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other1_on) }}" />
                                                            <input readonly type="date" name="Other1_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other1_on')" />
                                                        </div>
                                                        @error('Other1_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="sub-head">
                                                    Other's 2 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review2"> Other's 2 Review Required ?</label>
                                                        <select disabled
                                                            name="Other2_review"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other2_review" value="{{ $data1->Other2_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other2_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other2_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other2_review == 'na') selected @endif value="na">
                                                                NA</option>
                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Person2"> Other's 2 Person</label>
                                                        <select disabled
                                                            name="Other2_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other2_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other2_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>


                                                <div class="col-lg-12 Other2_reviews">

                                                    <div class="group-input">
                                                        <label for="Department2">Other's 2 Department
                                                            <span id="asteriskod5"
                                                                style="display: {{ $data1->Other5_review == 'yes' ? 'inline' : 'none' }}"
                                                                class="text-danger">*</span>
                                                        </label>
                                                        <select name="Other2_Department_person" @if ($data->stage == 6) disabled @endif
                                                            id="Other2_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}>
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other2_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>




                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment13">Impact Assessment (By Other's 2)</label>
                                                        <textarea disabled ="summernote"
                                                            name="Other2_Assessment"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-43">{{ $data1->Other2_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Feedback2"> Other's 2 Feedback</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other2_feedback"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-44">{{ $data1->Other2_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 2 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other2_attachment">
                                                                @if ($data1->Other2_attachment)
                                                                    @foreach (json_decode($data1->Other2_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other2_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other2_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="group-input">
                                                        <label for="Review Completed By2"> Other's 2 Review Completed By</label>
                                                        <input type="text" name="Other2_by" id="Other2_by" value="{{ $data1->Other2_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6 mb-3">
                                                                        <div class="group-input">
                                                                            <label for="Review Completed On2">Other's 2 Review Completed On</label>
                                                                            <input disabled type="date" name="Other2_on" id="Other2_on"
                                                                                value="{{ $data1->Other2_on }}">
                                                                        </div>
                                                                    </div> --}}
                                                <div class="col-6 Other2_reviews new-date-data-field">
                                                    <div class="group-input input-date">
                                                        <label for="Others 2 Completed On">Others 2 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other2_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other2_on) }}" />
                                                            <input readonly type="date" name="Other2_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other2_on')" />
                                                        </div>
                                                        @error('Other2_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>

                                                <div class="sub-head">
                                                    Other's 3 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review3"> Other's 3 Review Required ?</label>
                                                        <select disabled
                                                            name="Other3_review"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other3_review" value="{{ $data1->Other3_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other3_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other3_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other3_review == 'na') selected @endif value="na">
                                                                NA</option>
                                                        </select>

                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Person3">Other's 3 Person</label>
                                                        <select disabled
                                                            name="Other3_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other3_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other3_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach

                                                        </select>

                                                    </div>
                                                </div>


                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for="Department3">Other's 3 Department

                                                        </label>
                                                        <select name="Other3_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other3_Department_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other3_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment14">Impact Assessment (By Other's 3)</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other3_Assessment"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-45">{{ $data1->Other3_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="feedback3"> Other's 3 Feedback</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other3_feedback"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-46">{{ $data1->Other3_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 3 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other3_attachment">
                                                                @if ($data1->Other3_attachment)
                                                                    @foreach (json_decode($data1->Other3_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other3_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other3_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="group-input">
                                                        <label for="productionfeedback"> Other's 3 Review Completed By</label>
                                                        <input type="text" name="Other3_by" id="Other3_by" value="{{ $data1->Other3_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                <div class="col-6  new-date-data-field Other3_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 3 Completed On">Others 3 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other3_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other3_on) }}" />
                                                            <input readonly type="date" name="Other3_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other3_on')" />
                                                        </div>
                                                        @error('Other3_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="sub-head">
                                                    Other's 4 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review4">Other's 4 Review Required ?</label>
                                                        <select disabled
                                                            name="Other4_review"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other4_review" value="{{ $data1->Other4_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other4_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other4_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other4_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>

                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Person4"> Other's 4 Person</label>
                                                        <select name="Other4_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other4_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other4_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>



                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for="Department5">Other's 4 Department

                                                        </label>
                                                        <select name="Other4_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other4_Department_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other4_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>



                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment15">Impact Assessment (By Other's 4)</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other4_Assessment"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-47">{{ $data1->Other4_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="feedback4"> Other's 4 Feedback</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other4_feedback"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-48">{{ $data1->Other4_feedback }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 4 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other4_attachment">
                                                                @if ($data1->Other4_attachment)
                                                                    @foreach (json_decode($data1->Other4_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other4_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other4_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="group-input">
                                                        <label for="Review Completed By4"> Other's 4 Review Completed By</label>
                                                        <input type="text" name="Other4_by" id="Other4_by" value="{{ $data1->Other4_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                {{-- <div class="col-md-6 mb-3">
                                                                        <div class="group-input">
                                                                            <label for="Review Completed On4">Other's 4 Review Completed On</label>
                                                                            <input disabled type="date" name="Other4_on" id="Other4_on"
                                                                                value="{{ $data1->Other4_on }}">

                                                                        </div>
                                                                    </div> --}}
                                                <div class="col-6  new-date-data-field Other3_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 4 Completed On">Others 4 Review
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other4_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other4_on) }}" />
                                                            <input readonly type="date" name="Other4_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other4_on')" />
                                                        </div>
                                                        @error('Other4_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>



                                                <div class="sub-head">
                                                    Other's 5 ( Additional Person Review From Departments If Required)
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="review5">Other's 5 Review Required ?</label>
                                                        <select disabled
                                                            name="Other5_review"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other5_review" value="{{ $data1->Other5_review }}">
                                                            <option value="">-- Select --</option>
                                                            <option @if ($data1->Other5_review == 'yes') selected @endif value="yes">
                                                                Yes</option>
                                                            <option @if ($data1->Other5_review == 'no') selected @endif value="no">
                                                                No</option>
                                                            <option @if ($data1->Other5_review == 'na') selected @endif value="na">
                                                                NA</option>

                                                        </select>

                                                    </div>
                                                </div>
                                                @php
                                                    $userRoles = DB::table('user_roles')
                                                        ->where(['q_m_s_divisions_id' => $data->division_id])
                                                        ->select('user_id')
                                                        ->distinct()
                                                        ->get();
                                                    $userRoleIds = $userRoles->pluck('user_id')->toArray();
                                                    $users = DB::table('users')->whereIn('id', $userRoleIds)->get(); // Fetch user data based on user IDs
                                                @endphp
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Person5">Other's 5 Person</label>
                                                        <select disabled
                                                            name="Other5_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other5_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($users as $user)
                                                                <option {{ $data1->Other5_person == $user->name ? 'selected' : '' }}
                                                                    value="{{ $user->name }}">{{ $user->name }}</option>
                                                            @endforeach
                                                        </select>

                                                    </div>
                                                </div>


                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for="Department5">Other's 5 Department

                                                        </label>
                                                        <select name="Other5_Department_person"
                                                            {{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                            id="Other5_Department_person">
                                                            <option value="">-- Select --</option>
                                                            @foreach (Helpers::getDepartments() as $key => $name)
                                                                <option value="{{ $key }}"
                                                                    @if ($data1->Other5_Department_person == $key) selected @endif>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-lg-12">
                                                                        <div class="group-input">
                                                                            <label for="Department5"> Other's 5 Department</label>
                                                                            <select disabled
                                                                                name="Other5_Department_person"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }}
                                                                                id="Other5_Department_person">
                                                                                <option value="">-- Select --</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Production') selected @endif
                                                                                    value="Production">
                                                                                    Production</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Warehouse') selected @endif
                                                                                    value="Warehouse"> Warehouse
                                                                                </option>
                                                                                <option @if ($data1->Other5_Department_person == 'Quality_Control') selected @endif
                                                                                    value="Quality_Control">
                                                                                    Quality Control
                                                                                </option>
                                                                                <option @if ($data1->Other5_Department_person == 'Quality_Assurance') selected @endif
                                                                                    value="Quality_Assurance">
                                                                                    Quality
                                                                                    Assurance</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Engineering') selected @endif
                                                                                    value="Engineering">
                                                                                    Engineering</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Analytical_Development_Laboratory') selected @endif
                                                                                    value="Analytical_Development_Laboratory">Analytical Development
                                                                                    Laboratory</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Process_Development_Lab') selected @endif
                                                                                    value="Process_Development_Lab">Process
                                                                                    Development Laboratory / Kilo Lab
                                                                                </option>
                                                                                <option @if ($data1->Other5_Department_person == 'Technology transfer/Design') selected @endif
                                                                                    value="Technology transfer/Design">
                                                                                    Technology Transfer/Design</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Environment, Health & Safety') selected @endif
                                                                                    value="Environment, Health & Safety">
                                                                                    Environment, Health & Safety</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Human Resource & Administration') selected @endif
                                                                                    value="Human Resource & Administration">
                                                                                    Human Resource & Administration
                                                                                </option>
                                                                                <option @if ($data1->Other5_Department_person == 'Information Technology') selected @endif
                                                                                    value="Information Technology">
                                                                                    Information Technology</option>
                                                                                <option @if ($data1->Other5_Department_person == 'Project management') selected @endif
                                                                                    value="Project management">
                                                                                    Project
                                                                                    management</option>
                                                                            </select>

                                                                        </div>
                                                                    </div> -->
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="Impact Assessment16">Impact Assessment (By Other's 5)</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other5_Assessment"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-49">{{ $data1->Other5_Assessment }}</textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 mb-3">
                                                    <div class="group-input">
                                                        <label for="productionfeedback"> Other's 5 Feedback</label>
                                                        <textarea disabled class="tiny"
                                                            name="Other5_feedback"{{ $data->stage == 0 || $data->stage == 12 ? 'disabled' : '' }} id="summernote-50">{{ $data1->Other5_feedback }}</textarea>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Audit Attachments">Other's 5 Attachments</label>
                                                        <div><small class="text-primary">Please Attach all relevant or supporting
                                                                documents</small></div>
                                                        <div class="file-attachment-field">
                                                            <div disabled class="file-attachment-list" id="Other5_attachment">
                                                                @if ($data1->Other5_attachment)
                                                                    @foreach (json_decode($data1->Other5_attachment) as $file)
                                                                        <h6 type="button" class="file-container text-dark"
                                                                            style="background-color: rgb(243, 242, 240);">
                                                                            <b>{{ $file }}</b>
                                                                            <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                                                                    class="fa fa-eye text-primary"
                                                                                    style="font-size:20px; margin-right:-10px;"></i></a>
                                                                            <a type="button" class="remove-file"
                                                                                data-file-name="{{ $file }}"><i
                                                                                    class="fa-solid fa-circle-xmark"
                                                                                    style="color:red; font-size:20px;"></i></a>
                                                                        </h6>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="add-btn">
                                                                <div>Add</div>
                                                                <input {{ $data->stage == 0 || $data->stage == 6 ? 'disabled' : '' }} type="file"
                                                                    id="myfile" name="Other5_attachment[]"
                                                                    oninput="addMultipleFiles(this, 'Other5_attachment')" multiple>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <div class="group-input">
                                                        <label for="Review Completed By5"> Other's 5 Review Completed By</label>
                                                        <input type="text" name="Other5_by" id="Other5_by" value="{{ $data1->Other5_by }}"
                                                            disabled>

                                                    </div>
                                                </div>
                                                <div class="col-6  new-date-data-field Other5_reviews">
                                                    <div class="group-input input-date">
                                                        <label for="Others 5 Completed On">Others 5
                                                            Completed On</label>
                                                        <div class="calenderauditee">
                                                            <input type="text" id="Other5_on" readonly placeholder="DD-MMM-YYYY"
                                                                value="{{ Helpers::getdateFormat($data1->Other5_on) }}" />
                                                            <input readonly type="date" name="Other5_on"
                                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" value=""
                                                                class="hide-input" oninput="handleDateInput(this, 'Other5_on')" />
                                                        </div>
                                                        @error('Other5_on')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            @endif

                                        </div>
                                        <div class="button-block">
                                            <button style=" justify-content: center; width: 4rem; margin-left: 1px;;"
                                                type="submit"{{ $data->stage == 0 || $data->stage == 7 || $data->stage == 9 ? 'disabled' : '' }}
                                                id="ChangesaveButton" class="saveButton saveAuditFormBtn d-flex" style="align-items: center;">
                                                <div class="spinner-border spinner-border-sm auditFormSpinner" style="display: none"
                                                    role="status">
                                                    <span class="sr-only">Loading...</span>
                                                </div>
                                                Save
                                            </button>
                                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                                            <button style=" justify-content: center; width: 4rem; margin-left: 1px;;"
                                                type="button"{{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }} id="ChangeNextButton"
                                                class="nextButton">Next</button>

                                            <button style=" justify-content: center; width: 4rem; margin-left: 1px;;" type="button"> <a
                                                    href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                                    Exit </a> </button>
                                        </div>
                                    </div>
                                </div>



                                <div id="CCForm6" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="row">
                                            <div class="sub-head">
                                                Outcome
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Resolution Status</label>
                                                    <div class="relative-container">
                                                        <select id="resolution_status" name="resolution_status">
                                                            <option value="">Select Resolution Status</option>
                                                            <option value="Answered"
                                                                @if ($data->resolution_status == 'Answered') selected @endif>Answered
                                                            </option>
                                                            <option value="Referred"
                                                                @if ($data->resolution_status == 'Referred') selected @endif>Referred
                                                            </option>
                                                            <option value="Not Applicable"
                                                                @if ($data->resolution_status == 'Not Applicable') selected @endif>Not
                                                                Applicable</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="Short Description">Category Tags</label>
                                                    <div class="relative-container">
                                                        <select id="category_tags" name="category_tags[]" multiple>
                                                            <option value="">Select Category Tags</option>
                                                            <option value="Tag 1"
                                                                {{ strpos($data->category_tags, 'Tag 1') !== false ? 'selected' : '' }}>
                                                                Tag 1</option>
                                                            <option value="Tag 2"
                                                                {{ strpos($data->category_tags, 'Tag 2') !== false ? 'selected' : '' }}>
                                                                Tag 2</option>
                                                            <option value="Tag 3"
                                                                {{ strpos($data->category_tags, 'Tag 3') !== false ? 'selected' : '' }}>
                                                                Tag 3</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="group-input">
                                                <label for="qa-eval-comments">Response Details</label>
                                                <div class="relative-container">
                                                    <textarea name="response_details">{{ $data->response_details }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>

                                            <div class="group-input">
                                                <label for="qa-eval-comments">Follow-up Actions</label>
                                                <div class="relative-container">
                                                    <textarea name="followup_action">{{ $data->followup_action }}</textarea>
                                                    @component('frontend.forms.language-model')
                                                    @endcomponent
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <div class="group-input">
                                                    <label for="others">Supporting Documents</label>
                                                    <div><small class="text-primary">Please Attach all relevant or
                                                            supporting documents</small></div>
                                                    <div class="file-attachment-field">
                                                        <div disabled class="file-attachment-list" id="supporting_doc">
                                                            @if ($data->supporting_doc)
                                                                @foreach (json_decode($data->supporting_doc) as $file)
                                                                    <h6 type="button" class="file-container text-dark"
                                                                        style="background-color: rgb(243, 242, 240);">
                                                                        <b>{{ $file }}</b>
                                                                        <a href="{{ asset('upload/' . $file) }}"
                                                                            target="_blank"><i
                                                                                class="fa fa-eye text-primary"
                                                                                style="font-size:20px; margin-right:-10px;"></i></a>
                                                                        <a type="button" class="remove-file"
                                                                            data-file-name="{{ $file }}"><i
                                                                                class="fa-solid fa-circle-xmark"
                                                                                style="color:red; font-size:20px;"></i></a>
                                                                    </h6>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                        <div class="add-btn">
                                                            <div>Add</div>
                                                            <input type="file" id="myfile"
                                                                name="supporting_doc[]"
                                                                oninput="addMultipleFiles(this, 'supporting_doc')"
                                                                multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="button-block">
                                            <button type="submit" class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button" class="nextButton"
                                                onclick="nextStep()">Next</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}"> Exit </a> </button>
                                        </div>
                                    </div>
                                </div>

                                <div id="CCForm7" class="inner-block cctabcontent">
                                    <div class="inner-block-content">
                                        <div class="sub-head">
                                            Electronic Signatures
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Submit By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Submit On</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">HOD Review Complete By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">HOD Review Complete On</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Send to CFT/SME/QA Review By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Send to CFT/SME/QA Review On</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT/SME/QA Review Not required By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">CFT/SME/QA Review Not required On</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Review Completed By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Review Completed On</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Implemented By</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="group-input">
                                                    <label for="submitted">Implemented On</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="button-block">
                                            <button type="submit" value="save" name="submit"
                                                class="saveButton">Save</button>
                                            <button type="button" class="backButton"
                                                onclick="previousStep()">Back</button>
                                            <button type="button"> <a class="text-white"
                                                    href="{{ url('rcms/qms-dashboard') }}">
                                                    Exit
                                                </a> </button>
                                            <button type="submit">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </form>
                    </div>
                </div>


                <!-- Stage Modal Code Starts -->

                <div class="modal fade" id="signature-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('send-stage', $data->id) }}" method="POST">
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
                                        <input type="password" name="password" >
                                    </div>
                                    <div class="group-input">
                                        <label for="comment">Comment</label>
                                        <input type="comment" name="comment">
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

                <div class="modal fade" id="rejection-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('stage-reject', $data->id) }}" method="POST">
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
                                        <input type="text" name="username" required class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="comment">Comment <span class="text-danger">*</span></label>
                                        <input type="comment" name="comment" required class="form-control">
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

                <div class="modal fade" id="HOD-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('sendTo-HOD', $data->id) }}" method="POST">
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
                                        <input type="text" name="username" required class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="comment">Comment <span class="text-danger">*</span></label>
                                        <input type="comment" name="comment" required class="form-control">
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

                <div class="modal fade" id="Initiator-modal">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <form action="{{ route('sendTo-initiator', $data->id) }}" method="POST">
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
                                        <input type="text" name="username" required class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="password">Password <span class="text-danger">*</span></label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                    <div class="group-input">
                                        <label for="comment">Comment <span class="text-danger">*</span></label>
                                        <input type="comment" name="comment" required class="form-control">
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

                <div class="modal fade" id="child-modal1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">

                            <div class="modal-header">
                                <h4 class="modal-title">Child</h4>
                            </div>
                            <form action="{{ route('action-item-child', $data->id) }}" method="POST">
                                @csrf
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <div class="group-input">
                                        @if($data->stage == 3 || $data->stage == 5)
                                            <label for="major">
                                                <input type="radio" name="child_type" value="actionItem">
                                                Action Item
                                            </label>
                                        @endif
                                    </div>
                                </div>

                                <!-- Modal footer -->
                                <div class="modal-footer">
                                    <button type="button" data-bs-dismiss="modal">Close</button>
                                    <button type="submit">Continue</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>

                <!-- Stage Modal Code Ends -->


                <style>
                    #productTable,
                    #materialTable {
                        display: none;
                    }
                </style>

                <style>
                    .main-head {
                        display: flex;
                        justify-content: space-around;
                        gap: 12px;
                    }

                    .label-head {
                        display: flex !important;
                        gap: 14px;
                    }

                    .input-head {
                        margin-top: 4px;
                    }
                </style>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const currentStage = document.getElementById('stage').value;

                        if (currentStage > 2) {
                            const RA_Review = document.getElementById('RA_Review').value;
                            const qualityAssurnce = document.getElementById('Quality_Assurance_Review').value;
                            const Production_Table_Review = document.getElementById('Production_Table_Review').value;
                            const ProductionLiquid_Review = document.getElementById('ProductionLiquid_Review').value;
                            const Production_Injection_Review = document.getElementById('Production_Injection_Review').value;
                            const Store_Review = document.getElementById('Store_Review').value;
                            const Quality_review = document.getElementById('Quality_review').value;
                            const ResearchDevelopment_Review = document.getElementById('ResearchDevelopment_Review').value;
                            const Engineering_review = document.getElementById('Engineering_review').value;
                            const Human_Resource_review = document.getElementById('Human_Resource_review').value;
                            const Microbiology_Review = document.getElementById('Microbiology_Review').value;
                            const RegulatoryAffair_Review = document.getElementById('RegulatoryAffair_Review').value;
                            const CorporateQualityAssurance_Review = document.getElementById('CorporateQualityAssurance_Review')
                                .value;
                            const Environment_Health_review = document.getElementById('Environment_Health_review').value;
                            const Information_Technology_review = document.getElementById('Information_Technology_review')
                                .value;
                            const ContractGiver_Review = document.getElementById('ContractGiver_Review').value;


                            function updateFieldAttributes() {
                                if (currentStage == 3) {
                                    RA_Review.required = true;
                                    qualityAssurnce.required = true;
                                    Production_Table_Review.required = true;
                                    ProductionLiquid_Review.required = true;
                                    Production_Injection_Review.required = true;
                                    Store_Review.required = true;
                                    Quality_review.required = true;
                                    ResearchDevelopment_Review.required = true;
                                    Engineering_review.required = true;
                                    Human_Resource_review.required = true;
                                    Microbiology_Review.required = true;
                                    RegulatoryAffair_Review.required = true;
                                    CorporateQualityAssurance_Review.required = true;
                                    Environment_Health_review.required = true;
                                    Information_Technology_review.required = true;
                                    ContractGiver_Review.required = true;

                                    RA_Review.disabled = false;
                                    qualityAssurnce.disabled = false;
                                    Production_Table_Review.disabled = false;
                                    ProductionLiquid_Review.disabled = false;
                                    Production_Injection_Review.disabled = false;
                                    Store_Review.disabled = false;
                                    Quality_review.disabled = false;
                                    ResearchDevelopment_Review.disabled = false;
                                    Engineering_review.disabled = false;
                                    Human_Resource_review.disabled = false;
                                    Microbiology_Review.disabled = false;
                                    RegulatoryAffair_Review.disabled = false;
                                    CorporateQualityAssurance_Review.disabled = false;
                                    Environment_Health_review.disabled = false;
                                    Information_Technology_review.disabled = false;
                                    ContractGiver_Review.disabled = false;
                                } else if (currentStage == 4) {
                                    RA_Review.required = false;
                                    qualityAssurnce.required = false;
                                    Production_Table_Review.required = false;
                                    ProductionLiquid_Review.required = false;
                                    Production_Injection_Review.required = false;
                                    Store_Review.required = false;
                                    Quality_review.required = false;
                                    ResearchDevelopment_Review.required = false;
                                    Engineering_review.required = false;
                                    Human_Resource_review.required = false;
                                    Microbiology_Review.required = false;
                                    RegulatoryAffair_Review.required = false;
                                    CorporateQualityAssurance_Review.required = false;
                                    Environment_Health_review.required = false;
                                    Information_Technology_review.required = false;
                                    ContractGiver_Review.required = false;

                                    RA_Review.disabled = true;
                                    qualityAssurnce.disabled = true;
                                    Production_Table_Review.disabled = true;
                                    ProductionLiquid_Review.disabled = true;
                                    Production_Injection_Review.disabled = true;
                                    Store_Review.disabled = true;
                                    Quality_review.disabled = true;
                                    ResearchDevelopment_Review.disabled = true;
                                    Engineering_review.disabled = true;
                                    Human_Resource_review.disabled = true;
                                    Microbiology_Review.disabled = true;
                                    RegulatoryAffair_Review.disabled = true;
                                    CorporateQualityAssurance_Review.disabled = true;
                                    Environment_Health_review.disabled = true;
                                    Information_Technology_review.disabled = true;
                                    ContractGiver_Review.disabled = true;
                                }
                            }
                            updateFieldAttributes();
                            document.getElementById('CCFormInput').addEventListener('submit', function() {
                                if (currentStage == 4) {
                                    RA_Review.disabled = false;
                                    qualityAssurnce.disabled = false;
                                    Production_Table_Review.disabled = false;
                                    ProductionLiquid_Review.disabled = false;
                                    Production_Injection_Review.disabled = false;
                                    Store_Review.disabled = false;
                                    Quality_review.disabled = false;
                                    ResearchDevelopment_Review.disabled = false;
                                    Engineering_review.disabled = false;
                                    Human_Resource_review.disabled = false;
                                    Microbiology_Review.disabled = false;
                                    RegulatoryAffair_Review.disabled = false;
                                    CorporateQualityAssurance_Review.disabled = false;
                                    Environment_Health_review.disabled = false;
                                    Information_Technology_review.disabled = false;
                                    ContractGiver_Review.disabled = false;
                                }
                            });
                        }
                    });
                </script>

                <script>
                    VirtualSelect.init({
                        ele: '#refrenece_document, #category_tags, #risk_assessment_related_record, #concerned_department_review,#departments,#customer_nitification ,#Change_Application,#ref_qms_no,#responsible_department'
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
                        $('#add-input').click(function() {
                            var lastInput = $('.bar input:last');
                            var newInput = $('<input type="text" name="review_comment">');
                            lastInput.after(newInput);
                        });
                    });
                </script>

                <!-- Example Blade View -->
                <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.min.css" rel="stylesheet">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>


                <script>
                    $(document).ready(function() {
                        var disableInputs = {{ $data->stage }}; // Replace with your condition

                        if (disableInputs == 0 || disableInputs > 13) {
                            // Disable all input fields within the form
                            $('#CCFormInput :input:not(select)').prop('disabled', true);
                            $('#CCFormInput select').prop('disabled', true);
                        } else {
                            // $('#CCFormInput :input').prop('disabled', false);
                        }
                    });
                </script>


                <script>
                    const productSelect = document.getElementById('productSelect');
                    const productTable = document.getElementById('productTable');
                    const materialSelect = document.getElementById('materialSelect');
                    const materialTable = document.getElementById('materialTable');

                    materialSelect.addEventListener('change', function() {
                        if (materialSelect.value === 'yes') {
                            materialTable.style.display = 'block';
                        } else {
                            materialTable.style.display = 'none';
                        }
                    });

                    productSelect.addEventListener('change', function() {
                        if (productSelect.value === 'yes') {
                            productTable.style.display = 'block';
                        } else {
                            productTable.style.display = 'none';
                        }
                    });
                </script>

                <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $(document).on('click', '.remove-file', function() {
                            $(this).closest('.file-container').remove();
                        });
                    });
                </script>

                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const removeButtons = document.querySelectorAll('.remove-file');

                        removeButtons.forEach(button => {
                            button.addEventListener('click', function() {
                                const fileName = this.getAttribute('data-file-name');
                                const fileContainer = this.parentElement;

                                // Hide the file container
                                if (fileContainer) {
                                    fileContainer.style.display = 'none';
                                }
                            });
                        });
                    });
                </script>
                <script>
                    $(document).on('click', '.removeRowBtn', function() {
                        $(this).closest('tr').remove();
                    })
                </script>
                <script>
                    // JavaScript
                    document.getElementById('initiator_group').addEventListener('change', function() {
                        var selectedValue = this.value;
                        document.getElementById('initiator_group_code').value = selectedValue;
                    });
                </script>
                <script>
                    var maxLength = 255;
                    $('#docname').keyup(function() {
                        var textlen = maxLength - $(this).val().length;
                        $('#rchars').text(textlen);
                    });
                </script>

                <script>
                    $(document).ready(function() {
                        $('.remove-file').click(function() {
                            const removeId = $(this).data('remove-id')
                            console.log('removeId', removeId);
                            $('#' + removeId).remove();
                        })
                    })
                </script>

                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                <script>
                    $(document).ready(function() { //DISABLED PAST DATES IN APPOINTMENT DATE
                        var dateToday = new Date();
                        var month = dateToday.getMonth() + 1;
                        var day = dateToday.getDate();
                        var year = dateToday.getFullYear();

                        if (month < 10)
                            month = '0' + month.toString();
                        if (day < 10)
                            day = '0' + day.toString();

                        var maxDate = year + '-' + month + '-' + day;

                        $('#dueDate').attr('min', maxDate);
                    });
                </script>

            @endsection
