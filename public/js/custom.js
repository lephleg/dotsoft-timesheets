/**
 * Created by lephleg on 16/09/2016.
 */

$(function () {
    // $("#users").DataTable();
    $('#users').DataTable({
        "paging": false,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": false,
        "autoWidth": true,
        "columnDefs": [
            { "orderable": false, "targets": 1 },
            { "orderable": false, "targets": 4 },
            { "orderable": false, "targets": 5 },
            { "orderable": false, "targets": 6 },
        ],
    });

    $('#dailyDotsoftCalendar').fullCalendar({
        titleFormat: 'dddd, Do MMMM YYYY',
        editable: false,
        aspectRatio: 2.5,
        minTime: '06:00',
        maxTime: '21:00',
        customButtons: {
            myDatepicker: {
                text: 'custom!',
                icon: 'calendar',
            }
        },
        header: {
            left: '',
            center: 'title',
            right: 'today myDatepicker prev,next'
        },
        defaultView: 'timelineDay',
        views: {
            timelineThreeDays: {
                type: 'timeline',
                duration: { days: 3 }
            }
        },
        nowIndicator: true,
        resourceAreaWidth: '220px',
        resourceColumns: [
            {
                labelText: 'User',
                field: 'user'
            },
            {
                labelText: 'Total Hours',
                field: 'total'
            }
        ],
        resources:
            { url: 'users/resources' },
        events:
            { url: '/events/daily' },

        eventClick: function(calEvent, jsEvent, view) {
            $(this).popover({
                html: true,
                placement: 'top',
                title: calEvent.title,
                content: calEvent.start.format('HH:mm:ss')+"<br>"+calEvent.end.format('HH:mm:ss'),
            });
            $(this).popover('show');
        },
        navLinkDayClick: function(date, jsEvent) {
            alert('bingo!');
            console.log('day', date.format()); // date is a moment
            console.log('coords', jsEvent.pageX, jsEvent.pageY);
        }
    });

    $('.fc-myDatepicker-button').datepicker({
        autoclose: true
        })
        .on('changeDate', function(e) {
            var d = new Date(e.date);
            $('#dailyDotsoftCalendar').fullCalendar('gotoDate', d);
            $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('.fc-prev-button').click(function(){
        $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('.fc-next-button').click(function(){
        $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('.fc-today-button').click(function(){
        $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
    });

    setInterval(function(){
        view = $('#dailyDotsoftCalendar').fullCalendar('getView').start.utcOffset(3);
        now = moment();
        if (view.isSame(now,'day')) {
            $('#dailyDotsoftCalendar').fullCalendar('refetchEvents');
            $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
        }
    },  1*60000);

});




