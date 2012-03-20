<?php

include_once('provedb_query.php');

class mydb
{
	// parametri per la connessione al database
	private $nomehost = "localhost";    
	private $nomeuser = "root";         
	private $password = "password";
	private $connessione;
          
	// controllo sulle connessioni attive
	private $attiva = false;
 
	// funzione per la connessione a MySQL
	public function connetti()
	{
		echo 'connetti()';
		if(!$this->attiva)
		{
			$this->connessione = mysql_connect($this->nomehost,$this->nomeuser,$this->password);
		}
		else{
			return true;
			}
    }

	public function disconnetti()
	{
		echo 'disconnetti()';
        if($this->attiva)
        {
                if(mysql_close())
                {
					mysql_close($this->connessione) or die (mysql_error());
					$this->attiva = false;
					return true;
                }else{
                        return false;
                }
        }
	}

	//funzione per l'esecuzione delle query
	public function query($sql)
	{
		echo '<li>'.$sql.'</li>';
		if(isset($this->attiva))
		{
			$sql = mysql_query($sql) or die (mysql_error());
			return $sql;
		}
		else
			{
				return false;
			}
		
	}
	public function selectdb($db){
		mysql_select_db($db, $this->connessione) or die (mysql_error());
	}
}


echo '<hr />Creo la Classe mydb  <br />';

$data = new mydb();
// connessione a MySQL

echo '<hr />faccio la connect <br />';
$data->connetti();


echo '<hr />eseguo le query <br />';
// chiamata alla funzione per la creazione del database


echo "<ol>";
$data->query($drop_db);
$data->query($crea_db);			
$data->selectdb($database);		
$data->query($crea_tabella);	

foreach ($inserimento as $key => $value)
	$data->query($inserimento[$key]);	
	
echo "</ol>";


echo '<hr />disconnetto <br />';
// disconnessione
$data->disconnetti();

echo '<hr />fine <br />';
?>
