#Description:
Simple EE2 only plugin returning a bunch of infos about any given file (file name / server path / size / date)

#Example:
	{exp:jco_getfileinfos filename="{blogpost_image}"}
		{file_name}
		{file_extension}
		{file_path}
		{file_size}
		{file_date format="%l %F %Y"}
	{/exp:jco_getfileinfos}

#Parameter
`file="{custom field}"`

*Mandatory
*The custom field holding the file you want information about or a full server path to a file

#Variables

`{file_name}`
Filename

`{file_extension}`
File extension (without the dot)

`{file_path}`
Full path of the file

`{file_size}`
Size of the file (dynamically switches unit Mb, Gb, etc.)

`{file_date format="%D %m %Y"}`
Creation date of the file