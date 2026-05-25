<?php
class Zend_View_Helper_SetupEditor
{
	function setupEditor($idAreaTexto)
	{
		return '<script type="text/javascript">
		//<![CDATA[
			CKEDITOR.replace(name="' . $idAreaTexto . '",
		{
			skin : "office2003",
			extraPlugins : "uicolor"
		});
		//]]>
		</script>';
	}
}