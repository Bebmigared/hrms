$(function(){
  let status;
  let item_id;
  let flow;
  let approval_details;
  let requisition_flow;
  let remark = '';
function userupdate(){

}  

  $('.req_remark').on('keyup', function(e){
     
      let canapprove = $("#"+this.id+"").attr("canapproval");
      let approvalId = $("#"+this.id+"").attr('approvalId');
      let myId = $("#"+this.id+"").attr('myId');
      let attr_id = $("#"+this.id+"").attr('attr_id');
      if(approvalId != myId || canapprove == false) return false;
      remark = $("#"+this.id+"").val();
  //}
    //alert(remark);
  });
$('.badge_request').on("click", function(e){
    e.preventDefault();
    let _id;
    let approval;
    let s;
    let f;
    let all_flow = [];
    let to_continue = false;

    // let category = $("#"+this.id+"").attr('category');
    // requisition_flow = $("#"+this.id+"").attr('requisition_flow');
    // let email = $("#"+this.id+"").attr('email');
    // let each_process_status = $("#"+this.id+"").attr('each_process_status');
    // //alert(requisition_flow);
    // full_requisition_flow = $("#"+this.id+"").attr('full_requisition_flow');
    // all_flow = full_requisition_flow.split(";");
    // for(let r = 0; r < all_flow.length; r++){
    //   let each_flow = all_flow[r].split(":")[0];
    //   let this_email = all_flow[r].split(":")[1];
    //   //alert(each_flow.toLowerCase()+'_'+requisition_flow.toLowerCase());
    //   if(each_flow.toLowerCase() == requisition_flow.toLowerCase() && email == this_email && each_process_status == 'approved'){
    //     to_continue = true;
    //   }
    // }
    let canapprove = $("#"+this.id+"").attr("canapproval");
    //alert(canapprove);
    let approvalId = $("#"+this.id+"").attr('approvalId');
    let myId = $("#"+this.id+"").attr('myId');
    status = $("#"+this.id+"").attr('status');
    let attr_id = $("#"+this.id+"").attr('attr_id');
    // alert(myId);
    if(approvalId == myId && canapprove == true) to_continue = true;
    if(to_continue == false) return false;

    $('#approve'+attr_id+'').find("i").remove();
    $('#decline'+attr_id+'').find("i").remove();
    $('#pend'+attr_id+'').find("i").remove();
    $("#"+this.id+"").append('<i class="fas fa-check" style="padding: 0px;color: #4e73df; font-size:14px;"></i>');
    // s = $("#"+this.id+"").attr('status');
    // let val = $("#"+this.id+"").children().length;
    // if(val == 0){
    //   approval = $("#"+this.id+"").attr('approval');
    //   _id = $("#"+this.id+"").attr('item_id');
    //   f =  $("#"+this.id+"").attr('flow');
    //   //alert(f);
      
    //   $("#approve"+attr_id+"").find("i").remove();
    //   $("#decline"+attr_id+"").find("i").remove();
    //   $("#pend"+attr_id+"").find("i").remove();
    //   $("#"+this.id+"").append('<i class="fas fa-check" style="padding: 0px;color: #4e73df; font-size:14px;"></i>');
    //   item_id =_id;
    //   approval_details = approval;
    //   status = s;
    //   flow = f;
    // }else {
    //   $("#"+this.id+"").find("i").remove();
    //   status = '';
    //   item_id = '';
    //   approval_details = '';
    //   flow = '';
    // }
  });
    $('#submit_btn').on('click', function(e){
    e.preventDefault();
    let item_id = $('#submit_btn').attr('item_id');
    let approvallistId = $("#submit_btn").attr('approvallistId');

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
       status = $("#submit_btn").attr('status');
       
    }

    if(remark == '')
    {
       remark = $("#submit_btn").attr('remark');
    }

    // console.log(item_id, approvallistId, status, remark);
    // return false;

  
    if(host != 'localhost')
       window.location.href = "/process_requisition_approvals_remark.php/?approvalflowId="+btoa(approvallistId)+"&status="+btoa(status)+"&item_id="+btoa(item_id)+"&remark="+btoa(remark);
    else
       window.location.href = "/newhrcore/process_requisition_approvals_remark.php/?approvalflowId="+btoa(approvallistId)+"&status="+btoa(status)+"&item_id="+btoa(item_id)+"&remark="+btoa(remark);
  });
});