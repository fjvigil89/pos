<script type="text/javascript">	 
	var chart = AmCharts.makeChart("chartdiv_by_empleados_sales",
	{
	    "type": "serial",
		"theme": "light",
	    "dataProvider": [
	    	<?php 
	    		if($sales_by_employees != null)
	    		{
					foreach ($sales_by_employees as $value) {?>	
		  			{
		    			"Empleado": '<?php echo $value["first_name"]; ?>',
		    			"total": '<?php echo $value["total"]; ?>',
		    			"color": '<?php echo "#".$value["color"]; ?>',
		    			"bullet": '<?php echo $value["image_id"] ? site_url("app_files/view/".$value["image_id"]) : base_url("img/no-image.png")?>',
		    		},	    			
	    			<?php }
				}else
				{?>
					{"Empleado": '<?php echo "Vacio" ?>',
		    			"total": '<?php echo "Vacio" ?>',
		    			"color": '<?php echo "Vacio" ?>',
		    			"bullet": '<?php echo base_url("img/no-image.png")?>'}
			<?php } ?>

		],
	    "valueAxes": [{
	        "maximum": 10,
	        "minimum": 0,
	        "axisAlpha": 0,
	        "dashLength": 4,
	        "position": "left"
	    }],
	    "startDuration": 1,
	    "graphs": [{
	        "balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
	        "bulletOffset": -25,
	        "bulletSize": 50,
	        "colorField": "color",
	        "cornerRadiusTop": 8,
	        "customBulletField": "bullet",
	        "fillAlphas": 0.8,
	        "lineAlpha": 0,
	        "type": "column",
	        "valueField": "total"
	    }],
	    "marginTop": 0,
	    "marginRight": 0,
	    "marginLeft": 0,
	    "marginBottom": 0,
	    "autoMargins": false,
	    "categoryField": "Empleado",
	    "categoryAxis": {
	        "axisAlpha": 0,
	        "gridAlpha": 0,
	        "inside": true,
	        "tickLength": 0
	    },
	    "export": {
	    	"enabled": true
	    }
	});

	

</script>
