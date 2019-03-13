<script type="text/javascript">
	var chart = AmCharts.makeChart("chartdiv_best_sellers_items", {
	  	"type": "pie",
	  	"startDuration": 0,
	   	"theme": "light",
	  	"addClassNames": true,
	  	"legend":{
	   		"position":"right",
	    	"marginRight":100,
	    	"autoMargins":false
	  	},
	  	"innerRadius": "30%",
	  	"defs": {
	    	"filter": [{
	      		"id": "shadow",
	      		"width": "200%",
	  			"height": "200%",
	      		"feOffset": {
	        		"result": "offOut",
	        		"in": "SourceAlpha",
	        		"dx": 0,
	        		"dy": 0
	      		},
	      		"feGaussianBlur": {
	        		"result": "blurOut",
	        		"in": "offOut",
	        		"stdDeviation": 5
	      		},
	      		"feBlend": {
	        		"in": "SourceGraphic",
	        		"in2": "blurOut",
	        		"mode": "normal"
	      		}
	    	}]
	  	},
	  	"dataProvider": <?php if($best_sellers_items!=null)
  						{?> 
  							<?php echo json_encode($best_sellers_items); ?>,
  						<?php }
  						else
						{?>
							[],
						<?php }?>
	  	"valueField": "quantity_purchased",
	  	"titleField": "name",
	  	"export": {
	    	"enabled": true
	  	}
	});
	
	AmCharts.addInitHandler(function(chart) {
  
	  // check if data is mepty
	  if (chart.dataProvider === undefined || chart.dataProvider.length === 0) {
	    // add some bogus data
	    var dp = {};
	    dp[chart.titleField] = "";
	    dp[chart.valueField] = '1';
	    chart.dataProvider.push(dp)
	    
	    
	    
	    // disable slice labels
	    chart.labelsEnabled = false;
	    
	    // add label to let users know the chart is empty
	    chart.addLabel("50%", "50%", "No hay datos para mostrar", "middle", 15);
	    
	    // dim the whole chart
	    chart.alpha = 0.3;
	  }
  
}, ["pie"]);

</script>