<?php
include 'config.php';
include 'yeepay/yeepayMPay.php';
$mysql_server_name='localhost'; 
//改成自己的mysql数据库服务器  
$mysql_username='root'; 
//改成自己的mysql数据库用户名  
$mysql_password='huixiumo'; 
//改成自己的mysql数据库密码  
$mysql_database='paymentlib';
 //改成自己的mysql数据库名  
 //连上数据库
$conn=mysql_connect($mysql_server_name,
$mysql_username,$mysql_password,
$mysql_database);
//设置编码格式
mysql_query("set names UTF8");

mysql_select_db($mysql_database,$conn);  
 
//对unity传递的接口进行处理
//get callback
$yeepay = new yeepayMPay($merchantaccount, $merchantPublicKey, $merchantPrivateKey, $yeepayPublicKey);
try {
	$return =$yeepay->callback($_GET['data'],$_GET['encryptkey']);
	// TODO:添加订单处理逻辑代码
	echo $return;
	$orderid = $return['orderid'];
	$amount = $return['amount'];
	$status = $return['status'];
 
if($status =='1')
{
   	$userid 	= 'user';
	$lantype	='cn';
	$devicetype	='phone';
	$productid	='0';
	$orderid 	= $orderid;
	$amount 	= $amount;
	$selectsql = "SELECT * FROM paylist WHERE orderid=".$orderid;
	$result=mysql_query($selectsql);	
	$rows = mysql_fetch_array($result, MYSQL_ASSOC);  
  
    	if (!mysql_num_rows($result))//返回结果集合中行的数目
        { 
$sql = "INSERT INTO paylist(userid,lantype,devicetype,productid,orderid,amount,success) VALUES('".$userid."','".$lantype."','".$devicetype."','".$productid."','".$orderid."','".$amount."','5'".")"; 

        	//$sql = 'INSERT INTO paylist(userid,lantype,devicetype,productid,orderid,amount) 
	//VALUES'.'('.$userid.','.$lantype.','.$devicetype.','.$productid.','.$orderid.','.$amount.')';
            //echo "record doesn't exist~~~~~!!!!!!";  
        }
	else 
	{
		$sql = "UPDATE paylist SET success=5,amount="."'$amount'"."WHERE orderid="."'$orderid'"; 	
	}
	
	 
	printf($sql) ;
	mysql_query($sql);
	echo "added";
}
else if($status == '0')
{
	$userid 	= 'user';
	$lantype	='cn';
	$devicetype	='phone';
	$productid	='0';
	$orderid 	= $orderid;
	$amount 	= $amount;
	$selectsql = "SELECT * FROM paylist WHERE orderid=".$orderid;
	$result=mysql_query($selectsql);	
	$rows = mysql_fetch_array($result, MYSQL_ASSOC);  
  
    	if (!mysql_num_rows($result))//返回结果集合中行的数目
        {  
        	$sql = "INSERT INTO paylist(userid,lantype,devicetype,productid,orderid,amount,success) VALUES('".$userid."','".$lantype."','".$devicetype."','".$productid."','".$orderid."','".$amount."','0'".")";
            echo "record doesn't exist~~~~~!!!!!!";  
        }
	else 
	{
		$sql = 'UPDATE paylist SET success=0,amount='.$amount.'WHERE orderid='.$orderid; 	
	}
	 
	printf($sql) ;
	mysql_query($sql);
	echo "added";
}
 
mysql_close($conn); 
}catch (yeepayMPayException $e) {
// TODO：添加订单支付异常逻辑代码
echo'Error :'.$e;
}



?>
