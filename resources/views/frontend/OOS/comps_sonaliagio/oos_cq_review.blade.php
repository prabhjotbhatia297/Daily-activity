<div id="CCForm10" class="inner-block cctabcontent">
    <div class="inner-block-content">
        <div class="sub-head">
            CQ Review Comments
        </div>
        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="group-input">
                    <label for="Description Deviation">CQ Review comments</label>
                    <!-- <div><small class="text-primary">Please insert "NA" in the data field if it does not require completion</small></div> -->
                    <textarea class="summernote" name="cq_review_comments_ocqr" id="summernote-1">
                                {{ $data->cq_review_comments_ocqr ?? '' }}
                        </textarea>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="group-input">
                    <label for="Report Attachments">CAPA Required?</label>
                    <select name="capa_required_ocqr">
                        <option value="0" {{ $data->capa_required_ocqr == '0' ? 'selected' : '' }}>Enter Your
                            Selection Here</option>
                        <option value="Yes" {{ $data->capa_required_ocqr == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ $data->capa_required_ocqr == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
            </div>


            <div class="col-lg-6">
                <div class="group-input">
                    <label for="Reference Recores">Reference of CAPA </label>
                    <input type="text" name="reference_of_capa_ocqr" value="{{$data->reference_of_capa_ocqr}}">
                </div>
            </div>


            <div class="col-lg-6">
                <div class="group-input">
                    <label for="Auditee">Action Plan Requirement?</label>
                    <select multiple name="action_plan_requirement_ocqr" id="auditee">
                        <option value="0" {{ $data->action_plan_requirement_ocqr == '0' ? 'selected' : '' }}>Enter
                            Your Selection Here</option>
                        <option value="Yes" {{ $data->action_plan_requirement_ocqr == 'Yes' ? 'selected' : '' }}>Yes
                        </option>
                        <option value="No" {{ $data->action_plan_requirement_ocqr == 'No' ? 'selected' : '' }}>No
                        </option>

                    </select>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="group-input">
                    <label for="Audit Attachments">Ref Action Plan</label>
                    <input type="text" name="ref_action_plan_ocqr" value="{{ $data->ref_action_plan_ocqr ?? '' }}">
                </div>
            </div>

            <div class="col-12">
                <div class="group-input">
                    <label for="Audit Attachments"> CQ Attachment</label>
                    <small class="text-primary">
                        Please Attach all relevant or supporting documents
                    </small>
                    <div class="file-attachment-field">
                        <div class="file-attachment-list" id="file_attach">

                            @if ($data->cq_attachment_ocqr)
                            @foreach ($data->cq_attachment_ocqr as $file)
                            <h6 type="button" class="file-container text-dark"
                                style="background-color: rgb(243, 242, 240);">
                                <b>{{ $file }}</b>
                                <a href="{{ asset('upload/' . $file) }}" target="_blank"><i
                                        class="fa fa-eye text-primary"
                                        style="font-size:20px; margin-right:-10px;"></i></a>
                                <a type="button" class="remove-file" data-file-name="{{ $file }}"><i
                                        class="fa-solid fa-circle-xmark" style="color:red; font-size:20px;"></i></a>
                            </h6>
                            @endforeach
                            @endif
                        </div>
                        <div class="add-btn">
                            <div>Add</div>
                            <input type="file" id="myfile" name="cq_attachment_ocqr[]"
                                oninput="addMultipleFiles(this, 'file_attach')" multiple>
                        </div>
                    </div>

                </div>
            </div>

            <div class="button-block">
                <button type="submit" id="ChangesaveButton" class="saveButton">Save</button>
                <button type="button" class="backButton" onclick="previousStep()">Back</button>
                <button type="button" id="ChangeNextButton" class="nextButton" onclick="nextStep()">Next</button>
                <button type="button"> <a href="{{ url('rcms/qms-dashboard') }}" class="text-white">
                        Exit </a> </button>
            </div>
        </div>

    </div>
</div>