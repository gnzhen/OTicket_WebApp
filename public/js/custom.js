$.noConflict();

$( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.

	// setInitialSidebar();
	// getAllSession();
	// loadTable(1);

	showLoading(false);


	$('.hv-loading').click(function(){
		showLoading(true);
	});

    $('.navbar-toggler').on('click', function () {
        toggleSidebar();
    	// setSidebarSession(checkSidebar());
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


	/* Issue ticket */
	$('body').delegate('#btnIssueTicket', 'click', function() {
		var id = $(this).data('id');
		showModal('#issueTicketModal'+id);
	});

	/* Set Tab Session */
	$('body').delegate('#queueTab', 'click', function() {
		var id = $(this).data('id');
		setTabSession(id);
	});


	/* Functions */

	function toggleSidebar(){
	    $('#sidebar').toggleClass('active');
	    $('.wrapper').toggleClass('active');
	}

	function showLoading(show) {
		if(show){
			$('body').addClass('loading');
		}	
		else{
			$('body').removeClass('laoding');
		}
	}

	function showModal(id){
		jQuery(id).modal('show');
	}

	function hideModal(id){
		jQuery(id).modal('hide');
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
	

	//close alert
	window.setTimeout(function() {
    	$(".alert").fadeTo(3000, 0).slideUp(3000, function(){
        	$(this).remove(); 
    	});
	}, 1000);
	

});



	// $( document ).ready(function($) {
	
    

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

    $('#homeTable').dataTable({
    	destroy: true,
    	"iDisplayLength": 5,
	    "aLengthMenu": [[3, 5, 10, -1], [3, 5, 10, "All"]],
      	"rowsGroup": [0, 2]
	 });

/* Datepicker */
	var dateFormat = "dd/mm/yy";
  	
  	var dateFrom = $( "#datepickerFrom" )
  			.datepicker({
		  		dateFormat: 'dd/mm/yy',
		  		maxDate: '0'
		    })
			.on( "change", function() {
				dateTo.datepicker( "option", "minDate", getDate( this ) );
			});
			
	var dateTo = $( "#datepickerTo" )
			.datepicker({
    			dateFormat: 'dd/mm/yy',
  				maxDate: '0'
    		})
			.on( "change", function() {
				dateFrom.datepicker( "option", "maxDate", getDate( this ) );
			});

    function getDate( element ) {
		var date;

		try {
			date = $.datepicker.parseDate( dateFormat, element.value );
		} catch( error ) {
			date = null;
		}

		return date;
    }

    function loadTable(branchId){
	$.get('ticketsJSON', {'branchId':branchId})
		.done(function(data) {
			console.log("loadTable: " + data);
		})
		.fail(function(xhr, status, error){
			console.log(xhr);
		});		
	}
	

// });



// function checkSidebar(){
// 	if($('#sidebar').hasClass('active'))
// 		return "open";
// 	else
// 		return "close";
// }

// function openMenu(menu, status){
// 	if(status == 'open'){
// 		$(menu).addClass('show');
// 	}
// 	else{
// 		$(menu).removeClass('show');
// 	}
// }
//
// function checkAuth(){
// 	$.get('checkAuth')
// 		.done(function(data) {
//     		return data;
// 		})	
// 		.fail(function(xhr, status, error){
// 			console.log(xhr);
// 		});	
// }

// function setInitialSidebar(){
// 	//get sidebar open/closed
// 	$.get('getSidebarSession')
// 		.done(function(data) {
//     		if(data == 'open')
//     			toggleSidebar();
// 		})	
// 		.fail(function(xhr, status, error){
// 			console.log(xhr);
// 		});	

// 	// if($('#adminDropdownMenu').hasClass('active')){
// 	// 	openMenu('#adminMenu', 'open');
// 	// }
// }

// function setSidebarSession(status){
// 	$.get('setSidebarSession', {'sidebar':status})
// 		.done(function(data) {
// 			// console.log("set sidebar " + data);
// 		})
// 		.fail(function(xhr, status, error){
// 			console.log(xhr);
// 		});		
// }