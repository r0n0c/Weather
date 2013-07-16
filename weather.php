<?php
include_once('sqlconnect.php');
include_once('feeds.php');
// connecting to weather db
mysql_connect($host,$myuser,$mypass);
@mysql_select_db(weather) or die("Unable to select database");
// Get XML data from source
$feed1 = file_get_contents("http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KWISUNPR7");
$feed2 = file_get_contents("http://api.wunderground.com/weatherstation/WXCurrentObXML.asp?ID=KWISUNPR3");
// Check to ensure the feed exists
if(!$feed1){
die('Weather not found! Check feed URL');
}
// moving to simple XML element
$xmlc = new SimpleXmlElement($feed1);
$xmln = new SimpleXmlElement($feed2);

//filling in vairiables
$locationc = $xmlc->location->neighborhood;
$locationn = $xmln->location->neighborhood;
$raintodayc = $xmlc->precip_today_in;
$raintodayn = $xmln->precip_today_in;
$dewc = $xmlc->dewpoint_f;
$dewn = $xmln->dewpoint_f;
$tempfc = $xmlc->temp_f;
$tempfn = $xmln->temp_f;
$rainhourc = $xmlc->precip_1hr_in;
$rainhourn = $xmln->precip_1hr_in;
$timexc = $xmlc->observation_time;
//removes 'Last Updated on ' from preceeding the time variable
$timec = substr($timexc, 16);
$timexn = $xmln->observation_time;
//removes 'Last Updated on ' from preceeding the time variable
$timen = substr($timexn, 16);
$pressc = $xmlc->pressure_mb;
$pressn = $xmln->pressure_mb;
$windec = $xmlc->wind_degrees;
$winden = $xmln->wind_degrees;
$windirc = $xmlc->wind_dir;
$windirn = $xmln->wind_dir;
$windmphc = $xmlc->wind_mph;
$windmphn = $xmln->wind_mph;
$windgustc = $xmlc->wind_gust_mph;
$windgustn = $xmln->wind_gust_mph;
$relhumc = $xmlc->relative_humidity;
$relhumn = $xmln->relative_humidity;
$stationc = $xmlc->station_id;
$stationn = $xmln->station_id;

//writing to db vairiables
$query1 = "INSERT INTO weather (wr_station, wr_temp, wr_1hr, wr_today, wr_dew, ,wr_pressure, wr_winder, wr_windir, wr_windmph, wr_windgust, wr_hum) VALUES ('$stationc', '$tempfc', '$rainhourc', '$raintodayc', '$dewc', '$pressc', '$windec', '$windirc', '$windmphc', '$windgustc', '$relhumc')";
$query2 = "INSERT INTO weather (wr_station, wr_temp, wr_1hr, wr_today, wr_dew, ,wr_pressure, wr_winder, wr_windir, wr_windmph, wr_windgust, wr_hum) VALUES ('$stationn', '$tempfn', '$rainhourn', '$raintodayn', '$dewn', '$pressn', '$winden', '$windirn', '$windmphn', '$windgustn', '$relhumn')";

// Making the push
mysql_query($query1) or die("can't push query1");
mysql_query($query2) or die("can't push query2");

//Verifying Info
echo "The following has been submitted to the database! <BR><BR>";
echo "<p>$locationc</p> <p>Current Temp: $tempfc F</p> <p>Total rain today $raintodayc</p> <p>Rain in the last hour: $rainhourc</p> <p>Dewpoint: $dewc</p> <p>Collected at: $timec</p>";
echo "<BR><BR>";
echo "<p>$locationn</p> <p>Current Temp: $tempfn F</p> <p>Total rain today $raintodayn</p> <p>Rain in the last hour: $rainhourn</p> <p>Dewpoint: $dewn</p> <p>Collected at: $timen</p>";

//closeing db connection
mysql_close();

?>
