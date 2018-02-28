<html>
 <head>
<meta charset="utf-8">
  <title>
   Wynik
  </title>
 </head>

  <body>
  
  <?php //echo"<pre>";var_dump($_SERVER); ?>
  
  <p style="text-align: center;">Miasto: <?php echo $_POST['Miasto'] . ' IP: '. $_SERVER['REMOTE_ADDR']?></p>
  
 <?php
  //define (DBUSER, "pik");//nazwa uzytkownika lub adres serwera
  
  $api = "http://api.openweathermap.org/data/2.5/forecast?q=".$_POST['Miasto']."&mode=json&lang=pl&units=metric&APPID&APPID=9c398cd4cf22ab63cebf65a655f9d64d";
  // Get cURL resource
$curl = curl_init();
// Set some options - we are passing in a useragent too here
curl_setopt_array($curl, array(
    CURLOPT_RETURNTRANSFER => 1,
    CURLOPT_URL => $api,
));
// Send the request & save response to $resp
$resp = curl_exec($curl);
$resp = json_decode($resp, TRUE);
// Close request to clear up some resources
curl_close($curl);
  
  $conn = new mysqli ("localhost","root","","prognoza");
  if (mysqli_connect_errno()!=0)
  {
  $msg = mysqli_connect_error();
  echo "Wystąpił błąd: $msg";
  }
 else
 {

 

echo "<table style='width:100%; text-align:center;font-size:12px'>";
echo <<<HTML
<thead>
<tr>
<td>Dzień</td>
<td>Temperatura</td>
<td>Cisnienie</td>
<td>Wilgotnosc</td>
<td>Pogoda</td>
<td>Prędkośc wiatru</td>
</tr>
</thead>
HTML;

$from_date = ''; $to_date='';

 foreach($resp['list'] as $idx=>$ar){
    if($idx == 0){$from_date =$ar['dt_txt'];}


if (strpos($ar['dt_txt'], '12:00') !== false) {
  echo "<tr><td>" .$ar['dt_txt']."</td><td>"."". $ar['main']['temp'] ."</td><td>"."". $ar['main']['pressure'] ."</td><td>"."". $ar['main']['humidity'] ."</td><td>"."". $ar['weather'][0]['description'] ."</td><td>"."". $ar['wind']['speed'] ."</td>  </tr>";
    $to_date =$ar['dt_txt'];
    }
      

   //  <tr>
//	<td>..yty</td>	<td>.uugy..</td>
   // <td>..gygg.</td>	<td>...hvyv</td>
   // <td>..gygg.</td>	<td>...hvyv</td>
//</tr>
    
 }
echo "</table>";

$id = $_SERVER['REMOTE_ADDR'];
$town = $_POST['Miasto'];
$frdate = $from_date;
$todate = $to_date;
$ip = $_SERVER['REMOTE_ADDR'];
$sql = "INSERT INTO search_results (town,from_date,to_date,ip_address) VALUES ( '".$town."', '".$frdate."','".$todate."','".$ip."')";
 //$sql = "<SELECT * FROM `miasto`>";
 $t = $conn->query($sql);

$conn->close();
 }
 
?>
 </body>

</html>



