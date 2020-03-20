{{--Add Company Model--}}
<!-- Modal -->
<div id="addCompanyModel" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="add_company_form">
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Company Details</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="company_no">Company No</label>
                            <input type="text" id="company_no" name="company_no" readonly placeholder="Company No" value="1" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="add_company_name">Company Name</label>
                            <input type="text" id="add_company_name" name="add_company_name" placeholder="Company Name" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="company_type">Company Type</label>
                            <select  style="width: 100%;" id="company_type" name="company_type" class="form-control select2">
                                <option value="">Select Type</option>
                                <option value="Foundry Sand Casting">Foundry Sand Casting</option>
                                <option value="Foundry Investment Casting">Foundry Investment Casting</option>
                                <option value="Foundry Forging">Foundry Forging</option>
                                <option value="Fabrication">Fabrication</option>
                                <option value="Fabrication Tubes & Pipes">Fabrication Tubes & Pipes</option>
                                <option value="Raw Material">Raw Material</option>
                                <option value="T.P.I.">T.P.I.</option>
                                <option value="Valve Manufacturer">Valve Manufacturer</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="add_company_address">Company Address</label>
                            <input type="text" id="add_company_address" name="add_company_address" placeholder="Address" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" name="city" placeholder="Ahmedabad" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="district">District</label>
                            <input type="text" id="district" name="district" placeholder="Ahmedabad" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="state">State</label>
                            <input type="text" id="state" name="state" placeholder="Gujarat" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="pincode">Pincode</label>
                            <input type="text" id="pincode" name="pincode" placeholder="3823**" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="add_company_week_off">Weekly Off</label>
                            <input type="text" id="add_company_week_off" name="add_company_week_off" placeholder="Weekly Off" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="add_company_contact">Contact No</label>
                            <input type="text" id="add_company_contact" name="add_company_contact" placeholder="+91 XXXXXXXXXX" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="add_company_email">Email</label>
                            <input type="text" id="add_company_email" name="add_company_email" placeholder="your@email.com" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="add_company_website">Website</label>
                            <input type="text" id="add_company_website" name="add_company_website" placeholder="https://www.xyz.com" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Contact Person Details</h3>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Contact Person</label>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="designation">Designation</label>
                            <select  style="width: 100%;" id="designation" name="designation" class="form-control select2">
                                <option value="">Select Designation</option>
                                <option value="Proprietor">Proprietor</option>
                                <option value="Director">Director</option>
                                <option value="Manager">Manager</option>
                                <option value="Engineer">Engineer</option>
                                <option value="Q.C.Manager">Q.C.Manager</option>
                                <option value="H.R.Manager">H.R.Manager</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="add_company_contact">Contact No</label>
                            <input type="text" id="add_company_contact" name="add_company_contact" placeholder="+91 XXXXXXXXXX" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="add_company_email">Email</label>
                            <input type="text" id="add_company_email" name="add_company_email" placeholder="your@email.com" value="" class="form-control">
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