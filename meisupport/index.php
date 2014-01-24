<?php
/**
 * @package     Joomla.Site
 * @subpackage  Templates.protostar
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$this->direction = $doc->direction;

$menu = & JSite::getMenu();
// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" ) $fullWidth = 1;
else  $fullWidth = 0;

JHtml::_('bootstrap.framework');
$doc->addScript('templates/' .$this->template. '/js/template.js');
$doc->addScript('templates/' .$this->template. '/js/z-language.js');
$doc->addScript('templates/' .$this->template. '/js/z-schubert.js');
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/z-schubert.css');

JHtml::_('bootstrap.loadCss', false, $this->direction);
$user = JFactory::getUser(); /*Add current user information*/

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
	<meta name="viewport" content="width=device-width, user-scalable=false;">
<?php
  $lang = JFactory::getLanguage()->getLocale();
  $lang = str_replace('_','-',$lang[2]);
  echo "<script>var cvlang = '".$lang."';</script>";
?>
	<jdoc:include type="head" />
	<?php if ($this->params->get('templateColor')) { ?>
		<style type="text/css">
			body.site {border-top: 3px solid <?php echo $this->params->get('templateColor');?>; background-color: <?php echo $this->params->get('templateBackgroundColor');?> }
			a {color: <?php echo $this->params->get('templateColor');?>; }
			.navbar-inner, .nav-list > .active > a, .nav-list > .active > a:hover, .dropdown-menu li > a:hover, .dropdown-menu .active > a, .dropdown-menu .active > a:hover, .nav-pills > .active > a, .nav-pills > .active > a:hover, .btn-primary {background: <?php echo $this->params->get('templateColor');?>; }
			.navbar-inner {-moz-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2); -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2); box-shadow: 0 1px 3px rgba(0, 0, 0, .25), inset 0 -1px 0 rgba(0, 0, 0, .1), inset 0 30px 10px rgba(0, 0, 0, .2); }
		</style>
	<?php } ?>
	<link rel="stylesheet" href="/templates/meisupport/alerts/css/themes.css"><script src="/templates/meisupport/alerts/alerts.jquery.js"></script>
  <!--[if IE]>
      <script>
        if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) { 
           var ieversion=new Number(RegExp.$1);
           if (ieversion<=8)    setTimeout(function() { pagealert(vLanguage[cvlang][2]['outdated'],{'class':' fa-a fa-a-exclamation','theme':'red','delay':0}); },200);
        }
      </script>
  <![endif]-->
</head>

<body class="site <?php echo $option . ' view-' . $view . ($layout ? ' layout-' . $layout : ' no-layout') . ($task ? ' task-' . $task : ' no-task') . ($itemid ? ' itemid-' . $itemid : '') . ($params->get('fluidContainer') ? ' fluid' : ''); ?>">
  <div id="aboveNav">
    <a href="/"><img src="images/logo.png" alt="MEI Logo" ></a>
    <div class="rounded language"> Select Language <b>&#8669;</b> </div>
  </div>

  <?php if ($this->countModules('nav-top')) : ?>
    <div id="nav" class="mainnav rounded">
    	<div id="nav-active-bg"></div>
    	<div id="nav-mobile-call" style="display:none;">Menu <img src="images/mobile.png" width="30"></div>
      <jdoc:include type="modules" name="nav-top" style="none" />
    </div>
  <?php endif; ?>

  <div class="body rounded">
  	<?php if ($this->countModules('breadcrumbs')) : ?>
    	<div class='breadcrumbs'>
    	<jdoc:include type="modules" name="breadcrumbs" style="none" />
    	</div>
  	<?php endif; ?>
    <div id="main-article"  <?php if ($this->countModules('login-box') == 0) echo' style="width:100%;" '; ?> >
      <jdoc:include type="component" />
    </div>
    <div id="body-login">
      <jdoc:include type="modules" name="login-box" style="none" />
      <?php if ($menu->getActive() == $menu->getDefault() && $user->id > 0) include_once('templates/'.$this->template.'/includes/get-user-icon.php'); ?>
    </div>
    <div style="clear:both;"></div>
  </div>
 <?php if ($this->countModules('content-bottom') || $this->countModules('nav-bottom')) : ?>
  <div class="body rounded">
    <div id="second-article">
      <?php if ($this->countModules('nav-bottom')) : ?>
      <div id="product-nav">
        <jdoc:include type="modules" name="nav-bottom" style="none" />
        <div style="clear:both;"></div>
      </div>
      <?php include_once('templates/'.$this->template.'/includes/get-homepage-products.php');
      endif; ?>
      <jdoc:include type="modules" name="content-bottom" style="none" />
      <div style="clear:both;"></div>
    </div>
  </div>
 <?php endif; ?>
  <footer class="footer"  id="footer" role="contentinfo">
    <jdoc:include type="modules" name="footer" style="none" /> MEIgroup.com <span>&copy; <?php echo $sitename; ?> <?php echo date('Y');?></span>
</footer>
<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>