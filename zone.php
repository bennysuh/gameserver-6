﻿<?php 
//引用zoneLib
include ('admin.php');
include ('zoneLib.php'); 
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
if($_GET['type']==1)//type == 1获取所有数据
{
	$sql = 'SELECT * FROM unity';
	$result = mysql_query($sql);
	
	$show = '<List>';
	while($row = mysql_fetch_array($result))
	{
		$item = new Item;
		$item->{'id'} = $row['id'];
		$item->{'gatherType'} = $row['gatherType'];
		$item->{'gameType'} = $row['gameType'];
		$item->{'name'} = $row['name'];
		$item->{'cp'} = $row['cp'];
		$item->{'price'} = $row['price'];
		$item->{'capacity'} = $row['capacity'];
		$item->{'title'} = $row['title'];
		$item->{'content'} = $content;
		$item->{'star'} = $row['star'];
		$item->{'downloadURL'} = $row['downloadURL'];
		$item->{'iconurl'} = "img/".$item->{'id'}."/icon.png";//$row['iconurl'];
		$item->{'imgurl1'} = "img/".$item->{'id'}."/img1.png";//$row['imgurl1'];
		$item->{'imgurl2'} = "img/".$item->{'id'}."/img2.png";//$row['imgurl2'];
		//生成xml内容
		$show.='<Item>'.
		'<id>'.$item->{'id'}.'</id>'.
		'<gatherType>'.$item->{'gatherType'}.'</gatherType>'.
		'<gameType>'.$item->{'gameType'}.'</gameType>'.
		'<name>'.$item->{'name'}.'</name>'.
		'<cp>'.$item->{'cp'}.'</cp>'.
		'<price>'.$item->{'price'}.'</price>'.
		'<capacity>'.$item->{'capacity'}.'</capacity>'.
		'<title>'.$item->{'title'}.'</title>'.
		'<content>'.$item->{'content'}.'</content>'.
		'<star>'.$item->{'star'}.'</star>'.
		'<downloadURL>'.$item->{'downloadURL'}.'</downloadURL>'.
		'<iconurl>'.$localip.$item->{'iconurl'}.'</iconurl>'.
		'<imgurl1>'.$localip.$item->{'imgurl1'}.'</imgurl1>'.
		'<imgurl2>'.$localip.$item->{'imgurl2'}.'</imgurl2>'.'</Item>';
	} 
	$show.='</List>';
	echo $show;//urlencode(utf8_encode($show));
}
if($_GET['type']==2)//type == 1获取对应id的content
{
	//SQL脚本
	$sql = 'SELECT * FROM unity WHERE id='.$_GET['id'];
	$result = mysql_query($sql);
	//列出反馈内容
	while($row = mysql_fetch_array($result))
	{
		echo $row['content'];
	}
}
if($_GET['type']==3)//搜索相应名称的游戏 返回id name iconurl
{
	$sql = 'SELECT * FROM unity WHERE name='.$_GET['name'];
	//printf($sql);
	$result = mysql_query($sql);
	//列出反馈内容
	if($result)
	while($row = mysql_fetch_array($result))
	{
		$aresult = "<Item>";
		
		$aresult.="<id>".$row['id']."</id>";
		$aresult.="<name>".$row['name']."</name>";
		$aresult.="<iconurl>".$row['iconurl']."</iconurl>";
		$aresult.="</Item>";
	}
	echo $aresult;
}
else if($_GET['type']==4)//搜索相应名称的游戏 返回id name iconurl
{
	echo("ok");
}
//根据orderID 判断数据库中是否有该条记录
//0 不存在记录
//1 有记录并返回amount
else if($_GET['type'] == 'checkorder')
{ 
	$sql="SELECT * FROM paylist WHERE orderid='".$_GET['orderid']."'";
	$result=mysql_query($sql);  
  
	$row = mysql_fetch_array($result, MYSQL_ASSOC);  
  
    if (!mysql_num_rows($result))//返回结果集合中行的数目
        {  
        	echo "faild";
            //echo "record doesn't exist~~~~~!!!!!!";  
        }  
    else  
        {  
        	echo "success";
           // echo mysql_num_rows($result)."\n";  
            //echo $row['yb_orderid'];  
           // echo $row['yb_amount'];  
        }   
}
//添加一条记录
else if($_GET['type'] == 'add')
{ 
	$userid 	= $_GET['userid'];
	$lantype	='cn';
	$devicetype	=$_GET['devicetype'];
	$productid	=$_GET['productid'];
	$orderid 	= $_GET['orderid'];
	$amount 	= $_GET['amount'];

	$sql = 'INSERT INTO paylist(userid,lantype,devicetype,productid,orderid,amount) 
	VALUES'.'('.$userid.','.$lantype.','.$devicetype.','.$productid.','.$orderid.','.$amount.')';
	
	//$sql='INSERT INTO paylist(yb_data,yb_orderid,yb_amount)VALUES('$data','$orderid','$amount')';
	printf($sql) ;
	mysql_query($sql);
	echo "added";
}
else if($_GET['type']=='getall')//type == 1获取对应id的content
{
	$sql = 'SELECT * FROM paylist';
	//printf($sql);
	$result = mysql_query($sql);
	while($row = mysql_fetch_array($result))
	{
		echo "id".$row['yb_id'];
		echo "orderid".$row['yb_orderid'];
	}

}
else 
{
	echo 0;
}
	//执行数据库脚本
//mysql_query($sql); 
//需要执行的数据库脚本  
/*$sql='CREATE TABLE `counter` 
(`id` INT(255) UNSIGNED NOT NULL 
AUTO_INCREMENT ,`count` INT(255) 
UNSIGNED NOT NULL DEFAULT 0,PRIMARY KEY 
( `id` ) ) TYPE = innodb;';   
*/
 
//$result=mysql_query($sql);   
//echo $sql;   
mysql_close($conn);   
?> 
