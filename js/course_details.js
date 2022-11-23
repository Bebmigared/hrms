$(function(){
	let questions;
	let all_questions = [];
	$('#course_review').hide();
	$('#course_continue').hide();
	let n = 0;
	$('#upload_appraisal').on('click', function(e){
		$('#appraisal_file').trigger('click');
	});
	$(".course_link").on('click', function(e){
      let curr = $("#"+this.id+"").attr("curr");
      question = $("#question").val();
	});
	$(".question").on('keydown', function(e) {
	  if (e.which == 13) {
	  	question = $("#question").val();
	    $("#add_ques").trigger("click");
	  }
	});

	$(".view_courses").on("click", function(e){
		//alert('dhhdf');
		all_questions = JSON.parse($("#"+this.id+"").attr('data'));
		//alert($("#"+this.id+"").attr('data'));
		$("#view_questions").html('');
		for(let r = 0; r < all_questions.length; r++)
	      {
	      	//alert(r);
	      	    $("#view_questions").append("<div style='margin-bottom:30px'><h4>Question "+(r + 1)+"</h4>"
		    	+"<p style = 'text-align:justify'>"+all_questions[r].question+"</p><h5>Option A : "+all_questions[r].a+"</h5>"
		    	+"<h5>Option B : "+all_questions[r].b+"</h5>"
		    	+"<h5>Option C : "+all_questions[r].c+"</h5>"
		    	+"<h5>Option D : "+all_questions[r].d+"</h5>"
		    	+"<h5>Option E : "+all_questions[r].e+"</h5>"
		    	+"<h5>Answer : "+all_questions[r].answer+"</h5>"
		    	+"</div>");
	      }
	});

	$('body').on('change', '.option', function(e){
		let option = $('#'+this.id+'').val();
		all_questions[this.id].myanswer = option;
		//alert(JSON.stringify(all_questions[this.id]));
		$('#responsetoquestions').val(JSON.stringify(all_questions));
	})

	$(".myview_courses").on("click", function(e){
		//alert('dhhdf');
		all_questions = JSON.parse($("#"+this.id+"").attr('data'));
		$('#mycoursetestid').val($('#'+this.id+'').attr('id'));
		//alert($("#"+this.id+"").attr('data'));
		$("#view_questions").html('');
		for(let r = 0; r < all_questions.length; r++)
	      {
	      	//alert(r);
	      	    $("#view_questions").append("<div style='margin-bottom:30px'><h4>Question "+(r + 1)+"</h4>"
		    	+"<p style = 'text-align:justify'>"+all_questions[r].question+"</p><h5>Option A : "+all_questions[r].a+"</h5>"
		    	+"<h5>Option B : "+all_questions[r].b+"</h5>"
		    	+"<h5>Option C : "+all_questions[r].c+"</h5>"
		    	+"<h5>Option D : "+all_questions[r].d+"</h5>"
		    	+"<h5>Option E : "+all_questions[r].e+"</h5>"
		    	+"<h5><select class='form-control col-md-7 col-xs-12 option' style='margin-bottom:30px' id='"+r+"'>"
		    	+"<option value=''>Choose Answer</option>"
		    	+"<option value='A'>A</option>"
		    	+"<option value='B'>B</option>"
		    	+"<option value='C'>C</option>"
		    	+"<option value='D'>D</option>"
		    	+"<option value='E'>E</option>"
		    	+"</select></h5>"
		    	+"</div>");
	      }
	});
	$("#add_ques").on("click", function(e){
		e.preventDefault();
		if($("#question").val() == '') return false;
		if($("#answer").val() == '')
		{
			alert('Answer is Required');
			return false;
		}

		question = { 
			question : $("#question").val(), 
			a : $("#a").val(),
			b : $("#b").val(),
			c : $("#c").val(),
			d : $("#d").val(),
			e : $('#e').val(),
			answer: $("#answer").val()
		};
	    $(".all_questions").append("<div style='margin-bottom:30px'><h4>Question "+(all_questions.length + 1)+"</h4>"
	    	+"<p style = 'text-align:justify'>"+$("#question").val()+"</p><h5>Option A : "+$("#a").val()+"</h5>"
	    	+"<h5>Option B : "+$("#b").val()+"</h5>"
	    	+"<h5>Option C : "+$("#c").val()+"</h5>"
	    	+"<h5>Option D : "+$("#d").val()+"</h5>"
	    	+"<h5>Option E : "+$("#e").val()+"</h5>"
	    	+"<h5>Answer : "+$("#answer").val()+"</h5>"
	    	+"</div>");
	   	all_questions.push(question);
	   	$("#question").val('');
	   	$("#answer").val('');
	   	$("#a").val('');
	   	$("#b").val('');
	   	$("#c").val('');
	   	$("#d").val('');
	   	$("#e").val('');
	   	$('#course_review').show();
	    $('#course_continue').show();
	});
    $("body").on('click', "#course_continue", function(e){
      e.preventDefault();
      let data = JSON.stringify(all_questions);// all_questions.join("%%%");
      $("#course_test").val(data);
      $(".show_questions").addClass('hide');
      $('.process_form').removeClass('hide');
      $('.question_page').addClass('hide');
      $("#add_ques").addClass('hide');
      $('#main_question_page').removeClass('hide');
      $('.question_page, .show_questions').slideUp('4000', function(){
      })
	});
	$("body").on('click', "#main_question_page", function(e){
      e.preventDefault();
      $(".show_questions").addClass('hide');
      $('.process_form').addClass('hide');
      $('.question_page').removeClass('hide');
      $('#add_ques').removeClass('hide');
      $('#main_question_page').addClass('hide');
      $('.all_questions').html('');
      for(let r = 0; r < all_questions.length; r++)
      {
      	    $(".all_questions").append("<div style='margin-bottom:30px'><h4>Question "+(r + 1)+"</h4>"
	    	+"<p style = 'text-align:justify'>"+all_questions[r].question+"</p><h5>Option A : "+all_questions[r].a+"</h5>"
	    	+"<h5>Option B : "+all_questions[r].b+"</h5>"
	    	+"<h5>Option C : "+all_questions[r].c+"</h5>"
	    	+"<h5>Option D : "+all_questions[r].d+"</h5>"
	    	+"<h5>Option E : "+all_questions[r].e+"</h5>"
	    	+"<h5>Answer : "+all_questions[r].answer+"</h5>"
	    	+"</div>");
      	    //$(".all_questions").append("<div style='margin-top:10px'><h4>Question "+(r + 1)+"</h4><p style = 'text-align:justify'>"+all_questions[r].question+"</p><h5>Weight "+all_questions[r].weight+"%</h5></div>");
      }

      $('.show_questions, .process_form').slideUp('4000', function(){
      	   $(".question_page").slideDown();
      })
	});
	 $('body').on('click', "#course_review", function(e){
		e.preventDefault();
		let n = 1;
		//if(all_questions.length == 0) return false;
		$(".show_questions").removeClass('hide');
		$('.process_form').addClass('hide');
		$('#add_ques').addClass('hide');
		$('#main_question_page').removeClass('hide');
		$("#editdocument").attr('curr','1');
		$('.pagination').html('');
		$(".question_page").slideUp('4000',function(){
			//$(".each_questions").html('');
			$(".each_questions").html("<div style='margin-bottom:30px'><h4>Question 1</h4>"
	    	+"<p style = 'text-align:justify'>"+all_questions[0].question+"</p><h5>Option A : "+all_questions[0].a+"</h5>"
	    	+"<h5>Option B : "+all_questions[0].b+"</h5>"
	    	+"<h5>Option C : "+all_questions[0].c+"</h5>"
	    	+"<h5>Option D : "+all_questions[0].d+"</h5>"
	    	+"<h5>Option E : "+all_questions[0].e+"</h5>"
	    	+"<h5>Answer : "+all_questions[0].answer+"</h5>"
	    	+"</div>");
			//$(".each_questions").html("<h5>Question 1</h5><p style = 'text-align:justify'>"+all_questions[0].question+"</p><h5>Weight "+all_questions[0].weight+"%</h5>");
		    for (var i = 0; i < all_questions.length; i++) {
		     if(i == 0)
		    	$('.pagination').append("<li class='page-item course_link active'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
			 else if(i <= 6){
			 	n = i;
		        $('.pagination').append("<li class='page-item course_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");			 	
			 }
		    }
		    if(all_questions.length > 6){
		    	$(".pagination").append("<li class='page-item show_more_link' curr = '"+n+"'><a class='page-link' href='#' aria-label='Next'>"
                             +"<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span>"
                             +"</a></li>");
		    } 
		});
	});
	$('body').on('click', ".course_link", function(e){ 
	//$(".course_link").on("click", function(e){
		e.preventDefault();
		let curr = $("#"+this.id+"").attr("curr");
		//alert(curr);
		//alert(curr);
		$("#editdocument").attr('curr',curr);
		$(".each_questions").slideUp('2000', function(e){
			//$(".each_questions").html("<h5>Question "+(curr)+"</h5><p style = 'text-align:justify'>"+all_questions[parseInt(curr)-1].question+"</p><h5>Weight "+all_questions[parseInt(curr)-1].weight+"%</h5>");
			$(".each_questions").html("<div style='margin-bottom:30px'><h4>Question "+(curr)+"</h4>"
	    	+"<p style = 'text-align:justify'>"+all_questions[parseInt(curr)-1].question+"</p><h5>Option A : "+all_questions[parseInt(curr)-1].a+"</h5>"
	    	+"<h5>Option B : "+all_questions[parseInt(curr)-1].b+"</h5>"
	    	+"<h5>Option C : "+all_questions[parseInt(curr)-1].c+"</h5>"
	    	+"<h5>Option D : "+all_questions[parseInt(curr)-1].d+"</h5>"
	    	+"<h5>Option E : "+all_questions[parseInt(curr)-1].e+"</h5>"
	    	+"<h5>Answer : "+all_questions[parseInt(curr)-1].answer+"</h5>"
	    	+"</div>");
			$(this).slideDown('2000', function(){
				//$(".each_questions").html("<h5>Question "+(parseInt(curr) + 1)+"</h5><p style = 'text-align:justify'>"+all_questions[curr]+"</p>");
				$(".course_link").removeClass("active");
				$("#link"+(curr-1)+"").addClass("active");
			});
		});
	});
	$('body').on('click', "#editdocument", function(e){
		e.preventDefault();
		let curr = $(this).attr('curr');
		//alert(curr);
		$(".each_questions").slideUp('2000', function(e){
			$(".each_questions").html("<div><textarea class ='form-control' id = 'updated_document' rows ='10'>"+all_questions[curr-1].question+"</textarea>"
				+"<input value='"+all_questions[curr-1].a+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Option A' id='updatea'/>"
				+"<input value='"+all_questions[curr-1].b+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Option B' id='updateb'/>"
				+"<input value='"+all_questions[curr-1].c+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Option C' id='updatec'/>"
				+"<input value='"+all_questions[curr-1].d+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Option D' id='updated'/>"
				+"<input value='"+all_questions[curr-1].e+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Option E' id='updatee'/>"
				+"<input value='"+all_questions[curr-1].answer+"' style='margin-top: 20px' class='form-control' type='text' placeholder='Answer' id='updatef'/>"
				+"<div style = 'margin-top: 10px;'><a href = '#' curr = '"+(curr-1)+"' id = 'course_save_edit' class ='btn btn-primary'>Save Change</a></div></div>");
			// $(".each_questions").html("<div style='margin-bottom:30px'><h4>Question "+(curr)+"</h4>"
	  //   	+"<p style = 'text-align:justify'>"+all_questions[parseInt(curr)-1].question+"</p><h5>Option A : "+all_questions[parseInt(curr)-1].a+"</h5>"
	  //   	+"<h5>Option B : "+all_questions[parseInt(curr)-1].b+"</h5>"
	  //   	+"<h5>Option C : "+all_questions[parseInt(curr)-1].c+"</h5>"
	  //   	+"<h5>Option D : "+all_questions[parseInt(curr)-1].d+"</h5>"
	  //   	+"<h5>Option E : "+all_questions[parseInt(curr)-1].e+"</h5>"
	  //   	+"</div>");
			$(this).slideDown('2000', function(){
			});
 //  name=""
		});
		
	});
	$('body').on('click', "#course_save_edit", function(e){
      let curr = $(this).attr('curr');
      all_questions[curr] = { 
      	question : $("#updated_document").val(), 
      	a : $("#updatea").val(),
		b : $("#updateb").val(),
		c : $("#updatec").val(),
		d : $("#updated").val(),
		e : $('#updatee').val(),
		answer: $('#updatef').val(),
      }; 
      $(".each_questions").slideUp('2000', function(e){
			//$(".each_questions").html("<h5>Question "+(parseInt(curr) + 1) +"</h5><p style = 'text-align:justify'>"+all_questions[curr].question+"</p>");
		    //$(".all_questions").append("<div style='margin-top:10px'><h4>Question "+(all_questions.length + 1)+"</h4>"
		    //	+"<p style = 'text-align:justify'>"+$("#question").val()+"</p><h5>Weight "+$("#weight").val()+"%</h5></div>");
		    $(".all_questions").html("<div style='margin-bottom:30px'><h4>Question "+(curr)+"</h4>"
	    	+"<p style = 'text-align:justify'>"+$("#updated_document").val()+"</p><h5>Option A : "+$("#updatea").val()+"</h5>"
	    	+"<h5>Option B : "+$("#updateb").val()+"</h5>"
	    	+"<h5>Option C : "+$("#updatec").val()+"</h5>"
	    	+"<h5>Option D : "+$("#updated").val()+"</h5>"
	    	+"<h5>Option E : "+$('#updatee').val()+"</h5>"
	    	+"<h5>Answer : "+$('#updatef').val()+"</h5>"
	    	+"</div>");

			$(this).slideDown('2000', function(){
			});
		});
	});
	$('body').on('click', "#back_btn", function(e){ 
	//$(".show_more_link").on('click', function(e){
		let n = 0;
		let p = 0;
		e.preventDefault();
		let value = parseInt($("#back_btn").attr("start"));
		$(".pagination").html('');
		let _for_back;
		if((value - 6) > 0) _for_back = value - 6;
		else _for_back =  0;
		//alert(value);
		if(value > 0){
			$(".pagination").append("<li class='page-item' id = 'back_btn' start = '"+_for_back+"'><a class='page-link' href='#' aria-label='Previous'>"
                                +"<span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span>"
                                +"</a></li>");
		}
		for (var i = _for_back; i < all_questions.length; i++) {
			 if(p <= 6){
			 	n = i;
				$('.pagination').append("<li class='page-item course_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
			 }
			 p++;
	    }
	    if(all_questions.length > n){
	    	$(".pagination").append("<li class='page-item show_more_link' curr = '"+n+"'><a class='page-link' href='#' aria-label='Next'>"
                             +"<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span>"
                             +"</a></li>");
	    }

	})
	$('body').on('click', ".show_more_link", function(e){ 
	   //$(".show_more_link").on('click', function(e){
	   	//$(".show_more_link").
	   	let n = 0;
		let iter = 0;
		e.preventDefault();
		$("#back_btn").remove();
		$('.pagination').html('');
		let curr = parseInt($(this).attr('curr'));
		//alert(curr);
		let remain = all_questions.length - curr;
		if(remain > 6) iter = 6 + curr;
		else iter = remain + curr; 
		/*let _for_back;
		if((curr - 6) > 0) _for_back = curr - 6;
		else _for_back =  6 - curr;*/
		$(".pagination").append("<li class='page-item' id = 'back_btn' start = '"+curr+"'><a class='page-link' href='#' aria-label='Previous'>"
                                +"<span aria-hidden='true'>&laquo;</span><span class='sr-only'>Previous</span>"
                                +"</a></li>");
		//alert(iter);
		//$('.pagination').html('');
		for (var i = curr; i < iter; i++) {
			  n = i;
		      $('.pagination').append("<li class='page-item course_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
	    }
	   if(remain > 6){
	 	$(".pagination").append("<li class='page-item show_more_link' curr = '"+n+"'><a class='page-link' href='#' aria-label='Next'>"
                             +"<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span>"
                             +"</a></li>");
	    }

	})
})