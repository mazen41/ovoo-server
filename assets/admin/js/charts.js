// doughnut


function barChart(element, currency, series, categories, height = 380) {
     let barColors = ['#06a1bc', '#fd7412'];

     let options = {
          series: series,
          chart: {
               type: 'bar',
               height: height,
               toolbar: {
                    show: true,
                    offsetX: 0,
                    offsetY: 0,
                    tools: {
                         download: true,
                         selection: true,
                         zoom: true,
                         zoomin: true,
                         zoomout: true,
                         pan: true,
                         reset: true,
                         customIcons: []
                    },
                    export: {
                         csv: {
                              filename: undefined,
                              columnDelimiter: ',',
                              headerCategory: 'category',
                              headerValue: 'value',
                              dateFormatter(timestamp) {
                                   return new Date(timestamp).toDateString()
                              }
                         },
                         svg: {
                              filename: undefined,
                         },
                         png: {
                              filename: undefined,
                         }
                    },
                    autoSelected: 'zoom'
               },
          },
          plotOptions: {
               bar: {
                    horizontal: false,
                    columnWidth: '50%',
                    endingShape: 'rounded'
               },
          },
          dataLabels: {
               enabled: false
          },
          stroke: {
               show: true,
               width: 2,
               colors: ['transparent']
          },
          xaxis: {
               categories: categories,
          },

          yaxis: {
               title: {
                    text: currency,
                    style: {
                         color: '#7c97bb'
                    }
               }
          },
          grid: {
               xaxis: {
                    lines: {
                         show: false
                    }
               },
               yaxis: {
                    lines: {
                         show: false
                    }
               },
          },
          fill: {
               opacity: 1,
               colors: barColors
          },
          tooltip: {
               y: {
                    formatter: function (val) {
                         return currency + " " + val + " "
                    },
               },
               marker: {
                    fillColors: barColors
               },
          },
          legend: {
               show: true,
               markers: {
                    fillColors: barColors
               },
               labels: {
                    colors: barColors
               }
          }
     };

     let chart = new ApexCharts(element, options);
     chart.render();
     return chart
}
