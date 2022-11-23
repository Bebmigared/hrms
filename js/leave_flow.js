$(function(){
  let status;
  let leave_id;
  let flow;
  let approval_details;
  let leave_flow;
  let remark = '';

  $('.remark').on('keyup', function(e){
      //alert('aa');
    //   let this_leave_flow = $("#"+this.id+"").attr('leave_flow');
    //   let this_full_leave_flow = $("#"+this.id+"").attr('full_leave_flow');
    //   let this_all_flow = this_full_leave_flow.split(";");
    //   let email  = $("#"+this.id+"").attr('email');
    //   for(let r = 0; r < this_all_flow.length; r++){
    //   let each_flow = this_all_flow[r].split(":")[0];
    //   let this_email = this_all_flow[r].split(":")[1];
    //   //alert(each_flow);
    //   if(each_flow.toLowerCase() == this_leave_flow.toLowerCase() && email == this_email){
    //     //to_continue = true;
    //     remark = $("#"+this.id+"").val();
    //   }
    // }

      let canapprove = $("#"+this.id+"").attr("canapproval");
      let approvalId = $("#"+this.id+"").attr('approvalId');
      let myId = $("#"+this.id+"").attr('myId');
      let attr_id = $("#"+this.id+"").attr('attr_id');
      if(approvalId != myId || canapprove == false) return false;
      remark = $("#"+this.id+"").val();
    //alert(remark);
  });
  $('.badge_leave').on("click", function(e){
    e.preventDefault();
    let _id;
    let approval;
    let s;
    let f;
    let all_flow = [];
    let to_continue = false;
    let canapprove = $("#"+this.id+"").attr("canapproval");
    let approvalId = $("#"+this.id+"").attr('approvalId');
    let myId = $("#"+this.id+"").attr('myId');
    status = $("#"+this.id+"").attr('status');
    let attr_id = $("#"+this.id+"").attr('attr_id');
    if(approvalId == myId && canapprove == true) to_continue = true;
    if(to_continue == false) return false;

    //alert(status);

    $('#approve'+attr_id+'').find("i").remove();
    $('#decline'+attr_id+'').find("i").remove();
    $('#pend'+attr_id+'').find("i").remove();
    $("#"+this.id+"").append('<i class="fas fa-check" style="padding: 0px;color: #4e73df; font-size:14px;"></i>');
   
  });
 
    $('#submit_btn_leave').on('click', function(e){
    e.preventDefault();
    let item_id = $('#submit_btn_leave').attr('item_id');
    let approvallistId = $("#submit_btn_leave").attr('approvallistId');

    var host = window.location.host;
    if(item_id == ''){
      Swal.fire({
        type: 'Notice',
        title: 'Empty',
        text: 'Please select an action',
        footer: 'Thank you'
      });
      return false;
    } 

    if(status == undefined)
    {
       status = $("#submit_btn_leave").attr('status');
       
    }

    if(remark == '')
    {
       remark = $("#submit_btn_leave").attr('remark');
    }
    

    if(status != 'approved' && status != 'decline' && status != 'pend')
    {
      alert('Kindly Check the Request Status');
      return false;
    }
  
  // console.log(status,remark,item_id,approvallistId);
  // return false;
    
    if(host == 'localhost')
    {
       window.location.href = "/newhrcore/process_leave_approvals_remark.php/?approvalflowId="+btoa(approvallistId)+"&status="+btoa(status)+"&leave_id="+btoa(item_id)+"&remark="+btoa(remark);
    }else{
         window.location.href = "/process_leave_approvals_remark.php/?approvalflowId="+btoa(approvallistId)+"&status="+btoa(status)+"&leave_id="+btoa(item_id)+"&remark="+btoa(remark);
    }
  });
})
