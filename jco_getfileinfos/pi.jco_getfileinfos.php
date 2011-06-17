<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name' => 'JCO Get File Infos',
  'pi_version' =>'1.0',
  'pi_author' =>'Jerome Coupe',
  'pi_author_url' => 'http://twitter.com/jeromecoupe/',
  'pi_description' => 'Returns information about any given file',
  'pi_usage' => Jco_getfileinfos::usage()
  );


class Jco_getfileinfos {
	
	/* --------------------------------------------------------------
	* RETURNED DATA
	* ------------------------------------------------------------ */
	/**
	* Data returned from the plugin.
	*
	* @access	public
	* @var 		string
	*/
	public $return_data = "";
	
	/* --------------------------------------------------------------
	* CONSTRUCTOR
	* ------------------------------------------------------------ */
	
	/**
	* Support for EE prior to 2.1.3
	*
	* @access 	public
	* @return 	void
	* method first seen used by Stephen Lewis (https://github.com/experience/you_are_here.ee2_addon)
	*/
	public function Jco_getfileinfos()
	{
		$this->__construct();
	}
	
	
	/**
	* Constructor.
	*
	* @access	public
	* @return	string
	*/
	function __construct()
	{
		$this->EE =& get_instance();
		$this->return_data = $this->Retrieve_file_infos($this->EE->TMPL->fetch_param('filename'));
	}
	
	
	/* --------------------------------------------------------------
	* USED FUNCTIONS
	* ------------------------------------------------------------ */
	
	/**
	* Return number of items in category.
	*
	* @access	public
	* @return	string
	*/
	public function Retrieve_file_infos($file)
	{
		
		//get the relative url (without the "http://domain" part)
		$file = parse_url($file);
		$file = $file["path"];
		
		//make sure that it's an absolute server path. If not, make it one
		$file = (stristr($file,$_SERVER['DOCUMENT_ROOT'])) ? $file : $_SERVER['DOCUMENT_ROOT'].$file;
		
		//remove duplicate slashes
		$file = str_replace("//", "/", $file);
		
		//return error if file does not exists
		if (!file_exists($file)) return "file cannot be found: please check your settings and code";
		
		//load CI file helper and get file infos using it
		$this->EE->load->helper('file');
		$infos = get_file_info($file);
		
		//get file size using CI File helper & format it
		$file_size = $this->_format_bytes($infos['size']);
		
		//get file name using CI File helper
		$file_name = $infos['name'];
		
		//get file extension using php function path info
		$file_extension = pathinfo($file, PATHINFO_EXTENSION);
		
		//get file server path using php function path info
		//$file_path = pathinfo($file, PATHINFO_DIRNAME);
		
		//get file server path using CI File helper
		$file_path = $infos['server_path'];
		
		//get file date using CI File helper (dates can be formatted by default)
		$file_date = $infos['date'];
		
		//building variables array for variables output in tag pair
		$variables[0] = array(
			'file_name' 		=>	$file_name,
			'file_extension' 	=>	$file_extension,
			'file_path' 		=>	$file_path,
			'file_size' 		=>	$file_size,
			'file_date' 		=>	$file_date
			);
		
		return $this->EE->TMPL->parse_variables($this->EE->TMPL->tagdata, $variables);
	}
	
	/* --------------------------------------------------------------
	* PRIVATE FUNCTIONS
	* ------------------------------------------------------------ */
	
	/**
	* Check if category_id is a number and if it exists in DB
	*
	* @access	private
	* @return	string
	*/
	private function _format_bytes($bytes)
	{
		if ($bytes < 1024) return $bytes.'&nbsp;B';
		elseif ($bytes < 1048576) return round($bytes / 1024, 0).'&nbsp;KB';
		elseif ($bytes < 1073741824) return round($bytes / 1048576, 2).'&nbsp;MB';
		elseif ($bytes < 1099511627776) return round($bytes / 1073741824, 2).'&nbsp;GB';
		else return round($bytes / 1099511627776, 2).'&nbsp;TB';
	}
	
	/* --------------------------------------------------------------
	* PLUGIN USAGE
	* ------------------------------------------------------------ */

	/**
	 * Usage
	 *
	 * This function describes how the plugin is used.
	 *
	 * @access	public
	 * @return	string
	 */
	function usage()
	{
		ob_start(); 
		?>

			Description:
	
			Returns file informations.
	
			------------------------------------------------------
			
			Example:
			
			{exp:jco_getfileinfos filename="{custom_field}"}
				{file_name}
				{file_extension}
				{file_path}
				{file_size}
				{file_date}
			{/exp:jco_getfileinfos}
	
			------------------------------------------------------
			
			Parameters:
	
			file="{custom field}" : Mandatory
			
			
			Variables:
			{file_name}
			{file_extension}
			{file_path}
			{file_size}
			{file_date format="%D %m %Y"}
		
		<?php
		$buffer = ob_get_contents();

		ob_end_clean(); 

		return $buffer;
	}
	  // END

	}


/* End of file pi.jco_getfileinfos.php */ 
/* Location: ./system/expressionengine/third_party/plugin_name/pi.jco_getfileinfos.php */