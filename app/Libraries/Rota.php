<?php 
/*
	* Classe Rota
	* Cria as URL's, carrega os controladores, métodos e parâmetros
	* FORMATO URL - /controlador/metodo/parametros
*/

class Rota {
	// Atributos da Classe
	private $controlador = 'Paginas';
	private $metodo = 'index';
	private $parametros = [];

	public function __construct()
	{
		// Se a URL existir, joga a função url na variável $url
		$url = $this->url() ? $this->url() : [0];
		
		// Checa se o controlador existe
		// ucwords - Converte para maiúsculas o primeiro caractere de cada palavra 
		if(file_exists('../app/Controllers/'.ucwords($url[0]).'.php')):
			// Unset - Destrói a variável especifica
			$this->controlador = ucwords($url[0]);
			unset($url[0]);
		endif;

		// Requere o Controlador 
		require_once '../app/Controllers/'.$this->controlador.'.php';
		// Instancia o Controlador
		$this->controlador = new $this->controlador;

		// Checa se o método existe, segunda parte da url
		if(isset($url[1])):
			// method_exists - Checa se o método da classe existe
			if(method_exists($this->controlador, $url[1])):
				$this->metodo = $url[1];
				unset($url[1]);
			endif;
		endif;
		

		// Se existir retorna um array com os valores se não retorna um array vazio
		// array_values - Retorna todos os valores de um array
		$this->parametros = $url ? array_values($url) : [];
		// call_user_func_array - Chama uma dada função de usuário com um array de parâmetros
		call_user_func_array([$this->controlador, $this->metodo], $this->parametros);

		
	}

	// Retorna a url em um array
	private function url(){
		// O filtro FILTER_SANITIZE_URL remove todos os caracteres ilegais em uma URL
		$url = filter_input(INPUT_GET, 'url', FILTER_SANITIZE_URL);
		// Verificar se a url existe
		if (isset($url)){
			// trim - Retira os espaços no início e final de uma string
			// rtrim - Retira espaços em branco (ou outros caracteres) do final da string
			$url = trim(rtrim($url, '/'));
			// explode - Devide uma string em strings, retorna um array
			$url = explode('/', $url);
			return $url;
		}
	}
}



