<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VidyaGxP - Software</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Roboto', sans-serif;
        margin: 0;
        padding: 0;
        min-height: 100vh;
    }

    .w-10 {
        width: 10%;
    }

    .w-20 {
        width: 20%;
    }

    .w-25 {
        width: 25%;
    }

    .w-30 {
        width: 30%;
    }

    .w-40 {
        width: 40%;
    }

    .w-50 {
        width: 50%;
    }

    .w-60 {
        width: 60%;
    }

    .w-70 {
        width: 70%;
    }

    .w-80 {
        width: 80%;
    }

    .w-90 {
        width: 90%;
    }

    .w-100 {
        width: 100%;
    }

    .h-100 {
        height: 100%;
    }

    header table,
    header th,
    header td,
    footer table,
    footer th,
    footer td,
    .border-table table,
    .border-table th,
    .border-table td {
        border: 1px solid black;
        border-collapse: collapse;
        font-size: 0.9rem;
        vertical-align: middle;
    }

    table {
        width: 100%;
    }

    th,
    td {
        padding: 10px;
        text-align: left;
    }

    footer .head,
    header .head {
        text-align: center;
        font-weight: bold;
        font-size: 1.2rem;
    }

    @page {
        size: A4;
        margin-top: 160px;
        margin-bottom: 60px;
    }

    header {
        position: fixed;
        top: -140px;
        left: 0;
        width: 100%;
        display: block;
    }

    footer {
        width: 100%;
        position: fixed;
        display: block;
        bottom: -40px;
        left: 0;
        font-size: 0.9rem;
    }

    footer td {
        text-align: center;
    }

    .inner-block {
        padding: 10px;
    }

    .inner-block tr {
        font-size: 0.8rem;
    }

    .inner-block .block {
        margin-bottom: 30px;
    }

    .inner-block .block-head {
        font-weight: bold;
        font-size: 1.1rem;
        padding-bottom: 5px;
        border-bottom: 2px solid #4274da;
        margin-bottom: 10px;
        color: #4274da;
    }

    .inner-block th,
    .inner-block td {
        vertical-align: baseline;
    }

    .table_bg {
        background: #4274da57;
    }

    .head-number {
        font-weight: bold;
        font-size: 13px;
        padding-left: 10px;
    }

    .div-data {
        font-size: 13px;
        padding-left: 10px;
        margin-bottom: 10px;
    }
</style>

<body>
    <header>
        <table>
            <tr>
                <td class="w-70 head">
                    Regulatory Inspection Single Report
                </td>
                <td class="w-30">
                    <div class="logo">
                        <img src="https://vidyagxp.com/vidyaGxp_logo.png" alt="" class="w-100">
                    </div>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Regulatory Inspection No.</strong>
                </td>
                <td class="w-40">
                    {{ Helpers::divisionNameForQMS($data->division_id) }}/RI/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
                <td class="w-30">
                    <strong>Record No.</strong> {{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                </td>
            </tr>
        </table>
    </header>
    <footer>
        <table>
            <tr>
                <td class="w-30">
                    <strong>Printed On :</strong> {{ date('d-M-Y') }}
                </td>
                <td class="w-40">
                    <strong>Printed By :</strong> {{ Auth::user()->name }}
                </td>
                {{-- <td class="w-30">
                    <strong>Page :</strong> 1 of 1
                </td> --}}
            </tr>
        </table>
    </footer>
    <div class="inner-block">
        <div class="content-table">
            <div class="block">
                <div class="block-head">
                    General Information
                </div>
                <table>
                    <tr>
                        <th class="w-20">Record Number</th>
                        <td class="w-30">
                            {{ Helpers::divisionNameForQMS($data->division_id) }}/RI/{{ Helpers::year($data->created_at) }}/{{ str_pad($data->record, 4, '0', STR_PAD_LEFT) }}
                        </td>
                        <th class="w-20">Site/Location Code</th>
                        <td class="w-30">{{ $data->division ? $data->division->name : 'Na' }}</td>
                    </tr>
                    <tr> {{ $data->created_at }} added by {{ $data->originator }}
                        <th class="w-20">Initiator</th>
                        <td class="w-30">{{ Helpers::getInitiatorName($data->initiator_id) }}</td>
                        <th class="w-20">Date of Initiation</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->intiation_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Assigned to</th>
                        <td class="w-30">{{ Helpers::getInitiatorName($data->assign_to) }}</td>
                        <th class="w-20">Date Due</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->due_date) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Initiator Group</th>
                        <td class="w-30">
                            @if ($data->Initiator_Group)
                                {{ \Helpers::getInitiatorGroupFullName($data->Initiator_Group) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Initiator Group Code</th>
                        <td class="w-30">
                            @if ($data->initiator_group_code)
                                {{ $data->initiator_group_code }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <label class="head-number" for="Short Description">Short Description</label>
                <div class="div-data">
                    @if ($data->short_description)
                        {{ $data->short_description }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <table>
                    <tr>
                        <th class="w-20"> Severity Level</th>
                        <td class="w-30">
                            @if ($data->severity_level)
                                {{ $data->severity_level }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Initiated Through</th>
                        <td class="w-30">
                            @if ($data->initiated_through)
                                {{ $data->initiated_through }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <label class="head-number" for="Others">Others</label>
                <div class="div-data">
                    @if ($data->initiated_if_other)
                        {{ $data->initiated_if_other }}
                        {{-- {{ str_replace(',', ', ', $data->initiated_if_other) }} --}}
                    @else
                        Not Applicable
                    @endif
                </div>

                <table>
                    <tr>
                        <th class="w-20">Audit type</th>
                        <td class="w-30">
                            @if ($data->audit_type)
                                {{ $data->audit_type }}
                            @else
                                Not Applicable
                            @endif
                        </td>

                        <th class="w-20">Supplier Agencies </th>
                        <td class="w-30">
                            @if ($data->Supplier_agencies)
                                {{ $data->Supplier_agencies }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <label class="head-number" for="If Others">If Others</label>
                <div class="div-data">
                    @if ($data->if_other)
                        {{ $data->if_other }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Description">Description</label>
                <div class="div-data">
                    @if ($data->initial_comments)
                        {{ $data->initial_comments }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <div class="border-table">
                    <div class="block-head">
                        Initial Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                        @if ($data->inv_attachment)
                            @foreach (json_decode($data->inv_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>
            </div>


            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Audit Planning
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Audit Schedule Start Date</th>
                            <td class="w-30">
                                @if ($data->start_date)
                                    {{ Helpers::getdateFormat($data->start_date) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Audit Schedule End Date</th>
                            <td class="w-30">
                                @if ($data->end_date)
                                    {{ Helpers::getdateFormat($data->end_date) }}
                                @else
                                    Not Applicable
                                @endif
                            </td>

                        </tr>

                    </table>

                    <label class="head-number" for="Product/Material Name">Product/Material Name</label>
                    <div class="div-data">
                        @if ($data->material_name)
                            {{ $data->material_name }}
                        @else
                            Not Applicable
                        @endif
                    </div>

                    <label class="head-number" for="Comments (If Any)">Comments (If Any)</label>
                    <div class="div-data">
                        @if ($data->if_comments)
                            {{ $data->if_comments }}
                        @else
                            Not Applicable
                        @endif
                    </div>
                    {{-- <tr>
                            <th class="w-20">Comments (If Any)</th>
                            <td class="w-30">
                                @if ($data->if_comments)
                                    @foreach (explode(',', $data->if_comments) as $Key => $value)
                                        <li>{{ $value }}</li>
                                    @endforeach
                                @else
                                    Not Applicable
                                @endif
                            </td> --}}
                    {{-- <table>
                        <tr>
                            <th class="w-20">Product/Material Name</th>
                            <td class="w-80">
                                @if ($data->material_name)
                                    @foreach (explode(',', $data->material_name) as $Key => $value)
                                        <li>{{ $value }}</li>
                                    @endforeach
                                @else
                                    Not Applicable
                                @endif
                            </td>


                        </tr>

                    </table> --}}
                </div>
            </div>
            <div class="block">
                <div class="block-head">
                    Audit Preparation
                </div>
                <table>

                    <tr>
                        <th class="w-20">Lead Auditor</th>
                        <td class="w-30">
                            @if ($data->lead_auditor)
                                {{ Helpers::getInitiatorName($data->lead_auditor) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>
                <div class="border-table">
                    <div class="block-head">
                        File Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                        @if ($data->file_attachment)
                            @foreach (json_decode($data->file_attachment) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>

                <table>
                    <tr>
                        <th class="w-20">Audit team</th>
                        <td class="w-30">
                            @if ($data->Audit_team)
                                @foreach (explode(',', $data->Audit_team) as $Key => $value)
                                    <li>{{ Helpers::getInitiatorName($value) }}</li>
                                @endforeach
                            @else
                                Not Applicable
                            @endif
                        </td>
                        <th class="w-20">Auditee</th>
                        <td class="w-30">
                            @if ($data->Auditee)
                                @foreach (explode(',', $data->Auditee) as $Key => $value)
                                    <li>{{ Helpers::getInitiatorName($value) }}</li>
                                @endforeach
                            @else
                                Not Applicable
                            @endif
                        </td>

                    </tr>
                </table>

                <label class="head-number" for="Supplier Auditor Details">Supplier Auditor Details</label>
                <div class="div-data">
                    @if ($data->Auditor_Details)
                        {{ $data->Auditor_Details }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Supplier Auditing Agency">Supplier Auditing Agency</label>
                <div class="div-data">
                    @if ($data->Supplier_Auditing_Agency)
                        {{ $data->Supplier_Auditing_Agency }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Relevant Guidelines / Industry Standards">Relevant Guidelines /
                    Industry Standards</label>
                <div class="div-data">
                    @if ($data->Relevant_Guidelines)
                        {{ $data->Relevant_Guidelines }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="QA Comments">QA Comments</label>
                <div class="div-data">
                    @if ($data->QA_Comments)
                        {{ $data->QA_Comments }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <div class="border-table">
                    <div class="block-head">
                        Guideline Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                        @if ($data->file_attachment)
                            @foreach (json_decode($data->file_attachment_guideline) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>

                <table>
                    <tr>
                        <th class="w-20">Audit Category</th>
                        <td class="w-30">
                            @if ($data->Audit_Category)
                                {{ Helpers::getInitiatorName($data->Audit_Category) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <label class="head-number" for="Supplier/Vendor/Manufacturer Site">Supplier/Vendor/Manufacturer
                    Site</label>
                <div class="div-data">
                    @if ($data->Supplier_Site)
                        {{ $data->Supplier_Site }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Supplier/Vendor/Manufacturer Details">Supplier/Vendor/Manufacturer
                    Details</label>
                <div class="div-data">
                    @if ($data->Supplier_Details)
                        {{ $data->Supplier_Details }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Comments">Comments</label>
                <div class="div-data">
                    @if ($data->Comments)
                        {{ $data->Comments }}
                    @else
                        Not Applicable
                    @endif
                </div>

            </div>


            <div class="block">
                <div class="head">
                    <div class="block-head">
                        Audit Execution
                    </div>
                    <table>
                        <tr>
                            <th class="w-20">Audit Start Date</th>
                            <td class="w-30">
                                @if ($data->audit_start_date)
                                    {{ $data->audit_start_date }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                            <th class="w-20">Audit End Date</th>
                            <td class="w-30">
                                @if ($data->audit_end_date)
                                    {{ $data->audit_end_date }}
                                @else
                                    Not Applicable
                                @endif
                            </td>
                        </tr>
                    </table>

                    <div class="border-table">
                        <div class="block-head">
                            Audit Attachments
                        </div>
                        <table>

                            <tr class="table_bg">
                                <th class="w-20">S.N.</th>
                                <th class="w-60">Batch No</th>
                            </tr>
                            @if ($data->Audit_file)
                                @foreach (json_decode($data->Audit_file) as $key => $file)
                                    <tr>
                                        <td class="w-20">{{ $key + 1 }}</td>
                                        <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                                target="_blank"><b>{{ $file }}</b></a> </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="w-20">1</td>
                                    <td class="w-20">Not Applicable</td>
                                </tr>
                            @endif

                        </table>
                    </div>

                    <label class="head-number" for="Audit Comments">Audit Comments</label>
                    <div class="div-data">
                        @if ($data->Audit_Comments1)
                            {{ $data->Audit_Comments1 }}
                        @else
                            Not Applicable
                        @endif
                    </div>


                </div>
            </div>

            <div class="block">
                <div class="block-head">
                    Audit Response & Closure
                </div>

                <label class="head-number" for="Remarks">Remarks</label>
                <div class="div-data">
                    @if ($data->Remarks)
                        {{ $data->Remarks }}
                    @else
                        Not Applicable
                    @endif
                </div>
                <table>
                    <tr>
                        <th class="w-20">Reference Record</th>
                        <td class="w-80" colspan="3">
                            @if ($data->Reference_Recores1)
                                {{ str_replace(',', ', ', $data->Reference_Recores1) }}
                            @else
                                Not Applicable
                            @endif
                        </td>
                    </tr>
                </table>

                <div class="border-table">
                    <div class="block-head">
                        Report Attachment
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">Batch No</th>
                        </tr>
                        @if ($data->report_file)
                            @foreach (json_decode($data->report_file) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>

                <div class="border-table">
                    <div class="block-head">
                        Audit Attachments
                    </div>
                    <table>

                        <tr class="table_bg">
                            <th class="w-20">S.N.</th>
                            <th class="w-60">File </th>
                        </tr>
                        @if ($data->myfile)
                            @foreach (json_decode($data->myfile) as $key => $file)
                                <tr>
                                    <td class="w-20">{{ $key + 1 }}</td>
                                    <td class="w-20"><a href="{{ asset('upload/' . $file) }}"
                                            target="_blank"><b>{{ $file }}</b></a> </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td class="w-20">1</td>
                                <td class="w-20">Not Applicable</td>
                            </tr>
                        @endif

                    </table>
                </div>

                <label class="head-number" for="Audit Comments">Audit Comments</label>
                <div class="div-data">
                    @if ($data->Audit_Comments2)
                        {{ $data->Audit_Comments2 }}
                    @else
                        Not Applicable
                    @endif
                </div>

                <label class="head-number" for="Due Date Extension Justification">Due Date Extension
                    Justification</label>
                <div class="div-data">
                    @if ($data->due_date_extension)
                        {{ $data->due_date_extension }}
                    @else
                        Not Applicable
                    @endif
                </div>

            </div>


            <div class="border-table">
                <div class="block-head">
                    Audit Agenda
                </div>
                <table>

                    <tr class="table_bg">
                        <th class="w-20">Row #</th>
                        <th class="w-60">Area of Audit</th>
                        <th class = "w-60">Scheduled Start Date</th>
                        <th class = "w-60">Scheduled Start Time</th>
                        <th class = "w-60">Scheduled End Date</th>
                    </tr>
                    @php
                        $serialNumber = 1;
                    @endphp
                    @if ($grid_data->start_date)
                        @foreach (unserialize($grid_data->start_date) as $key => $temps)
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                <td>{{ unserialize($grid_data->area_of_audit)[$key] ?? '' }}</td>
                                <td>{{ Helpers::getdateFormat(unserialize($grid_data->start_date)[$key]) ?? '' }}</td>
                                <td>{{ unserialize($grid_data->start_time)[$key] ?? '' }}</td>
                                <td>{{ Helpers::getdateFormat(unserialize($grid_data->end_date)[$key]) ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endif



                </table>
            </div>
            <div class="border-table">
                <div class="block-head">
                    Audit Agenda II
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">Row #</th>
                        <th class="w-60">Scheduled End Time</th>
                        <th class="w-60">Auditor</th>
                        <th class="w-60">Auditee</th>
                        <th class="w-60">Remarks</th>
                    </tr>
                    @php
                        use Carbon\Carbon;
                        $users = DB::table('users')->select('id', 'name')->get();
                        $serialNumber = 1; // Initialize serial number
                    @endphp

                    @if ($grid_data->start_date)
                        @foreach (unserialize($grid_data->start_date) as $key => $startDate)
                            {{-- @php
                                $scheduledEndTime = unserialize($grid_data->scheduled_end_time)[$key] ?? '';
                                $formattedStartDate = Carbon::parse($startDate)->format('d-M-Y');
                                $formattedEndTime = $scheduledEndTime
                                    ? Carbon::parse($scheduledEndTime)->format('d-M-Y')
                                    : '';
                            @endphp --}}
                            <tr>
                                <td>{{ $serialNumber++ }}</td>
                                {{-- <td>{{ $formattedEndTime }}</td> --}}
                                <td>{{ unserialize($grid_data->end_time)[$key] ?? '' }}</td>
                                <td>
                                    @foreach ($users as $value)
                                        @if (unserialize($grid_data->auditor1)[$key] == $value->id)
                                            {{ $value->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($users as $value)
                                        @if (unserialize($grid_data->auditee1)[$key] == $value->id)
                                            {{ $value->name }}
                                        @endif
                                    @endforeach
                                </td>
                                <td>{{ unserialize($grid_data->remark1)[$key] ?? '' }}</td>
                            </tr>
                        @endforeach
                    @endif
                </table>
            </div>

            {{-- <div class="border-table">
                <div class="block-head">
                    Observation Details
                </div>
                <table>
                    <tr class="table_bg">
                        <th class="w-20">Row #</th>
                        <th class="w-60">Observation Details</th>
                        <th class="w-60">Pre Comments</th>
                        <th class="w-60">CAPA Details if any</th>
                        <th class="w-60">Post Comments</th>
                    </tr>
                    @php

                        $users = DB::table('users')->select('id', 'name')->get();
                        $serialNumber = 1; // Initialize serial number
                    @endphp

                    @if ($grid_data1->observation_id)
                        @foreach (unserialize($grid_data1->observation_id) as $key => $tempData)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $tempData }}</td>
                                <td>{{ unserialize($grid_data1->observation_description)[$key] ? unserialize($grid_data1->observation_description)[$key] : '' }}
                                </td>
                                <td>{{ unserialize($grid_data1->area)[$key] ? unserialize($grid_data1->area)[$key] : '' }}
                                </td>
                                <td>{{ unserialize($grid_data1->auditee_response)[$key] ? unserialize($grid_data1->auditee_response)[$key] : '' }}
                                </td>

                            </tr>
                        @endforeach
                    @endif
                </table>
            </div> --}}



            <div class="block">
                <div class="block-head">
                    Activity Log
                </div>
                <table>
                    <tr>
                        <th class="w-20">Audit Schedule By</th>
                        <td class="w-30">{{ $data->audit_schedule_by }}</td>
                        <th class="w-20">Audit Schedule On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_schedule_on) }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-80" colspan="3">{{ $data->comment }}</td>

                    </tr>
                    <tr>
                        <th class="w-20">Cancelled By</th>
                        <td class="w-30">{{ $data->cancelled_by }}</td>
                        <th class="w-20">Cancelled On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->cancelled_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->comment_cancelled_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Completed Audit Preparation by</th>
                        <td class="w-30">{{ $data->audit_preparation_completed_by }}</td>
                        <th class="w-20">Completed Audit Preparation On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_preparation_completed_on) }}
                        </td>
                    </tr>

                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->audit_preparation_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Issue Report By</th>
                        <td class="w-30">{{ $data->audit_mgr_more_info_reqd_by }}</td>
                        <th class="w-20"> Issue Report On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_mgr_more_info_reqd_on) }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->pending_response_comment }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">CAPA Plan Proposed By</th>
                        <td class="w-30">{{ $data->audit_observation_submitted_by }}</td>
                        <th class="w-20">CAPA Plan Proposed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_observation_submitted_on) }}
                        </td>
                    </tr>
                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->capa_execution_in_progress_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">All CAPA Closed By
                        </th>
                        <td class="w-30">{{ $data->audit_lead_more_info_reqd_by }}</td>
                        <th class="w-20">All CAPA Closed On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->audit_lead_more_info_reqd_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->comment_closed_done_by_comment }}</td>
                    </tr>

                    <tr>
                        <th class="w-20">Rejected By</th>
                        <td class="w-30">{{ $data->rejected_by }}</td>
                        <th class="w-20">Rejected On</th>
                        <td class="w-30">{{ Helpers::getdateFormat($data->rejected_on) }}</td>
                    </tr>
                    <tr>
                        <th class="w-20">Comment</th>
                        <td class="w-30" colspan="3">{{ $data->comment_rejected_comment }}</td>
                    </tr>


                </table>
            </div>
        </div>
    </div>



</body>

</html>
