<?php

$nazwa_pliku = $_FILES["file1"]["name"];
$str = str_replace('\\', '/', $str);
$katalog = getcwd();

// Załączenie pliku connect.php

require_once "connect.php";
	

// QUERY SQL

$create_table_sql="CREATE TABLE IF NOT EXISTS `studenci` (
				`id` int(11) NOT NULL,
				`imie` text COLLATE utf8_polish_ci NOT NULL,
				`nazwisko` text COLLATE utf8_polish_ci NOT NULL,
				`nazwa_przedmiotu` text COLLATE utf8_polish_ci NOT NULL,
				`ocena` int(11) NOT NULL,
				`datae` date NOT NULL
				) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci";

$clean_table_sql="TRUNCATE TABLE studenci";

$mediana_sql="SELECT x.`ocena` from studenci x, studenci y 
			GROUP BY x.`ocena` 
			HAVING SUM(SIGN(1-SIGN(y.`ocena`-x.`ocena`)))/COUNT(*) > .5 
			LIMIT 1";

$lista_sql="SELECT imie, nazwisko, ocena, nazwa_przedmiotu, datae FROM studenci";
	
	
	
$upload_danych_sql=sprintf('LOAD DATA INFILE "%s/%s" into table studenci
							FIELDS TERMINATED BY ";"
							LINES TERMINATED BY "\r\n"',$katalog,$nazwa_pliku);
							
	

// Połączenie z bazą danych i wysłanie kwerend

$polaczenie = @new mysqli($host, $db_user, $db_password, $db_name);
	
	if ($polaczenie->connect_errno!=0)
	{
		echo "Error: ".$polaczenie->connect_errno;
	}
	else
	{
		
				
		
		$polaczenie->query($create_table_sql);
		$polaczenie->query($upload_danych_sql);
		

		$rezultat = $polaczenie->query($lista_sql);
		$result_med= $polaczenie->query($mediana_sql);
		
		$polaczenie->query($clean_table_sql);
		
		
// Obsługa przesłanych danych	

echo "</br>Dane pobrano z pliku: "$nazwa_pliku"</br></br>";

echo "<table>
<tr>
<th>Imie</th>
<th>Nazwisko</th>
<th>Nazwa Przedmiotu</th>
<th>Ocena</th>
<th>Data egzaminu</th>

</tr>";
while($row = mysqli_fetch_assoc($rezultat)) {
    echo "<tr>";
    echo "<td>" . $row['imie'] . "</td>";
    echo "<td>" . $row['nazwisko'] . "</td>";
    echo "<td>" . $row['nazwa_przedmiotu'] . "</td>";
    echo "<td>" . $row['ocena'] . "</td>";
    echo "<td>" . $row['datae'] . "</td>";
    echo "</tr>";
}
echo "</table>";

	echo "</br>";
			
	$row_med=mysqli_fetch_assoc($result_med);
		printf("<b><p>Mediana ocen: %s</p></b>", $row_med["ocena"]);	
    
        $rezultat->free();
		$result_med->free();	
		$polaczenie->close();
	}
?>
