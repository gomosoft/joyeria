<?php

require_once( dirname(__FILE__) . '/config.inc');


class wcomerce_api{

	protected $db;
	
    function __construct(){
      
        $this->db = new mysqli(db_host, db_user, db_pass, db_bd);

    }


    function getProducts(){

    	  $query = "SELECT * FROM `" . tb_prefix ."posts` WHERE post_status = 'publish' AND post_type = 'product'";

    	  if(isset($_GET['id']))
    	  	$query .= " AND id = {$_GET['id']}";

    	  $rs = $this->db->query($query) or die($db->error);
    	  $products = array();

    	  if($rs->num_rows > 0)
    	  {

    	  	while($row = $rs->fetch_assoc())
    	  		{
    	  			$rss = $row;
    	  			$query = "SELECT * FROM `" . tb_prefix . "postmeta` WHERE post_id= {$row['ID']}";
    	  			$metas = $this->db->query($query) or die($db->error);

    	  			if($metas->num_rows > 0){
    	  				
    	  				$metas_ = array();

    	  				while($row = $metas->fetch_assoc())    	  				
    	  					$metas_[] = $row;
    	  				
    	  				$rss['metas'] = $metas_;

    	  				$products[] = $rss;

    	  			}else
    	  			   $products[] = $rss;

    	  			

    	  		}

    	  	echo $this->ok($products);
    	  	return;

    	  }
    	  else
    	  	echo $this->no('no_product');

    }


     function getProduct($id){

    	  $query = "SELECT * FROM `" . tb_prefix ."posts` WHERE post_status = 'publish' AND post_type = 'product' AND ID = {$id}";


    	  $rs = $this->db->query($query) or die($db->error);
    	  $products = array();

    	  if($rs->num_rows > 0)
    	  {

    	  	while($row = $rs->fetch_assoc())
    	  		{
    	  			$rss = $row;
    	  			$query = "SELECT * FROM `" . tb_prefix . "postmeta` WHERE post_id= {$row['ID']}";
    	  			$metas = $this->db->query($query) or die($db->error);

    	  			if($metas->num_rows > 0){
    	  				
    	  				$metas_ = array();

    	  				while($row = $metas->fetch_assoc())    	  				
    	  					$metas_[] = $row;
    	  				
    	  				$rss['metas'] = $metas_;

    	  				$products[] = $rss;

    	  			}else
    	  			   $products[] = $rss;

    	  			

    	  		}

    	  	return $products;

    	  }
    	  else
    	  	return false;

    }


    function getCats(){

    	  $query = "SELECT * FROM `" . tb_prefix ."term_taxonomy` WHERE taxonomy = 'product_cat'";

    	  if(isset($_GET['id']))
    	  	$query .= " AND term_id = {$_GET['id']}";

    	  $rs = $this->db->query($query);
    	  $cats = array();

    	  if($rs->num_rows > 0)
    	  {

    	  	$rss = array();

    	  	while($row = $rs->fetch_assoc())
    	  		{
    	  			$rss = $row;

    	  			$query = "SELECT * FROM `" . tb_prefix . "terms` WHERE term_id = {$row['term_id']}";
    	  			$metas = $this->db->query($query) or die($db->error);

    	  			if($metas->num_rows > 0){
    	  				
    	  				$metas_ = array();

    	  				while($row = $metas->fetch_assoc())    	  				
    	  					{
    	  						

    	  						$query = "SELECT * FROM `" . tb_prefix . "woocommerce_termmeta` WHERE meta_key = 'thumbnail_id' AND woocommerce_term_id = {$row['term_id']} LIMIT 1";

    	  						$thumb = $this->db->query($query) or die($db->error);

    	  						$thumb = $thumb->fetch_assoc();

    	  						$row['img_id'] = $thumb['meta_value'];
                       
                        if(isset($_GET['prods'])){

    	  			     $query = "SELECT * FROM `" . tb_prefix . "term_relationships` WHERE term_taxonomy_id = {$row['term_id']}";
    	  			     $products = $this->db->query($query) or die($db->error);
    	  			     $prods = array();

    	  			        while($row_ = $products->fetch_assoc())
    	  			        	{
    	  			        		$prods[] = $this->getProduct($row_['object_id']);

    	  			        	}

    	  			            $row['products'] = $prods;

    	  			         }

    	  						$metas_[] = $row;

    	  					}





    	  				
    	  				$cats[] = $metas_;

    	  			}

    	  		}

    	  	echo $this->ok($cats);
    	  	return;

    	  }
    	  else
    	  	echo $this->no('no_product');

    }


    public function get_attachments(){

    	  $query = "SELECT * FROM `" . tb_prefix ."posts` WHERE post_type = 'attachment'";

    	   if(isset($_GET['id']))
    	  	$query .= " AND ID = {$_GET['id']}";


    	  $rs = $this->db->query($query);
    	  $atts = array();

    	  if($rs->num_rows > 0)
    	  {

    	  	while($row = $rs->fetch_assoc())
    	  		$atts[] = $row;

    	  	$this->ok($atts);
    	  	exit;


    	  }
    	  $this->no('no_attachments');


    }


    public function get_image(){

    	  $query = "SELECT * FROM `" . tb_prefix ."posts` WHERE post_type = 'attachment' AND ID = {$_GET['id']} LIMIT 1";


    	  $rs = $this->db->query($query);
    	  $atts = array();

    	  if($rs->num_rows > 0)
    	  {

    	  	$img = $rs->fetch_assoc();


    	  	header("Content-Type:{$img['post_mime_type']}");

    	  	echo file_get_contents($img['guid']);

    	  	exit;


    	  }
    	  $this->no('no_attachments');


    }



    public function no($msg = false){

    	header('content-type: application/json; charset=utf-8');
          
           $msg = $msg ? $msg : 'no_autorizado';

           echo json_encode(array('error' => true, 'mensaje' => $msg));

     }

     public function ok($rs, $msg = false){

     	header('content-type: application/json; charset=utf-8');
         
         $json = array('error' => false, 'data' => $rs);

         if($msg)
          $json['msg'] = $msg;

         echo json_encode($json);

     }

}


function main(){
 
    $verbo = $_SERVER['REQUEST_METHOD'];
    $api = new wcomerce_api;

    switch ($verbo) {

    	case 'GET':

    		 if(isset($_GET['products']))
    		 {
    		 	$api->getProducts();
    		 	exit;
    		 }

    		  if(isset($_GET['cats']))
    		 {
    		 	$api->getCats();
    		 	exit;
    		 }

    		  if(isset($_GET['image']))
    		 {
    		 	$api->get_image();
    		 	exit;
    		 }
    	
    		break;
    	
    	default:
    		exit;
    	break;

    }

}


main();