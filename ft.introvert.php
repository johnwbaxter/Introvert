<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Introvert: Fieldtype which outputs reverse related entries to the one being edited
 *
 * @package		ExpressionEngine
 * @subpackage	Fieldtypes
 * @category	Fieldtypes
 * @author    	Iain Urquhart <shout@iain.co.nz>
 * @copyright 	Copyright (c) 2010 Iain Urquhart
 * @license   	All Rights Reserved.
*/

	class Introvert_ft extends EE_Fieldtype
	{
		var $info = array(
			'name'		=> 'Introvert Fieldtype',
			'version'	=> '1.0'
		);

		public function Introvert_()
		{
			parent::EE_Fieldtype();
			$this->EE->lang->loadfile('introvert');
		}	

		// output the field on the publish page
		public function display_field($data)
		{
		
			// when editing an entry the entry_id should be available as a GET
			$entry_id = $this->EE->input->get('entry_id');
						
			// if its not there, then its a new entry being published via the cp
			if($entry_id == NULL)
			{
				// we'll stop here then, laters
				return '<p>No Registrations yet</p>';
			}
			
			$r = '';
			
			// build the css based on statuses
			// if you have other statuses apart from open/closed, style them here
			$r .= '
				<style type="text/css">
					tr.introvert-closed td{
						color: #E11842;
					}
					tr.introvert-open td {
						background:#E6EFC2;
						color:#264409;
						border-top: 1px solid #C6D880 !important;
						border-bottom: 1px solid #C6D880 !important;
					}
					tr.introvert-open td a{
						color:#264409;
					}
					
					tr.introvert-closed td {
						background:#FBE3E4;
						color:#8a1f11;
						border-top: 1px solid #FBC2C4 !important;
						border-bottom: 1px solid #FBC2C4 !important;
					}
					tr.introvert-closed td a{
						color:#8a1f11;
					}
				</style>			
			';
			
			// my needs are quite specific, so adjust the column names to suit what you're after
			$r .= '<table class="mainTable" border="0" cellspacing="0" cellpadding="0" style="margin-top: 5px;">
						<tr>
							<th style="width: 30%;">Name</th>
							<th style="width: 30%;">Email</th>
							<th style="width: 30%;">Tel</th>
							<th></th>
						</tr>';

			
			// build our reverse related query
			// I'm getting some specific field data for my setup
			// You'll need to update for your needs/setup
			
			// field_id_25 = Registrant first name
			// field_id_26 = Registrant surname
			// field_id_27 = Email
			// field_id_28 = Phone
			// field_id_29 = Institute
			
			$sql = "SELECT 
				exp_relationships.rel_parent_id,
				exp_relationships.rel_child_id,
				exp_channel_titles.title,
				exp_channel_titles.entry_id,
				exp_channel_titles.status,
				exp_channel_data.field_id_25,
				exp_channel_data.field_id_26,
				exp_channel_data.field_id_27,
				exp_channel_data.field_id_28,
				exp_channel_data.field_id_29
				FROM exp_relationships
					
					LEFT JOIN exp_channel_titles
					ON (exp_relationships.rel_parent_id=exp_channel_titles.entry_id)
					
					LEFT JOIN exp_channel_data
					ON (exp_relationships.rel_parent_id=exp_channel_data.entry_id)
				
				WHERE exp_relationships.rel_child_id = '$entry_id'
				AND exp_relationships.rel_type = 'channel'
				ORDER BY exp_channel_titles.title";
				
			// run it
			$query = $this->EE->db->query($sql);
			
			// build our 'edit' links
			$edit_base = BASE.AMP.'C=content_publish'.AMP.'M=entry_form'.AMP.'channel_id=3'.AMP.'entry_id=';
			
			if ($query->num_rows() > 0)
			{	
				// build the table rows
				foreach($query->result_array() as $row)
				{
					// make things a little more readable
					// print_r($row);
					$title 		= $row['title'];
					$firstname 	= $row['field_id_25'];
					$surname 	= $row['field_id_26'];
					$email 		= $row['field_id_27'];
					$phone 		= $row['field_id_28'];
					$institute 	= $row['field_id_29'];
					$status		= $row['status'];
					$edit_link 	= $edit_base.$row['entry_id'];
					
					$r .= '<tr class="introvert-'.$status.'">';
					$r .= "<td><strong>$firstname $surname</strong> ($institute)</td>";
					$r .= "<td><a href='mailto:$email'>$email</a></td>";
					$r .= "<td>$phone</td>";
					$r .= "<td><a href='$edit_link'>View / Edit</a></td>";
					$r .= '</tr>';
				}

			}
			// no results
			else
			{
				return '<p>Nothing yet&hellip;</p>';
			}
			
			$r .= '</table>';

			return $r;

		}
		
		function pre_process($data)
		{
			// nada
		}
		
		public function replace_tag($data, $params = FALSE, $tagdata = FALSE)
		{
			// nada	
		}
		
		public function save($data)
		{
			// we're not even saving anything
		}
		
		function post_save($data)
		{
			// nada
		}
		
		public function validate($data)
		{
			return TRUE;
		}
		
		public function save_settings($data)
		{
			// nada
		}

		public function display_settings($data)
		{
			// nada
 		}			

		function install()
		{
			// zip
		}

		function unsinstall()
		{
			// move along, nothing to see
		}
	}
	//END CLASS
	
/* End of file ft.introvert.php */