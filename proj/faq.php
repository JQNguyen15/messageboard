<?php require_once('includes/include.php'); ?>

<?php generateHeader('FAQ Page'); ?>


<?php logoNav(); ?>
      
    <script type="text/javascript">
      /*
        This will dynamically resize the window depending on the size of the iframe
        This avoids there being scroll bars in the iframe
      */
      function resizeIframe(obj) {
        obj.style.height = obj.contentWindow.document.body.scrollHeight + 'px';
      }
    </script>
    
        <div class="well well-lg">
            <div class="row">
                <div class="col-md-12">
                    <iframe frameborder="0" scrolling="no" src="FAQ.xml" onload="resizeIframe(this)"></iframe>
                </div>
            </div>
        </div>
        
<?php generateFooter(); ?>        