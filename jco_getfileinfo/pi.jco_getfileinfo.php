<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name' => 'JCO Get File Info',
  'pi_version' =>'1.0',
  'pi_author' =>'Jerome Coupe',
  'pi_author_url' => 'http://twitter.com/jeromecoupe/',
  'pi_description' => 'Returns information about any given file',
  'pi_usage' => Jco_getfileinfo::usage()
  );


class Jco_getfileinfo {
	
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
	public function Jco_getfileinfo()
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
		$this->return_data = $this->Retrieve_file_info($this->EE->TMPL->fetch_param('filename'));
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
	public function Retrieve_file_info($file)
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
		
		//get file size using CI File helper & format it using CI Number Helper
		$this->EE->load->helper('number');
		$file_size = byte_format($infos['size'],0);
		
		//get file name using CI File helper
		//added basename wrapper for IIS support ... otherwise IIS returns part of the path
		//ref: http://devot-ee.com/add-ons/support/jco-get-file-info/viewthread/2216/
		$file_name = basename($infos['name']);
		
		//get file extension using php function path info
		$file_extension = pathinfo($file, PATHINFO_EXTENSION);
		
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
	public function usage()
	{
		ob_start(); 
		?>

			Description:
	
			Returns file informations.
	
			------------------------------------------------------
			
			Example:
			
			{exp:jco_getfileinfo filename="{custom_field}"}
				{file_name}
				{file_extension}
				{file_path}
				{file_size}
				{file_date}
			{/exp:jco_getfileinfo}
	
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


/* End of file pi.jco_getfileinfo.php */ 
/* Location: ./system/expressionengine/third_party/jco_getfileinfo/pi.jco_getfileinfo.php */