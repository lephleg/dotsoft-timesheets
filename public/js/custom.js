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

    $('#calendar').fullCalendar({
        now: '2016-09-07',
        editable: true,
        aspectRatio: 1.8,
        scrollTime: '00:00',
        header: {
            left: 'today prev,next',
            center: 'title',
            right: 'timelineDay,timelineThreeDays,agendaWeek,month'
        },
        defaultView: 'timelineDay',
        views: {
            timelineThreeDays: {
                type: 'timeline',
                duration: { days: 3 }
            }
        },
        resourceAreaWidth: '30%',
        resourceColumns: [
            {
                labelText: 'User',
                field: 'title'
            },
            {
                labelText: 'Day Total',
                field: 'occupancy'
            }
        ],
        resources: [
            { id: 'a', title: 'Auditorium A', occupancy: 40 },
            { id: 'b', title: 'Auditorium B', occupancy: 40, eventColor: 'green' },
            { id: 'c', title: 'Auditorium C', occupancy: 40, eventColor: 'orange' },
            { id: 'd', title: 'Auditorium D', occupancy: 40, children: [
                { id: 'd1', title: 'Room D1', occupancy: 10 },
                { id: 'd2', title: 'Room D2', occupancy: 10 }
            ] },
            { id: 'e', title: 'Auditorium E', occupancy: 40 },
            { id: 'f', title: 'Auditorium F', occupancy: 40, eventColor: 'red' },
            { id: 'g', title: 'Auditorium G', occupancy: 40 },
            { id: 'h', title: 'Auditorium H', occupancy: 40 },
            { id: 'i', title: 'Auditorium I', occupancy: 40 },
            { id: 'j', title: 'Auditorium J', occupancy: 40 },
            { id: 'k', title: 'Auditorium K', occupancy: 40 },
            { id: 'l', title: 'Auditorium L', occupancy: 40 },
            { id: 'm', title: 'Auditorium M', occupancy: 40 },
            { id: 'n', title: 'Auditorium N', occupancy: 40 },
            { id: 'o', title: 'Auditorium O', occupancy: 40 },
            { id: 'p', title: 'Auditorium P', occupancy: 40 },
            { id: 'q', title: 'Auditorium Q', occupancy: 40 },
            { id: 'r', title: 'Auditorium R', occupancy: 40 },
            { id: 's', title: 'Auditorium S', occupancy: 40 },
            { id: 't', title: 'Auditorium T', occupancy: 40 },
            { id: 'u', title: 'Auditorium U', occupancy: 40 },
            { id: 'v', title: 'Auditorium V', occupancy: 40 },
            { id: 'w', title: 'Auditorium W', occupancy: 40 },
            { id: 'x', title: 'Auditorium X', occupancy: 40 },
            { id: 'y', title: 'Auditorium Y', occupancy: 40 },
            { id: 'z', title: 'Auditorium Z', occupancy: 40 }
        ],
        events: [
            { id: '1', resourceId: 'b', start: '2016-09-07T02:00:00', end: '2016-09-07T07:00:00', title: 'event 1' },
            { id: '2', resourceId: 'c', start: '2016-09-07T05:00:00', end: '2016-09-07T22:00:00', title: 'event 2' },
            { id: '3', resourceId: 'd', start: '2016-09-06', end: '2016-09-08', title: 'event 3' },
            { id: '4', resourceId: 'e', start: '2016-09-07T03:00:00', end: '2016-09-07T08:00:00', title: 'event 4' },
            { id: '5', resourceId: 'f', start: '2016-09-07T00:30:00', end: '2016-09-07T02:30:00', title: 'event 5' }
        ]
    });



});