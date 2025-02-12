@extends('frontend.layout.main')
@section('container')

    @php
        $users = DB::table('users')->select('id', 'name')->get();
        $requestNUmber = 'RV/RP/' . str_pad($data->record, 4, '0', STR_PAD_LEFT) . '/' . date('Y');
        $formStatus = $data->stage;
    @endphp
    <style>
        textarea.note-codable {
            display: none !important;
        }
        #request_for > div > div {
            z-index: 3 !important;
        }

        header {
            display: none;
        }

        .custom-file-upload {
            background-color: #007bff;
            color: white;
            padding: 6px 12px;
            cursor: pointer;
            border-radius: 4px;
            margin-right: 10px;
        }

        #fileName {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }
    </style>
    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }

        .custom-select {
            border: 1px solid black !important;
            height: 32px;
            margin-top: -11px;
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
            font-size: 12px;

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

        #change-control-fields>div>div.inner-block.state-block>div.status>div.progress-bars.d-flex>div:nth-child(14) {
            border-radius: 0px 20px 20px 0px;
        }
    </style>

    <style>
        .collapsible-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            padding: 10px;
            /* background-color: #f8f9fa; */
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-top: 10px;
        }

        .collapsible-header .title {
            font-weight: bold;
            color: #007bff;
            text-decoration: none;
            flex-grow: 1;
            font-size: 18px;
        }

        .collapsible-header .icon {
            font-size: 20px;
            transition: transform 0.3s;
        }

        .collapsible-header.collapsed .icon {
            transform: rotate(180deg);
        }

        .collapsible-content {
            padding: 15px;
            border: 1px solid #ddd;
            border-top: none;
            border-radius: 0 0 4px 4px;
        }
    </style>


    <style>
        textarea.note-codable {
            display: none !important;
        }

        header {
            display: none;
        }

        .sub-main-head {
            display: flex;
            justify-content: space-evenly;
        }

        .Activity-type {
            margin-bottom: 7px;
        }

        /* .sub-head {
                                margin-left: 280px;
                                margin-right: 280px;
                                color: #4274da;
                                border-bottom: 2px solid #4274da;
                                padding-bottom: 5px;
                                margin-bottom: 20px;
                                font-weight: bold;
                                font-size: 1.2rem;

                            } */
        .launch_extension {
            background: #4274da;
            color: white;
            border: 0;
            padding: 4px 15px;
            border: 1px solid #4274da;
            transition: all 0.3s linear;
        }

        .main_head_modal li {
            margin-bottom: 10px;
        }

        .extension_modal_signature {
            display: block;
            width: 100%;
            border: 1px solid #837f7f;
            border-radius: 5px;
        }

        .main_head_modal {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .create-entity {
            background: #323c50;
            padding: 10px 15px;
            color: white;
            margin-bottom: 20px;

        }

        .bottom-buttons {
            display: flex;
            justify-content: flex-end;
            margin-right: 300px;
            margin-top: 50px;
            gap: 20px;
        }

        .text-danger {
            margin-top: -22px;
            padding: 4px;
            margin-bottom: 3px;
        }

        /* .saveButton:disabled{
                                    background: black!important;
                                    border:  black!important;

                                } */

        .main-danger-block {
            display: flex;
        }

        .swal-modal {
            scale: 0.7 !important;
        }

        .swal-icon {
            scale: 0.8 !important;
        }

        .custom-select {
            border: 1px solid black !important;
            height: 32px;
            margin-top: -11px;
        }

        .custom-date-picker {
            height: 35px;
            border: 1px solid black !important;
            padding: 11px !important;
        }

        .custom-border {
            border: 1px solid black !important;
            /* padding: 10px;
                            margin-bottom: 10px;
                            margin-top: 10px; */
        }
    </style>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"
        integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    @if (Session::has('swal'))
        <script>
            swal("{{ Session::get('swal')['title'] }}", "{{ Session::get('swal')['message'] }}",
                "{{ Session::get('swal')['type'] }}")
        </script>
    @endif

    <!-- <script>
        $(document).ready(function() {
            let certificationDataIndex =
                {{ $certificationData && is_array($certificationData) ? count($certificationData) : 1 }};
            $('#certificationData').click(function(e) {
                function generateTableRow(serialNumber) {
                    var html =
                        '<tr>' +
                        '<td><input disabled type="text" name="serial[]" value="' + serialNumber +
                        '"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex +
                        '][type]"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex +
                        '][issuingAgency]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificationDataIndex +
                        '][issueDate]"></td>' +
                        '<td><input type="date" name="certificationData[' + certificationDataIndex +
                        '][expiryDate]"></td>' +
                        ' <td><input type="text" name="certificationData[' + certificationDataIndex +
                        '][supportingDoc]"></td>' +
                        '<td><input type="text" name="certificationData[' + certificationDataIndex +
                        '][remarks]"></td>' +
                        '<td><button type="text" class="removeRowBtn">Remove</button></td>' +

                        '</tr>';
                    '</tr>';
                    certificationDataIndex++;
                    return html;
                }

                var tableBody = $('#certificationDataTable tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);
            });
        });
    </script> -->
                                                                     <script>
                                                                            document.addEventListener('DOMContentLoaded', function () {
                                                                                // Get all issue date and expiry date inputs
                                                                                const issueDateInputs = document.querySelectorAll('.issuedate');
                                                                                const expiryDateInputs = document.querySelectorAll('.expirydate');

                                                                                // Add event listeners to issue date inputs
                                                                                issueDateInputs.forEach(input => {
                                                                                    input.addEventListener('change', validateDates);
                                                                                });

                                                                                // Add event listeners to expiry date inputs
                                                                                expiryDateInputs.forEach(input => {
                                                                                    input.addEventListener('change', validateDates);
                                                                                });

                                                                                function validateDates() {
                                                                                    const type = this.dataset.type;
                                                                                    const index = this.dataset.index;
                                                                                    const issueDate = document.getElementById(`issuedate_${index}_${type}`).value;
                                                                                    const expiryDate = document.getElementById(`expirydate_${index}_${type}`).value;

                                                                                    if (issueDate && expiryDate) {
                                                                                        if (new Date(issueDate) > new Date(expiryDate)) {
                                                                                            alert('Expiry date cannot be before the issue date.');
                                                                                            this.value = '';
                                                                                        }
                                                                                    }
                                                                                }
                                                                            });
                                                                        </script>

    <script>
        $(document).ready(function() {
            let certificationDataIndex =
                {{ $certificationData && is_array($certificationData) ? count($certificationData) : 1 }};

            // Function to generate new table row
            function generateTableRow(serialNumber) {
                var html =
                    '<tr>' +
                    '<td style="width: 60px;"><input disabled type="text" name="serial[]" value="' + serialNumber +
                    '"></td>' +
                    '<td><input type="text" name="certificationData[' + certificationDataIndex + '][type]"></td>' +
                    '<td><input type="text" name="certificationData[' + certificationDataIndex +
                    '][issuingAgency]"></td>' +
                    '<td><input type="date" name="certificationData[' + certificationDataIndex +
                    '][issueDate]" class="issueDate" max="' + getTodayDate() + '"></td>' +
                    '<td><input type="date" name="certificationData[' + certificationDataIndex +
                    '][expiryDate]" class="expiryDate" disabled></td>' +
                    '<td><input type="text" name="certificationData[' + certificationDataIndex +
                    '][supportingDoc]"></td>' +
                    '<td><input type="text" name="certificationData[' + certificationDataIndex +
                    '][remarks]"></td>' +
                    '<td><button type="button" class="removeRowBtn">Remove</button></td>' +
                    '</tr>';
                certificationDataIndex++;
                return html;
            }

            // Function to get today's date in YYYY-MM-DD format
            function getTodayDate() {
                var today = new Date();
                return today.toISOString().split('T')[0];
            }

            // Function to add date validation
            function addDateValidation() {
                $('.issueDate').off('change').on('change', function() {
                    var issueDate = $(this).val();
                    var expiryDateInput = $(this).closest('tr').find('.expiryDate');
                    expiryDateInput.attr('min', issueDate);
                    expiryDateInput.removeAttr('disabled');
                });

                $('.expiryDate').off('change').on('change', function() {
                    var issueDate = $(this).closest('tr').find('.issueDate').val();
                    var expiryDate = $(this).val();

                    if (expiryDate <= issueDate) {
                        alert('Expiry date must be greater than issue date.');
                        $(this).val('');
                    }
                });

                $('.removeRowBtn').off('click').on('click', function() {
                    $(this).closest('tr').remove();
                });
            }

            // Add new row on button click
            $('#certificationData').click(function(e) {
                var tableBody = $('#certificationDataTable tbody');
                var rowCount = tableBody.children('tr').length;
                var newRow = generateTableRow(rowCount + 1);
                tableBody.append(newRow);

                // Add validation for the new row
                addDateValidation();
            });

            // Add validation to existing rows
            addDateValidation();
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const issuedates = document.querySelectorAll('.issuedate');
            const expirydates = document.querySelectorAll('.expirydate');

            issuedates.forEach(function(issuedate) {
                issuedate.addEventListener('change', function() {
                    const type = issuedate.dataset.type;
                    const index = issuedate.dataset.index;
                    const correspondingExpiryDate = document.getElementById(
                        `expirydate_${index}_${type}`);

                    if (issuedate.value) {
                        correspondingExpiryDate.min = issuedate.value;
                        if (correspondingExpiryDate.value && correspondingExpiryDate.value <
                            issuedate.value) {
                            correspondingExpiryDate.value = '';
                        }
                    } else {
                        correspondingExpiryDate.removeAttribute('min');
                    }
                });
            });

            expirydates.forEach(function(expirydate) {
                expirydate.addEventListener('change', function() {
                    const type = expirydate.dataset.type;
                    const index = expirydate.dataset.index;
                    const correspondingIssueDate = document.getElementById(
                        `issuedate_${index}_${type}`);

                    if (expirydate.value) {
                        correspondingIssueDate.max = expirydate.value;
                        if (correspondingIssueDate.value && correspondingIssueDate.value >
                            expirydate.value) {
                            correspondingIssueDate.value = '';
                        }
                    } else {
                        correspondingIssueDate.removeAttribute('max');
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
    <div class="form-field-head">
        <div class="division-bar">
            <strong>Site Division/Project</strong> :
            {{ Helpers::getDivisionName($data->division_id) }} / Supplier
        </div>
    </div>
    <div id="change-control-fields">
        <div class="container-fluid">
            <div class="inner-block state-block">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="main-head">Record Workflow </div>
                    <div class="d-flex" style="gap:20px;">
                        @php
                            $userRoles = DB::table('user_roles')
                                ->where(['user_id' => Auth::user()->id, 'q_m_s_divisions_id' => 1])
                                ->get();
                            $userRoleIds = $userRoles->pluck('q_m_s_roles_id')->toArray();
                        @endphp
                        <button class="button_theme1"> <a class="text-white"
                                href="{{ url('rcms/supplier-audit-trail', $data->id) }}"> Audit Trail </a> </button>
                        @if ($data->stage == 1 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#cancel-modal">
                                Cancel
                            </button>
                            @if ($data->approvedBy_contract_giver_by == null)
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                    Need for Sourcing of Starting Material
                                </button>
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#contract-giver-modal">
                                    Approved By Contract Giver
                                </button>
                            @endif
                            @if (!empty($data->approvedBy_contract_giver_by))
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#link-manufacturer">
                                    Link Manufacturer Code to Material Code through MPN in SAP
                                </button>
                                <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#">
                                    Initiate Periodic Revaluation
                                </button>
                            @endif
                        @elseif($data->stage == 2 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Request Justified
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request Not Justified
                            </button>
                        @elseif($data->stage == 3 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Pre-Purchase Sample Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#pendingCQAReview-modal">
                                Pre-Purchase Sample Not Required
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 4 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Purchase Sample Request Initiated & Acknowledgement By Purchase Department
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 5 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Purchase Sample Analysis Satisfactory
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Purchase Sample Analysis Not Satisfactory
                            </button>
                        @elseif($data->stage == 6 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                F&D Review Completed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 7 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Acknowledgement By Purchase Departament
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 8 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                All Requirements Fulfilled
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal"
                                data-bs-target="#manufacturer-reject-modal">
                                All Requirements Not Fulfilled
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 10 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Risk Rating Observed as High/Medium
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#risk-rating-low-modal">
                                Risk Rating Observed as Low
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 11 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Manufacturer Audit Passed
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button> --}}
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button>
                        @elseif($data->stage == 12 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Initiate Periodic Revaluation
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#">
                                Link Manufacturer Code to Material Code through MPN in SAP
                            </button>
                            <!-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Request More Info
                            </button> -->
                        @elseif($data->stage == 13 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Risk Rating Observed as High/Medium
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#backword-modal">
                                Risk Rating Observed as Low
                            </button>
                        @elseif($data->stage == 14 && Helpers::check_roles($data->division_id, 'Supplier', 18))
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#signature-modal">
                                Manufacturer Audit Failed
                            </button>
                            <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#pending-manufacturer-audit-more-info">
                                Request More Info
                            </button>
                            {{-- <button class="button_theme1" data-bs-toggle="modal" data-bs-target="#child-modal">
                                Child
                            </button> --}}
                        @endif
                            <button class="button_theme1"> <a class="text-white" href="{{ url('rcms/qms-dashboard') }}"> Exit</a>
                            </button>
                    </div>
                </div>
                <div class="status">
                    <div class="head">Current Status</div>
                    @if ($data->stage == 0)
                        <div class="progress-bars">
                            <div class="bg-danger">Closed-Cancelled</div>
                        </div>
                    @else
                        <div class="progress-bars d-flex" style="font-size: 15px;">
                            @if ($data->stage >= 1)
                                <div class="active">Opened</div>
                            @else
                                <div class="">Opened</div>
                            @endif

                            @if ($data->stage >= 2)
                                <div class="active">Pending Initiating Department Update</div>
                            @else
                                <div class="">Pending Initiating Department Update</div>
                            @endif

                            @if ($data->stage >= 3)
                                <div class="active">Pending Update FROM CQA</div>
                            @else
                                <div class="">Pending Update FROM CQA</div>
                            @endif

                            @if ($data->stage >= 4)
                                <div class="active">Pending Purchase Sample Request</div>
                            @else
                                <div class="">Pending Purchase Sample Request</div>
                            @endif

                            @if ($data->stage >= 5)
                                <div class="active">Pending CQA Review After Purchase Sample Request</div>
                            @else
                                <div class="">Pending CQA Review After Purchase Sample Request</div>
                            @endif

                            @if ($data->stage >= 6)
                                <div class="active">Pending F&D Review</div>
                            @else
                                <div class="">Pending F&D Review</div>
                            @endif

                            @if ($data->stage >= 7)
                                <div class="active">Pending Acknowledgement By Purchase Department</div>
                            @else
                                <div class="">Pending Acknowledgement By Purchase Department</div>
                            @endif

                            @if ($data->stage >= 8)
                                <div class="active">Pending CQA Final Review</div>
                            @else
                                <div class="">Pending CQA Final Review</div>
                            @endif

                            @if ($data->stage >= 9)
                                <div class="active bg-danger"
                                    @if ($data->stage > 9) style="display: none" @endif>Manufacturer Rejected
                                </div>
                            @else
                                <div class="" @if ($data->stage > 9) style="display: none" @endif>
                                    Manufacturer Rejected</div>
                            @endif

                            @if ($data->stage >= 10)
                                <div class="active" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Risk Assessment</div>
                            @else
                                <div class="" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Risk Assessment</div>
                            @endif

                            @if ($data->stage >= 11)
                                <div class="active" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Audit</div>
                            @else
                                <div class="" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Audit</div>
                            @endif

                            @if ($data->stage >= 12)
                                <div class="active" @if ($data->stage == 9) style="display: none" @endif>
                                    Approved Manufacturer/Supplier</div>
                            @else
                                <div class="" @if ($data->stage == 9) style="display: none" @endif>
                                    Approved Manufacturer/Supplier</div>
                            @endif

                            @if ($data->stage >= 13)
                                <div class="active" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Risk Assessment</div>
                            @else
                                <div class="" @if ($data->stage == 9) style="display: none" @endif>Pending
                                    Manufacturer Risk Assessment</div>
                            @endif

                            @if ($data->stage >= 14)
                                <div class="active" @if ($data->stage == 9) style="display: none" @endif>
                                    Pending Manufacturer Audit</div>
                            @else
                                <div class="" @if ($data->stage == 9) style="display: none" @endif>
                                    Pending Manufacturer Audit</div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            <!-- Tab links -->
            <div class="cctab">
                <button class="cctablinks active" onclick="openCity(event, 'CCForm1')">Request for Creation of New
                    Manufacturer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm2')">HOD Review</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm3')">Supplier Details</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm4')">Score Card</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm5')">QA Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm6')">Risk Assessment</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm7')">QA Head Reviewer</button>
                <button class="cctablinks" onclick="openCity(event, 'CCForm8')">Activity Log</button>
            </div>

            <!--  Contract Tab content -->
            <form id="target" action="{{ route('supplier-update', $data->id) }} }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div id="CCForm1" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Request Number</b></label>
                                    <input type="text" value="{{ $requestNUmber }}" disabled>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Division</b></label>
                                    <input type="text" disabled id="division_id"
                                        value="{{ Helpers::getDivisionName($data->division_id) }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiator"><b>Initiator</b></label>
                                    <input disabled type="text" name="initiator_id" id="initiator_id"
                                        value="{{ Helpers::getInitiatorName($data->initiator_id) }}">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Initiation"><b>Initiation Date</b></label>
                                    <input disabled type="text"
                                        value="{{ Helpers::getdateFormat($data->intiation_date) }}">
                                </div>
                            </div>

                            <!-- <div class="col-md-6">
                                                    <div class="group-input">
                                                        <label for="search">Assigned To <span class="text-danger"></span>
                                                        </label>
                                                        <select id="select-state" name="assign_to">
                                                            <option value="">Select a value</option>
                                                            @if (!empty($users))
    @foreach ($users as $user)
    <option @if ($data->assign_to == $user->id) selected @endif value="{{ $user->id }}">{{ $user->name }}</option>
    @endforeach
    @endif
                                                        </select>
                                                    </div>
                                                </div> -->

                            @php
                                $initiationDate = date('Y-m-d');
                                $dueDate = date('Y-m-d', strtotime($initiationDate . '+30 days'));
                            @endphp

                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="due-date">Date Due</label>
                                    <div><small class="text-primary">Please mention expected date of completion</small>
                                    </div>
                                    <div class="calenderauditee">
                                        <div class="calenderauditee">
                                            <input readonly type="text"
                                                value="{{ Helpers::getdateFormat($data->due_date) }}" name="due_date" />
                                            <input type="date" name="due_date"
                                                min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                                oninput="handleDateInput(this, 'due_date')" disabled />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <script>
                                // Format the due date to DD-MM-YYYY
                                // Your input date
                                var dueDate = "{{ $dueDate }}"; // Replace {{ $dueDate }} with your actual date variable

                                // Create a Date object
                                var date = new Date(dueDate);

                                // Array of month names
                                var monthNames = [
                                    "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                    "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                ];

                                // Extracting day, month, and year from the date
                                var day = date.getDate().toString().padStart(2, '0'); // Ensuring two digits
                                var monthIndex = date.getMonth();
                                var year = date.getFullYear();

                                // Formatting the date in "dd-MMM-yyyy" format
                                var dueDateFormatted = `${day}-${monthNames[monthIndex]}-${year}`;

                                // Set the formatted due date value to the input field
                                document.getElementById('due_date').value = dueDateFormatted;
                            </script>

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Short Description">Short Description<span
                                            class="text-danger">*</span></label><span id="rchars">255</span>
                                    characters remaining
                                    <div class="relative-container">
                                        <input id="docname" class="mic-input" type="text" name="short_description"
                                            maxlength="255" required value="{{ $data->short_description }}">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <script>
                                var maxLength = 255;
                                $('#docname').keyup(function() {
                                    var textlen = maxLength - $(this).val().length;
                                    $('#rchars').text(textlen);
                                });
                            </script>

                            <div class="container">
                                <div class="collapsible-section">
                                    <div class="collapsible-header" data-toggle="collapse"
                                        data-target="#collapsePurchase" aria-expanded="false"
                                        aria-controls="collapsePurchase">
                                        <span class="title">Purchase Department</span>
                                        <span class="icon">&#x25B2;</span>
                                    </div>
                                    <div class="collapse" id="collapsePurchase">
                                        <div class="collapsible-content">
                                            <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group">Initiation Department</label>
                                                        <select name="initiation_group" id="initiation_group">
                                                            <option value="">-- Select --</option>
                                                            <option value="CQA"
                                                                @if ($data->initiator_group_code == 'CQA') selected @endif> Corpo
                                                                Assurance Biopharma</option>
                                                            <option value="CQC"
                                                                @if ($data->initiator_group_code == 'CQC') selected @endif> Central
                                                                Quality Control</option>
                                                            <option value="MANU"
                                                                @if ($data->initiator_group_code == 'MANU') selected @endif>
                                                                Manufacturing</option>
                                                            <option value="PSG"
                                                                @if ($data->initiator_group_code == 'PSG') selected @endif> Plasma
                                                                Sourcing Group</option>
                                                            <option value="CS"
                                                                @if ($data->initiator_group_code == 'CS') selected @endif> Central
                                                                Stores</option>
                                                            <option value="ITG"
                                                                @if ($data->initiator_group_code == 'ITG') selected @endif>
                                                                Information Technology Group</option>
                                                            <option value="MM"
                                                                @if ($data->initiator_group_code == 'MM') selected @endif>
                                                                Molecular Medicine</option>
                                                            <option value="CL"
                                                                @if ($data->initiator_group_code == 'CL') selected @endif> Central
                                                                Laboratory</option>
                                                            <option value="TT"
                                                                @if ($data->initiator_group_code == 'TT') selected @endif> Tech
                                                                Team</option>
                                                            <option value="QA"
                                                                @if ($data->initiator_group_code == 'QA') selected @endif> Quality
                                                                Assurance</option>
                                                            <option value="QM"
                                                                @if ($data->initiator_group_code == 'QM') selected @endif> Quality
                                                                Management</option>
                                                            <option value="IA"
                                                                @if ($data->initiator_group_code == 'IA') selected @endif>
                                                                IT Administration</option>
                                                            <option value="ACC"
                                                                @if ($data->initiator_group_code == 'ACC') selected @endif>
                                                                Accounting</option>
                                                            <option value="LOG"
                                                                @if ($data->initiator_group_code == 'LOG') selected @endif>
                                                                Logistics</option>
                                                            <option value="SM"
                                                                @if ($data->initiator_group_code == 'SM') selected @endif> Senior
                                                                Management</option>
                                                            <option value="BA"
                                                                @if ($data->initiator_group_code == 'BA') selected @endif>
                                                                Business Administration</option>
                                                        </select>
                                                        @error('initiator_group_code')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>


                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Initiator Department Code</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                name="initiator_group_code" id="initiator_group_code"
                                                                class='mic-input'
                                                                value="{{ $data->initiator_group_code }}" readonly>
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Name of Manufacturer</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                name="manufacturerName"
                                                                value="{{ $data->manufacturerName }}"
                                                                id="manufacturerName" placeholder="Name of Manufacturer">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Name of Starting Material</label>
                                                        <div class="relative-container">
                                                            <input type="text" value="{{ $data->starting_material }}"
                                                                name="starting_material" id="starting_material">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Material Code</label>
                                                        <div class="relative-container">
                                                            <input type="text" name="material_code" id="material_code"
                                                                value="{{ $data->material_code }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Pharmacopoeial Claim</label>
                                                        <div class="relative-container">
                                                            <input type="text" name="pharmacopoeial_claim"
                                                                id="pharmacopoeial_claim"
                                                                value="{{ $data->pharmacopoeial_claim }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">CEP Grade Material</label>
                                                        <select id="cep_grade" name="cep_grade">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->cep_grade == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->cep_grade == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>


                                                @if ($data->cep_attachment)
                                                    @foreach (json_decode($data->cep_attachment) as $file)
                                                        <input id="2-{{ $loop->index }}" type="hidden"
                                                        name="existing_attach_files_b[{{ $loop->index }}]"
                                                        value="{{ $file }}">
                                                    @endforeach
                                                @endif
                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for=" Attachments">CEP Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or
                                                                supporting documents</small></div>
                                                    <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="cep_attachment">
                                                        @if ($data->cep_attachment)
                                                            @foreach (json_decode($data->cep_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="2-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            type="file" id="myfile" name="cep_attachment[]"
                                                            oninput="addMultipleFiles(this, 'cep_attachment')" multiple>
                                                    </div>
                                                </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="request_for">Request For</label>
                                                        <select id="request_for" name="request_for[]" multiple>
                                                            <option value="API"
                                                                {{ strpos($data->request_for, 'API') !== false ? 'selected' : '' }}>
                                                                API</option>
                                                            <option value="Excipient"
                                                                {{ strpos($data->request_for, 'Excipient') !== false ? 'selected' : '' }}>
                                                                Excipient</option>
                                                            <option value="New Manufacturer"
                                                                {{ strpos($data->request_for, 'New Manufacturer') !== false ? 'selected' : '' }}>
                                                                New Manufacturer</option>
                                                            <option value="Existing Manufacturer"
                                                                {{ strpos($data->request_for, 'Existing Manufacturer') !== false ? 'selected' : '' }}>
                                                                Existing Manufacturer</option>
                                                            <option value="Additional Site of Existing Manufacturer"
                                                                {{ strpos($data->request_for, 'Additional Site of Existing Manufacturer') !== false ? 'selected' : '' }}>
                                                                Additional Site of Existing Manufacturer</option>
                                                            <option value="Brand New API"
                                                                {{ strpos($data->request_for, 'Brand New API') !== false ? 'selected' : '' }}>
                                                                Brand New API</option>
                                                            <option value="Existing API"
                                                                {{ strpos($data->request_for, 'Existing API') !== false ? 'selected' : '' }}>
                                                                Existing API</option>
                                                            <option value="Brand New Excipient"
                                                                {{ strpos($data->request_for, 'Brand New Excipient') !== false ? 'selected' : '' }}>
                                                                Brand New Excipient</option>
                                                            <option value="Existing Excipient"
                                                                {{ strpos($data->request_for, 'Existing Excipient') !== false ? 'selected' : '' }}>
                                                                Existing Excipient</option>
                                                            <option value="R&D development"
                                                                {{ strpos($data->request_for, 'R&D development') !== false ? 'selected' : '' }}>
                                                                R&D development</option>
                                                            <option value="Site Transfer"
                                                                {{ strpos($data->request_for, 'Site Transfer') !== false ? 'selected' : '' }}>
                                                                Site Transfer</option>
                                                            <option value="Alternate manufacturer"
                                                                {{ strpos($data->request_for, 'Alternate manufacturer') !== false ? 'selected' : '' }}>
                                                                Alternate manufacturer</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Attach Three Batch CQAs</label>
                                                        <select id="attach_batch" name="attach_batch">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->attach_batch == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->attach_batch == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Justification for Request</label>
                                                        <textarea type="text" name="request_justification" value="{{ $data->request_justification }}" class="tiny">{{ $data->request_justification }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>





                            <div class="container">
                                <div class="collapsible-section">
                                    <!-- CQA Department Section -->
                                    <div class="collapsible-header" data-toggle="collapse" data-target="#collapseCQA"
                                        aria-expanded="false" aria-controls="collapseCQA">
                                        <span class="title">CQA Department</span>
                                        <span class="icon">&#x25B2;</span>
                                    </div>
                                    <div class="collapse" id="collapseCQA">
                                        <div class="collapsible-content">
                                            <div class="row">
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Availability of Manufacturer
                                                            CQAs</label>
                                                        <select id="manufacturer_availability"
                                                            name="manufacturer_availability">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->manufacturer_availability == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->manufacturer_availability == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Request Accepted</label>
                                                        <select id="request_accepted" name="request_accepted">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->request_accepted == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->request_accepted == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Remark</label>
                                                        <textarea type="text" name="cqa_remark" id="cqa_remark" class="tiny">{{ $data->cqa_remark }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Accepted By</label>
                                                        <select type="hidden" name="accepted_by" id="accepted_by">
                                                            <option value="">---- Select ----</option>
                                                            @if (!empty($users))
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @if ($data->accepted_by == $user->id) selected @endif>
                                                                        {{ $user->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <!-- <label for="Initiator Group Code">Accepted On</label> -->
                                                        <input type="hidden" name="accepted_on" id="accepted_on">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Pre Purchase Sample
                                                            Required?</label>
                                                        <div><small class="text-primary">If Yes inform purchase department
                                                                to initiate pre-purchase sample intimation sheet</small>
                                                        </div>
                                                        <div><small class="text-primary">If No then provide Justification
                                                                proceed to section 16</small></div>
                                                        <select id="pre_purchase_sample" name="pre_purchase_sample">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->pre_purchase_sample == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->pre_purchase_sample == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Justification</label>
                                                        <textarea type="text" name="justification" id="justification" class="tiny">{{ $data->justification }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">CQA Coordinator</label>
                                                        <select type="hidden" name="cqa_coordinator"
                                                            id="cqa_coordinator">
                                                            <option value="">---- Select ----</option>
                                                            @if (!empty($users))
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @if ($data->cqa_coordinator == $user->id) selected @endif>
                                                                        {{ $user->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Certificate Checklist Data -->

                                                <!-- <div class="col-12">
                                                                            <div class="group-input">
                                                                                <div class="why-why-chart">
                                                                                @php
                                                                                    $types = [
                                                                                        'tse',
                                                                                        'residual_solvent',
                                                                                        'melamine',
                                                                                        'gmo',
                                                                                        'gluten',
                                                                                        'manufacturer_evaluation',
                                                                                        'who',
                                                                                        'gmp',
                                                                                        'ISO',
                                                                                        'manufacturing_license',
                                                                                        'CEP',
                                                                                        'risk_assessment',
                                                                                        'elemental_impurity',
                                                                                        'azido_impurities',
                                                                                    ];
                                                                                @endphp

                                                                                @foreach ($types as $type)
    <table class="table table-bordered">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th style="width: 15%">Certificate Name</th>
                                                                                                <th style="width: 25%">Attachment</th>
                                                                                                <th style="width: 15%">Issue Date</th>
                                                                                                <th style="width: 15%">Expiry Date</th>
                                                                                                <th>Remark</th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody id="{{ $type }}_rows">
                                                                                            @foreach ($supplierChecklist->where('doc_type', $type) as $grid)
    @php
        $filePath = $grid->attachment;
        $fileName = str_replace('upload\\', '', $filePath);
    @endphp
                                                                                                <tr>
                                                                                                    <td>
                                                                                                        {{ strtoupper(str_replace('_', ' ', $type)) }} <br>
                                                                                                        <button type="button" onclick="addRow('{{ $type }}')">Add Row</button>
                                                                                                        <button type="button" onclick="removeRow(this)">Remove</button>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        @if ($grid->attachment)
    <input type="file" name="{{ $type }}_attachment[]" class="custom-border" style="color: white;">
                                                                                                            <span type="button" class="file-container text-dark mt-2" style="background-color: rgb(243, 242, 240);">
                                                                                                                <b>{{ $fileName }}</b>
                                                                                                                <a href="{{ asset('upload/' . $fileName) }}" target="_blank"><i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i></a>
                                                                                                                <a type="button" class="remove-file" data-file-name="{{ $fileName }}"><i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                                                                                                            </span>
@else
    <input type="file" name="{{ $type }}_attachment[]" class="custom-border">
    @endif
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input type="date" name="certificate_issue_{{ $type }}[]" value="{{ $grid->issue_date }}" class="custom-border">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <input type="date" name="certificate_expiry_{{ $type }}[]" value="{{ $grid->expiry_date }}" class="custom-border">
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <textarea name="{{ $type }}_remarks[]" class="custom-border">{{ $grid->remarks }}</textarea>
                                                                                                    </td>
                                                                                                </tr>
    @endforeach
                                                                                        </tbody>
                                                                                    </table>
    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div> -->

                                                <input type="hidden" name="supplier_id" value="{{ $data->id }}">
                                                <div class="col-12">
                                                <div class="group-input">
                                                    <div class="why-why-chart">
                                                        @php
                                                            $types = [
                                                                'tse',
                                                                'residual_solvent',
                                                                'melamine',
                                                                'gmo',
                                                                'gluten',
                                                                'manufacturer_evaluation',
                                                                'who',
                                                                'gmp',
                                                                'ISO',
                                                                'manufacturing_license',
                                                                'CEP',
                                                                'risk_assessment',
                                                                'elemental_impurity',
                                                                'azido_impurities',
                                                            ];
                                                        @endphp

                                                        @foreach ($types as $type)
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 24%">Certificate Name</th>
                                                                        <th style="width: 20%">Attachment</th>
                                                                        <th style="width: 15%">Issue Date</th>
                                                                        <th style="width: 15%">Expiry Date</th>
                                                                        <th>Remark</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="{{ $type }}_rows">
                                                                    @foreach ($supplierChecklist->where('doc_type', $type) as $grid)
                                                                        @php
                                                                            $filePath = $grid->attachment;
                                                                            $fileName = str_replace('upload\\', '', $filePath);
                                                                        @endphp
                                                                        <tr>
                                                                            <td style="display: flex; justify-content: space-between;">
                                                                                <div>{{ strtoupper(str_replace('_', ' ', $type)) }}</div>
                                                                                <div>
                                                                                    <button class="button_theme" type="button" onclick="addRow('{{ $type }}')">Add Row</button>
                                                                                </div>
                                                                            </td>
                                                                            <td>
                                                                                @if ($grid->attachment)
                                                                                    <input type="file" name="{{ $type }}_attachment[]" class="custom-border" style="color: white;">
                                                                                    <span type="button" class="file-container text-dark mt-2" style="background-color: rgb(243, 242, 240);">
                                                                                        <b>{{ $fileName }}</b>
                                                                                        <a href="{{ asset('upload/' . $fileName) }}" target="_blank">
                                                                                            <i class="fa fa-eye text-primary" style="font-size:20px; margin-right:-10px;"></i>
                                                                                        </a>
                                                                                        <a type="button" class="remove-file" data-file-name="{{ $fileName }}">
                                                                                            <i class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i>
                                                                                        </a>
                                                                                    </span>
                                                                                @else
                                                                                    <input type="file" name="{{ $type }}_attachment[]" class="custom-border">
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                                <input type="date" id="issuedate_{{ $loop->index }}_{{ $type }}" name="certificate_issue_{{ $type }}[]" value="{{ $grid->issue_date }}" class="custom-border issuedate" data-type="{{ $type }}" data-index="{{ $loop->index }}">
                                                                            </td>
                                                                            <td>
                                                                                <input type="date" id="expirydate_{{ $loop->index }}_{{ $type }}" name="certificate_expiry_{{ $type }}[]" value="{{ $grid->expiry_date }}" class="custom-border expirydate" data-type="{{ $type }}" data-index="{{ $loop->index }}">
                                                                            </td>
                                                                            <td class="relative-container">
                                                                                <textarea name="{{ $type }}_remarks[]" class="custom-border">{{ $grid->remarks }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </td>
                                                                        </tr>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>


                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Pre Purchase Sample Analysis
                                                            Completed?</label>
                                                        <select id="pre_purchase_sample_analysis"
                                                            name="pre_purchase_sample_analysis">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->pre_purchase_sample_analysis == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->pre_purchase_sample_analysis == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Availability of CQAs After
                                                            Analysis</label>
                                                        <select id="availability_od_coa" name="availability_od_coa">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->availability_od_coa == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->availability_od_coa == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Analyzed on Location</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                name="analyzed_location" id="analyzed_location"
                                                                value="{{ $data->analyzed_location }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Review Comment of CQA</label>
                                                        <textarea type="text" name="cqa_comment" id="cqa_comment" class="tiny">{{ $data->cqa_comment }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">If Analysis found satisfactory of
                                                            Pre-purchase samples send intimation</label>
                                                        <!-- <div><small class="text-primary">To: Formulation and Development / MS&T Department.</small></div>
                                                                                <div><small class="text-primary">From: Corporate Quality Assurance</small></div> -->
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Material Name</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input" name="materialName"
                                                                id="materialName" value="{{ $data->materialName }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Name of the Manufacturer</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                name="manufacturerNameNew" id="manufacturerNameNew"
                                                                value="{{ $data->manufacturerNameNew }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Analyzed on Location</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                name="analyzedLocation" id="analyzedLocation"
                                                                value="{{ $data->analyzedLocation }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Justification</label>
                                                        <textarea type="text" name="supplierJustification" id="supplierJustification" class="tiny">{{ $data->supplierJustification }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Review Comment of Corporate
                                                            CQA</label>
                                                        <textarea type="text" name="cqa_corporate_comment" id="cqa_corporate_comment" class="tiny">{{ $data->cqa_corporate_comment }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>


                                                @if ($data->coa_attachment)
                                                    @foreach (json_decode($data->coa_attachment) as $file)
                                                        <input id="3-{{ $loop->index }}" type="hidden"
                                                        name="existing_attach_files_c[{{ $loop->index }}]"
                                                        value="{{ $file }}">
                                                    @endforeach
                                                @endif
                                                <div class="col-lg-12">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">CQAs Attachment</label>
                                                        <div><small class="text-primary">Please Attach all relevant or
                                                                supporting documents</small></div>
                                                        <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="coa_attachment">
                                                        @if ($data->coa_attachment)
                                                            @foreach (json_decode($data->coa_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="3-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="coa_attachment[]"
                                                            oninput="addMultipleFiles(this, 'coa_attachment')" multiple>
                                                    </div>
                                                </div>
                                <!-- </div> -->
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">CQA Designee</label>
                                                        <select type="hidden" name="cqa_designee" id="cqa_designee">
                                                            <option value="">---- Select ----</option>
                                                            @if (!empty($users))
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @if ($data->cqa_designee == $user->id) selected @endif>
                                                                        {{ $user->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- to be filled by CQA Department -->
                            <div class="container">
                                    <div class="collapsible-section">

                                        <!-- Formulation & Development Department/CQA/MS&T Section -->
                                        <div class="collapsible-header" data-toggle="collapse" data-target="#collapseFormulation" aria-expanded="false"
                                            aria-controls="collapseFormulation">
                                            <span class="title">Formulation & Development Department/CQA/MS&T</span>
                                            <span class="icon">&#x25B2;</span>
                                        </div>
                                        <div class="collapse" id="collapseFormulation">
                                            <div class="collapsible-content">
                                                <div class="row">

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Samples Ordered for Suitability
                                                            Trail at R&D/MS & T</label>
                                                        <div><small class="text-primary">If no provide
                                                                Justification.</small></div>
                                                        <select id="sample_ordered" name="sample_ordered">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->sample_ordered == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->sample_ordered == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-12">
                                                    <div class="group-input relative-container">
                                                        <label for="Initiator Group Code">Sample Justification</label>
                                                        <textarea type="text" name="sample_order_justification" id="sample_order_justification" class="tiny">{{ $data->sample_order_justification }}</textarea>
                                                        @component('frontend.forms.language-model')
                                                        @endcomponent
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Acknowledge By</label>
                                                        <select type="hidden" name="acknowledge_by" id="acknowledge_by">
                                                            <option value="">---- Select ----</option>
                                                            @if (!empty($users))
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id }}"
                                                                        @if ($data->acknowledge_by == $user->id) selected @endif>
                                                                        {{ $user->name }}</option>
                                                                @endforeach
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Feedback on Trail Status
                                                            Completed</label>
                                                        <select id="trail_status_feedback" name="trail_status_feedback">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->trail_status_feedback == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->trail_status_feedback == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- To be filled by CQA Department -->

                                                <!-- <div class="col-lg-6"></div> -->
                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Sample Stand Approved?</label>
                                                        <select id="sample_stand_approved" name="sample_stand_approved">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->sample_stand_approved == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->sample_stand_approved == 'No') selected @endif>No
                                                            </option>
                                                            <option value="N/A"
                                                                @if ($data->sample_stand_approved == 'N/A') selected @endif>N/A
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <!-- Checklist -->

                                                <div class="col-12">
                                                    <div class="group-input">
                                                        <div class="why-why-chart">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="width: 5%;">Sr. No.</th>
                                                                        <th style="width: 30%;">Document Received</th>
                                                                        <th style="width: 15%;">Selection</th>
                                                                        <th>Remark</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td class="flex text-center">1.1</td>
                                                                        <td>TSE/BSE</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="tse_bse" name="tse_bse">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->tse_bse == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->tse_bse == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="tse_bse_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->tse_bse_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.2</td>
                                                                        <td>Residual Solvent</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="residual_solvent"
                                                                                    name="residual_solvent">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->residual_solvent == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->residual_solvent == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="residual_solvent_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->residual_solvent_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.3</td>
                                                                        <td>GMO</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="gmo" name="gmo">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->gmo == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->gmo == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="gmo_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gmo_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.4</td>
                                                                        <td>Melamine</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="melamine" name="melamine">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->melamine == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->melamine == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="melamine_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->melamine_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.5</td>
                                                                        <td>Gluten</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="gluten" name="gluten">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->gluten == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->gluten == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="gluten_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gluten_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.6</td>
                                                                        <td>Nitrosamine</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="nitrosamine" name="nitrosamine">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->nitrosamine == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->nitrosamine == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="nitrosamine_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->nitrosamine_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.7</td>
                                                                        <td>WHO</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="who" name="who">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->who == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->who == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="who_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->who_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.8</td>
                                                                        <td>GMP</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="gmp" name="gmp">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->gmp == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->gmp == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="gmp_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->gmp_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.9</td>
                                                                        <td>ISO Cerificates</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="iso_certificate"
                                                                                    name="iso_certificate">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->iso_certificate == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->iso_certificate == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="iso_certificate_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->iso_certificate_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.10</td>
                                                                        <td>Manufacturing License</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="manufacturing_license"
                                                                                    name="manufacturing_license">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->manufacturing_license == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->manufacturing_license == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="manufacturing_license_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->manufacturing_license_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.11</td>
                                                                        <td>CEP</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="cep" name="cep">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->cep == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->cep == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="cep_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->cep_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.12</td>
                                                                        <td>MSDS</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="msds" name="msds">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->msds == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->msds == 'Yes') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="msds_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->msds_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.13</td>
                                                                        <td>Elemental Impurities</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="elemental_impurities"
                                                                                    name="elemental_impurities">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->elemental_impurities == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->elemental_impurities == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="elemental_impurities_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->elemental_impurities_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td class="flex text-center">1.14</td>
                                                                        <td>Assessment/Declaration of Azido Impurities as
                                                                            Applicable</td>
                                                                        <td>
                                                                            <div
                                                                                style="display: flex; justify-content: space-around; align-items: center;  margin: 5%; gap:5px">
                                                                                <select class="custom-select"
                                                                                    id="declaration" name="declaration">
                                                                                    <option value="">---- Select ----
                                                                                    </option>
                                                                                    <option value="Yes"
                                                                                        @if ($data->declaration == 'Yes') selected @endif>
                                                                                        Yes</option>
                                                                                    <option value="No"
                                                                                        @if ($data->declaration == 'No') selected @endif>
                                                                                        No</option>
                                                                                </select>
                                                                            </div>
                                                                        </td>
                                                                        <td>
                                                                            <div style="margin: auto; display: flex; justify-content: center; "
                                                                                class="relative-container">
                                                                                <textarea name="declaration_remark" style="border-radius: 7px; border: 1.5px solid black;">{{ $data->declaration_remark }}</textarea>
                                                                                @component('frontend.forms.language-model')
                                                                                @endcomponent
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Availability of Supply
                                                            Chain?</label>
                                                        <select id="supply_chain_availability"
                                                            name="supply_chain_availability">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->supply_chain_availability == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->supply_chain_availability == 'No') selected @endif>No
                                                            </option>
                                                            <option value="N/A"
                                                                @if ($data->supply_chain_availability == 'N/A') selected @endif>N/A
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Availability of Quality
                                                            Agreement?</label>
                                                        <select id="quality_agreement_availability"
                                                            name="quality_agreement_availability">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->quality_agreement_availability == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->quality_agreement_availability == 'No') selected @endif>No
                                                            </option>
                                                            <option value="N/A"
                                                                @if ($data->quality_agreement_availability == 'N/A') selected @endif>N/A
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Risk Assessment Done?</label>
                                                        <select id="risk_assessment_done" name="risk_assessment_done">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->risk_assessment_done == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->risk_assessment_done == 'No') selected @endif>No
                                                            </option>
                                                            <option value="N/A"
                                                                @if ($data->risk_assessment_done == 'N/A') selected @endif>N/A
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Risk Rating</label>
                                                        <select id="risk_rating" name="risk_rating">
                                                            <option value="">---- Select ----</option>
                                                            <option value="High"
                                                                @if ($data->risk_rating == 'High') selected @endif>High
                                                            </option>
                                                            <option value="Medium"
                                                                @if ($data->risk_rating == 'Medium') selected @endif>Medium
                                                            </option>
                                                            <option value="Low"
                                                                @if ($data->risk_rating == 'Low') selected @endif>Low
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Manufacturer Audit
                                                            planned</label>
                                                        <select id="manufacturer_audit_planned"
                                                            name="manufacturer_audit_planned">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Yes"
                                                                @if ($data->manufacturer_audit_planned == 'Yes') selected @endif>Yes
                                                            </option>
                                                            <option value="No"
                                                                @if ($data->manufacturer_audit_planned == 'No') selected @endif>No
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Maufacturer Audit Conducted
                                                            On</label>
                                                        <div class="relative-container">
                                                            <input type="text" class="mic-input"
                                                                id="manufacturer_audit_conducted"
                                                                name="manufacturer_audit_conducted"
                                                                value="{{ $data->manufacturer_audit_conducted }}">
                                                            @component('frontend.forms.language-model')
                                                            @endcomponent
                                                        </div>

                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="group-input">
                                                        <label for="Initiator Group Code">Manufacturer Can be? </label>
                                                        <select id="manufacturer_can_be" name="manufacturer_can_be">
                                                            <option value="">---- Select ----</option>
                                                            <option value="Approved"
                                                                @if ($data->manufacturer_can_be == 'Approved') selected @endif>
                                                                Approved</option>
                                                            <option value="Not Approved"
                                                                @if ($data->manufacturer_can_be == 'Not Approved') selected @endif>Not
                                                                Approved</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="button-block mt-4">
                            <button type="submit" class="saveButton">Save</button>
                            <!-- <button type="button" class="backButton" onclick="previousStep()">Back</button> -->
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white"> Exit
                                </a> </button>
                        </div>
                    </div>
                </div>


                <!-- HOD Review content -->
                <div id="CCForm2" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input relative-container">
                                    <label for="HOD_feedback">HOD Feedback</label>
                                    <textarea class="tiny" type="text" name="HOD_feedback"value="{{ $data->HOD_feedback }}"
                                        placeholder="Enter HOD Feedback" id="HOD_feedback">{{ $data->HOD_feedback }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input relative-container">
                                    <label for="HOD_comment">HOD Comments</label>
                                    <textarea class="tiny" type="text" name="HOD_comment" value="{{ $data->HOD_comment }}"
                                        placeholder="Enter HOD Comment" id="HOD_comment">{{ $data->HOD_comment }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>


                                            @if ($data->HOD_attachment)
                                                    @foreach (json_decode($data->HOD_attachment) as $file)
                                                        <input id="4-{{ $loop->index }}" type="hidden"
                                                        name="existing_attach_files_d[{{ $loop->index }}]"
                                                        value="{{ $file }}">
                                                    @endforeach
                                                @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="HOD_attachment">HOD Attachments</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="HOD_attachment">
                                                        @if ($data->HOD_attachment)
                                                            @foreach (json_decode($data->HOD_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="4-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            type="file" id="myfile" name="HOD_attachment[]"
                                                            oninput="addMultipleFiles(this, 'HOD_attachment')" multiple>
                                                    </div>
                                                </div>

                                </div>
                            </div>


                            @if ($data->hod_additional_attachment)
                                @foreach (json_decode($data->hod_additional_attachment) as $file)
                                    <input id="5-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_e[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="hod_additional_attachment">HOD Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="hod_additional_attachment">
                                                        @if ($data->hod_additional_attachment)
                                                            @foreach (json_decode($data->hod_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="5-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="hod_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'hod_additional_attachment')" multiple>
                                                    </div>
                                                </div>

                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- Supplier Details content -->
                <div id="CCForm3" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <!-- <div class="col-12">
                                                    <div class="group-input">
                                                        <label for="Issues">
                                                            Certifications & Accreditation<button type="button" name="ann" id="certificationData">+</button>
                                                        </label>
                                                        <table class="table table-bordered" id="certificationDataTable">
                                                            <thead>
                                                                <tr>
                                                                    <th>Row #</th>
                                                                    <th>Type</th>
                                                                    <th>Issuing Agancy</th>
                                                                    <th>Issue Date</th>
                                                                    <th>Expiry Date</th>
                                                                    <th>Supporting Document</th>
                                                                    <th>Remarks</th>
                                                                    <th>Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @if ($certificationData && is_array($certificationData))
    @foreach ($certificationData as $gridData)
    <tr>
                                                                            <td>
                                                                                <input disabled type="text" name="certificationData[{{ $loop->index }}][serial]"
                                                                                    value="{{ $loop->index + 1 }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="type" type="text" name="certificationData[{{ $loop->index }}][type]"
                                                                                    value="{{ isset($gridData['type']) ? $gridData['type'] : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="issuingAgency" type="text" name="certificationData[{{ $loop->index }}][issuingAgency]"
                                                                                    value="{{ isset($gridData['issuingAgency']) ? $gridData['issuingAgency'] : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="issueDate" type="date"
                                                                                    name="certificationData[{{ $loop->index }}][issueDate]"
                                                                                    value="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="expiryDate" type="date"
                                                                                    name="certificationData[{{ $loop->index }}][expiryDate]"
                                                                                    value="{{ isset($gridData['expiryDate']) ? $gridData['expiryDate'] : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="supportingDoc" type="text"
                                                                                    name="certificationData[{{ $loop->index }}][supportingDoc]"
                                                                                    value="{{ isset($gridData['supportingDoc']) ? $gridData['supportingDoc'] : '' }}">
                                                                            </td>
                                                                            <td>
                                                                                <input class="remarks" type="text"
                                                                                    name="certificationData[{{ $loop->index }}][remarks]"
                                                                                    value="{{ isset($gridData['remarks']) ? $gridData['remarks'] : '' }}">
                                                                            </td>
                                                                            <td><button type="text" class="removeRowBtn">Remove</button></td>
                                                                        </tr>
    @endforeach
@else
    <td><input type="text" name="certificationData[0][serial]" value="1" readonly></td>
                                                                    <td><input type="text" name="certificationData[0][type]"></td>
                                                                    <td><input type="text" name="certificationData[0][issuingAgency]"></td>
                                                                    <td><input type="date" name="certificationData[0][issueDate]"></td>
                                                                    <td><input type="date" name="certificationData[0][expiryDate]"></td>
                                                                    <td><input type="text" name="certificationData[0][supportingDoc]"></td>
                                                                    <td><input type="text" name="certificationData[0][remarks]"></td>
                                                                    <td><button type="text" class="removeRowBtn">Remove</button></td>
    @endif
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div> -->

                            <div class="col-12">
                                <div class="group-input">
                                    <label for="Issues">
                                        Certifications & Accreditation
                                        <button type="button" name="ann" id="certificationData">+</button>
                                    </label>
                                    <table class="table table-bordered" id="certificationDataTable">
                                        <thead>
                                            <tr>
                                                <th style="width: 60px;">Row #</th>
                                                <th>Type</th>
                                                <th>Issuing Agency</th>
                                                <th>Issue Date</th>
                                                <th>Expiry Date</th>
                                                <th>Supporting Document</th>
                                                <th>Remarks</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($certificationData && is_array($certificationData))
                                                @foreach ($certificationData as $gridData)
                                                    <tr>
                                                        <td style="width: 60px;">
                                                            <input disabled type="text"
                                                                name="certificationData[{{ $loop->index }}][serial]"
                                                                value="{{ $loop->index + 1 }}">
                                                        </td>
                                                        <td>
                                                            <input class="type" type="text"
                                                                name="certificationData[{{ $loop->index }}][type]"
                                                                value="{{ isset($gridData['type']) ? $gridData['type'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issuingAgency" type="text"
                                                                name="certificationData[{{ $loop->index }}][issuingAgency]"
                                                                value="{{ isset($gridData['issuingAgency']) ? $gridData['issuingAgency'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="issueDate" type="date"
                                                                name="certificationData[{{ $loop->index }}][issueDate]"
                                                                value="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}"
                                                                max="{{ date('Y-m-d') }}">
                                                        </td>
                                                        <td>
                                                            <input class="expiryDate" type="date"
                                                                name="certificationData[{{ $loop->index }}][expiryDate]"
                                                                value="{{ isset($gridData['expiryDate']) ? $gridData['expiryDate'] : '' }}"
                                                                min="{{ isset($gridData['issueDate']) ? $gridData['issueDate'] : '' }}"
                                                                {{ isset($gridData['issueDate']) ? '' : 'disabled' }}>
                                                        </td>
                                                        <td>
                                                            <input class="supportingDoc" type="text"
                                                                name="certificationData[{{ $loop->index }}][supportingDoc]"
                                                                value="{{ isset($gridData['supportingDoc']) ? $gridData['supportingDoc'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <input class="remarks" type="text"
                                                                name="certificationData[{{ $loop->index }}][remarks]"
                                                                value="{{ isset($gridData['remarks']) ? $gridData['remarks'] : '' }}">
                                                        </td>
                                                        <td>
                                                            <button type="button" class="removeRowBtn">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @else
                                                <tr>
                                                    <td style="width: 60px;"><input type="text"
                                                            name="certificationData[0][serial]" value="1" readonly>
                                                    </td>
                                                    <td><input type="text" name="certificationData[0][type]"></td>
                                                    <td><input type="text"
                                                            name="certificationData[0][issuingAgency]"></td>
                                                    <td><input type="date" name="certificationData[0][issueDate]"
                                                            class="issueDate" max="{{ date('Y-m-d') }}"></td>
                                                    <td><input type="date" name="certificationData[0][expiryDate]"
                                                            class="expiryDate" disabled></td>
                                                    <td><input type="text"
                                                            name="certificationData[0][supportingDoc]"></td>
                                                    <td><input type="text" name="certificationData[0][remarks]"></td>
                                                    <td><button type="button" class="removeRowBtn">Remove</button></td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier Name</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="supplier_name"
                                            value="{{ $data->supplier_name }}" id="supplier_name"
                                            placeholder="Enter Supplier Name">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier.">Supplier ID</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="supplier_id"
                                            value="{{ $data->supplier_id }}" placeholder="Enter Supplier ID">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Manufacturer Name</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="manufacturer_name"
                                            value="{{ $data->manufacturer_name }}"
                                            placeholder="Enter Manufacturer Name">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Manufacturer ID</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="manufacturer_id"
                                            value="{{ $data->manufacturer_id }}" placeholder="Enter Manufacturer ID">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="">Vendor Name</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="vendor_name"
                                            value="{{ $data->vendor_name }}" placeholder="Enter Vendor Name">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="manufacturer">Vendor ID</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="vendor_id"
                                            value="{{ $data->vendor_id }}" placeholder="Enter Vendor ID">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contact Person">Contact Person</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="contact_person"
                                            value="{{ $data->contact_person }}" id="contact_person"
                                            placeholder="Enter Contact Person">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="group-input">
                                    <label for="Other Contacts">Other Contacts</label>
                                    <div class="relative-container">
                                        <input id="other_contacts" class="mic-input" type="text"
                                            name="other_contacts" value="{{ $data->other_contacts }}">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Supplier Services">Supplier Services</label>
                                    <textarea class="tiny" name="supplier_serivce" value="{{ $data->supplier_serivce }}" id="supplier_serivce"
                                        cols="30">{{ $data->supplier_serivce }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Zone">Zone</label>
                                    <select name="zone">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Asia" @if ($data->zone == 'Asia') selected @endif>Asia
                                        </option>
                                        <option value="Europe" @if ($data->zone == 'Europe') selected @endif>Europe
                                        </option>
                                        <option value="Africa" @if ($data->zone == 'Africa') selected @endif>
                                            Africa</option>
                                        <option value="Central America"
                                            @if ($data->zone == 'Central America') selected @endif>Central America</option>
                                        <option value="South America"
                                            @if ($data->zone == 'South America') selected @endif>South America</option>
                                        <option value="Oceania" @if ($data->zone == 'Oceania') selected @endif>
                                            Oceania</option>
                                        <option value="North America"
                                            @if ($data->zone == 'North America') selected @endif>North America</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Country">Country</label>
                                    <select name="country" class="form-select country"
                                        aria-label="Default select example" onchange="loadStates()">
                                        <option value="">Select Country</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="State">State</label>
                                    <select name="state" class="form-select state"
                                        aria-label="Default select example" onchange="loadCities()" disabled>
                                        <option value="">Select State</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="City">City</label>
                                    <select name="city" class="form-select city"
                                        aria-label="Default select example" disabled>
                                        <option value="">Select City</option>
                                    </select>
                                </div>
                            </div>


                            <script>
                                var config = {
                                    cUrl: 'https://api.countrystatecity.in/v1',
                                    ckey: 'NHhvOEcyWk50N2Vna3VFTE00bFp3MjFKR0ZEOUhkZlg4RTk1MlJlaA=='
                                };

                                var countrySelect = document.querySelector('.country'),
                                    stateSelect = document.querySelector('.state'),
                                    citySelect = document.querySelector('.city');

                                var selectedCountry = "{{ $data->country }}";
                                var selectedState = "{{ $data->state }}";
                                var selectedCity = "{{ $data->city }}";

                                function loadCountries() {
                                    let apiEndPoint = `${config.cUrl}/countries`;

                                    $.ajax({
                                        url: apiEndPoint,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(country => {
                                                const option = document.createElement('option');
                                                option.value = country.name; // Store the name in the option value
                                                option.textContent = country.name; // Display the name
                                                option.dataset.code = country.iso2; // Store the code in a data attribute if needed
                                                if (country.name === selectedCountry) {
                                                    option.selected = true;
                                                }
                                                countrySelect.appendChild(option);
                                            });
                                            loadStates(); // Load states after countries are populated
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading countries:', error);
                                        }
                                    });
                                }

                                function loadStates() {
                                    stateSelect.disabled = false;
                                    stateSelect.innerHTML = '<option value="">Select State</option>';

                                    const selectedCountryCode = countrySelect.options[countrySelect.selectedIndex].dataset.code;

                                    $.ajax({
                                        url: `${config.cUrl}/countries/${selectedCountryCode}/states`,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(state => {
                                                const option = document.createElement('option');
                                                option.value = state.name; // Store the name in the option value
                                                option.textContent = state.name; // Display the name
                                                option.dataset.code = state.iso2; // Store the code in a data attribute if needed
                                                if (state.name === selectedState) {
                                                    option.selected = true;
                                                }
                                                stateSelect.appendChild(option);
                                            });
                                            loadCities(); // Load cities after states are populated
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading states:', error);
                                        }
                                    });
                                }

                                function loadCities() {
                                    citySelect.disabled = false;
                                    citySelect.innerHTML = '<option value="">Select City</option>';

                                    const selectedCountryCode = countrySelect.options[countrySelect.selectedIndex].dataset.code;
                                    const selectedStateCode = stateSelect.options[stateSelect.selectedIndex].dataset.code;

                                    $.ajax({
                                        url: `${config.cUrl}/countries/${selectedCountryCode}/states/${selectedStateCode}/cities`,
                                        headers: {
                                            "X-CSCAPI-KEY": config.ckey
                                        },
                                        success: function(data) {
                                            data.forEach(city => {
                                                const option = document.createElement('option');
                                                option.value = city.name; // Store the name in the option value
                                                option.textContent = city.name; // Display the name
                                                if (city.name === selectedCity) {
                                                    option.selected = true;
                                                }
                                                citySelect.appendChild(option);
                                            });
                                        },
                                        error: function(xhr, status, error) {
                                            console.error('Error loading cities:', error);
                                        }
                                    });
                                }

                                $(document).ready(function() {
                                    loadCountries();
                                });
                            </script>


                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Address">Address</label>
                                    <div class="relative-container">
                                        <textarea type="text" class="mic-input" value="{{ $data->address }}" name="address" class="tiny" id="address">{{ $data->address }}</textarea>
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Web Site">Supplier Web Site</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="suppplier_web_site"
                                            value="{{ $data->suppplier_web_site }}" placeholder="Enter Website ">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="ISO Certification date">ISO Certification Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="iso_certified_date" readonly
                                            placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->iso_certified_date) }}" />
                                        <input type="date" name="iso_certified_date"
                                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                            value="{{ $data->iso_certified_date }}" class="hide-input"
                                            oninput="handleDateInput(this, 'iso_certified_date')" />
                                    </div>
                                </div>
                            </div>


                            @if ($data->iso_certificate_attachment)
                                @foreach (json_decode($data->iso_certificate_attachment) as $file)
                                    <input id="6-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_f[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="iso_certificate_attachment">ISO Ceritificate Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="iso_certificate_attachment">
                                                        @if ($data->iso_certificate_attachment)
                                                            @foreach (json_decode($data->iso_certificate_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="6-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="iso_certificate_attachment[]"
                                                            oninput="addMultipleFiles(this, 'iso_certificate_attachment')" multiple>
                                                    </div>
                                                </div>

                                </div>

                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Contracts">Contracts</label>
                                    <div class="relative-container">
                                        <input type="text" name="suppplier_contacts"
                                            value="{{ $data->suppplier_contacts }}" id="suppplier_contacts">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Related Non Conformances">Related Non Conformances</label>
                                    <div class="relative-container">
                                        <input type="text" class="mic-input" name="related_non_conformance"
                                            value="{{ $data->related_non_conformance }}" id="related_non_conformance">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Contracts/Agreements">Supplier Contracts/Agreements</label>
                                    <div class="relative-container">
                                        <input type="text" id="suppplier_agreement" name="suppplier_agreement"
                                            value="{{ $data->suppplier_agreement }}">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Regulatory History">Regulatory History</label>
                                    <div class="relative-container">
                                        <input type="text" id="regulatory_history" name="regulatory_history"
                                            value="{{ $data->regulatory_history }}">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Distribution Sites">Distribution Sites</label>
                                    <div class="relative-container">
                                        <input type="text" id="distribution_sites" name="distribution_sites"
                                            value="{{ $data->distribution_sites }}" maxlength="50">
                                        @component('frontend.forms.language-model')
                                        @endcomponent
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Quality Management ">Manufacturing Sites </label>
                                    <textarea class="tiny" id="manufacturing_sited" type="text" name="manufacturing_sited"
                                        value="{{ $data->manufacturing_sited }}">{{ $data->manufacturing_sited }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Quality Management ">Quality Management </label>
                                    <textarea class="tiny" id="quality_management" type="text" name="quality_management"
                                        value="{{ $data->quality_management }}">{{ $data->quality_management }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Business History">Business History</label>
                                    <textarea class="tiny" id="bussiness_history" type="text" name="bussiness_history"
                                        value="{{ $data->bussiness_history }}">{{ $data->bussiness_history }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Performance History ">Performance History </label>
                                    <textarea class="tiny" id="performance_history" type="text" name="performance_history"
                                        value="{{ $data->performance_history }}">{{ $data->performance_history }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="group-input relative-container">
                                    <label for="Compliance Risk">Compliance Risk</label>
                                    <textarea class="tiny" id="compliance_risk" type="text" name="compliance_risk"
                                        value="{{ $data->compliance_risk }}">{{ $data->compliance_risk }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>


                            @if ($data->supplier_detail_additional_attachment)
                                @foreach (json_decode($data->supplier_detail_additional_attachment) as $file)
                                    <input id="7-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_g[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="supplier_detail_additional_attachment">Supplier Additional
                                        Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="supplier_detail_additional_attachment">
                                                        @if ($data->supplier_detail_additional_attachment)
                                                            @foreach (json_decode($data->supplier_detail_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="7-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="supplier_detail_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'supplier_detail_additional_attachment')" multiple>
                                                    </div>
                                                </div>

                                </div>

                                <!-- </div> -->
                            <!-- </div> -->
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- score card content -->
                <div id="CCForm4" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction">Cost Reduction</label>
                                    <select id="cost_reduction" name="cost_reduction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Unacceptable"
                                            @if ($data->cost_reduction == 'Unacceptable') selected @endif>Unacceptable</option>
                                        <option value="Does Not Meet Expectation"
                                            @if ($data->cost_reduction == 'Does Not Meet Expectation') selected @endif>Does Not Meet Expectation
                                        </option>
                                        <option value="Meets Expectations"
                                            @if ($data->cost_reduction == 'Meets Expectations') selected @endif>Meets Expectations
                                        </option>
                                        <option value="Exceeds Expectations"
                                            @if ($data->cost_reduction == 'Exceeds Expectations') selected @endif>Exceeds Expectations
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Cost Reduction Weight">Cost Reduction Weight</label>
                                    <select id="cost_reduction_weight" name="cost_reduction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->cost_reduction_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms">Payment Terms</label>
                                    <select id="payment_term" name="payment_term">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 30 Days" @if ($data->payment_term == '< 30 Days') selected @endif>
                                            < 30 Days</option>
                                        <option value="30 - 45 Days"
                                            @if ($data->payment_term == '30 - 45 Days') selected @endif>30 - 45 Days</option>
                                        <option value="45 - 60 Days"
                                            @if ($data->payment_term == '45 - 60 Days') selected @endif>45 - 60 Days</option>
                                        <option value=">= 60 Days" @if ($data->payment_term == '>= 60 Days')
                                            selected
                                            @endif>>= 60 Days</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Payment Terms Weight">Payment Terms Weight</label>
                                    <select name="payment_term_weight" id="payment_term_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->payment_term_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days">Lead Time Days</label>
                                    <select name="lead_time_days" name="lead_time_days">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 11 Days" @if ($data->lead_time_days == '> 11 Days')
                                            selected
                                            @endif> > 11 Days</option>
                                        <option value="6 - 10" @if ($data->lead_time_days == '6 - 10') selected @endif>6 -
                                            10</option>
                                        <option value="3 -5" @if ($data->lead_time_days == '3 -5') selected @endif>3 -5
                                        </option>
                                        <option value="1 Day or Consignment"
                                            @if ($data->lead_time_days == '1 Day or Consignment') selected @endif>1 Day or Consignment
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Lead Time Days Weight">Lead Time Days Weight</label>
                                    <select name="lead_time_days_weight" id="lead_time_days_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->lead_time_days_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery">On-Time Delivery</label>
                                    <select id="ontime_delivery" name="ontime_delivery">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="100%" @if ($data->ontime_delivery == '100%') selected @endif>100%
                                        </option>
                                        <option value="98-99%" @if ($data->ontime_delivery == '98-99%') selected @endif>
                                            98-99%</option>
                                        <option value="96-97%" @if ($data->ontime_delivery == '96-97%') selected @endif>
                                            96-97%</option>
                                        <option value="< 95%" @if ($data->ontime_delivery == '< 95%') selected @endif>
                                            < 95%</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="On-Time Delivery Weight">On-Time Delivery Weight</label>
                                    <select id="ontime_delivery_weight" name="ontime_delivery_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->ontime_delivery_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Planning">Supplier Business Planning</label>
                                    <select id="supplier_bussiness_planning" name="supplier_bussiness_planning">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Not Information at All"
                                            @if ($data->supplier_bussiness_planning == 'Not Information at All') selected @endif>Not Information at All
                                        </option>
                                        <option value="No Formal Information About"
                                            @if ($data->supplier_bussiness_planning == 'No Formal Information About') selected @endif>No Formal Information
                                            About</option>
                                        <option value="Yes - Partially Aligned With"
                                            @if ($data->supplier_bussiness_planning == 'Yes - Partially Aligned With') selected @endif>Yes - Partially Aligned
                                            With</option>
                                        <option value="Yes - Completely Aligns"
                                            @if ($data->supplier_bussiness_planning == 'Yes - Completely Aligns') selected @endif>Yes - Completely Aligns
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Supplier Business Weight">Supplier Business Weight</label>
                                    <select id="supplier_bussiness_planning_weight"
                                        name="supplier_bussiness_planning_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->supplier_bussiness_planning_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM">Rejection in PPM</label>
                                    <select id="rejection_ppm" name="rejection_ppm">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 500001 Defects PPM" @if ($data->rejection_ppm == '> 500001 Defects PPM')
                                            selected
                                            @endif>> 500001 Defects PPM</option>
                                        <option value="5001 - 50000 Defects PPM"
                                            @if ($data->rejection_ppm == '5001 - 50000 Defects PPM') selected @endif>5001 - 50000 Defects PPM
                                        </option>
                                        <option value="501 - 500 Defects PPM"
                                            @if ($data->rejection_ppm == '501 - 500 Defects PPM') selected @endif>501 - 5000 Defects PPM
                                        </option>
                                        <option value="Upto 500 Defects PPM"
                                            @if ($data->rejection_ppm == 'Upto 500 Defects PPM') selected @endif>Upto 500 Defects PPM"
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Rejection in PPM Weight">Rejection in PPM Weight</label>
                                    <select id="rejection_ppm_weight" name="rejection_ppm_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->rejection_ppm_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems">Quality Systems</label>
                                    <select id="quality_system" name="quality_system">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="No System/No Team"
                                            @if ($data->quality_system == 'No System/No Team') selected @endif>No System/No Team</option>
                                        <option value="System Not Certified"
                                            @if ($data->quality_system == 'System Not Certified') selected @endif>System Not Certified
                                        </option>
                                        <option value="ISO 9000 Cert"
                                            @if ($data->quality_system == 'ISO 9000 Cert') selected @endif>ISO 9000 Cert</option>
                                        <option value="ISO 9000 & 1400 Cert"
                                            @if ($data->quality_system == 'ISO 9000 & 1400 Cert') selected @endif>ISO 9000 & 1400 Cert
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Quality Systems Weight">Quality Systems Weight</label>
                                    <select id="quality_system_ranking" name="quality_system_ranking">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->quality_system_ranking == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated"># of CAR's generated</label>
                                    <select id="car_generated" name="car_generated">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="0" @if ($data->car_generated == '0') selected @endif>0
                                        </option>
                                        <option value="> 8" @if ($data->car_generated == '> 8')
                                            selected
                                            @endif>> 8</option>
                                        <option value="2-7" @if ($data->car_generated == '2-7') selected @endif>2-7
                                        </option>
                                        <option value="0-1" @if ($data->car_generated == '0-1') selected @endif>0-1
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="# of CAR's generated Weight"># of CAR's generated Weight</label>
                                    <select id="car_generated_weight" name="car_generated_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->car_generated_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time">CAR Closure Time</label>
                                    <select id="closure_time" name="closure_time">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="> 60" @if ($data->closure_time == '> 60')
                                            selected
                                            @endif>> 60</option>
                                        <option value="30-60" @if ($data->closure_time == '30-60') selected @endif>30-60
                                        </option>
                                        <option value="15-30" @if ($data->closure_time == '15-30') selected @endif>15-30
                                        </option>
                                        <option value="0-15" @if ($data->closure_time == '0-15') selected @endif>0-15
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="CAR Closure Time Weight">CAR Closure Time Weight</label>
                                    <select id="closure_time_weight" name="closure_time_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->closure_time_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction">End-User Satisfaction</label>
                                    <select id="end_user_satisfaction" name="end_user_satisfaction">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Information Lacks"
                                            @if ($data->end_user_satisfaction == 'Information Lacks') selected @endif>Information Lacks</option>
                                        <option value="Not Reactive Enough"
                                            @if ($data->end_user_satisfaction == 'Not Reactive Enough') selected @endif>Not Reactive Enough
                                        </option>
                                        <option value="Required" @if ($data->end_user_satisfaction == 'Required') selected @endif>
                                            Required</option>
                                        <option value="Active Participation"
                                            @if ($data->end_user_satisfaction == 'Active Participation') selected @endif>Active Participation
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="End-User Satisfaction Weight">End-User Satisfaction Weight</label>
                                    <select id="end_user_satisfaction_weight" name="end_user_satisfaction_weight">
                                        <option value="">Enter Your Selection Here</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}"
                                                @if ($data->end_user_satisfaction_weight == $i) selected @endif>{{ $i }}
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                            </div>



                            @if ($data->score_card_additional_attachment)
                                @foreach (json_decode($data->score_card_additional_attachment) as $file)
                                    <input id="8-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_h[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="score_card_additional_attachment">Score Card Additional Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>                             
                                     <div class="file-attachment-field">
                                        <div class="file-attachment-list" id="score_card_additional_attachment">
                                                        @if ($data->score_card_additional_attachment)
                                                            @foreach (json_decode($data->score_card_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="8-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="score_card_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'score_card_additional_attachment')" multiple>
                                                    </div>
                                                </div>

                                <!-- </div> -->
                                </div>
                            </div>
                            {{-- <div class="col-12 sub-head">
                                Total Score
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Scorecard Record">Scorecard Record</label>
                                    <input type="text" name="scorecard_record" id="scorecard_record" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Achived Score">Achived Score</label>
                                    <input type="text" name="achieved_score" id="achieved_score" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Total Available Score</label>
                                    <input type="text" name="total_available_score" id="total_available_score" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Score">Total Score</label>
                                    <input type="text" name="total_score"  id="total_score" readonly>
                                </div>
                            </div> --}}
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- QA Reviewer content -->
                <div id="CCForm5" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input relative-container">
                                    <label for="QA_reviewer_feedback">QA Reviewer Feedback</label>
                                    <textarea class="tiny" type="text" name="QA_reviewer_feedback" placeholder="Enter QA Reviewer Feedback"
                                        id="QA_reviewer_feedback">{{ $data->QA_reviewer_feedback }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="group-input relative-container">
                                    <label for="QA_reviewer_comment">QA Reviewer Comment</label>
                                    <textarea class="tiny" type="text" name="QA_reviewer_comment" placeholder="Enter QA Reviewer Comment"
                                        id="QA_reviewer_comment">{{ $data->QA_reviewer_comment }}</textarea>
                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>


                            @if ($data->QA_reviewer_attachment)
                                @foreach (json_decode($data->QA_reviewer_attachment) as $file)
                                    <input id="9-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_i[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_reviewer_attachment">QA Reviewer Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                            <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="QA_reviewer_attachment">
                                                        @if ($data->QA_reviewer_attachment)
                                                            @foreach (json_decode($data->QA_reviewer_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="9-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="QA_reviewer_attachment[]"
                                                            oninput="addMultipleFiles(this, 'QA_reviewer_attachment')" multiple>
                                                    </div>
                                                </div>
                                </div>
                            </div>


                            @if ($data->qa_reviewer_additional_attachment)
                                @foreach (json_decode($data->qa_reviewer_additional_attachment) as $file)
                                    <input id="10-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_j[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                                <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_reviewer_additional_attachment">QA Reviewer Additional
                                        Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                            <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="qa_reviewer_additional_attachment">
                                                        @if ($data->qa_reviewer_additional_attachment)
                                                            @foreach (json_decode($data->qa_reviewer_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="10-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="qa_reviewer_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'qa_reviewer_additional_attachment')" multiple>
                                                    </div>
                                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- Risk Assessment Content -->
                <div id="CCForm6" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Last Audit Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="last_audit_date" placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->last_audit_date) }}" />
                                        <input type="date" name="last_audit_date"
                                            value="{{ $data->last_audit_date }}"
                                            max="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'last_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 new-date-data-field">
                                <div class="group-input input-date">
                                    <label for="Last Audit Date">Next Audit Date</label>
                                    <div class="calenderauditee">
                                        <input type="text" id="next_audit_date" placeholder="DD-MMM-YYYY"
                                            value="{{ Helpers::getdateFormat($data->next_audit_date) }}" />
                                        <input type="date" name="next_audit_date"
                                            value="{{ $data->next_audit_date }}"
                                            min="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="hide-input"
                                            oninput="handleDateInput(this, 'next_audit_date')" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Audit Frequency">Audit Frequency</label>
                                    <select id="audit_frequency" name="audit_frequency">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Every 10 Years"
                                            @if ($data->audit_frequency == 'Every 10 Years') selected @endif>Every 10 Years</option>
                                        <option value="Every 9 Years"
                                            @if ($data->audit_frequency == 'Every 9 Years') selected @endif>Every 9 Years</option>
                                        <option value="Every 8 Years"
                                            @if ($data->audit_frequency == 'Every 8 Years') selected @endif>Every 8 Years</option>
                                        <option value="Every 7 Years"
                                            @if ($data->audit_frequency == 'Every 7 Years') selected @endif>Every 7 Years</option>
                                        <option value="Every 6 Years"
                                            @if ($data->audit_frequency == 'Every 6 Years') selected @endif>Every 6 Years</option>
                                        <option value="Every 5 Years"
                                            @if ($data->audit_frequency == 'Every 5 Years') selected @endif>Every 5 Years</option>
                                        <option value="Every 4 Years"
                                            @if ($data->audit_frequency == 'Every 4 Years') selected @endif>Every 4 Years</option>
                                        <option value="Every 3 Years"
                                            @if ($data->audit_frequency == 'Every 3 Years') selected @endif>Every 3 Years</option>
                                        <option value="Every 2 Years"
                                            @if ($data->audit_frequency == 'Every 2 Years') selected @endif>Every 2 Years</option>
                                        <option value="Annual" @if ($data->audit_frequency == 'Annual') selected @endif>
                                            Annual</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Last Audit Result">Last Audit Result</label>
                                    <select id="last_audit_result" name="last_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="5" @if ($data->last_audit_result == '5') selected @endif>5
                                        </option>
                                        <option value="4" @if ($data->last_audit_result == '4') selected @endif>4
                                        </option>
                                        <option value="3" @if ($data->last_audit_result == '3') selected @endif>3
                                        </option>
                                        <option value="2" @if ($data->last_audit_result == '2') selected @endif>2
                                        </option>
                                        <option value="1" @if ($data->last_audit_result == '1') selected @endif>1
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-12 sub-head">
                                Risk Factors
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Facility Type">Facility Type</label>
                                    <select id="facility_type" name="facility_type">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Operation, R&M - Level 3"
                                            @if ($data->facility_type == 'Operation, R&M - Level 3') selected @endif>Operation, R&M - Level 3
                                        </option>
                                        <option value="Operation, R&M - Level 2"
                                            @if ($data->facility_type == 'Operation, R&M - Level 2') selected @endif>Operation, R&M - Level 2
                                        </option>
                                        <option value="Operation Only, Stock Point Only"
                                            @if ($data->facility_type == 'Operation Only, Stock Point Only') selected @endif>Operation Only, Stock
                                            Point Only</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Number of Employees">Number of Employees</label>
                                    <select id="nature_of_employee" name="nature_of_employee">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="< 25" @if ($data->nature_of_employee == '< 25') selected @endif>
                                            < 25 </option>
                                        <option value="26-49" @if ($data->nature_of_employee == '26-49') selected @endif>26-49
                                        </option>
                                        <option value=">50" @if ($data->nature_of_employee == '>50')
                                            selected
                                            @endif>>50</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Access to Technical Support">Access to Technical Support</label>
                                    <select id="technical_support" name="technical_support">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Very Limited Access to Technical Experts"
                                            @if ($data->technical_support == 'Very Limited Access to Technical Experts') selected @endif>Very Limited Access to
                                            Technical Experts</option>
                                        <option value="Available When Requested or Via Beacon Center"
                                            @if ($data->technical_support == 'Available When Requested or Via Beacon Center') selected @endif>Available When Requested
                                            or Via Beacon Center</option>
                                        <option value="Regulatory Schedule Visit by Region Experts"
                                            @if ($data->technical_support == 'Regulatory Schedule Visit by Region Experts') selected @endif>Regulatory Schedule Visit
                                            by Region Experts</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Services Supported">Services Supported</label>
                                    <select name="survice_supported" id="survice_supported">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Integrated, Multi-Combo Jobs"
                                            @if ($data->survice_supported == 'Integrated, Multi-Combo Jobs') selected @endif>Integrated, Multi-Combo
                                            Jobs</option>
                                        <option value="Basic D&E Services"
                                            @if ($data->survice_supported == 'Basic D&E Services') selected @endif>Basic D&E Services
                                        </option>
                                        <option value="Motors or Standalone MWD"
                                            @if ($data->survice_supported == 'Motors or Standalone MWD') selected @endif>Motors or Standalone MWD
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Reliability">Reliability</label>
                                    <select id="reliability" name="reliability">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Significantly Below Expectations"
                                            @if ($data->reliability == 'Significantly Below Expectations') selected @endif>Significantly Below
                                            Expectations</option>
                                        <option value="Marginally Below Expectations"
                                            @if ($data->reliability == 'Marginally Below Expectations') selected @endif>Marginally Below
                                            Expectations</option>
                                        <option value="Meets Expectations"
                                            @if ($data->reliability == 'Meets Expectations') selected @endif>Meets Expectations
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Revenue">Revenue</label>
                                    <select name="revenue" id="revenue">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value=">50 M" @if ($data->revenue == '>50 M')
                                            selected
                                            @endif>>50 M</option>
                                        <option value="26-49 M" @if ($data->revenue == '26-49 M') selected @endif>
                                            26-49 M</option>
                                        <option value="< 25 M" @if ($data->revenue == '< 25 M') selected @endif>
                                            < 25 M</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Client Base">Client Base</label>
                                    <select id="client_base" name="client_base">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Single or Disproportionally Skewed"
                                            @if ($data->client_base == 'Single or Disproportionally Skewed') selected @endif>Single or
                                            Disproportionally Skewed</option>
                                        <option value="Multiple Clients"
                                            @if ($data->client_base == 'Multiple Clients') selected @endif>Multiple Clients</option>
                                        <option value="Well Diversified"
                                            @if ($data->client_base == 'Well Diversified') selected @endif>Well Diversified</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Previous Audit Results">Previous Audit Results</label>
                                    <select id="previous_audit_result" name="previous_audit_result">
                                        <option value="">Enter Your Selection Here</option>
                                        <option value="Below Requirement Major NCN's or No Audit History"
                                            @if ($data->previous_audit_result == "Below Requirement Major NCN's or No Audit History") selected @endif>Below Requirement Major
                                            NCN's or No Audit History</option>
                                        <option value="Marginally Below Requirement With Minor NCN's"
                                            @if ($data->previous_audit_result == "Marginally Below Requirement With Minor NCN's") selected @endif>Marginally Below
                                            Requirement With Minor NCN's</option>
                                        <option value="Meets Requirement and Minimal NCN's"
                                            @if ($data->previous_audit_result == "Meets Requirement and Minimal NCN's") selected @endif>Meets Requirement and
                                            Minimal NCN's</option>
                                    </select>
                                </div>
                            </div>


                            @if ($data->risk_assessment_additional_attachment)
                                @foreach (json_decode($data->risk_assessment_additional_attachment) as $file)
                                    <input id="1-{{ $loop->index }}" type="hidden"
                                    name="existing_attach_files_a[{{ $loop->index }}]"
                                    value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="risk_assessment_additional_attachment">Risk Assesment Additional
                                        Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                    <div class="file-attachment-field">
                                                    <div class="file-attachment-list" id="risk_assessment_additional_attachment">
                                                        @if ($data->risk_assessment_additional_attachment)
                                                            @foreach (json_decode($data->risk_assessment_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="1-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="risk_assessment_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'risk_assessment_additional_attachment')" multiple>
                                                    </div>
                                                </div>
                                </div>
                            </div>

                            {{-- <div class="sub-head">
                                Results
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Row Total</label>
                                    <input type="text" name="risk_raw_total" id="risk_raw_total" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Median</label>
                                    <input type="text" name="risk_median" id="risk_median" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Average</label>
                                    <input type="text" name="risk_average" id="risk_average" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="group-input">
                                    <label for="Total Available Score">Risk Assessment Total</label>
                                    <input type="text" name="risk_assessment_total" id="risk_assessment_total" readonly>
                                </div>
                            </div> --}}
                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <!-- QA Head content -->
                <div id="CCForm7" class="inner-block cctabcontent">
                    <div class="inner-block-content">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="group-input relative-container">
                                    <label for="QA_head_comment">QA Head Comment</label>
                                    <textarea class="tiny" type="text" name="QA_head_comment" value="{{ $data->QA_head_comment }}"
                                        placeholder="Enter QA Head Comment" id="QA_head_comment">{{ $data->QA_head_comment }}</textarea>

                                    @component('frontend.forms.language-model')
                                    @endcomponent
                                </div>
                            </div>

                            @if ($data->QA_head_attachment)
                                @foreach (json_decode($data->QA_head_attachment) as $file)
                                    <input id="11-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_k[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="QA_head_attachment">QA Head Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                            <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="QA_head_attachment">
                                                        @if ($data->QA_head_attachment)
                                                            @foreach (json_decode($data->QA_head_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="11-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="QA_head_attachment[]"
                                                            oninput="addMultipleFiles(this, 'QA_head_attachment')" multiple>
                                                    </div>
                                                </div>
                                </div>
                            </div>



                            @if ($data->qa_head_additional_attachment)
                                @foreach (json_decode($data->qa_head_additional_attachment) as $file)
                                    <input id="12-{{ $loop->index }}" type="hidden"
                                        name="existing_attach_files_l[{{ $loop->index }}]"
                                        value="{{ $file }}">
                                @endforeach
                            @endif
                            <div class="col-lg-12">
                                <div class="group-input">
                                    <label for="qa_head_additional_attachment">QA Head Reviewer Additional
                                        Attachment</label>
                                    <div><small class="text-primary">Please Attach all relevant or supporting
                                            documents</small></div>
                                            <div class="file-attachment-field">
                                                         <div class="file-attachment-list" id="qa_head_additional_attachment">
                                                        @if ($data->qa_head_additional_attachment)
                                                            @foreach (json_decode($data->qa_head_additional_attachment) as $file)
                                                                <h6 type="button" class="file-container text-dark"
                                                                    style="background-color: rgb(243, 242, 240);">
                                                                    <b>{{ $file }}</b>
                                                                    <a href="{{ asset('upload/' . $file) }}"
                                                                        target="_blank"><i class="fa fa-eye text-primary"
                                                                            style="font-size:20px; margin-right:-10px;"></i></a>
                                                                    <a type="button" class="remove-file"
                                                                        data-remove-id="12-{{ $loop->index }}"
                                                                        data-file-name="{{ $file }}"
                                                                        style="@if ($data->stage == 0 || $data->stage == 7) pointer-events: none; @endif"><i
                                                                            class="fa-solid fa-circle-xmark"
                                                                            style="color:red; font-size:20px;"></i></a>
                                                                </h6>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                    <div class="add-btn">
                                                        <div>Add</div>
                                                        <input
                                                            {{ $data->stage == 0 || $data->stage == 7 ? 'disabled' : '' }}
                                                            type="file" id="myfile" name="qa_head_additional_attachment[]"
                                                            oninput="addMultipleFiles(this, 'qa_head_additional_attachment')" multiple>
                                                    </div>
                                                </div>
                                </div>
                            </div>

                        </div>
                        <div class="button-block">
                            <button type="submit" class="saveButton">Save</button>
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button" class="nextButton" onclick="nextStep()">Next</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

                <div id="CCForm8" class="inner-block cctabcontent">
                    <div class="inner-block-content">

                    <div class="sub-head">Activity Log</div>

                    <div class="d-flex align-item-end justify-content-end">
                            <button style="margin-bottom:20px;" class="button_theme1"> <a
                                    class="text-white"
                                    href="{{ url('rcms/supplierActivityLog', $data->id) }}"> Print </a>
                            </button>
                    </div>


                    <div class="printable-content">
                        {{-- <div class="row">
                            <h5>
                                <strong>Submit By:</strong> {{ $data->submit_by }}
                                &nbsp;&nbsp;&nbsp;
                                <strong>Date of Initiation:</strong>
                            </h5>
                        </div> --}}

                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tbody>
                                    <tr>
                                        <td>
                                            <strong>Need for Sourcing of Starting Material By :</strong><br>
                                            {{ $data->submitted_by }}
                                        </td>
                                        <td>
                                            <strong>Need for Sourcing of Starting Material On :</strong><br>
                                            {{-- $data->submitted_on --}}

                                            @php
                                                $utcTime = $data->submitted_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Need for Sourcing of Starting Material Comment :</strong><br>
                                            {{ $data->submitted_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Approved by Contract Giver By :</strong><br>
                                            {{  $data->approvedBy_contract_giver_by  }}
                                        </td>
                                        <td>
                                            <strong>Approved by Contract Giver On:</strong><br>
                                            {{-- $data->approvedBy_contract_giver_on --}}

                                            @php
                                                $utcTime = $data->approvedBy_contract_giver_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Approved by Contract Giver Comment :</strong><br>
                                            {{ $data->approvedBy_contract_giver_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Request Justified By :</strong><br>
                                            {{ $data->request_justified_by }}
                                        </td>
                                        <td>
                                            <strong>Request Justified On :</strong><br>
                                            {{-- $data->request_justified_on --}}

                                            @php
                                                $utcTime = $data->request_justified_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Request Justified Comment :</strong><br>
                                            {{ $data->request_justified_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Request Not Justified By :</strong><br>
                                            {{ $data->request_not_justified_by }}
                                        </td>
                                        <td>
                                            <strong>Request Not Justified On :</strong><br>
                                            {{-- $data->request_not_justified_on }}

                                            @php
                                                $utcTime = $data->request_not_justified_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Request Not Justified Comment :</strong><br>
                                            {{ $data->request_not_justified_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Pre-Purchase Sample Required By :</strong><br>
                                            {{ $data->prepurchase_sample_by}}
                                        </td>
                                        <td>
                                            <strong>Pre-Purchase Sample Required On :</strong><br>
                                            {{-- $data->prepurchase_sample_on --}}

                                            @php
                                                $utcTime = $data->prepurchase_sample_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Pre-Purchase Sample Required Comment :</strong><br>
                                            {{ $data->prepurchase_sample_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Pre-Purchase Sample Not Required By :</strong><br>
                                            {{ $data->prepurchase_sample_notRequired_by }}
                                        </td>
                                        <td>
                                            <strong>Pre-Purchase Sample Not Required On :</strong><br>
                                            {{-- $data->prepurchase_sample_notRequired_on --}}

                                            @php
                                                $utcTime = $data->prepurchase_sample_notRequired_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Pre-Purchase Sample Not Required Comment :</strong><br>
                                            {{ $data->prepurchase_sample_notRequired_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Purchase Sample Request Ack. by Dep. :</strong><br>
                                            {{ $data->pendigPurchaseSampleRequested_by  }}
                                        </td>
                                        <td>
                                            <strong>Purchase Sample Request Ack. by Dep. On :</strong><br>
                                            {{-- $data->pendigPurchaseSampleRequested_on --}}

                                            @php
                                                $utcTime = $data->pendigPurchaseSampleRequested_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Purchase Sample Request Ack. by Dep. Comment :</strong><br>
                                            {{ $data->pendigPurchaseSampleRequested_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Purchase Sample Analysis Satisfactory By :</strong><br>
                                            {{ $data->purchaseSampleanalysis_by }}
                                        </td>
                                        <td>
                                            <strong>Purchase Sample Analysis Satisfactory On :</strong><br>
                                            {{-- $data->purchaseSampleanalysis_on --}}

                                            @php
                                                $utcTime = $data->purchaseSampleanalysis_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Purchase Sample Analysis Satisfactory Comment :</strong><br>
                                            {{ $data->purchaseSampleanalysis_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Purchase Sample Analysis Not Satisfactory By:</strong><br>
                                            {{ $data->purchaseSampleanalysisNotSatisfactory_by }}
                                        </td>
                                        <td>
                                            <strong>Purchase Sample Analysis Not Satisfactory On :</strong><br>
                                            {{-- $data->purchaseSampleanalysisNotSatisfactory_on --}}

                                            @php
                                                $utcTime = $data->purchaseSampleanalysisNotSatisfactory_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Purchase Sample Analysis Not Satisfactory Comment :</strong><br>
                                            {{ $data->purchaseSampleanalysisNotSatisfactory_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>



                                    <tr>
                                        <td>
                                            <strong>F&D Review Completed By :</strong><br>
                                            {{ $data->FdReviewCompleted_by }}
                                        </td>
                                        <td>
                                            <strong>F&D Review Completed On :</strong><br>
                                            {{-- $data->FdReviewCompleted_on --}}

                                            @php
                                                $utcTime = $data->FdReviewCompleted_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>F&D Review Completed Comment :</strong><br>
                                            {{ $data->FdReviewCompleted_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Acknowledgement By Purchase Dept. By :</strong><br>
                                            {{  $data->acknowledgByPD_by}}
                                        </td>
                                        <td>
                                            <strong>Acknowledgement By Purchase Dept. On :</strong><br>
                                            {{-- $data->acknowledgByPD_on  --}}

                                            @php
                                                $utcTime = $data->acknowledgByPD_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Acknowledgement By Purchase Dept. Comment :</strong><br>
                                            {{ $data->acknowledgByPD_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>All Requirements Fulfilled By :</strong><br>
                                            {{  $data->requirementFullfilled_by}}
                                        </td>
                                        <td>
                                            <strong>All Requirements Fulfilled On :</strong><br>
                                            {{-- $data->requirementFullfilled_on --}}

                                            @php
                                                $utcTime = $data->requirementFullfilled_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>All Requirements Fulfilled Comment :</strong><br>
                                            {{ $data->requirementFullfilled_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>All Requirements Not Fulfilled By :</strong><br>
                                            {{ $data->requiredNotFulfilled_by }}
                                        </td>
                                        <td>
                                            <strong>All Requirements Not Fulfilled On :</strong><br>
                                            {{-- $data->requiredNotFulfilled_on --}}

                                            @php
                                                $utcTime = $data->requiredNotFulfilled_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>All Requirements Not Fulfilled Comment :</strong><br>
                                            {{  $data->requiredNotFulfilled_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Risk Rating Observed as High By :</strong><br>
                                            {{  $data->riskRatingObservedAsHigh_by }}
                                        </td>
                                        <td>
                                            <strong>Risk Rating Observed as High On :</strong><br>
                                            {{-- $data->riskRatingObservedAsHigh_on  --}}

                                            @php
                                                $utcTime = $data->riskRatingObservedAsHigh_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Risk Rating Observed as High Comment :</strong><br>
                                            {{ $data->riskRatingObservedAsHigh_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    
                                    <tr>
                                        <td>
                                            <strong>Risk Rating Observed as Low By :</strong><br>
                                            {{ $data->riskRatingObservedAsLow_by }}
                                        </td>
                                        <td>
                                            <strong>Risk Rating Observed as Low On :</strong><br>
                                            {{-- $data->riskRatingObservedAsLow_on  --}}

                                            @php
                                                $utcTime = $data->riskRatingObservedAsLow_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Risk Rating Observed as Low Comment :</strong><br>
                                            {{ $data->riskRatingObservedAsLow_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Manufacturer Audit Passed By :</strong><br>
                                            {{ $data->manufacturerAuditPassed_by }}
                                        </td>
                                        <td>
                                            <strong>Manufacturer Audit Passed On :</strong><br>
                                            {{-- $data->manufacturerAuditPassed_on --}}

                                            @php
                                                $utcTime = $data->manufacturerAuditPassed_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Manufacturer Audit Passed Comment :</strong><br>
                                            {{ $data->manufacturerAuditPassed_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Initiate Periodic Revaluation By :</strong><br>
                                            {{ $data->periodicRevolutionInitiated_by }}
                                        </td>
                                        <td>
                                            <strong>Initiate Periodic Revaluation On :</strong><br>
                                            {{-- $data->periodicRevolutionInitiated_on --}}

                                            @php
                                                $utcTime = $data->periodicRevolutionInitiated_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Initiate Periodic Revaluation Comment :</strong><br>
                                            {{ $data->periodicRevolutionInitiated_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Risk Rating Observed as High/Medium By :</strong><br>
                                            {{ $data->riskRatingObservedAsHighMedium_by }}
                                        </td>
                                        <td>
                                            <strong>Risk Rating Observed as High/Medium On :</strong><br>
                                            {{-- $data->riskRatingObservedAsHighMedium_on --}}

                                            @php
                                                $utcTime = $data->riskRatingObservedAsHighMedium_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Risk Rating Observed as High/Medium Comment :</strong><br>
                                            {{ $data->riskRatingObservedAsHighMedium_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <strong>Risk Rating Observed as Low By :</strong><br>
                                            {{ $data->riskRatingObservedLow_by }}
                                        </td>
                                        <td>
                                            <strong>Risk Rating Observed as Low On :</strong><br>
                                            {{-- $data->riskRatingObservedLow_on --}}

                                            @php
                                                $utcTime = $data->riskRatingObservedLow_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Risk Rating Observed as Low Comment :</strong><br>
                                            {{ $data->riskRatingObservedLow_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>


                                    <tr>
                                        <td>
                                            <strong>Manufacturer Audit Failed By :</strong><br>
                                            {{ $data->pendingManufacturerAuditFailed_by }}
                                        </td>
                                        <td>
                                            <strong>Manufacturer Audit Failed On :</strong><br>
                                            {{-- $data->pendingManufacturerAuditFailed_on --}}

                                            @php
                                                $utcTime = $data->pendingManufacturerAuditFailed_on ?? null;

                                                if ($utcTime) {
                                                    try {
                                                        $istTime = \Carbon\Carbon::parse($utcTime, 'UTC')
                                                            ->setTimezone('Asia/Kolkata')
                                                            ->format('d-M-Y H:i:s T');
                                                        echo $istTime;
                                                    } catch (\Exception $e) {
                                                        echo 'Invalid Date Format';
                                                    }
                                                } else {
                                                    echo 'No Time Available';
                                                }
                                            @endphp
                                        </td>
                                    </tr>

                                    <tr>
                                        <td colspan="2">
                                            <strong>Manufacturer Audit Failed Comment :</strong><br>
                                            {{ $data->pendingManufacturerAuditFailed_comment ?? 'Not Applicable' }}
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>

                        <div class="button-block">
                            <button type="button" class="backButton" onclick="previousStep()">Back</button>
                            <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                                    Exit </a> </button>
                        </div>
                    </div>
                </div>

            </form>

            <div class="sticky-buttons">
                <div>
                    <a type="button" class="" data-toggle="modal" data-target="#myModal3">

                        <svg width="18" height="24" viewBox="0 0 384 512" xmlns="http://www.w3.org/2000/svg">
                            <path fill="#ffffff"
                                d="M369.9 97.9L286 14C277 5 264.8-.1 252.1-.1H48C21.5 0 0 21.5 0 48v416c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V131.9c0-12.7-5.1-25-14.1-34M332.1 128H256V51.9zM48 464V48h160v104c0 13.3 10.7 24 24 24h104v288zm220.1-208c-5.7 0-10.6 4-11.7 9.5c-20.6 97.7-20.4 95.4-21 103.5c-.2-1.2-.4-2.6-.7-4.3c-.8-5.1.3.2-23.6-99.5c-1.3-5.4-6.1-9.2-11.7-9.2h-13.3c-5.5 0-10.3 3.8-11.7 9.1c-24.4 99-24 96.2-24.8 103.7c-.1-1.1-.2-2.5-.5-4.2c-.7-5.2-14.1-73.3-19.1-99c-1.1-5.6-6-9.7-11.8-9.7h-16.8c-7.8 0-13.5 7.3-11.7 14.8c8 32.6 26.7 109.5 33.2 136c1.3 5.4 6.1 9.1 11.7 9.1h25.2c5.5 0 10.3-3.7 11.6-9.1l17.9-71.4c1.5-6.2 2.5-12 3-17.3l2.9 17.3c.1.4 12.6 50.5 17.9 71.4c1.3 5.3 6.1 9.1 11.6 9.1h24.7c5.5 0 10.3-3.7 11.6-9.1c20.8-81.9 30.2-119 34.5-136c1.9-7.6-3.8-14.9-11.6-14.9h-15.8z" />
                        </svg>
                    </a>
                </div>
            </div>
    
    </div>
    </div>
                <div class="modal right fade" id="myModal3" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-titles ml-10">Deviation Workflow</h4>
                            </div>
                            <div  style="" class="modal-body main-new-workflow">
                                <Div class="button-box">
                                    @if ($data->stage == 0)
                                    <div class="">
                                        <div class="mini_buttons  bg-danger">Closed-Cancelled</div>
                                    @else
                                        @if ($data->stage >= 1)
                                        <div  class="active">
                                            Opened
                                        </div>
                                        @else
                                        <div class="mini_buttons">Opened</div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..." class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 2)
                                        <div  class="active">
                                            Pending Initiating Department Update
                                        </div> 
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Initiating Department Update
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 3)
                                        <div  class="active">
                                            Pending Update FROM CQA
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Update FROM CQA
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 4)
                                        <div  class="active">
                                        Pending Purchase Sample Request
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                        Pending Purchase Sample Request
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 5)
                                        <div  class="active">
                                            Pending CQA Review After Purchase Sample Request
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending CQA Review After Purchase Sample Request
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 6)
                                        <div  class="active">
                                            Pending F&D Review
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending F&D Review
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 7)
                                        <div  class="active">
                                            Pending Acknowledgement By Purchase Department
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Acknowledgement By Purchase Department
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 8)
                                        <div  class="active">
                                            Pending CQA Final Review
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending CQA Final Review
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 9)
                                        <div  class="active">
                                            Manufacturer Rejected
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Manufacturer Rejected
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 10)
                                        <div  class="active">
                                            Pending Manufacturer Risk Assessment
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Manufacturer Risk Assessment
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 11)
                                        
                                        <div  class="active">
                                            Pending Manufacturer Audit
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Manufacturer Audit
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 12)
                                        <div  class="active">
                                            Approved Manufacturer/Supplier
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Approved Manufacturer/Supplier
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 13)
                                        
                                        <div  class="active">
                                            Pending Manufacturer Risk Assessment
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                            Pending Manufacturer Risk Assessment
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 14)
                                        <div  class="active">
                                        Pending Manufacturer Audit
                                        </div>
                                        @else
                                        <div  class="mini_buttons">
                                        Pending Manufacturer Audit
                                        </div>
                                        @endif
                                        <div class="down-logo">
                                            <img class="dawn_arrow" src="{{ asset('user/images/down.gif') }}" alt="..."
                                                class="w-100 h-100">
                                        </div>
                                        @if ($data->stage >= 15)
                                            <div class=" mini_buttons bg-danger">Manufacturer RejectedRisk</div>
                                        @else
                                            <div class="mini_buttons">Manufacturer RejectedRisk </div>
                                        @endif
                                    @endif    
                                </Div>
                            </div>
                        </div>
                    </div>
                </div>

    <style>
        .sticky-buttons div {
             background: #4274da;
             width: 40px;
             height: 40px;
             display: grid;
             place-items: center;
             border-radius: 0 5px 5px 0;
         }
            .sticky-buttons {
                        position: fixed;
                        top: 50%;
                        left: 0;
                        transform: translate(0, -50%);
                        display: grid;
                        gap: 10px;
                        z-index: 5;
                    }
            .btn-position{
                    top:50%;
                    left:50%;
                    transform:translate(-50%, -50%);
                    position:absolute;
                    }
                .modal.right.fade.in .modal-dialog {
                right:0 !important;
                transform: translateX(-50%);
                }
            
            .modal.right .modal-content {
            height:100%;
            overflow:auto;
            border-radius:0;
            }
            
            .modal.right .modal-dialog {
                    position: fixed;
                    margin: auto;
                    height: 100%;
                    -webkit-transform: translate3d(0%, 0, 0);
                    -ms-transform: translate3d(0%, 0, 0);
                    -o-transform: translate3d(0%, 0, 0);
                    transform: translate3d(0%, 0, 0);
                    }
            
            .modal.right.fade.in .modal-dialog {
            transform: translateX(0%);
            }
            .modal.right.fade .modal-dialog {
            
            -webkit-transition: opacity 0.3s linear, right 0.3s ease-out;
            -moz-transition: opacity 0.3s linear, right 0.3s ease-out;
            -o-transition: opacity 0.3s linear, right 0.3s ease-out;
            transition: opacity 0.3s linear, right 0.3s ease-out;
            width: 340px;
            }
                            
                
                .modal.right .modal-header {
                background-color:#bfd0f2; 
                display: flex;
                justify-content: center;
                color:#fff
            }
                .modal.right .modal-header::after {content:""; display:inline-block;}
                .modal.right .close {text-shadow:none; opacity:1; color:#ff4d4d; font-size:26px}
            /*  form-control  */
                
                .form-control {border-radius:0; box-shadow:none}
                .form-control:focus {box-shadow:none}
                
                
            /*  Button  */
            
                
                .btn {border-radius:0}
            
                .down-logo {
                display: flex;
                justify-content: center;
            }
            .dawn_arrow {
                /* position: absolute; */
                top: 100%;
                left: 50%;
                transform: rotate(90deg) translate(-12%, 23px);
                width: 50px;
                height: 50px;
                margin-left: 42px;
            }
            
            /* scrollbar */
            ::-webkit-scrollbar {
                width: 5px;
                height: 5px;
            }
                    
            ::-webkit-scrollbar-track {
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
                -webkit-border-radius: 15px;
                border-radius: 15px;
            }
                    
            ::-webkit-scrollbar-thumb {
                -webkit-border-radius: 15px;
                border-radius: 15px;
                background: rgba(255, 255, 255, 0.3);
                -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.5);
            }
                    
            ::-webkit-scrollbar-thumb:window-inactive {
                background: rgba(255, 255, 255, 0.3);
            }
                
            .mini_buttons{
                /* background: #1aa71a; */
                display: flex;
                border: 1px solid;
                padding: 5px;
                width: 250px;
                border-radius: 10px;
                justify-content: center;
            }
            .button-box .active{
                background: #1aa71a;
                display: flex;
                border: 1px solid;
                border-radius: 10px;
                padding: 7px;
                width: 250px;
                justify-content: center;
            }
            .main-new-workflow{
                display: flex;
                justify-content: center;
            }
            .state-block .top-block {
                background: #4274da;
                color: white;
                display: grid;
                grid-template-columns: repeat(4, 1fr);
            }
            .state-block .top-block div {
                padding: 10px 20px;
                border-right: 1px dashed #ffffff;
                font-size: 0.9rem;
            }

                        
            
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>


            <!-- Forword Stage Modal -->
            <div class="modal fade" id="signature-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-send-stage', $data->id) }}" method="POST">
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
                                    <input type="comment" name="comments" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Close Cancelled Stage Modal -->
            <div class="modal fade" id="cancel-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-close-cancelled', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contract Giver Modal -->
            <div class="modal fade" id="contract-giver-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/approvedBy-contract-giver', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                <label for="comment">Comment <span class="text-danger">*</span></label>
                                <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Link Manufacturer Modal -->
            <div class="modal fade" id="link-manufacturer">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/link-manufacturer', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                <label for="comment">Comment <span class="text-danger">*</span></label>
                                <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="modal fade" id="pending-manufacturer-audit-more-info">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/pending-manufacturer-audit-more-info', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Backword Stage Modal -->
            <div class="modal fade" id="backword-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/supplier-reject-stage', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Manufacturer Reject Modal -->
            <div class="modal fade" id="manufacturer-reject-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/manufacturer-reject', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Pending CQA Review Modal -->
            <div class="modal fade" id="pendingCQAReview-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/sendTo-pendig-CQA', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Risk Rating Observed Low Modal -->
            <div class="modal fade" id="risk-rating-low-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">E-Signature</h4>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="{{ url('rcms/risk-rating-observed-low', $data->id) }}" method="POST">
                            @csrf
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
                                <div class="group-input mt-3">
                                    <label for="password">Password <span class="text-danger">*</span></label>
                                    <input type="password" name="password" required class="form-control">
                                </div>
                                <div class="group-input mt-3">
                                    <label for="comment">Comment <span class="text-danger">*</span></label>
                                    <input type="comment" name="comments" required class="form-control">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" data-bs-dismiss="modal">Submit</button>
                                <button type="button" data-bs-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Child Stage Modal -->
            <div class="modal fade" id="child-modal">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Child</h4>
                        </div>
                        <form action="{{ route('supplier_child_1', $data->id) }}" method="POST" target="_blank">
                            @csrf
                            <!-- Modal body -->
                            <div class="modal-body">
                                <div class="group-input">
                                    @if ($data->stage == 11 || $data->stage == 14)
                                        <label for="major">
                                            <input type="radio" name="revision" id="major" value="SA">
                                            Supplier Audit
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
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const removeButtons = document.querySelectorAll('.remove-file');

            removeButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const fileName = this.getAttribute('data-file-name');
                    const fileContainer = this.closest('.file-container');

                    // Hide the file container
                    if (fileContainer) {
                        fileContainer.style.display = 'none';
                    }
                });
            });
        });
    </script>

    <script>
        VirtualSelect.init({
            ele: '#supplier-product, #ppap-elements, #supplier-services, #other-products, #manufacture-sites, #request_for'
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

        function handleDateInput(input, targetId) {
            const target = document.getElementById(targetId);
            const date = new Date(input.value);
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            };
            const formattedDate = date.toLocaleDateString('en-US', options).replace(/ /g, '-');
            target.value = formattedDate;
        }
    </script>
    <script>
        // JavaScript
        document.getElementById('initiation_group').addEventListener('change', function() {
            var selectedValue = this.value;
            document.getElementById('initiator_group_code').value = selectedValue;
        });
    </script>

   <script>
    document.addEventListener("DOMContentLoaded", function() {
    function addRow(type) {
        let tbody = document.getElementById(`${type}_rows`);
        let newRow = document.createElement('tr');
        newRow.innerHTML = `
            <td>
                <button class="button_theme" style="margin-left: px;"  type="button" onclick="removeRow(this)">Remove</button>
            </td>
            <td>
                <input type="file" name="${type}_attachment[]" class="custom-border">
            </td>
            <td>
                <input type="date" name="certificate_issue_${type}[]" class="custom-border">
            </td>
            <td>
                <input type="date" name="certificate_expiry_${type}[]" class="custom-border">
            </td>
            <td>
                <textarea name="${type}_remarks[]" class="custom-border"></textarea>
            </td>
        `;
        tbody.appendChild(newRow);
    }

    window.addRow = addRow;

    function removeRow(button) {
        let row = button.parentNode.parentNode;
        row.parentNode.removeChild(row);
    }

    window.removeRow = removeRow;

    document.getElementById('fileInput').addEventListener('change', function() {
        var fileName = this.files[0] ? this.files[0].name : 'No file chosen';
        document.getElementById('fileName').textContent = fileName;
    });
});

   </script>

    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            var formStatus = @json($formStatus);
            if (formStatus === 9) {
                var inputs = document.querySelectorAll('input, textarea, select');
                inputs.forEach(function(input) {
                    input.setAttribute('disabled', 'disabled');
                });
            }
        });
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.collapsible-header').click(function() {
                $(this).toggleClass('collapsed');
                $(this).find('.icon').html($(this).hasClass('collapsed') ? '&#x25BC;' : '&#x25B2;');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            <?php if ($data->stage == 0 ) { ?>
            $("#target :input").not(".backButton, .nextButton").prop("disabled", true);
            <?php } ?>
        });
    </script>
 <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const removeButtons = document.querySelectorAll('.remove-file');

                    removeButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const fileName = this.getAttribute('data-file-name');
                            const fileContainer = this.closest('.file-container');

                            // Hide the file container
                            if (fileContainer) {
                                fileContainer.style.display = 'none';
                            }
                        });
                    });
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
@endsection
