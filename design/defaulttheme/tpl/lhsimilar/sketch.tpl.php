<div class="header-list">
            <h1><?=erTranslationClassLhTranslation::getInstance()->getTranslation('similar/sketch','Search by sketch')?></h1>
</div>
    
<div id="PaintWebTarget"></div>
    
<p><a style="position:ansolute;top:-5000px;left:-5000px;"title="Freshalicious" 
      href="http://www.robodesign.ro/marius/my-projects/images/freshalicious"><img 
      id="editableImage" src="<?=erLhcoreClassDesign::design('js/paintweb/bgedit.jpg')?>" alt="Freshalicious"></a></p>
    
    <script type="text/javascript" src="<?=erLhcoreClassDesign::design('js/paintweb/build/paintweb.js')?>"></script>

    <script type="text/javascript">
(function () { 
  function pwInit (ev) {
    var initTime = (new Date()).getTime() - timeStart,str = 'Demo: Yay, PaintWeb loaded in ' + initTime + ' ms! ' + pw.toString();
    if (ev.state === PaintWeb.INIT_ERROR) {
      alert('Demo: PaintWeb initialization failed.');
      return;
    } else if (ev.state === PaintWeb.INIT_DONE) {
      if (window.console && console.log) {
        console.log(str);
      } else if (window.opera) {
        opera.postError(str);
      }
    } else {
      alert('Demo: Unrecognized PaintWeb initialization state ' + ev.state);
      return;
    }
    img.style.display = 'none';
  };
  var img    = document.getElementById('editableImage'),
      target = document.getElementById('PaintWebTarget'),
      timeStart = null,
      pw = new PaintWeb();
      pw.config.guiPlaceholder = target;
      pw.config.imageLoad      = img; 
      pw.config.uploadCanvasURL      = '<?=erLhcoreClassDesign::baseurl('similar/uploadcanvas')?>';
      pw.config.configFile     = 'config-example.json';
      timeStart = (new Date()).getTime();
      pw.init(pwInit);      
})();</script>

<br />

<div id="img-list-search">

</div>