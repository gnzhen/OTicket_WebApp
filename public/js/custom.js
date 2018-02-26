$.noConflict();

jQuery( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.

	setInitialSidebar();
	getAllSession();

    $('.navbar-toggler').on('click', function () {
        toggleSidebar();
    	setSidebarSession(checkSidebar());
    });

    /* Clear modal input on closed */
    $('.modal-add').on('hidden.bs.modal', function (e) {
	  	$(this)
		    .find("input,textarea,select").val('').end()
	       	.find("span").html('').end()
		    .find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
		$('.modal-edit').find("span").html('').end();
	})

	$('.modal-edit').on('hidden.bs.modal', function (e) {
	  	$(this).find("span").html('').end()
	})


    /* Show different modal form on button click */
    /* Add */
    $('#btnAddStaff').click(function() {
		showModal("#registerStaffModal");
	});

	$('#btnAddAdmin').click(function() {
		showModal("#registerAdminModal");
	});

	$('#btnAddSuperAdmin').click(function() {
		showModal("#registerSuperAdminModal");
	});

	$('#btnDeleteStaff').click(function() {
		showModal("#deleteStaffModal");
	});

	$('#btnDeleteAdmin').click(function() {
		showModal("#deleteAdminModal");
	});

	$('#btnAddBranch').click(function() {
		showModal('#addBranchModal');
	});

	$('#btnAddService').click(function() {
		showModal('#addServiceModal');
	});
	
	$('#btnAddCounter').click(function() {
		showModal('#addCounterModal');
	});

	$('body').delegate('#btnAddBranchService', 'click', function() {
		var id = $(this).data('id');
		showModal('#addBranchServiceModal'+id);
	});


	/* Edit */

	$('body').delegate('#btnEditStaff', 'click', function() {
		var id = $(this).data('id');
		showModal('#updateUserModal'+id);
	});

	$('body').delegate('#btnEditAdmin', 'click', function() {
		var id = $(this).data('id');
		showModal('#updateUserModal'+id);
	});

	$('body').delegate('#btnEditSuperAdmin', 'click', function() {
		var id = $(this).data('id');
		showModal('#updateUserModal'+id);
	});

	$('body').delegate('#btnEditBranch', 'click', function() {
		var id = $(this).data('id');
		showModal('#editBranchModal'+id);
	});

	$('body').delegate('#btnEditService', 'click', function() {
		var id = $(this).data('id');
		showModal('#editServiceModal'+id);
	});

	$('body').delegate('#btnEditCounter', 'click', function() {
		var id = $(this).data('id');
		showModal('#editCounterModal'+id);
	});

	$('body').delegate('#btnEditBranchCounter', 'click', function() {
		var id = $(this).data('id');
		showModal('#editBranchCounterModal'+id);
	});

	$('body').delegate('#btnEditBranchService', 'click', function() {
		var id = $(this).data('id');
		showModal('#editBranchServiceModal'+id);
	});

	/* Delete */

	$('body').delegate('#btnDeleteStaff', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteStaffModal'+id);
	});

	$('body').delegate('#btnDeleteAdmin', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteAdminModal'+id);
	});

	$('body').delegate('#btnDeleteSuperAdmin', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteSuperAdminModal'+id);
	});

	$('body').delegate('#btnDeleteBranch', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteBranchModal'+id);
	});

	$('body').delegate('#btnDeleteService', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteServiceModal'+id);
	});

	$('body').delegate('#btnDeleteCounter', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteCounterModal'+id);
	});

	$('body').delegate('#btnDeleteBranchCounter', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteBranchCounterModal'+id);
	});

	$('body').delegate('#btnDeleteBranchService', 'click', function() {
		var id = $(this).data('id');
		showModal('#deleteBranchServiceModal'+id);
	});

	$('#btnResetSystemWaitTime').click(function() {
		$('#systemWaitTimeHr').val(0);
		$('#systemWaitTimeMin').val(0);
		$('#systemWaitTimeSec').val(0);
	});


	/* Timer */

	// $('body').delegate('#btnCallNext', 'click', function() {
	// 	var id = $(this).data('id');
	// 	startTimer(id);
	// 	console.log(id);
	// });

	// $('body').delegate('#btnRecall', 'click', function() {
	// 	var id = $(this).data('id');
	// 	stopTimer();
	// 	console.log(id);
	// });

	// $('body').delegate('#btnSkip', 'click', function() {
	// 	var id = $(this).data('id');
	// 	stopTimer();
	// 	console.log(id);
	// });

	// $('body').delegate('#btnDone', 'click', function() {
	// 	var id = $(this).data('id');
	// 	stopTimer();
	// 	console.log(id);
	// });

	var count = 0;
	var timer;

	function startTimer(id) {
		count ++;
		$('#timer'+id).text(secToStr(count));
	    timer = setTimeout(function(){ startTimer(id) }, 1000);
	}

	function stopTimer(){
		clearTimeout(timer);
		count = 0;
	}

	function secToStr(sec){
		var hours = Math.floor(sec / 3600);
  		var minutes = Math.floor((sec / 60) % 60);
  		var seconds = sec % 60;

  		if(hours < 10)
  			hours = '0' + hours;
  		if(minutes < 10)
  			minutes = '0' + minutes;
  		if(seconds < 10)
  			seconds = '0' + seconds;
  
  		return hours + ':' + minutes + ':' + seconds;
	}

	/* Issue ticket */
	$('body').delegate('#btnIssueTicket', 'click', function() {
		var id = $(this).data('id');
		showModal('#issueTicketModal'+id);
	});
	
});


/* Code that uses other library's $ can follow here. */
// $( document ).ready(function() {

    //close alert
	window.setTimeout(function() {
    	$(".alert").fadeTo(3000, 0).slideUp(3000, function(){
        	$(this).remove(); 
    	});
	}, 1000);

    /* Initialize datatables */

    $('.dataTable').dataTable({
	    "iDisplayLength": 5,
	    "aLengthMenu": [[3, 5, 10, -1], [3, 5, 10, "All"]],
	 });

    $('#branchServiceTable').dataTable({
    	destroy: true,
    	"iDisplayLength": 5,
	    "aLengthMenu": [[3, 5, 10, -1], [3, 5, 10, "All"]],
	    "columnDefs": [{ "orderable": false, "targets": [2,3,4,5] }],
      	"rowsGroup": [0, 5]
	 });

    $('#branchCounterTable').dataTable({
    	destroy: true,
    	"iDisplayLength": 5,
	    "aLengthMenu": [[3, 5, 10, -1], [3, 5, 10, "All"]],
	    "columnDefs": [{ "orderable": false, "targets": [2] }],
      	"rowsGroup": [0, 2]
	 });

	
    /* Custom filtering function which will search data in column four between two values */
	var table = $('.dataTable').DataTable();
     
    // Event listener to the two range filtering inputs to redraw on input
    $('#min, #max').keyup( function() {
        table.draw();
    } );

	$.fn.dataTable.ext.search.push(
	    function( settings, data, dataIndex ) {
	        var min = parseInt( $('#min').val(), 10 );
	        var max = parseInt( $('#max').val(), 10 );
	        var age = parseFloat( data[3] ) || 0; // use data for the age column
	 
	        if ( ( isNaN( min ) && isNaN( max ) ) ||
	             ( isNaN( min ) && age <= max ) ||
	             ( min <= age   && isNaN( max ) ) ||
	             ( min <= age   && age <= max ) )
	        {
	            return true;
	        }
	        return false;
	    }
	);

	//table checker
	$("#mytable #checkall").click(function () {
        if ($("#mytable #checkall").is(':checked')) {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", true);
            });

        } else {
            $("#mytable input[type=checkbox]").each(function () {
                $(this).prop("checked", false);
            });
        }
    });

// });

/* Functions */

function checkSidebar(){
	if($('#sidebar').hasClass('active'))
		return "open";
	else
		return "close";
}

function toggleSidebar(){
    $('#sidebar').toggleClass('active');
    $('.wrapper').toggleClass('active');
}

function openMenu(menu, status){
	if(status == 'open'){
		$(menu).addClass('show');
	}
	else{
		$(menu).removeClass('show');
	}
}

function showModal(id){
	jQuery(id).modal('show');
}

function hideModal(id){
	jQuery(id).modal('hide');
}

function checkAuth(){
	$.get('checkAuth')
		.done(function(data) {
    		return data;
		})	
		.fail(function(xhr, status, error){
			console.log(xhr);
		});	
}

function setInitialSidebar(){
	//get sidebar open/closed
	$.get('getSidebarSession')
		.done(function(data) {
    		if(data == 'open')
    			toggleSidebar();
		})	
		.fail(function(xhr, status, error){
			console.log(xhr);
		});	

	if($('#adminDropdownMenu').hasClass('active')){
		openMenu('#adminMenu', 'open');
	}
}

function setSidebarSession(status){
	$.get('setSidebarSession', {'sidebar':status})
		.done(function(data) {
			// console.log("set sidebar " + data);
		})
		.fail(function(xhr, status, error){
			console.log(xhr);
		});		
}

function setTabSession(status){
	$.get('setTabSession', {'tab':status})
		.done(function(data) {
			// console.log("set tab " + data);
		})
		.fail(function(xhr, status, error){
			console.log(xhr);
		});		
}

function getAllSession(status){
	$.get('getAllSession')
		.done(function(data) {
			console.log(data);
		})
		.fail(function(xhr, status, error){
			console.log(xhr);
		});		
}
