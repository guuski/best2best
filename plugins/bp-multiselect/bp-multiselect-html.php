<?php
function geg_getHTML() {
	
	$output="
	<div id='GEG_contenitore'>
		  <p>Quarta soluzione</p>
		  <form action='#' method='POST'>
			<div class='GEG_affianca'>
				<select multiple=\"multiple\" size='10'>
					<optgroup label=\"Arredi\">
						<option value=\"Arredi scolastici\">Arredi scolastici</option>
						<option value=\"Arredi per ufficio\">Arredi per ufficio</option>
						<option value=\"Arredi per seggi elettorali\">Arredi per seggi elettorali</option>
						<option value=\"Tende, veneziane, tappezzerie e articoli affini\">Tende, veneziane, tappezzerie e articoli affini</option>
						<option value=\"Porte, finestre, scale e articoli affini\">Porte, finestre, scale e articoli affini</option>
						<option value=\"Arredi vari\">Arredi vari</option>
					</optgroup>
				</select>
			</div>
			
			<div class='GEG_affianca'>
				<select multiple=\"multiple\" size='10'>
					<optgroup label=\"Ristorazione\">
						<option value=\"Buoni pasto\">Buoni pasto</option>
						<option value=\"Ristorazione\">Ristorazione</option>
						<option value=\"Varie\">Varie</option>
					</optgroup>
				</select>
			</div>
			
			<div class='GEG_affianca'>
				<select  multiple=\"multiple\" size='10'>
					<optgroup label=\"Ufficio e Cancelleria\">
						<option value=\"Carta per tipografia\">Carta per tipografia</option>
						<option value=\"Carta per fotocopiatrici\">Carta per fotocopiatrici</option>
						<option value=\"Cancelleria\">Cancelleria</option>
						<option value=\"Modulistica\">Modulistica</option>
						<option value=\"Materiali di consumo\">Materiali di consumo</option>
						<option value=\"Macchine e attrezzature d'ufficio\">Macchine e attrezzature d&#40ufficio</option>
						<option value=\"Computers e periferiche\">Computers e periferiche</option>
						<option value=\"Apparati di rete\">Apparati di rete</option>
						<option value=\"Software\">Software</option>
						<option value=\"Pubblicazioni\">Pubblicazioni</option>
					</optgroup>
				</select> 
			</div>		
			
		  </form>
		  <div id='output'></div>
	  </div>
	  ";
	  echo $output;
  }
?>
