<section class="scrollable">
<?php if (!User::is_client() || Project::setting('show_project_calendar',$project_id)) { ?>
    <div class="calendar" id="cal"></div>
        <?php } ?>
</section>
