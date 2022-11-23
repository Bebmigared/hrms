$(function(){
	let questions;
	let all_questions = [];
	$('#review').hide();
	$('#continue').hide();
	let n = 0;
	$('#upload_appraisal').on('click', function(e){
		$('#appraisal_file').trigger('click');
	});
	$(".page_link").on('click', function(e){
      let curr = $("#"+this.id+"").attr("curr");
      question = $("#question").val();
	});
	$(".question").on('keydown', function(e) {
	  if (e.which == 13) {
	  	question = $("#question").val();
	    $("#add_question").trigger("click");
	  }
	});
	$("#add_question").on("click", function(e){
		e.preventDefault();
		if($("#question").val() == '') return false;
		if($("#weight").val() == '')
		{
			alert('Weight is Required');
			return false;
		}
		const sum = all_questions.map(questions => questions.weight).reduce((prev, curr) => prev + curr, 0);
		if((sum + parseFloat($("#weight").val())) > 100)
		{
			alert('Weight Sum cannot be greater than 100');
			return false;
		}
		question = { question : $("#question").val(), weight : parseFloat($("#weight").val()) };
	    $(".all_questions").append("<div style='margin-bottom:30px'><h4>Question "+(all_questions.length + 1)+"</h4><p style = 'text-align:justify'>"+$("#question").val()+"</p><h5>Weight "+$("#weight").val()+"%</h5></div>");
	   	all_questions.push(question);
	   	$("#question").val('');
	   	$("#weight").val('');
	   	$('#review').show();
	    $('#continue').show();
	});
    $("body").on('click', "#continue", function(e){
      e.preventDefault();
      let data = JSON.stringify(all_questions);// all_questions.join("%%%");
      $("#appraisal_data").val(data);
      $(".show_questions").addClass('hide');
      $('.process_form').removeClass('hide');
      $('.question_page').addClass('hide');
      $("#add_question").addClass('hide');
      $('#main_question_page').removeClass('hide');
      $('.question_page, .show_questions').slideUp('4000', function(){
      })
	});
	$("body").on('click', "#main_question_page", function(e){
      e.preventDefault();
      $(".show_questions").addClass('hide');
      $('.process_form').addClass('hide');
      $('.question_page').removeClass('hide');
      $('#add_question').removeClass('hide');
      $('#main_question_page').addClass('hide');
      $('.all_questions').html('');
      for(let r = 0; r < all_questions.length; r++)
      {
      	    $(".all_questions").append("<div style='margin-top:10px'><h4>Question "+(r + 1)+"</h4><p style = 'text-align:justify'>"+all_questions[r].question+"</p><h5>Weight "+all_questions[r].weight+"%</h5></div>");
      }

      $('.show_questions, .process_form').slideUp('4000', function(){
      	   $(".question_page").slideDown();
      })
	});
	 $('body').on('click', "#review", function(e){
		e.preventDefault();
		let n = 1;
		//if(all_questions.length == 0) return false;
		$(".show_questions").removeClass('hide');
		$('.process_form').addClass('hide');
		$('#add_question').addClass('hide');
		$('#main_question_page').removeClass('hide');
		$("#editdoc").attr('curr','1');
		$('.pagination').html('');
		$(".question_page").slideUp('4000',function(){
			//$(".each_questions").html('');
			$(".each_questions").html("<h5>Question 1</h5><p style = 'text-align:justify'>"+all_questions[0].question+"</p><h5>Weight "+all_questions[0].weight+"%</h5>");
		    for (var i = 0; i < all_questions.length; i++) {
		     if(i == 0)
		    	$('.pagination').append("<li class='page-item page_link active'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
			 else if(i <= 6){
			 	n = i;
		        $('.pagination').append("<li class='page-item page_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");			 	
			 }
		    }
		    if(all_questions.length > 6){
		    	$(".pagination").append("<li class='page-item show_more_link' curr = '"+n+"'><a class='page-link' href='#' aria-label='Next'>"
                             +"<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span>"
                             +"</a></li>");
		    } 
		});
	});
	$('body').on('click', ".page_link", function(e){ 
	//$(".page_link").on("click", function(e){
		e.preventDefault();
		let curr = $("#"+this.id+"").attr("curr");
		//alert(curr);
		//alert(curr);
		$("#editdoc").attr('curr',curr);
		$(".each_questions").slideUp('2000', function(e){
			$(".each_questions").html("<h5>Question "+(curr)+"</h5><p style = 'text-align:justify'>"+all_questions[parseInt(curr)-1].question+"</p><h5>Weight "+all_questions[parseInt(curr)-1].weight+"%</h5>");

			$(this).slideDown('2000', function(){
				//$(".each_questions").html("<h5>Question "+(parseInt(curr) + 1)+"</h5><p style = 'text-align:justify'>"+all_questions[curr]+"</p>");
				$(".page_link").removeClass("active");
				$("#link"+(curr-1)+"").addClass("active");
			});
		});
	});
	$('body').on('click', "#editdoc", function(e){
		e.preventDefault();
		let curr = $(this).attr('curr');
		//alert(curr);
		$(".each_questions").slideUp('2000', function(e){
			$(".each_questions").html("<div><textarea class ='form-control' id = 'updated_doc' rows ='10'>"+all_questions[curr-1].question+"</textarea><input value='"+all_questions[curr-1].weight+"' style='margin-top: 20px' class='form-control' type='number' placeholder='weight in Percentage' id='updateweight'/><div style = 'margin-top: 10px;'><a href = '#' curr = '"+(curr-1)+"' id = 'save_edit' class ='btn btn-primary'>Save Change</a></div></div>");
			$(this).slideDown('2000', function(){
			});
 //  name=""
		});
		
	});
	$('body').on('click', "#save_edit", function(e){
      let curr = $(this).attr('curr');
      all_questions[curr] = { question : $("#updated_doc").val(), weight : parseFloat($("#updateweight").val()) }; 
      $(".each_questions").slideUp('2000', function(e){
			//$(".each_questions").html("<h5>Question "+(parseInt(curr) + 1) +"</h5><p style = 'text-align:justify'>"+all_questions[curr].question+"</p>");
		    $(".all_questions").append("<div style='margin-top:10px'><h4>Question "+(all_questions.length + 1)+"</h4><p style = 'text-align:justify'>"+$("#question").val()+"</p><h5>Weight "+$("#weight").val()+"%</h5></div>");

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
				$('.pagination').append("<li class='page-item page_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
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
		      $('.pagination').append("<li class='page-item page_link'  id= 'link"+i+"' curr = '"+(i+1)+"'><a class='page-link' href='#'>"+(i+1)+"</a></li>");
	    }
	   if(remain > 6){
	 	$(".pagination").append("<li class='page-item show_more_link' curr = '"+n+"'><a class='page-link' href='#' aria-label='Next'>"
                             +"<span aria-hidden='true'>&raquo;</span><span class='sr-only'>Next</span>"
                             +"</a></li>");
	    }

	})
})