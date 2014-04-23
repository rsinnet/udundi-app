// Define a global variable which holds the charts information.
window.charts = {};

function ChartPanel(data)
{
    this.id = makeId();
    this.data = data;
}

ChartPanel.prototype.getData = function() { return this.data; };

ChartPanel.prototype.getDatum = function(key) { return this.data[key]; };

ChartPanel.prototype.toString = function() { return this.id; };

ChartPanel.prototype.chartId = function() { return "chart_" + this.id; };

ChartPanel.prototype.html = function()
{
    articleElem = $('<article/>').addClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');

    divElem = $('<div data-widget-editbutton="false"/>')
	.attr({id: 'widget_'+this.id}).addClass('jarviswidget');

    headerElem = $('<header/>')
	.append($('<span/>').addClass('widget-icon')
		.append($('<i/>').addClass('fa fa-bar-chart-o')))
	.append($('<h2/>').text(this.data['fullName']));

    chartElem = $('<div/>')
	.append($('<div/>').addClass('jarviswidget-editbox'))
	.append($('<div/>').addClass('widget-body no-padding')
		.append($('<div/>').attr({id: 'chart_'+this.id}).addClass('chart')));

    divElem.append(headerElem).append(chartElem);
    articleElem.append(divElem);

    console.log(articleElem);

    return articleElem;
};

ChartPanel.prototype.add = function() { $('#charts_container').append(this.html()); };

ChartPanel.prototype.remove = function() { $('#' + this.to.String()).remove(); };

ChartPanel.prototype.refresh = function()
{
    // @todo Error handling on dates. At least verify that start date is before end date.
    console.log('Refresh chart.');
};


// Parses the returned message containing chart data for the Jarvis Widget chart.
ChartPanel.prototype.parse = function(msg)
{
    if (msg.name == "page_fans_online")
    {
	data = _.values(msg.values[0].value);
	labels = _.keys(msg.values[0].value);

	return _.map(_.range(data.length), function(index) { return [labels[index], data[index]]; });
    }
    else
    {
	// Extract the data from the message.
	data = _.map(msg.values, function(val) { return val.value; });
	labels = _.map(msg.values, function(val, index) { return new Date(Date.parse(val.end_time)); });

	// Sort the data in chronological order.
	data = _.sortBy(data, function(datum, index) { return labels[index].getTime(); });
	labels = _.sortBy(labels, function(label) { return label.getTime(); });
	
	// Structure the data for the Jarvis Widget charts.
	return _.map(_.range(data.length), function(index) { return [labels[index].getTime(), data[index]]; });
    }
}

ChartPanel.prototype.populate = function(msg)
{
    var d = this.parse(msg);
    if ($("#" + this.chartId()).length) {
	for (var i = 0; i < d.length; ++i)
	    d[i][0] += 60 * 60 * 1000;

	var options = {
	    xaxis : { mode : "time", tickLength : 5 },
	    series : {
		lines : {
		    show : true,
		    lineWidth : 1,
		    fill : true,
		    fillColor : { colors : [{ opacity : 0.1 }, { opacity : 0.15 }] }
		},
		//points: { show: true },
		shadowSize : 0
	    },
	    selection : { mode : "x" },
	    grid : {
		hoverable : true,
		clickable : true,
		tickColor : $chrt_border_color,
		borderWidth : 0,
		borderColor : $chrt_border_color,
	    },
	    tooltip : true,
	    tooltipOpts : {
		content : "Your Likes for <b>%x</b> were <span>$%y</span>",
		dateFormat : "%y-%0m-%0d",
		defaultTheme : false
	    },
	    colors : [$chrt_second]

	};
	$.plot($("#" + this.chartId()), [d], options);
    }    
};

function makeId()
{
    var n = 10;
    var text = "";
    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";

    for ( var i=0; i < n; i++ )
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    return text;
}

function addChartPanel(chartData)
{
    // Generate a unique string for each panel.
    //var myLine = new Chart(document.getElementById(cp.canvasId()).getContext('2d')).Line(chartData);
}

function weekendAreas(axes) {
    var markings = [];
    var d = new Date(axes.xaxis.min);
    // go to the first Saturday
    d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
    d.setUTCSeconds(0);
    d.setUTCMinutes(0);
    d.setUTCHours(0);
    var i = d.getTime();
    do {
	// when we don't set yaxis, the rectangle automatically
	// extends to infinity upwards and downwards
	markings.push({
	    xaxis : {
		from : i,
		to : i + 2 * 24 * 60 * 60 * 1000
	    }
	});
	i += 7 * 24 * 60 * 60 * 1000;
    } while (i < axes.xaxis.max);

    return markings;
}
