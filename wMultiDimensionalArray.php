
/* Add user to CRM database upon order completion*/
add_action( 'woocommerce_payment_complete', 'so_payment_complete' );
function so_payment_complete(){

$ch = curl_init("https://www.prospernutrition.com/wp-json/wc/v2/orders");
$headers[]  = 'Content-Type: application/json';

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
curl_setopt($ch, CURLOPT_USERPWD, "api key here");
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); 
$response1 = curl_exec($ch);
$result = json_decode($response1, true);

//print the received pay load with pre formated text
//echo '<pre>';
////print_r($result);

//close the channel 
curl_close($ch);

//Start new 
$curl = curl_init('crm api URL');

//Loop through the items in the received payload

foreach($result as $key){
$email = $key['billing']['email'];
$phone = $key['billing']['phone'];
$firstName = $key['billing']['first_name'];
$lastName = $key['billing']['last_name'];
$address = $key['billing']['address_1'];
$city = $key['billing']['city'];
$state = $key['billing']['state'];
$customerType = "Customer";
$tags = [];
foreach($key['line_items'] as $lineitems){
     array_push($tags, $lineitems['name']);
}

//Put those variables into an array to post to CRM

$arrayForRocketfuel = array('email' => $email, 'phone' => $phone, 'firstName' => $firstName, 'lastName' => $lastName, 'address1' => $address, 'city' => $city, 'state' => $state, 'type' => $customerType, 'tags' => $tags);

//set up the new connection then encode the array so that rocketfuel detects it as json

curl_setopt_array($curl, array(
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS => json_encode($arrayForRocketfuel),
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json',
    'Authorization: Bearer ef8dccc0-0099-4549-8233-1167ac989a89'
  ),
));
$response2 = curl_exec($curl);
//echo '<pre>';
//print_r($response2);
	}

curl_close($curl);
     
}
