<?php
//链接
$mongo = new Mongo($server="mongodb://http://59.56.69.163/:27017");
// 选择一个数据库
$db = $mongo->feigou;
$collection = $db->ProductInfo;
//添加一个元素
$obj = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
$collection->insert($obj);
//修改
$newdata = array('$set' => array("title" => "Calvin and Hobbes"));
$collection->update(array("author" => "caleng"), $newdata);
//删除
$collection->remove(array('author'=>'caleng'), array("justOne" => true));
//添加另一个元素，使用不同的格式
$obj = array( "title" => "XKCD", "online" => true );
$collection->insert($obj);
//查询所有的集合
$cursor = $collection->find();
//重复显示结果
foreach ($cursor as $obj) {
    echo $obj["title"] . "\n";
}
// 关闭链接
$m->close();
?>