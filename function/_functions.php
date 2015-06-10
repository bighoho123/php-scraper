<?php 
	/**
	 * Extract information on carsales page
	 * @param  [type] $crawler [description]
	 * @param  [type] $db      [description]
	 * @return [type]          [description]
	 */
	function getInfomationOnThisPage($crawler,$db,$batchId) {
		$title="";
		$link="";
		$cid="";
		$price="";
		$odo="";

		// Get title
		$info = $crawler->filter('.result-item')->each(function ($node) {
			$info=array();

			/* Title & Link */
			$tmp=$node->filter("h2 a")->each(function($node_1){
				$tmp=array();
			    $tmp['title'] = trim(preg_replace("/<span.*span>/s","",$node_1->html()));
			    $tmp['link'] = "http://www.carsales.com.au".$node_1->attr("href");
			    $tmp['cid'] = $node_1->attr('recordid');
			    return $tmp;
			});
			if (count($tmp)>0){
				$info = array_merge($info,$tmp[0]);
			}
			/* Price */
			$tmp=$node->filter(".additional-information .price a")->each(function($node_2){
				$tmp=array();
			    $tmp['price'] = $node_2->attr('data-price');
			    return $tmp;
			});
			if (count($tmp)>0){
				$info = array_merge($info,$tmp[0]);
			}
			/* Odometer */
			$tmp = $node->filter(".vehicle-features .item-odometer")->each(function($node_2){
				$tmp=array();
			    $tmp['odo'] = preg_replace(array("/<i.*i>/","/,/","/ km$/"),array("","",""),$node_2->html());
			    return $tmp;
			});
			if (count($tmp)>0){
				$info = array_merge($info,$tmp[0]);
			}

			return $info;

		});

		$sqls=array();
		foreach ($info AS $i) {
			$title=isset($i['title'])?$db->real_escape_string($i['title']):"";
			$link=isset($i['link'])?$db->real_escape_string($i['link']):"";
			$cid=isset($i['cid'])?$db->real_escape_string($i['cid']):"";
			$price=isset($i['price'])?$db->real_escape_string($i['price']):"";
			$odo=isset($i['odo'])?$db->real_escape_string($i['odo']):"";

			$sqls[]="('$cid','$title','$price','$odo','$batchId','$link')";
		}
		$db->query("INSERT INTO subaru_outback (record_id,title,price,odometer,batch_id,link) VALUES ".implode(",",$sqls));

	}

	/**
	 * Get the batch id for this crawl
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	function getThisBatchId($db) {
		$batchId=0;
		$res=$db->query("SELECT MIN(id) as id_min FROM batch");
		while($row=$res->fetch_assoc()) {
			$batchId=$row["id_min"];
		}
		return $batchId+1;
	}
	/**
	 * Insert this batch into the system
	 * @param  [type] $db [description]
	 * @return [type]     [description]
	 */
	function insertThisBatch($db) {
		$now=date("Y-m-d H:i:s");
		$db->query("INSERT INTO batch (time) VALUES ('$now')");
		return $db->insert_id;
	}

?>