<?php
//����
$mongo = new Mongo($server="mongodb://http://59.56.69.163/:27017");
// ѡ��һ�����ݿ�
$db = $mongo->feigou;
$collection = $db->ProductInfo;
//���һ��Ԫ��
$obj = array( "title" => "Calvin and Hobbes", "author" => "Bill Watterson" );
$collection->insert($obj);
//�޸�
$newdata = array('$set' => array("title" => "Calvin and Hobbes"));
$collection->update(array("author" => "caleng"), $newdata);
//ɾ��
$collection->remove(array('author'=>'caleng'), array("justOne" => true));
//�����һ��Ԫ�أ�ʹ�ò�ͬ�ĸ�ʽ
$obj = array( "title" => "XKCD", "online" => true );
$collection->insert($obj);
//��ѯ���еļ���
$cursor = $collection->find();
//�ظ���ʾ���
foreach ($cursor as $obj) {
    echo $obj["title"] . "\n";
}
// �ر�����
$m->close();
?>