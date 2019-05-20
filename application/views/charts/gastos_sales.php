<script type="text/javascript">
set_sales_monsth();
function set_sales_monsth(){
    var chart_sales = AmCharts.makeChart("chartdiv_get_sales_monsth", {
                type: "serial",
                theme: "light",
                //pathToImages: App.getGlobalPluginsPath() + "amcharts/amcharts/images/",
                autoMargins: !1,
                marginLeft: 30,
                marginRight: 8,
                marginTop: 10,
                marginBottom: 26,
                fontFamily: "Open Sans",
                color: "#888",
                dataProvider: [{
                    year: 2009,
                    income: 23.5,
                    expenses: 18.1
                }, {
                    year: 2010,
                    income: 26.2,
                    expenses: 22.8
                }, {
                    year: 2011,
                    income: 30.1,
                    expenses: 23.9
                }, {
                    year: 2012,
                    income: 29.5,
                    expenses: 25.1
                }, {
                    year: 2013,
                    income: 30.6,
                    expenses: 27.2,
                }, {
                    year: 2014,
                    income: 34.1,
                    expenses: 29.9,
                    dashLengthColumn: 5
                }],
                valueAxes: [{
                    axisAlpha: 0,
                    position: "left"
                }],
                startDuration: 1,
                graphs: [{
                    alphaField: "alpha",
                    balloonText: "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b> [[additional]]</span>",
                    dashLengthField: "dashLengthColumn",
                    fillAlphas: 1,
                    title: "Income",
                    type: "column",
                    valueField: "income"
                }, {
                    balloonText: "<span style='font-size:13px;'>[[title]] in [[category]]:<b>[[value]]</b> [[additional]]</span>",
                    bullet: "round",
                    dashLengthField: "dashLengthLine",
                    lineThickness: 3,
                    bulletSize: 7,
                    bulletBorderAlpha: 1,
                    bulletColor: "#FFFFFF",
                    useLineColorForBulletBorder: !0,
                    bulletBorderThickness: 3,
                    fillAlphas: 0,
                    lineAlpha: 1,
                    title: "Expenses",
                    valueField: "expenses"
                }],
                categoryField: "year",
                categoryAxis: {
                    gridPosition: "start",
                    axisAlpha: 0,
                    tickLength: 0
                }
            });
            $("#chartdiv_get_sales_monsth").closest(".portlet").find(".fullscreen").click(function() {
                e.invalidateSize()
            })
    AmCharts.checkEmptyData = function (chart_sales) 
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