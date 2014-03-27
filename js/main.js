function getXMLHttpRequest() {
  var xhr = null;

  if (window.XMLHttpRequest || window.ActiveXObject) {
    if (window.ActiveXObject) {
      try {
        xhr = new ActiveXObject("Msxml2.XMLHTTP");
      } catch(e) {
        xhr = new ActiveXObject("Microsoft.XMLHTTP");
      }
    } else {
      xhr = new XMLHttpRequest(); 
    }
  } else {
    alert("Pas d'Ajax, dommage!");
    return null;
  }

  return xhr;
}

//asynchronous call to the update process
function age_sexe_f() {
  var params = "table=age_sexe&sexe=f";
  var req = getXMLHttpRequest();
  req.onreadystatechange = function() {
    if (req.readyState == 4 && (req.status == 200 || req.status == 0)) {
      //return JSON.parse(req.responseText);
      var abs = [], values = [];
      var res = JSON.parse(req.responseText);
      var ct = 5;
      for(var cel in res) {
        values.push(parseInt(res[cel][1]));
        abs.push(res[cel][0]);  
        /*if(ct++ == 5) {
          ct = 0;
          abs.push(res[cel][0]);  
        } else
          abs.push('');*/
      }

      var lineChartData = {
            title: {
                text: 'Monthly Average Temperature',
                x: -20 //center
            },
            subtitle: {
                text: 'Source: WorldClimate.com',
                x: -20
            },
            xAxis: {
                categories: abs
            },
            yAxis: {
                min: 0, 
                title: {
                    text: 'Temperature (°C)'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                valueSuffix: '°C'
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle',
                borderWidth: 0
            },
            series: [{
                name: 'Tokyo',
                data: values
            }]
      }
      console.log(values);
      console.log(lineChartData);
      $('#container').highcharts(lineChartData);
    }
  };
  req.open("GET", "get.php?" + params, true);
  req.send(null);
}