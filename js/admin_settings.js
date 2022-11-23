$(function(){
	let branch = [];
	let department = [];
	let roleflow = [];
	let appraisalflow = [];
	let leaveflow = [];
	let cashflow = [];
	let exitflow= [];
	let priviledge = [];
	let isadded_forleave = false;
	let isadded = false;
	let isadded_branch = false;
	let isadded_dept = false;
	let requisitionflow = [];
	let isadded_req = false;
	let isadded_forcash = false;
	let isadded_forexit = false;
	let isadded_role = false;
	let is_leave_reset = 0;
	let is_req_reset = 0;
	let is_app_reset = 0;
	let is_cash_reset = 0;
	let is_exit_reset = 0;
	var host = window.location.host;
	//alert(host);
	
	$("#add_branch").on("click", function(e){
		e.preventDefault();
		let val = $('#branch').val();
		let d = $("#add_branch").attr('branch');
        if(d != "") {
        	if(isadded_branch == false){ branch = d.split(";"); isadded_branch = true;}
        }
        //alert(branch);
		if(val == '') return false;
		branch.push(val);
		$('#listbranch').append("<li class='list-group-item'>"+val+"</li>");
		$('#branch').val('');
	});
	$("#add_dept").on("click", function(e){
		e.preventDefault();
		let val = $('#dept').val();
		let d = $("#add_dept").attr('dept');
        if(d != "") {
        	if(isadded_dept == false){ department = d.split(";"); isadded_dept = true;}
        }
        //alert(department);
		if(val == '') return false;
		department.push(val);
		$('#listdept').append("<li class='list-group-item'>"+val+"</li>");
		$('#dept').val('');
	});
	$("#add_role").on("click", function(e){
		e.preventDefault();
		let val = $('#role').val();
		let d = $("#add_role").attr('dept');
        if(d != "") {
        	if(isadded_role == false){ role = d.split(";"); isadded_role = true;}
        }
        //alert(val);
		if(val == '') return false;
		roleflow.push(val);
		$('#listrole').append("<li class='list-group-item'>"+val+"</li>");
		$('#role').val('');
	});
	$("#appraisal_aprroval").on('click', function(e){
       e.preventDefault();
       let appraisal = $("#appraisal_aprroval").attr('appraisal_flow');
       if(appraisal != "" && is_app_reset == 0) {
       	if(isadded == false){ appraisalflow = appraisal.split(";"); isadded = true;}
       }
       //alert(appraisalflow);
       let val = $('#approval').val();
       if(val == "") return false;
       appraisalflow.push(val.trim());
       //alert(appraisalflow);
       $(".app_flow").append("<li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span>"+val+" </a></li>");
       $('#approval').val('');
	});
	$("#reset_approval").on('click', function(e){
		e.preventDefault();
		appraisalflow = [];
		is_app_reset = 1;
		//$("#appraisal_flow").attr()
		$(".app_flow").empty();
		$(".app_flow").append("<li><a class='active'>Staff </a></li>");
	});
	$("#reset_leave_approval").on('click', function(e){
		e.preventDefault();
		leaveflow = [];
		is_leave_reset = 1;
		$(".leave_flow").empty();
		$(".leave_flow").append("<li><a class='active'>Staff </a></li>");
	});
	$("#leave_approval").on('click', function(e){
       e.preventDefault();
       let leave = $("#leave_approval").attr('leave_flow');
       if(leave != "" && is_leave_reset == 0) {
       	if(isadded_forleave == false){ leaveflow = leave.split(";"); isadded_forleave = true;}
       };
       //alert(leaveflow);
       let val = $('#leave_level').val();
       if(val == "") return false;
       leaveflow.push(val.trim());
       $(".leave_flow").append("<li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span>"+val+" </a></li>");
       $('#leave_level').val('');
	});
	$("#cash_approval").on('click', function(e){
       e.preventDefault();
       let cash = $("#cash_approval").attr('cash_flow');
       if(cash != "" && is_cash_reset == 0) {
       	if(isadded_forcash == false){ cashflow = cash.split(";"); isadded_forcash = true;}
       };
       //alert(leaveflow);
       let val = $('#cash_level').val();
       if(val == "") return false;
       cashflow.push(val.trim());
       $(".c_flow").append("<li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span>"+val+" </a></li>");
       $('#cash_level').val('');
       //alert(cashflow);
	});
	$("#exit_approval").on('click', function(e){
       e.preventDefault();
       //alert('aaa');
       let exit = $("#exit_approval").attr('exit_flow');
       if(exit != "" && is_exit_reset == 0) {
       	if(isadded_forexit == false){ exitflow = exit.split(";"); isadded_forexit = true;}
       };
       
       let val = $('#exit_level').val();
       //alert(val);
       if(val == "") return false;
       exitflow.push(val.trim());
       $(".e_flow").append("<li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span>"+val+" </a></li>");
       $('#exit_level').val('');
       //alert(cashflow);
	});
	$("#reset_exit").on('click', function(e){
		e.preventDefault();
		exitflow = [];
		is_exit_reset = 1;
		//alert('aaa');
		$(".e_flow").empty();
		$(".e_flow").append("<li><a class='active'>Staff </a></li>");
	});
	$("#reset_cash").on('click', function(e){
		e.preventDefault();
		cashflow = [];
		is_cash_reset = 1;
		$(".c_flow").empty();
		$(".c_flow").append("<li><a class='active'>Staff </a></li>");
	});
	/*$("#reset_leave").on('click', function(e){
		e.preventDefault();
		$(".leave_flow").empty();
		leaveflow = [];
		is__reset = 1;
		$(".leave_flow").append("<li><a class='active'>Staff </a></li>");
	});*/
	$("#requistion_approval").on('click', function(e){
       e.preventDefault();
       let requistion = $("#requistion_approval").attr('requisition_flow');
       //alert(requistion);
       if(requistion != "" && is_req_reset == 0) {
       	if(isadded_req == false){ requisitionflow = requistion.split(";"); isadded_req = true;}
       }
       //alert(appraisalflow);
       let val = $('#requisition_level').val();
       if(val == "") return false;
       requisitionflow.push(val.trim());
       //alert(appraisalflow);
       $(".req_flow").append("<li><a class=''><span style='margin-left:2px;margin-right:7px;'>&#x27F6;</span>"+val+" </a></li>");
       $('#requisition_level').val('');
	});
	$("#reset_req").on('click', function(e){
		e.preventDefault();
		requisitionflow = [];
		is_req_reset = 1;
		$(".req_flow").empty();
		$(".req_flow").append("<li><a class='active'>Staff </a></li>");
	});
	$('#submit_btn').on('click', function(e){
		e.preventDefault();
		//alert(appraisalflow);
		if(requisitionflow.length == 0 && is_req_reset == 0) $("#requistion_approval").trigger("click");
		if(leaveflow.length == 0 && is_leave_reset == 0) $("#leave_approval").trigger("click");
		if(cashflow.length == 0 && is_cash_reset == 0) $("#cash_approval").trigger("click");
		if(exitflow.length == 0 && is_exit_reset == 0) $("#exit_approval").trigger("click");
		if(appraisalflow.length == 0 && is_app_reset == 0) $("#appraisal_aprroval").trigger("click");
		if(branch.length == 0) $("#add_branch").trigger("click");
		if(department.length == 0) $("#add_dept").trigger("click");
		let company_name =  $("#company_name").val();
		let address =  $('#address').val();
		let file = document.getElementById('loadfile').files[0];
		let data = {
			cashflow : cashflow.length > 0 ? cashflow.join(";") : "",
			leaveflow : leaveflow.length > 0 ? leaveflow.join(";") : "",
			appraisalflow : appraisalflow.length > 0 ? appraisalflow.join(";") : "", 
			requisitionflow : requisitionflow.length > 0 ? requisitionflow.join(";") : "",
			exitflow : exitflow.length > 0 ? exitflow.join(";") : "",
			company_name: company_name,
			branch: branch.length > 0 ? branch.join(";") : "",
			department:department.length > 0 ? department.join(";") : "",
			address: address,
			role: roleflow.join(";")
		};
		if(data['requistionflow'] == '' && data['leaveflow'] == '' && data['appraisalflow'] == '' && data['company_name'] == '' && data['branch'] == '' && department == "" && address == ""){
			Swal.fire({
			  type: 'error',
			  title: 'Empty',
			  text: 'Please complete the form!',
			  footer: 'Thank you'
			});
			return false;
		}
		//alert(data.branch);
		let formdata = new FormData();
		formdata.append('leaveflow', data.leaveflow);
		formdata.append('cashflow', data.cashflow);
		formdata.append('exitflow', data.exitflow);
		formdata.append('appraisalflow', data.appraisalflow);
		formdata.append('requisitionflow', data.requisitionflow);
		formdata.append('company_name',company_name);
		formdata.append('branch', data.branch);
		formdata.append('department',data.department);
		formdata.append("role", data.role);
		formdata.append('address',address);
		formdata.append('submit', 'true');
		if(file) formdata.append('image', file);
		//alert(data.role);
		//return false;
		$('#submit_btn').text('Processing....');
		//$('#submit_btn').attr('disabled','true');
		$.ajax({
			type : 'post',
			url: 'process_admin_data.php',
			data: formdata,
			processData:false,
			contentType:false,
			success:function(data){
				$('#submit_btn').text('Submit');
		        //$('#submit_btn').attr('disabled','false');
				//alert(data);
				//console.log(data);
				if(data == true){
					Swal.fire({
					  position: 'top-end',
					  type: 'success',
					  title: 'Your update is Noted',
					  showConfirmButton: false,
					  timer: 1500
					});
					//window.location.href = host == 'localhost' ? '/newhrcore/admin_settings.php' : '/admin_settings.php';
				}else{
					//window.location.href = host == 'localhost' ? '/newhrcore/admin_settings.php' :'/admin_settings';
				}
			}

		})

	});
	$('#openfile').on('click', function(e){
		$('#loadfile').trigger('click');
	});
});
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