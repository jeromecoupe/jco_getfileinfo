<?php

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$plugin_info = array(
  'pi_name'         => 'JCO Get File Info',
  'pi_version'      => '1.2.1',
  'pi_author'       => 'Jérôme Coupé',
  'pi_author_url'   => 'http://webstoemp.com',
  'pi_description'  => 'Returns information about any given file',
  'pi_usage'        => Jco_getfileinfo::usage()
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
	* Constructor.
	*
	* @access	public
	* @return	string
	*/
	function __construct()
	{
		//load Codeigniter helpers (file and number)
		ee()->load->helper(array('file','number'));

		$file = trim(ee()->TMPL->fetch_param('filename'));
		$this->return_data = $this->Retrieve_file_info($file);
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
		//file as passed by EE
		$this->_log_item('URL as passed by EE - '.$file);

		//get the relative url (without the "http://domain" part) using parse url
		$file = parse_url($file);
		$file = $file["path"];
		$this->_log_item('Domain stripped out - '.$file);

		//make sure that it's an absolute server path. If not, make it one
		$file = (stristr($file,$_SERVER['DOCUMENT_ROOT'])) ? $file : rtrim($_SERVER['DOCUMENT_ROOT'], '/').$file;
		$this->_log_item('Full server path to file - '.$file);

		//remove duplicate slashes
		$file = str_replace("//", "/", $file);
		$this->_log_item('Removed duplicate slashes - '.$file);

		//encode URL to deal with special characters
		$file = urldecode($file);
		$this->_log_item('Decoded URL (special chars) - '.$file);

		//return error if file does not exists
		if (!file_exists($file)) return "file cannot be found: please check template debugger for 'JCO GET FILE INFO' and verify your code";

		//load CI file helper and get file infos using it
		$infos = get_file_info($file);

		//get file size using CI File helper & format it using CI Number Helper
		$file_size = byte_format($infos['size'],0);

		//get file name using CI File helper
		//added basename wrapper for IIS support ... otherwise IIS returns part of the path
		//ref: http://devot-ee.com/add-ons/support/jco-get-file-info/viewthread/2216/
		$file_name = basename($infos['name']);

		//get file extension using php function path info
		$file_extension = pathinfo($file, PATHINFO_EXTENSION);

		//get filename without extension
		$file_filename =  basename($file_name,'.'.$file_extension);

		//get file server path using CI File helper
		$file_path = $infos['server_path'];

		//get file date using CI File helper (dates can be formatted by default)
		$file_date = $infos['date'];

		//building variables array for variables output in tag pair
		$variables[0] = array(
			'file_name'       =>  $file_name,
			'file_filename'   =>  $file_filename,
			'file_extension'  =>  $file_extension,
			'file_path'       =>  $file_path,
			'file_size'       =>  $file_size,
			'file_date'       =>  $file_date
			);

		return ee()->TMPL->parse_variables(ee()->TMPL->tagdata, $variables);
	}

	private function _log_item($message)
	{
		ee()->TMPL->log_item('&nbsp;&nbsp;- JCO GET FILE INFO: '.$message);
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
	public static function usage()
	{
		ob_start();
		?>

			Description:

			Returns file informations.

			------------------------------------------------------

			Example:

			{exp:jco_getfileinfo filename="{custom_field}"}
				{file_name}
				{file_filename}
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
			{file_filename}
			{file_extension}
			{file_path}
			{file_size}
			{file_date format="%l %F %j, %Y"}

		<?php
		$buffer = ob_get_contents();

		ob_end_clean();

		return $buffer;
	}
	  // END

	}


/* End of file pi.jco_getfileinfo.php */
/* Location: ./system/expressionengine/third_party/jco_getfileinfo/pi.jco_getfileinfo.php */
