<script>

//total ventas por tienda en dinero
set_sales_by_store_total_money(<?php echo $sales_total_by_store_money!=null?json_encode($sales_total_by_store_money):'[]'?>);
function set_sales_by_store_total_money(sales_total_by_store_money){
	var chart = AmCharts.makeChart("chartdiv_sales_by_store_total_money", {
        "theme": "light",
        "type": "serial",
        "startDuration": 2,
        "dataProvider":sales_total_by_store_money,
        "graphs": [{
            "balloonText": "[[name]]: <br><b>[[value]]</b>",
            "labelText":'[[value]]',
            "labelPosition":"bottom",
            "fillColorsField": "color",
            "fillAlphas": 1,
            "lineAlpha": 0.1,
            "type": "column",
            "valueField": "Total",
            
        }],
        "numberFormatter": 
        {
            "precision": 1,
            "decimalSeparator": ".",
            "thousandsSeparator": ","
        },
        "valueAxes": [{
            "title": "Ventas realizadas",
            "labelsEnabled":true
        }],
        "depth3D": 20,
        "angle": 30,
        "chartCursor": {
            "categoryBalloonEnabled": true,
            "cursorAlpha": 0,
            "zoomable": false
        },    
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 0
        },
        "export": {
            "enabled": false
        },"legend": {
            "showEntries": false
        }
    });

    AmCharts.checkEmptyData = function (chart) 
    {
        //chart.labelText = '[[Total]]';
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