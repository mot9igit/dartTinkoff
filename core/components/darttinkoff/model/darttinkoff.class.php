<?php

class dartTinkoff
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;

		$corePath = $this->modx->getOption('darttinkoff_core_path', $config, $this->modx->getOption('core_path') . 'components/darttinkoff/');
		$assetsUrl = $this->modx->getOption('darttinkoff_assets_url', $config, $this->modx->getOption('assets_url') . 'components/darttinkoff/');
		$assetsPath = $this->modx->getOption('darttinkoff_assets_path', $config, $this->modx->getOption('base_path') . 'assets/components/darttinkoff/');

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',
			'version' => '0.0.1',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('darttinkoff', $this->config['modelPath']);
        $this->modx->lexicon->load('darttinkoff:default');
    }

    public function loadCustomOrderJsCss(){
		$this->modx->controller->addCss($this->config['cssUrl'] . 'mgr/darttinkoff.css?v='.$this->config['version']);
		$this->modx->controller->addJavascript($this->config['jsUrl'] . 'mgr/darttinkoff.js?v='.$this->config['version']);
		$this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/misc/utils.js?v='.$this->config['version']);
		$this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/misc/combo.js?v='.$this->config['version']);
		$this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/widgets/payments.grid.js');
		$this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/widgets/payments.windows.js');
		$this->modx->controller->addLastJavascript($this->config['jsUrl'] . 'mgr/widgets/order.tab.js');

		$this->modx->controller->addHtml('<script>
            Ext.onReady(function() {
                dartTinkoff.config = ' . json_encode($this->config) . ';
                dartTinkoff.config.connector_url = "' . $this->config['connectorUrl'] . '";
            });
        </script>');

		$this->modx->controller->addLexiconTopic('darttinkoff:default');
	}

    public function getOrderData($order_id){
		if (empty($order_id)) {
			return false;
		}

		if (!$order = $this->modx->getObject('msOrder', $order_id)) {
			return $this->modx->lexicon('ms2_err_order_nf');
		}
		if ($order->get('status') != 5) {
			return false;
		}
		$order_data = array();
		$order_data['order'] = $order->toArray();
		$products = $order->getMany("Products");
		foreach($products as $product){
			$order_data['product'] = $product->toArray();
			$resource = $this->modx->getObject("modResource", $product->get("product_id"));
			$class = $resource->getTVValue("vnutrclasses");
			$zalog = $this->modx->getObject("modResource", $class);
			$order_data['product']['zalog_price'] = $zalog->getTVValue("zalog_price");
		}
		$user = $order->getOne('User');
		$order_data['user'] = $user->toArray();
		if ($user) {
			$profile = $user->getOne('Profile');
			$order_data['user_profile'] = $profile->toArray();
		}

		$order_data['address'] = $order->getOne('Address')->toArray();
		$order_data['delivery'] = $order->getOne('Delivery')->toArray();
		$order_data['payment'] = $order->getOne('Payment')->toArray();

		return $order_data;
	}

    public function init($order_id){
    	$method = 'Init';
    	$order_data = $this->getOrderData($order_id);
		$data = array(
			"Amount" => $order_data['product']['zalog_price'] * 100,
			"OrderId" => $order_data['order']['id']
		);
		$data['PayType'] = $this->modx->getOption('darttinkoff_pay_type');
    	if($this->modx->getOption('darttinkoff_terminal_key')){
			$data['TerminalKey'] = $this->modx->getOption('darttinkoff_terminal_key');
			$data['RedirectDueDate'] = $this->getRedirectDueDate();
			$data['NotificationURL'] = $this->modx->getOption("site_url").'assets/components/darttinkoff/notification.php';
			if($this->modx->getOption('darttinkoff_success_page_id')) {
				$data['SuccessURL'] = $this->modx->makeUrl($this->modx->getOption('darttinkoff_success_page_id'), '', '', 'full');
			}
			if($this->modx->getOption('darttinkoff_fail_page_id')) {
				$data['FailURL'] = $this->modx->makeUrl($this->modx->getOption('darttinkoff_fail_page_id'), '', '', 'full');
			}
			if($this->modx->getOption('darttinkoff_process_receipt')){
				$data['Receipt'] = $this->getReceipt($order_id);
			}
			$data = $this->request($method, $data);
			if($data['Status'] == 'NEW'){
				$object = $this->setPayment($data);
				return $object;
			}else{
				return $data;
			}
		}else{
			$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Не указан ID терминала!');
		}
	}

	public function receive($order_id, $payment_id, $params = [])
	{
		$payment = $this->modx->getObject('dartTinkoffPayment', array('payment_id' => $payment_id));
		if($payment){
			if (!isset($params['TerminalKey'], $params['OrderId'], $params['Token'])
			) {
				return $this->paymentError('Wrong payment request', $params);
			}
			if ($this->getToken($params) != $params['Token']) {
				return $this->paymentError('Wrong Token', $params);
			}
			if (intval($payment->get('amount') * 100) != $params['Amount']) {
				return $this->paymentError('Wrong Amount', $params);
			}
			if($params['Success']){
				$payment->set('status', $params['Status']);
				if($params['CardId']){
					$payment->set('card_id', $params['CardId']);
				}
				if($params['Pan']){
					$payment->set('pan', $params['Pan']);
				}
				if($params['ExpDate']){
					$payment->set('exp_date', $params['ExpDate']);
				}
				$payment->set('updatedon', time());
				$payment->save();
			}else{
				return $this->paymentError('Error', $params);
			}
		}else{
			return $this->paymentError('Payment not found', $params);
		}
		return true;
	}

	public function buildQuery($method, $payment_id){
		$payment = $this->modx->getObject('dartTinkoffPayment', array('payment_id' => $payment_id));
		if($payment){
			if($this->modx->getOption('darttinkoff_terminal_key')) {
				$data['TerminalKey'] = $this->modx->getOption('darttinkoff_terminal_key');
				$data['PaymentId'] = $payment->get('payment_id');
				$data['Token'] = $this->getToken($data);
				$response = $this->request($method, $data);
				return $response;
			}
		}else{
			$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Платеж не найден!');
		}
	}

	public function getState($payment_id){
    	$method = 'GetState';
		$response = $this->buildQuery($method, $payment_id);
		return $response;
	}

	public function confirm($payment_id){
		$method = 'Confirm';
		$response = $this->buildQuery($method, $payment_id);
		return $response;
	}

	public function cancel($payment_id){
		$method = 'Cancel';
		$response = $this->buildQuery($method, $payment_id);
		return $response;
	}

	protected function getToken($params = [])
	{
		foreach (['Token', 'DATA', 'Receipt'] as $k) {
			unset($params[$k]);
		}

		foreach ($params as $k => $v) {
			switch ($k) {
				case 'Success':
					$params[$k] = filter_var($v, FILTER_VALIDATE_BOOLEAN) ? "true" : "false";
					break;
				default:
					break;
			}
		}
		$params['Password'] = $this->modx->getOption('darttinkoff_secret_key');
		ksort($params);
		$token = implode('', array_values($params));
		$token = hash('sha256', $token);

		return $token;
	}

	public function setPayment($data){
    	$payment = $this->modx->getObject('dartTinkoffPayment', array('payment_id' => $data['PaymentId']));
    	if($payment){
			$this->modx->log(xPDO::LOG_LEVEL_ERROR, 'dartTinkoff: Такой платеж уже существует!');
		}else{
			$payment = $this->modx->newObject('dartTinkoffPayment');
			$payment->set('payment_id', $data['PaymentId']);
			$payment->set('order_id', $data['OrderId']);
			$payment->set('amount', $data['Amount'] * 0.01);
			$payment->set('status', $data['Status']);
			$payment->set('payment_url', $data['PaymentURL']);
			$payment->set('createdon', time());
			$payment->save();
			return $payment->toArray();
		}
	}

	// пока базовый вариант под 1 клиента
	public function getReceipt($order_id){
    	$order = $this->modx->getObject("msOrder", $order_id);
    	if($order) {
			$order_cart_cost = $order->get('cart_cost');
			$order_delivery_cost = $order->get('delivery_cost');

			$products = [];
			/** @var msOrderProduct $product */
			foreach ($order->getMany('Products') as $product) {
				$products[] = $product->toArray();
			}

			if (empty($products)) {
				$products = [
					'name'  => $this->modx->getOption('product_name', null, 'Продукт', true),
					'price' => $order_cart_cost,
					'count' => 1,
				];
			}
			// add delivery
			if (!empty($order_delivery_cost)) {
				$products[] = [
					'name'     => $this->modx->getOption('delivery_name', null, 'Доставка', true),
					'price'    => $order_delivery_cost,
					'count'    => 1,
					'delivery' => 1,
				];
			}

			$amount = 0;
			foreach ($products as $product) {
				$quantity = round($product['count'], 2);
				$price = round($product['price'], 2);
				$amount += $price * $quantity;
			}

			$diff = $amount - $order_cart_cost - $order_delivery_cost;
			if (abs($diff) >= 0.001) {
				$coff = $diff / $amount;
				foreach ($products as $i => $product) {
					$products[$i]['price'] = $product['price'] - $product['price'] * $coff;
				}
			}

			$tax = $this->getPaymentTax();
			$taxation = $this->getPaymentTaxation();
			$format = $this->getPaymentReceiptFormat();

			$items = [];
			foreach ($products as $product) {
				$quantity = (int)$product['count'];
				$price = (int)($product['price'] * 100);
				$amount = (int)($price * $quantity);
				$name = mb_substr($product['name'], 0, 64, 'UTF-8');

				if (!empty($amount)) {
					$item = [
						'Name'     => $name,
						'Price'    => $price,
						'Quantity' => $quantity,
						'Amount'   => $amount,
						'Tax'      => $tax,
					];

					// TODO ФФД 1.05
					if (in_array($format, ['1.05', '1.1'])) {
						if ($payment_mode = $this->getProductValue('payment_mode', $product)) {
							$item['PaymentMethod'] = mb_substr($payment_mode, 0, 64, 'UTF-8');
						}
						if ($payment_subject = $this->getProductValue('payment_subject', $product)) {
							$item['PaymentObject'] = mb_substr($payment_subject, 0, 64, 'UTF-8');
						}
					}

					$items[] = $item;
				}
			}

			$receipt['Items'] = $items;

			if ($profile = $order->getOne('UserProfile')) {
				$email = trim($profile->get('email'));
				$phone = trim($profile->get('phone'));

				$receipt['Email'] = $email;
				if (!empty($phone)) {
					$receipt['Phone'] = $phone;
				}
			}
			$receipt['Taxation'] = $taxation;

			return $receipt;
		}else{
    		return array();
		}
	}

	protected function getPaymentReceiptFormat(array $params = [], array $form = [])
	{
		return $this->getOption('receipt_format', null);
	}

	public function getPaymentTaxation($taxation = 'osn')
	{
		$taxation = $this->getOption('taxation', null, $taxation, true);
		$taxation = mb_strtolower(trim($taxation), 'UTF-8');
		switch ($taxation) {
			case 'usn_income':
			case 'usn_income_outcome':
			case 'envd':
			case 'esn':
			case 'patent':
				break;
			default:
				$taxation = 'osn';
				break;
		}

		return $taxation;
	}

	public function getOption($key, $config = [], $default = null, $skipEmpty = false)
	{
		return $this->modx->getOption("darttinkoff_{$key}", $config, $default, $skipEmpty);
	}

	public function getProductValue($key = '')
	{
		$value = $this->getOption('receipt_' . $key, null);
		return $value;
	}

	public function getRedirectDueDate(){
		if ($paymentReferenceTerm = trim($this->getOption('payment_reference_term', null, false))) {
			$date = time();
			$term = strtolower(trim($paymentReferenceTerm));

			$pattern_term_value = $this->getOption('pattern_term_value', null, "/[^0-9]/");
			$pattern_term_unit = $this->getOption('pattern_term_unit', null, "/[^y|m|d|w|h|i]/");
			$term_value = preg_replace($pattern_term_value, '', $term);
			$term_unit = preg_replace($pattern_term_unit, '', $term);

			if (empty($term_value)) {
				$term_value = 0;
			}

			switch ($term_unit) {
				case 'y':
					$interval = "P{$term_value}Y";
					break;
				case 'm':
					$interval = "P{$term_value}M";
					break;
				case 'w':
					$term_value = 7 * $term_value;
					$interval = "P{$term_value}D";
					break;
				case 'd':
					$interval = "P{$term_value}D";
					break;
				case 'h':
					$interval = "PT{$term_value}H";
					break;
				case 'i':
					$interval = "PT{$term_value}M";
					break;
				default:
					return false;
			}

			if (!is_numeric($date)) {
				$date = strtotime($date);
			}
			$date = new DateTime(date('Y-m-d\TH:i:s\Z', $date));
			if(!$interval){
				$interval = "P1D";
			}
			$interval = new DateInterval($interval);
			$date->add($interval);

			return $date->format('Y-m-d\TH:i:s\Z');
		}
	}

    public function request($method, $data){
    	if($this->modx->getOption('darttinkoff_test')){
    		$url = $this->modx->getOption('darttinkoff_test_url');
		}else{
			$url = $this->modx->getOption('darttinkoff_actual_url');
		}
		if($this->modx->getOption('darttinkoff_log')){
			$this->to_log($data, $url.$method);
		}
		$ch = curl_init($url.$method);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$res = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($this->modx->getOption('darttinkoff_log')){
			$this->to_log($res, $http_code);
		}
		return json_decode($res, true);
	}

	public function to_log($data, $message = '') {
    	$file = $this->modx->getOption('darttinkoff_log_file');
		$this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($data, 1).' -- '.$message, array(
			'target' => 'FILE',
			'options' => array(
				'filename' => $file
			)
		));
	}

	public function paymentError($text, $request = [])
	{
		$this->modx->log(modX::LOG_LEVEL_ERROR, '[miniShop2:dartTinkoff] ' . $text);
		$this->modx->log(modX::LOG_LEVEL_ERROR, var_export($request, 1));

		return true;
	}
}