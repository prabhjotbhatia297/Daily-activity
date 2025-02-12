@extends('frontend.layout.main')
@section('container')
    @php
        $users = DB::table('users')->get();
    @endphp
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }
    </style>

    <style>
        .progress-bars div {
            flex: 1 1 auto;
            border: 1px solid grey;
            padding: 5px;
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

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(7) {
            border-radius: 0px 20px 20px 0px;

        }
    </style>
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

    <div class="form-field-head">

        <div class="division-bar" style="font-size: 22px; padding: 10px;  border-radius: 5px; color: #fff; display: flex; align-items: center;">
            <i class="fas fa-user-check" style="color: #fff; font-size: 26px; margin-right: 10px;"></i> 
            <strong>Classroom Training</strong>
            
            {{ Helpers::getDivisionName(session()->get('division')) }}
        </div>
    </div>




    {{-- ======================================
                    DATA FIELDS
    ======================================= --}}

    <div id="change-control-fields">
        <div class="container-fluid">


            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head"><i class="fas fa-tasks"></i> Record Workflow </div>

                    <div class="d-flex" style="gap:20px;">
                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => 4])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        @endphp

                        <button class="button_theme1">
                            <a class="text-white" href="{{ route('classroom_audittrail', $ClassroomTraining->id) }}"><i class="fas fa-history"></i> Audit Trail
                            </a>
                        </button>

                        @if ($ClassroomTraining->stage == 1)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                <i class="fas fa-check"></i> Submit
                            </button>

                        {{-- @elseif($ClassroomTraining->stage == 2)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                              Accept Complete
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                              Reject
                            </button> 
                        @elseif($ClassroomTraining->stage == 3)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Review Complete
                            </button> --}}

                        @elseif($ClassroomTraining->stage == 2)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                <i class="fas fa-check-circle"></i> Approval Complete
                            </button>
                        @elseif($ClassroomTraining->stage == 3)
                            <!-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                            Answer Submit
                            </button> -->
                        @elseif($ClassroomTraining->stage == 4)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                <i class="fas fa-clipboard-check"></i> Evaluation Complete
                            </button>
                        @elseif($ClassroomTraining->stage == 5)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                <i class="fas fa-check-circle"></i>QA/CQA Head Review Complete
                            </button>
                        @elseif($ClassroomTraining->stage == 6)
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                <i class="fas fa-check-circle"></i> Verification and Approval Complete
                            </button>
                        @endif
                        <button class="button_theme1"> 
                            <i class="fas fa-sign-out-alt"></i>
                            <a class="text-white" href="{{ url('TMS') }}"> Exit
                            </a> 
                        </button>
                    </div>

                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    {{-- ------------------------------By Pankaj-------------------------------- --}}
                    @if ($ClassroomTraining->stage == 0)
                        <div class="progress-bars ">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars d-flex">
                            @if ($ClassroomTraining->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            {{-- @if ($ClassroomTraining->stage >= 2)
                                <div class="active">In Accept</div>
                            @else
                                <div class="">In Accept</div>
                            @endif
                            @if ($ClassroomTraining->stage >= 3)
                                <div class="active">QA Review</div>
                            @else
                                <div class="">QA Review</div>
                            @endif --}}

                            @if ($ClassroomTraining->stage >= 2)
                                <div class="active">QA/CQA Head Approval</div>
                            @else
                                <div class="">QA/CQA Approval</div>
                            @endif
                            
                            @if ($ClassroomTraining->stage >= 3)
                                <div class="active">Employee Answers</div>
                            @else
                                <div class="">Employee Answers</div>
                            @endif

                            @if ($ClassroomTraining->stage >= 4)
                                <div class="active">Evaluation</div>
                            @else
                                <div class="">Evaluation</div>
                            @endif

                            @if ($ClassroomTraining->stage >= 5)
                                <div class="active">QA/CQA Head Final Review</div>
                            @else
                                <div class="">QA/CQA Head Final Review</div>
                            @endif

                            @if ($ClassroomTraining->stage >= 6)
                                <div class="active">Verification and Approval</div>
                            @else
                                <div class="">Verification and Approval</div>
                            @endif

                            @if ($ClassroomTraining->stage >= 7)
                                <div class="bg-danger">Closed - Done</div>
                            @else
                                <div class="">Closed - Done</div>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- @endif --}}
            </div>
        </div>

        <!-- Tab links -->
        <div class="cctab">
            <button class="cctablinks active" onclick="openCity(event, 'CCForm1')"><i class="fas fa-info-circle"></i> General Information</button>
            <!-- <button class="cctablinks " onclick="openCity(event, 'CCForm2')">Job Description</button> -->
            

            <!-- <button class="cctablinks " onclick="openCity(event, 'CCForm3')">QA Review</button> -->
            <button class="cctablinks " onclick="openCity(event, 'CCForm2')"><i class="fas fa-check-circle"></i> QA/CQA Approval</button>

            <!-- <button class="cctablinks " onclick="openCity(event, 'CCForm5')">Questionaries</button> -->

            <button class="cctablinks " onclick="openCity(event, 'CCForm3')"><i class="fas fa-user-check"></i> Evaluation</button>

            @if ($ClassroomTraining->stage >= 5)
            <button class="cctablinks" onclick="openCity(event, 'CCForm4')"><i class="fas fa-award"></i> Certificate</button>
            @endif

            <button class="cctablinks " onclick="openCity(event, 'CCForm5')"><i class="fas fa-user-check"></i> QA/CQA Head Final Review</button>
            
            <button class="cctablinks " onclick="openCity(event, 'CCForm6')"><i class="fas fa-check-double"></i> QA Final Approval</button>
            <button class="cctablinks " onclick="openCity(event, 'CCForm7')"><i class="fas fa-clipboard-list"></i> Activity Log</button>
        </div>

        <script>
            $(document).ready(function() {
                <?php if (in_array($ClassroomTraining->stage, [7])) : ?>
                $("#target :input").prop("disabled", true);
                <?php endif; ?>
            });
        </script>

        <form id="target" action="{{ route('classroom_trainingupdate', ['id' => $ClassroomTraining->id]) }}" method="post"
            enctype="multipart/form-data">
            @csrf
            @method('put')
            <div id="step-form">

                @if (!empty($parent_id))
                    <input type="hidden" name="parent_id" value="{{ $parent_id }}">
                    <input type="hidden" name="parent_type" value="{{ $parent_type }}">
                @endif
                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="row">
                                <div class="sub-head">
                                <i class="fas fa-info-circle"></i> Classroom Information
                            </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="RLS Record Number">Employee Name </label>
                                        <input type="text" name="name" id="name_employee"
                                            value="{{ $ClassroomTraining->name }}" readonly>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="employee_id">Employee Code</label>
                                        <input id="employee_id" name="employee_id" type="text"
                                            value="{{ $ClassroomTraining->employee_id }}" readonly>
                                        @error('empcode')
                                            <p class="text-danger">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="type_of_training">SOP Document</label>

                                        <select name="sopdocument" id="sopdocument" onchange="fetchQuestions(this.value)">
                                            <option value="">---Select SOP Document---</option>

                                            @foreach ($data as $dat)
                                                <option
                                                    value="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}"
                                                    {{ $savedSop == $dat->sop_type_short . '/' . $dat->department_id . '/000' . $dat->id . '/R' . $dat->major ? 'selected' : '' }}>
                                                    {{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}
                                                </option>
                                            @endforeach

                                        </select> 
                                    </div>
                                </div> --}}

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Department">Department</label>
                                        <input type="text" name="department" id="department" value="{{ $ClassroomTraining->department }}" readonly>
                                        {{-- <select name="department" readonly >
                                            <option value="">-- Select Dept --</option>
                                
                                            @php
                                                $savedDepartmentId = old('department', $ClassroomTraining->department);
                                            @endphp

                                            @foreach (Helpers::getDepartments() as $code => $department)
                                                <option value="{{ $code }}"
                                                    @if ($savedDepartmentId == $code) selected @endif>{{ $department }}
                                                </option>
                                            @endforeach
                                        </select> --}}
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Job Title">Designation</label>
                                        <input type="text" name="designation" id="designation" value="{{ $ClassroomTraining->designation }}" readonly>
                                    </div>
                                </div>

                                {{-- <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="Division Code">Location</label>
                                        <input type="text" name="location" value="{{ $ClassroomTraining->location }}" readonly>
                                    </div>
                                </div> --}}

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="start_date">Start Date</label>
                                        <input id="start_date" type="date" name="start_date"
                                            value="{{ $ClassroomTraining->start_date }}" onchange="setMinEndDate()">
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="end_date">End Date</label>
                                        <input id="end_date" type="date" name="end_date"
                                            value="{{ $ClassroomTraining->end_date }}" onchange="setMaxStartDate()">
                                    </div>
                                </div>

                            <script>
                                function setMinEndDate() {
                                    var startDate = document.getElementById('start_date').value;
                                    document.getElementById('end_date').min = startDate; 
                                }

                                function setMaxStartDate() {
                                    var endDate = document.getElementById('end_date').value;
                                    document.getElementById('start_date').max = endDate;
                                }
                            </script>

                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="HOD Persons">HOD</label>
                                        <select name="hod" id="hod">
                                            <option value="">-- Select HOD --</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $user->id == old('hod', $ClassroomTraining->hod) ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>


                                <div class="col-lg-6">
                                    <div class="group-input">
                                        <label for="revision_purpose">Revision Purpose</label>
                                        <select name="revision_purpose" id="revision_purpose" onchange="toggleRemarkInput()">
                                            <option value="">----Select---</option>
                                            <option value="New" {{ isset($ClassroomTraining) && $ClassroomTraining->revision_purpose == 'New' ? 'selected' : '' }}>New</option>
                                            <option value="Old" {{ isset($ClassroomTraining) && $ClassroomTraining->revision_purpose == 'Old' ? 'selected' : '' }}>Old</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Remark Input Field -->
                                <div class="col-lg-6" id="remark_container" style="display: {{ isset($ClassroomTraining) && $ClassroomTraining->revision_purpose == 'Old' ? 'block' : 'none' }};">
                                    <div class="group-input">
                                        <label for="remark">Remark</label>
                                        <textarea name="remark" id="remark" rows="4" placeholder="Enter your remark here...">{{ isset($ClassroomTraining) ? $ClassroomTraining->remark : '' }}</textarea>
                                    </div>
                                </div>
                                <script>
                                    // Function to toggle the remark input based on selection
                                    function toggleRemarkInput() {
                                        const revisionPurposeSelect = document.getElementById('revision_purpose');
                                        const remarkContainer = document.getElementById('remark_container');

                                        // Show the remark input if "Old" is selected, otherwise hide it
                                        if (revisionPurposeSelect.value === 'Old') {
                                            remarkContainer.style.display = 'block';
                                        } else {
                                            remarkContainer.style.display = 'none';
                                            // Clear the remark input when hiding (optional)
                                            document.getElementById('remark').value = '';
                                        }
                                    }

                                    // Call the function on page load to set the initial state
                                    document.addEventListener('DOMContentLoaded', function() {
                                        toggleRemarkInput(); // Call the function to initialize display based on current selection
                                    });
                                </script>



                                <div class="col-lg-6" id="evaluationContainer" style="display: none;">
                                    <div class="group-input">
                                        <label for="evaluation">Evaluation Required</label>
                                        <select name="evaluation_required" id="evaluationRequired">
                                            <option value="">----Select---</option>
                                            <option value="Yes" {{ $ClassroomTraining->evaluation_required == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ $ClassroomTraining->evaluation_required == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="evaluation">Minimum Sop View Time(in min)</label>
                                    <input type="number" min="0" name="total_minimum_time" id="" value="{{ $ClassroomTraining->total_minimum_time }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="evaluation">Maximum Sop View Time(in min)</label>
                                    <input type="number" min="0" name="per_screen_running_time" id="" value="{{$ClassroomTraining->per_screen_running_time}}">
                                </div>
                            </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        const lowerDesignations = ['Trainee', 'Officer', 'Senior Officer', 'Executive', 'Senior Executive'];
                                        const higherDesignations = ['Assistant Manager', 'Manager','Senior General Manager','Senior Manager', 'Deputy General Manager', 'Assistant General Manager and General Manager', 'Head Quality', 'VP Quality', 'Plant Head'];

                                        const designationInput = document.getElementById('designation');
                                        const evaluationContainer = document.getElementById('evaluationContainer');
                                        const evaluationSelect = document.getElementById('evaluationRequired');
                                        const questionnaireSection = document.getElementById('questionnaireSection');

                                        // Check the designation and show/hide the evaluation dropdown
                                        function checkDesignation() {
                                            const designation = designationInput.value;

                                            if (higherDesignations.includes(designation)) {
                                                evaluationContainer.style.display = 'block'; // Show evaluation dropdown for higher designations
                                            } else {
                                                evaluationContainer.style.display = 'none'; // Hide for lower designations
                                                questionnaireSection.style.display = 'none'; // Also hide questionnaire section if evaluation is hidden
                                                evaluationSelect.value = ''; // Reset evaluation selection
                                            }
                                        }

                                        // Add an event listener to handle evaluation selection
                                        evaluationSelect.addEventListener('change', function () {
                                            if (this.value === 'Yes') {
                                                questionnaireSection.style.display = 'block'; // Show questionnaire if 'Yes' is selected
                                            } else {
                                                questionnaireSection.style.display = 'none'; // Hide if 'No' is selected
                                            }
                                        });

                                        // Initial check when the page loads
                                        checkDesignation();
                                    });
                                </script>


                                <div class="col-12">
                                    <div class="group-input">
                                        <div class="why-why-chart">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 5%;">Sr.No.</th>
                                                        <th style="width: 30%;">Document Title</th>
                                                        <th>Type of Training</th>
                                                        <th>SOP NO.</th>
                                                        <th>Trainer</th>
                                                        <th>SOP Preview</th>
                                                        <th>Attachment</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        // Fetch the trainers' IDs
                                                        $trainerIds = DB::table('user_roles')
                                                            ->where('q_m_s_roles_id', 6)
                                                            ->pluck('user_id');
                                                        $usersDetails = DB::table('users')->select('id', 'name')->get();
                                                        $trainers = DB::table('users')
                                                            ->whereIn('id', $trainerIds)
                                                            ->select('id', 'name')
                                                            ->get();
                                                    @endphp

                                                    <!-- @for ($i = 1; $i <= 5; $i++)
                                                        <tr>
                                                            <td>{{ $i }} </td>
                                                            <td>
                                                                <input type="text" name="subject_{{ $i }}"
                                                                    value="{{ $ClassroomTraining->{'subject_' . $i} }}">
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="type_of_training_{{ $i }}"
                                                                    value="{{ $ClassroomTraining->{'type_of_training_' . $i} }}">
                                                            </td>
                                                            <td>
                                                                <input type="text"
                                                                    name="reference_document_no_{{ $i }}"
                                                                    value="{{ $ClassroomTraining->{'reference_document_no_' . $i} }}">
                                                            </td>
                     
                                                            <td>
                                                                <select name="trainer_{{ $i }}"
                                                                    id="">
                                                                    <option value="">-- Select --</option>
                                                                    @foreach ($usersDetails as $u)
                                                                        <option value="{{ $u->id }}"
                                                                            {{ $ClassroomTraining->{'trainer_' . $i} == $u->id ? 'selected' : '' }}>
                                                                            {{ $u->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="date"
                                                                    name="startdate_{{ $i }}"
                                                                    value="{{ $ClassroomTraining->{'startdate_' . $i} }}"
                                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                    class="hide-input"
                                                                    oninput="handleDateInput(this, 'startdate');checkDate('startdate','enddate')">
                                                            </td>

                                                            <td>
                                                                <input type="date"
                                                                    name="enddate_{{ $i }}"
                                                                    value="{{ $ClassroomTraining->{'enddate_' . $i} }}"
                                                                    min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                                                    class="hide-input"
                                                                    oninput="handleDateInput(this, 'enddate');checkDate('enddate','startdate')">
                                                            </td>
                              
                                                        </tr>
                                                    @endfor -->
                                                    <tr>
                                                    <td>1</td>
                                                    <td>
                                                        <select name="subject_1" id="sopdocument" onchange="fetchDocumentDetails(this)">
                                                            <option value="">---Select Document Name---</option>
                                                            @foreach ($data as $dat)
                                                            <option value="{{ $dat->document_name }}" 
                                                                    data-doc-number="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}" 
                                                                    data-sop-id="{{ $dat->id }}"
                                                                    @if(old('subject_1', $ClassroomTraining->subject_1 ?? '') == $dat->document_name) selected @endif>
                                                                {{ $dat->document_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="type_of_training_1" value="{{ old('type_of_training_1', $ClassroomTraining->type_of_training_1 ?? '') }}">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="reference_document_no_1" id="reference_document_no_1" 
                                                            value="{{ old('reference_document_no_1', $ClassroomTraining->reference_document_no_1 ?? '') }}">
                                                        <input type="text" id="document_number" 
                                                            value="{{ old('document_number', $ClassroomTraining->reference_document_no_1 ? 
                                                            $ClassroomTraining->sop_type_short . '/' . $ClassroomTraining->department_id . '/000' . $ClassroomTraining->reference_document_no_1 . '/R' . $ClassroomTraining->major : '') }}" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="trainer_1" id="trainer_1">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($usersDetails as $u)
                                                            <option value="{{ $u->id }}" @if(old('trainer_1', $ClassroomTraining->trainer_1 ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $ClassroomTraining->reference_document_no_1 ? '/documents/view/' . $ClassroomTraining->reference_document_no_1 : '#' }}" 
                                                        id="view_sop" target="_blank" 
                                                        style="display: {{ $ClassroomTraining->reference_document_no_1 ? 'inline' : 'none' }};">
                                                            View SOP
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <label for="Attached CV"></label>
                                                        <input type="file" id="myfile" name="supporting_attachments1"
                                                            value="{{ $ClassroomTraining->supporting_attachments1 }}">
                                                        <a href="{{ asset('upload/' . $ClassroomTraining->supporting_attachments1) }}"
                                                            target="_blank">{{ $ClassroomTraining->supporting_attachments1 }}</a>
                                                    </td>



                                                    <!-- <input type="hidden" name="reference_document_no_1" id="selected_document_id" 
                                                        value="{{ old('reference_document_no_1', $ClassroomTraining->reference_document_no_1 ?? '') }}"> -->
                                                        </tr>

                                                                                                        <tr>
                                                                                                        <td>2</td>
                                                    <td>
                                                        <select name="subject_2" id="sopdocument2" onchange="fetchDocumentDetails2(this)">
                                                            <option value="">---Select Document Name---</option>
                                                            @foreach ($data as $dat)
                                                            <option value="{{ $dat->document_name }}" 
                                                                    data-doc-number="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}" 
                                                                    data-sop-id="{{ $dat->id }}"
                                                                    @if(old('subject_2', $ClassroomTraining->subject_2 ?? '') == $dat->document_name) selected @endif>
                                                                {{ $dat->document_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input type="text" name="type_of_training_2" value="{{ old('type_of_training_2', $ClassroomTraining->type_of_training_2 ?? '') }}">
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="reference_document_no_2" id="reference_document_no_2" 
                                                            value="{{ old('reference_document_no_2', $ClassroomTraining->reference_document_no_2 ?? '') }}">
                                                        <input type="text" id="document_number2" 
                                                            value="{{ old('document_number2', $ClassroomTraining->reference_document_no_2 ? 
                                                            $ClassroomTraining->sop_type_short . '/' . $ClassroomTraining->department_id . '/000' . $ClassroomTraining->reference_document_no_2 . '/R' . $ClassroomTraining->major : '') }}" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="trainer_2" id="trainer_2">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($usersDetails as $u)
                                                            <option value="{{ $u->id }}" @if(old('trainer_2', $ClassroomTraining->trainer_2 ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $ClassroomTraining->reference_document_no_2 ? '/documents/view/' . $ClassroomTraining->reference_document_no_2 : '#' }}" 
                                                        id="view_sop" target="_blank" 
                                                        style="display: {{ $ClassroomTraining->reference_document_no_2 ? 'inline' : 'none' }};">
                                                            View SOP
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <label for="Attached CV"></label>
                                                        <input type="file" id="myfile" name="supporting_attachments2"
                                                            value="{{ $ClassroomTraining->supporting_attachments2 }}">
                                                        <a href="{{ asset('upload/' . $ClassroomTraining->supporting_attachments2) }}"
                                                            target="_blank">{{ $ClassroomTraining->supporting_attachments2 }}</a>
                                                    </td>
                                                    </tr>

                                                    <tr>
                                                    <td>3</td>
                                                    <td>
                                                        <select name="subject_3" id="sopdocument3" onchange="fetchDocumentDetails3(this)">
                                                            <option value="">---Select Document Name---</option>
                                                            @foreach ($data as $dat)
                                                            <option value="{{ $dat->document_name }}" 
                                                                    data-doc-number="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}" 
                                                                    data-sop-id="{{ $dat->id }}"
                                                                    @if(old('subject_3', $ClassroomTraining->subject_3 ?? '') == $dat->document_name) selected @endif>
                                                                {{ $dat->document_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="type_of_training_3" value="{{ old('type_of_training_3', $ClassroomTraining->type_of_training_3 ?? '') }}"></td>
                                                    <td>
                                                        <input type="hidden" name="reference_document_no_3" id="reference_document_no_3" 
                                                            value="{{ old('reference_document_no_3', $ClassroomTraining->reference_document_no_3 ?? '') }}">
                                                        <input type="text" id="document_number3" 
                                                            value="{{ old('document_number3', $ClassroomTraining->reference_document_no_3 ? 
                                                            $ClassroomTraining->sop_type_short . '/' . $ClassroomTraining->department_id . '/000' . $ClassroomTraining->reference_document_no_3 . '/R' . $ClassroomTraining->major : '') }}" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="trainer_3" id="trainer_3">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($usersDetails as $u)
                                                            <option value="{{ $u->id }}" @if(old('trainer_3', $ClassroomTraining->trainer_3 ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $ClassroomTraining->reference_document_no_3 ? '/documents/view/' . $ClassroomTraining->reference_document_no_3 : '#' }}" 
                                                        id="view_sop3" target="_blank" 
                                                        style="display: {{ $ClassroomTraining->reference_document_no_3 ? 'inline' : 'none' }};">
                                                            View SOP
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <label for="Attached CV"></label>
                                                        <input type="file" id="myfile" name="supporting_attachments3"
                                                            value="{{ $ClassroomTraining->supporting_attachments3 }}">
                                                        <a href="{{ asset('upload/' . $ClassroomTraining->supporting_attachments3) }}"
                                                            target="_blank">{{ $ClassroomTraining->supporting_attachments3 }}</a>
                                                    </td>
                                                    </tr>

                                                    <tr>
                                                    <td>4</td>
                                                    <td>
                                                        <select name="subject_4" id="sopdocument4" onchange="fetchDocumentDetails4(this)">
                                                            <option value="">---Select Document Name---</option>
                                                            @foreach ($data as $dat)
                                                            <option value="{{ $dat->document_name }}" 
                                                                    data-doc-number="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}" 
                                                                    data-sop-id="{{ $dat->id }}"
                                                                    @if(old('subject_4', $ClassroomTraining->subject_4 ?? '') == $dat->document_name) selected @endif>
                                                                {{ $dat->document_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="type_of_training_4" value="{{ old('type_of_training_4', $ClassroomTraining->type_of_training_4 ?? '') }}"></td>
                                                    <td>
                                                        <input type="hidden" name="reference_document_no_4" id="reference_document_no_4" 
                                                            value="{{ old('reference_document_no_4', $ClassroomTraining->reference_document_no_4 ?? '') }}">
                                                        <input type="text" id="document_number4" 
                                                            value="{{ old('document_number4', $ClassroomTraining->reference_document_no_4 ? 
                                                            $ClassroomTraining->sop_type_short . '/' . $ClassroomTraining->department_id . '/000' . $ClassroomTraining->reference_document_no_4 . '/R' . $ClassroomTraining->major : '') }}" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="trainer_4" id="trainer_4">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($usersDetails as $u)
                                                            <option value="{{ $u->id }}" @if(old('trainer_4', $ClassroomTraining->trainer_4 ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $ClassroomTraining->reference_document_no_4 ? '/documents/view/' . $ClassroomTraining->reference_document_no_4 : '#' }}" 
                                                        id="view_sop4" target="_blank" 
                                                        style="display: {{ $ClassroomTraining->reference_document_no_4 ? 'inline' : 'none' }};">
                                                            View SOP
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <label for="Attached CV"></label>
                                                        <input type="file" id="myfile" name="supporting_attachments4"
                                                            value="{{ $ClassroomTraining->supporting_attachments4 }}">
                                                        <a href="{{ asset('upload/' . $ClassroomTraining->supporting_attachments4) }}"
                                                            target="_blank">{{ $ClassroomTraining->supporting_attachments4 }}</a>
                                                    </td>
                                                    </tr>

                                                    <tr>
                                                    <td>5</td>
                                                    <td>
                                                        <select name="subject_5" id="sopdocument5" onchange="fetchDocumentDetails5(this)">
                                                            <option value="">---Select Document Name---</option>
                                                            @foreach ($data as $dat)
                                                            <option value="{{ $dat->document_name }}" 
                                                                    data-doc-number="{{ $dat->sop_type_short }}/{{ $dat->department_id }}/000{{ $dat->id }}/R{{ $dat->major }}" 
                                                                    data-sop-id="{{ $dat->id }}"
                                                                    @if(old('subject_5', $ClassroomTraining->subject_5 ?? '') == $dat->document_name) selected @endif>
                                                                {{ $dat->document_name }}
                                                            </option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input type="text" name="type_of_training_5" value="{{ old('type_of_training_5', $ClassroomTraining->type_of_training_5 ?? '') }}"></td>
                                                    <td>
                                                        <input type="hidden" name="reference_document_no_5" id="reference_document_no_5" 
                                                            value="{{ old('reference_document_no_5', $ClassroomTraining->reference_document_no_5 ?? '') }}">
                                                        <input type="text" id="document_number5" 
                                                            value="{{ old('document_number5', $ClassroomTraining->reference_document_no_5 ? 
                                                            $ClassroomTraining->sop_type_short . '/' . $ClassroomTraining->department_id . '/000' . $ClassroomTraining->reference_document_no_5 . '/R' . $ClassroomTraining->major : '') }}" readonly>
                                                    </td>
                                                    <td>
                                                        <select name="trainer_5" id="trainer_5">
                                                            <option value="">-- Select --</option>
                                                            @foreach ($usersDetails as $u)
                                                            <option value="{{ $u->id }}" @if(old('trainer_5', $ClassroomTraining->trainer_5 ?? '') == $u->id) selected @endif>{{ $u->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <a href="{{ $ClassroomTraining->reference_document_no_5 ? '/documents/view/' . $ClassroomTraining->reference_document_no_5 : '#' }}" 
                                                        id="view_sop5" target="_blank" 
                                                        style="display: {{ $ClassroomTraining->reference_document_no_5 ? 'inline' : 'none' }};">
                                                            View SOP
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <label for="Attached CV"></label>
                                                        <input type="file" id="myfile" name="supporting_attachments5"
                                                            value="{{ $ClassroomTraining->supporting_attachments5 }}">
                                                        <a href="{{ asset('upload/' . $ClassroomTraining->supporting_attachments5) }}"
                                                            target="_blank">{{ $ClassroomTraining->supporting_attachments5 }}</a>
                                                    </td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                          

                            <div class="button-block">
                                <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                                <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                    
                            </div>
                        </div>
                    </div>

                </div>
                <script>
                     function fetchDocumentDetails(selectElement) {
                        var selectedOption = selectElement.options[selectElement.selectedIndex];

                        var documentNumber = selectedOption.getAttribute('data-doc-number');
                        var documentId = selectedOption.getAttribute('data-sop-id');
                        document.getElementById('reference_document_no_1').value = documentId;

                        document.getElementById('document_number').value = documentNumber;

                        var sopAnchor = document.getElementById('view_sop');
                        if (documentId) {
                            sopAnchor.href = '/documents/view/' + documentId;
                            sopAnchor.style.display = 'inline';
                        } else {
                            sopAnchor.style.display = 'none';
                        }

                        console.log("Document Number (displayed):", documentNumber);
                        console.log("Document ID (stored):", documentId);
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        var existingDocId = document.getElementById('reference_document_no_1').value;
                        var selectElement = document.getElementById('sopdocument');

                        if (existingDocId) {
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (option.getAttribute('data-sop-id') == existingDocId) {
                                    selectElement.selectedIndex = i;
                                    fetchDocumentDetails(selectElement);
                                    break;
                                }
                            }
                        }
                    });
                </script>

                <script>
                    function fetchDocumentDetails2(selectElement) {
                        var selectedOption = selectElement.options[selectElement.selectedIndex];

                        var documentNumber = selectedOption.getAttribute('data-doc-number');
                        var documentId = selectedOption.getAttribute('data-sop-id');

                        // Update the hidden input field with document ID
                        document.getElementById('reference_document_no_2').value = documentId;

                        // Update the document number input field with full document number
                        document.getElementById('document_number2').value = documentNumber;

                        // Update the "View SOP" link
                        var sopAnchor = document.getElementById('view_sop2'); // Corrected to view_sop2
                        if (documentId) {
                            sopAnchor.href = '/documents/view/' + documentId;
                            sopAnchor.style.display = 'inline';
                        } else {
                            sopAnchor.style.display = 'none';
                        }

                        console.log("Document Number (displayed):", documentNumber);
                        console.log("Document ID (stored):", documentId);
                    }

                    // Load the document details based on the existing job training record (if any)
                    document.addEventListener("DOMContentLoaded", function() {
                        var existingDocId = document.getElementById('reference_document_no_2').value;
                        var selectElement = document.getElementById('sopdocument2');

                        if (existingDocId) {
                            // Find the corresponding option based on the existing document ID
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (option.getAttribute('data-sop-id') == existingDocId) {
                                    selectElement.selectedIndex = i; // Select the option
                                    fetchDocumentDetails2(selectElement); // Call the correct function
                                    break;
                                }
                            }
                        }
                    });

                </script>

                <script>
                        function fetchDocumentDetails3(selectElement) {
                        var selectedOption = selectElement.options[selectElement.selectedIndex];

                        var documentNumber = selectedOption.getAttribute('data-doc-number');
                        var documentId = selectedOption.getAttribute('data-sop-id');
                        
                        document.getElementById('reference_document_no_3').value = documentId;
                        document.getElementById('document_number3').value = documentNumber;

                        var sopAnchor = document.getElementById('view_sop3');
                        if (documentId) {
                            sopAnchor.href = '/documents/view/' + documentId;
                            sopAnchor.style.display = 'inline';
                        } else {
                            sopAnchor.style.display = 'none';
                        }
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        var existingDocId = document.getElementById('reference_document_no_3').value;
                        var selectElement = document.getElementById('sopdocument3');

                        if (existingDocId) {
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (option.getAttribute('data-sop-id') == existingDocId) {
                                    selectElement.selectedIndex = i;
                                    fetchDocumentDetails3(selectElement);
                                    break;
                                }
                            }
                        }
                    });
                </script>

                <script>
                        function fetchDocumentDetails4(selectElement) {
                        var selectedOption = selectElement.options[selectElement.selectedIndex];

                        var documentNumber = selectedOption.getAttribute('data-doc-number');
                        var documentId = selectedOption.getAttribute('data-sop-id');
                        
                        document.getElementById('reference_document_no_4').value = documentId;
                        document.getElementById('document_number4').value = documentNumber;

                        var sopAnchor = document.getElementById('view_sop4');
                        if (documentId) {
                            sopAnchor.href = '/documents/view/' + documentId;
                            sopAnchor.style.display = 'inline';
                        } else {
                            sopAnchor.style.display = 'none';
                        }
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        var existingDocId = document.getElementById('reference_document_no_4').value;
                        var selectElement = document.getElementById('sopdocument4');

                        if (existingDocId) {
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (option.getAttribute('data-sop-id') == existingDocId) {
                                    selectElement.selectedIndex = i;
                                    fetchDocumentDetails4(selectElement);
                                    break;
                                }
                            }
                        }
                    });
                </script>

                <script>
                    function fetchDocumentDetails5(selectElement) {
                        var selectedOption = selectElement.options[selectElement.selectedIndex];

                        var documentNumber = selectedOption.getAttribute('data-doc-number');
                        var documentId = selectedOption.getAttribute('data-sop-id');
                        
                        document.getElementById('reference_document_no_5').value = documentId;
                        document.getElementById('document_number5').value = documentNumber;

                        var sopAnchor = document.getElementById('view_sop5');
                        if (documentId) {
                            sopAnchor.href = '/documents/view/' + documentId;
                            sopAnchor.style.display = 'inline';
                        } else {
                            sopAnchor.style.display = 'none';
                        }
                    }

                    document.addEventListener("DOMContentLoaded", function() {
                        var existingDocId = document.getElementById('reference_document_no_5').value;
                        var selectElement = document.getElementById('sopdocument5');

                        if (existingDocId) {
                            for (var i = 0; i < selectElement.options.length; i++) {
                                var option = selectElement.options[i];
                                if (option.getAttribute('data-sop-id') == existingDocId) {
                                    selectElement.selectedIndex = i;
                                    fetchDocumentDetails5(selectElement);
                                    break;
                                }
                            }
                        }
                    });
                </script>


                {{-- <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Activated On">Remark</label>
                                <textarea name="qa_review" maxlength="255">{{ $ClassroomTraining->qa_review }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Attachment">Attachment</label>
                                <input type="file" id="myfile" name="qa_review_attachment" value="{{ $ClassroomTraining->qa_review_attachment }}">
                                <a href="{{ asset('upload/' . $ClassroomTraining->qa_review_attachment) }}" target="_blank">{{ $ClassroomTraining->qa_review_attachment }}</a>
                            </div>
                        </div>
  
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>                                    
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        </div>
                    </div>
                </div> --}}

                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Activated On">Remark</label>
                                <textarea name="qa_cqa_comment" maxlength="255">{{ $ClassroomTraining->qa_cqa_comment }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Attachment">Attachment</label>
                                <input type="file" id="myfile" name="qa_cqa_attachment" value="{{ $ClassroomTraining->qa_cqa_attachment }}">
                                <a href="{{ asset('upload/' . $ClassroomTraining->qa_cqa_attachment) }}" target="_blank">{{ $ClassroomTraining->qa_cqa_attachment }}</a>
                            </div>
                        </div>
  
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>                                    
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        </div>
                    </div>
                </div>


                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Activated On">Remark</label>
                                <textarea name="evaluation_comment" maxlength="255">{{ $ClassroomTraining->evaluation_comment }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Attachment">Attachment</label>
                                <input type="file" id="myfile" name="evaluation_attachment" value="{{ $ClassroomTraining->evaluation_attachment }}">
                                <a href="{{ asset('upload/' . $ClassroomTraining->evaluation_attachment) }}" target="_blank">{{ $ClassroomTraining->evaluation_attachment }}</a>
                            </div>
                        </div>
  
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>                                    
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        </div>
                    </div>
                </div>

                @if ($ClassroomTraining->stage >= 5)
                    <div id="CCForm4" class="inner-block cctabcontent">
                        <div class="inner-block-content">
                            <div class="row">
                                <div class="col-lg-12">
                                <div class="button-block">
                                        <button type="button" class="printButton" onclick="downloadCertificate()">
                                            <i class="fas fa-print"></i> Print
                                        </button>
                                </div>

                            <div id="certificateContent" class="pm-certificate-container">
                                <div class="outer-border"></div>
                                <div class="inner-border"></div>

                                <div class="pm-certificate-border">
                                    <!-- Logos Section -->
                                    <div class="pm-certificate-logos text-center">
                                        <img src="{{ asset('user/images/connexo.png') }}" alt="Agio Logo" class="logo logo-left">
                                        {{-- <img src="{{ asset('user/images/symbiotec_pharmalab_pvt_ltd__logo.jpg') }}" alt="Vidhya GxP Logo" class="logo logo-left"> --}}
                                    </div>

                                    <div class="pm-certificate-header">
                                        <div class="pm-certificate-title cursive text-center">
                                            <h2>Certificate of Class Room Training</h2>
                                        </div>
                                    </div>

                                    <div class="pm-certificate-body">
                                        <div class="pm-certificate-block">
                                            <p class="text-center">
                                                This is to certify that Mr. / Ms. / Mrs. 
                                                <strong> {{ $ClassroomTraining->name }}</strong>
                                                has undergone Class Room Training, including the requirement of cGMP and has shown a good attitude and thorough understanding in the subject.
                                            </p>

                                            <p class="text-center">
                                                Therefore, we certify that Mr. / Ms. / Mrs. 
                                                <strong> {{ $ClassroomTraining->name }}</strong>
                                                is capable of performing his/her assigned duties in the 
                                                <strong>{{ $ClassroomTraining->department }}</strong> Department independently.
                                            </p>
                                        </div>

                                        <div class="pm-certificate-footer">
                                            <div class="pm-certified text-center">
                                                <span class="bold block">Sign / Date:</span>
                                                <strong>{{ $ClassroomTraining->evaluation_complete_by }} / {{ \Carbon\Carbon::parse($ClassroomTraining->evaluation_complete_on)->format('d-M-Y') }}</strong>
                                                <span class="pm-empty-space block underline"></span>
                                                <span class="bold block">Head of Department</span>
                                            </div>
                                            <div class="pm-certified text-center">
                                                <span class="bold block">Sign / Date:</span>
                                                <strong>{{ $ClassroomTraining->approval_complete_by }} / {{ \Carbon\Carbon::parse($ClassroomTraining->approval_complete_on)->format('d-M-Y') }}</strong>
                                                <span class="pm-empty-space block underline"></span>
                                                <span class="bold block">Head QA/CQA</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 40px;" class="button-block">
                                <button type="submit" class="btn btn saveButton">Save</button>
                                <button type="button" id="ChangeNextButton" class="btn btn nextButton">Next</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif




                <!-- CSS Styling -->
            <style>
              @import url('https://fonts.googleapis.com/css?family=Open+Sans|Pinyon+Script|Rochester');

                .cursive {
                    font-family: 'Pinyon Script', cursive;
                }

                .sans {
                    font-family: 'Open Sans', sans-serif;
                }

                .bold {
                    font-weight: bold;
                }

                .block {
                    display: block;
                }

                .underline {
                    border-bottom: 1px solid #777;
                    padding: 5px;
                    margin-bottom: 15px;
                }

                .text-center {
                    text-align: center;
                }

                .pm-empty-space {
                    // height: 40px; 
                    width: 100%;
                }

                .pm-certificate-container {
                    position: relative;
                    width: 90%;
                    max-width: 800px;
                    background-color: #618597;
                    padding: 30px;
                    color: #333;
                    font-family: 'Open Sans', sans-serif;
                    box-shadow: 0 9px 15px rgb(18 5 23 / 60%);
                    margin-top: 35px;
                   
                }
 

                .outer-border {
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    border: 2px solid #fff;
                    pointer-events: none;
                }

                .inner-border {
                    position: absolute;
                    top: 10px;
                    left: 10px;
                    right: 10px;
                    bottom: 10px;
                    border: 2px solid #fff;
                    pointer-events: none;
                }

                .pm-certificate-border {
                    position: relative;
                    padding: 20px;
                    border: 1px solid #E1E5F0;
                    background-color: rgba(255, 255, 255, 1);
                }

                    .printButton:hover {
                        background-color: #1a252f;
                    }

                .pm-certificate-logos {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                
                }

                .logo {
                    max-width: 100px;
                }

                .logo-left {
                    transform: scale(0.7);
                    margin-bottom: 14px;
                }

                .logo-right {
                    transform: scale(1.8);
                    margin-right: 65px;
                }

                .pm-certificate-header {
                    margin-bottom: 10px;
                }

                .pm-certificate-title h2 {
                    font-size: 34px;
                }

                .pm-certificate-body {
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                }

                .pm-certificate-block {
                    text-align: center;
                }

                .pm-name-text {
                    font-size: 20px;
                }

                .pm-earned {
                    margin: 15px 0 20px;
                }

                .pm-earned-text {
                    font-size: 20px;
                }

                .pm-credits-text {
                    font-size: 15px;
                }

                .pm-course-title {
                    margin-bottom: 15px;
                }

                .pm-certified {
                    font-size: 12px;
                    width: 300px; 
                    margin-top: 0; 
                    text-align: center;
                }

                .pm-certificate-footer {
                    display: flex;
                    justify-content: space-between;
                    align-items: center; 
                    width: 100%;
                    margin-top: 20px;
                    flex-wrap: nowrap
                }
                @media print {
                    .print-button {
                        display: none;
                    }
                    .print-button-container {
                        display: none;
                    }
                }


                .print-button {
                    padding: 10px 20px;
                    background-color: #007bff; 
                    color: #fff;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 14px;
                    font-weight: bold;
                    margin-block-end: 700px;
                }


                @media print {
                    body {
                        background: none;
                        -webkit-print-color-adjust: exact; 
                        margin: 0;
                        padding: 0;
                        width: 100%;
                    }

                    .pm-certificate-container {
                        page-break-inside: avoid; 
                        page-break-after: avoid; 
                        width: 100%;
                        height: auto; 
                        max-height: 100vh; 
                        overflow: hidden; 
                        box-shadow: none; 
                        background-color: #618597; 
                        // padding: 30px;
                        margin: 0 auto; 
                    }

                    .outer-border, .inner-border {
                        border-color: #d3d0d0; 
                    }

                    .print-button, .print-button-container {
                        display: none; 
                        
                    }

                
                    html, body {
                        height: auto; 
                        max-height: 100vh; 
                        overflow: hidden;
                    }
                }

                </style>


             
                <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

                <script>
                    function downloadCertificate() {
                        const element = document.querySelector('.pm-certificate-container');
                        const options = {
                            margin: 0, 
                            filename: 'Class-Room-Training-certificate.pdf',
                            html2canvas: { 
                                scale: 2,
                              
                                x: 0,
                                y: 0
                            },
                            jsPDF: { orientation: 'landscape' }
                        };
                        html2pdf().from(element).set(options).save();
                    }
                </script>





                <script>
                    document.getElementById("saveForm").addEventListener("click", function(event) {
                        let questionInputs = document.querySelectorAll(".question-input");
                        let answerInputs = document.querySelectorAll(".answer-input");
                        let allFilled = true;

                        questionInputs.forEach(function(input) {
                            if (input.value.trim() === "") {
                                allFilled = false;
                            }
                        });

                        answerInputs.forEach(function(input) {
                            if (input.value.trim() === "") {
                                allFilled = false;
                            }
                        });

                        if (!allFilled) {
                            event.preventDefault();
                            alert("Please fill required field before submitting.");
                        }
                    });
                </script>



                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Activated On">Remark</label>
                                <textarea name="qa_cqa_head_comment" maxlength="255">{{ $ClassroomTraining->qa_cqa_head_comment }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Attachment">Attachment</label>
                                <input type="file" id="myfile" name="qa_cqa_head_attachment" value="{{ $ClassroomTraining->qa_cqa_head_attachment }}">
                                <a href="{{ asset('upload/' . $ClassroomTraining->qa_cqa_head_attachment) }}" target="_blank">{{ $ClassroomTraining->qa_cqa_head_attachment }}</a>
                            </div>
                        </div>
  
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>                                    
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        </div>
                    </div>
                </div>
                </div>


                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">

                        <div class="col-lg-12">
                            <div class="group-input">
                                <label for="Activated On">Remark</label>
                                <textarea name="final_review_comment" maxlength="255">{{ $ClassroomTraining->final_review_comment }}</textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="group-input">
                                <label for="External Attachment">Attachment</label>
                                <input type="file" id="myfile" name="final_review_attachment" value="{{ $ClassroomTraining->final_review_attachment }}">
                                <a href="{{ asset('upload/' . $ClassroomTraining->final_review_attachment) }}" target="_blank">{{ $ClassroomTraining->final_review_attachment }}</a>
                            </div>
                        </div>
  
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>                                    
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                        </div>
                    </div>
                </div>

                <div id="CCForm7" class="inner-block cctabcontent">
                <div class="inner-block-content">
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Activated By">Submit By</label>
                                <div class="static">{{ $ClassroomTraining->submit_by }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Activated On">Submit On</label>
                                <div class="static">
                                    {{ $ClassroomTraining->submit_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Activated On">Submit Comment</label>
                                <div class="static">
                                    {{ $ClassroomTraining->submit_comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for=" Rejected By">Approval Complete By</label>
                                <div class="static">{{ $ClassroomTraining->approval_complete_by }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Approval Complete On</label>
                                <div class="static">{{ $ClassroomTraining->approval_complete_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Approval Complete Comment</label>
                                <div class="static">{{ $ClassroomTraining->approval_complete_comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Answer Submit By</label>
                                <div class="static">{{ $ClassroomTraining->answer_submit_by }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Answer Submit On</label>
                                <div class="static">{{ $ClassroomTraining->answer_submit_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Answer Submit Comment</label>
                                <div class="static">{{ $ClassroomTraining->answer_submit_comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Evaluation Complete By</label>
                                <div class="static">{{ $ClassroomTraining->evaluation_complete_by }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Evaluation Complete On</label>
                                <div class="static">{{ $ClassroomTraining->evaluation_complete_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Evaluation Complete Comment</label>
                                <div class="static">{{ $ClassroomTraining->evaluation_complete_comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">QA/CQA Head Review Complete By</label>
                                <div class="static">{{ $ClassroomTraining->qa_head_review_complete_by }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">QA/CQA Head Review Complete On</label>
                                <div class="static">{{ $ClassroomTraining->qa_head_review_complete_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">QA/CQA Head Review Complete Comment</label>
                                <div class="static">{{ $ClassroomTraining->qa_head_review_complete_comment }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Verification and Approval Complete By</label>
                                <div class="static">{{ $ClassroomTraining->verification_approval_complete_by }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Verification and Approval Complete On</label>
                                <div class="static">{{ $ClassroomTraining->verification_approval_complete_on }}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="group-input">
                                <label for="Rejected On">Verification and Approval Complete Comment</label>
                                <div class="static">{{ $ClassroomTraining->verification_approval_complete_comment }}
                                </div>
                            </div>
                        </div>
                       

                    </div>
                    <div class="button-block">
                        <button type="submit" class="saveButton"> <a href="{{ url('TMS') }}" class="text-white">
                                Save </a></button>
                        {{-- <button type="button" class="backButton">Back</button> --}}
                        </a>
                        {{-- <button type="submit">Submit</button> --}}
                        <button type="button" class="backButton" onclick="previousStep()">Back</button>
                        <button type="button"> <a href="{{ url('TMS') }}" class="text-white">
                                Exit </a> </button>
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

    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee , #capa_related_record,#cft_reviewer'
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
        }



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
        document.getElementById('initiator_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });

        function setCurrentDate(item) {
            if (item == 'yes') {
                $('#effect_check_date').val('{{ date('
                                                d - M - Y ') }}');
            } else {
                $('#effect_check_date').val('');
            }
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
    $(document).ready(function() {
        $('#ObservationAdd').click(function(e) {
            function generateTableRow(serialNumber) {

                var html =
                    '<tr>' +
                    '<td><input disabled type="text" name="jobResponsibilities[' + serialNumber +
                    '][serial]" value="' + serialNumber +
                    '"></td>' +
                    '<td><input type="text" name="jobResponsibilities[' + serialNumber +
                    '][job]"></td>' +
                    '<td><input type="text" class="Document_Remarks" name="jobResponsibilities[' +
                    serialNumber + '][remarks]"></td>' +


                    '</tr>';

                return html;
            }

            var tableBody = $('#job-responsibilty-table tbody');
            var rowCount = tableBody.children('tr').length;
            var newRow = generateTableRow(rowCount + 1);
            tableBody.append(newRow);
        });
    });
</script>


    <div class="modal fade" id="signature-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ url('rcms/classroom_trainer_send', $ClassroomTraining->id) }}" method="POST"
                    id="sendstage">
                    @csrf
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
                            <input type="comment" name="comment">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="on-submit-disable-button">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            
            $('#sendstage').on('submit', function(e) {
                $('.on-submit-disable-button').prop('disabled', true);
            });
        })
    </script>


    <div class="modal fade" id="cancel-modal">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title">E-Signature</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <form action="{{ url('tms/classroomTraining/cancelstage', $ClassroomTraining->id) }}" method="POST" id="cancelstage">
                    @csrf
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
                            <label for="comment">Comment <span class="text-danger">*</span></label>
                            <input type="comment" name="comments">
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="on-submit-disable-button">Submit</button>
                        <button type="button" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function() {
        
        $('#cancelstage').on('submit', function(e) {
            $('.on-submit-disable-button').prop('disabled', true);
        });
    })
</script>

@endsection
