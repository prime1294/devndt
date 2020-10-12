{{--Add Company Model--}}
<!-- Modal -->
<div id="editReferenceModel" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="edit_ref_form">
                {!! csrf_field() !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Update Reference</h3>
                    </div>
                </div>
                <input type="hidden" id="edit_ref_id" name="edit_ref_id">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ref_dev_ndt_id">Dev NDT ID</label>
                            <select  style="width: 100%;" id="edit_ref_dev_ndt_id" name="edit_ref_dev_ndt_id" class="form-control ref_fetch_ndt select2">
                                <option value="">Find By Id</option>
                                @foreach($enrollment_list as $row)
                                <option value="{{ $row->id }}">{{ $row->id }} - {{ $row->front_fname.' '.$row->front_lname }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <div class="row">
                                <div class="col-md-4">
                                    <input type="text" id="edit_ref_fname" name="edit_ref_fname" placeholder="First Name" value="" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="edit_ref_mname" name="edit_ref_mname" placeholder="Middle Name" value="" class="form-control">
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="edit_ref_lname" name="edit_ref_lname" placeholder="Last Name" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">

                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ref_company_contact">Contact No</label>
                            <input type="text" id="edit_ref_company_contact" name="edit_ref_company_contact" value="" class="form-control" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ref_company_email">Email</label>
                            <input type="email" id="edit_ref_company_email" name="edit_ref_company_email" placeholder="your@email.com" value="@gmail.com" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ref_designation">Designation</label>
                            <select  style="width: 100%;" id="edit_ref_designation" name="edit_ref_designation" class="form-control select2">
                                <option value="">Select Designation</option>
                                @foreach($comp['designation'] as $drow)
                                    <option value="{{ $drow->name }}">{{ $drow->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_ref_company_no">Company No.</label>
                            <select  style="width: 100%;" id="edit_ref_company_no" name="edit_ref_company_no" class="form-control ref_fetch_company select2">
                                <option value="">Find By Id</option>
                                @foreach($comp_ids as $row)
                                    <option value="{{ $row->id }}">{{ $row->id }} - {{ $row->company_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="edit_ref_company_name">Company Name</label>
                            <input type="text" id="edit_ref_company_name" name="edit_ref_company_name" placeholder="Company Name" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_ref_company_address">Address</label>
                            <input type="text" id="edit_ref_company_address" name="edit_ref_company_address" placeholder="Address" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="edit_ref_remarks">Remarks</label>
                            <textarea id="edit_ref_remarks" name="edit_ref_remarks" class="form-control" placeholder="Write Something..." rows="2"></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="submiteditref" class="btn btn-primary"><i class="fa fa-upload"></i> Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
            </form>
        </div>

    </div>
</div>
{{--end of Company Add Model--}}