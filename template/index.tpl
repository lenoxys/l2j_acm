<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{vm_title}</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link href="./template/style.css" rel="stylesheet" type="text/css" />
<!-- BEGIN image -->
	<SCRIPT language="JavaScript" type="text/javascript">
	function reloadImage(img) {
		document.images["L_image"].src=document.images["L_image"].src+"?"+new Date();
	}
	</script>
<!-- END image -->
</head>
<body>
<div class="main">
	<div id="header"><h1>{vm_title_page}</h1></div>
	<div id="container">
    	<div class="container">

{CONTENT}

	<hr class="clear" />

		</div>
	</div>
	<div id="footer"><h5>Copyleft 2007</h5></div>
</div>
</body>
</html>
