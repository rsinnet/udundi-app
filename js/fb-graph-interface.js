function loadFacebookData(msg) {
    console.log(msg)

    // Extract the data from the message.
    data = _.map(msg.values, function(val) { return val.value; });
    labels = _.map(msg.values, function(val) { return new Date(Date.parse(val.end_time)); });

    // Sort the data in chronological order.
    data = _.sortBy(data, function(datum, index) { return labels[index].getTime(); });
    labels = _.sortBy(labels, function(label) { return label.getTime(); });

    // Structure the data for the Jarvis Widget charts.
    d = _.map(_.range(data.length), function(index) { return [labels[index], data[index]]; });
 
    var insight = 'page_impressions';
    var insight_id = '#' + insight;

    // need a load condition
    //if ($("#" + insight).length == 0)
    {
	$(insight_id).append('<div><form id="page_impressions_form"></form></div>')
	_.each(periods, function(val) {
	    $(insight_id + '_form').append(' <input type="checkbox" value="' + val + '"/> ' + val); })
	    $(insight_id).append("<canvas id=\"" + insight + "-canvas\" height=\"450\" width=\"600\"></canvas>");
	var myLine = new Chart(document.getElementById(insight + "-canvas").getContext("2d")).Line(lineChartData);

    }	
}

function initializeControls() {
    selectElement = $('edges_select');

    console.log($('#edges_select'))

    $.getJSON("facebook_edges.json", function(data) {
	_.forEach(data, function(datum) {
	    console.log(datum);
	    selectElement.append("<option>" + datum + "</option>");
	});
    });
}


window.fbAsyncInit = function() {
    FB.init({
	appId      : '262037167304306',
	status     : true, // check login status
	cookie     : true, // enable cookies to allow the server to access the session
	xfbml      : true  // parse XFBML
    });
    
    // Here we subscribe to the auth.authResponseChange JavaScript event. This event is fired
    // for any authentication related change, such as login, logout or session refresh. This means that
    // whenever someone who was previously logged out tries to log in again, the correct case below 
    // will be handled. 
    FB.Event.subscribe('auth.authResponseChange', function(response) {
	// Here we specify what we do with the response anytime this event occurs. 
	if (response.status === 'connected') {
	    // The response object is returned with a status field that lets the app know the current
	    // login status of the person. In this case, we're handling the situation where they 
	    // have logged in to the app.
	    facebookConnectCallback();
	} else if (response.status === 'not_authorized') {
	    // In this case, the person is logged into Facebook, but not into the app, so we call
	    // FB.login() to prompt them to do so. 
	    // In real-life usage, you wouldn't want to immediately prompt someone to login 
	    // like this, for two reasons:
	    // (1) JavaScript created popup windows are blocked by most browsers unless they 
	    // result from direct interaction from people using the app (such as a mouse click)
	    // (2) it is a bad experience to be continually prompted to login upon page load.
	    FB.login(function(){}, {scope: 'manage_pages'});
	} else {
	    // In this case, the person is not logged into Facebook, so we call the login() 
	    // function to prompt them to do so. Note that at this stage there is no indication
	    // of whether they are logged into the app. If they aren't then they'll see the Login
	    // dialog right after they log in to Facebook. 
	    // The same caveats as above apply to the FB.login() call here.
	    FB.login(function(){}, {scope: 'manage_pages'});
	}
    });
};

// Load the SDK asynchronously
(function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
}(document));

function facebookConnectCallback() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me', function(response) {
	console.log('Good to see you, ' + response.name + '.');
    });

    initializeCharts();
}
