{{--  --}}@extends('frontend.layout.main')
@section('container')
    @include('frontend.TMS.head')

    @if (count($errors) > 0)
        @foreach ($errors->all() as $error)
            @php
                toastr()->error($error);
            @endphp
        @endforeach
    @endif

    <script>
        document.getElementById('searchButton').addEventListener('click', function() {
            var searchTerm = document.getElementById('searchInput').value;
            console.log('Searching for:', searchTerm);
        });
    </script>

    <style>
        .vscomp-toggle-button {
            border-color: gray !important;
        }
    </style>
    {{-- ======================================
                CREATING TRAINING
    ======================================= --}}
    <div id="create-training-plan">
        <div class="container-fluid">

            <form action="{{ route('TMS.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="inner-block">
                    <div class="main-head">
                        Basic Information
                    </div>
                    <div class="inner-block-content">
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Site/Location">Site/Location</label>
                                    <select id="site_division" name="site_division" onchange="toggleMultiSelect()">
                                        <option value="">Select Division</option>
                                        <option value="P1">P1 (Indore Location)</option>
                                        <option value="P2">P2 (Pithampur Location)</option>
                                        <option value="P4">P4 (Ujjain Site)</option>
                                        <option value="C1">C1 (China Plant)</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="training-name">Training Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" id="traning_plan_name" name="traning_plan_name" required>
                                </div>
                                <p id="trainingPlan" style="color: red">
                                    ** Training plan is missing...
                                </p>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Trainer">Trainer</label>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="External Trainer">External Trainer</label>
                                    <input type="text" name="External_Trainer">
                                </div>
                            </div>


                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="training-id">Training Plan ID</label>
                                    <div class="static">Not-Applicable</div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="training-type">Training Plan Type <span class="text-danger">*</span></label>
                                    <select id="training-select" name="training_plan_type" required
                                        onchange="toggleMultiSelect()">
                                        <option value="">Select Training Plan</option>
                                        <option value="Read & Understand">Read & Understand</option>
                                        <option value="Read & Understand with Questions">Read & Understand with Questions</option>
                                        <option value="Classroom Training">Classroom Training</option>
                                        <option value="On Job Training">On Job Training</option>
                                        <option value="External Training">External Training</option>
                                        <option value="Refresher Training">Refresher Training</option>
                                        <option value="Retraining">Retraining</option>
                                        <option value="CBT">Computer Based Training</option>
                                    </select>
                                    <p id="trainingType" style="color: red">
                                        ** Training type is missing...
                                    </p>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Department">Department</label>
                                    <select multiple name="department[]" placeholder="Select Department" data-search="false"
                                        data-silent-initial-value-set="true" id="department">
                                        @foreach (Helpers::getDepartments() as $code => $department)
                                            <option value="{{ $department }}"> {{ $department }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>



                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Training Start Date">Training Start Date<span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" name="training_start_date" id="training_start_date">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Training End Date">Training End Date<span
                                            class="text-danger">*</span></label>
                                    <input type="datetime-local" name="training_end_date" id="training_end_date">
                                </div>
                            </div>

                            <script>
                                // Set minimum date to today
                                window.onload = function() {
                                    const today = new Date();
                                    const formattedToday = today.toISOString().slice(0, 16); // Format date to YYYY-MM-DDTHH:MM

                                    // Set 'min' attribute for both inputs to prevent selecting dates before today
                                    document.getElementById('training_start_date').setAttribute('min', formattedToday);
                                    document.getElementById('training_end_date').setAttribute('min', formattedToday);
                                };

                                function validateDates() {
                                    const startDate = new Date(document.getElementById('training_start_date').value);
                                    const endDate = new Date(document.getElementById('training_end_date').value);

                                    // Check if end date is before start date
                                    if (startDate && endDate && endDate < startDate) {
                                        alert('End date cannot be before the start date.');
                                        document.getElementById('training_end_date').value = ''; // Clear the end date field if validation fails
                                    }
                                }

                                // Attach event listeners to both date fields
                                document.getElementById('training_start_date').addEventListener('change', validateDates);
                                document.getElementById('training_end_date').addEventListener('change', validateDates);
                            </script>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Total mininmum time">Total Minimum Time(In Minutes)</label>
                                    <input type="number" name="total_minimum_time" id="total_minimum_time" min="0">
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Total mininmum time">Per Screen Runing Time(In Minutes)</label>
                                    <input type="number" name="per_screen_run_time" id="per_screen_run_time" min="0">
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="employee_name">Employees Name</label>
                                    <select multiple name="employee_name[]" id="employee_name"
                                        placeholder="Select Employees" data-search="false"
                                        data-silent-initial-value-set="true" onchange="fetchEmployeeDetails()">
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->id }}"
                                                data-code="{{ $employee->full_employee_id }}">
                                                {{ $employee->employee_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="trainees">Users Name</label>
                                    <select multiple name="trainees[]" id="trainees" placeholder="Select Users"
                                        data-search="false" data-silent-initial-value-set="true"
                                        onchange="fetchEmployeeDetails()">
                                        @foreach ($users as $datas)
                                            <option value="{{ $datas->id }}">{{ $datas->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>


                            <div class="col-6">
                                <div class="group-input" id="assessmentBlock" style="display: none">
                                    <label for="classRoom_trainingName">Assessment Required? <span
                                            class="text-danger">*</span></label>
                                    <select class="assessment_required" id="assessment_required"
                                        name="assessment_required" placeholder="SelectclassRoom_training Name"
                                        onchange="toggleAssessmentQuiz()">
                                        <option value="">-- Select --</option>
                                        <option value="yes"> Yes</option>
                                        <option value="no"> No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-6">
                                <div class="group-input" id="classroomTrainingBlock" style="display: none">
                                    <label for="classRoom_trainingName">Class Room Trainer <span
                                            class="text-danger">*</span></label>
                                    <select multiple class="classRoom_training" name="classRoom_training[]"
                                        placeholder="Select class room trainer" style="border: 1px solid #ddd"
                                        data-search="false" data-silent-initial-value-set="true" id="classRoom_training">
                                        @foreach ($traineesPerson as $user_id)
                                            @php
                                                $user = \App\Models\User::find($user_id);
                                            @endphp
                                            @if ($user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    var selectField = document.getElementById('training-select');
                                    var inputsToToggle = [];

                                    // Add elements with class 'facility-name' to inputsToToggle
                                    var facilityNameInputs = document.getElementsByClassName('assessment_required');
                                    var facilityNameInputs1 = document.getElementsByClassName('classRoom_training');

                                    for (var i = 0; i < facilityNameInputs.length; i++) {
                                        inputsToToggle.push(facilityNameInputs[i]);
                                    }
                                    for (var i = 0; i < facilityNameInputs.length; i++) {
                                        inputsToToggle.push(facilityNameInputs1[i]);
                                    }

                                    selectField.addEventListener('change', function() {
                                        var isRequired = this.value === 'Classroom Training';

                                        inputsToToggle.forEach(function(input) {
                                            input.required = isRequired;
                                        });
                                    });
                                });
                            </script>

                            <script>
                                function toggleMultiSelect() {
                                    var selectedValue = document.getElementById("training-select").value;
                                    var multiSelectField = document.getElementById("classroomTrainingBlock");
                                    var AssessmentQuiz = document.getElementById("AssessmentQuiz");
                                    var multiSelectField1 = document.getElementById("assessmentBlock");

                                    if (selectedValue === "Classroom Training") {
                                        multiSelectField.style.display = "block";
                                        multiSelectField1.style.display = "block";
                                    } else {
                                        multiSelectField.style.display = "none";
                                        multiSelectField1.style.display = "none";
                                    }

                                    if (selectedValue === "Read & Understand with Questions") {
                                        AssessmentQuiz.style.display = "block";
                                    } else {
                                        AssessmentQuiz.style.display = "none";
                                    }

                                }
                            </script>
                           

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="desc">Training Plan Description</label>
                                    <textarea name="desc"></textarea>
                                </div>
                            </div>


                            <script>
                                $(document).ready(function() {
                                    $(".add_training_attachment").click(function() {
                                        $("#myfile").trigger("click");
                                    });
                                });

                                function addAttachmentFiles(input, block_id) {
                                    console.log('test')
                                    let block = document.getElementById(block_id);
                                    let files = input.files;
                                    for (let i = 0; i < files.length; i++) {
                                        let div = document.createElement('div');
                                        div.className = 'attachment-item';
                                        div.innerHTML = files[i].name;

                                        let viewLink = document.createElement("a");
                                        viewLink.href = URL.createObjectURL(files[i]);
                                        viewLink.textContent = "</View>";
                                        viewLink.addEventListener('click', function(e) {
                                            e.preventDefault();
                                            window.open(viewLink.href, '_blank');
                                        });


                                        let removeButton = document.createElement("a");
                                        removeButton.className = 'remove-button';
                                        removeButton.textContent = "</Remove>";
                                        removeButton.addEventListener('click', function() {
                                            div.remove();
                                            input.value = '';
                                        });

                                        console.log(removeButton)

                                        div.appendChild(viewLink);
                                        div.appendChild(removeButton);
                                        block.appendChild(div);
                                    }
                                }
                            </script>
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="attachments">Attachments</label>
                                    <div><small class="text-primary">Please attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="training_attachment"></div>
                                        <div class="add-btn">
                                            <div class="add_training_attachment" style="cursor: pointer;">Add</div>
                                            <input type="file" id="myfile" name="training_attachment[]"
                                                oninput="addAttachmentFiles(this, 'training_attachment')" multiple>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function toggleAssessmentQuiz() {
                                    var selectedValue = document.getElementById("assessment_required").value;
                                    var AssessmentQuiz = document.getElementById("AssessmentQuiz");

                                    if (selectedValue === "yes") {
                                        AssessmentQuiz.style.display = "block";
                                    } else {
                                        AssessmentQuiz.style.display = "none";
                                    }

                                }
                            </script>

                            <div class="col-12" id="AssessmentQuiz" style="display: none">
                                <div class="group-input">
                                    <label for="quize">Quizz <span class="text-danger">*</span></label>
                                    <div><small class="text-primary">To pass, score 80% or higher. Example: In a 10-question quiz, answer at least 8 correctly.</small></div>
                                    <select id="quizzz" name="quize">
                                        <option value="">---</option>
                                        @foreach ($quize as $temp)
                                            <option data-id="{{ $temp->passing }}" value="{{ $temp->id }}">
                                                {{ $temp->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p id="trainingQuiz" style="color: red">
                                        ** Training quiz is missing...
                                    </p>
                                </div>
                            </div>
                            <script>
                                $(document).ready(function() {
                                    $('#quizz').hide();

                                    $('[name="classRoom_training[]"]').change(function() {
                                        if ($(this).val() === 'yes') {
                                            $('#quizz').show();
                                            $('#quizz span').show();
                                        } else {
                                            $('#quizz').hide();
                                            $('#quizz span').hide();
                                        }
                                    });
                                });
                            </script>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="effective-criteria">Employee Effective Criteria(in %) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" min='0' max='100' name="effective_criteria"
                                        oninput="validateInput(this)" required>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="classRoom_trainingName">Status </label>
                                    <select class="assessment_required" id="status" name="status"
                                        placeholder="SelectclassRoom_training Name">
                                        <option value="active"> Active</option>
                                        <option value="inactive"> Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <script>
                                function validateInput(input) {
                                    if (input.value < 0) {
                                        input.value = 0;
                                    }
                                    if (input.value > 100) {
                                        input.value = 100;
                                    }
                                }
                            </script>
                           
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for=""></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner-block">
                            <div class="main-head">
                                Selecting Document's
                            </div>
                            <div class="inner-block-content">
                              
                                <div class="search-bar">
                                    <input type="text" id="searchInput" name="search"
                                        placeholder="Search Document's">
                                    <button id="searchButton"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </div>

                                <div class="selection-table">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>&nbsp;</th>
                                                <th>Document Number</th>
                                                <th>Document Title</th>
                                                <th>Due Date</th>
                                                <th>Status</th>
                                                <th>Effective Date</th>
                                                <th>Originator</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            @foreach ($due as $temp)
                                                @if ($temp->root_document)
                                                    @if (
                                                        $temp->root_document->stage >= 6 &&
                                                            $temp->trainer == auth()->id() &&
                                                            $temp->root_document->status == 'Under-Training' &&
                                                            $temp->status == 'Past-due')
                                                        <tr>
                                                            <td class="text-center">
                                                                <input class="select-sop-js"
                                                                    data-id="{{ $temp->root_document->id }}"
                                                                    type="checkbox" id="sopData" name="sops[]"
                                                                    value="{{ $temp->document_id }}">
                                                            </td>

                                                            @php
                                                                $temp1 = DB::table('document_types')
                                                                    ->where('name', $temp->document_type_name)
                                                                    ->value('typecode');
                                                            @endphp
                                                            <td>{{ $temp->division_name }}/@if ($temp->document_type_name)
                                                                    {{ $temp1 }} /
                                                                @endif{{ $temp->year }}/
                                                                {{ str_pad($temp->root_document->id, 4, '0', STR_PAD_LEFT) }}/R{{ $temp->major }}.{{ $temp->minor }}
                                                            </td>
                                                            <td>
                                                                {{ $temp->root_document ? $temp->root_document->document_name : '' }}
                                                            </td>
                                                            <td>{{ $temp->training->due_dateDoc }}</td>
                                                            <td>{{ $temp->training->status }}</td>
                                                            <td>{{ $temp->training->effective_date ? $temp->training->effective_date : '-' }}
                                                            </td>
                                                            <td>{{ $temp->originator }}</td>
                                                        </tr>
                                                    @elseif($temp->root_document->status == 'Effective')
                                                        <tr>
                                                            <td class="text-center">
                                                                <input class="select-sop-js"
                                                                    data-id="{{ $temp->root_document->id }}"
                                                                    type="checkbox" id="sopData" name="sops[]"
                                                                    value="{{ $temp->document_id }}">
                                                            </td>

                                                            @php
                                                                $temp1 = DB::table('document_types')
                                                                    ->where('name', $temp->document_type_name)
                                                                    ->value('typecode');
                                                            @endphp
                                                            <td>{{ $temp->division_name }}/@if ($temp->document_type_name)
                                                                    {{ $temp1 }} /
                                                                @endif{{ $temp->year }}/
                                                                000{{ $temp->root_document ? $temp->root_document->document_number : '' }}/R{{ $temp->major }}.{{ $temp->minor }}
                                                            </td>
                                                            <td>
                                                                {{ $temp->root_document ? $temp->root_document->document_name : '' }}
                                                            </td>
                                                            <td>{{ $temp->root_document->due_dateDoc }}</td>
                                                            <td>{{ $temp->root_document->status }}</td>
                                                            <td>{{ $temp->root_document->effective_date ? $temp->root_document->effective_date : '-' }}
                                                            </td>
                                                            <td>{{ $temp->originator }}</td>
                                                        </tr>
                                                    @endif
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                    <p id="SOPType" style="color: red">
                                        ** Please Select atliest one Document...
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>

              

                <div class="foot-btns">
                    <a href="{{ route('TMS.index') }}"><button>Cancel</button></a>
                    <button type="submit" id="SubmitTraining">Create</button>
                </div>
            </form>

        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.8/axios.min.js"
        integrity="sha512-PJa3oQSLWRB7wHZ7GQ/g+qyv6r4mbuhmiDb8BjSFZ8NZ2a42oTtAq5n0ucWAwcQDlikAtkub+tPVCw4np27WCg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('input[name="sops[]"]').change(async function() {

            let getUrl = "{{ route('sop_training_users') }}";
            const isChecked = $(this).is(':checked');

            if (isChecked) {
                let sopId = $(this).data('id');
                getUrl = `${getUrl}/${sopId}`;

                $('#fetchUserBlock').show();

                try {

                    const res = await axios.get(getUrl);

                    if (res.data.status == 'ok') {

                        $('.user-selection-table-js').html(res.data.body);

                    } else {
                        throw Error(res.data.message);
                    }

                } catch (err) {
                    console.log('Error in sop user fetching', err.message);
                }

                $('#fetchUserBlock').hide();

            }
        })
    </script>

    <script>
        $(document).ready(function() {
            $("#training-select").change(function() {
                var selectedValue = $(this).val();
                var quizDiv = $("#quizz");
                var traineeCriteriaDiv = $("#trainee_criteria");
                if (selectedValue === "Read & Understand") {
                    quizDiv.hide();
                    traineeCriteriaDiv.hide();
                } else if (selectedValue === "Read & Understand with Questions") {
                    quizDiv.show();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "Classroom Training") {
                    quizDiv.hide();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "On Job Training") {
                    quizDiv.show();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "External Training") {
                    quizDiv.hide();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "Refresher Training") {
                    quizDiv.show();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "Retraining") {
                    quizDiv.hide();
                    traineeCriteriaDiv.show();
                } else if (selectedValue === "CBT") {
                    quizDiv.hide();
                    traineeCriteriaDiv.show();
                }
            });
        });

        const itemList = document.getElementById('training-question');
        const selectedList = document.getElementById('training-ques-selected');
        itemList.addEventListener('click', function(e) {
            const selectedItem = e.target.closest('tr');
            if (selectedItem) {
                const itemData = selectedItem.getAttribute('data-item');
                const existingItem = selectedList.querySelector(`tr[data-item="${itemData}"]`);
                if (!existingItem) {
                    const newItem = selectedItem.cloneNode(true);
                    const deleteBtn = document.createElement('button');
                    deleteBtn.textContent = 'Delete';
                    deleteBtn.addEventListener('click', function() {
                        newItem.remove();
                    });
                    const td = document.createElement('td');
                    td.appendChild(deleteBtn);
                    newItem.appendChild(td);
                    selectedList.appendChild(newItem);
                }
            }
        });
    </script>
    <script>
        VirtualSelect.init({
            ele: '#Facility, #Group, #Audit, #Auditee ,#capa_related_record ,#classRoom_training, #department, #employee_name, #trainees'
        });
    </script>
@endsection
