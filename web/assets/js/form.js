$(document).ready(function () {
    const SUCCESS = 'success';
    const DANGER = 'danger';

    $("form").submit(function (event) {
        event.preventDefault()
        $(".form-group").removeClass("has-error");
        $(".help-block").remove();
        $('.turnover-per-brand-report a').remove()
        $('.turnover-per-day-report a').remove()

        let formData = {
            startDate: $("#startDate").val(),
            csrfToken: $("#token").val(),
        }

        function downloadableLink(reportName) {
            return '<a target="_blank" href="./reports/' + reportName + '">Download ' + reportName + '</a>\n';
        }

        function generateAlert(type, message) {
            let title = type === SUCCESS ? 'Success!' : 'Error!';

            return '<div class="alert alert-' + type.toLowerCase() + ' alert-dismissible fade show mt-5" role="alert">\n' +
                '    <strong>' + title + '</strong> ' + message + '\n' +
                '    <button type="button" class="close" data-dismiss="alert" aria-label="Close">\n' +
                '        <span aria-hidden="true">&times;</span>\n' +
                '    </button>\n' +
                '</div>';
        }

        $.ajax({
            type: "POST",
            url: "/reports",
            data: formData,
            dataType: "json",
            encode: true
        }).done(function (data) {
            if (data.success) {
                $('.show-alert').html(generateAlert(SUCCESS, 'Report created successfully.'))

                $('.turnover-per-brand-report').append(downloadableLink(data.reports.turnoverPerBrandReport));
                $('.turnover-per-day-report').append(downloadableLink(data.reports.turnoverPerDayReport));

                return;
            }

            if (data.message) {
                $('.show-alert').html(generateAlert(DANGER, data.message))

                return;
            }

            if (data.errors.startDate) {
                $("#date-group").addClass("has-error");
                $("#date-group").append(
                    '<div class="help-block">' + data.errors.startDate + "</div>"
                );
            }
        }).fail(function (data) {
            $('.show-alert').html(generateAlert(DANGER, 'Could not reach server, please try again later.'))
        });
    })
})
