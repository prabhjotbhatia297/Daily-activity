<?php

namespace App\Http\Controllers;
use App\Models\RecordNumber;
use App\Models\CriticalAction;
use App\Models\CriticalActionAuditTrail;
use App\Models\CriticalActionHistory;
use Carbon\Carbon;
use App\Models\RoleGroup;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CC;
use App\Models\Taskdetails;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\App;
use PDF;







use Illuminate\Http\Request;

class CriticalActionController extends Controller
{
    public function index(){
        
        $old_record = CriticalAction::select('id', 'division_id', 'record')->get();
        $record = ((RecordNumber::first()->value('counter')) + 1);
        $record = str_pad($record, 4, '0', STR_PAD_LEFT);
        $currentDate = Carbon::now();
        $formattedDate = $currentDate->addDays(30);
        $due_date = $formattedDate->format('Y-m-d');
        return view('frontend.critical-action.critical_action_new', compact('due_date', 'record','old_record'));
    }  

    public function store(Request $request)
    {
        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $openState = new CriticalAction();
        $openState->cc_id = $request->ccId;
        $openState->initiator_id = Auth::user()->id;
        $openState->record = DB::table('record_numbers')->value('counter') + 1;
        $openState->parent_id = $request->parent_id;
        $openState->division_code = $request->division_code;
        $openState->parent_type = $request->parent_type;
        $openState->division_id = $request->division_id;
        $openState->parent_id = $request->parent_id;
        $openState->parent_type = $request->parent_type;
        $openState->intiation_date = $request->intiation_date;
        $openState->assign_to = $request->assign_to;
        $openState->due_date = $request->due_date;
         $openState->Reference_Recores1 = implode(',', $request->related_records);
        $openState->short_description = $request->short_description;
        $openState->priority_data = $request->priority_data;
        $openState->title = $request->title;
       // $openState->hod_preson = json_encode($request->hod_preson);
        $openState->hod_preson =  implode(',', $request->hod_preson);
        $openState->dept = $request->dept;
        $openState->description = $request->description;
        $openState->departments = $request->departments;
        $openState->initiatorGroup = $request->initiatorGroup;
        $openState->action_taken = $request->action_taken;
        $openState->start_date = $request->start_date;
        $openState->end_date = $request->end_date;
        $openState->comments = $request->comments;
        $openState->due_date_extension= $request->due_date_extension;
        $openState->qa_comments = $request->qa_comments;
        $openState->status = 'Opened';
        $openState->stage = 1;

        if (!empty($request->file_attach)) {
            $files = [];
            if ($request->hasfile('file_attach')) {
                foreach ($request->file('file_attach') as $file) {
                      
                    $name = $request->name . 'file_attach' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            
            }
            $openState->file_attach = json_encode($files);
        }
        if (!empty($request->Support_doc)) {
            $files = [];
            if ($request->hasfile('Support_doc')) {
                foreach ($request->file('Support_doc') as $file) {
                    
                    $name = $request->name . 'Support_doc' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            
            $openState->Support_doc = json_encode($files);
            }
        }



        $openState->save();

        // if (!empty($openState->short_description)) {
        //     $history = new ActionItemAuditTrail();
        //     $history->aci_id = $openState->id;
        //     $history->activity_type = 'Shor Description';
        //     $history->previous = "NA";
        //     $history->current = $openState->short_description;
        //     $history->comment = "Not Applicable";
        //     $history->user_id = Auth::user()->id;
        //     $history->user_name = Auth::user()->name;
        //     $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        //     $history->origin_state = $openState->status;
        //      $history->change_to = "Opened";
        //         $history->change_from = "Initiator";
        //         $history->action_name = "store";
        //     $history->save();
        // }








        $counter = DB::table('record_numbers')->value('counter');
        $recordNumber = str_pad($counter, 5, '0', STR_PAD_LEFT);
        $newCounter = $counter + 1;
        DB::table('record_numbers')->update(['counter' => $newCounter]);
 
        if (!empty($openState->title)) {
        $history = new CriticalActionAuditTrail();
        $history->cc_id = $openState->id;
        $history->activity_type = 'Title';
        $history->previous = "Null";
        $history->current =  $openState->title;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }

        if (!empty($openState->dept)) {
        $history = new CriticalActionAuditTrail();
        $history->cc_id =  $openState->id;
        $history->activity_type = 'Responsible Department';
        $history->previous = "Null";
        $history->current =  $openState->dept;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        
        if (!empty($openState->Reference_Recores1)) {
        $history = new CriticalActionAuditTrail();
        $history->cc_id =   $openState->id;
        $history->activity_type = 'Action Item Related Records';
        $history->previous = "Null";
        $history->current =  $openState->Reference_Recores1;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->save();
        }
        
          
        if (!empty($openState->short_description)) {
        $history = new CriticalActionAuditTrail();
        $history->cc_id =   $openState->id;
        $history->activity_type = 'Short Description';
        $history->previous = "Null";
        $history->current =  $openState->short_description;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->change_to = "Opened";
         $history->change_from = "Initiator";
         $history->action_name = "store";

        $history->save();
        }
        
        if (!empty($openState->priority_data)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'priority Data';
            $history->previous = "Null";
            $history->current =  $openState->priority_data;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
             $history->change_from = "Initiator";
             $history->action_name = "store";
    
            $history->save();
            }

        if (!empty($openState->initiatorGroup)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'Inititator Group';
            $history->previous = "Null";
            $history->current =  $openState->initiatorGroup;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
            }
            
          
        if (!empty($openState->assign_to)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'Assigned To';
            $history->previous = "Null";
            $history->current =  $openState->assign_to;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
            }
        
            if (!empty($openState->description)) {
                $history = new CriticalActionAuditTrail();
                $history->cc_id =   $openState->id;
                $history->activity_type = 'Description';
                $history->previous = "Null";
                $history->current =  $openState->description;
                $history->comment = "NA";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $openState->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
       
                $history->save();
                }
            
                if (!empty($openState->hod_preson)) {
                    $history = new CriticalActionAuditTrail();
                    $history->cc_id =   $openState->id;
                    $history->activity_type = 'HOD Persons';
                    $history->previous = "Null";
                    $history->current =  $openState->hod_preson;
                    $history->comment = "NA";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $openState->status;
                    $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
           
                    $history->save();
                    }
                 if (!empty($openState->action_taken)) {
                    $history = new CriticalActionAuditTrail();
                    $history->cc_id =   $openState->id;
                    $history->activity_type = 'Action Taken';
                    $history->previous = "Null";
                    $history->current =  $openState->action_taken;
                    $history->comment = "NA";
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                    $history->origin_state = $openState->status;
                    $history->change_to = "Opened";
                    $history->change_from = "Initiator";
                    $history->action_name = "store";
           
                    $history->save();
               }
               if (!empty($openState->start_date)) {
                $history = new CriticalActionAuditTrail();
                $history->cc_id =   $openState->id;
                $history->activity_type = 'Actual Start Date';
                $history->previous = "Null";
                $history->current =  $openState->start_date;
                $history->comment = "NA";
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                $history->origin_state = $openState->status;
                $history->change_to = "Opened";
                $history->change_from = "Initiator";
                $history->action_name = "store";
       
                $history->save();
           }
           if (!empty($openState->end_date)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'Actual End Date';
            $history->previous = "Null";
            $history->current =  $openState->end_date;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
       }
       if (!empty($openState->comments)) {
        $history = new CriticalActionAuditTrail();
        $history->cc_id =   $openState->id;
        $history->activity_type = 'Comments';
        $history->previous = "Null";
        $history->current =  $openState->comments;
        $history->comment = "NA";
        $history->user_id = Auth::user()->id;
        $history->user_name = Auth::user()->name;
        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
        $history->origin_state = $openState->status;
        $history->change_to = "Opened";
        $history->change_from = "Initiator";
        $history->action_name = "store";

        $history->save();
   }
        if (!empty($openState->qa_comments)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = "Null";
            $history->current =  $openState->qa_comments;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
        }

        if (!empty($openState->due_date_extension)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'Due Date Extension Justification';
            $history->previous = "Null";
            $history->current =  $openState->due_date_extension;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
        }

        if (!empty($openState->file_attach)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'File Attachments';
            $history->previous = "Null";
            $history->current =  $openState->file_attach;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
        }
        if (!empty($openState->Support_doc)) {
            $history = new CriticalActionAuditTrail();
            $history->cc_id =   $openState->id;
            $history->activity_type = 'Supporting Documents';
            $history->previous = "Null";
            $history->current =  $openState->Support_doc;
            $history->comment = "NA";
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $openState->status;
            $history->change_to = "Opened";
            $history->change_from = "Initiator";
            $history->action_name = "store";
   
            $history->save();
        }
   
   
                             
       
        toastr()->success('Document created');
        return redirect('rcms/qms-dashboard');
    }

    public function show($id)
    {
        $old_record = CriticalAction::select('id', 'division_id', 'record')->get();
        $data = CriticalAction::find($id);
        $data->record = str_pad($data->record, 4, '0', STR_PAD_LEFT);
        return view('frontend.critical-action.critical_action_view', compact('data','old_record'));
    }

    public function update(Request $request, $id)
    {

        if (!$request->short_description) {
            toastr()->error("Short description is required");
            return redirect()->back();
        }
        $lastopenState = CriticalAction::find($id);
        $openState = CriticalAction::find($id);
        // $openState->related_records = $request->related_records;
        $openState->Reference_Recores1 = implode(',', $request->related_records);
        $openState->description = $request->description;
        $openState->title = $request->title;
        //$openState->hod_preson = json_encode($request->hod_preson);
        $openState->hod_preson =  implode(',', $request->hod_preson);
        // $openState->hod_preson = $request->hod_preson;
        $openState->dept = $request->dept;
        $openState->initiatorGroup = $request->initiatorGroup;
        $openState->action_taken = $request->action_taken;
        $openState->start_date = $request->start_date;
        $openState->end_date = $request->end_date;
        $openState->comments = $request->comments;
        $openState->qa_comments = $request->qa_comments;
        $openState->due_date_extension= $request->due_date_extension;
        $openState->assign_to = $request->assign_to;
        $openState->departments = $request->departments;

        $openState->short_description = $request->short_description;
        $openState->priority_data = $request->priority_data;



        // $openState->status = 'Opened';
        // $openState->stage = 1;
             $files = [];
            if ($request->hasfile('file_attach')) {
                foreach ($request->file('file_attach') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {  
                    $name = $request->name . 'file_attach' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            }
            $openState->file_attach = json_encode($files);
        

        if (!empty($request->Support_doc)) {
            $files = [];
            if ($request->hasfile('Support_doc')) {
                foreach ($request->file('Support_doc') as $file) {
                    if ($file instanceof \Illuminate\Http\UploadedFile) {  
                    $name = $request->name . 'Support_doc' . rand(1, 100) . '.' . $file->getClientOriginalExtension();
                    $file->move('upload/', $name);
                    $files[] = $name;
                }
            }
            }
            $openState->Support_doc = json_encode($files);
        }

        
        $openState->update();


        // ----------------Action History--------------

        if ($lastopenState->title != $openState->title || !empty($request->title_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Title')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Title';
            $history->previous = $lastopenState->title;
            $history->current = $openState->title;
            $history->comment = $request->title_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name =  $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }

        if ($lastopenState->dept != $openState->dept || !empty($request->dept_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Responsible Department')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Responsible Department';
            $history->previous = $lastopenState->dept;
            $history->current = $openState->dept;
            $history->comment = $request->dept_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = $lastopenState->status;
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }  
        if ($lastopenState->assign_to != $openState->assign_to || !empty($request->assign_to_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Assigned To')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Assigned To';
            $history->previous = $lastopenState->assign_to;
            $history->current = $openState->assign_to;
            $history->comment = $request->dept_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }  
          
        if ($lastopenState->Reference_Recores1 != $openState->Reference_Recores1 || !empty($request->Reference_Recores1_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Action Item Related Records')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Action Item Related Records';
            $history->previous = $lastopenState->Reference_Recores1;
            $history->current = $openState->Reference_Recores1;
            $history->comment = $request->Reference_Recores1_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }

        if ($lastopenState->short_description != $openState->short_description || !empty($request->short_description_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Short Description')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Short Description';
            $history->previous = $lastopenState->short_description;
            $history->current = $openState->short_description;
            $history->comment = $request->short_description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->priority_data != $openState->priority_data || !empty($request->priority_data_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Priority Data')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Priority Data';
            $history->previous = $lastopenState->priority_data;
            $history->current = $openState->priority_data;
            $history->comment = $request->priority_data_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->description != $openState->description || !empty($request->description_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Description')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Description';
            $history->previous = $lastopenState->description;
            $history->current = $openState->description;
            $history->comment = $request->description_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->hod_preson != $openState->hod_preson || !empty($request->hod_preson_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'HOD Persons')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'HOD Persons';
            $history->previous = $lastopenState->hod_preson;
            $history->current = $openState->hod_preson;
            $history->comment = $request->hod_preson_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->initiatorGroup != $openState->initiatorGroup || !empty($request->initiatorGroup_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Inititator Group')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;

            $history->activity_type = 'Inititator Group';
            $history->previous = $lastopenState->initiatorGroup;
            $history->current = $openState->initiatorGroup;
            $history->comment = $request->initiatorGroup_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->action_taken != $openState->action_taken || !empty($request->action_taken_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Action Taken')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Action Taken';
            $history->previous = $lastopenState->action_taken;
            $history->current = $openState->action_taken;
            $history->comment = $request->action_taken_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastopenState->start_date != $openState->start_date || !empty($request->start_date_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Actual Start Date')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Actual Start Date';
            $history->previous = $lastopenState->start_date;
            $history->current = $openState->start_date;
            $history->comment = $request->start_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
             $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';

            $history->save();
        }
        if ($lastopenState->end_date != $openState->end_date || !empty($request->end_date_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Actual End Date')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Actual End Date';
            $history->previous = $lastopenState->end_date;
            $history->current = $openState->end_date;
            $history->comment = $request->end_date_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->comments != $openState->comments || !empty($request->comments_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Comments')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Comments';
            $history->previous = $lastopenState->comments;
            $history->current = $openState->comments;
            $history->comment = $request->comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->qa_comments != $openState->qa_comments || !empty($request->qa_comments_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'QA Review Comments')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = $lastopenState->qa_comments;
            $history->current = $openState->qa_comments;
            $history->comment = $request->qa_comments_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->due_date_extension != $openState->due_date_extension || !empty($request->due_date_extension_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'QA Review Comments')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'QA Review Comments';
            $history->previous = $lastopenState->due_date_extension;
            $history->current = $openState->due_date_extension;
            $history->comment = $request->due_date_extension_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        if ($lastopenState->file_attach != $openState->file_attach || !empty($request->file_attach_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'File Attachments')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'File Attachments';
            $history->previous = $lastopenState->file_attach;
            $history->current = $openState->file_attach;
            $history->comment = $request->file_attach_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
            $history->save();
        }
        if ($lastopenState->Support_doc != $openState->Support_doc || !empty($request->Support_doc_comment)) {
            $lastDocumentAuditTrail = CriticalActionAuditTrail::where('cc_id', $openState->id)
            ->where('activity_type', 'Supporting Documents')
            ->exists();
            $history = new CriticalActionAuditTrail;
            $history->cc_id = $id;
            $history->activity_type = 'Supporting Documents';
            $history->previous = $lastopenState->Support_doc;
            $history->current = $openState->Support_doc;
            $history->comment = $request->Support_doc_comment;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
            $history->origin_state = $lastopenState->status;
            $history->change_to = "Not Applicable";
            $history->change_from = $lastopenState->status;
            $history->action_name = $lastDocumentAuditTrail ? 'Update' : 'New';
   
            $history->save();
        }
        toastr()->success('Document update');

        return back();
    }

    public function stageChange(Request $request, $id)
    {
        // return "hii";
        if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
            $changeControl = CriticalAction::find($id);
            $lastopenState = CriticalAction::find($id);
            $openState = CriticalAction::find($id);
            $task = Taskdetails::where('cc_id', $id)->first();
            if ($changeControl->stage == 1) {
                // $rules = [
                //     'action_taken' => 'required|max:255',

                // ];
                // $customMessages = [
                //     'action_taken.required' => 'The action taken field is required.',

                // ];
                // if ($task != null) {
                //     $validator = Validator::make($task->toArray(), $rules, $customMessages);
                    // if ($validator->fails()) {
                    //     $errorMessages = implode('<br>', $validator->errors()->all());
                    //     session()->put('errorMessages', $errorMessages);
                    //     return back();
                    // } else {
                //         $changeControl->stage = '2';
                //         $changeControl->status = 'Work In Progress';
                //         $changeControl->update();
                //         $history = new CriticalActionHistory();
                //         $history->type = "Action-Item";
                //         $history->doc_id = $id;
                //         $history->user_id = Auth::user()->id;
                //         $history->user_name = Auth::user()->name;
                //         $history->stage_id = $changeControl->stage;
                //         $history->status = $changeControl->status;
                //         $history->save();
                //         toastr()->success('Document Sent');

                //         return back();
                    
                // } else {
                    $changeControl->stage = '2';
                    $changeControl->status = 'Work In Progress';
                    $changeControl->submitted_by = Auth::user()->name;
                    $changeControl->submitted_on = Carbon::now()->format('d-M-Y');
                    $changeControl->submitted_comment = $request->comment;

                        $history = new CriticalActionAuditTrail;
                        $history->cc_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->action = 'Submit';
                        $history->current = $changeControl->submitted_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastopenState->status;
                        $history->stage = "2";
                        $history->change_to = "Work In Progress";
                        $history->change_from = "Opened";
                        $history->action_name = 'Not Applicable';
                        $history->save();
                    $changeControl->update();
                    $history = new CriticalActionHistory();
                    $history->type = "Action-Item";
                    $history->doc_id = $id;
                    $history->user_id = Auth::user()->id;
                    $history->user_name = Auth::user()->name;
                    $history->stage_id = $changeControl->stage;
                    $history->status = $changeControl->status;
                    $history->save();
                //     $list = Helpers::getActionOwnerUserList();
                //     foreach ($list as $u) {
                //         if($u->q_m_s_divisions_id == $openState->division_id){
                //             $email = Helpers::getInitiatorEmail($u->user_id);
                //              if ($email !== null) {
                          
                //               Mail::send(
                //                   'mail.view-mail',
                //                    ['data' => $openState],
                //                 function ($message) use ($email) {
                //                     $message->to($email)
                //                         ->subject("Document is Submitted By ".Auth::user()->name);
                //                 }
                //               );
                //             }
                //      } 
                //   }
                    toastr()->success('Document Sent');

                    return back();
                }
            
            if ($changeControl->stage == 2) {
                $changeControl->stage = '3';
                $changeControl->status = 'Closed-Done';
                $changeControl->completed_by = Auth::user()->name;
                $changeControl->completed_on = Carbon::now()->format('d-M-Y');
                $changeControl->completed_comment = $request->comment;

                      $history = new CriticalActionAuditTrail;
                        $history->cc_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->action = 'Complete';
                        $history->previous = $lastopenState->completed_by;
                        $history->current = $changeControl->completed_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastopenState->status;
                        $history->stage = "3";
                        $history->change_to = "Closed-Done";
                        $history->change_from = "Work In Progress";
                        $history->action_name = 'Not Applicable';
                        $history->save();
                $changeControl->update();
                $history = new CriticalActionHistory();
                $history->type = "Action-Item";
                $history->doc_id = $id;
                $history->user_id = Auth::user()->id;
                $history->user_name = Auth::user()->name;
                $history->stage_id = $changeControl->stage;
                $history->status = $changeControl->status;
                $history->save();
            //     $list = Helpers::getInitiatorUserList();
            //     foreach ($list as $u) {
            //         if($u->q_m_s_divisions_id == $openState->division_id){
            //             $email = Helpers::getInitiatorEmail($u->user_id);
            //              if ($email !== null) {
                      
            //               Mail::send(
            //                   'mail.view-mail',
            //                    ['data' => $openState],
            //                 function ($message) use ($email) {
            //                     $message->to($email)
            //                         ->subject("Document is Send By ".Auth::user()->name);
            //                 }
            //               );
            //             }
            //      } 
            //   }
                toastr()->success('Document Sent');

                return back();
            }
        } else {
            toastr()->error('E-signature Not match');

            return back();
        }
    }

    public function actionStageCancel(Request $request, $id)
{
    if ($request->username == Auth::user()->email && Hash::check($request->password, Auth::user()->password)) {
        $changeControl = CriticalAction::find($id);
        $lastopenState = CriticalAction::find($id);
        $openState = CriticalAction::find($id);

        if ($changeControl->stage == 1) {
            $changeControl->stage = "0";
            $changeControl->status = "Closed-Cancelled";
            $changeControl->cancelled_by = Auth::user()->name;
            $changeControl->cancelled_on = Carbon::now()->format('d-M-Y');
            $changeControl->cancelled_comment =$request->comment;

                        $history = new CriticalActionAuditTrail;
                        $history->cc_id = $id;
                        $history->activity_type = 'Activity Log';
                        $history->action = 'Submit';
                        $history->current = $changeControl->cancelled_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastopenState->status;
                        $history->stage = "0";
                        $history->change_to = "Cancelled";
                        $history->change_from = "Opened";
                        $history->action_name = 'Not Applicable';
                        $history->save();
            $changeControl->update();
            $history = new CriticalActionHistory();
            $history->type = "Action Item";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $changeControl->stage;
            $history->status = $changeControl->status;
            $history->save();
            // $list = Helpers::getActionOwnerUserList();
            //         foreach ($list as $u) {
            //             if($u->q_m_s_divisions_id == $openState->division_id){
            //                 $email = Helpers::getInitiatorEmail($u->user_id);
            //                  if ($email !== null) {
                          
            //                   Mail::send(
            //                       'mail.view-mail',
            //                        ['data' => $openState],
            //                     function ($message) use ($email) {
            //                         $message->to($email)
            //                             ->subject("Document is Cancel By ".Auth::user()->name);
            //                     }
            //                   );
            //                 }
            //          } 
            //       }
            toastr()->success('Document Sent');
            return back();
        }

        if ($changeControl->stage == 2) {
            $changeControl->stage = "1";
            $changeControl->status = "Opened";
            $changeControl->more_information_required_by = (string)Auth::user()->name;
            $changeControl->more_information_required_on = Carbon::now()->format('d-M-Y');
            $changeControl->more_info_requ_comment =$request->comment;

                        $history = new CriticalActionAuditTrail;
                        $history->cc_id = $id;
                        $history->action = 'More Information Required';
                        $history->activity_type = 'Activity Log';
                        $history->current = $changeControl->more_information_required_by;
                        $history->comment = $request->comment;
                        $history->user_id = Auth::user()->id;
                        $history->user_name = Auth::user()->name;
                        $history->user_role = RoleGroup::where('id', Auth::user()->role)->value('name');
                        $history->origin_state = $lastopenState->status;
                        $history->stage = "1";
                        $history->change_to = "Opened";
                        $history->change_from = "Work In Progress";
                        $history->action_name = 'Not Applicable';
                        $history->save();
            $changeControl->update();
            $history = new CriticalActionHistory();
            $history->type = "Action Item";
            $history->doc_id = $id;
            $history->user_id = Auth::user()->id;
            $history->user_name = Auth::user()->name;
            $history->stage_id = $changeControl->stage;
            $history->status = "More-information Required";
            $history->save();
        //     $list = Helpers::getInitiatorUserList();
        //     foreach ($list as $u) {
        //         if($u->q_m_s_divisions_id == $openState->division_id){
        //             $email = Helpers::getInitiatorEmail($u->user_id);
        //              if ($email !== null) {
                  
        //               Mail::send(
        //                   'mail.view-mail',
        //                    ['data' => $openState],
        //                 function ($message) use ($email) {
        //                     $message->to($email)
        //                         ->subject("Document is Send By ".Auth::user()->name);
        //                 }
        //               );
        //             }
        //      } 
        //   }
            toastr()->success('Document Sent');
            return redirect('critical-action-view/'.$id);
        }
    } else {
        toastr()->error('E-signature Not match');
        return back();
    }
}

public function actionItemAuditTrialShow($id)
{
    $audit = CriticalActionAuditTrail::where('cc_id', $id)->orderByDESC('id')->paginate();
    $today = Carbon::now()->format('d-m-y');
    $document = CriticalAction::where('id', $id)->first();
    $document->initiator = User::where('id', $document->initiator_id)->value('name');

    return view('frontend.critical-action.critical_action_audittrail', compact('audit', 'document', 'today'));
}

// public function actionItemAuditTrialDetails($id)
// {
//     $detail = CriticalActionAuditTrail::find($id);

//     $detail_data = CriticalActionAuditTrail::where('activity_type', $detail->activity_type)->where('cc_id', $detail->cc_id)->latest()->get();

//     $doc = CriticalAction::where('id', $detail->cc_id)->first();

//     $doc->origiator_name = User::find($doc->initiator_id);
//     return view('frontend.action-item.audit-trial-inner', compact('detail', 'doc', 'detail_data'));
// }

public static function singleReport($id)
{
    $data = CriticalAction::find($id);
    if (!empty($data)) {
        $data->originator = User::where('id', $data->initiator_id)->value('name');
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.critical-action.critical_action_singlereport', compact('data'))
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
        $canvas->page_text($width / 4, $height / 2, $data->status, null, 25, [0, 0, 0], 2, 6, -20);
        return $pdf->stream('CriticalAction' . $id . '.pdf');
    }
}

public static function auditReport($id)
{
    $doc = CriticalAction::find($id);
    if (!empty($doc)) {
        $doc->originator = User::where('id', $doc->initiator_id)->value('name');
        $data = CriticalActionAuditTrail::where('cc_id', $id)->get();
        $pdf = App::make('dompdf.wrapper');
        $time = Carbon::now();
        $pdf = PDF::loadview('frontend.critical-action.critical_action_auditreport', compact('data', 'doc'))
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
        return $pdf->stream('ActionItem-Audit' . $id . '.pdf');
    }
}




}
