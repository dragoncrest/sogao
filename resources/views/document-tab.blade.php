    <div>
        <div>
            <div id="tab-header-1" class="tab-header tab-active" onclick="$.DisplayContent(1);">Tab 1</div>
            <div id="tab-header-2" class="tab-header" onclick="$.DisplayContent(2);">Tab 2</div>
        </div>
       
        <div id="tab-content-1" class="tab-content">
           <?php echo $content;?>
        </div>
        
        <div id="tab-content-2" class="tab-content"></div>
    </div>