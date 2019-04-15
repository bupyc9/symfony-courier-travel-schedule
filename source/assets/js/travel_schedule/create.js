$(function () {
    let moment = require('moment');
    let $success = $('<div />').addClass('success').text('Travel schedule successfully added');

    $('.travel-schedule-popup-js').magnificPopup({type: 'ajax'});

    $('body')
        .on('change', '.date-departure-js', function () {
            let $self = $(this);

            if ($self.val() === '') {
                return;
            }

            let dateDeparture = moment($self.val());
            let travelTime = $('.region-js').find(':selected').data('travelTime');

            let $dateArrival = $('.date-arrival-js');
            $dateArrival.val(dateDeparture.add(travelTime, 'd').format('DD/MM/YYYY'));
        })
        .on('change', '.region-js', function () {
            let $self = $(this);
            let $dateDeparture = $('.date-departure-js');

            if ($dateDeparture.val() === '') {
                return;
            }

            let dateDeparture = moment($dateDeparture.val());
            let travelTime = $self.find(':selected').data('travelTime');

            let $dateArrival = $('.date-arrival-js');
            $dateArrival.val(dateDeparture.add(travelTime, 'd').format('DD/MM/YYYY'));
        })
        .on('submit', '.travel-schedule-form-js form', function (e) {
            e.preventDefault();

            let $self = $(this);
            let sendingRequest = $self.data('sendingRequest');
            let $container = $self.closest('.travel-schedule-form-js');

            if (sendingRequest) {
                return;
            }
            $self.data('sendingRequest', true);

            $.ajax({
                data: $self.serialize(),
                dataType: 'json',
                type: $self.attr('method'),
                url: $self.attr('action'),
                success: function (response) {
                    $self.data('sendingRequest', false);

                    if (response.success) {
                        $container.html($success);
                        $.magnificPopup.instance.close = function () {
                            location.reload();
                        };

                        return;
                    }

                    $container.find('.errors').remove();

                    let errors = '<ul class="errors">';
                    for (let items in response.errors) {
                        for (let item in items) {
                            errors += '<li>' + item + '</li>';
                        }
                    }
                    errors += '</ul>';

                    $container.prepend(errors);
                },
                error: function () {
                    $self.data('sendingRequest', false);
                }
            });
        })
});