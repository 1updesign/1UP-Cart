<?php
/*
	Template Name: Confirm
*/
?>
<?php
	/*==================================================================
	 PayPal Express Checkout Call
	 ===================================================================
	*/
require_once ("./wp-content/plugins/cart/assets/php/paypalfunctions.php");

 $PaymentOption = "PayPal";

 if ( $PaymentOption == "PayPal" )
 {
	/*
	'------------------------------------
	' The paymentAmount is the total value of 
	' the shopping cart, that was set 
	' earlier in a session variable 
	' by the shopping cart page
	'------------------------------------
	*/
	
 	$finalPaymentAmount =  $_SESSION["Payment_Amount"];
		
	/*
	'------------------------------------
	' Calls the DoExpressCheckoutPayment API call
	'
	' The ConfirmPayment function is defined in the file PayPalFunctions.jsp,
	' that is included at the top of this file.
	'-------------------------------------------------
	*/

 	$resArray = ConfirmPayment ( $finalPaymentAmount );
 	$ack = strtoupper($resArray["ACK"]);
 	if( $ack == "SUCCESS" || $ack == "SUCCESSWITHWARNING" )
 	{
		/*
		'********************************************************************************************************************
		'
		' THE PARTNER SHOULD SAVE THE KEY TRANSACTION RELATED INFORMATION LIKE 
		'                    transactionId & orderTime 
		'  IN THEIR OWN  DATABASE
		' AND THE REST OF THE INFORMATION CAN BE USED TO UNDERSTAND THE STATUS OF THE PAYMENT 
		'
		'********************************************************************************************************************
		*/

 		$transactionId		= $resArray["PAYMENTINFO_0_TRANSACTIONID"]; // ' Unique transaction ID of the payment. Note:  If the PaymentAction of the request was Authorization or Order, this value is your AuthorizationID for use with the Authorization & Capture APIs. 
 		$transactionType 	= $resArray["PAYMENTINFO_0_TRANSACTIONTYPE"]; //' The type of transaction Possible values: l  cart l  express-checkout 
 		$paymentType		= $resArray["PAYMENTINFO_0_PAYMENTTYPE"];  //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
 		$orderTime 			= $resArray["PAYMENTINFO_0_ORDERTIME"];  //' Time/date stamp of payment
 		$amt				= $resArray["PAYMENTINFO_0_AMT"];  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
 		$currencyCode		= $resArray["PAYMENTINFO_0_CURRENCYCODE"];  //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
 		$feeAmt				= $resArray["PAYMENTINFO_0_FEEAMT"];  //' PayPal fee amount charged for the transaction
 		$settleAmt			= $resArray["SETTLEAMT"];  //' Amount deposited in your PayPal account after a currency conversion.
 		$taxAmt				= $resArray["TAXAMT"];  //' Tax charged on the transaction.
 		$exchangeRate		= $resArray["EXCHANGERATE"];  //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.
		
		/*
		' Status of the payment: 
				'Completed: The payment has been completed, and the funds have been added successfully to your account balance.
				'Pending: The payment is pending. See the PendingReason element for more information. 
		*/
		
 		$paymentStatus	= $resArray["PAYMENTINFO_0_PAYMENTSTATUS"]; 

		/*
		'The reason the payment is pending:
		'  none: No pending reason 
		'  address: The payment is pending because your customer did not include a confirmed shipping address and your Payment Receiving Preferences is set such that you want to manually accept or deny each of these payments. To change your preference, go to the Preferences section of your Profile. 
		'  echeck: The payment is pending because it was made by an eCheck that has not yet cleared. 
		'  intl: The payment is pending because you hold a non-U.S. account and do not have a withdrawal mechanism. You must manually accept or deny this payment from your Account Overview. 		
		'  multi-currency: You do not have a balance in the currency sent, and you do not have your Payment Receiving Preferences set to automatically convert and accept this payment. You must manually accept or deny this payment. 
		'  verify: The payment is pending because you are not yet verified. You must verify your account before you can accept this payment. 
		'  other: The payment is pending for a reason other than those listed above. For more information, contact PayPal customer service. 
		*/
 		
 		$pendingReason	= $resArray["PAYMENTINFO_0_PENDINGREASON"];  
 
 		/*
 		'The reason for a reversal if TransactionType is reversal:
 		'  none: No reason code 
 		'  chargeback: A reversal has occurred on this transaction due to a chargeback by your customer. 
 		'  guarantee: A reversal has occurred on this transaction due to your customer triggering a money-back guarantee. 
 		'  buyer-complaint: A reversal has occurred on this transaction due to a complaint about the transaction from your customer. 
 		'  refund: A reversal has occurred on this transaction because you have given the customer a refund. 
 		'  other: A reversal has occurred on this transaction due to a reason not listed above. 
 		*/
 		
 		$reasonCode		= $resArray["PAYMENTINFO_0_REASONCODE"];   
 	}
 	else  
 	{
 		//Display a user friendly Error on the page using any of the following error information returned by PayPal
 		$ErrorCode = urldecode($resArray["L_ERRORCODE0"]);
 		$ErrorShortMsg = urldecode($resArray["L_SHORTMESSAGE0"]);
 		$ErrorLongMsg = urldecode($resArray["L_LONGMESSAGE0"]);
 		$ErrorSeverityCode = urldecode($resArray["L_SEVERITYCODE0"]);
 	}
 }		
		
?>

<?php
########UPDATE THE ITINAREY################

$transactionId;		
$paymentType; //' Indicates whether the payment is instant or delayed. Possible values: l  none l  echeck l  instant 
$orderTime;  //' Time/date stamp of payment
$amt;	  //' The final amount charged, including any shipping and taxes from your Merchant Profile.
$currencyCode; //' A three-character currency code for one of the currencies listed in PayPay-Supported Transactional Currencies. Default: USD. 
$feeAmt;	  //' PayPal fee amount charged for the transaction
$settleAmt;	 //' Amount deposited in your PayPal account after a currency conversion.
$exchangeRate; //' Exchange rate if a currency conversion occurred. Relevant only if your are billing in their non-primary currency. If the customer chooses to pay with a currency other than the non-primary currency, the conversion occurs in the customer’s account.

########SEND CONFIRMATION EMAIL TO CLIENT#########

$transaction_id = "#393937847483";
$client_name = "Pete Nickless";

//Name of Client for email body
$business = "Friendly Fire Music";

$to      = "pete@1updesign.org";
$subject = 'URGENT enquiry from the '.$website.' website';
$headers = 'From: pete@1updesign.org' . "\r\n" .
    'Reply-To: webmaster@example.com';
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

$message = "
	<table width='100%' align='center'>
		<table width='100%' align='center'>
			<thead>
				<img src='http://asliceofthepie.co.uk/wp-content/themes/friendlyfire/images/friendly-fire-logo200px.png'>
				<td align='left'>
					Your transaction with ".$business." is complete!
				</td>
				<td align='right'>
					".date(DATE_ATOM, mktime(0, 0, 0, 7, 1, 2000))."<br/>
					".$transaction_id."
				</td>
			</thead>
		</table>
		<table>
			<tbody>
				<tr>
					<td>
						Hello ".$client_name." <br/>
						Your transation with ".$business." is complete payment has been processed and your order is placed.  
					</td>
				</tr>
			</tbody>
		</table>
		<table width='100%' align='center'>
			<tbody>
				<tr>
					<td style='font-weight:bold; border:1px solid #ccc; border-right:none; border-left:none;'>Description</td>
					<td align='right' style='font-weight:bold; border:1px solid #ccc; border-right:none; border-left:none;'>Unit Price</td>
					<td align='right' style='font-weight:bold; border:1px solid #ccc; border-right:none; border-left:none;'>Quantity</td>
					<td align='right' style='font-weight:bold; border:1px solid #ccc; border-right:none; border-left:none;'>Amount</td>
				</tr>
				<tr>
					<td>Item 1</td>
					<td align='right'>£2.00</td>
					<td align='right'>1</td>
					<td align='right'>£2.00</td>
				</tr>
			</tbody>
		</table>
		<hr/>
		<table align='right'>
		<tbody>
			<tr>
				<td align='right' style='font-weight:bold'>
					Total:
				</td>
				<td align='right'>
					£9.12
				</td>	
			</tr>	
		</tbody>
	</table>
	<table>
		<tbody>
			<tr>
				<td style='font-weight:bold'>Shipping Address</td>
			</tr>
			<tr>
				<td>Line 1<br/>
					Line 2<br/>
					Line 3<br/>
					Line 4<br/>
				</td>
			</tr>
		</tbody>
</table>";

//echo $to.$subject.$message.$headers;

mail('petenickless@gmail.com', $subject, $message, $headers);

########ADD BOUGHT ITEMS TO ARRAY LINKED TO USER#######

########SEND ORDER CREATED EMAIL TO VENDOR########

?>
<!-- Paypal Email not recieving payment info - https://cms.paypal.com/uk/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_nvp_r_DoExpressCheckoutPayment  -->

<?php get_header(); ?>

<?php get_sidebar(); ?>
<div class="content" id="payment_complete">
	<?php if( $ack == "SUCCESS"): ?>
		<h1 id="title">PAYMENT COMPLETE</h1>
		<p>Thanks you, your payment has been received, your transaction ID is <?php echo $transactionId; ?>.  Please keep this ID safe and quote it in any correspondence with us.  </p>
		<p>Any products you've purchased will be posted as soon as possible.  We'll contact you if there's a problem with the transation.  </p>
		<p><strong>Your digital downloads are available in your profile, please click here to download your purchases now.  </strong></p>
	<?php endif; ?>
	<?php if($ack == "SUCCESSWITHWARNING" ): ?>
		<h1 id="title">PAYMENT PENDING</h1>
		<p>Thank you.  Your payment is pending for this transaction.  We'll contact you as soon as it clears.  There may be a number of reasons why your payment is pending. </p>
		<p>Your transaction ID is <?php echo $transactionId; ?>.  Please keep this ID safe and quote it in any correspondence with us.</p>
	<?php endif; ?>
	<?php if($ack == "FAILURE"): ?>
		<h1 id="title">PAYMENT REJECTED</h1>
		<p>We're very sorry, your payment has been rejected because <?php echo $ErrorLongMsg; ?>.  No funds have been transferred from your account.  <a href="./cart">To try again please click here</a></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
