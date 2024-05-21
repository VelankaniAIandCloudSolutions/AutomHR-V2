<script type="text/javascript">
    $(document).ready(function () {
        $('#cal').fullCalendar({
            <?php
            $tasks = Applib::retrieve(Applib::$tasks_table, array('project' => $project_id));
            $events = App::get_by_where('events', array('project'=>$project_id));
            ?>
            eventAfterRender: function(event, element, view) {
                $(element).attr('data-toggle', 'ajaxModal').addClass('ajaxModal');
            },
            header: {
        left: 'prev,next today',
        center: 'title',
        right: 'month,agendaWeek,agendaDay'
      },
            eventSources: [
                {
                    events: [// put the array in the `events` property
                    <?php foreach ($tasks as $key => $t) { ?>
                            {
                                title  : '<?=addslashes($t->task_name)?>',
                                start  : '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($t->due_date)) ?>',
                                url: '<?= base_url('calendar/event/tasks/' . $t->t_id) ?>'
                            },
                    <?php } ?>
                    <?php foreach ($events as $e) { ?>
                            {
                                title  : '<?=addslashes($e->event_name)?>',
                                start  : '<?=date('Y-m-d', strtotime($e->start_date)) ?>',
                                end: '<?= date('Y-m-d', strtotime($e->end_date)) ?>',
                                url: '<?= base_url('calendar/event/events/' . $e->id) ?>',
                                color: '<?=$e->color?>'
                            },
                    <?php } ?>
                    ],
                    color: '#7266BA',
                    textColor: 'white'
                }
                // additional sources
            ]
        });
    });
</script>
