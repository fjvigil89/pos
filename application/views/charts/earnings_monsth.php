<script type="text/javascript">
set_sales_earnings_monsth(<?php echo $earnings_monsth_day!=null?json_encode($earnings_monsth_day):'[]'?>);
function set_sales_earnings_monsth(earnings_monsth_day){
    var chart = AmCharts.makeChart("chartdiv_get_earnings_monsth", {
        type: "serial",
        theme: "light",
        fontFamily: "Open Sans",
        color: "#888888",
        pathToImages: '<?php echo 'img/amcharts/images/'; ?>',
        dataProvider:earnings_monsth_day,
        graphs: [{
            labelText:'[[value]]',
            labelPosition:"top",
            bullet: "square",
            bulletBorderAlpha: 1,
            bulletBorderThickness: 1,
            fillAlphas: .3,
            fillColorsField: "lineColor",
            legendValueText: "[[value]]",
            lineColorField: "lineColor",
            title: "profit",
            valueField: "profit"
        }],
        chartCursor: {
            categoryBalloonDateFormat: "YYYY MMM DD",
            cursorAlpha: 0,
            zoomable: false,
            valueZoomable:false
        },
        categoryField: "fecha",
    });

    AmCharts.checkEmptyData = function (chart) 
    {
        if ( 0 == chart.dataProvider.length ) 
        {
            // set min/max on the value axis
            chart.valueAxes[0].minimum = 0;
            chart.valueAxes[0].maximum = 100;
            
            // add dummy data point
            var dataPoint = {
                dummyValue: 0
            };
            dataPoint[chart.categoryField] = '';
            chart.dataProvider = [dataPoint];
            
            // add label
            chart.addLabel(0, '50%', 'No hay datos para mostrar', 'center');
            
            // set opacity of the chart div
            chart.chartDiv.style.opacity = 0.5;
            
            // redraw it
            chart.validateNow();
        }
    }

    AmCharts.checkEmptyData(chart);
}
</script>