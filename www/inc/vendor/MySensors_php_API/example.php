<?
require(dirname(__FILE__).'/src/mysensors.class.php');

// instance Class ---------------------------------------------
//Ethernet Gateway
$mys=new MySensorSendEthernet('10.1.7.40');
// or
//Serial Gateway
//$mys=new MySensorSendSerial('COM1');


// fetching the Gateway Version -------------------------------
echo "Gateway version is : ";
echo $mys->internal(0, 0, 'I_VERSION',false,true);
echo " <br>\n";
DisplayMessages(1);


echo "<hr>\n\n";


// Sending a message to a node -------------------------------

$node_id	='199';
$child_id	='0';
$type		='V_STATUS';
$payload	=1;

echo "Sending $type=$payload to node $node_id , child $child_id<br>\n";
$mys->set($node_id, $child_id,$type,$payload);
DisplayMessages(0);



//-----------------------------------------------
function DisplayMessages($with_answer=0){
	global $mys;
	echo " - Message sent : ".$mys->GetRawMessage() ." <br>\n";
	if($with_answer){
		echo " - Answer received : ".$mys->GetRawAnswer()." <br>\n";;
	}
	echo "\n";
}

?>