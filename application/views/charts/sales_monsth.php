<script type="text/javascript">
set_sales_monsth(<?php echo $sales_monsth!=null?json_encode($sales_monsth):'[]'?>);
function set_sales_monsth(sales_monsth){
    $("#chartdiv_get_sales_monsth").highcharts({
        chart: {
            style: {
                fontFamily: "Open Sans"
            }
        },
        title: {
            text: "",
            x: -20
        },
        subtitle: {
           // text: "Source: WorldClimate.com",
           //x: -20
        },
        xAxis: {
            categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"]
        },
        yAxis: {
            title: {
                text: "Total"
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: "#808080"
            }],
            labels: {
                formatter: function() {
                    return Highcharts.numberFormat(this.value,0);
                }
            }
        },
        tooltip: {
            valueSuffix: ""
        },
        legend: {
            layout: "vertical",
            align: "right",
            verticalAlign: "middle",
            borderWidth: 0
        },
        series: sales_monsth
    })
}
</script>