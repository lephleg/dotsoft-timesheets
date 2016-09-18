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
        now: '2016-09-16',
        titleFormat: 'dddd, Do MMMM YYYY',
        editable: false,
        aspectRatio: 1.8,
        minTime: '00:00',
        // maxTime: '21:00',
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
            {
                url: 'users/resources',
                data: {
                    start: '2016-09-16',
                    end: '2016-09-17'
                }
            },
        events:
            { url: '/events/daily'},

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

});




