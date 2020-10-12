{{--Add Company Model--}}
<!-- Modal -->
<div id="editCompanyModel" class="modal fade" data-keyboard="false" data-backdrop="static" role="dialog">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <form method="post" id="edit_company_form">
                {!! csrf_field() !!}
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <h3 class="page-header">Company Details</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="edit_ac_no">Company No</label>
                            <input type="text" id="edit_ac_no" name="ac_no" readonly placeholder="Company No" value="0" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="form-group">
                            <label for="edit_ac_comp_name">Company Name</label>
                            <input type="text" id="edit_ac_comp_name" name="ac_comp_name" placeholder="Company Name" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_type">Company Type</label>
                            <select  style="width: 100%;" id="edit_ac_comp_type" name="ac_comp_type" class="form-control select2">
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
                            <label for="edit_ac_comp_address">Company Address</label>
                            <input type="text" id="edit_ac_comp_address" name="ac_comp_address" placeholder="Address" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_city">City</label>
                            <input type="text" id="edit_ac_comp_city" name="ac_comp_city" placeholder="Ahmedabad" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_district">District</label>
                            <input type="text" id="edit_ac_comp_district" name="ac_comp_district" placeholder="Ahmedabad" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_state">State</label>
                            <input type="text" id="edit_ac_comp_state" name="ac_comp_state" placeholder="Gujarat" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_pincode">Pincode</label>
                            <input type="text" id="edit_ac_comp_pincode" name="ac_comp_pincode" value="" class="form-control" placeholder="382345" data-inputmask='"mask": "999999"' data-mask>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="edit_ac_comp_weekoff">Weekly Off</label>
                            <input type="text" id="edit_ac_comp_weekoff" name="ac_comp_weekoff" placeholder="Weekly Off" value="" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_comp_contact">Contact No</label>
                            <input type="text" id="edit_ac_comp_contact" name="ac_comp_contact" value="" class="form-control" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_comp_email">Email</label>
                            <input type="email" id="edit_ac_comp_email" name="ac_comp_email" placeholder="your@email.com" value="" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_comp_website">Website</label>
                            <input type="search" id="edit_ac_comp_website" name="ac_comp_website" placeholder="https://www.xyz.com" value="" class="form-control">
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
                            <label for="ac_person">Contact Person</label>
                            <div class="row">
                                <div class="col-md-2">
                                    <select id="edit_ac_person_greet" name="ac_person_greet" value="" class="form-control">
                                        <option value="MR">Mr</option>
                                        <option value="MS">Ms</option>
                                        <option value="MD">Md</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <input type="text" id="edit_ac_person_fname" name="ac_person_fname" placeholder="First Name" value="" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="edit_ac_person_mname" name="ac_person_mname" placeholder="Middle Name" value="" class="form-control">
                                </div>
                                <div class="col-md-3">
                                    <input type="text" id="edit_ac_person_lname" name="ac_person_lname" placeholder="Last Name" value="" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_person_designation">Designation</label>
                            <select  style="width: 100%;" id="edit_ac_person_designation" name="ac_person_designation" class="form-control select2">
                                <option value="">Select Designation</option>
                                @foreach($comp['designation'] as $drow)
                                    <option value="{{ $drow->name }}">{{ $drow->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_person_contact">Contact No</label>
                            <input type="text" id="edit_ac_person_contact" name="ac_person_contact" value="" class="form-control" placeholder="8733883364" data-inputmask='"mask": "9999999999"' data-mask>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="edit_ac_person_email">Email</label>
                            <input type="email" id="edit_ac_person_email" name="ac_person_email" placeholder="your@email.com" value="" class="form-control">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" id="edit_submitcompany" class="btn btn-primary"><i class="fa fa-upload"></i> Submit</button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
            </form>
        </div>

    </div>
</div>
{{--end of Company Add Model--}}