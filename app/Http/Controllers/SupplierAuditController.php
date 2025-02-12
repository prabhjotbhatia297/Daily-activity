<?php

namespace App\Http\Controllers;

// use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Models\SupplierAudit;
use App\Models\RecordNumber;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ExternalAuditTrailSupplier;
use App\Models\ExternalAuditGridSupplier;
use Helpers;
use App\Models\User;
use Dompdf\Options;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use PDF;

use Dompdf\Dompdf;
use App\Models\RoleGroup;
use Illuminate\Support\Facades\File;


class SupplierAuditController extends Controller
{
    public function supplier_audit()
    {
        $old_record = SupplierAudit::select('id', 'division_id', 'record')->get();
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');



        return view("frontend.supplier-Audit.supplier_audit", compact('due_date', 'record_number', 'old_record'));
    }



    public function create(Request $request)
    {
        // dd($request->all());

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back()->withInput();
        }

        $internalAudit = new SupplierAudit();
        $lastDocument = new SupplierAudit();
        $internalAudit->form_type = "Supplier-audit";
        $internalAudit->record = ((RecordNumber::first()->value('counter')) + 1);
        $internalAudit->initiator_id = Auth::user()->id;
        $internalAudit->parent_type = $request->parent_type;
        $internalAudit->parent_id = $request->parent_id;
        $internalAudit->division_id = $request->division_id;
        $internalAudit->division_code = $request->division_code;
        // dd($request->division_id);
        $internalAudit->intiation_date = $request->intiation_date;
        $internalAudit->assign_to = $request->assign_to;
        $internalAudit->due_date = $request->due_date;
        $internalAudit->initiator_group_code = $request->initiator_group_code;
        $internalAudit->Initiator_Group = $request->Initiator_Group;
        $internalAudit->recordNumber = $request->recordNumber;

        $internalAudit->short_description = $request->short_description;
        $internalAudit->audit_type = $request->audit_type;
        $internalAudit->if_other = $request->if_other;
        $internalAudit->initiated_through = $request->initiated_through;
        $internalAudit->initiated_if_other = $request->initiated_if_other;
        $internalAudit->others = $request->others;
        $internalAudit->repeat = $request->repeat;
        $internalAudit->repeat_nature = $request->repeat_nature;
        $internalAudit->due_date_extension = $request->due_date_extension;
        $internalAudit->initial_comments = $request->initial_comments;
        $internalAudit->severity_level = $request->severity_level;
        if (!empty($request->start_date)) {
            $internalAudit->start_date = $request->start_date;
        }
        if (!empty($request->end_date)) {
            $internalAudit->end_date = $request->end_date;
        }
        $internalAudit->audit_agenda = $request->audit_agenda;
        $internalAudit->material_name = $request->material_name;
        $internalAudit->if_comments = $request->if_comments;
        $internalAudit->lead_auditor = $request->lead_auditor;
        $internalAudit->Audit_team =  implode(',', $request->Audit_team);
        $internalAudit->Auditee =  implode(',', $request->Auditee);
        $internalAudit->Auditor_Details = $request->Auditor_Details;
        $internalAudit->External_Auditing_Agency = $request->External_Auditing_Agency;
        $internalAudit->Relevant_Guidelines = $request->Relevant_Guidelines;
        $internalAudit->QA_Comments = $request->QA_Comments;
        $internalAudit->Audit_Category = $request->Audit_Category;
        $internalAudit->Supplier_Details = $request->Supplier_Details;
        $internalAudit->Supplier_Site = $request->Supplier_Site;
        $internalAudit->Comments = $request->Comments;
        $internalAudit->Audit_Comments1 = $request->Audit_Comments1;
        $internalAudit->Remarks = $request->Remarks;
        $internalAudit->refrence_record =  implode(',', $request->refrence_record);
        $internalAudit->Audit_Comments2 = $request->Audit_Comments2;
        if (!empty($request->audit_start_date)) {
            $internalAudit->audit_start_date = $request->audit_start_date;
        }

        if (!empty($request->audit_end_date)) {
            $internalAudit->audit_end_date = $request->audit_end_date;
        }
        $internalAudit->status = 'Opened';
        $internalAudit->stage = 1;
        $internalAudit->external_agencies = $request->external_agencies;

        // File Attachments


        if (!empty($request->file_attachment_guideline)) {
            $files = [];
            if ($request->hasfile('file_attachment_guideline')) {
                foreach ($request->file('file_attachment_guideline') as $file) {
                    $name = $request->name . 'file_attachment_guideline' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->file_attachment_guideline = json_encode($files);
        }

        if (!empty($request->inv_attachment)) {
            $files = [];
            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->inv_attachment = json_encode($files);
        }

        if (!empty($request->file_attachment)) {
            $files = [];
            if ($request->hasfile('file_attachment')) {
                foreach ($request->file('file_attachment') as $file) {
                    $name = $request->name . 'file_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }

            $internalAudit->file_attachment = json_encode($files);
        }

        $files = is_array($request->existing_Audit_file) ? $request->existing_Audit_file : null;
        if (!empty($request->Audit_file)) {
            // $files = [];
            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->Audit_file = json_encode($files);
        }



        // if (!empty($request->Audit_file)) {
        //     if ($internalAudit->Audit_file) {
        //         $existingFiles = json_decode($internalAudit->Audit_file, true); // Convert to associative array
        //         if (is_array($existingFiles)) {
        //             $files = $existingFiles;
        //         }
        //     }

        //     if ($request->hasfile('Audit_file')) {
        //         foreach ($request->file('Audit_file') as $file) {
        //             $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        // }

        // // If no files are attached, set to null
        // $internalAudit->Audit_file = !empty($files) ? json_encode($files) : null;


        if (!empty($request->report_file)) {
            $files = [];
            if ($request->hasfile('report_file')) {
                foreach ($request->file('report_file') as $file) {
                    $name = $request->name . 'report_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->report_file = json_encode($files);
        }


        if (!empty($request->myfile)) {
            $files = [];
            if ($request->hasfile('myfile')) {
                foreach ($request->file('myfile') as $file) {
                    $name = $request->name . 'myfile' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            $internalAudit->myfile = json_encode($files);
        }
        $internalAudit->save();
        // dd($internalAudit);

        $record = RecordNumber::first();
        $record->counter = ((RecordNumber::first()->value('counter')) + 1);
        $record->update();

        // -----------------grid---- Audit Agenda
        $data3 = new ExternalAuditGridSupplier();
        $data3->audit_id = $internalAudit->id;
        $data3->type = "external_audit";
        if (!empty($request->audit)) {
            $data3->area_of_audit = serialize($request->audit);
        }
        if (!empty($request->scheduled_start_date)) {
            $data3->start_date = serialize($request->scheduled_start_date);
        }
        if (!empty($request->scheduled_start_time)) {
            $data3->start_time = serialize($request->scheduled_start_time);
        }
        if (!empty($request->scheduled_end_date)) {
            $data3->end_date = serialize($request->scheduled_end_date);
        }
        if (!empty($request->scheduled_end_time)) {
            $data3->end_time = serialize($request->scheduled_end_time);
        }
        if (!empty($request->auditor)) {
            $data3->auditor = serialize($request->auditor);
        }
        if (!empty($request->auditee)) {
            $data3->auditee = serialize($request->auditee);
        }
        if (!empty($request->remark)) {
            $data3->remark = serialize($request->remark);
        }

        $data3->save();

        // -----------------grid ---- Observation Details
        $data4 = new ExternalAuditGridSupplier();
        $data4->audit_id = $internalAudit->id;
        $data4->type = "Observation_field_Auditee";
        if (!empty($request->observation_id)) {
            $data4->observation_id = serialize($request->observation_id);
        }
        if (!empty($request->date)) {
            $data4->date = serialize($request->date);
        }
        if (!empty($request->auditorG)) {
            $data4->auditor = serialize($request->auditorG);
        }
        if (!empty($request->auditeeG)) {
            $data4->auditee = serialize($request->auditeeG);
        }
        if (!empty($request->observation_description)) {
            $data4->observation_description = serialize($request->observation_description);
        }
        if (!empty($request->severity_level)) {
            $data4->severity_level = serialize($request->severity_level);
        }
        if (!empty($request->area)) {
            $data4->area = serialize($request->area);
        }
        if (!empty($request->observation_category)) {
            $data4->observation_category = serialize($request->observation_category);
        }
        if (!empty($request->capa_required)) {
            $data4->capa_required = serialize($request->capa_required);
        }
        if (!empty($request->auditee_response)) {
            $data4->auditee_response = serialize($request->auditee_response);
        }
        if (!empty($request->auditor_review_on_response)) {
            $data4->auditor_review_on_response = serialize($request->auditor_review_on_response);
        }
        if (!empty($request->qa_comment)) {
            $data4->qa_comment = serialize($request->qa_comment);
        }
        if (!empty($request->capa_details)) {
            $data4->capa_details = serialize($request->capa_details);
        }
        if (!empty($request->capa_due_date)) {
            $data4->capa_due_date = serialize($request->capa_due_date);
        }
        if (!empty($request->capa_owner)) {
            $data4->capa_owner = serialize($request->capa_owner);
        }
        if (!empty($request->action_taken)) {
            $data4->action_taken = serialize($request->action_taken);
        }
        if (!empty($request->capa_completion_date)) {
            $data4->capa_completion_date = serialize($request->capa_completion_date);
        }
        if (!empty($request->status_Observation)) {
            $data4->status = serialize($request->status_Observation);
        }
        if (!empty($request->remark_observation)) {
            $data4->remark = serialize($request->remark_observation);
        }
        $data4->save();

        // AuditTrail----------------------------------------


        $currentName = User::find($internalAudit['initiator_id']);
        if (!empty($internalAudit->initiator_id)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Initiator';
            $history->previous = "Null";
            $history->current = $currentName ? $currentName->name : 'Null';
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }




        if (!empty($internalAudit->intiation_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Date Of Null';
            $history->previous = "Null";
            $history->current = $internalAudit->intiation_date->format('d-M-Y');
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        $history = new ExternalAuditTrailSupplier();
        $history->supplier_id = $internalAudit->id;
        $history->activity_type = 'Record Number';
        $history->previous = "Null";
        $history->current = $internalAudit->recordNumber;
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->change_to = 'Opened';
        $history->change_from = 'Null';
        $history->action_name = "Create";
        $history->origin_state = $internalAudit->status;
        $history->save();

        if (!empty($internalAudit->division_id)){
         $history = new ExternalAuditTrailSupplier();
        $history->supplier_id = $internalAudit->id;
        $history->activity_type = 'Site/Location Code';
        $history->previous = "Null";
        $history->current = Helpers::getDivisionName($internalAudit->division_id);
        $history->comment = "Not Applicable";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->change_to = 'Opened';
        $history->change_from = 'Null';
        $history->action_name = "Create";
        $history->origin_state = $internalAudit->status;
        $history->save();

        }
        if (!empty($internalAudit->severity_level)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Severity Level';
            $history->previous = "Null";
            $history->current = $internalAudit->severity_level;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->refrence_record)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Reference Record';
            $history->previous = "Null";
            $history->current = $internalAudit->refrence_record;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }



        if (!empty($internalAudit->initiated_if_other)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Others';
            $history->previous = "Null";
            $history->current = $internalAudit->initiated_if_other;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->others)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier Agencies Others';
            $history->previous = "Null";
            $history->current = $internalAudit->others;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->external_agencies)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier Agencies';
            $history->previous = "Null";
            $history->current = $internalAudit->external_agencies;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }


        if (!empty($internalAudit->initiated_through)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Initiated Through';
            $history->previous = "Null";
            $history->current = $internalAudit->initiated_through;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->initial_comments)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $internalAudit->initial_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->if_comments)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Comments(If Any)';
            $history->previous = "Null";
            $history->current = $internalAudit->if_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->External_Auditing_Agency)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier Auditing Agency';
            $history->previous = "Null";
            $history->current = $internalAudit->External_Auditing_Agency;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Relevant_Guidelines)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Relevant Guidelines / Industry Standards';
            $history->previous = "Null";
            $history->current = $internalAudit->Relevant_Guidelines;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->QA_Comments)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'QA Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->QA_Comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Audit_Category)) {
            // Define the mapping array
            $auditCategoryMap = [
                '1' => 'Internal Audit/Self Inspection',
                '2' => 'Supplier Audit',
                '3' => 'Regulatory Audit',
                '4' => 'Consultant Audit',
            ];

            // Convert the numeric value to the corresponding name
            $currentAuditCategory = $auditCategoryMap[$internalAudit->Audit_Category] ?? $internalAudit->Audit_Category;

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Category';
            $history->previous = "Null";
            $history->current = $currentAuditCategory;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }


        if (!empty($internalAudit->file_attachment_guideline)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Guideline Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->file_attachment_guideline;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Supplier_Details)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Details';
            $history->previous = "Null";
            $history->current = $internalAudit->Supplier_Details;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->Supplier_Site)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Site';
            $history->previous = "Null";
            $history->current = $internalAudit->Supplier_Site;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->due_date_extension)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current = $internalAudit->due_date_extension;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        // if (!empty($$internalAudit->division_code)){
        //     $history = new $ExternalAuditTrailSupplier();
        //     $history->supplier_id = $internalAudit->id;
        //     $history->activity_type = 'Site/Location code';
        //     $history->previous = "Null";
        //     $history->current = $internalAudit->division_code;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $internalAudit->status;
        //     $history->change_to =   "Opened";
        //     $history->change_from = "Null";
        //     $history->action_name = 'Create';
        //     $history->save();
        // }

        if (!empty($internalAudit->date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Date of Initiator';
            $history->previous = "Null";
            $history->current = $internalAudit->date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }
        $previousAssignedToName = User::find($lastDocument->assign_to);
        $currentAssignedToName = User::find($internalAudit['assign_to']);



        if (!empty($internalAudit->assign_to)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Assigned to';
            $history->previous = $previousAssignedToName ? $previousAssignedToName->name : 'Null';
            $history->current = $currentAssignedToName ? $currentAssignedToName->name : 'Null';
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($request->initiator_group_code)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = "Null";
            $history->current = $request->initiator_group_code;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($request->initiator_group_code)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Initiator Group';
            $history->previous = "Null";
            $history->current = Helpers::getInitiatorGroupFullName($request->initiator_group_code);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }


        if (!empty($internalAudit->short_description)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Short Description';
            $history->previous = "Null";
            $history->current = $internalAudit->short_description;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->audit_type)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Type of Audit';
            $history->previous = "Null";
            $history->current = $internalAudit->audit_type;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->origin_state = $internalAudit->status;
            $history->save();
        }

        if (!empty($internalAudit->if_other)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'If Other';
            $history->previous = "Null";
            $history->current = $internalAudit->if_other;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->initial_comments)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Description';
            $history->previous = "Null";
            $history->current = $internalAudit->initial_comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->start_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Schedule Start Date';
            $history->previous = "Null";
            $history->current = $internalAudit->start_date->format('d-M-Y');
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->end_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Schedule End Date';
            $history->previous = "Null";
            $history->current = $internalAudit->end_date->format('d-M-Y');
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->audit_agenda)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Agenda';
            $history->previous = "Null";
            $history->current = $internalAudit->audit_agenda;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($internalAudit->material_name)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Product/Material Name';
            $history->previous = "Null";
            $history->current = $internalAudit->material_name;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        $previousleadauditor = User::find($lastDocument->lead_auditor);
        $currentleadauditor = User::find($internalAudit['lead_auditor']);
        if (!empty($internalAudit->lead_auditor)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Lead Auditor';
            $history->previous = $previousleadauditor ? $previousleadauditor->name : 'Null';
            $history->current = $currentleadauditor ? $currentleadauditor->name : 'Null';
            // $history->previous = "Null";
            // $history->current = $internalAudit->lead_auditor;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }
        // Retrieve and convert previous audit team IDs to names
        $previousAuditTeamIds = explode(',', $lastDocument->Audit_team);
        $previousAuditTeamNames = User::whereIn('id', $previousAuditTeamIds)->pluck('name')->toArray();
        $previousauditteam = implode(', ', $previousAuditTeamNames);

        // Retrieve and convert current audit team IDs to names
        $currentAuditTeamIds = explode(',', $internalAudit->Audit_team);
        $currentAuditTeamNames = User::whereIn('id', $currentAuditTeamIds)->pluck('name')->toArray();
        $currentauditteam = implode(', ', $currentAuditTeamNames);

        if (!empty($internalAudit->Audit_team)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Team';
            $history->previous = $previousauditteam;
            $history->current = $currentauditteam;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        $previousAuditTeeeIds = explode(',', $lastDocument->Auditee);
        $previousAuditTeee = User::whereIn('id', $previousAuditTeamIds)->pluck('name')->toArray();
        $previousauditee = implode(', ', $previousAuditTeamNames);

        // Retrieve previous and current auditee names
        $currentAuditTeeIds = explode(',', $internalAudit->Auditee);
        $currentAuditTeeNames = User::whereIn('id', $currentAuditTeeIds)->pluck('name')->toArray();
        $currentaudittee = implode(', ', $currentAuditTeeNames);

        if (!empty($internalAudit->Auditee)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Auditee';
            $history->previous = $previousauditee;
            $history->current = $currentaudittee;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->Auditor_Details)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Supplier Auditor Details';
            $history->previous = "Null";
            $history->current = $internalAudit->Auditor_Details;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->Comments)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->Comments;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->Audit_Comments1)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Comments';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_Comments1;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to ='Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->Remarks)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Remarks';
            $history->previous = "Null";
            $history->current = $internalAudit->Remarks;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }




        if (!empty($internalAudit->Audit_Comments2)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Comments.';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_Comments2;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->inv_attachment)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->inv_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->file_attachment)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'File Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->file_attachment;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->Audit_file)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Attachments';
            $history->previous = "Null";
            $history->current = $internalAudit->Audit_file;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->report_file)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Report Attachments';
            $history->previous = "Null";
            $history->current = $internalAudit->report_file;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }


        if (!empty($internalAudit->myfile)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Attachment';
            $history->previous = "Null";
            $history->current = $internalAudit->myfile;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->due_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Due Date';
            $history->previous = "Null";
            $history->current = $internalAudit->due_date;
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->audit_start_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit Start Date';
            $history->previous = "Null";
            $history->current = Helpers::getdateFormat($internalAudit->audit_start_date);
            $history->comment = "Not Applicable";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        if (!empty($internalAudit->audit_end_date)) {
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $internalAudit->id;
            $history->activity_type = 'Audit End Date';
            $history->previous = "Null";
            $history->current = $internalAudit->audit_end_date->format('d-M-Y');
            $history->comment = "Not Applicable ";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $internalAudit->status;
            $history->change_to = 'Opened';
            $history->change_from = 'Null';
            $history->action_name = "Create";
            $history->save();
        }

        toastr()->success("Record is Create Successfully");
        return redirect(url('rcms/qms-dashboard'));
    }

    public function show($id)
    {

        $old_record = SupplierAudit::select('id', 'division_id', 'record')->get();
        $data = SupplierAudit::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        $data->assign_to_name = User::where('id', $data->assign_id)->value('name');
        $data->initiator_name = User::where('id', $data->initiator_id)->value('name');
        $sgrid = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "external_audit")->firstOrCreate();
        $grid_data1 = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "Observation_field_Auditee")->first();
        // foreach($sgrid as $s)
        // return $sgrid;
        // dd($data);
        return view('frontend.supplier-Audit.supplierView', compact('data', 'old_record', 'sgrid', 'grid_data1'));
    }

    public function update(Request $request, $id)
    {


        $lastDocument = SupplierAudit::find($id);
        $internalAudit = SupplierAudit::find($id);
        $internalAudit->assign_to = $request->assign_to;

        $internalAudit->initiator_group_code = $request->initiator_group_code;
        $internalAudit->Initiator_Group = $request->Initiator_Group;
        $internalAudit->short_description = $request->short_description;
        $internalAudit->audit_type = $request->audit_type;
        $internalAudit->if_other = $request->if_other;

        $internalAudit->initiated_through = $request->initiated_through;
        $internalAudit->initiated_if_other = $request->initiated_if_other;
        $internalAudit->others = $request->others;
        $internalAudit->external_agencies = $request->external_agencies;
        $internalAudit->repeat = $request->repeat;
        $internalAudit->repeat_nature = $request->repeat_nature;
        $internalAudit->due_date_extension = $request->due_date_extension;

        $internalAudit->initial_comments = $request->initial_comments;
        if (!empty($request->start_date)) {
            $internalAudit->start_date = $request->start_date;
        }
        if (!empty($request->end_date)) {
            $internalAudit->end_date = $request->end_date;
        }
        $internalAudit->audit_agenda = $request->audit_agenda;
        $internalAudit->material_name = $request->material_name;
        $internalAudit->if_comments = $request->if_comments;
        $internalAudit->lead_auditor = $request->lead_auditor;
        $internalAudit->Audit_team =  implode(',', $request->Audit_team);
        $internalAudit->Auditee =  implode(',', $request->Auditee);
        $internalAudit->Auditor_Details = $request->Auditor_Details;
        $internalAudit->Audit_Category = $request->Audit_Category;
        $internalAudit->External_Auditing_Agency = $request->External_Auditing_Agency;
        $internalAudit->Relevant_Guidelines = $request->Relevant_Guidelines;
        $internalAudit->QA_Comments = $request->QA_Comments;
        $internalAudit->Supplier_Details = $request->Supplier_Details;
        $internalAudit->Supplier_Site = $request->Supplier_Site;
        $internalAudit->Comments = $request->Comments;
        $internalAudit->Audit_Comments1 = $request->Audit_Comments1;
        $internalAudit->Remarks = $request->Remarks;
        $internalAudit->refrence_record =  implode(',', $request->refrence_record);
        // if (!empty($request->file_attachment_guideline)) {
        //     $files = [];
        //     if ($request->hasfile('file_attachment_guideline')) {

        //         foreach ($request->file('file_attachment_guideline') as $file) {
        //             $name = $request->name . 'file_attachment_guideline' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $internalAudit->file_attachment_guideline = json_encode($files);
        // }

        $files = is_array($request->existing_file_attachment_guideline) ? $request->existing_file_attachment_guideline : null;

        if (!empty($request->file_attachment_guideline)) {
            if ($internalAudit->file_attachment_guideline) {
                $existingFiles = json_decode($internalAudit->file_attachment_guideline, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('file_attachment_guideline')) {
                foreach ($request->file('file_attachment_guideline') as $file) {
                    $name = $request->name . 'file_attachment_guideline' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        //If no files are attached, set to null
        $internalAudit->file_attachment_guideline = !empty($files) ? json_encode(array_values($files)) : null;


        $internalAudit->Audit_Comments2 = $request->Audit_Comments2;
        if (!empty($request->audit_start_date)) {
            $internalAudit->audit_start_date = $request->audit_start_date;
        }

        if (!empty($request->audit_end_date)) {
            $internalAudit->audit_end_date = $request->audit_end_date;
        }

        $internalAudit->severity_level = $request->severity_level;


        // $files = is_array($request->existing_inv_attachment) ? $request->existing_inv_attachment : null;

        // if (!empty($request->inv_attachment)) {
        //     if ($internalAudit->inv_attachment) {
        //         $existingInitialFiles = json_decode($internalAudit->inv_attachment, true); // Convert to associative array
        //         if (is_array($existingInitialFiles)) {
        //             $files = $existingInitialFiles;
        //         }
        //     }

        //     if ($request->hasfile('inv_attachment')) {
        //         foreach ($request->file('inv_attachment') as $file) {
        //             $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        // }

        // // If no files are attached, set to null
        // $internalAudit->inv_attachment = !empty($files) ? json_encode($files) : null;

        $files = is_array($request->existing_inv_attachment) ? $request->existing_inv_attachment : null;

        if (!empty($request->inv_attachment)) {
            if ($internalAudit->inv_attachment) {
                $existingFiles = json_decode($internalAudit->inv_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('inv_attachment')) {
                foreach ($request->file('inv_attachment') as $file) {
                    $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        $internalAudit->inv_attachment = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->inv_attachment)) {
        //     if ($internalAudit->inv_attachment) {
        //         $existingFiles = json_decode($internalAudit->inv_attachment, true); // Convert to associative array
        //         if (is_array($existingFiles)) {
        //             $files = $existingFiles;
        //         }
        //     }

        //     if ($request->hasfile('inv_attachment')) {
        //         foreach ($request->file('inv_attachment') as $file) {
        //             $name = $request->name . 'inv_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }
        // }

        // If no files are attached, set to null
        // $internalAudit->inv_attachment = !empty($files) ? json_encode($files) : null;

        // if (!empty($request->file_attachment)) {
        //     $files = [];
        //     if ($request->hasfile('file_attachment')) {

        //         foreach ($request->file('file_attachment') as $file) {
        //             $name = $request->name . 'file_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $internalAudit->file_attachment = json_encode($files);
        // }


        // $files = is_array($request->existing_file_attachment) ? $request->existing_file_attachment : null;


        $files = is_array($request->existing_file_attachment) ? $request->existing_file_attachment : null;

        if (!empty($request->file_attachment)) {
            if ($internalAudit->file_attachment) {
                $existingFiles = json_decode($internalAudit->file_attachment, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('file_attachment')) {
                foreach ($request->file('file_attachment') as $file) {
                    $name = $request->name . 'file_attachment' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        // If no files are attached, set to null
        $internalAudit->file_attachment = !empty($files) ? json_encode(array_values($files)) : null;





        // if (!empty($request->Audit_file)) {
        //     $files = [];
        //     if ($request->hasfile('Audit_file')) {
        //         foreach ($request->file('Audit_file') as $file) {
        //             $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $internalAudit->Audit_file = json_encode($files);
        // }
        $files = is_array($request->existing_Audit_file) ? $request->existing_Audit_file : null;

        if (!empty($request->Audit_file)) {
            if ($internalAudit->Audit_file) {
                $existingFiles = json_decode($internalAudit->Audit_file, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('Audit_file')) {
                foreach ($request->file('Audit_file') as $file) {
                    $name = $request->name . 'Audit_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }

        //  If no files are attached, set to null
        $internalAudit->Audit_file = !empty($files) ? json_encode(array_values($files)) : null;

        // if (!empty($request->report_file)) {
        //     $files = [];
        //     if ($request->hasfile('report_file')) {
        //         foreach ($request->file('report_file') as $file) {
        //             $name = $request->name . 'report_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $internalAudit->report_file = json_encode($files);
        // }

        $files = is_array($request->existing_report_file) ? $request->existing_report_file : null;

        if (!empty($request->report_file)) {
            if ($internalAudit->report_file) {
                $existingFiles = json_decode($internalAudit->report_file, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('report_file')) {
                foreach ($request->file('report_file') as $file) {
                    $name = $request->name . 'report_file' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        //  If no files are attached, set to null
        $internalAudit->report_file = !empty($files) ? json_encode(array_values($files)) : null;


        // if (!empty($request->myfile)) {
        //     $files = [];
        //     if ($request->hasfile('myfile')) {
        //         foreach ($request->file('myfile') as $file) {
        //             $name = $request->name . 'myfile' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
        //             $file->move('upload/', $name);
        //             $files[] = $name;
        //         }
        //     }


        //     $internalAudit->myfile = json_encode($files);
        // }
        $files = is_array($request->existing_myfile) ? $request->existing_myfile : null;

        if (!empty($request->myfile)) {
            if ($internalAudit->myfile) {
                $existingFiles = json_decode($internalAudit->myfile, true); // Convert to associative array
                if (is_array($existingFiles)) {
                    $files = array_values($existingFiles);
                }
            }

            if ($request->hasfile('myfile')) {
                foreach ($request->file('myfile') as $file) {
                    $name = $request->name . 'myfile' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
        }
        //  If no files are attached, set to null
        $internalAudit->myfile = !empty($files) ? json_encode(array_values($files)) : null;

        $internalAudit->update();
        // dd($internalAudit->Auditee);

        $data3 = ExternalAuditGridSupplier::where('audit_id', $internalAudit->id)->where('type', 'external_audit')->first();
        // dd($data3);
        if ($data3) {

            $fields = [
                'audit' => 'area_of_audit',
                'scheduled_start_date' => 'start_date',
                'scheduled_start_time' => 'start_time',
                'scheduled_end_date' => 'end_date',
                'scheduled_end_time' => 'end_time',
                'auditor' => 'auditor',
                'auditee' => 'auditee',
                'remark' => 'remark'
            ];

            foreach ($fields as $requestField => $modelField) {
                if (!empty($request->$requestField)) {
                    $data3->$modelField = serialize($request->$requestField);
                }
            }

            $data3->update();
            // dd($data3);
        } else {
            // ===================
        }

        // dd($data3);

        $data4 = ExternalAuditGridSupplier::where('audit_id', $internalAudit->id)->where('type', 'Observation_field_Auditee')->first();

        if (!empty($request->observation_id)) {
            $data4->observation_id = serialize($request->observation_id);
        }
        if (!empty($request->date)) {
            $data4->date = serialize($request->date);
        }
        if (!empty($request->auditorG)) {
            $data4->auditor = serialize($request->auditorG);
        }
        if (!empty($request->auditeeG)) {
            $data4->auditee = serialize($request->auditeeG);
        }
        if (!empty($request->observation_description)) {
            $data4->observation_description = serialize($request->observation_description);
        }
        if (!empty($request->severity_level)) {
            $data4->severity_level = serialize($request->severity_level);
        }
        if (!empty($request->area)) {
            $data4->area = serialize($request->area);
        }
        if (!empty($request->observation_category)) {
            $data4->observation_category = serialize($request->observation_category);
        }
        if (!empty($request->capa_required)) {
            $data4->capa_required = serialize($request->capa_required);
        }
        if (!empty($request->auditee_response)) {
            $data4->auditee_response = serialize($request->auditee_response);
        }
        if (!empty($request->auditor_review_on_response)) {
            $data4->auditor_review_on_response = serialize($request->auditor_review_on_response);
        }
        if (!empty($request->qa_comment)) {
            $data4->qa_comment = serialize($request->qa_comment);
        }
        if (!empty($request->capa_details)) {
            $data4->capa_details = serialize($request->capa_details);
        }
        if (!empty($request->capa_due_date)) {
            $data4->capa_due_date = serialize($request->capa_due_date);
        }
        if (!empty($request->capa_owner)) {
            $data4->capa_owner = serialize($request->capa_owner);
        }
        if (!empty($request->action_taken)) {
            $data4->action_taken = serialize($request->action_taken);
        }
        if (!empty($request->capa_completion_date)) {
            $data4->capa_completion_date = serialize($request->capa_completion_date);
        }
        if (!empty($request->status_Observation)) {
            $data4->status = serialize($request->status_Observation);
        }
        if (!empty($request->remark_observation)) {
            $data4->remark = serialize($request->remark_observation);
        }
        $data4->update();
        if ($lastDocument->date != $internalAudit->date || !empty($request->date_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', '  Date of Initiator')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = '  Date of Initiator';
            $history->previous = $lastDocument->date;
            $history->current = $internalAudit->date;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }
        $previousAssignedToName = User::find($lastDocument->assign_to);
        $currentAssignedToName = User::find($internalAudit['assign_to']);


        if ($lastDocument->assign_to != $internalAudit->assign_to || !empty($request->assign_to_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', '  Assigned To')
                ->exists();
            // dd($existingHistory);
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = '  Assigned To';
            $history->previous = $previousAssignedToName ? $previousAssignedToName->name : 'Null';
            $history->current = $currentAssignedToName ? $currentAssignedToName->name : 'Null';
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }



            $history->save();
            // dd($existingHistory);
        }



        if ($lastDocument->initiator_group_code != $request->initiator_group_code) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Initiator Group Code')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Initiator Group Code';
            $history->previous = $lastDocument->initiator_group_code;
            $history->current = $request->initiator_group_code;
            $history->comment = "";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->initiator_group_code != $request->initiator_group_code) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Initiator Group')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Initiator Group';
            $history->previous = Helpers::getInitiatorGroupFullName($lastDocument->initiator_group_code);
            $history->current = Helpers::getInitiatorGroupFullName($request->initiator_group_code);
            $history->comment = "";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }




        // $history->change_from = $lastDocument->status;
        // //  $history->action_name = "Update";

        // if ($existingHistory) {
        //     $history->action_name = "Update";
        // } else {
        //         if ($lastDocument->short_description != $internalAudit->short_description || !empty($request->short_description_comment)) {

        //             $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
        //                 ->where('activity_type', 'Short Description')
        //                 ->exists();

        //             $history = new ExternalAuditTrailSupplier();
        //             $history->supplier_id = $id;
        //             $history->activity_type = 'Short Description';
        //             $history->previous = $lastDocument->short_description;
        //             $history->current = $internalAudit->short_description;
        //             $history->comment = $request->date_comment;
        //             $history->user_id = Auth::user()->id;
        //             $history->user_name = Auth::user()->name;
        //             $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //             $history->origin_state = $lastDocument->status;
        //             $history->change_to = "Not Applicable";
        //            $history->action_name = "New";
        //     }

        //     $history->save();
        // }


        if ($lastDocument->short_description != $internalAudit->short_description || !empty($request->short_description_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Short Description')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastDocument->short_description;
            $history->current = $internalAudit->short_description;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }


        if ($lastDocument->refrence_record != $internalAudit->refrence_record || !empty($request->refrence_record_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Reference Record')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Reference Record';
            $history->previous = $lastDocument->refrence_record;
            $history->current = $internalAudit->refrence_record;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }

        // if ($lastDocument->refrence_record != $internalAudit->refrence_record || !empty($request->refrence_record)) {
        //     $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $internalAudit->id)
        //     ->where('activity_type', 'Reference Records')
        //     ->exists();
        //     $history = new ExternalAuditTrailSupplier();
        //     $history->supplier_id = $id;
        //     $history->activity_type = 'Reference Records';
        //     $history->previous = $lastDocument->refrence_record;
        //     $history->current = $internalAudit->refrence_record;
        //     $history->comment = $request->capa_related_record_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = 'Not Applicable';
        //     $history->change_from = $lastDocument->status;
        //     if (is_null($lastDocument->refrence_record) || $lastDocument->refrence_record === '') {
        //         $history->action_name = 'New';
        //     } else {
        //         $history->action_name = 'Update';
        //     }            $history->save();
        // }




        if ($lastDocument->external_agencies != $internalAudit->external_agencies || !empty($request->external_agencies_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier Agencies')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier Agencies';
            $history->previous = $lastDocument->external_agencies;
            $history->current = $internalAudit->external_agencies;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }


        if ($lastDocument->initiated_if_other != $internalAudit->initiated_if_other || !empty($request->initiated_if_other_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Others')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Others';
            $history->previous = $lastDocument->initiated_if_other;
            $history->current = $internalAudit->initiated_if_other;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }

        if ($lastDocument->audit_type != $internalAudit->audit_type) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Type of Audit')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Type of Audit';
            $history->previous = $lastDocument->audit_type;
            $history->current = $internalAudit->audit_type;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            //$history->action_name = "Update";
            // $history->action_name = "Null";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->if_other != $internalAudit->if_other || !empty($request->if_other_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'If Other')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'If Other';
            $history->previous = $lastDocument->if_other;
            $history->current = $internalAudit->if_other;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->initial_comments != $internalAudit->initial_comments || !empty($request->initial_comments_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Description')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Description';
            $history->previous = $lastDocument->initial_comments;
            $history->current = $internalAudit->initial_comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        $lastStartDate = !is_null($lastDocument->start_date) ? Carbon::parse($lastDocument->start_date) : null;
        $internalStartDate = !is_null($internalAudit->start_date) ? Carbon::parse($internalAudit->start_date) : null;

        if ((!is_null($lastStartDate) && !is_null($internalStartDate) && $lastStartDate->format('d-M-Y') != $internalStartDate->format('d-M-Y')) || !empty($request->start_date_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Schedule Start Date')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Schedule Start Date';
            $history->previous = !is_null($lastStartDate) ? $lastStartDate->format('d-M-Y') : 'Null';
            $history->current = !is_null($internalStartDate) ? $internalStartDate->format('d-M-Y') : 'Null';
            $history->comment = $request->start_date_comment ?? ''; // Ensure to use the correct comment field
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";


            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        $lastEndDate = !is_null($lastDocument->end_date) ? Carbon::parse($lastDocument->end_date) : null;
        $internalEndDate = !is_null($internalAudit->end_date) ? Carbon::parse($internalAudit->end_date) : null;

        if ((!is_null($lastEndDate) && !is_null($internalEndDate) && $lastEndDate->format('d-M-Y') != $internalEndDate->format('d-M-Y')) || !empty($request->end_date_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Schedule End Date')
                ->exists();


            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Schedule End Date';
            $history->previous = !is_null($lastEndDate) ? $lastEndDate->format('d-M-Y') : 'Null';
            $history->current = !is_null($internalEndDate) ? $internalEndDate->format('d-M-Y') : 'Null';
            $history->comment = $request->end_date_comment ?? ''; // Ensure to use the correct comment field
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";
            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            // Determine the action_name based on existing history
            // if ($existingHistory) {
            //     // Check if this specific change already exists to avoid duplicate 'New'
            //     $existingSpecificHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
            //         ->where('activity_type', 'Audit Schedule End Date')
            //         ->where('previous', $history->previous)
            //         ->where('current', $history->current)
            //         ->exists();

            //     $history->action_name = $existingSpecificHistory ? "Update" : "New";
            // } else {
            //     $history->action_name = "New";
            // }
            $history->save();
        }
        if ($lastDocument->audit_agenda != $internalAudit->audit_agenda || !empty($request->audit_agenda_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Agenda')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Agenda';
            $history->previous = $lastDocument->audit_agenda;
            $history->current = $internalAudit->audit_agenda;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }

        if ($lastDocument->others != $internalAudit->others || !empty($request->others_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier Agencies Other')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier Agencies Other';
            $history->previous = $lastDocument->others;
            $history->current = $internalAudit->others;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            //  $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }

        if ($lastDocument->material_name != $internalAudit->material_name || !empty($request->material_name_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Product/Material Name')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Product/Material Name';
            $history->previous = $lastDocument->material_name;
            $history->current = $internalAudit->material_name;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        $previousleadauditor = User::find($lastDocument->lead_auditor);
        $currentleadauditor = User::find($internalAudit['lead_auditor']);
        if ($lastDocument->lead_auditor != $internalAudit->lead_auditor || !empty($request->lead_auditor_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Lead Auditor')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Lead Auditor';
            $history->previous = $previousleadauditor ? $previousleadauditor->name : 'Null';
            $history->current = $currentleadauditor ? $currentleadauditor->name : 'Null';
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Audit_team != $internalAudit->Audit_team || !empty($request->Audit_team_comment)) {
            $previousAuditTeamIds = explode(',', $lastDocument->Audit_team);
            $currentAuditTeamIds = explode(',', $internalAudit->Audit_team);

            $previousAuditTeamNames = User::whereIn('id', $previousAuditTeamIds)->pluck('name')->toArray();
            $currentAuditTeamNames = User::whereIn('id', $currentAuditTeamIds)->pluck('name')->toArray();

            $previousAuditTeam = implode(', ', $previousAuditTeamNames);
            $currentAuditTeam = implode(', ', $currentAuditTeamNames);

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Team')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Team';
            $history->previous = $previousAuditTeam;
            $history->current = $currentAuditTeam;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $existingHistory ? "Update" : "New";

            $history->save();
        }

        if ($lastDocument->Auditee != $internalAudit->Auditee) {
            $previousAuditTeamIds = explode(',', $lastDocument->Auditee);
            $currentAuditTeamIds = explode(',', $internalAudit->Auditee);
            // dd($previousAuditTeamIds,$currentAuditTeamIds);
            $previousAuditTeamNames = User::whereIn('id', $previousAuditTeamIds)->pluck('name')->toArray();
            $currentAuditTeamNames = User::whereIn('id', $currentAuditTeamIds)->pluck('name')->toArray();
            // dd($currentAuditTeamNames);

            $previousAuditTeam = implode(', ', $previousAuditTeamNames);
            $currentAuditTeam = implode(', ', $currentAuditTeamNames);

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Auditee')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Auditee';
            $history->previous = $previousAuditTeam;
            $history->current = $currentAuditTeam;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = $existingHistory ? "Update" : "New";

            $history->save();
        }



        // $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
        //     ->where('activity_type', 'Auditee')
        //     ->exists();

        // $history = new ExternalAuditTrailSupplier();
        // $history->supplier_id = $id;
        // $history->activity_type = 'Auditee';
        // $history->previous =  $lastDocument->Auditee;
        // $history->current = $internalAudit->Auditee;
        // $history->comment = $request->Auditee_comment;
        // $history->user_id = Auth::user()->id;
        // $history->user_name = Auth::user()->name;
        // $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        // $history->origin_state = $lastDocument->status;
        // $history->change_to = "Null";
        // $history->change_from = $lastDocument->status;
        // $history->action_name = $existingHistory ? "Update" : "New";

        // $history->save();

        if ($lastDocument->Auditor_Details != $internalAudit->Auditor_Details || !empty($request->Auditor_Details_comment)) {


            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier Auditor Details')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier Auditor Details';
            $history->previous = $lastDocument->Auditor_Details;
            $history->current = $internalAudit->Auditor_Details;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->Comments != $internalAudit->Comments || !empty($request->Comments_comment)) {


            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Comments')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastDocument->Comments;
            $history->current = $internalAudit->Comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->Audit_Comments1 != $internalAudit->Audit_Comments1 || !empty($request->Audit_Comments1_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Comments')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Comments';
            $history->previous = $lastDocument->Audit_Comments1;
            $history->current = $internalAudit->Audit_Comments1;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->Remarks != $internalAudit->Remarks || !empty($request->Remarks_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Remarks')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Remarks';
            $history->previous = $lastDocument->Remarks;
            $history->current = $internalAudit->Remarks;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }


        if ($lastDocument->Audit_Comments2 != $internalAudit->Audit_Comments2 || !empty($request->Audit_Comments2_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Comments.')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Comments.';
            $history->previous = $lastDocument->Audit_Comments2;
            $history->current = $internalAudit->Audit_Comments2;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";


            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->inv_attachment != $internalAudit->inv_attachment || !empty($request->inv_attachment_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Initial Attachment')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Initial Attachment';
            $history->previous = $lastDocument->inv_attachment;
            $history->current = $internalAudit->inv_attachment;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->file_attachment != $internalAudit->file_attachment || !empty($request->file_attachment_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'File Attachment')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'File Attachment';
            $history->previous = $lastDocument->file_attachment;
            $history->current = $internalAudit->file_attachment;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->Audit_file != $internalAudit->Audit_file || !empty($request->Audit_file_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Attachments')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Attachments';
            $history->previous = $lastDocument->Audit_file;
            $history->current = $internalAudit->Audit_file;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        if ($lastDocument->report_file != $internalAudit->report_file || !empty($request->report_file_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Report Attachments')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Report Attachments';
            $history->previous = $lastDocument->report_file;
            $history->current = $internalAudit->report_file;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";


            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }
        if ($lastDocument->myfile != $internalAudit->myfile || !empty($request->myfile_comment)) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Attachment')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Attachment';
            $history->previous = $lastDocument->myfile;
            $history->current = $internalAudit->myfile;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // if ($lastDocument->due_date != $internalAudit->due_date || !empty($request->due_date_comment)) {

        //     $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
        //     ->where('activity_type', 'Due Date')
        //     ->exists();
        //     $history = new ExternalAuditTrailSupplier();
        //     $history->supplier_id = $id;
        //     $history->activity_type = 'Due Date';
        //     $history->previous = $lastDocument->due_date;
        //     $history->current = $internalAudit->due_date;
        //     $history->comment = $request->date_comment;
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $lastDocument->status;
        //     $history->change_to = "Null";
        //     $history->change_from = $lastDocument->status;
        //     $history->action_name = "Update";


        //     if ($existingHistory) {
        //         $history->action_name = "Update";
        //     } else {
        //         $history->action_name = "New";
        //     }
        //     $history->save();
        // }

        $lastAuditStartDate = !is_null($lastDocument->audit_start_date) ? Carbon::parse($lastDocument->audit_start_date) : null;
        $requestAuditStartDate = !is_null($request->audit_start_date) ? Carbon::parse($request->audit_start_date) : null;

        // if (!empty($request->audit_start_date) != $lastDocument->audit_start_date) {


        //     $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
        //     ->where('activity_type', 'Audit Start Date')
        //     ->exists();
        if ((!is_null($lastAuditStartDate) && !is_null($requestAuditStartDate) && $lastAuditStartDate->format('d-M-Y') != $requestAuditStartDate->format('d-M-Y')) || !empty($request->start_date_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Start Date')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Start Date';
            $history->previous = !is_null($lastAuditStartDate) ? $lastAuditStartDate->format('d-M-Y') : 'Null';
            $history->current = !is_null($requestAuditStartDate) ? $requestAuditStartDate->format('d-M-Y') : 'Null';

            $history->comment = $request->audit_start_date_comment ?? ''; // Ensure to use the correct comment field
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }


        $lastAuditEndDate = !is_null($lastDocument->audit_end_date) ? Carbon::parse($lastDocument->audit_end_date) : null;
        $requestAuditEndDate = !is_null($request->audit_end_date) ? Carbon::parse($request->audit_end_date) : null;

        if ((!is_null($lastAuditEndDate) && !is_null($requestAuditStartDate) && $lastAuditEndDate->format('d-M-Y') !=  $requestAuditEndDate->format('d-M-Y')) || !empty($request->start_date_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit End Date')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit End Date';
            $history->previous = !is_null($lastAuditEndDate) ? $lastAuditEndDate->format('d-M-Y') : 'Null';
            $history->current = !is_null($requestAuditEndDate) ? $requestAuditEndDate->format('d-M-Y') : 'Null';
            $history->comment = $request->audit_end_date_comment ?? ''; // Ensure to use the correct comment field
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastDocument->status;
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }


        // update Audit Trail Supplier

        // Check and record changes for each specified field


        // Severity Level
        if ($lastDocument->severity_level != $internalAudit->severity_level) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Severity Level')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Severity Level';
            $history->previous = $lastDocument->severity_level;
            $history->current = $internalAudit->severity_level;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // Initiated Through
        if ($lastDocument->initiated_through != $internalAudit->initiated_through) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Initiated Through')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Initiated Through';
            $history->previous = $lastDocument->initiated_through;
            $history->current = $internalAudit->initiated_through;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }


        // IF Comments
        if ($lastDocument->if_comments != $internalAudit->if_comments || !empty($request->if_comments_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Comments(If Any)')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Comments(If Any)';
            $history->previous = $lastDocument->if_comments;
            $history->current = $internalAudit->if_comments;
            $history->comment = $request->if_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";


            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }




        // External Auditing Agency
        if ($lastDocument->External_Auditing_Agency != $internalAudit->External_Auditing_Agency) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier Auditing Agency')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier Auditing Agency';
            $history->previous = $lastDocument->External_Auditing_Agency;
            $history->current = $internalAudit->External_Auditing_Agency;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // Relevant Guidelines
        if ($lastDocument->Relevant_Guidelines != $internalAudit->Relevant_Guidelines) {

            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Relevant Guidelines / Industry Standards')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Relevant Guidelines / Industry Standards';
            $history->previous = $lastDocument->Relevant_Guidelines;
            $history->current = $internalAudit->Relevant_Guidelines;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            ///$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // QA Comments

        if ($lastDocument->QA_Comments != $internalAudit->QA_Comments) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'QA Comments')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'QA Comments';
            $history->previous = $auditCategoryMap[$lastDocument->QA_Comments] ?? $lastDocument->QA_Comments;
            $history->current = $auditCategoryMap[$internalAudit->QA_Comments] ?? $internalAudit->QA_Comments;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }


        // Audit Category
        // Define the mapping array
        $auditCategoryMap = [
            '1' => 'Internal Audit/Self Inspection',
            '2' => 'Supplier Audit',
            '3' => 'Regulatory Audit',
            '4' => 'Consultant Audit',
        ];

        if ($lastDocument->Audit_Category != $internalAudit->Audit_Category) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Audit Category')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Audit Category';
            $history->previous = $auditCategoryMap[$lastDocument->Audit_Category] ?? $lastDocument->Audit_Category;
            $history->current = $auditCategoryMap[$internalAudit->Audit_Category] ?? $internalAudit->Audit_Category;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }

            $history->save();
        }


        // Guideline Attachment
        if ($lastDocument->file_attachment_guideline != $internalAudit->file_attachment_guideline || !empty($request->file_attachment_guideline_comments)) {


            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Guideline Attachment')
                ->exists();
            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Guideline Attachment';
            $history->previous = $lastDocument->file_attachment_guideline;
            $history->current = $internalAudit->file_attachment_guideline;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // Supplier Details
        if ($lastDocument->Supplier_Details != $internalAudit->Supplier_Details  || !empty($request->Supplier_Details_comment)) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier/Vendor/Manufacturer Details')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Details';
            $history->previous = $lastDocument->Supplier_Details;
            $history->current = $internalAudit->Supplier_Details;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // Supplier Site
        if ($lastDocument->Supplier_Site != $internalAudit->Supplier_Site) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Supplier/Vendor/Manufacturer Site')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Supplier/Vendor/Manufacturer Site';
            $history->previous = $lastDocument->Supplier_Site;
            $history->current = $internalAudit->Supplier_Site;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            // $history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // Due Date Extension
        if ($lastDocument->due_date_extension != $internalAudit->due_date_extension) {
            $existingHistory = ExternalAuditTrailSupplier::where('supplier_id', $id)
                ->where('activity_type', 'Due Date Extension Justification')
                ->exists();

            $history = new ExternalAuditTrailSupplier();
            $history->supplier_id = $id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = $lastDocument->due_date_extension;
            $history->current = $internalAudit->due_date_extension;
            $history->comment = $request->date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastDocument->status;
            $history->change_from = $lastDocument->status;
            $history->change_to = "Not Applicable";
            //$history->action_name = "Update";

            if ($existingHistory) {
                $history->action_name = "Update";
            } else {
                $history->action_name = "New";
            }
            $history->save();
        }

        // update  Audit Trail Supplier


        toastr()->success("Record is Update Successfully");
        return back();
    }


    public function SupplierAuditStateChange(Request $request, $id)
    {
        // dd("test");
        if ($request->username == Auth::user()->Username && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = SupplierAudit::find($id);
            $lastDocument = SupplierAudit::find($id);
            $internalAudit = SupplierAudit::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "2";
                $changeControl->status = "Audit Preparation";
                $changeControl->audit_schedule_by = Auth::user()->name;
                // $changeControl->audit_schedule_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_schedule_on =  Carbon::now('Asia/Kolkata')->format('d-M-Y H:i:s T') . 
                                        ' | GMT: ' . Carbon::now('UTC')->format('d-M-Y H:i:s T');
                $changeControl->comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;

                $history->activity_type = 'Schedule Audit by,Schedule Audit on ';
                if (is_null($lastDocument->audit_schedule_by) || $lastDocument->audit_schedule_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_schedule_by . ' , ' . $lastDocument->audit_schedule_on;
                }
                $history->current = $changeControl->audit_schedule_by . ' , ' .  $changeControl->audit_schedule_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Schedule";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Audit Preparation';
                $history->action = 'schedule audit';
                if (is_null($lastDocument->audit_schedule_by) || $lastDocument->audit_schedule_ons === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupplierAuditorDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Audit Preparation";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supplier Auditor";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    $email = Helpers::getSupplierAuditorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl,'site'=>'SA','history' => 'Submit', 'process' => 'SCAR', 'comment' => $changeControl->comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl) {
                                 $message->to($email)
                                 ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($record_number, 4, '0', STR_PAD_LEFT) . " - Activity: Submit Performed"); }
                                );

                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "3";
                $changeControl->status = "Pending Audit";
                $changeControl->audit_preparation_completed_by = Auth::user()->name;
                // $changeControl->audit_preparation_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_preparation_completed_on =  Carbon::now('Asia/Kolkata')->format('d-M-Y H:i:s T') . 
                                        ' | GMT: ' . Carbon::now('UTC')->format('d-M-Y H:i:s T');
                $changeControl->audit_preparation_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'Complete Audit Preparation By,Complete Audit Preparation On';
                if (is_null($lastDocument->audit_preparation_completed_by) || $lastDocument->audit_preparation_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_preparation_completed_by . ' , ' . $lastDocument->audit_preparation_completed_on;
                }
                $history->current =  $changeControl->audit_preparation_completed_by . ' , ' .  $changeControl->audit_preparation_completed_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Preparation Completed";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Pending Audit';
                $history->action = 'Complete Audit Preparation';
                if (is_null($lastDocument->audit_preparation_completed_by) || $lastDocument->audit_preparation_completed_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "4";
                $changeControl->status = "Pending Response";
                $changeControl->audit_mgr_more_info_reqd_by = Auth::user()->name;
                // $changeControl->audit_mgr_more_info_reqd_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_mgr_more_info_reqd_on =  Carbon::now('Asia/Kolkata')->format('d-M-Y H:i:s T') . 
                                        ' | GMT: ' . Carbon::now('UTC')->format('d-M-Y H:i:s T');
                $changeControl->pending_response_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'Issue Report by,Issue Report on';
                if (is_null($lastDocument->audit_mgr_more_info_reqd_by) || $lastDocument->audit_mgr_more_info_reqd_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_mgr_more_info_reqd_by . ' , ' . $lastDocument->audit_mgr_more_info_reqd_on;
                }
                $history->current =  $changeControl->audit_mgr_more_info_reqd_by . ' , ' .  $changeControl->audit_mgr_more_info_reqd_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Mgr More Info Reqd";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Pending Response';
                $history->action = 'Issue Report';
                if (is_null($lastDocument->audit_mgr_more_info_reqd_by) || $lastDocument->audit_mgr_more_info_reqd_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Pending Response ";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                   
                    $email = Helpers::getAuditeesEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl,'site'=>'SA', 'history' => 'Issue Report ', 'process' => 'SCAR', 'comment' => $changeControl->pending_response_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Issue Report Performed"); }
                                );

                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    
                }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 4) {
                $changeControl->stage = "5";
                $changeControl->status = "CAPA Execution in Progress";
                $changeControl->audit_observation_submitted_by  = Auth::user()->name;
                // $changeControl->audit_observation_submitted_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_observation_submitted_on =  Carbon::now('Asia/Kolkata')->format('d-M-Y H:i:s T') . 
                                        ' | GMT: ' . Carbon::now('UTC')->format('d-M-Y H:i:s T');
                $changeControl->capa_execution_in_progress_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                
                $history->activity_type = 'CAPA  Plan Proposed by,CAPA  Plan Proposed on';
                if (is_null($lastDocument->audit_observation_submitted_by) || $lastDocument->audit_observation_submitted_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_observation_submitted_by   . ' , ' . $lastDocument->audit_observation_submitted_on;
                }
                $history->current = $changeControl->audit_observation_submitted_by   . ' , ' .  $changeControl->audit_observation_submitted_on;
                
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Schedule";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'CAPA Execution in Progress';
                $history->action = 'CAPA  Plan Proposed';
                if (is_null($lastDocument->audit_observation_submitted_by) || $lastDocument->audit_observation_submitted_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }

                $history->save();
                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }

            if ($changeControl->stage == 5) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->audit_lead_more_info_reqd_by = Auth::user()->name;
                // $changeControl->audit_lead_more_info_reqd_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_lead_more_info_reqd_on =  Carbon::now('Asia/Kolkata')->format('d-M-Y H:i:s T') . 
                                        ' | GMT: ' . Carbon::now('UTC')->format('d-M-Y H:i:s T');
                $changeControl->response_feedback_verified_by = Auth::user()->name;
                //$changeControl->response_feedback_verified_on = Carbon::now()->format('d-M-Y');
                $changeControl->comment_closed_done_by_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'All Capa Closed by,All Capa Closed on';
                if (is_null($lastDocument->audit_lead_more_info_reqd_by) || $lastDocument->audit_lead_more_info_reqd_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_lead_more_info_reqd_by . ' , ' . $lastDocument->audit_lead_more_info_reqd_on;
                }
                $history->current =  $changeControl->audit_lead_more_info_reqd_by . ' , ' .  $changeControl->audit_lead_more_info_reqd_on;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Lead More Info Reqd";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Closed Done';
                $history->action = 'All Capa Closed';
                if (is_null($lastDocument->audit_lead_more_info_reqd_by) || $lastDocument->audit_lead_more_info_reqd_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                // $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                // $userIds = collect($list)->pluck('user_id')->toArray();
                // $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                // $userId = $users->pluck('id')->implode(',');
                // if(!empty($users)){
                //     try {
                //         $history = new ExternalAuditTrailSupplier();
                //         $history->supplier_id = $id;
                //         $history->activity_type = "Not Applicable";
                //         $history->previous = "Not Applicable";
                //         $history->current = "Not Applicable";
                //         $history->action = 'Notification';
                //         $history->comment = "";
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //         $history->origin_state = "Not Applicable";
                //         $history->change_to = "Not Applicable";
                //         $history->change_from = "Closed - Done";
                //         $history->stage = "";
                //         $history->action_name = "";
                //         $history->mailUserId = $userId;
                //         $history->role_name = "Auditee";
                //         $history->save(); 
                //     } catch (\Throwable $e) {
                //         \Log::error('Mail failed to send: ' . $e->getMessage());
                //     }
                // }

                // foreach ($list as $u) {
                   
                //     $email = Helpers::getAuditeesEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             Mail::send(
                //                 'mail.view-mail',

                //                 ['data' => $changeControl,'site'=>'SA' ,'history' => 'All Capa Closed ', 'process' => 'SCAR', 'comment' => $changeControl->comment_closed_done_by_comment,'user'=> Auth::user()->name],
                //             //     function ($message) use ($email) {
                //             //         $message->to($email)
                //             //             ->subject("Document is Sent By " . Auth::user()->name);
                //             //     }
                //             // );
                //             function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Capa Closed Performed"); }
                //             );
                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }

                // $list = Helpers::getSupplierAuditorDepartmentList($changeControl->division_id);
                // $userIds = collect($list)->pluck('user_id')->toArray();
                // $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                // $userId = $users->pluck('id')->implode(',');
                // if(!empty($users)){
                //     try {
                //         $history = new ExternalAuditTrailSupplier();
                //         $history->supplier_id = $id;
                //         $history->activity_type = "Not Applicable";
                //         $history->previous = "Not Applicable";
                //         $history->current = "Not Applicable";
                //         $history->action = 'Notification';
                //         $history->comment = "";
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //         $history->origin_state = "Not Applicable";
                //         $history->change_to = "Not Applicable";
                //         $history->change_from = "Closed - Done";
                //         $history->stage = "";
                //         $history->action_name = "";
                //         $history->mailUserId = $userId;
                //         $history->role_name = "Supplier Auditor";
                //         $history->save(); 
                //     } catch (\Throwable $e) {
                //         \Log::error('Mail failed to send: ' . $e->getMessage());
                //     }
                // }

                // foreach ($list as $u) {
                //     // if($u->q_m_s_divisions_id == $supplier->division_id){
                //     $email = Helpers::getSupplierAuditorEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl, 'site'=>'SA' ,'history' => 'All Capa Closed ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_closed_done_by_comment,'user'=> Auth::user()->name],
                           
                //             function ($message) use ($email, $changeControl) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Capa Closed Performed"); }
                //             );
                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }

                // $list = Helpers::getAuditManagerDepartmentList($changeControl->division_id);
                // $userIds = collect($list)->pluck('user_id')->toArray();
                // $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                // $userId = $users->pluck('id')->implode(',');
                // if(!empty($users)){
                //     try {
                //         $history = new ExternalAuditTrailSupplier();
                //         $history->supplier_id = $id;
                //         $history->activity_type = "Not Applicable";
                //         $history->previous = "Not Applicable";
                //         $history->current = "Not Applicable";
                //         $history->action = 'Notification';
                //         $history->comment = "";
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //         $history->origin_state = "Not Applicable";
                //         $history->change_to = "Not Applicable";
                //         $history->change_from = "Closed - Done";
                //         $history->stage = "";
                //         $history->action_name = "";
                //         $history->mailUserId = $userId;
                //         $history->role_name = "Audit Manager";
                //         $history->save(); 
                //     } catch (\Throwable $e) {
                //         \Log::error('Mail failed to send: ' . $e->getMessage());
                //     }
                // }
                // foreach ($list as $u) {
                //     // if($u->q_m_s_divisions_id == $supplier->division_id){
                //     $email = Helpers::getAuditManagerEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'SA' ,'history' => 'All Capa Closed ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_closed_done_by_comment,'user'=> Auth::user()->name],
                //             //     function ($message) use ($email) {
                //             //         $message->to($email)
                //             //             ->subject("Document is Sent By " . Auth::user()->name);
                //             //     }
                //             // );
                //             function ($message) use ($email, $changeControl ) {
                //                 $message->to($email)
                //                 ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: All Capa Closed Performed"); }
                //             );
                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }


                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function SupplierAuditRejectState(Request $request, $id)
    {
        if ($request->username == Auth::user()->Username && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = SupplierAudit::find($id);
            $lastDocument = SupplierAudit::find($id);
            $internalAudit = SupplierAudit::find($id);

            if ($changeControl->stage == 4) {
                $changeControl->stage = "6";
                $changeControl->status = "Closed - Done";
                $changeControl->audit_response_completed_by = Auth::user()->name;
                $changeControl->audit_response_completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->audit_responce_comment = $request->comment;
                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'No CAPA Required By, No CAPA Required On';
                if (is_null($lastDocument->audit_response_completed_by) || $lastDocument->audit_response_completed_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->audit_response_completed_by . ' , ' . $lastDocument->audit_response_completed_on;
                }
                $history->current = $changeControl->audit_response_completed_by . ' , ' .  $changeControl->audit_response_completed_on;
                // $history->activity_type = 'Activity Log';
                // $history->current =$changeControl->audit_response_completed_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "No CAPA Required";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Closed Done';
                if (is_null($lastDocument->audit_response_completed_by) || $lastDocument->audit_response_completed_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();
                $changeControl->update();

                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on = Carbon::now()->format('d-M-Y');
                $changeControl->comment_rejected_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'rejected by, rejected On';
                if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->rejected_by . ' , ' . $lastDocument->rejected_on;
                }
                $history->current =  $changeControl->rejected_by . ' , ' .  $changeControl->rejected_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Rejected";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Opened';
                $history->action = 'Reject';
                if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditManagerDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Opened";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Audit Manager";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getAuditManagerEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl,'site'=>'SA', 'history' => 'Reject ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_rejected_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Reject performed"); }
                                );

                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "1";
                $changeControl->status = "Opened";
                $changeControl->rejected_by = Auth::user()->name;
                $changeControl->rejected_on = Carbon::now()->format('d-M-Y');
                $changeControl->comment_rejected_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->rejected_by . ' , ' . $lastDocument->rejected_on;
                }
                $history->current =  $changeControl->rejected_by . ' , ' .  $changeControl->rejected_on;
                $history->activity_type = 'Reject By,Reject On';
                // $history->current = $changeControl->rejected_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Rejected";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Opened';
                $history->action = 'Reject';
                if (is_null($lastDocument->rejected_by) || $lastDocument->rejected_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getAuditManagerDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Opened";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Audit Manager";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                // foreach ($list as $u) {
                //     // if($u->q_m_s_divisions_id == $supplier->division_id){
                //     $email = Helpers::getAuditManagerEmail($u->user_id);
                //     $userIds = collect($list)->pluck('user_id')->toArray();
                //     $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                //     $userId = $users->pluck('id')->implode(',');
                //     if(!empty($users)){
                //         try {
                //             $history = new ExternalAuditTrailSupplier();
                //             $history->supplier_id = $id;
                //             $history->activity_type = "Not Applicable";
                //             $history->previous = "Not Applicable";
                //             $history->current = "Not Applicable";
                //             $history->action = 'Notification';
                //             $history->comment = "";
                //             $history->user_id = Auth::user()->id;
                //             $history->user_name = Auth::user()->name;
                //             $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //             $history->origin_state = "Not Applicable";
                //             $history->change_to = "Not Applicable";
                //             $history->change_from = "Opened";
                //             $history->stage = "";
                //             $history->action_name = "";
                //             $history->mailUserId = $userId;
                //             $history->role_name = "Audit Manager";
                //             $history->save(); 
                //         } catch (\Throwable $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     if (!empty($email)) {
                //         try {
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl,'site'=>'SA', 'history' => 'Reject ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_rejected_comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl ) {
                //                     $message->to($email)
                //                     ->subject("QMS Notification: Supplier Audit , Record # " . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity:Rejected performed"); }
                //                 );
                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }


                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }

    public function CancelStateSupplierAudit(Request $request, $id)
    {
        if ($request->username == Auth::user()->Username && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = SupplierAudit::find($id);
            $lastDocument = SupplierAudit::find($id);
            $internalAudit = SupplierAudit::find($id);

            if ($changeControl->stage == 1) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $changeControl->capa_execution_in_progress_comment = $request->comment;

                $history = new ExternalAuditTrailSupplier();
                $history->supplier_id = $id;
                $history->activity_type = 'Cancel By, Cancel On';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by  . ' , ' . $lastDocument->cancelled_on;
                }
                $history->current = $changeControl->cancelled_by  . ' , ' .  $changeControl->cancelled_on;

                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->stage = "Audit Schedule";
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Closed Cancelled';
                $history->action = 'Cancel';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by === '') {
                    $history->action_name = 'New';
                } else {
                    $history->action_name = 'Update';
                }
                $history->save();

                $list = Helpers::getSupplierAuditorDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supplier Auditor";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }

                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getSupplierAuditorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site'=>'SA','history' => 'Cancel ', 'process' => 'Supplier Audit', 'comment' => $changeControl->capa_execution_in_progress_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record # " . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel  performed"); }
                                );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }


                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getAuditeesEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site'=>'SA', 'history' => 'Cancel', 'process' => 'Supplier Audit', 'comment' => $changeControl->capa_execution_in_progress_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel Performed"); }
                                );

                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 2) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $changeControl->comment_cancelled_comment = $request->comment;
                $history = new ExternalAuditTrailSupplier();
                $history->Supplier_id = $id;
                $history->activity_type = 'Cancel By, Cancel On';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by  . ' , ' . $lastDocument->cancelled_on;
                }
                $history->current =  $changeControl->cancelled_by  . ' , ' .  $changeControl->cancelled_on;
                //$history->activity_type = 'Activity Log';
                //$history->current = $changeControl->cancelled_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Closed Cancelled';
                $history->action = 'Cancel';
                $history->stage = "Cancelled";
                $history->save();

                $list = Helpers::getSupplierAuditorDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Auditee";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getSupplierAuditorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site'=>'SA', 'history' => 'Cancel Performed', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_cancelled_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record # " . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel performed"); }
                                );

                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                // if(!empty($users)){
                //     try {
                //         $history = new ExternalAuditTrailSupplier();
                //         $history->supplier_id = $id;
                //         $history->activity_type = "Not Applicable";
                //         $history->previous = "Not Applicable";
                //         $history->current = "Not Applicable";
                //         $history->action = 'Notification';
                //         $history->comment = "";
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                //         $history->origin_state = "Not Applicable";
                //         $history->change_to = "Not Applicable";
                //         $history->change_from = "Closed-Cancelled";
                //         $history->stage = "";
                //         $history->action_name = "";
                //         $history->mailUserId = $userId;
                //         $history->role_name = "Auditee";
                //         $history->save(); 
                //     } catch (\Throwable $e) {
                //         \Log::error('Mail failed to send: ' . $e->getMessage());
                //     }
                // }
                // foreach ($list as $u) {
                //     // if($u->q_m_s_divisions_id == $supplier->division_id){
                //     $email = Helpers::getAuditeeEmail($u->user_id);
                //     if (!empty($email)) {
                //         try {
                //             Mail::send(
                //                 'mail.view-mail',
                //                 ['data' => $changeControl],
                //                 ['data' => $changeControl, 'site'=>'SA','history' => 'Cancel ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_cancelled_comment,'user'=> Auth::user()->name],
                //                 function ($message) use ($email, $changeControl ) {
                //                     $message->to($email)
                //                     ->subject("QMS Notification: Supplier Audit , Record #" . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel performed"); }
                //                 );
                //         } catch (\Exception $e) {
                //             \Log::error('Mail failed to send: ' . $e->getMessage());
                //         }
                //     }
                //     // }
                // }

                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
            if ($changeControl->stage == 3) {
                $changeControl->stage = "0";
                $changeControl->status = "Closed-Cancelled";
                $changeControl->cancelled_by = Auth::user()->name;
                $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
                $changeControl->comment_cancelled_comment = $request->comment;
                 $history = new ExternalAuditTrailSupplier();
                $history->Supplier_id = $id;
                $history->activity_type = 'Canceled By, Canceled On';
                if (is_null($lastDocument->cancelled_by) || $lastDocument->cancelled_by  === '') {
                    $history->previous = "";
                } else {
                    $history->previous = $lastDocument->cancelled_by  . ' , ' . $lastDocument->cancelled_on;
                }
                $history->current =  $changeControl->cancelled_by  . ' , ' .  $changeControl->cancelled_on;
                //$history->activity_type = 'Activity Log';
                //$history->current = $changeControl->cancelled_by;
                $history->comment = $request->comment;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $lastDocument->status;
                $history->change_from = $lastDocument->status;
                $history->change_to = 'Closed Cancelled';
                $history->action = 'Cancel';
                $history->stage = "Cancelled";
                $history->save();

                $history->save();

                $list = Helpers::getSupplierAuditorDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supplier Auditor";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getSupplierAuditorEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site'=>'SA','history' => 'Cancel ', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_cancelled_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record # " . str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel performed"); }
                                );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }

                $list = Helpers::getAuditeeDepartmentList($changeControl->division_id);
                $userIds = collect($list)->pluck('user_id')->toArray();
                $users = User::whereIn('id', $userIds)->select('id', 'name', 'email')->get();
                $userId = $users->pluck('id')->implode(',');
                if(!empty($users)){
                    try {
                        $history = new ExternalAuditTrailSupplier();
                        $history->supplier_id = $id;
                        $history->activity_type = "Not Applicable";
                        $history->previous = "Not Applicable";
                        $history->current = "Not Applicable";
                        $history->action = 'Notification';
                        $history->comment = "";
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = "Not Applicable";
                        $history->change_to = "Not Applicable";
                        $history->change_from = "Closed-Cancelled";
                        $history->stage = "";
                        $history->action_name = "";
                        $history->mailUserId = $userId;
                        $history->role_name = "Supplier Auditor";
                        $history->save(); 
                    } catch (\Throwable $e) {
                        \Log::error('Mail failed to send: ' . $e->getMessage());
                    }
                }
                foreach ($list as $u) {
                    // if($u->q_m_s_divisions_id == $supplier->division_id){
                    $email = Helpers::getAuditeesEmail($u->user_id);
                    if (!empty($email)) {
                        try {
                            Mail::send(
                                'mail.view-mail',
                                ['data' => $changeControl, 'site'=>'SA','history' => 'Cancel', 'process' => 'Supplier Audit', 'comment' => $changeControl->comment_cancelled_comment,'user'=> Auth::user()->name],
                                function ($message) use ($email, $changeControl ) {
                                    $message->to($email)
                                    ->subject("QMS Notification: Supplier Audit , Record # " .str_pad($changeControl->record, 4, '0', STR_PAD_LEFT) . " - Activity: Cancel performed"); }
                                );
                        } catch (\Exception $e) {
                            \Log::error('Mail failed to send: ' . $e->getMessage());
                        }
                    }
                    // }
                }


                $changeControl->update();
                toastr()->success('Document Sent');
                return back();
            }
        } else {
            toastr()->error('E-signature Not match');
            return back();
        }
    }


    public function AuditTrialSupplierShow($id)
    {
        $audit = ExternalAuditTrailSupplier::where('supplier_id', $id)
            ->orderByDESC('id')
            ->paginate(5); // Adjust the number as needed for items per page
        $today = Carbon::now()->format('d-m-y');
        $document = SupplierAudit::where('id', $id)->first();
        $document->initiator = User::where('id', $document->initiator_id)->value('name');

        return view('frontend.supplier-Audit.supplierAudit_audit-trail', compact('audit', 'document', 'today'));
    }

    public static function auditReport($id)
    {
        $doc = SupplierAudit::find($id);

        if (!empty($doc)) {
            $doc->originator = User::where('id', $doc->initiator_id)->value('name');
            $data = ExternalAuditTrailSupplier::where('supplier_id', $id)->get();
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $pdf = PDF::loadview('frontend.supplier-Audit.auditReport_Supplier', compact('data', 'doc'))
                ->setOptions([
                    'defaultFont' => 'sans-serif',
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true,
                ]);
            $pdf->setPaper('A4');
            $pdf->render();
            $canvas = $pdf->getDomPDF()->getCanvas();
            $height = $canvas->get_height();
            $width = $canvas->get_width();
            $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');
            $canvas->page_text($width / 4, $height / 2, $doc->status, null, 25, [0, 0, 0], 2, 6, -20);

            return $pdf->stream('Supplier-Audit' . $id . '.pdf');
        }
    }

    public static function singleReport($id)
    {
        $data = SupplierAudit::with('division')->find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $sgrid = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "external_audit")->firstOrCreate();
            $grid_data1 = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "Observation_field_Auditee")->first();
            // dd($data,$sgrid,$grid_data1);
            $pdf = PDF::loadview('frontend.supplier-Audit.supplierauditsinglereport', compact('data', 'sgrid', 'grid_data1'))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);
        $pdf->setPaper('A4');
        $pdf->render();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

        $canvas->page_text(
            $width / 4,
            $height / 2,
            $data->status,
            null,
            25,
            [0, 0, 0],
            2,
            6,
            -20
        );


        $directoryPath = public_path("user/pdf/reg/");
        $filePath = $directoryPath . '/reg' . $id . '.pdf';

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true); // Recursive creation with read/write permissions
        }  

        $pdf->save($filePath);

        return $pdf->stream('Supplier-Audit-SingleReport' . $id . '.pdf');
        }
    }

    public function supplierAuditReportShow($id)
    {
        $data = SupplierAudit::find($id);
        
        return view('frontend.supplier-Audit.supplierauditreportshow', compact('id', 'data'));
    }


    public function child_external_Supplier(Request $request, $id)
    {
        $parent_id = $id;
        $parent_type = "Supplier Audit";
        $record_number = ((RecordNumber::first()->value('counter')) + 1);
        $record_number = str_pad($record_number, 4, '0', STR_PAD_LEFT);
        $division = SupplierAudit::find($id);
        $divisionId = $division->division_id;
        $parent_division_id = SupplierAudit::where('id', $id)->value('division_id');
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('d-M-Y');
        return view('frontend.forms.observation', compact('record_number', 'parent_division_id', 'due_date', 'parent_id', 'parent_type', 'divisionId'));
    }

    public static function supplierAuditActivityLog($id)
    {
        $data = SupplierAudit::with('division')->find($id);
        if (!empty($data)) {
            $data->originator = User::where('id', $data->initiator_id)->value('name');
            $pdf = App::make('dompdf.wrapper');
            $time = Carbon::now();
            $sgrid = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "external_audit")->firstOrCreate();
            $grid_data1 = ExternalAuditGridSupplier::where('audit_id', $id)->where('type', "Observation_field_Auditee")->first();
            // dd($data,$sgrid,$grid_data1);
            $pdf = PDF::loadview('frontend.supplier-Audit.activityLog', compact('data', 'sgrid', 'grid_data1'))
            ->setOptions([
                'defaultFont' => 'sans-serif',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
            ]);
        $pdf->setPaper('A4');
        $pdf->render();
        $canvas = $pdf->getDomPDF()->getCanvas();
        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->page_script('$pdf->set_opacity(0.1,"Multiply");');

        $canvas->page_text(
            $width / 4,
            $height / 2,
            $data->status,
            null,
            25,
            [0, 0, 0],
            2,
            6,
            -20
        );


        $directoryPath = public_path("user/pdf/reg/");
        $filePath = $directoryPath . '/reg' . $id . '.pdf';

        if (!File::isDirectory($directoryPath)) {
            File::makeDirectory($directoryPath, 0755, true, true); // Recursive creation with read/write permissions
        }  

        $pdf->save($filePath);

        return $pdf->stream('Supplier-Audit-Activity-Log' . $id . '.pdf');
        }
    }


    public function supplier_exportcsv(Request $request)
    {
        $query = SupplierAudit::query();
            if (!empty($request->department_auditprogram)) {
                $query->whereIn('Initiator_Group', $request->department_auditprogram);  // Use whereIn for multiple selections
            }
    
            if ($request->division_id_auditprogram) {
                $query->where('division_id', $request->division_id_auditprogram);
            }
    
            if ($request->initiator_id_auditprogram) {
                $query->where('initiator_id', $request->initiator_id_auditprogram);
            }
    
            if ($request->date_fromauditprogram) {
                $dateFrom = Carbon::parse($request->date_fromauditprogram)->startOfDay();
                $query->whereDate('intiation_date', '>=', $dateFrom);
            }
    
            if ($request->date_toauditprogram) {
                $dateTo = Carbon::parse($request->date_toauditprogram)->endOfDay();
                $query->whereDate('intiation_date', '<=', $dateTo);
            }
    
      
      
        $auditprogram = $query->get();
    
        $fileName = 'export.supplier.csv.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
        ];
    
        $columns = [
            'Sr. No.', 'Initiation Date', 'Record No',
            'Division', 'Department Name', 'Short Description.', 'Due Date',
            'Initiator', 'Status'
        ];
    
        $callback = function () use ($auditprogram, $columns) {
            $file = fopen('php://output', 'w');
    
            fputcsv($file, $columns);
    
            if ($auditprogram->isEmpty()) {
                fputcsv($file, ['No records found']);
            } else {
                foreach ($auditprogram as $index => $row) {
                    $data = [
                        $index + 1, 
                        $row->intiation_date ?? 'Not Applicable', 
                        $row->division ? $row->division->name . '/CC/' . date('Y') . '/' . str_pad($row->record, 4, '0', STR_PAD_LEFT) : 'Not Applicable',
                        $row->division ? $row->division->name : 'Not Applicable', 
                        $row->Initiator_Group ?? 'Not Applicable', 
                        $row->short_description ?? 'Not Applicable', 
                        $row->due_date ?? 'Not Applicable', 
                        $row->initiator ? $row->initiator->name : 'Not Applicable', 
                        $row->status ?? 'Not Applicable' 
                         
                    ];
    
                    fputcsv($file, $data);
                }
            }
    
            fclose($file);
        };
    
        return response()->stream($callback, 200, $headers);
    }
    

    public function supplier_exportExcel(Request $request)
    {
        $query = SupplierAudit::query();
        if (!empty($request->department_auditprogram)) {
            $query->whereIn('Initiator_Group', $request->department_auditprogram);  // Use whereIn for multiple selections
        }

        if ($request->division_id_auditprogram) {
            $query->where('division_id', $request->division_id_auditprogram);
        }

        if ($request->initiator_id_auditprogram) {
            $query->where('initiator_id', $request->initiator_id_auditprogram);
        }

        if ($request->date_fromauditprogram) {
            $dateFrom = Carbon::parse($request->date_fromauditprogram)->startOfDay();
            $query->whereDate('intiation_date', '>=', $dateFrom);
        }

        if ($request->date_toauditprogram) {
            $dateTo = Carbon::parse($request->date_toauditprogram)->endOfDay();
            $query->whereDate('intiation_date', '<=', $dateTo);
        }

  
  
         $auditprogram = $query->get();

        $fileName = "export.supplier.csv.xls";
    
        $headers = [
            "Content-Type" => "application/vnd.ms-excel",
            "Content-Disposition" => "attachment; filename=\"$fileName\"",
        ];
    
        $columns = [
            'Sr. No.', 'Initiation Date', 'Record No',
            'Division', 'Department Name', 'Short Description.', 'Due Date',
            'Initiator', 'Status'
        ];
    
        $callback = function () use ($auditprogram, $columns) {
            echo '<table border="1">';
    
            echo '<tr style="font-weight: bold; background-color: # rgb(236,160,53); ; color: #FFFFFF;">';
            foreach ($columns as $column) {
                echo "<th style='padding: 5px;'>" . htmlspecialchars($column) . "</th>";
            }
            echo '</tr>';
    
            if ($auditprogram->isEmpty()) {
                echo '<tr>';
                echo "<td colspan='" . count($columns) . "' style='text-align: center;'>No records found</td>";
                echo '</tr>';
            } else {
                foreach ($auditprogram as $index => $row) {
                    echo '<tr>';
                    echo "<td style='padding: 5px;'>" . ($index + 1) . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->intiation_date ?? 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->division ? $row->division->name . '/CC/' . date('Y') . '/' . str_pad($row->record, 4, '0', STR_PAD_LEFT) : 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars( $row->division ? $row->division->name : 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->Initiator_Group ?? 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->short_description ?? 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->due_date ?? 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->initiator ? $row->initiator->name : 'Not Applicable') . "</td>";
                    echo "<td style='padding: 5px;'>" . htmlspecialchars($row->status ?? 'Not Applicable') . "</td>";
                  
                    echo '</tr>';
                }
            }
    
            echo '</table>';
        };
    
        return response()->stream($callback, 200, $headers);
    }
}
