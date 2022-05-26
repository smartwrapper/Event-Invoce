<?php 

/**
 * 
 * PayPal Express checkout integration service
 * sources-
 * http://paypal.github.io/PayPal-PHP-SDK/sample/doc/payments/CreatePaymentUsingPayPal.html
 * http://paypal.github.io/PayPal-PHP-SDK/sample/doc/payments/ExecutePayment.html
 * https://medium.com/justlaravel/how-to-integrate-paypal-payment-gateway-in-laravel-695063599449
 * 
*/

namespace Classiebit\Eventmie\Services;


/**
 * For fetching PayPal $_GET callback requests 
*/
use Illuminate\Http\Request;


use Omnipay\Omnipay;

class PaypalExpress 
{
    
    private $_callback_url;
    private $gateway;

    /**
     * PaypalExpress constructor.
     *
     */
    public function __construct($settings = [])
    {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId($settings['paypal_client_id']);
        $this->gateway->setSecret($settings['paypal_secret']);

        $this->gateway->setTestMode(true); 
        if($settings['paypal_mode'])
            $this->gateway->setTestMode(false); 
        
        // set callback url
        $this->_callback_url = 'bookings/paypal/callback';
    }

    // 1. Validate and create new order request for single item only
    public function create_order($order = [], $currency = 'USD')
    {
        // required params
        if( empty($order['product_title']) ||
            empty($order['item_sku']) ||
            empty($order['price']) ||
            empty($order['price_title']) ||
            empty($order['price_tagline']) ||
            empty($order['order_number'])
        )
            return false;

        try 
        {
            $response = $this->gateway->purchase(array(
                'amount'    => $order['price'],
                'currency'  => $currency,
                'description'   => $order['product_title'].' ('.$order['price_title'].')',
                'returnUrl' => eventmie_url($this->_callback_url),
                'cancelUrl' => eventmie_url($this->_callback_url),
            ))->send();
            
            if ($response->isRedirect()) 
            {
                // url: redirect to paypal for checkout  
                return ['url' => $response->getRedirectUrl(), 'status' => true];
            } 
            else 
            {
                // not successful
                return ['error' => $response->getMessage(), 'status' => false];
            }
        } 
        catch(\Throwable $th) 
        {
            return ['error' => $th->getMessage(), 'status' => false];
        }
    }

    // 2. On return from gateway check if payment fail or success
    public function callback(Request $request)
    {
        // if in return not get PayerID and token, then payment cancelled
        if (!$request->has('PayerID') || !$request->has('token') || !$request->has('paymentId') ) 
        {
            return [
                'error'         => 'Payment cancelled!', 
                'status'        => false
            ];
        }

        // response data from Paypal
        $payerId    = $request->input('PayerID');
        $paymentId  = $request->input('paymentId');
            
         // Once the transaction has been approved, we need to complete it
        $transaction =  $this->gateway->completePurchase(array(
                            'payer_id'             => $payerId,
                            'transactionReference' => $paymentId,
                        ));
        $response = $transaction->send();

        // final check if return approved: payment success
        if ($response->isSuccessful()) 
        {
            // The customer has successfully paid.
            $result = $response->getData();

            // set success data
            $success = [
                'transaction_id'    => $paymentId,
                'payer_reference'   => $payerId,
                'message'           => $result['state'],
                'status'            => true,
            ];
            
            return $success;
        }

        // else payment failed
        return [
            // only for reference
            'paymentId' => $paymentId,
            'error'     => $response->getMessage(), 
            'status'    => false
        ];
    }    

    
    
}
