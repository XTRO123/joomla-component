<?php

/**
* @package     Joomla.Site
* @subpackage  com_atoms
*
* @copyright   Copyright (C) Atom-S LLC. All rights reserved.
* @license     GNU General Public License version 3 or later; see LICENSE.txt
*/

/** @var $this AtomsViewPayment */
defined( '_JEXEC' ) or die; // No direct access

?>
<div class="item-page <?php echo $this->pageclass_sfx; ?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page-header">
		<h1> <?php echo $this->escape($this->params->get('page_heading')); ?> </h1>
	</div>
	<?php endif; ?>
        
    <div id="atom-<?php echo $this->apiKey ?>">Загрузка...</div>
    <script type="text/javascript">
        var w<?php echo md5($this->apiKey) ?>;
        (function(d, t) {
          var s = d.createElement(t), options = {"host":"<?php echo $this->apiHost; ?>","frameUrl":"/package_constructor","setPathFromQueryParams":true,"apiKey":"<?php echo $this->apiKey ?>","api_version":"<?php echo $this->apiVersion ?>","autoResize":true,"height":"2000px","width":"100%","minHeight":0};
          s.src = 'https://atom-s.com/widgets/embedder.js';
          s.onload = s.onreadystatechange = function() {
            var rs = this.readyState; if (rs) if (rs != 'complete') if (rs != 'loaded') return;
            try {
              w<?php echo md5($this->apiKey) ?> = new WidgetEmbedder();
              w<?php echo md5($this->apiKey) ?>.initialize(options);
              w<?php echo md5($this->apiKey) ?>.display();
            } catch (e) {
                console.log(e);
            }
          };
          var scr = d.getElementsByTagName(t)[0], par = scr.parentNode; par.insertBefore(s, scr);
        })(document, 'script');
    </script>
</div>