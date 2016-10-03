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
        // aspectRatio: 2.5,
        height: 'auto',
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
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

        resourceRender: function(resourceObj, labelTds, bodyTds) {
            var name = $(labelTds[0]).find(".fc-cell-text");
            name.html('<strong><a href="users/' + resourceObj.id + '">' + resourceObj.user + '</a></strong>');

            var total = $(labelTds[1]).find(".fc-cell-text");
            total.html(resourceObj.total);
            $(labelTds[1]).attr('style', 'text-align: center !important');
        },
        eventRender: function(event, eventElement) {
            if (event.imageurl) {
                eventElement.find("div.fc-content").prepend("<img src='" + event.imageurl +"' width='20' height='20'>");
            }
        },

        eventClick: function(calEvent, jsEvent, view) {
            var duration = moment.duration(calEvent.end.diff(calEvent.start));

            var startIconHtml = '<i class="fa fa-arrow-circle-o-right green-active color-palette"></i>';
            var startTimeHtml = '<span class="timeline-header"> ' + calEvent.start.format('HH:mm:ss') + '</span>';

            var endIconHtml = '<i class="fa fa-arrow-circle-o-left red-active color-palette"></i>';
            var endTimeHtml = '<span class="timeline-header"> ' + calEvent.end.format('HH:mm:ss') + '</span>';

            console.log(calEvent.fake);
            if (calEvent.fake == 'start') {
                startIconHtml = '<i class="fa fa-times red-bright"></i>';
                startTimeHtml = '<span class="timeline-header"> No record </span>';
            }
            if (calEvent.fake == 'end') {
                endIconHtml = '<i class="fa fa-times red-bright"></i>';
                endTimeHtml = '<span class="timeline-header"> No record </span>';
            }

            $(this).popover({
                html: true,
                placement: 'top',
                title:
                "<i class=\"fa fa-clock-o blue-active\"></i>" +
                "<span class=\"event-header\"> " + duration._data.hours + "h " + duration._data.minutes + "m " + "</span>",
                content:
                "<ul class=\"timeline-sm timeline-inverse\">" +
                    "<li>" +
                        startIconHtml +
                        startTimeHtml +
                    "</li>" +
                    "<li>" +
                        endIconHtml +
                        endTimeHtml +
                    "</li>" +
                "</ul>",
                container: 'body'
            });
            $(this).popover('show');
        },
        // navLinkDayClick: function(date, jsEvent) {
        //     alert('bingo!');
        //     console.log('day', date.format()); // date is a moment
        //     console.log('coords', jsEvent.pageX, jsEvent.pageY);
        // },

    });

    $('.fc-myDatepicker-button').datepicker({
        autoclose: true
        })
        .on('changeDate', function(e) {
            var d = new Date(e.date);
            $('#dailyDotsoftCalendar').fullCalendar('gotoDate', d);
            $('#dailyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('#daterange-btn').daterangepicker(
        {
            ranges: {
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            startDate: moment().subtract(29, 'days'),
            endDate: moment()
        },
        function (start, end) {
            $('#daterange-btn span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
        }
    );

    $('#daterange-btn').on('apply.daterangepicker', function(ev, picker) {
        var params = {
            start    : picker.startDate.format('YYYY-MM-DD'),
            end : picker.endDate.format('YYYY-MM-DD')
        };
        window.location.href = window.location.href + '?' + $.param(params);
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

    $(document).on('click', function (e) {
        var
            $popover,
            $target = $(e.target);

        if ($target.hasClass('fc-content') || $target.closest('.fc-timeline-event').length) {
            return;
        }

        //do nothing if there was a click on popover content
        if ($target.hasClass('popover') || $target.closest('.popover').length) {
            return;
        }

        $('.popover').each(function () {
            $popover = $(this);
            if (!$popover.is(e.target) &&
                $popover.has(e.target).length === 0 &&
                $('.popover').has(e.target).length === 0)
            {
                $popover.popover('hide');
            } else {
                //fixes issue described above
                $popover.popover('toggle');
            }
        });
    })

});




