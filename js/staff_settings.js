$(function(){
   $('#showfile').on('click', function(e){
     $('#loadimg').trigger('click');
    });
});
function validateEmail(email) {
      var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
      return re.test(String(email).toLowerCase());
}
let leaveflow = [];
let appraisalflow = [];
let reqflow = [];
let cashflow = [];
alert('ass');
$('.lmanager').on('change', function(e){
    let id = this.id;
    let val = $(this).val();
    leaveflow[id] = val;
    alert(leaveflow);
});
$('.amanager').on('change', function(e){
    let id = this.id;
    let val = $(this).val();
    appraisalflow[id] = val;
});
$('.rmanager').on('change', function(e){
    let id = this.id;
    let val = $(this).val();
    reqflow[id] = val;
});
$('.cmanager').on('change', function(e){
    let id = this.id;
    let val = $(this).val();
    cashflow[id] = val;
});
$('#staff_btn').on('click',function(e){
	e.preventDefault();
	let approvals = [];
	let l_approvals = [];
	let r_approvals = [];
	let c_approvals = [];
	let appraisal_approvals = [];
	let request_approvals = [];
	let cashs_approvals = [];
	let leave_approvals = [];
	let name = $('#name').val();
	let fname = $('#fname').val();
	let mname = $('#mname').val();
	let phone_number = $('#phone_number').val();
	let email = $('#email').val();
	let position = $('#position').val();
	let department = $('#department').val();
	let branch = $('#branch').val();
	let gender = $('#gender').val();
	let marital_status = $('#marital_status').val();
	let dob = $('#dob').val();
	let town = $('#town').val();
	let lga = $('#lga').val();
	let sorigin = $('#sorigin').val();
	let sresidence = $('#sresidence').val();
	let on_hmo = $('#on_hmo').val();
	let hmo = $('#hmo').val();
	let hmo_number = $('#hmo_number').val();
	let hmo_plan = $('#hmo_plan').val();
	let hmo_hospital = $('#hmo_hospital').val();
	let hmo_status = $('#hmo_status').val();
	let hmo_remarks = $('#hmo_remarks').val();
	let pension = $('#pension').val();
	let pension_number = $('#pension_number').val();
	let hmo = $('#hmo').val();
	if(leaveflow.length > 0) 
	 leaveflow = leaveflow.split(';');
	else leaveflow = ''; 
	if(appraisalflow.length > 0) 
	 appraisalflow = appraisalflow.split(';');
	else appraisalflow = ''; 
	if(reqflow.length > 0) 
	 reqflow = reqflow.split(';');
	else reqflow = ''; 
	if(cashflow.length > 0) 
	 cashflow = cashflow.split(';');
	else cashflow = ''; 
	
	let formdata =  new FormData();
	formdata.append('department',department);
	formdata.append('branch',branch);
	formdata.append('employee_ID',$("#employee_ID").val());
	formdata.append('phone_number',$("#phone_number").val());
	formdata.append('name', $("#name").val());
	formdata.append('role',$("#role").val());
	formdata.append('position', $('#position').val());
	formdata.append('submit','true');
    formdata.append('all_leave_approvals',all_leave_approvals);
	formdata.append('all_appraisal_approvals',all_appraisal_approvals);
	formdata.append('all_requisition_approvals', all_requisition_approvals);
	formdata.append('all_cash_approvals', all_cash_approvals);
	$.ajax({
		type: 'POST',
		url : 'process_staff_data.php',
        data: formdata,
        processData:false,
	    contentType:false,
	    success:function(data){
	     //alert(data);
          if(data == true){
					Swal.fire({
					  position: 'top-end',
					  type: 'success',
					  title: 'Your update is Noted',
					  showConfirmButton: false,
					  timer: 1500
					});
		   }else{
					window.location.href = '/selfservice/staff_settings.php';
		 }
	    }
	})
	//alert(all_appraisal_approvals);
	//alert(all_leave_approvals);
})
function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
        $('.uploadimg')
            .attr('src', e.target.result)
            .width(100)
            .height(100);
      };
      reader.readAsDataURL(input.files[0]);
     }
    }