@extends('layout.main')
@section('main-container')

<link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">

<style>

* {

  box-sizing: border-box;

}



body {

  background-color: #f1f1f1;

}



#regForm {

  background-color: #ffffff;

  /*margin: 100px auto;*/

  font-family: Raleway;

  /*padding: 40px;*/

  width: 100%;

  /*min-width: 300px;*/

}



h1 {

  text-align: center;  

}



input {

  padding: 10px;

  width: 100%;

  font-size: 17px;

  font-family: Raleway;

  border: 1px solid #aaaaaa;

}



/* Mark input boxes that gets an error on validation: */

input.invalid {

  background-color: #ffdddd;

}



/* Hide all steps by default: */

.tab {

  display: none;

}

.btn1{

  background-color:#95f3ff;  

}

.btn1:hover {

  background-color: #e0f7fa;

}



button {

  background-color: #04AA6D;

  color: #ffffff;

  border: none;

  padding: 10px 20px;

  font-size: 17px;

  font-family: Raleway;

  cursor: pointer;

}



button:hover {

  opacity: 0.8;

}



#prevBtn {

  background-color: #bbbbbb;

}



/* Make circles that indicate the steps of the form: */

.step {

  height: 15px;

  width: 15px;

  margin: 0 2px;

  background-color: #bbbbbb;

  border: none;  

  border-radius: 50%;

  display: inline-block;

  opacity: 0.5;

}



.step.active {

  opacity: 1;

}



/* Mark the steps that are finished and valid: */

.step.finish {

  background-color: #04AA6D;

}

.tab{

    padding: 20px;

    background-color: white;

    

}

.tab1{

    padding: 20px;

     border: 1px solid #a8adb1; 

    

}



.col-sm-3 {

    width: 20% !important;

}

select.form-control {

 width: 200px;

}



tbody, td, tfoot, th, thead, tr {

    border: none !important;

}



</style>



<!--<br><br>-->

<div class="card">

   <div class="app-content">

      <section class="section">

         <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="#" class="text-muted">Factory Creation</a></li>

            <li class="breadcrumb-item active text-" aria-current="page">Inputer List </li>

         </ol>

         <div class="row">

            <div class="container">

               <br>

               

               <!--<hr>-->

               <div>

                   <form id="regForm" action="/action_page.php">

                      <!--<h1>Register:</h1>-->

                      <!-- One "tab" for each step in the form: -->

                      

                      <div class="tab">

                          

                    <div class="row">

                  <div class="col-4">

                     <!--<h5>Inputer View List</h5>-->

                  </div>

                  <div class="col-12">

            

                <div class="row">

                    <div class="col">

                    <h5>Address Details</h5>

                    </div>

                    <div class="col">

                    <label for="">Inputer Name</label>

                    <input type="text" style="border-radius: 12px;" class="form-control" placeholder="Auto By Login ID">

                    </div>

                    <div class="col">

                    <label for="">Date & Time</label>

                    <input type="text" style="border-radius: 12px;" class="form-control" placeholder="Auto Update (Today)">

                    </div>

                     <!--<div class="col"  style="text-align:right">-->

                     <!--      <a href="{{URL('/AddFactory')}}" class="btn btn-primary">Add Factory</a>-->

                     <!--   </div>-->

                </div>

                </div>

                 

               </div><br>

               <div class="tab1">

                        

                          <div class="row">

                           <div class="col-sm-3 form-group">

                                <label>Organization*</lable>

                                <p>

                                <select  class="form-control" name="organization">

                                    <option value="null" selected disabled>Select Option</option>

                                    <option value="">Option 1</option>

                                    <option value="">Option 2</option>

                                    <option value="">Option 3</option>

                                     <option value="">Option 4</option>

                                </select></p>

                            </div>

                              <div class="col-sm-3 form-group">

                                 <label>Name Of Unit*</lable>

                                <select  class="form-control" name="name_unit">

                                    <option value="null" selected disabled>Select Option</option>

                                    <option value="">Option 1</option>

                                    <option value="">Option 2</option>

                                    <option value="">Option 3</option>

                                     <option value="">Option 4</option>

                                </select>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Country*</lable>

                                 <select  class="form-control" name="country">

                                    <option value="null" selected disabled>Select Option</option>

                                    <option value="">Option 1</option>

                                    <option value="">Option 2</option>

                                    <option value="">Option 3</option>

                                     <option value="">Option 4</option>

                                </select>

                              

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>State*</lable>

                                  <select  class="form-control" name="state">

                                    <option value="null" selected disabled>Select Option</option>

                                    <option value="">Option 1</option>

                                    <option value="">Option 2</option>

                                    <option value="">Option 3</option>

                                     <option value="">Option 4</option>

                                </select>

                               

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>District*</lable>

                                  <select  class="form-control"  name="district">

                                    <option value="null" selected disabled>Select Option</option>

                                    <option value="">Option 1</option>

                                    <option value="">Option 2</option>

                                    <option value="">Option 3</option>

                                     <option value="">Option 4</option>

                                </select>

                               

                             </div>

                         

                         </div><br>

                            <div class="row">

                           <div class="col-sm-3 form-group">

                               

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="address[]"></p>-->

                                <!-- <a href="javascript:void(0);" class="add_button" title="Add field"><img src="add-icon.png"/>add</a>-->

                                  <table class="table" id="dynamic_field">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <label style="display:flex;">

                                                    Address<span class="req">*</span>

                                                </label>

                                                <input type="text" autocomplete="off" placeholder="Manual Entry" class="form-control" name="address[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" style="margin-top: 24px;" name="add" id="add" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                            </div>

                              <div class="col-sm-3 form-group">

                                 <label>Pincode*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Manual Entry" oninput="this.className = ''" name="pincode"></p>

                             </div>

                             <div class="col-sm-4 form-group">

                                <label for="State">Remarks:</label>

                                   <textarea name="remark1" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea>

                                </div>

                         

                         </div>

                        </div>

                      

                      </div>

                      <div class="tab">

                            <h5>Statutory Details</h5>

                           <div class="tab1">

                         <div class="row">

                           <div class="col-sm-2 form-group">

                                <label>GST IN No.</lable><br>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="gst_in_no"></p>

                            </div>

                              <div class="col-sm-4 form-group">

                                 <label>GST IN Certificate Attachement*</lable>

                                <p><input type="file" style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="gst_certification"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Pan No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="pan_no"></p>

                             </div>

                               <div class="col-sm-4 form-group">

                                 <label>PAN Attachement*</lable>

                                <p><input type="file" style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="pan_attachement"></p>

                             </div>

                              

                            

                         </div>

                          <div class="row">

                               <div class="col-sm-2 form-group">

                                 <label>Factory License No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="factory_license_no"></p>

                             </div>

                           <div class="col-sm-4 form-group">

                                 <label>Factory License Attachement*</lable>

                                <p><input type="file" style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="factory_license_att"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Labour License No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="labour_license_no"></p>

                             </div>

                               <div class="col-sm-4 form-group">

                                 <label>Labour License Attachement*</lable>

                                <p><input  type="file" style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="labour_license_att"></p>

                             </div>

                             </div>

                             <div class="row">

                               <div class="col-sm-2 form-group">

                                 <label>Pollution Certificate No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="pollution_cerificate_no"></p>

                             </div>

                               <div class="col-sm-4 form-group">

                                 <label>Pollution Certificate Attachement*</lable>

                                <p><input  type="file" style="border-radius: 12px;" placeholder="Auto Fetch From Statutory" oninput="this.className = ''" name="pollution_cerificate_attch"></p>

                             </div>

                             </div>

                             <div class="row">

                             <div class="col-sm-6 form-group">

                                 <label>Others*</lable>

                                <table class="table table-bordered" id="dynamic_field1">

                                    <tr>

                                        <td style="display:none;">

                                            <div class="field-wrap" >

                                                <label style="display:flex;">

                                                    Add Field Name Manually<span class="req">*</span>

                                                </label>

                                                <input style="border-radius: 12px;" type="text" autocomplete="off" class="form-control" name="add_other1[]">

                                            </div>

                                        </td>

                                        <td style="display:none;">

                                            <div class="field-wrap">

                                                <label style="display:flex;">

                                                    Add Field Name Manually<span class="req">*</span>

                                                </label>

                                               <input  type="file" style="border-radius: 12px;"  placeholder="Auto Fetch From Statutory" name="pollution_cerificate_attch[]">

                                               

                                            </div>

                      

                                        </td>

                                        <td><button type="button" name="add" id="add1" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                                

                             </div>

                              <div class="col-sm-6 form-group">

                                <label for="State">Remarks:</label>

                                   <textarea name="remark1" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea>

                                </div>

                         </div>

                         </div>

                      </div>

                      </div>

                      <div class="tab">

                          <h5>Land & Building:</h5>

                           <div class="tab1">

                         <div class="row">

                          

                              <div class="col-sm-3 form-group">

                                 <label>Land Type*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="land_type"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Land Area*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="land_area"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Open Area*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="open_area"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Cover Area*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="cover_area"></p>

                             </div>

                             <div class="col-sm-3 form-group">

                                 <label>Building Area</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="bulding_area"></p>

                             </div>

                              <div class="col-sm-3 form-group">

                                 

                             </div>

                         </div>

                          <div class="row">

                          

                              <div class="col-sm-3 form-group">

                                 <label>Building Type*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="building_type[]"></p>

                               

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Boundary Height*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="boundary_height"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Boundary Width*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="boundary_width"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Boundary Type*</lable>

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="boundary_type"></p>-->

                                  <table class="table table-bordered" id="dynamic_field2">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="text" autocomplete="off" placeholder="Auto Fetch" class="form-control" name="boundary_type[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add2" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                             <div class="col-sm-3 form-group">

                                 <label>Attachement</lable>

                                <!--<p><input type="file" style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="attachement[]"></p>-->

                                <table class="table table-bordered" id="dynamic_field3">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="file" autocomplete="off" class="form-control" name="attachement[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add3" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                              <div class="col-sm-3 form-group">

                                 

                             </div>

                         </div>

                          <div class="row">

                          

                              <div class="col-sm-3 form-group">

                                 <label>Window*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="window"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Gate*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="gate"></p>

                             </div>

                             </div>

                             <div class="row">

                               <div class="col-sm-6 form-group">

                                 <label>Other*</lable>

                                 <!--<label>Add Field Name Manually</lable>-->

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="add_other2[]"></p>-->

                                 <table class="table table-bordered" id="dynamic_field4">

                                    <tr>

                                        <td style="display:none;">

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap" >

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="text" autocomplete="off" class="form-control" name="add_other2[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add4" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                              <div class="col-sm-6 form-group">

                                 <label for="State">Remark:</label>

                                   <textarea name="remark2" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea> 

                             </div>

                         </div>

                         </div>

                      </div>

                      <div class="tab">

                          <h5>Plant & Machinery</h5>

                        <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>Plant Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="plant_name"></p>

                             </div>

                               <div class="col-sm-1 form-group">

                                 <label>Production Capacity*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="production_capacity"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                               

                                 <label>Product</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="product"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Sub product</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="sub_product"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Sub Sub product</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="sub_sub_product"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>UOM*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="uom1"></p>

                                

                             </div>

                              <div class="col-sm-1 form-group">

                                 <label>Duration*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="duration"></p>

                             </div>

                            

                             

                         </div>

                          <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>Machine Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="machine_name"></p>

                             </div>

                               <div class="col-sm-1 form-group">

                                 <label>Attachement</lable>

                                <p><input type="file" style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="attachement2"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                               

                                 <label>Machine Code*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="machine_code"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Accessories*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="accessories"></p>

                             </div>

                              <div class="col-sm-1 form-group">

                                 <label>Attachement</lable>

                                <p><input type="file" style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="attachement3"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Specification*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Auto Fetch" oninput="this.className = ''" name="specification"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                 <label>Make & Model*</lable>

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="make_model[]"></p>-->

                                  <table class="table table-bordered" id="dynamic_field6">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="text" autocomplete="off" class="form-control" name="make_model[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add6" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                            

                             

                         </div>

                            <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label> Warranty*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="warranty"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Production Capacity*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="production_capacity"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                               

                                 <label>UOM*</lable>

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="uom2"></p>-->

                                 <table class="table table-bordered" id="dynamic_field5">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                              <select name="" class="form-control">

                                                  <option value="null" selected disabled>Select</option>

                                                  <option value="null">Option 1</option>

                                                   <option value="null">Option 2</option>

                                                    <option value="null">Option 3</option> 

                                                    <option value="null">Option 4</option>

                                              </select>

                                                <!--<input type="text" autocomplete="off" class="form-control" name="uom2[]"/>-->

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add5" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                              <div class="col-sm-4 form-group">

                                 <label>Others</lable>

                                  <!--<label>Add Field Name Manually</lable>-->

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="add_other3"></p>-->

                                 <table class="table table-bordered" id="dynamic_field7">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="text"  autocomplete="off" class="form-control" placeholder="Add Field Name Manually" name="add_other3[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add7" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                            

                             

                         </div>

                           <div class="row">

                          

                              <div class="col-sm-3 form-group">

                                 <label> Date Of Purchase*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Manually" oninput="this.className = ''" name="date_of_purchase"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Machine Company Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="Manually" oninput="this.className = ''" name="machine_company_name"></p>

                             </div>

                            

                             

                         </div>

                      </div>

                       <div class="tab">

                            <h5>Amenities</h5> <br>

                         <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>Toilet Count</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="toilet_count"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>For Men</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="for_men"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>For Women</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="for_women"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>WashBasin Count</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="washbasin_count"></p>

                             </div>

                             <div class="col-sm-2 form-group">

                                 <label>Urinals</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="urinals"></p>

                             </div>

                             

                         </div>

                          <div class="row">

                          

                          

                             <!-- <div class="col-sm-2 form-group">-->

                             <!--     <label>Others</lable>-->

                             <!--   <p><input style="border-radius: 12px;" placeholder="Enter Field Name" oninput="this.className = ''" name="add_other4"></p>-->

                             <!--</div>-->

                             

                             <!--<div class="col-sm-2 form-group">-->

                             <!--    <label></lable>-->

                             <!--   <p><input style="border-radius: 12px;" placeholder="Count" oninput="this.className = ''" name="urinals"></p>-->

                             <!--</div>-->

                              <table class="table table-bordered" id="dynamic_field8">

                                 

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                                            <div class="col-sm-2 form-group">

                                                <div class="field-wrap">

                                                     <label>Others</lable>

                                                  <input type="text"  autocomplete="off" class="form-control" placeholder="Enter Field Name" name="add_other4[]"/>

                                              </div>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                  <div class="field-wrap">

                                                    <input type="text"  autocomplete="off" class="form-control" placeholder="Count" name="urinals[]"/>

                                                  </div>

                                             </div>

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add8" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                                 

                         </div>

                         

                         

                         

                      </div>

                        <div class="tab"> 

                        <h5>Electricity</h5>

                         <br>

                         <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>Total Capacity*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="total_capacity"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Running Capacity*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="running_capacity"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Meter*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="meter"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Sub Meter*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="sub_meter"></p>

                             </div>

                             <div class="col-sm-2 form-group">

                                 <label>Source Of Electricity*</lable>

                                <!--<p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="source_electricity"></p>-->

                                 <table class="table table-bordered" id="dynamic_field9">

                                    <tr>

                                        <td>

                                            <!--div class="top-row"-->

                                           

                    

                                            <div class="field-wrap">

                                                <!--<label style="display:flex;">-->

                                                <!--    Add Field Name Manually<span class="req">*</span>-->

                                                <!--</label>-->

                                                <input type="text"  style="border-radius: 12px;" autocomplete="off" class="form-control" placeholder="Add Field Name Manually" name="source_electricity[]"/>

                                            </div>

                    

                                           

                                        </td>

                                        <td><button type="button" name="add" id="add9" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></td>

                                    </tr>

                                </table>

                             </div>

                             

                         </div>

                          <div class="row" id="dynamic_field10">

                            

                                   <div class="col-sm-2 form-group">

                                          <label>Generator*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p>

                                     </div>

                                      <div class="col-sm-2 form-group">

                                         <label>Generator Capacity*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p>

                                     </div>

                                       <div class="col-sm-2 form-group">

                                     <button type="button" name="add" id="add10" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                       </div>

                              </div>

                               

                         

                           <div class="row" style="justify-content: end;">

                          

                             

                               <div class="col-sm-4 form-group">

                                <label for="State">Remark:</label>

                                   <textarea name="remark3" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea>

                                </div>

                         </div>

                         

                         

                         

                      </div>

                          <div class="tab">

                                 <h5>WareHouse & Room</h5> <br>

                         <div class="row">

                          

                              <div class="col-sm-3 form-group">

                                 <label>Total Warehouse*</lable>

                               

                             </div>

                               <div class="col-sm-3 form-group">

                              

                                 <p><input style="border-radius: 12px;" placeholder="Manual Entry" oninput="this.className = ''" name="total_warehouse"></p>

                             </div>

                            

                            

                         </div>

                         <div class="row" id="dynamic_field11">

                             <div class="col-sm-3 form-group">

                                 <label>Warehouse Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="warehouse_no[]"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Count*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="[]"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Warehouse Type*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="warehouse_type[]"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                     <button type="button" name="add" id="add11" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                </div>

                            

                         </div>

                         <div class="row" >

                          

                              <div class="col-sm-3 form-group">

                                 <label>Total Room*</lable>

                               

                             </div>

                               <div class="col-sm-3 form-group">

                               

                                <p><input style="border-radius: 12px;" placeholder="Manual Entry" oninput="this.className = ''" name="total_room"></p>

                             </div>

                            

                            

                         </div>

                          <div class="row" id="dynamic_field12">

                          

                               <div class="col-sm-3 form-group">

                                 <label>Room Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="room_name[]"></p>

                             </div>

                               <div class="col-sm-3 form-group">

                                 <label>Room Count*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="room_count[]"></p>

                             </div>

                              <div class="col-sm-2 form-group">

                                     <button type="button" name="add" id="add12" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                </div>

                            

                         </div>

                           <div class="row" style="justify-content: end;">

                          

                             

                               <div class="col-sm-4 form-group">

                                <label for="State">Remark:</label>

                                   <textarea name="remark4" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea>

                                </div>

                         </div>

                          

                      </div>

                        <div class="tab"> 

                        <h5>Offices</h5> 

                         <br>

                         <div id="dynamic_field13">

                         <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>Asset Type*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="asset_type[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Asset Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="asset_name[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Asset SL No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="asset_sl_no[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Date Of Purchase*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="date_of_p[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Supplier Name*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="supplier_name[]"></p>

                             </div>  

                             <div class="col-sm-2 form-group">

                                 <label>Invoice No.*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="invoice_no[]"></p>

                             </div>

                             

                         </div>

                           <div class="row">

                          

                              <div class="col-sm-2 form-group">

                                 <label>QTY*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="qty[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Organization*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="org[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Use By*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="used_by[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Use In*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="used_in[]"></p>

                             </div>

                               <div class="col-sm-2 form-group">

                                 <label>Location*</lable>

                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="location[]"></p>

                             </div>  

                            <div class="col-sm-2 form-group">

                                     <button type="button" name="add" id="add13" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                            </div>

                         </div>

                         

                        </div>

                         

                          <div class="row" style="justify-content: end;">

                          

                             

                               <div class="col-sm-4 form-group">

                                <label for="State">Remark:</label>

                                   <textarea name="remark5" id="" cols="30" rows="5" class="form-control" style="border-radius: 12px;"></textarea>

                                </div>

                         </div>

                      </div>

                        <div class="tab">

                            <h5>Power House</h5> <br>

                         

                        

                          

                      </div>

                          <div class="tab" >

                              <h5>Store</h5><br>

                                <div class="row">

                              

                                  <div class="col-sm-2 form-group">

                                     <label>Total Rack*</lable>

                                    <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="total_rock"></p>

                                 </div>

                                

                                 <div class="col-sm-2 form-group">

                                     <label> Rack Capacity*</lable>

                                    <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="rock_capacity"></p>

                                 </div>

                                  <div class="col-sm-2 form-group">

                                     <label>Total Bin*</lable>

                                    <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="total_bin"></p>

                                 </div>

                                 

                                  <div class="col-sm-2 form-group">

                                     <label>Total Bin Capacity*</lable>

                                    <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="total_cin_capacity"></p>

                                 </div>

                               

                                </div>

                                <hr>

                                <div class="row">

                                    <div class="col-sm-2 form-group">

                                         <label> Rack No.*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="rack_no"></p>

                                     </div>

                                     <div class="col-sm-2 form-group">

                                         <label> Rack Capacity*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="rack_capacity"></p>

                                    </div>

                               

                                </div><hr>

                                <div id="dynamic_field16">

                                <div class="row" >

                                         <div class="col-sm-2 form-group">

                                         <label> Sub Rack No.*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="sub_rock_no"></p>

                                         </div>

                                       <div class="col-sm-2 form-group">

                                         <label> Sub Rack Capacity*</lable>

                                        <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="sub_rock_capacity"></p>

                                        </div>

                                </div>

                                    <div id="dynamic_field15">

                                       <div class="row">

                                            <div class="col-sm-2 form-group">

                                                <label> Bin No.*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_no"></p>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                 <label> Bin Capacity*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_capacity"></p>

                                             </div>

                                            <div class="col-sm-2 form-group">

                                                <button type="button" name="add" id="add15" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                            </div>

                                        </div>

                                    </div>

                                    <div id="dynamic_field14">

                                       <div class="row">

                                            <div class="col-sm-2 form-group">

                                             <label>Sub Bin No.*</lable>

                                            <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="sub_bin_no[]"></p>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                 <label>Sub Bin Capacity*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="sub_bin_capacity[]"></p>

                                             </div>

                                                <div class="col-sm-2 form-group">

                                                    <button type="button" name="add" id="add14" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                                </div>

                                        </div>

                                        <button type="button" name="add" id="add16" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                    </div>

                                    

                                    

                                    </div>

                                </div>

                                  <div class="tab">

                                 <h5>Sheilf Details</h5> <br>

                                    <div class="row">

                                            <div class="col-sm-2 form-group">

                                                <label>Total Shelf*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_no"></p>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                 <label> Total Shelf Capacity*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_capacity"></p>

                                             </div>

                                             

                                    </div>

                                    <div id="dynamic_field18">

                                     <div class="row">

                                            <div class="col-sm-2 form-group">

                                                <label>Shelf No.*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_no"></p>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                 <label> Shelf Capacity*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_capacity"></p>

                                             </div>

                                            

                                    </div>

                                     <div class="row" id="dynamic_field17">

                                            <div class="col-sm-2 form-group">

                                                <label>Sub Shelf No.*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_no[]"></p>

                                            </div>

                                             <div class="col-sm-2 form-group">

                                                 <label>Sub Shelf Capacity*</lable>

                                                <p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="bin_capacity[]"></p>

                                             </div>

                                              <div class="col-sm-2 form-group">

                                                    <button type="button" name="add" id="add17" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                                </div>

                                      </div>

                                      <div class="col-sm-2 form-group">

                                                    <button type="button" name="add" id="add18" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button>

                                                </div>

                                    </div>

                          

                           </div>

                        </div>

                      <br> <br>

                      <div style="overflow:auto;">

                        <div style="float:right;">

                             <button class="btn btn1 float-right" style="margin: 5px;">Draft & Save</button>

                          <button class="btn btn1 float-right" style="margin: 5px;">Clear All</button>

                          <button type="button" class="btn btn1 float-right" id="prevBtn" onclick="nextPrev(-1)">Previous</button>

                          <button type="button" class="btn btn1 float-right" id="nextBtn" onclick="nextPrev(1)">Submit & Next</button>

                        </div>

                      </div>

                      

                      <!-- Circles which indicates the steps>-->

                        <div style="text-align:center;margin-top:40px;display: none;">

                            <span class="step"></span>

                            <span class="step"></span>

                            <span class="step"></span>

                            <span class="step"></span>

                          </div>

                    </form>

               </div>

            </div>

         </div>

      </section>

     

   </div>

</div>

 <!--<div>-->

 <!--   <h5 style="text-align:center;">Copyright © <a href="http://maestrosinfotech.com/">Maestros Infotech</a></h5>-->

 <!-- </div> -->

</section>





<script>

var currentTab = 0; // Current tab is set to be the first tab (0)

showTab(currentTab); // Display the current tab



function showTab(n) {

  // This function will display the specified tab of the form...

  var x = document.getElementsByClassName("tab");

  x[n].style.display = "block";

  //... and fix the Previous/Next buttons:

  if (n == 0) {

    document.getElementById("prevBtn").style.display = "none";

  } else {

    document.getElementById("prevBtn").style.display = "inline";

  }

  if (n == (x.length - 1)) {

    document.getElementById("nextBtn").innerHTML = "Submit & Next";

  } else {

    document.getElementById("nextBtn").innerHTML = "Submit & Next";

  }

  //... and run a function that will display the correct step indicator:

  fixStepIndicator(n)

}



function nextPrev(n) {

  // This function will figure out which tab to display

  var x = document.getElementsByClassName("tab");

  // Exit the function if any field in the current tab is invalid:

//   if (n == 1 && !validateForm()) return false;

  // Hide the current tab:

  x[currentTab].style.display = "none";

  // Increase or decrease the current tab by 1:

  currentTab = currentTab + n;

  // if you have reached the end of the form...

  if (currentTab >= x.length) {

    // ... the form gets submitted:

    document.getElementById("regForm").submit();

    return false;

  }

  // Otherwise, display the correct tab:

  showTab(currentTab);

}



function validateForm() {

  // This function deals with validation of the form fields

  var x, y, i, valid = true;

  x = document.getElementsByClassName("tab");

  y = x[currentTab].getElementsByTagName("input");

  // A loop that checks every input field in the current tab:

  for (i = 0; i < y.length; i++) {

    // If a field is empty...

    if (y[i].value == "") {

      // add an "invalid" class to the field:

      y[i].className += " invalid";

      // and set the current valid status to false

      valid = false;

    }

  }

  // If the valid status is true, mark the step as finished and valid:

  if (valid) {

    document.getElementsByClassName("step")[currentTab].className += " finish";

  }

  return valid; // return the valid status

}



function fixStepIndicator(n) {

  // This function removes the "active" class of all steps...

  var i, x = document.getElementsByClassName("step");

  for (i = 0; i < x.length; i++) {

    x[i].className = x[i].className.replace(" active", "");

  }

  //... and adds the "active" class on the current step:

  x[n].className += " active";

}

</script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<script>

        $(document).ready(function(){

            var i = 1;

            $('#add').click(function(){

                i++;

                $('#dynamic_field').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><label>Address<span class="req">*</span></label><input type="text" autocomplete="off" class="form-control" name="address[]"/></div><td><button style="margin-top: 24px;" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

    <script>

        $(document).ready(function(){

            var i = 1;

            $('#add1').click(function(){

                i++;

                $('#dynamic_field1').append('<tr id="row'+i+'"><td><div class="field-wrap"><label style="display:flex;">Add Field Manually<span class="req">*</span></label><input style="border-radius: 12px;" type="text" autocomplete="off" class="form-control" placeholder="Enter Manually (Doc No)" name="add_other1[]"></div></td><td><div class="field-wrap"><label style="display:flex;">Add Field Attachement Manually<span class="req">*</span></label><input  type="file" style="border-radius: 12px;"  placeholder="Auto Fetch From Statutory" name="pollution_cerificate_attch[]"></div></td><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

       <script>

        $(document).ready(function(){

            var i = 1;

            $('#add2').click(function(){

                i++;

                $('#dynamic_field2').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="boundary_type[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add3').click(function(){

                i++;

                $('#dynamic_field3').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="attachement[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add4').click(function(){

                i++;

                $('#dynamic_field4').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="add_other4[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add5').click(function(){

                i++;

                $('#dynamic_field5').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="uom2[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

       <script>

        $(document).ready(function(){

            var i = 1;

            $('#add6').click(function(){

                i++;

                $('#dynamic_field6').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="uom2[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add7').click(function(){

                i++;

                $('#dynamic_field7').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="add_other3[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add8').click(function(){

                i++;

                $('#dynamic_field8').append('<tr id="row'+i+'"><td> <div class="col-sm-2 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Enter Field Name" name="add_other4[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Count" name="urinals[]"/></div></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add9').click(function(){

                i++;

                $('#dynamic_field9').append('<tr id="row'+i+'"><td><div class="field-wrap"><div class="field-wrap"><input type="text" autocomplete="off" class="form-control" name="source_electricity[]"/></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });



            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

      <script>

        $(document).ready(function(){

            var i = 1;

            $('#add10').click(function(){

                i++;

                $('#dynamic_field10').append('<tr id="row'+i+'"><td><div class="row"> <div class="col-sm-2 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="generator[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="generator_capacity[]"/></div></div><td><div class="col-sm-2 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr></div>');

            });

//                                           <div class="row" id="row'+i+'"><div class="col-sm-2 form-group"><label>Generator*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p></div><div class="col-sm-2 form-group"><label>Generator Capacity*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p></div><div class="col-sm-2 form-group"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div </div>

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

     <script>

        $(document).ready(function(){

            var i = 1;

            $('#add11').click(function(){

                i++;

                $('#dynamic_field11').append('<tr id="row'+i+'"><td><div class="row"> <div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="warehouse_no[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="warehouse_type[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr></div>');

            });

//                                           <div class="row" id="row'+i+'"><div class="col-sm-2 form-group"><label>Generator*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p></div><div class="col-sm-2 form-group"><label>Generator Capacity*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p></div><div class="col-sm-2 form-group"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div </div>

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

       <script>

        $(document).ready(function(){

            var i = 1;

            $('#add12').click(function(){

                i++;

                $('#dynamic_field12').append('<tr id="row'+i+'"><td> <div class="row"><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="room_name[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="room_count[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr></div>');

            });

//                                           <div class="row" id="row'+i+'"><div class="col-sm-2 form-group"><label>Generator*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p></div><div class="col-sm-2 form-group"><label>Generator Capacity*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p></div><div class="col-sm-2 form-group"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div </div>

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

        <script>

        $(document).ready(function(){

            var i = 1;

            $('#add13').click(function(){

                i++;

                $('#dynamic_field13').append('<tr id="row'+i+'"><td> <div class="row"><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="asset_type[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="asset_name[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="asset_sl_no[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="date_of_p[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="supplier_name[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="invoice_no[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="qty[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="org[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="used_by[]"/></div></div> <div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="used_in[]"/></div></div><div class="col-sm-2 form-group"><div class="field-wrap"><label style="display:flex;"> Asset Type*<span class="req">*</span></label><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="location[]"/></div></div></div><td><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');

            });

//                                           <div class="row" id="row'+i+'"><div class="col-sm-2 form-group"><label>Generator*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p></div><div class="col-sm-2 form-group"><label>Generator Capacity*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p></div><div class="col-sm-2 form-group"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div </div>

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

        <script>

        $(document).ready(function(){

            var i = 1;

            $('#add14').click(function(){

                i++;

                $('#dynamic_field14').append('<tr id="row'+i+'"><td> <div class="row"><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="sub_bin_no[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="sub_bin_capacity[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr></div>');

            });

//                                           <div class="row" id="row'+i+'"><div class="col-sm-2 form-group"><label>Generator*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator[]"></p></div><div class="col-sm-2 form-group"><label>Generator Capacity*</lable><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = ''" name="generator_capacity[]"></p></div><div class="col-sm-2 form-group"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div </div>

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

      <script>

        $(document).ready(function(){

            var i = 1;

            $('#add15').click(function(){

                i++;

                $('#dynamic_field15').append('<tr id="row'+i+'"><td> <div class="row"><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_no[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_capacity[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr></div>');

            });

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

    <script>

    $(document).ready(function() {

        var i = 1;

        $('#add16').click(function() {

            i++;

            $('#dynamic_field16').append('<tr id="row'+i+'"><td><div class="row"><div class="col-sm-2 form-group"><label> Sub Rack No.*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="sub_rack_no"></p></div><div class="col-sm-2 form-group"><label> Sub Rack Capacity*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="sub_rack_capacity"></p></div></div><div id="dynamic_field15"><div class="row"><div class="col-sm-2 form-group"><label> Bin No.*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="bin_no"></p></div><div class="col-sm-2 form-group"><label> Bin Capacity*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="bin_capacity"></p></div><div class="col-sm-2 form-group"><button type="button" name="add" id="add15" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></div></div></div><div id="dynamic_field14"><div class="row"><div class="col-sm-2 form-group"><label>Sub Bin No.*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="sub_bin_no[]"></p></div><div class="col-sm-2 form-group"><label>Sub Bin Capacity*</label><p><input style="border-radius: 12px;" placeholder="" oninput="this.className = \'\'" name="sub_bin_capacity[]"></p></div><div class="col-sm-2 form-group"><button type="button" name="add" id="add14" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i></button></div></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></div></td></tr>');

        });

        $(document).on('click', '.btn_remove', function() {

            var button_id = $(this).attr("id");

            $("#row"+button_id+"").remove();

        });

    });

</script>

   <script>

        $(document).ready(function(){

            var i = 1;

            $('#add17').click(function(){

                i++;

                $('#dynamic_field17').append('<tr id="row'+i+'"><td> <div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_no[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_capacity[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></td></tr></div>');

            });

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

       <script>

        $(document).ready(function(){

            var i = 1;

            $('#add18').click(function(){

                i++;

                $('#dynamic_field18').append('<tr id="row'+i+'"><td> <div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_no[]"/></div></div><div class="col-sm-3 form-group"><div class="field-wrap"><input type="text"  autocomplete="off" class="form-control" placeholder="Manual Entry" name="bin_capacity[]"/></div></div><td><div class="col-sm-3 form-group"><div class="field-wrap"><button name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></div></td></tr></div>');

            });

            $(document).on('click','.btn_remove', function(){

                var button_id = $(this).attr("id");

                $("#row"+button_id+"").remove();

            });



        });

    </script>

 @endsection 

