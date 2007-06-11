<?php

/**
 * Guestbook Addon 
 * @author staab[at]public-4u[dot]de Markus Staab
 * @author <a href="http://www.public-4u.de">www.public-4u.de</a>
 * @package redaxo3
 * @version $Id: module.list.inc.php,v 1.1 2007/06/11 11:54:26 kills Exp $
 */
 
function gbook_list_input($elementsPerPage, $paginationsPerPage, $dateFormat, $emailFormat, $encryptEmails)
{
?>
    Einträge pro Seite:
    <br/>
    <input type="text" name="VALUE[1]" value="<?php echo $elementsPerPage ?>" size="5" maxlength="2" style="text-align: center"/>
    <br/><br/>
    Anzahl anzuzgeigender Seiten:
    <br/>
    <input type="text" name="VALUE[2]" value="<?php echo $paginationsPerPage ?>" size="5" maxlength="2" style="text-align: center"/>
    <br/><br/>
    Email-Adressen verschlüsseln:
    <br/>
    <select name="VALUE[5]">
     <option value="0" <?php echo $encryptEmails == '0' ? 'selected="selected"' : '' ?>>Nein</option>
     <option value="1" <?php echo $encryptEmails == '1' ? 'selected="selected"' : '' ?>>Ja</option>
    </select>
    <br/><br/>
    Datums-Format:
    <br/>
    <input type="text" name="VALUE[3]" value="<?php echo $dateFormat ?>" size="45"/>
    <br/>
    siehe <a href="http://php.net/strftime" target="_blank">PHP Manual - strftime()</a>
    <br/><br/>
    Email-Adressen-Format:
    <br/>
    <input type="text" name="VALUE[4]" value="<?php echo $emailFormat ?>" size="45"/>
    <br/><br/>
    Beispiel:<br/>
    max.mustermann@nowhere.no<br/>
    %to% == max.mustermann<br/>
    %domain% == nowhere<br/>
    %tldomain% == no<br/>
    <br/>
    Format-Beispiele:<br/>
    %to%@%domain%.%tldomain%<br/>
    %to%[AT]%domain%[DOT]%tldomain%<br/>
    %to%*AT*%domain%*DOT*%tldomain%<br/>
    <?php


}

function gbook_list_output($elementsPerPage, $paginationsPerPage, $dateFormat, $emailFormat, $encryptEmails, $article_id)
{
  global $REX;

  // hier beliebige mail encrypt funktion einbinden
  include ($REX['INCLUDE_PATH'].'/addons/guestbook/encryptions/mailcrypt2.php');

  $article_id = $article_id;

  // Ausgabe nur im Frontend
  if ($REX['REDAXO'] != true)
  {
    $page = empty ($_GET['page']) ? 0 : $_GET['page'];

    $qry = 'SELECT * FROM '.TBL_GBOOK.' ORDER BY id DESC LIMIT '. ($page * $elementsPerPage).', '.$elementsPerPage;
    $sql = new sql();
    $data = $sql->get_array($qry);

    if (is_array($data))
    {
      echo '<div class="gbook">';
      echo '<div class="pagination">'.gbook_pagination($page, $elementsPerPage, $paginationsPerPage).'</div>';
      foreach ($data as $row)
      {
        $url = strpos($row['url'], 'http://') === false ? 'http://'.$row['url'] : $row['url'];
        $row['url'] = empty ($row['url']) ? 'keine Angabe' : '<a href="'.$url.'">'.$row['url'].'</a>';
        $row['created'] = strftime( $dateFormat, $row['created']);

        $maillabel = gbook_formatemail($row['email'], $emailFormat);
        if ($encryptEmails == '1')
        {
          $maillabel = gbook_encryptmail($maillabel);
          $row['email'] = gbook_encryptmail($row['email']);
        }
        $row['email'] = '<a href="mailto:'.$row['email'].'">'.$maillabel.'</a>';
?>
 <div class="entry">
   <div class="name">
     <div class="label">Name:</div>
     <div class="value"><?php echo stripslashes( $row['author']) ?></div>
   </div>
   <div class="email">
     <div class="label">Email:</div>
     <div class="value"><?php echo $row['email'] ?></div>
   </div>
   <div class="url">
     <div class="label">Homepage:</div>
     <div class="value"><?php echo $row['url'] ?></div>
   </div>
   <div class="time">
     <div class="label">Verfasst:</div>
     <div class="value"><?php echo $row['created'] ?></div>
   </div>
   <div class="text">
     <div class="label">Nachricht:</div>
     <div class="value"><?php echo nl2br( stripslashes( $row['message'])) ?></div>
   </div>
   <?php if ( trim( $row['reply']) != '') : ?>
   <div class="reply">
     <div class="label">Antwort:</div>
     <div class="value"><?php echo nl2br( stripslashes( $row['reply'])) ?></div>
   </div>
   <?php endif; ?>
 </div>
 <?php


      }
      echo '<div class="pagination">'.gbook_pagination($page, $elementsPerPage, $paginationsPerPage).'</div>';
      echo '</div>';
    }

  }
  else
  {
?>
 <b>Die Einträge sind nur im Frontend sichtbar!</b>
 <br/><br/>
 <b>Konfiguration:</b>
 <br/>
 Einträge pro Seite: <b><?php echo $elementsPerPage ?></b>
 <br/>
 Anzahl anzuzgeigender Seiten: <b><?php echo $paginationsPerPage ?></b>
 <br/>
 Emailverschlüsselung: <b><?php echo $encryptEmails == '1' ? 'Ja' : 'Nein' ?></b>
 <br/>
 Datumsformat: <b><?php echo $dateFormat ?></b>
 <br/>
 Emailformat: <b><?php echo $emailFormat ?></b>
 <?php


  }

}
function gbook_pagination($currentPage, $elementsPerPage, $paginationsPerPage)
{
  $qry = 'SELECT count(*) rowCount FROM '.TBL_GBOOK;
  $sql = new sql();
  $data = $sql->get_array($qry);

  $oneSidePaginations = floor($paginationsPerPage / 2);
  //var_dump( $oneSidePaginations);
  $rowCount = $data[0]['rowCount'];
  //var_dump( $rowCount);
  $pageCount = ceil($rowCount / $elementsPerPage) + 1;
  //var_dump( $pageCount);
  if ($currentPage <= $oneSidePaginations)
  {
    $start = 1;
  }
  else
  {
    $start = $currentPage - $oneSidePaginations;
  }
  //var_dump( $start);

  $str = '';

  if ($currentPage != 0)
  {
    $str .= gbook_paginationurl(0, '&laquo;');
  }

  for ($i = 0; $i <= $paginationsPerPage; $i ++)
  {
    if ($start == $pageCount)
    {
      break;
    }
    $str .= gbook_paginationurl($start -1, $start);
    $start ++;
  }

  if ($currentPage != ($pageCount -2))
  {
    $str .= gbook_paginationurl($pageCount -2, '&raquo;');
  }
  return $str;
}

function gbook_paginationurl($page, $label = null)
{
  if ($label === null)
  {
    $label = $page;
  }
  return '<a href="?article_id='.$GLOBALS['article_id'].'&amp;page='.$page.'">'.$label.'</a>';
}

function gbook_formatemail($email, $format)
{
  $iATPos = strpos($email, '@');
  $iDotPos = strrpos($email, '.');

  $to = substr($email, 0, $iATPos);
  $domain = substr($email, $iATPos +1, $iDotPos - $iATPos -1);
  $tldomain = substr($email, $iDotPos +1);

  return str_replace(array ('%to%', '%domain%', '%tldomain%'), array ($to, $domain, $tldomain), $format);
}
?>