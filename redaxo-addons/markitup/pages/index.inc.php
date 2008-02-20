<?php

/**
 * TinyMCE Addon
 *
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 *
 * @author Dave Holloway
 * @author <a href="http://www.GN2-Netwerk.de">www.GN2-Netwerk.de</a>s
 *
 * @package redaxo4
 * @version $Id: index.inc.php,v 1.1 2008/02/20 13:50:47 kills Exp $
 */

include $REX['INCLUDE_PATH']."/layout/top.php";

rex_title($I18N_A287->msg('title'));

$mdl_in =<<<EOD
<textarea class="markitup" name="VALUE[1]" cols="80" rows="10">
REX_HTML_VALUE[1]
</textarea>
EOD;

$mdl_out =<<<EOD
REX_HTML_VALUE[1]
EOD;

?>

<div class="rex-addon-output">
	<h2><?php echo $I18N_A287->msg('moduleinput'); ?></h2>

	<div class="rex-addon-content">
		<?php highlight_string($mdl_in); ?>
	</div>

  <h2><?php echo $I18N_A287->msg('moduleoutput'); ?></h2>

	<div class="rex-addon-content">
		<?php highlight_string($mdl_out); ?>
	</div>

	<div class="rex-addon-content">
		<p>
			<a href="http://www.public-4u.de">Public-4u e.K.</a>
		</p>
	</div>

<?php

include $REX['INCLUDE_PATH']."/layout/bottom.php";

?>