<?php 

       $judge=$_POST['judge'];
//实例化一个memcache对象
//实例化一个memcache对象
if(!empty($_SERVER['HTTP_APPNAME'])){
    @$mem=memcache_init();
} 
else if(class_exists("Memcache")){
		@$mem=new Memcache;
		@$mem->connect('localhost','11211');
}
if(!empty($mem)){

    usememcache($mem);
}else{
    
    usemysql();
}      
function usememcache($mem){
    global $judge;
	require ('db.php');
    include('../wall/biaoqing.php');
                $sql = 'SELECT * FROM  `weixin_shake_toshake`';
                $query1=mysql_query($sql,$link) or die(mysql_error());
				 while($z=mysql_fetch_assoc($query1)){
					$key[]=realpath("..").$z['wecha_id'];
				 }
            //从memcache服务器获取数据
            $data = $mem->get($key);
            //判断memcache是否有数据
            if( !$data ){
                 
                $i=1;
                while($q=mysql_fetch_assoc($query1)){
                    $q['phone']=pack('H*',$q['phone']);
                    $q['phone']=emoji_unified_to_html(emoji_softbank_to_unified($q['phone']));
                   
                    $data[$i]=$q;
                     $mem->set(realpath("..").$q['wecha_id'],$q, MEMCACHE_COMPRESSED, 3600);
                      $i++;
                }
			}
                //print_r($data);
                if(!empty($data)){
                     usort($data,"compare"); 
                }
                          
        //var_dump( $arr_one[0]['phone']);
   $start=realpath("..")."UPDATE  `weixin_wall_config` SET  `isopen` = ";
$key2 = substr(md5($start), 10, 8);
        //var_dump($data);
        if($judge == 1)
        {
            
        $json_string=json_encode($data);
        echo $json_string;	
         }
        
         else if($judge == 2){
        
        $num=count($data);
         if(empty($data)){
             $num = 0;
         }
         echo $num;
         }
        
         else if($judge == 3){
             
        $startvalue = 2;
                $mem->set($key2,$startvalue, MEMCACHE_COMPRESSED, 3600);
        
         }
        
          else if($judge == 4){
				$startvalue = 1;
                $mem->set($key2, $startvalue, MEMCACHE_COMPRESSED, 3600);
        
         }
             $mem->close(); //关闭memcache连接
    
}   
        function compare($x,$y)
        { 
        	if($x['point'] == $y['point']) 
        		return 0; 
        	elseif($x['point'] > $y['point']) 
        		return -1; 
        	else 
        		return 1; 
        } 

function usemysql(){
    
               global $judge;
         require ('db.php');
        
         include('../wall/biaoqing.php');
        
         $judge=$_POST['judge'];
        
         
        
         $sql1="SELECT * FROM  `weixin_shake_toshake` order by point desc";
        
        $query1=mysql_query($sql1,$link) or die(mysql_error());
        
        
        
        while($q=mysql_fetch_assoc($query1)){
        $q['phone']=pack('H*',$q['phone']);
        $q=emoji_unified_to_html(emoji_softbank_to_unified($q));
        
        $arr_one[]=$q;
        
        }
        
        //var_dump( $arr_one[0]['phone']);
        
         if($judge == 1)
        
         {
        
        $json_string=json_encode($arr_one);
        
        echo $json_string;	
        
         }
        
         else if($judge == 2){
        
        $num=count($arr_one);
        
         echo $num;
        
         }
        
         else if($judge == 3){
        
         $start="UPDATE  `weixin_wall_config` SET  `isopen` =  '2'";
        
        $query1=mysql_query($start,$link) or die(mysql_error());
        
         }
        
          else if($judge == 4){
        
         $start="UPDATE  `weixin_wall_config` SET  `isopen` =  '1'";
        
        $query1=mysql_query($start,$link) or die(mysql_error());

 }
}

?>