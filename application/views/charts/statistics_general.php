<script type="text/javascript">
	
	//Grafica de barras mostrando los totales de los modulos
	var chart = AmCharts.makeChart("chartdiv_statistics_general", {
	  "type": "serial",
	  "theme": "light",
	  "dataProvider": [{
	    "module": '<?php echo lang("module_items"); ?>',
	    "total": '<?php echo $total_items; ?>',
	    "color": "#FF0F00"
	  }, {
	    "module": '<?php echo lang("module_customers"); ?>',
	    "total": '<?php echo $total_customers?>',
	    "color": "#FF6600"
	  }, {
	    "module": '<?php echo lang("module_employees"); ?>',
	    "total": '<?php echo $total_employees?>',
	    "color": "#FF9E01"
	  }, {
	    "module": '<?php echo lang("module_item_kits"); ?>',
	    "total": '<?php echo $total_item_kits?>',
	    "color": "#FCD202"
	  }, {
	    "module": '<?php echo lang("module_sales"); ?>',
	    "total": '<?php echo $total_sales?>',
	    "color": "#F8FF01"
	  }, {
	    "module": '<?php echo lang("module_giftcards"); ?>',
	    "total": '<?php echo $total_giftcards?>',
	    "color": "#B0DE09"
	  }],
	  "valueAxes": [{
	    "axisAlpha": 0,
	    "position": "left",
	    "title": "Cantidad Total por módulo"
	  }],
	  "startDuration": 1,
	  "graphs": [{
	    "balloonText": "<b>[[category]]: [[value]]</b>",
	    "fillColorsField": "color",
	    "fillAlphas": 0.9,
	    "lineAlpha": 0.2,
	    "type": "column",
	    "valueField": "total"
	  }],
	  "chartCursor": {
	    "categoryBalloonEnabled": false,
	    "cursorAlpha": 0,
	    "zoomable": false
	  },
	  "categoryField": "module",
	  "categoryAxis": {
	    "gridPosition": "start",
	    "labelRotation": 45
	  },
	  "export": {
	    "enabled": true
	  }

	});

	var v = '<?php echo $total_items ?>'; 
	console.log(v);

</script>