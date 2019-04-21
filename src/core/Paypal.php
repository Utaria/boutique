<?php  
class Paypal{

	private $username;
	private $pwd;
	private $signature;
	private $sandbox;	
	private $endpoint;


	public $errors = array();


	public function __construct($username = false, $password = false, $signature = false, $sandbox = false){
		$this->username  = $username;
		$this->password  = $password;
		$this->signature = $signature;

		$this->sandbox  = $sandbox;
		$this->endpoint = "https://api-3t." . ($sandbox ? "sandbox." : "") . "paypal.com/nvp";
	}


	public function getProductCommitLink($params) {
		$price    = (!empty($params["product"]["price"]))       ? $params["product"]["price"]       : 0.00;
		$qty      = (!empty($params["product"]["amount"]))      ? $params["product"]["amount"]      : 1;
		$name     = (!empty($params["product"]["name"]))        ? $params["product"]["name"]        : "";
		$desc     = (!empty($params["product"]["description"])) ? $params["product"]["description"] : "";
		$currency = (!empty($params["currency"]))               ? $params["currency"]               : "EUR";

		$request = $this->request("SetExpressCheckout", array(
			"RETURNURL" => $params["urls"]["success"],
			"CANCELURL" => $params["urls"]["cancel"],

			"PAYMENTREQUEST_0_AMT"          => $price,
			"PAYMENTREQUEST_0_CURRENCYCODE" => $currency,
			"PAYMENTREQUEST_0_SHIPPINGAMT"  => 0.00,
			"PAYMENTREQUEST_0_ITEMAMT"      => $price,

			"L_PAYMENTREQUEST_0_NAME0" => $name,
			"L_PAYMENTREQUEST_0_DESC0" => $desc,
			"L_PAYMENTREQUEST_0_AMT0"  => $price,
			"L_PAYMENTREQUEST_0_QTY0"  => $qty
		));

		// return "https://www." . ($this->sandbox ? "sandbox." : "") . "paypal.com/webscr?cmd=_express-checkout&useraction=commit&token=" . $request["TOKEN"];
		if (isset($params["cb"]) && $params["cb"] === true)
			return "https://www." . ($this->sandbox ? "sandbox." : "") . "paypal.com/webapps/xoonboarding?token=" . $request["TOKEN"];
		else
			return "https://www." . ($this->sandbox ? "sandbox." : "") . "paypal.com/checkoutnow?token=" . $request["TOKEN"];
	}

	public function getCheckoutDetails($token) {
		return $this->request("GetExpressCheckoutDetails", array("TOKEN" => $token));
	}

	public function doCheckout($params) {
		$price    = (!empty($params["product"]["price"]))       ? $params["product"]["price"]       : 0.00;
		$qty      = (!empty($params["product"]["amount"]))      ? $params["product"]["amount"]      : 1;
		$name     = (!empty($params["product"]["name"]))        ? $params["product"]["name"]        : "";
		$desc     = (!empty($params["product"]["description"])) ? $params["product"]["description"] : "";
		$currency = (!empty($params["currency"]))               ? $params["currency"]               : "EUR";

		return $this->request("doExpressCheckoutPayment", array(
			"TOKEN"          => $params["token"],
			"PAYERID"        => $params["payerId"],
			"PAYMENT_ACTION" => "Sale",

			"PAYMENTREQUEST_0_AMT"          => $price,
			"PAYMENTREQUEST_0_CURRENCYCODE" => $currency,

			"L_PAYMENTREQUEST_0_NAME0" => $name,
			"L_PAYMENTREQUEST_0_DESC0" => $desc,
			"L_PAYMENTREQUEST_0_AMT0"  => $price,
			"L_PAYMENTREQUEST_0_QTY0"  => $qty
		));
	}


	private function request($method, $params){
		$params = array_merge($params, array(
			"METHOD"    => $method,
			"VERSION"   => "74.0",
			"USER"      => $this->username,
			"SIGNATURE" => $this->signature,
			"PWD"       => $this->password
		));
		$params = http_build_query($params);

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $this->endpoint,
			CURLOPT_POST => 1,
			CURLOPT_POSTFIELDS => $params,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_VERBOSE => true
		));

		$res = curl_exec($curl);
		$resArray = array();

		parse_str($res, $resArray);

		if(curl_errno($curl)){
			$this->errors = curl_error($curl);
			curl_close($curl);
			return false;
		}else{
			if($resArray["ACK"] == "Success"){
				curl_close($curl);
				return $resArray;
			}else{
				$this->errors = $resArray;
				curl_close($curl);
				return false;
			}
		}
	}

}
