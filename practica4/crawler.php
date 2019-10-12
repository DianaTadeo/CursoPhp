<?php
	function revisar($url, $depth=5){
		static $seen = array();
		if (isset($seen[$url]) or $depth===0)
			return;

		$seen[$url] = true;
		$dom = new DOMDocument('1.0');
		@$dom->loadHTMLFile($url);
		#Recorremos el html hasta encontrar la aparicion de la etiqueta 'a' 
		$anchors = $dom->getElementsByTagName("a");
		foreach ($anchors as $element) {
			$href = $element->getAttribute("href");#Se obtiene el atributo 'href'
			if(strpos($href, "http")!==0){
				$host = "http://".parse_url($url,PHP_URL_HOST);
				$href = $host.'/'.ltrim($href, "/");
			}
			#Llamada recursiva por cada pagina encontrada
			revisar($href, $depth - 1);
		}
		echo "Pagina: $url \n";
		$dom->saveHTML();
		file_put_contents('paginas.txt',"$url \n",FILE_APPEND);
}
revisar("https://aztlan.fciencias.unam.mx/~canek/pensadero/",2);
?>
