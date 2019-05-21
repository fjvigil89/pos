<script type="text/javascript">
    var chart = AmCharts.makeChart("chartdiv_items_scarce", {
        "theme": "light",
        "type": "serial",
        "startDuration": 2,
        "dataProvider": 
					<?php if($items_scarce!=null)
						{?> 
							<?php echo json_encode($items_scarce); ?>,
						<?php }
						else
					{?>
						[],
					<?php }?>
        
        "graphs": [{
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillColorsField": "color",
            "fillAlphas": 1,
            "lineAlpha": 0.1,
            "type": "column",
						"topRadius": 1,
            "valueField": "cantidad"
        }],
        "valueAxes": [{
            "title": "<?php echo lang('reports_quantity') ?>"
        }],
        "depth3D": 20,
        "angle": 60,
        "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
        },    
        "categoryField": "name",
        "categoryAxis": {
            "gridPosition": "start",
            "labelRotation": 45
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