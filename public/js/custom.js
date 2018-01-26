$(document).ready(function () {

	setInitialSidebar();

    $('.navbar-toggler').on('click', function () {
        toggleSidebar();
    	setSidebarSession(checkSidebar());
    });

    $('#example').DataTable();
});

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

