<?php
class LoginController extends Controller {

	public function index() {
		connectPage();
	}

	public function process() {
		connectPage();

		if (!isset($_POST["pseudo"]) || !isset($_POST["password"])) die("false");
		$pseudo   = getPost("pseudo");
		$password = getPost("password");

		$player = $this->DB->findFirst(array(
			"table"      => "players",
			"conditions" => array(
				"playername" => $pseudo,
				"password"   => sha1($password)
				// En cas problème, décommenter la ligne en dessous, 
				// et ajouter une virgule à la fin de ligne du dessus.
				// "grade_id"   => 1
			)
		));

		if ($player == null || empty($player)) die("false");
		else {
			unset($player->password);
			$_SESSION["user"] = $player;

			die("true");
		}
	}

}
?>