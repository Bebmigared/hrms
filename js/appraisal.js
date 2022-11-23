$(function(){
	let current = 0;
  let question = 0;
	let remark = [];
    let remark_meaning = [];
	let justification = [];
    let is_created = false;
    let appraisal_data_array = [];
    let manager_remark = [];
    let manager_justification = [];

  $('.getdata1').on('click', function(e){
    var host = window.location.host;
    let app = $("#"+this.id+"").attr('appraisal_id');
    var url;
    if(host == 'localhost')
        url = "/newhrcore/get_appraisal_for_staff.php?appraisal_id="+btoa(app);
    else  
        url = "/get_appraisal_for_staff.php?appraisal_id="+btoa(app);
    window.location.href = url; 
  });
    
	$('.getdata').on('click', function(e){
    var host = window.location.host;
    let app = $("#"+this.id+"").attr('appraisal_id');
    var url;
    if(host == 'localhost')
        url = "/newhrcore/get_appraisal_for_staff.php?appraisal_id="+btoa(app);
    else  
        url = "/get_appraisal_for_staff.php?appraisal_id="+btoa(app);
    window.location.href = url; 
  });

    $('.manager_justification').on('keyup', function(e){
       let appraisal_data = $(this).attr('appraisal_data');
       appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;  
       //let question = $(this).attr('question');
       let who = $(this).attr('who');
       let email = $(this).attr('address');
       let q = question + 1;

       let data = { justification : $(this).val(), email : email, who : who, question : (question+1) };

       if( appraisal_data_array[parseInt(question)].manager_justification != undefined)
       {
        //alert('allit');
           let index = appraisal_data_array[parseInt(question)].manager_justification.findIndex(x => x.question == q && x.who == who );
           //alert(index);

           if(index > -1)
              appraisal_data_array[parseInt(question)].manager_justification[index] = data;
           else 
              appraisal_data_array[parseInt(question)].manager_justification.push(data);
       }else {
        //alert('okies');
              appraisal_data_array[parseInt(question)].manager_justification = [];
              appraisal_data_array[parseInt(question)].manager_justification.push(data);
       }

       $("#managerappraisallist").val(JSON.stringify(appraisal_data_array));

     // alert(JSON.stringify(appraisal_data_array[parseInt(question)]));

    });
    $('.manager_remark').on('change', function(e){
       let appraisal_data = $(this).attr('appraisal_data');
       appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;  
       //let question = $(this).attr('question');
       let who = $(this).attr('who');
       let email = $(this).attr('address');
       let q = parseInt(question) + 1;
       let score = parseFloat(appraisal_data_array[parseInt(question)].weight) * parseInt($(this).val());
       let data = { remark : $(this).val(), email : email, who : who, question : q, score :score };
       if( appraisal_data_array[parseInt(question)].manager_remark != undefined)
       {
           let index = appraisal_data_array[parseInt(question)].manager_remark.findIndex(x => x.question == q );
           if(index > -1 && appraisal_data_array[parseInt(question)].manager_justification[index].who == who)
              appraisal_data_array[parseInt(question)].manager_remark[index] = data;
           else 
              appraisal_data_array[parseInt(question)].manager_remark.push(data);
       }else {
              appraisal_data_array[parseInt(question)].manager_remark = [];
              appraisal_data_array[parseInt(question)].manager_remark.push(data);
       }
       //alert(JSON.stringify(appraisal_data_array[question]));
       $("#managerappraisallist").val(JSON.stringify(appraisal_data_array));
    })
    $("#next").on('click',function(e){
    	//let appraisal_data_array = [];
    	let appraisal_data = $(this).attr('appraisal_data');
    	appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;// appraisal_data.split("%%%");
      appraisal_data_array[current].remark = $('#remark').val();
      appraisal_data_array[current].justification = $('#justification').val();
      appraisal_data_array[current].score = parseFloat(appraisal_data_array[current].weight) * parseInt(appraisal_data_array[current].remark);
      //alert('okie');
      //alert(appraisal_data_array[current].score);
    	let next = current + 1;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        //alert(JSON.stringify(appraisal_data_array));
        if(next < appraisal_data_array.length){
        	current = next;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){
                    if(remark[next] != undefined) $("#remark").val(remark[next]);
                    else $("#remark").val('0');
                    if(justification[next] != undefined) $("#justification").val(justification[next]);
                    else $("#justification").val('');
                    $(this).html("<h5>Question "+(next+1)+"</h5><p>"+appraisal_data_array[next].question+"</p> <p style = 'text-align:justify'>Weight "+appraisal_data_array[next].weight+"%</p>");
                    $('#stage').html(""+(next + 1)+"/"+appraisal_data_array.length);
                });
                if(next == appraisal_data_array.length - 1 && is_created == false){
                    is_created = true;
                    $(".main_btn").append("<button type='button' class='btn btn-success' appraisal_data = '"+appraisal_data+"'  style='margin: 4px;' id='review'>Review</button>");
                }
            });
        }

    });
    $("#previous").on('click',function(e){
    	//let appraisal_data_array = [];

    	let appraisal_data = $(this).attr('appraisal_data');
    	appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;
      appraisal_data_array[current].remark = $('#remark').val();
      appraisal_data_array[current].justification = $('#justification').val();
    	let previous;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        if(current > 0){
        	previous = current - 1; 
        	current = previous;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){
                    //alert(remark[previous]);
                    if(remark[previous] != undefined) $("#remark").val(remark[previous]);
                    else $("#remark").val('0');
                    if(justification[previous] != undefined) $("#justification").val(justification[previous]);
                    else $("#justification").val('');
                    $(this).html("<h5>Question "+(previous+1)+"</h5><p>"+appraisal_data_array[previous].question+"</p><p style = 'text-align:justify'>Weight "+appraisal_data_array[previous].weight+"%</p>");
                    $('#stage').html(""+(previous + 1)+"/"+appraisal_data_array.length);
                });

            });
        }

    });
    $("#mnext").on('click',function(e){
      //let appraisal_data_array = [];
      //$(this).removeAttr("question");
      let appraisal_data = $(this).attr('appraisal_data');
      appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;// appraisal_data.split("%%%");
      $('.manager_remark').val('');
      $('.manager_justification').val('');

      let appraisal_flow = $(this).attr('appraisal_flow').split(';');
      //alert('okie');
      let next = current + 1;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        //alert(JSON.stringify(appraisal_data_array));
        if(next < appraisal_data_array.length){
          current = next;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){

                    $(this).html("<h5>Question "+(next+1)+"</h5><p>"+appraisal_data_array[next].question+"</p>"
                                      +"<p style = 'text-align:justify'>Weight "+appraisal_data_array[next].weight+"%</p>"
                                      +"<h5 style = 'margin-top:10px;'>Remark</h5><p> "+appraisal_data_array[next].remark+"</p>"
                                      +"<h5 style = 'margin-top:10px;'>Justification</h5>"
                                      +"<p>"+appraisal_data_array[next].justification+"</p>");
                    question = question + 1;
                    //$(this).attr('question',next);

                    //alert(JSON.stringify(appraisal_data_array[next].manager_justification));

                    if(appraisal_data_array[next].manager_justification != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[next].manager_justification.findIndex(x => x.question == (question+1) && x.who == flow[0] );
                         // alert(question);
                         if(index > -1)
                            $("#justification"+i+"").val(appraisal_data_array[next].manager_justification[index].justification);
                          // alert(`#justification${i}`);
                          //alert(appraisal_data_array[next].manager_justification[index].who);
                        }
                        //alert(appraisal_data_array[next].manager_justification[index].who);
                    }
                    if(appraisal_data_array[next].manager_remark != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[next].manager_remark.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                         if(index > -1 && appraisal_data_array[next].manager_remark[index].who == flow[0])
                         {

                            $("#remark"+i+"").val(appraisal_data_array[next].manager_remark[index].remark);
                         }
                          //alert(appraisal_data_array[next].manager_remark[index].remark);
                        }
                    }
                    $('#stage').html(""+(next + 1)+"/"+appraisal_data_array.length);
                });
                if(next == appraisal_data_array.length - 1 && is_created == false){
                    is_created = true;

                    $(".main_btn").append("<button type='button' class='btn btn-success' appraisal_data = '"+JSON.stringify(appraisal_data_array)+"'  style='margin: 4px;' id='submitdatamanager'>Submit</button>");
                }
            });
        }

    });
    $("#mprevious").on('click',function(e){
      //let appraisal_data_array = [];
      
      let appraisal_data = $(this).attr('appraisal_data');
      appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;
      let appraisal_flow = $(this).attr('appraisal_flow').split(';');
      let who = $(this).attr('who');
      let previous;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        if(current > 0){
          previous = current - 1; 
          current = previous;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){
                    $(this).html("<h5>Question "+(previous+1)+"</h5><p>"+appraisal_data_array[previous].question+"</p>"
                                      +"<p style = 'text-align:justify'>Weight "+appraisal_data_array[previous].weight+"%</p>"
                                      +"<h5 style = 'margin-top:10px;'>Remark</h5><p> "+appraisal_data_array[previous].remark+"</p>"
                                      +"<h5 style = 'margin-top:10px;'>Justification</h5>"
                                      +"<p>"+appraisal_data_array[previous].justification+"</p>");
                    //$(this).attr('question',previous);
                    question = question - 1; 
                    //alert(previous);
                    if(appraisal_data_array[previous].manager_justification != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[previous].manager_justification.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                          //alert(question);
                          //alert(JSON.stringify(appraisal_data_array[previous]));
                         if(index > -1)
                            $("#justification"+i+"").val(appraisal_data_array[previous].manager_justification[index].justification);
                          // alert(`#justification${i}`);
                          //alert(appraisal_data_array[previous].manager_justification[index].justification);
                        }
                    }
                    if(appraisal_data_array[previous].manager_remark != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[previous].manager_remark.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                         if(index > -1)
                            $("#remark"+i+"").val(appraisal_data_array[previous].manager_remark[index].remark);
                          //alert(appraisal_data_array[previous].manager_remark[index].remark);
                        }
                    }
                    $('#stage').html(""+(previous + 1)+"/"+appraisal_data_array.length);
                });

            });
        }

    });
    $("#snext").on('click',function(e){
      //let appraisal_data_array = [];
      //$(this).removeAttr("question");
      let appraisal_data = $(this).attr('appraisal_data');
      appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;// appraisal_data.split("%%%");
      $('.manager_remark').val('');
      $('.manager_justification').val('');
      let appraisal_flow = $(this).attr('appraisal_flow').split(';');
      //alert('okie');
      let next = current + 1;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        //alert(JSON.stringify(appraisal_data_array));
        if(next < appraisal_data_array.length){
          current = next;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){

                    $(this).html("<h5>Question "+(next+1)+"</h5><p>"+appraisal_data_array[next].question+"</p>"
                                      +"<p style = 'text-align:justify'>Weight "+appraisal_data_array[next].weight+"%</p>"
                                      +"<h5 style = 'margin-top:10px;'>Remark</h5><p> "+appraisal_data_array[next].remark+"</p>"
                                      +"<h5 style = 'margin-top:10px;'>Justification</h5>"
                                      +"<p>"+appraisal_data_array[next].justification+"</p>");
                    question = question + 1;
                    //$(this).attr('question',next);

                    //alert(JSON.stringify(appraisal_data_array[next].manager_justification));

                    if(appraisal_data_array[next].manager_justification != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[next].manager_justification.findIndex(x => x.question == (question+1) && x.who == flow[0] );
                         // alert(question);
                         if(index > -1)
                            $("#justification"+i+"").val(appraisal_data_array[next].manager_justification[index].justification);
                          // alert(`#justification${i}`);
                          //alert(appraisal_data_array[next].manager_justification[index].who);
                        }
                        //alert(appraisal_data_array[next].manager_justification[index].who);
                    }
                    if(appraisal_data_array[next].manager_remark != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[next].manager_remark.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                         if(index > -1 && appraisal_data_array[next].manager_remark[index].who == flow[0])
                         {

                            $("#remark"+i+"").val(appraisal_data_array[next].manager_remark[index].remark);
                         }
                          //alert(appraisal_data_array[next].manager_remark[index].remark);
                        }
                    }
                    $('#stage').html(""+(next + 1)+"/"+appraisal_data_array.length);
                });
                if(next == appraisal_data_array.length - 1 && is_created == false){
                    is_created = true;

                    //$(".main_btn").append("<button type='button' class='btn btn-success' appraisal_data = '"+JSON.stringify(appraisal_data_array)+"'  style='margin: 4px;' id='submitdatamanager'>Submit</button>");
                }
            });
        }

    });
    $("#sprevious").on('click',function(e){
      //let appraisal_data_array = [];
      
      let appraisal_data = $(this).attr('appraisal_data');
      appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;
      let appraisal_flow = $(this).attr('appraisal_flow').split(';');
      let who = $(this).attr('who');
      let previous;
        remark[current] = $('#remark').val();
        justification[current] = $('#justification').val();
        if(current > 0){
          previous = current - 1; 
          current = previous;
            $("#questionrow").slideUp('3000', function(){
                $("#questionrow").slideDown('2000', function(){
                    $(this).html("<h5>Question "+(previous+1)+"</h5><p>"+appraisal_data_array[previous].question+"</p>"
                                      +"<p style = 'text-align:justify'>Weight "+appraisal_data_array[previous].weight+"%</p>"
                                      +"<h5 style = 'margin-top:10px;'>Remark</h5><p> "+appraisal_data_array[previous].remark+"</p>"
                                      +"<h5 style = 'margin-top:10px;'>Justification</h5>"
                                      +"<p>"+appraisal_data_array[previous].justification+"</p>");
                    //$(this).attr('question',previous);
                    question = question - 1; 
                    //alert(previous);
                    if(appraisal_data_array[previous].manager_justification != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[previous].manager_justification.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                          //alert(question);
                          //alert(JSON.stringify(appraisal_data_array[previous]));
                         if(index > -1)
                            $("#justification"+i+"").val(appraisal_data_array[previous].manager_justification[index].justification);
                          // alert(`#justification${i}`);
                          //alert(appraisal_data_array[previous].manager_justification[index].justification);
                        }
                    }
                    if(appraisal_data_array[previous].manager_remark != undefined)
                    {
                        for (var i = 0; i < appraisal_flow.length; i++) {
                          let flow = appraisal_flow[i].split(':');
                          let index =  appraisal_data_array[previous].manager_remark.findIndex(x => x.question == (question+1) && x.who == flow[0]);
                         if(index > -1)
                            $("#remark"+i+"").val(appraisal_data_array[previous].manager_remark[index].remark);
                          //alert(appraisal_data_array[previous].manager_remark[index].remark);
                        }
                    }
                    $('#stage').html(""+(previous + 1)+"/"+appraisal_data_array.length);
                });

            });
        }

    });
     $("body").on("click", "#edit",  function(e){
        e.preventDefault();
        $("#edit").addClass('hide');
        $("#review_replies").slideUp('3000', function(){
            $("#all_reply").slideDown('3000', function(){

            });
        });
     });
     $("body").on("click", "#submit",  function(e){
        e.preventDefault();
        //alert("as");
        $("#all_remark").val(remark.join(";"));
        $("#all_justification").val(justification.join(";"));
        let appraisal = JSON.stringify(appraisal_data_array);
        $("#appraisallist").val(appraisal);
        $("#flowplan").click();
     });

     $("body").on("click", "#submitdatamanager",  function(e){
        e.preventDefault();
        $("#managerappraisallist").val(JSON.stringify(appraisal_data_array));
        $("#submit_data_manager").click();
     });
     $("body").on('click', "#review", function(e){
        //let appraisal_data_array = [];
        $("#edit").removeClass('hide');
        appraisal_data_array = appraisal_data_array.length == 0 ? JSON.parse(appraisal_data) : appraisal_data_array;
        //alert(JSON.stringify(appraisal_data_array));
        $("#next").trigger('click');
        $(".each_reply").html('');
        $("#all_reply").slideUp('3000', function(){
            $("#review_replies").slideDown('3000', function(){
                for (var i = 0; i < appraisal_data_array.length; i++) {
                    //alert(i);
                  $(".each_reply").append("<h5 style='margin-bottom:25px;font-weight:500'>Question " +(i+1)+"</h5><p style='text-align:justify'>"+appraisal_data_array[i].question+"</p><p style = 'text-align:justify'>Weight "+appraisal_data_array[i].weight+"%</p><p style = 'margin-top:10px;'>Score "+appraisal_data_array[i].remark+"</p><p style = 'margin-top:10px;font-weight:500'>Justification</p><p>"+appraisal_data_array[i].justification+"</p>");  
                }
                $(".each_reply").append("<div class='btn-group main_btn' role='group' aria-label='Basic example' style='margin-top: 10px;'><button type='button' class='btn btn-warning' id = 'edit'>Edit</button><button style='margin-left:50px' type='button' class='btn btn-success' id = 'submit'>Submit</button></div>");
            })
        })
     });
     $("#uploadfilled").click(function(e){
        e.preventDefault();
        $("#filledApp").trigger("click");
     });
     $("#filledApp").change(function(e){
        e.preventDefault();
        //alert("ass");
        $("#submitApp").trigger('click');
     });
     $('.manager_remark').on('change', function(e){
         e.preventDefault();
         //alert('sss');
         let id = $('#'+this.id+'').attr('val');
         manager_remark[parseInt(id)] = $('#'+this.id+'').val();
         $('#manager_remark').val(manager_remark.join(';'));
     });
     $('.manager_justification').on('keyup', function(e){
         e.preventDefault();
         let id = $('#'+this.id+'').attr('val');
         manager_justification[parseInt(id)] = $('#'+this.id+'').val();
         $('#manager_justification').val(manager_justification.join('%%%'));
     });
     $(".textarea").on("keyup", function(e){
        e.preventDefault();
        //alert(this.id);
        let appraisal_id = $("#"+this.id+"").attr('appraisal_id');
        let staff_id = $("#"+this.id+"").attr('staff_id');
            $("#staff_id").val(staff_id);
            $("#appraisal_id").val(appraisal_id);
            $("#comment").val($("#"+this.id+"").val());
            //alert($("#"+this.id+"").val());
        //alert(staff_id+"----------"+appraisal_id);

     });
     /*$("#add_comment").on("click", function(e){
        e.preventDefault();
        let appraisal_flow = [];
        appraisal_flow = $(this).attr("appraisal_flow").split(";");
        let current_user_email = $(this).attr("current_user_email");
        alert(current_user_email);
        for(let r = 0; r < appraisal_flow.length; r++){
            let approval_email = $("#textarea"+r+"").attr('approval_email');
            if(current_user_email == approval_email){
                $("#approval_email").val(approval_email);
                $("#comment").val($("#textarea"+r+"").val());
                //$("")
                //alert($("#comment").val());
            }
                
        }

     })*/

});
