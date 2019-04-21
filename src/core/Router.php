<?php  
class Router{

	private $config;

	private $database;

	public $routes    = array();

	public $redirects = array();

	public $params    = array();

	public $page   = "";

	public function __construct($config, $database) {
		$this->config = $config;
		$this->database = $database;
	}

	public function load(){
		$this->format();
		$this->parse();
		$this->loadPage();
	}

	public function format(){
		$suffix = "";

		if (isset($_GET['p'])) {
			$this->page = !empty($_GET['p']) ? addslashes(htmlentities($_GET['p'])) : null;
		} else {
			$this->page = $_SERVER['REQUEST_URI'];
		}

		$this->page = trim($this->page, '/');

		define('BASE', 'https://'.$_SERVER['HTTP_HOST'].'/');

		// Règles de page spécifiques
		if (empty($this->page)) {
			$this->page = 'index';
		}
		if ($this->page == "\'\'\'") {
			redirect(BASE);
		}
		if ($this->page == "connexion") {
			$this->page = "login";
		}
	}

	public function parse() {
		// Redirections
		foreach ($this->redirects as $regex => $v) {
			$regex = "/" . preg_replace("/\//", "\/", $regex) . "/";

			if (preg_match($regex, $this->page)) {
				redirect($v);
			}
		}

		// Routes
		foreach ($this->routes as $regex => $v) {
			$regex   = preg_replace("/\*\*/", "([0-9]+)", $regex);
			$reg   = "/" . preg_replace("/\//", "\/", preg_replace("/\*/", "(.*)", $regex)) . "/";
			$matches = array();

			$subpath = (strpos($v, "[.]") !== false);
			if($subpath) $v = str_replace("[.]", "", $v);

			if(preg_match($reg, $this->page, $matches)){

				if(empty($matches)) continue;
				array_shift($matches);

				if($subpath){
					$f = $this->page;
					foreach ($matches as $v) $f = str_replace($v, "", $f);

					$v = str_replace("//", "/", $f);
					if(substr($v, -1) == "/") $v = substr($v, 0, -1);
				}
				$this->page = $v;

				if(count($matches) == 1){
					$this->params = addslashes(htmlentities($matches[0]));
					continue;
				}

				foreach ($matches as $v) $this->params[] = addslashes(htmlentities($v));
			}
		}

		if(is_array($this->params)){
			foreach($this->params as $k => $v){
				if(strpos($v, "/") !== false){
					$this->params[$k] = explode("/", $v);
				}
			}
		}else{
			if(strpos($this->params, "/") !== false){
				$this->params = explode("/", $this->params);
				
				if(count($this->params) == 2 && empty($this->params[1]))
					$this->params = $this->params[0];
			}
		}
	}

	public function loadPage(){
		if ($this->page == null) return false;

		// On appelle le controleur dédié
		$root      = $_SERVER["DOCUMENT_ROOT"];
		$fileExist = (file_exists(SRC . DS . 'views' . DS . $this->page . '.php')) ? true : false;
		$ctrlName  = "Index";
		$action    = "index";

		if (!$fileExist) {
			header("HTTP/1.0 404 Not Found");
			$this->page = "errors/error404";
		}

		$split = preg_split("/\//", $this->page);
		if(count($split)>0) $ctrlName = ucfirst($split[0]);
		if(count($split)>1) $action = ucfirst($split[1]);

		$action = str_replace("-", "_", $action);

		$ctrlName = $ctrlName.'Controller';
		require(SRC . DS . 'controllers' . DS . $ctrlName . '.php');
		$ctrl = new $ctrlName($this->config, $this->database);
		$action = strtolower($action);
		$ctrl->$action($this->params);

		// foreach ($ctrl->getData() as $k => $v) ${$k} = $v;
		$d = $ctrl->getData();

		$content_for_layout = false;

		// Custom vars
		$header = ($this->page == 'index') ? true : false;

		$doMaintenance     = false;
		$shouldMaintenance = $this->config['maintenance'];

		if($shouldMaintenance && (!getUser() || (getUser()->grade != "administrateur" && getUser()->grade != "administrator")) && $ctrlName != "LoginController")
			$doMaintenance = true;

		if ($doMaintenance) {
			$protocol = "HTTP/1.0";
			if("HTTP/1.1" == $_SERVER["SERVER_PROTOCOL"]) $protocol = "HTTP/1.1";

			header( "$protocol 503 Service Unavailable", true, 503 );
			header( "Retry-After: 3600" );
			$this->page = "errors/error503";
		}

		$config = $this->config;

		ob_start();
		require SRC . DS . 'views' . DS . $this->page . '.php';
		$content_for_layout = ob_get_clean();
		require SRC . DS . 'views' . DS . 'template.php';
	}



	public function addRoute($regex, $to){
		$this->routes[$regex] = $to;
	}

	public function addRedirection($regex, $to){
		$this->redirects[$regex] = $to;
	}

}
?>