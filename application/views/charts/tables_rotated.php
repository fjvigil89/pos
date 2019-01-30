<script type="text/javascript">
	
	//Grafica de barras mostrando los totales de los modulos
	var chart = AmCharts.makeChart("details_tables", {
	  "type": "serial",
	  "theme": "light",
	  "dataProvider": [
	  <?php for ($i=0; $i < $ntable; $i++) { ?>
	  {
	    "module": ' <?php echo lang("table").": ".($i+1); ?>',
	    "total": '<?php 
					if(isset($tables[($i+1)]))
					echo $tables[($i+1)]; 
					else echo 0;
					?>',
	    "color": "#FF6600"
	  },
	  <?php }?>
	   ],
	  "valueAxes": [{
	    "axisAlpha": 0,
	    "position": "left",
	    "title": "Cantidad Total Ocupada"
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

	var v = '<?php echo 56; ?>'; 
	console.log(v);

</script>