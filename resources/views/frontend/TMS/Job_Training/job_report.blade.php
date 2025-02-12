<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Vidyagxp - Software</title>
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
</style>
<header>
    
    <div style="display: flex; align-items: center; justify-content: space-between; width: 100%;">
        <!-- Left logo -->
        <div style="width: 5%; text-align: left;">
            <img src="https://navin.mydemosoftware.com/public/admin/assets/images/connexo.png" 
                 alt="Left Logo" 
                 style="height: 30px; width: auto; margin-bottom: -15px !important;">
        </div>
        
    
        <!-- Center title -->
        <div style="text-align: center; font-weight: 1200;">
            On The Job Training Report
        </div>
    
        <!-- Right logo -->
        <div style="text-align: right; margin-left: auto;">
            {{-- <img src="https://media.licdn.com/dms/image/v2/C4E0BAQFbURQWpKn58A/company-logo_200_200/company-logo_200_200/0/1630619488370/symbiotec_pharmalab_pvt_ltd__logo?e=2147483647&v=beta&t=ijLmHrqtD-uAkL-S29EmQlvC3709-6BC7VvU19lcbTM" 
                 alt="Right Logo" 
                 style="height: 90px; max-width: 100%; margin-top: -9.9% !important;"> --}}
        </div>
        
    </div>
    <table style="margin-top: 12px !important;">
        <tr>
            <td class="w-30">
                <strong>Employee ID.</strong>
            </td>
            <td class="w-30">
                {{ $data->empcode }}
            </td>
            <td class="w-30">
                <strong>Employee Name.</strong>
            </td>
            <td class="w-30">
                {{ $data->name }}
            </td>

        </tr>
    </table>
    <table>
        <tr>

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
                    <th class="w-20">Employee Name</th>

                    <td class="w-30">
                        @if ($data->name)
                            {{ $data->name }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Employee Code</th>

                    <td class="w-30">
                        @if ($data->empcode)
                            {{ $data->empcode }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                {{-- <tr>
                    <th class="w-20">SOP Document</th>

                    <td class="w-30">
                        @if ($data->sopdocument)
                            {{ $data->sopdocument }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Type of Training</th>

                    <td class="w-30">
                        @if ($data->type_of_training)
                            {{ $data->type_of_training }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr> --}}

                <tr>
                    <th class="w-20">Start Date</th>

                    <td class="w-30">
                        @if ($data->start_date)
                            {{ Helpers::getdateFormat($data->start_date) }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">End Date</th>

                    <td class="w-30">
                        @if ($data->end_date)
                            {{ Helpers::getdateFormat($data->end_date) }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Department</th>

                    <td class="w-30">
                        @if ($data->department)
                            {{ ($data->department) }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">HOD</th>

                    <td class="w-30">
                        @if ($data->hod)
                            {{ $data->hod }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Revision Purpose</th>

                    <td class="w-30">
                        @if ($data->revision_purpose)
                            {{ $data->revision_purpose }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Remark</th>

                    <td class="w-30">
                        @if ($data->remark)
                            {{ $data->remark }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>

                <tr>
                    <th class="w-20">Evaluation Required</th>

                    <td class="w-30">
                        @if ($data->evaluation_required)
                            {{ $data->evaluation_required }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                </tr>
            </table>
            <div class="block-heads">

            </div>
            <style>
                .block-heads {
                    background-color: black;

                }
            </style>
            <div class="col-12">
                <div class="group-input">
                    <div class="why-why-chart">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 5%;">Sr.No.</th>
                                    <th style="width: 30%;">Subject</th>
                                    <th>Type of Training</th>
                                    <th>Reference Document No.</th>
                                    <th>Trainer</th>
                                    <th>Date of Training</th>
                                    <th>Date of Completion</th>
                                </tr>
                            </thead>
                            <tbody>


                                <!-- Row 1 -->
                                <tr>
                                    <td>1</td>
                                    <td>
                                        @if ($data->subject_1)
                                            {{ $data->subject_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->type_of_training_1)
                                            {{ $data->type_of_training_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->reference_document_no_1)
                                            {{ $data->reference_document_no_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->trainer_1)
                                            {{ $data->trainer_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->startdate_1)
                                            {{ $data->startdate_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->enddate_1)
                                            {{ $data->enddate_1 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                </tr>

                                <!-- Row 2 -->
                                <tr>
                                    <td>2</td>
                                    <td>
                                        @if ($data->subject_2)
                                            {{ $data->subject_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->type_of_training_2)
                                            {{ $data->type_of_training_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->reference_document_no_2)
                                            {{ $data->reference_document_no_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->trainer_2)
                                            {{ $data->trainer_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->startdate_2)
                                            {{ $data->startdate_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->enddate_2)
                                            {{ $data->enddate_2 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                </tr>

                                <!-- Row 3 -->
                                <tr>
                                    <td>3</td>
                                    <td>
                                        @if ($data->subject_3)
                                            {{ $data->subject_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->type_of_training_3)
                                            {{ $data->type_of_training_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->reference_document_no_3)
                                            {{ $data->reference_document_no_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->trainer_3)
                                            {{ $data->trainer_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->startdate_3)
                                            {{ $data->startdate_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->enddate_3)
                                            {{ $data->enddate_3 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                </tr>

                                <!-- Row 4 -->
                                <tr>
                                    <td>4</td>
                                    <td>
                                        @if ($data->subject_4)
                                            {{ $data->subject_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->type_of_training_4)
                                            {{ $data->type_of_training_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->reference_document_no_4)
                                            {{ $data->reference_document_no_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>

                                        @if ($data->trainer_4)
                                            {{ $data->trainer_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->startdate_4)
                                            {{ $data->startdate_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->enddate_4)
                                            {{ $data->enddate_4 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                </tr>

                                <!-- Row 5 -->
                                <tr>
                                    <td>5</td>
                                    <td>
                                        @if ($data->subject_5)
                                            {{ $data->subject_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->type_of_training_5)
                                            {{ $data->type_of_training_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->reference_document_no_5)
                                            {{ $data->reference_document_no_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>

                                        @if ($data->trainer_5)
                                            {{ $data->trainer_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->startdate_5)
                                            {{ $data->startdate_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->enddate_5)
                                            {{ $data->enddate_5 }}
                                        @else
                                            Not Applicable
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>

            <div class="block-head">
                QA/CQA Approval
            </div>

            <table>
                <tr>
                    <th class="w-20">Remark</th>
                    <td class="w-30">
                        @if ($data->qa_cqa_comment)
                            {{ $data->qa_cqa_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Attachment</th>
                    <td class="w-30">
                        @if ($data->qa_cqa_attachment)
                            {{ $data->qa_cqa_attachment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>

            <div class="block-head">
                Evaluation
            </div>

            <table>
                <tr>
                    <th class="w-20">Remark</th>
                    <td class="w-30">
                        @if ($data->evaluation_comment)
                            {{ $data->evaluation_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Attachment</th>
                    <td class="w-30">
                        @if ($data->evaluation_attachment)
                            {{ $data->evaluation_attachment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>

            <div class="block-head">
                QA/CQA Head Final Review
            </div>

            <table>
                <tr>
                    <th class="w-20">Remark</th>
                    <td class="w-30">
                        @if ($data->qa_cqa_head_comment)
                            {{ $data->qa_cqa_head_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Attachment</th>
                    <td class="w-30">
                        @if ($data->qa_cqa_head_attachment)
                            {{ $data->qa_cqa_head_attachment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>

            <div class="block-head">
                QA Final Approval
            </div>

            <table>
                <tr>
                    <th class="w-20">Remark</th>
                    <td class="w-30">
                        @if ($data->final_review_comment)
                            {{ $data->final_review_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="w-20">Attachment</th>
                    <td class="w-30">
                        @if ($data->final_review_attachment)
                            {{ $data->final_review_attachment }}
                        @else
                            Not Applicable
                        @endif
                    </td>
                </tr>
            </table>

            <div class="block-head">Activity Log</div>
            <table>
                <tr>
                    <th class="w-20">Submit By</th>
                    <td class="w-30">
                        @if ($data->submit_by)
                            {{ $data->submit_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Submit On</th>
                    <td class="w-30">
                        @if ($data->submit_on)
                            {{ $data->submit_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Submit Comment</th>
                    <td class="w-30">
                        @if ($data->submit_comment)
                            {{ $data->submit_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>

                <tr>
                    <th class="w-20">Approval Complete By</th>
                    <td class="w-30">
                        @if ($data->approval_complete_by)
                            {{ $data->approval_complete_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Approval Complete On</th>
                    <td class="w-30">
                        @if ($data->approval_complete_on)
                            {{ $data->approval_complete_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Approval Complete Comment</th>
                    <td class="w-30">
                        @if ($data->approval_complete_comment)
                            {{ $data->approval_complete_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>
                <tr>
                    <th class="w-20">Answer Submit By</th>
                    <td class="w-30">
                        @if ($data->answer_submit_by)
                            {{ $data->answer_submit_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Answer Submit On</th>
                    <td class="w-30">
                        @if ($data->answer_submit_on)
                            {{ $data->answer_submit_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Answer Submit Comment</th>
                    <td class="w-30">
                        @if ($data->answer_submit_comment)
                            {{ $data->answer_submit_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>

                <tr>
                    <th class="w-20">Evaluation Complete By</th>
                    <td class="w-30">
                        @if ($data->evaluation_complete_by)
                            {{ $data->evaluation_complete_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Evaluation Complete On</th>
                    <td class="w-30">
                        @if ($data->evaluation_complete_on)
                            {{ $data->evaluation_complete_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Evaluation Complete Comment</th>
                    <td class="w-30">
                        @if ($data->evaluation_complete_comment)
                            {{ $data->evaluation_complete_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>
                <tr>
                    <th class="w-20">QA/CQA Head Review Complete By</th>
                    <td class="w-30">
                        @if ($data->qa_head_review_complete_by)
                            {{ $data->qa_head_review_complete_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">QA/CQA Head Review Complete On</th>
                    <td class="w-30">
                        @if ($data->qa_head_review_complete_on)
                            {{ $data->qa_head_review_complete_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">QA/CQA Head Review Complete Comment</th>
                    <td class="w-30">
                        @if ($data->qa_head_review_complete_comment)
                            {{ $data->qa_head_review_complete_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>

                <tr>
                    <th class="w-20">Verification and Approval Complete By</th>
                    <td class="w-30">
                        @if ($data->verification_approval_complete_by)
                            {{ $data->verification_approval_complete_by }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Verification and Approval Complete On</th>
                    <td class="w-30">
                        @if ($data->verification_approval_complete_on)
                            {{ $data->verification_approval_complete_on }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                    <th class="w-20">Verification and Approval Complete Comment</th>
                    <td class="w-30">
                        @if ($data->verification_approval_complete_comment)
                            {{ $data->verification_approval_complete_comment }}
                        @else
                            Not Applicable
                        @endif
                    </td>

                </tr>

               


            </table>



        </div>
    </div>
</div>

<footer>
    <table>
        <tr>
            <td class="w-30">
                <strong>Printed On :</strong> {{ date('d-M-Y') }}
            </td>
            <td class="w-40">
                <strong>Printed By :</strong> {{ Auth::user()->name }}
            </td>

        </tr>
    </table>
</footer>

</body>

</html>
