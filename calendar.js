// calendar.js


//login
function loginAjax(event){
	var username = document.getElementById("login-username").value; // Get the username from the form
	var password = document.getElementById("login-password").value; // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
 
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "login_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			var token = jsonData.token;          
			document.getElementById("token").value = token;
			alert("You've been Logged In!");
			document.getElementById("login-username").value = "";
			document.getElementById("login-password").value = "";
			document.getElementById("greet").textContent = "Welcome, "+username+"! ";
			$("#login").modal('hide');
			updateCalendar(curMonth);
			displayEvents();

		}else{
			alert("You were not logged in.  "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}
 
document.getElementById("login-submit").addEventListener("click", loginAjax, false); // Bind the AJAX call to button click




//register
function registerAjax(event){
	var username = document.getElementById("register-username").value; // Get the username from the form
	var password = document.getElementById("register-password").value; // Get the password from the form
 
	// Make a URL-encoded string for passing POST data:
	var dataString = "username=" + encodeURIComponent(username) + "&password=" + encodeURIComponent(password);
 
	var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
	xmlHttp.open("POST", "register_ajax.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
	xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
	xmlHttp.addEventListener("load", function(event){
		var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
		if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
			document.getElementById("register-username").value = "";
			document.getElementById("register-password").value = "";
			$("#register").modal('hide');
			alert("Successfully Registered!");
		}else{
			alert("Failed to register.  "+jsonData.message);
		}
	}, false); // Bind the callback to the load event
	xmlHttp.send(dataString); // Send the data
}
 
document.getElementById("register-submit").addEventListener("click", registerAjax, false); // Bind the AJAX call to button click


//logout
function logoutAjax(event) {
	var dataString;
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "logout.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
        alert("You've been Logged Out!");
        document.getElementById("greet").textContent = "Welcome, Guest! ";
        updateCalendar(curMonth);
        displayEvents();//update events
        }else{
            alert("You were not logged out.  "+jsonData.message);
        }
    }, false); // Bind the callback to the load event

    xmlHttp.send(dataString); // Send the data
}

document.getElementById("logout").addEventListener("click", logoutAjax, false); // Bind the AJAX call to button click




//display calendar
var d = new Date();
var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
var weekdays = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
var curMonth = new Month(d.getFullYear(), d.getMonth());

var curEvent = null;

var displayToday = function(d){
	var dayDisplay=d.getDate();
	var monthDisplay=months[d.getMonth()];
	var yearDisplay=curMonth.year;
	var display = dayDisplay + " " + monthDisplay+ " " +yearDisplay;
	return display;
}

var displayCurMonth = function (curMonth) {
    var monthDisplay = months[curMonth.month];
    var yearDisplay = curMonth.year;
    var display = monthDisplay + " " + yearDisplay;
    return display;
};


function updateCalendar(curMonth) {
    var old = document.getElementById("calendar-body");
	var element = old.firstChild;
	while (element){
        old.removeChild(element);
		element=old.firstChild;
    }
	
	var weeks=curMonth.getWeeks();
	
	var weekTrack=0;
	
	for (var w in weeks) {
		if (weeks.hasOwnProperty(w)) {
            var week=document.createElement("tr");
			week.setAttribute("id", "week"+Math.floor(weekTrack/7));
			week.setAttribute("class", "row panel-body");
			document.getElementById("calendar-body").appendChild(week);
			var days = weeks[w].getDates();
			for (var d in days) {
                var dayBox = document.createElement("td");
				var idMonth = days[d].getMonth();
				var day = days[d].getDate();
				var idYear = days[d].getYear();
				dayBox.textContent=day;
				if (days[d].getMonth() != curMonth.month) {
                    dayBox.setAttribute("style", "background-color: #C0C0C0");
                }
				dayBox.setAttribute("id", idMonth + "-" + day);
				dayBox.setAttribute("class", "box");
				dayBox.setAttribute("data-day", day);
				dayBox.setAttribute("data-month", idMonth);
				dayBox.setAttribute("data-year", idYear);
				document.getElementById("week" + Math.floor(weekTrack / 7)).appendChild(dayBox);
				weekTrack = weekTrack + 1;
			}
        }
    }
}


document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("today").textContent = "Today:    " + displayToday(d);
    document.getElementById("current-month").textContent = displayCurMonth(curMonth);
    updateCalendar(curMonth);
    displayEvents();
}, false);



//today   
document.getElementById("today").addEventListener("click", function () {
    curMonth = new Month(d.getFullYear(), d.getMonth());
    curWeek = new Week(d);
    document.getElementById("current-month").textContent = displayCurMonth(curMonth);
    updateCalendar(curMonth);
    updateWeekCalendar(curWeek);
    displayEvents();
}, false);

//previous-month
document.getElementById("previous-month").addEventListener("click", function () {
    curMonth = curMonth.prevMonth();
    document.getElementById("current-month").textContent = displayCurMonth(curMonth);
    updateCalendar(curMonth);
    displayEvents();
}, false);

//next-month
document.getElementById("next-month").addEventListener("click", function () {
    curMonth = curMonth.nextMonth();
    document.getElementById("current-month").textContent = displayCurMonth(curMonth);
    updateCalendar(curMonth);
    displayEvents();
}, false);




//show all events
var displayEvents = function () {

    //clean up events in month display
    var weeks = curMonth.getWeeks();
    for (var w in weeks) {
      if (weeks.hasOwnProperty(w)) {
		var days = weeks[w].getDates();
		for (var d in days) {
            var idMonth = days[d].getMonth();
			var day = days[d].getDate();
			var idYear=days[d].getYear();
			var child = document.getElementById(idMonth+"-"+day);
			var firstChild=child.firstChild;
			while (firstChild) {
                child.removeChild(firstChild);
				firstChild=child.firstChild;
            }
			var popUp=document.createElement("div");
			popUp.setAttribute("class", "box-content");
			var popUpMiddle=document.createElement("a");
			popUpMiddle.setAttribute("data-toggle","modal");
			popUpMiddle.setAttribute("href","#add");
			popUpMiddle.setAttribute("data-month",idMonth);
			popUpMiddle.setAttribute("data-day", day);
			popUpMiddle.setAttribute("data-year",idYear);
			popUpMiddle.textContent=day;
			popUp.appendChild(popUpMiddle);
			child.appendChild(popUp);
        }
	  }
	}

    //query events
    var dataString = "token=" + document.getElementById('token').value;
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "display-event.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function (event) {
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        if (jsonData.success) {
            //successfully get events
            for (var e in jsonData.events) {

                if (jsonData.events.hasOwnProperty(e)) {
					
                //split the datetime into the info we need
                var t = jsonData.events[e].event_time.split(/[- :]/);
                var dateOfEvent = new Date(t[0], t[1] - 1, t[2]);
                var time = t[3]+":"+t[4];
                var title = jsonData.events[e].title;
                var id = jsonData.events[e].id;
				
                
                if ((dateOfEvent.getMonth() == curMonth.month) && (dateOfEvent.getFullYear()==curMonth.year)) {
                    //display the events in current month
                    var eventItem = document.createElement("a");
                    eventItem.textContent = title+"  "+time;
                    eventItem.setAttribute("id", "event"+id);
                    eventItem.setAttribute("class", "event");
                    eventItem.setAttribute("data-toggle", "modal");
                    eventItem.setAttribute("href", "#display");
                    eventItem.setAttribute("onclick","showDetail(this)");
                    eventItem.setAttribute("data-event-id",id);                                   
                    document.getElementById((dateOfEvent.getMonth()) + "-" + dateOfEvent.getDate()).appendChild(eventItem);
                    document.getElementById((dateOfEvent.getMonth()) + "-" + dateOfEvent.getDate()).innerHTML+="<br>";


                }
            }

        }
    } else {
            //NOT A REGISTERED USER, SO DO NOT SHOW ANYTHING        
        }
    }, false); // Bind the callback to the load event

    xmlHttp.send(dataString); // Send the data
};





//Add Event
function addEventAjax(event){
    var category = $('input[name="event-type"]:checked').val(); // Get the type from the form
	var groupUser = document.getElementById("group-user").value; // Get the title from the form
	var title = document.getElementById("event-title").value; // Get the title from the form
    var content = document.getElementById("event-detail").value; // Get the content from the form
    var date = document.getElementById("event-date").value; // Get the date from the form
    var time = document.getElementById("event-time").value; // Get the time from the form
	
	
    var token = document.getElementById("token").value;
    
    // Make a URL-encoded string for passing POST data:
    var dataString = "title=" + encodeURIComponent(title) + "&content=" + encodeURIComponent(content) + "&date=" + encodeURIComponent(date) + "&time=" + encodeURIComponent(time)
	+ "&category=" + encodeURIComponent(category) + "&groupUser=" + encodeURIComponent(groupUser) + "&token=" + encodeURIComponent(token);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "add-event.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
        alert("Event added!");
        document.getElementById("event-title").value = "";
        document.getElementById("event-detail").value = "";
        document.getElementById("event-date").value = "";
        document.getElementById("event-time").value = "";
        document.getElementById("group-user").value = "";
        $("#add").modal('hide');
		}else{
		    alert("Fail to add event.  "+jsonData.message);
		}
		displayEvents();
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

document.getElementById("add-event-submit").addEventListener("click", addEventAjax, false); // Bind the AJAX call to button click




//update the details of current event chosen based on the current event
var updateDetail = function () {
    if (curEvent !== null) {
        var eventData = curEvent;
        var element = document.getElementById("view-event");
        element.parentNode.removeChild(element);
        //display event details in the modal
        var t = eventData.event_time.split(/[- :]/);
        var date = t[0] + "-" + t[1] + "-" + t[2];
        var time = t[3] + ":" + t[4];
        var title = eventData.title;
        var content = eventData.content;
        var id = eventData.id;
		var category = eventData.category;
		var groupUser = eventData.groupUser;
        var viewE = document.createElement("div");
        viewE.setAttribute("id", "view-event");
        viewE.innerHTML="<h2><strong>"+title+"</strong>"+" "+time+" "+"</h2>"+"<h3 class='pull-right'>"+date+"</h3><h4>"
						+content+"</h4><p>Category: "+category+"<br>Grouped with: "+groupUser+"</p>";
        document.getElementById("event-body").appendChild(viewE);
        document.getElementById("update").setAttribute("data-renew-id", id);
        document.getElementById("delete-event").setAttribute("data-delete-id", id);
    }
};




//show event detail
var showDetail = function(event){

    var event_id = event.getAttribute("data-event-id");
    var dataString = "token=" + document.getElementById('token').value+"&event_id="+event_id;
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "show-event-detail.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xmlHttp.addEventListener("load", function (event) {
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object       
        if (jsonData.success) {
            curEvent = jsonData.detail;
            updateDetail();
            updatePage();           
        } else {
			
        }
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString);
    
};






//update event
function updateEventAjax(event){
    

	
    var event_id2 = document.getElementById("update").getAttribute("data-renew-id"); //Get the event id
    var title2 = document.getElementById("event-title-update").value; // Get the title from the form
    var content2 = document.getElementById("event-detail-update").value; // Get the content from the form
    var date2 = document.getElementById("event-date-update").value; // Get the date from the form
    var time2 = document.getElementById("event-time-update").value; // Get the time from the form
    var category2 = $('input[name="event-type-update"]:checked').val(); // Get the type from the form
    var groupUser2 = document.getElementById("group-user-update").value; // Get the time from the form
    var token = document.getElementById("token-update").value;
	
    var dataString = "id=" + encodeURIComponent(event_id2) + "&title=" + encodeURIComponent(title2) + "&content=" + encodeURIComponent(content2) + "&date=" + encodeURIComponent(date2) + "&time=" + encodeURIComponent(time2)
	+ "&category=" + encodeURIComponent(category2) + "&groupUser=" + encodeURIComponent(groupUser2) + "&token=" + encodeURIComponent(token);
    	
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "update-event.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        if(jsonData.success){                                                                                  
            $("#update").modal('hide');
            alert("Event updated!"); 
        }else{
            alert("Fail to update event.  "+jsonData.message);
        }
        displayEvents();
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

document.getElementById("update-event-submit").addEventListener("click", updateEventAjax, false); 



var updatePage = function(){
    var curEt = curEvent.event_time.split(/[- :]/);
    var curEventDate = curEt[0]+"-"+curEt[1]+"-"+curEt[2];
    var curEventTime = curEt[3]+":"+curEt[4];
    document.getElementById("event-title-update").setAttribute("value",curEvent.title);
    document.getElementById("event-detail-update").textContent = curEvent.content;
    document.getElementById("event-date-update").setAttribute("value",curEventDate);
    document.getElementById("event-time-update").setAttribute("value",curEventTime);
    document.getElementById("group-user-update").setAttribute("value",curEvent.groupUser);
    document.getElementById(curEvent.category+"2").setAttribute("checked", true);
	
};




$(document).ready(function(){
    $("#update-event").click(function () {
        $("#display").modal('hide');
        $("#update").modal('show');  
    });
});



//delete event
function deleteEventAjax(event){
    var event_id3 = document.getElementById("delete-event").getAttribute("data-delete-id"); 
    var token = document.getElementById("token").value;
    

    // Make a URL-encoded string for passing POST data:
    var dataString = "id=" + encodeURIComponent(event_id3) + "&token=" + encodeURIComponent(token);
    
    var xmlHttp = new XMLHttpRequest(); // Initialize our XMLHttpRequest instance
    xmlHttp.open("POST", "delete-event.php", true); // Starting a POST request (NEVER send passwords as GET variables!!!)
    xmlHttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); // It's easy to forget this line for POST requests
    xmlHttp.addEventListener("load", function(event){
        var jsonData = JSON.parse(event.target.responseText); // parse the JSON into a JavaScript object
        if(jsonData.success){  // in PHP, this was the "success" key in the associative array; in JavaScript, it's the .success property of jsonData
        $("#display").modal('hide'); 
        alert("Event deleted!");            
    }else{
        alert("Fail to delete event.  "+jsonData.message);
    }
    displayEvents();
    }, false); // Bind the callback to the load event
    xmlHttp.send(dataString); // Send the data
}

document.getElementById("delete-event").addEventListener("click", deleteEventAjax, false); // Bind the AJAX call to button click


