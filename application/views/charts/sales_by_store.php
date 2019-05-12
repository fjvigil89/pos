<script type="text/javascript">
    set_sales_by_store_total(<?php echo $sales_total_by_store!=null?json_encode($sales_total_by_store):'[]'?>);
  							
    function set_sales_by_store_total(sales_total_by_store){
	var sales_by_store_total = AmCharts.makeChart("chartdiv_sales_by_store_total", {
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
	  	"dataProvider":sales_total_by_store,
	  	"valueField": "total_sales",
	  	"titleField": "name",
	  	"export": {
	    	"enabled": true
	  	}
	});
	
	AmCharts.addInitHandler(function(sales_by_store_total) {
  
	  // check if data is mepty
	  if (sales_by_store_total.dataProvider === undefined || sales_by_store_total.dataProvider.length === 0) {
	    // add some bogus data
	    var dp = {};
	    dp[sales_by_store_total.titleField] = "";
	    dp[sales_by_store_total.valueField] = '1';
	    sales_by_store_total.dataProvider.push(dp)
	    
	    
	    
	    // disable slice labels
	    sales_by_store_total.labelsEnabled = false;
	    
	    // add label to let users know the chart is empty
	    sales_by_store_total.addLabel("50%", "50%", "No hay datos para mostrar", "middle", 15);
	    
	    // dim the whole chart
	    sales_by_store_total.alpha = 0.3;
	  }
  
}, ["pie"]);
}

</script>