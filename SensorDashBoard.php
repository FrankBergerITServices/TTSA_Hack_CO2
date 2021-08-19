<!doctype html><html lang=en><head>
<title>TTSA Hackathon</title>
<meta charset='UTF-8'>
<link rel='icon' type='image/png' sizes='16x16' href='data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAABHNCSVQICAgIfAhkiAAAAHtJREFUOE9jvMnA+5+BAsBIFQMkl85h+P3kGcOb8jqwW+TPH2H4de0GA29UGNxtfx49YWCRk0HwHz5iuKegwwB2AS4DkA2F6VR6cAWsEQbgBqDY9vARw/ejJ+Au+LxsFcPz6BSwHpwGYPMCSS6gyAAKYhESiKMGjPgwAADopHVhn5ynEwAAAABJRU5ErkJggg=='/>
<link rel='stylesheet' href='https://unpkg.com/purecss@2.0.3/build/pure-min.css'>
<link rel='stylesheet' href='https://unpkg.com/purecss@2.0.3/build/grids-responsive-min.css'>
<script src='https://cdn.plot.ly/plotly-basic-1.58.2.min.js'></script>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<meta http-equiv="refresh" content="30" />
</head>
<body>
<div class='pure-g'><div class='pure-u-1'><div class='pure-menu'><p class='pure-menu-heading'>The Things Summer Academy Hackathon CO<sub>2</sub> Project</p></div></div>
<div class='pure-u-1'><ul class='pure-menu pure-menu-horizontal pure-menu-list'>
</ul></div></div>


    <form name='testform' method='POST'>
      <select id="device_id_select" name='cat' onChange="updateDeviceId()">

<?php
  $db = new SQLite3('measurements.db');
  $result = $db->query("SELECT DISTINCT	device_id FROM measurements");      

  while($row = $result->fetchArray()) {
    echo "<option value='{$row['device_id']}'>{$row['device_id']}</option>\n";
  }
?>
      </select>
	  
  
    </form>
	


<script>




hue=(1-(Math.min(Math.max(parseInt(document.title),500),1600)-500)/1100)*120;
document.getElementById('led').style.color=['hsl(',hue,',100%,50%)'].join('');
</script>
<div class='pure-g'>
<div class='pure-u-1' id='graph'></div>
</div>
<div class='pure-g'>
<div id='log' class='pure-u-1 pure-u-md-1-2'></div>
</div>
<script>
document.body.style.cursor = 'default';

var device_id;
var get_data_url;

			function updateDeviceId() {
				var select = document.getElementById('device_id_select');
                device_id = select.options[select.selectedIndex].value;
				
				get_data_url = '/TT4102138/getData.php?device_id='+ device_id;
				
                console.log(device_id);
				
				console.log(get_data_url);

fetch(get_data_url,{credentials:'include'})
.then(response=>response.text())
.then(csvText=>csvToTable(csvText))
.then(_=>Plotly.newPlot('graph',data,layout,{displaylogo:false}))
.catch(e=>console.error(e));
xs=[];
data=[{x:xs,y:[],type:'scatter',name:'CO<sub>2</sub>',line:{color:'#2ca02c'}},
{x:xs,y:[],type:'scatter',name:'Temperature',yaxis:'y2',line:{color:'#ff7f0e',dash:'dot'}},
{x:xs,y:[],type:'scatter',name:'Humidity',yaxis:'y3',line:{color:'#1f77b4',dash:'dot'}}];
layout={height:600,title:device_id,legend:{xanchor:'right',x:0.2,y:1.0},
xaxis:{domain:[0.0,0.85]},yaxis:{ticksuffix:'ppm',range:[0,2000],dtick:200},
yaxis2:{overlaying:'y',side:'right',ticksuffix:'°C',position:0.9,anchor:'free',range:[0,30],dtick:3},
yaxis3:{overlaying:'y',side:'right',ticksuffix:'%',position:0.95,anchor:'free',range:[0,100],dtick:10}
};

			}

			updateDeviceId();


function csvToTable(csvText) {
csvText=csvText.trim();
lines=csvText.split('\n');
table=document.createElement('table');
table.className='pure-table-striped';
n=lines.length;
lines.forEach((line,i)=>{
fields=line.split(';');
xs.push(fields[0]);
data[0]['y'].push(fields[1]);
data[1]['y'].push(fields[2]);
data[2]['y'].push(fields[3]);
if(i>4 && i<n-12){if(i==5){fields=['...','...','...','...']}else{return;}}
row=document.createElement('tr');
fields.forEach((field,index)=>{
cell=document.createElement(i<2?'th':'td');
cell.appendChild(document.createTextNode(field));
row.appendChild(cell);});
table.appendChild(row);});
return table;}

function addLogTableToPage(table){document.getElementById('log').appendChild(table);}

</script>
</body>
</html>