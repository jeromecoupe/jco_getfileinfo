# Description

Simple EE2 only plugin returning a bunch of infos about any given file (file name / file extension / file size / file date / file server path). The file has to be on the local install (does not work with remote files).

	{exp:jco_getfileinfo filename="{cf_blogpost_image}"}
		{file_name}
		{file_filename}
		{file_extension}
		{file_path}
		{file_size}
		{file_date format="%l %F %Y"}
	{/exp:jco_getfileinfo}

# Parameters and Variables

## Parameter
`file="{custom field}"`

- Mandatory
- The custom field holding the file you want information about or a full server path to a file

## Variables

- `{file_name}`: Full name of the file (including extension)
- `{file_filename}`: Filename (without extension)
- `{file_extension}`: File extension (without the dot)
- `{file_path}`: Full path of the file
- `{file_size}`: Size of the file (dynamically switches unit Mb, Gb, etc.)
- `{file_date format="%D %m %Y"}`: File creation date

# Changelog

- version 1.0
- version 1.1: added support for `{file_filename}`
- version 1.2: fixed a bug with special characters in file names