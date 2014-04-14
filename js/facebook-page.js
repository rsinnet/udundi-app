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

$(document).ready(function() {

    // DO NOT REMOVE : GLOBAL FUNCTIONS!
    pageSetUp();

    // Load all charts from configuration data.
    cp = new ChartPanel('wid-id-4');
    cp.add();

    /* sales chart */

    if ($("#saleschart").length) {
	var d = [[1196463600000, 0], [1196550000000, 0], [1196636400000, 0], [1196722800000, 77], [1196809200000, 3636], [1196895600000, 3575], [1196982000000, 2736], [1197068400000, 1086], [1197154800000, 676], [1197241200000, 1205], [1197327600000, 906], [1197414000000, 710], [1197500400000, 639], [1197586800000, 540], [1197673200000, 435], [1197759600000, 301], [1197846000000, 575], [1197932400000, 481], [1198018800000, 591], [1198105200000, 608], [1198191600000, 459], [1198278000000, 234], [1198364400000, 1352], [1198450800000, 686], [1198537200000, 279], [1198623600000, 449], [1198710000000, 468], [1198796400000, 392], [1198882800000, 282], [1198969200000, 208], [1199055600000, 229], [1199142000000, 177], [1199228400000, 374], [1199314800000, 436], [1199401200000, 404], [1199487600000, 253], [1199574000000, 218], [1199660400000, 476], [1199746800000, 462], [1199833200000, 500], [1199919600000, 700], [1200006000000, 750], [1200092400000, 600], [1200178800000, 500], [1200265200000, 900], [1200351600000, 930], [1200438000000, 1200], [1200524400000, 980], [1200610800000, 950], [1200697200000, 900], [1200783600000, 1000], [1200870000000, 1050], [1200956400000, 1150], [1201042800000, 1100], [1201129200000, 1200], [1201215600000, 1300], [1201302000000, 1700], [1201388400000, 1450], [1201474800000, 1500], [1201561200000, 546], [1201647600000, 614], [1201734000000, 954], [1201820400000, 1700], [1201906800000, 1800], [1201993200000, 1900], [1202079600000, 2000], [1202166000000, 2100], [1202252400000, 2200], [1202338800000, 2300], [1202425200000, 2400], [1202511600000, 2550], [1202598000000, 2600], [1202684400000, 2500], [1202770800000, 2700], [1202857200000, 2750], [1202943600000, 2800], [1203030000000, 3245], [1203116400000, 3345], [1203202800000, 3000], [1203289200000, 3200], [1203375600000, 3300], [1203462000000, 3400], [1203548400000, 3600], [1203634800000, 3700], [1203721200000, 3800], [1203807600000, 4000], [1203894000000, 4500]];

	for (var i = 0; i < d.length; ++i)
	    d[i][0] += 60 * 60 * 1000;

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

	var options = {
	    xaxis : {
		mode : "time",
		tickLength : 5
	    },
	    series : {
		lines : {
		    show : true,
		    lineWidth : 1,
		    fill : true,
		    fillColor : {
			colors : [{
			    opacity : 0.1
			}, {
			    opacity : 0.15
			}]
		    }
		},
		//points: { show: true },
		shadowSize : 0
	    },
	    selection : {
		mode : "x"
	    },
	    grid : {
		hoverable : true,
		clickable : true,
		tickColor : $chrt_border_color,
		borderWidth : 0,
		borderColor : $chrt_border_color,
	    },
	    tooltip : true,
	    tooltipOpts : {
		content : "Your sales for <b>%x</b> was <span>$%y</span>",
		dateFormat : "%y-%0m-%0d",
		defaultTheme : false
	    },
	    colors : [$chrt_second],

	};

	var plot = $.plot($("#saleschart"), [d], options);
    };

    /* end sales chart */
});
