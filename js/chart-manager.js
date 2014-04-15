// Define a global variable which holds the charts information.
window.charts = {};

function ChartPanel(data)
{
    this.id = makeId();
    this.data = data;
}

ChartPanel.prototype.toString = function() { return this.id; };

ChartPanel.prototype.html = function()
{
    articleElem = $('<article/>').addClass('col-xs-12 col-sm-12 col-md-12 col-lg-12');

    divElem = $('<div data-widget-editbutton="false"/>')
	.attr({id: 'widget_'.this.id}).addClass('jarviswidget');

    headerElem = $('<header/>')
	.append($('<span/>').addClass('widget-icon')
		.append($('<i/>').addClass('fa fa-bar-chart-o')))
	.append($('<h2/>').text(this.data['fullName']));

    chartElem = $('<div/>')
	.append($('<div/>').addClass('jarviswidget-editbox'))
	.append($('<div/>').addClass('widget-body no-padding')
		.append($('<div/>').attr({id: 'chart_'.this.id}).addClass('chart')));

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

ChartPanel.prototype.populate = function(msg)
{
    
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
