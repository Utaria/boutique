<?php
require_once SRC . DS . 'core' . DS . 'Paypal.php';

class BoutiqueController extends Controller {

	private $PAYMENT_MEANS = array("paypal", "cb", "paysafecard", "youpass");
	private $COINS_TABLE   = "shop_coins_products";
	

	public function choix_moyen_paiement() {
		privatePage();
	}
 
	public function choix_produit($mean) {
		privatePage();
		if (!in_array($mean, $this->PAYMENT_MEANS)) redirect("/boutique/choix-moyen-paiement");

		$products = $this->DB->find(array(
			"table"      => $this->COINS_TABLE,
			"fields"     => array("id", "price", "coins", "payment_mean"),
			"conditions" => array(
				"payment_mean" => $mean
			)
		));

		$this->set($products);
	}

	public function confirmer_choix_produit($slug) {
		privatePage();
		if(empty($slug) || strpos($slug, "-") === false) redirect("/boutique/choix-moyen-paiement");

		$parts       = preg_split("/-/", $slug);
		$product_id  = $parts[0];
		$paymentLink = null;

		if(empty($product_id) || is_nan($product_id)) redirect("/boutique/choix-moyen-paiement");
		
		$product = $this->DB->findFirst(array(
			"table"      => $this->COINS_TABLE,
			"conditions" => array(
				"id" => $product_id
			)
		));

		// Par sécurité, avant la transaction, on supprime le cache
		unset($_SESSION["productInfo"]);

		if(empty($product)) redirect("/boutique/choix-moyen-paiement");

		// Génération du lien de paiement en fonction du moyen de paiement
		switch($product->payment_mean) {
			case "paypal":
			$paypal       = $this->getPaypalInstance();
			$product_info = array(
					"price"       => $product->price,
					"name"        => $product->coins . " coins sur Utaria",
					"description" => $product->coins . " coins utilisables en jeu sur mc.utaria.fr",
					"amount"      => 1
				);

				$paymentLink = $paypal->getProductCommitLink(array(
					"product" => $product_info,
					"urls"    => array(
						"success" => "https://boutique.utaria.fr/boutique/execution-paiement",
						"cancel"  => "https://boutique.utaria.fr/boutique/annulation-paiement"
					)
				));

				$product_info["id"]    = $product->id;
				$product_info["coins"] = $product->coins;

				$_SESSION["productInfo"] = $product_info;
				break;
			case "cb":
			$paypal       = $this->getPaypalInstance();
			$product_info = array(
					"price"       => $product->price,
					"name"        => $product->coins . " coins sur Utaria",
					"description" => $product->coins . " coins utilisables en jeu sur mc.utaria.fr",
					"amount"      => 1
				);

				$paymentLink = $paypal->getProductCommitLink(array(
					"product" => $product_info,
					"urls"    => array(
						"success" => "https://boutique.utaria.fr/boutique/execution-paiement",
						"cancel"  => "https://boutique.utaria.fr/boutique/annulation-paiement"
					),
					"cb"      => true
				));

				$product_info["id"]    = $product->id;
				$product_info["coins"] = $product->coins;

				$_SESSION["productInfo"] = $product_info;
				break;
			case "youpass":
				// Liens générés depuis le site de YouPass.com
				switch ($product->coins) {
					case 100:
						$paymentLink = "https://ws.youpass.com/box/xnd1vZmLbD/8fNrQyPmRa";
						break;
					case 380:
						$paymentLink = "https://ws.youpass.com/box/xnd1vZmLbD/7sfZIr6FpG";
						break;
					case 700:
						$paymentLink = "https://ws.youpass.com/box/xnd1vZmLbD/4Rty4sV6ej";
						break;
					case 1500:
						$paymentLink = "https://ws.youpass.com/box/xnd1vZmLbD/6FoSjETYJY";
						break;
				}

				// Création de la session conctenant l'info du produit
				$_SESSION["productInfo"] = (array) $product;
				break;
		}

		// Récupération des avantages après l'achat
		$finalCoins  = getUser()->coins + $product->coins;
		$products    = $this->DB->req("SELECT * FROM survie_products WHERE price <= $finalCoins");
		$affProducts = array();

		foreach ($products as $v)
			if (empty($affProducts[$v->type]) || $affProducts[$v->type]->price < $v->price)
				$affProducts[$v->type] = $v;


		$this->set((object) array(
			"product"     => $product,
			"payment"     => (object) array(
				"link" => $paymentLink
			),
			"advProducts" => $affProducts
		));
	}


	public function execution_paiement() {
		privatePage();
		if(!isset($_GET["token"]) || !isset($_GET["PayerID"]))
			redirect("/boutique/annulation-paiement?reason=erreur de l'API Paypal.");

		$paypal = $this->getPaypalInstance();
		$user   = getUser();

		$details = $paypal->getCheckoutDetails($_GET["token"]);
		if(!$details) redirect("/boutique/annulation-paiement");

		// On regarde si le paiement n'a pas déjà été completé
		if($details["CHECKOUTSTATUS"] == "PaymentActionCompleted")
			redirect("/boutique/annulation-paiement?reason=le paiement a déjà été complété.");

		// On regarde si le produit est bien en session
		if(empty($_SESSION["productInfo"]))
			redirect("/boutique/annulation-paiement?reason=vous n'avez acheté aucun produit.");

		// On met à jour le prix pour améliorer la sécurité de la transaction
		// (Cela permet d'éviter les fraudes via la session)
		$_SESSION["productInfo"]->price = $details["PAYMENTINFO_0_AMT"];

		// On effectue le prélèvement
		$payment = $paypal->doCheckout(array(
			"token"   => $_GET["token"],
			"payerId" => $_GET["PayerID"],
			"product" => $_SESSION["productInfo"]
		));

		// On regarde si la paiement a bien été effectué
		if(!$payment)
			redirect("/boutique/annulation-paiement?reason=le paiement a été refusé.");
		if($payment["ACK"] != "Success" || $payment["PAYMENTINFO_0_ACK"] != "Success")
			redirect("/boutique/annulation-paiement?reason=le paiement a été refusé.");

		// On enregistre la paiement et on ajoute les coins au joueur
		$product = (object) $_SESSION["productInfo"];
		
		$this->DB->save(array(
			"table"  => "shop_log",
			"fields" => array(
				"payer_id"              => $_GET["PayerID"],
				"transaction_id"        => $payment["PAYMENTINFO_0_TRANSACTIONID"],
				"ordertime"             => date("Y-m-d H:i:s", strtotime($payment["PAYMENTINFO_0_ORDERTIME"])),
				"player_id"             => $user->id,
				"shop_coins_product_id" => $product->id
			)
		));

		// On ajoute les coins au joueur
		$this->DB->save(array(
			"table"      => "players",
			"fields"     => array("coins" => $user->coins + $product->coins),
			"where"      => "id",
			"wherevalue" => $user->id
		));

		// On mets à jour la session
		$_SESSION["user"]->coins += $product->coins;

		// On renvoit l'utilisateur sur la page de succès
		redirect("/boutique/transaction-terminee");
	}

	public function execution_paiement_youpass() {
		privatePage();

		// On insère le script YouPass pour éviter que quelqu'un non autorisée se connecte.
		$GLOBALS["HEAD_SCRIPT"] = '<noscript><meta http-equiv="Refresh" content="0;URL=https://ws.youpass.com/error"></noscript><script language="Javascript" src="https://ws.youpass.com/access_check/xnd1vZmLbD/8fNrQyPmRa"></script>';

		$user = getUser();

		// On regarde si le produit est bien en session
		if(empty($_SESSION["productInfo"]))
			redirect("/boutique/annulation-paiement?reason=vous n'avez acheté aucun produit.");

		// On va de nouveau chercher le produit en BDD pour éviter au maximum
		// une tentative de modification de la session.
		$productId = $_SESSION["productInfo"]["id"];
		$product   = $this->DB->findFirst(array(
			"table"      => "shop_coins_products",
			"conditions" => array("id" => $productId)
		));

		// On regarde si le produit existe bien
		if(empty($product))
			redirect("/boutique/annulation-paiement?reason=Le produit que vous acheté n'existe pas.");

		
		$this->DB->save(array(
			"table"  => "shop_log",
			"fields" => array(
				"payer_id"              => "", // Pas d'ID de payeur sur YouPass
				"transaction_id"        => "", // Pas d'ID de transaction sur YouPass
				"ordertime"             => date("Y-m-d H:i:s", time()),
				"player_id"             => $user->id,
				"shop_coins_product_id" => $product->id
			)
		));

		// On ajoute les coins au joueur
		$this->DB->save(array(
			"table"      => "players",
			"fields"     => array("coins" => $user->coins + $product->coins),
			"where"      => "id",
			"wherevalue" => $user->id
		));

		// On mets à jour la session
		$_SESSION["user"]->coins += $product->coins;

		// On renvoit l'utilisateur sur la page de succès
		redirect("/boutique/transaction-terminee");
	}

	public function annulation_paiement() {
		privatePage();

		// On supprime le cache de la session par sécurité
		unset($_SESSION["productInfo"]);
	}

	public function transaction_terminee() {
		privatePage();
		if(empty($_SESSION["productInfo"])) redirect("/boutique/choix-moyen-paiement");

		// On supprime le cache de la session par sécurité
		unset($_SESSION["productInfo"]);
	}



	public function cancel() {
		privatePage();

		unset($_SESSION["user"]);
		privatePage();
	}

	private function getPaypalInstance()
	{
		return new Paypal(
			$this->config['paypal']['user'],
			$this->config['paypal']['pass'],
			$this->config['paypal']['signature'],
			$this->config['paypal']['sandbox']
		);
	}

}
?>