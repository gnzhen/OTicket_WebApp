$.noConflict();

jQuery( document ).ready(function( $ ) {
  // Code that uses jQuery's $ can follow here.

	setInitialSidebar();

    $('.navbar-toggler').on('click', function () {
        toggleSidebar();
    	setSidebarSession(checkSidebar());
    });

    //session flash
    getAllSession();

    /* Show different modal form on button click */
    $('#btnAddStaff').click(function() {
		$('#registerStaffModal').modal('show');
	});

	$('#btnAddAdmin').click(function() {
		$('#registerAdminModal').modal('show');
	});

	$('#btnEditStaff').click(function() {
		$('#updateStaffModal').modal('show');
	});

	$('#btnEditAdmin').click(function() {
		$('#updateAdminModal').modal('show');
	});

	$('#btnEditSuperAdmin').click(function() {
		$('#updateSuperAdminModal').modal('show');
	});

	$('#btnDeleteStaff').click(function() {
		$('#deleteStaffModal').modal('show');
	});

	$('#btnDeleteAdmin').click(function() {
		$('#deleteAdminModal').modal('show');
	});

	$('#btnAddBranch').click(function() {
		$('#addBranchModal').modal('show');
	});

	$('#btnAddService').click(function() {
		$('#addServiceModal').modal('show');
	});

	$('#btnAddCounter').click(function() {
		$('#addCounterModal').modal('show');
	});

	$('#btnAddBranchService').click(function() {
		$('#addBranchServiceModal').modal('show');
	});

	$('#btnAddBranchCounter').click(function() {
		$('#addBranchCounterModal').modal('show');
	});


    // $("[data-toggle=tooltip]").tooltip();

});


/* Code that uses other library's $ can follow here. */


    //close alert
	window.setTimeout(function() {
    	$(".alert").fadeTo(500, 0).slideUp(500, function(){
        	$(this).remove(); 
    	});
	}, 1000);

    //sorting table
    /* Set custom pagination entries */
    
    $('.dataTable').dataTable({
	    "iDisplayLength": 3,
	    "aLengthMenu": [[ 3, 5, 10, -1], [3, 5, 10, "All"]]
	 });

    $('#branchServiceTable').dataTable({
    	destroy: true,
	    "columnDefs": [{ "orderable": false, "targets": [2,3] }],
      	"rowsGroup": [0, 4]
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

