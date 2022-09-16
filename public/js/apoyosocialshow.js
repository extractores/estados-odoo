urlgeeneral=$("#url_raiz_proyecto").val();

window.addEventListener("load", function (event) {

    //listadocategorias();
    $(".loader").fadeOut("slow"); 

   

    });
   ///DATOS TEXTAREA

   CKEDITOR.config.height = 100;
   CKEDITOR.config.width  = 'auto';
   CKEDITOR.replace('descripcion');


  
    setInterval(function () {

        function getChartColorsArray(e) {
            e = $(e).attr("data-colors");
            return (e = JSON.parse(e)).map(function (e) {
                e = e.replace(" ", "");
                if (-1 == e.indexOf("--")) return e;
                e = getComputedStyle(document.documentElement).getPropertyValue(e);
                return e || void 0;
            });
        }
            var lineColors = getChartColorsArray("#line-chart"),
            dom = document.getElementById("line-chart"),
            myChart = echarts.init(dom),
            app = {};
        (option = null),
            (option = {
                grid: {
                    zlevel: 0,
                    x: 50,
                    x2: 50,
                    y: 30,
                    y2: 30,
                    borderWidth: 0,
                    backgroundColor: "rgba(0,0,0,0)",
                    borderColor: "rgba(0,0,0,0)",
                },
                xAxis: {
                    type: "category",
                    data: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                    axisLine: { lineStyle: { color: "#858d98" } },
                },
                yAxis: {
                    type: "value",
                    axisLine: { lineStyle: { color: "#858d98" } },
                    splitLine: { lineStyle: { color: "rgba(133, 141, 152, 0.1)" } },
                },
                series: [
                    { data: [820, 932, 901, 934, 1290, 1330, 1320], type: "line" },
                ],
                color: lineColors,
            }),
            option && "object" == typeof option && myChart.setOption(option, !0);



        
    }, 10000)
  
