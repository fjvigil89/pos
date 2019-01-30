<script type="text/javascript">
    var chart = AmCharts.makeChart("chartdiv_get_weekly_sales", {
        "theme": "light",
        "type": "serial",
        "startDuration": 2,
        "dataProvider": 
        [            
            {
                "Días": "<?php echo isset($weekly_sales['Lunes']['days']) ? $weekly_sales['Lunes']['days'] : 'Lunes' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Lunes']['quantity_sales']) ? $weekly_sales['Lunes']['quantity_sales'] : 0 ?>",
                "color": "#FF0F00"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Martes']['days']) ? $weekly_sales['Martes']['days'] : 'Martes' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Martes']['quantity_sales']) ? $weekly_sales['Martes']['quantity_sales'] : 0 ?>",
                "color": "#FF9E01"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Miércoles']['days']) ? $weekly_sales['Miércoles']['days'] : 'Miércoles' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Miércoles']['quantity_sales']) ? $weekly_sales['Miércoles']['quantity_sales'] : 0 ?>",
                "color": "#F8FF01"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Jueves']['days']) ? $weekly_sales['Jueves']['days'] : 'Jueves' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Jueves']['quantity_sales']) ? $weekly_sales['Jueves']['quantity_sales'] : 0 ?>",
                "color": "#04D215"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Viernes']['days']) ? $weekly_sales['Viernes']['days'] : 'Viernes' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Viernes']['quantity_sales']) ? $weekly_sales['Viernes']['quantity_sales'] : 0 ?>",
                "color": "#0D52D1"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Sábado']['days']) ? $weekly_sales['Sábado']['days'] : 'Sábado' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Sábado']['quantity_sales']) ? $weekly_sales['Sábado']['quantity_sales'] : 0 ?>",
                "color": "#8A0CCF"
            }, 
            {
                "Días": "<?php echo isset($weekly_sales['Domingo']['days']) ? $weekly_sales['Domingo']['days'] : 'Domingo' ?>",
                "quantity_sales": "<?php echo isset($weekly_sales['Domingo']['quantity_sales']) ? $weekly_sales['Domingo']['quantity_sales'] : 0 ?>",
                "color": "#CD0D74"
            }                                  
        ],
        "graphs": [{
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillColorsField": "color",
            "fillAlphas": 1,
            "lineAlpha": 0.1,
            "type": "column",
            "valueField": "quantity_sales"
        }],
        "valueAxes": [{
            "title": "Ventas realizadas"
        }],
        "depth3D": 20,
        "angle": 30,
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },    
        "categoryField": "Días",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 90
        },
        "export": {
            "enabled": true
        }
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

</script>