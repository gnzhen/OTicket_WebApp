$.noConflict();

jQuery( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.

	setInitialSidebar();

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
    $('#btnAddStaff').click(function() {
		showModal("#registerStaffModal");
	});

	$('#btnAddAdmin').click(function() {
		showModal("#registerAdminModal");
	});

	$('#btnEditStaff').click(function() {
		showModal("#updateStaffModal");
	});

	$('#btnEditAdmin').click(function() {
		showModal("#updateAdminModal");
	});

	$('#btnEditSuperAdmin').click(function() {
		showModal("#updateSuperAdminModal");
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
			console.log("setsidebar" + data);
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
