/**
 * Created by lephleg on 16/09/2016.
 */

$(function () {

    // get 1rst day of the current month
    var startDate = moment().startOf('month');

    $('#monthlyDotsoftCalendar').fullCalendar({
        titleFormat: 'MMMM YYYY',
        editable: false,
        height: 'auto',
        schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
        customButtons: {
            myMonthDatepicker: {
                text: 'custom!',
                icon: 'calendar',
            }
        },
        header: {
            left: '',
            center: 'title',
            right: 'today myMonthDatepicker prev,next'
        },
        defaultView: 'timelineMonth',
        defaultDate: startDate.format(),
        views: {
            timelineMonth: {
                type: 'timeline',
                duration: { months: 1 }
            }
        },
        resourceAreaWidth: '220px',
        resourceColumns: [
            {
                labelText: 'Employee',
                field: 'employee',
                width: '140px'
            },
            {
                labelText: 'Average time',
                field: 'average',
                width: '80px'
            }
        ],
        resources:
        { url: 'employees/monthly-resources' },

        resourceRender: function(resourceObj, labelTds, bodyTds) {
            var name = $(labelTds[0]).find(".fc-cell-text");
            name.html('<strong><a href="employees/' + resourceObj.id + '">' + resourceObj.name + '</a></strong>');

            var average = $(labelTds[1]).find(".fc-cell-text");
            average.html(resourceObj.average);
            $(labelTds[1]).attr('style', 'text-align: center !important');
        },
    });

    $('.fc-myMonthDatepicker-button').datepicker({
        autoclose: true
    })
        .on('changeDate', function(e) {
            var d = new Date(e.date);
            $('#monthlyDotsoftCalendar').fullCalendar('gotoDate', d);
            $('#monthlyDotsoftCalendar').fullCalendar('refetchResources');
        });

    $('.fc-prev-button').click(function(){
        $('#monthlyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('.fc-next-button').click(function(){
        $('#monthlyDotsoftCalendar').fullCalendar('refetchResources');
    });

    $('.fc-today-button').click(function(){
        $('#monthlyDotsoftCalendar').fullCalendar('refetchResources');
    });
});




