<?php

use App\Models\PaymentGatewayCredentials;
use Illuminate\Database\Migrations\Migration;

class ChangeDefaultValueOfPaypalAndStripeOnPaymentGatewayCredentialsTable extends Migration
{

    public function up()
    {
        $payment = PaymentGatewayCredentials::first();

        /* Set paypal status deactive if client id and secret key is null */
        if($payment->paypal_status == 'active') {

            if($payment->paypal_mode == 'sandbox' && is_null($payment->sandbox_paypal_client_id) && is_null($payment->sandbox_paypal_secret)) {
                $payment->paypal_status = 'deactive';
            }

            if($payment->paypal_mode == 'live' && is_null($payment->paypal_client_id) && is_null($payment->paypal_secret)) {
                $payment->paypal_status = 'deactive';
            }

        }

        /* Set stripe status deactive if client id and secret is null */
        if($payment->stripe_status == 'active' && is_null($payment->stripe_client_id) && is_null($payment->stripe_secret)) {
            $payment->stripe_status = 'deactive';
        }

        /* Set razorpay status deactive if client id and secret is null */
        if($payment->razorpay_status == 'active' && is_null($payment->stripe_client_id) && is_null($payment->stripe_secret)) {
            $payment->razorpay_status = 'inactive';
        }

        $payment->save();

    }

}
