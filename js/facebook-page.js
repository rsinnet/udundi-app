// PAGE RELATED SCRIPTS

/* chart colors default */
var $chrt_border_color = "#efefef";
var $chrt_grid_color = "#DDD"
var $chrt_main = "#E24913";
/* red       */
var $chrt_second = "#6595b4";
/* blue      */
var $chrt_third = "#FF9F01";
/* orange    */
var $chrt_fourth = "#7e9d3a";
/* green     */
var $chrt_fifth = "#BD362F";
/* dark red  */
var $chrt_mono = "#000";

// Finite state machine for loading and page operations
function FSM() {
    this.state = {
	docReady: false,
	configLoaded: false,
	jarvisWidgetLoaded: false,
	facebookConnected: false,
	chartDataLoading: false,
	pagesLoading: false,
	pagesLoaded: false
    };
}
FSM.prototype.setState = function(state) { this.state[state] = true; };
FSM.prototype.clearState = function(state) { this.state[state] = false; };
FSM.prototype.toString = function() { return this.state.toString(); };
FSM.prototype.go = function() {
    if (this.state['docReady'] &&
	!this.state['configLoaded'])
	loadPanelConfig();

    if (this.state['docReady'] &&
	this.state['facebookConnected'] &&
	!this.state['pagesLoading'] &&
	!this.state['pagesLoaded'])
	loadFacebookPages();

    if (this.state['docReady'] &&
	this.state['configLoaded'] &&
	!this.state['jarvisWidgetLoaded'])
	loadJarvisWidget();

    if (this.state['docReady'] &&
	this.state['configLoaded'] &&
	this.state['jarvisWidgetLoaded'] &&
	this.state['facebookConnected'] &&
	this.state['pagesLoaded'] &&
	!this.state['chartDataLoading'])
	loadChartData();
};

window.fsm = new FSM();

$(document).ready(function() {
    window.fsm.setState('docReady');
    window.fsm.go();
});

window.charts = {};

function loadPanelConfig() {
    $.ajax({
	type: "GET",
	url: "data/facebook_default.json"
    }).done(function(msg) {
	_.each(msg["data"], function(configData) {
	    // Load up charts
	    // Get the chart for configData.name from the python
	    
	    // Load all charts from configuration data.
	    cp = new ChartPanel(configData);
	    charts[cp.toString()] = cp;
	    cp.add();
	});

	window.fsm.setState('configLoaded');
	window.fsm.go();
    });
}

function loadJarvisWidget() {
    $.getScript('js/smartwidgets/jarvis.widget.js', function() {	    
	// DO NOT REMOVE : GLOBAL FUNCTIONS!
	pageSetUp();
	
	window.fsm.setState('jarvisWidgetLoaded');
	window.fsm.go();
    });
}

function loadFacebookPages()
{
    window.fsm.setState('pagesLoading');

    FB.api('/me/accounts', function(response) {
	console.log(response);
	_.forEach(response.data, function(datum) {
	    $('#facebook_pages').append('<option value="' + datum['id'] + '">' + datum['name'] + '</option>');
	});

	$('#facebook_pages').change(function() {
	    window.fsm.clearState('chartDataLoading');
	    console.log($(this).children('option:selected').text());
	    window.fsm.go();
	});

	window.fsm.setState('pagesLoaded');
	window.fsm.go()
    });

    window.fsm.go();    
}

function loadChartData()
{
    window.fsm.setState('chartDataLoading');

    var authResponse = FB.getAuthResponse();
    var accessToken = authResponse.accessToken;

    console.log(accessToken);

    var since_date = $('#since_date').val();
    var until_date = $('#until_date').val();

    if (since_date == "")
	since_date = "2014-04-01";

    if (until_date == "")
	until_date = "2014-04-22";

    _.each(Object.keys(window.charts), function(key) {
	$.ajax({
	    type: "POST",
	    url: "py/fpe_interface.py",
	    data: {
		user_access_token: accessToken,
		edge: window.charts[key].getDatum('name'),
		period: window.charts[key].getDatum('period'),
		since: since_date,
		until: until_date,
		page_id: $('#facebook_pages').val()
	    }
	}).done(function(msg) {
	    console.log(msg);
	    window.charts[key].populate(msg);
	}).fail(function(msg) {
	    console.log('Could not access Facebook--Python interface.');
	    console.log(msg);
	});
    });

    window.fsm.go();
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
	    window.fsm.clearState('facebookConnected');
	} else {
	    // In this case, the person is not logged into Facebook, so we call the login() 
	    // function to prompt them to do so. Note that at this stage there is no indication
	    // of whether they are logged into the app. If they aren't then they'll see the Login
	    // dialog right after they log in to Facebook. 
	    // The same caveats as above apply to the FB.login() call here.
	    FB.login(function(){}, {scope: 'manage_pages'});
	    window.fsm.clearState('facebookConnected');
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

    window.fsm.setState('facebookConnected');
    window.fsm.go();
}
