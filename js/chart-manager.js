// Define a global variable which holds the charts information.
window.charts = {};

function ChartPanel(id)
{
    this.id = id;
}

ChartPanel.prototype.toString = function()
{
    return this.id;
}

ChartPanel.prototype.html = function()
{
    articleElem = $('<article/>', {class: "col-xs-12 col-sm-12 col-md-12 col-lg-12"});
    divElem = $('<div data-widget-editbutton="false"></div>').attr({id: this.id});
    headerElem = $('<header/>')
	.append($('<span/>').addClass('widget-icon')
		.append($('<i/>').addClass('fa fa-bar-chart-o')))
	.append($('<h2/>').text('Page Likes'));
    
    chartElem = $('<div/>')
	.append($('<div/>').addClass('jarviswidget-editbox'))
	.append($('<div/>').addClass('widget-body no-padding')
		.append($('<div/>').attr({id: 'saleschart', class: 'chart'})));

    headerElem.append(chartElem);
    divElem.append(headerElem);
    articleElem.append(divElem);

    return articleElem;
}

ChartPanel.prototype.add = function()
{
    $('#charts_container').append(this.html());
}

ChartPanel.prototype.remove = function()
{
    $('#' + this.to.String()).remove();
}

ChartPanel.prototype.refresh = function()
{
    // @todo Error handling on dates. At least verify that start date is before end date.
    console.log('Refresh chart.');
}

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
    var currentId = makeId();
    cp = new ChartPanel(currentId);
    cp.add();
    charts[currentId] = cp;
    var myLine = new Chart(document.getElementById(cp.canvasId()).getContext('2d')).Line(chartData);
}
