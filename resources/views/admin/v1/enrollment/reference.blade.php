{{--Add Company Model--}}
<!-- Modal -->
<div id="addReferenceModel" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="add_company_form">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Reference Details</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="add_ref_no">Reference No</label>
                            <input type="text" id="add_ref_no" name="add_ref_no" readonly placeholder="Reference No" value="1" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="add_dev_ndt_id">Dev NDT Id</label>
                            <select  style="width: 100%;" id="add_dev_ndt_id" name="add_dev_ndt_id" class="form-control select2">
                                <option value="">Find By Id</option>
                                <option value="3939">3939</option>
                                <option value="3940">3940</option>
                                <option value="3950">3950</option>
                                <option value="3951">3951</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <select id="f_greet" name="f_greet" value="" class="form-control">
                                        <option value="MR">MR</option>
                                        <option value="MS">MS</option>
                                        <option value="MISS">MISS</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="f_fname" name="f_fname" placeholder="First Name" value="" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="f_mname" name="f_mname" placeholder="Middle Name" value="" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="f_lname" name="f_lname" placeholder="Last Name" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="add_company_contact">Contact No</label>
                            <input type="text" id="add_company_contact" name="add_company_contact" placeholder="+91 XXXXXXXXXX" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="add_company_email">Email</label>
                            <input type="text" id="add_company_email" name="add_company_email" placeholder="your@email.com" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="add_ref_remarks">Remarks</label>
                            <textarea id="add_ref_remarks" name="add_ref_remarks" class="form-control" placeholder="Write Something..." rows="4"></textarea>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-upload"></i> Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
            </form>
        </div>

    </div>
</div>
{{--end of Company Add Model--}}