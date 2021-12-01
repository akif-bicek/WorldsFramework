<nav id="mySidebar" class="sidebar bg-dark shadow-4-strong">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
    <?php $lister->start("navbar"); ?>
    <a class="btn btn-block btn-success btn-lg" data-id="{id}" href="{link}">{text}</a>
    <?php $lister->end(); ?>
</nav>