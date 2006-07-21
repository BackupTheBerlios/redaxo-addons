<style type='text/css'>
<!--
.clear {clear:both;}
div#help { width:760px; position:relative; }

#col1 { float:left; width:360px;}
#col2 { float:left; width:360px; margin-left:20px; background:#f8f8f6;}

ul, li {
	margin:0;
	padding:0;
}

ul { margin:-10px 0 0 30px; }
li { list-style-type:decimal-leading-zero; }

li ul { margin:-10px 0 0 0; }
li ul li { list-style-type:lower-alpha; }
-->
</style>

<div id="help">
<?php
echo '<div id="col1">';
echo nl2br( file_get_contents( dirname( __FILE__). '/_readme.txt'));
echo "</div>";
/*
echo nl2br( file_get_contents( dirname( __FILE__). '/_changelog.txt'));
*/
echo '<div id="col2">';
echo nl2br( file_get_contents( dirname( __FILE__). '/_todo.txt'));
echo '</div><br class="clear" />';
?>

</div>
