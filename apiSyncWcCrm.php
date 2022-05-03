
add_action( 'woocommerce_payment_complete', 'so_payment_complete' );
function so_payment_complete(){

/*Connect to wooCommerce API for order Data*/
	$ch = curl_init("xxxx");
	$headers[]  = 'Content-Type: application/json';

	curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
  curl_setopt($ch, CURLOPT_USERPWD, "xxxx");
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
	$response = curl_exec($ch);
	$result = json_decode($response, true);

/*you can use this commented out code to view the payload received from the wooCommerce API
curl_close($ch);
print the received pay load with pre formated text
echo '<pre>';
print_r($result);
exit;*/

//Loop through the results of the result
	foreach($result as $key){
		$email = $key['billing']['email'];
		$phone = $key['billing']['phone'];
		$firstName = $key['billing']['first_name'];
		$lastName = $key['billing']['last_name'];
//Put those results into an array to post to crm database
		$arrayForCRM = array('email' => $email, 'phone' => $phone, 'firstName' => $firstName, 'lastName' => $lastName);
//open up the client url to post to crm and json encode the array in the post fields
		$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://rest.gohighlevel.com/v1/contacts/',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($arrayForCrm,
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer xxxx'
  ),
));		
$response = curl_exec($curl);
curl_close($curl);
echo $response;
	}
}
