<?php
class Item_taxes_finder extends CI_Model
{
	/*
	Gets tax info for a particular item
	*/
	private static $definido1;
	private static $default_tax_1_rate1;
	private static $default_tax_1_name1 ;
					
	private static $default_tax_2_rate1 ;
	private static $default_tax_2_name1 ;
	private static $default_tax_2_cumulative1 ;
		
	private static $default_tax_3_rate1 ;
	private static $default_tax_3_name1 ;
		
	private static	$default_tax_4_rate1 ;
	private static	$default_tax_4_name1 ;
			
	private static	$default_tax_5_rate1 ;
	private static	$default_tax_5_name1 ;
	//----------------------------------
	private static $definido2;
	private static $default_tax_1_rate2;
	private static $default_tax_1_name2 ;
					
	private static $default_tax_2_rate2 ;
	private static $default_tax_2_name2 ;
	private static $default_tax_2_cumulative2 ;
		
	private static $default_tax_3_rate2 ;
	private static $default_tax_3_name2 ;
		
	private static	$default_tax_4_rate2 ;
	private static	$default_tax_4_name2 ;
			
	private static	$default_tax_5_rate2 ;
	private static	$default_tax_5_name2 ;
	
	function get_info($item_id)
	{
		$item_location_info = $this->Item_location->get_info($item_id);

		if($item_location_info->override_default_tax)
		{
			return $this->Item_location_taxes->get_info($item_id);
		}
		
		$item_info = $this->Item->get_info($item_id);

		if($item_info->override_default_tax)
		{
			return $this->Item_taxes->get_info($item_id);
		}

		$return = array(); 
		
		//Location Config
		if(self::$definido1 == null)
		{
			self::$definido1=true;
			self::$default_tax_1_rate1 = $this->Location->get_info_for_key('default_tax_1_rate');

			self::$default_tax_1_rate1 = $this->Location->get_info_for_key('default_tax_1_rate');
			self::$default_tax_1_name1 = $this->Location->get_info_for_key('default_tax_1_name');
					
			self::$default_tax_2_rate1 = $this->Location->get_info_for_key('default_tax_2_rate');
			self::$default_tax_2_name1 = $this->Location->get_info_for_key('default_tax_2_name');
			self::$default_tax_2_cumulative1 = $this->Location->get_info_for_key('default_tax_2_cumulative') ? $this->Location->get_info_for_key('default_tax_2_cumulative') : 0;
			
			self::$default_tax_3_rate1 = $this->Location->get_info_for_key('default_tax_3_rate');
			self::$default_tax_3_name1 = $this->Location->get_info_for_key('default_tax_3_name');
			
			self::$default_tax_4_rate1 = $this->Location->get_info_for_key('default_tax_4_rate');
			self::$default_tax_4_name1 = $this->Location->get_info_for_key('default_tax_4_name');
			
			self::$default_tax_5_rate1 = $this->Location->get_info_for_key('default_tax_5_rate');
			self::$default_tax_5_name1 = $this->Location->get_info_for_key('default_tax_5_name');
		}
		
		if (self::$default_tax_1_rate1)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_1_name1,
				'percent' => self::$default_tax_1_rate1,
				'cumulative' => 0
			);
		}
		
		if (self::$default_tax_2_rate1)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' =>self:: $default_tax_2_name1,
				'percent' =>self:: $default_tax_2_rate1,
				'cumulative' => self::$default_tax_2_cumulative1
			);
		}

		if (self::$default_tax_3_rate1)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_3_name1,
				'percent' => self::$default_tax_3_rate1,
				'cumulative' => 0
			);
		}

		if (self::$default_tax_4_rate1)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_4_name1,
				'percent' => self::$default_tax_4_rate1,
				'cumulative' => 0
			);
		}

		if (self::$default_tax_5_rate1)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_5_name1,
				'percent' => self::$default_tax_5_rate1,
				'cumulative' => 0
			);
		}
		
		if (!empty($return))
		{
			return $return;
		}
		
		//Global Store Config
		if(self::$definido2 == null)
		{
			self::$definido2=true;
			self::$default_tax_1_rate2 = $this->config->item('default_tax_1_rate');
			self::$default_tax_1_name2 = $this->config->item('default_tax_1_name');
					
			self::$default_tax_2_rate2 = $this->config->item('default_tax_2_rate');
			self::$default_tax_2_name2 = $this->config->item('default_tax_2_name');
			self::$default_tax_2_cumulative2 = $this->config->item('default_tax_2_cumulative') ? $this->config->item('default_tax_2_cumulative') : 0;
			
			self::$default_tax_3_rate2 = $this->config->item('default_tax_3_rate');
			self::$default_tax_3_name2 = $this->config->item('default_tax_3_name');
			
			self::$default_tax_4_rate2 = $this->config->item('default_tax_4_rate');
			self::$default_tax_4_name2 = $this->config->item('default_tax_4_name');
			
			self::$default_tax_5_rate2 = $this->config->item('default_tax_5_rate');
			self::$default_tax_5_name2 = $this->config->item('default_tax_5_name');
		}
		$return = array();
		
		if (self::$default_tax_1_rate2)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_1_name2,
				'percent' => self::$default_tax_1_rate2,
				'cumulative' => 0
			);
		}
		
		if (self::$default_tax_2_rate2)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_2_name2,
				'percent' =>self::$default_tax_2_rate2,
				'cumulative' => self::$default_tax_2_cumulative2
			);
		}

		if (self::$default_tax_3_rate2)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_3_name2,
				'percent' => self::$default_tax_3_rate2,
				'cumulative' => 0
			);
		}

		if (self::$default_tax_4_rate2)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_4_name2,
				'percent' => self::$default_tax_4_rate2,
				'cumulative' => 0
			);
		}

		if (self::$default_tax_5_rate2)
		{
			$return[] = array(
				'id' => -1,
				'item_id' => $item_id,
				'name' => self::$default_tax_5_name2,
				'percent' => self::$default_tax_5_rate2,
				'cumulative' => 0
			);
		}	
				
		return $return;
	}
}
?>
