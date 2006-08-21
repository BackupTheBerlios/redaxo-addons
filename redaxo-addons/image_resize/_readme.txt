/**
 * ImageResize Addon
 * @author vscope new media - www.vscope.at - office[AT]vscope[DOT]at
 * @author public-4u - www.public-4u.de - staab[AT]public-4u[DOT]de
 * @author pergopa kristinus Gbr - www.pergopa.de - jan[AT]pergopa[DOT]de
 *
 * @package redaxo3
 * @version $Id: _readme.txt,v 1.1 2006/08/21 10:42:37 kills Exp $
 */

<a href="?page=addon&amp;spage=help&amp;addonname=image_resize&amp;mode=changelog">Changelog</a>

<strong>Features:</strong>

 Makes resize of images on the fly, with extra cache of resized images so
 performance loss is extremly small. The image must be located in the folder /files

<strong>Syntax:</strong>
 
   index.php?rex_resize=&lt;width/height&gt;&lt;method&gt;__&lt;filename&gt;
   
<strong>Usage:</strong>
 
 call an image that way 
 &nbsp;&nbsp;<i>index.php?rex_resize=100w__mypic.jpg</i>
 to resize the file <i>mypic.jpg</i> to </i>width</i> of <i>100</i> pixels
 
<strong>supported methods:</strong>
 
   w = width
   h = height
   a = automatic