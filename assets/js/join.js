jQuery(document).ready(function() {

    (function($) {

        var jq_Form = $('form[name=InviteForm]');
        var jq_EmailField = jq_Form.find('input[type=email][name=email]');
        var jq_SubmitBtn = jq_Form.find('button[type=submit]');

        jq_Form.on('submit', function(e) {
            e.preventDefault();

            var jq_This = $(this);
            var serialized = jq_Form.serialize();

            /**
             * Pre-XHR actions
             */
            jq_SubmitBtn.attr('disabled', 'disabled');
            jq_EmailField.attr('disabled', 'disabled');

            // Do the ajax
            var _action = jq_This.attr('action');
            console.log(serialized);
            $.ajax({
                data: serialized,
                method: jq_This.attr('method'),
                url: _action,
                success: function($res) {
                    if ($res.success) {
                        swal({
                            title: "Invitation has been sent!",
                            text: "Please check your e-mail for the link",
                            timer: 3000,
                            showConfirmButton: false
                        });
                        setTimeout(function() {
                            window.location.reload();
                        }, 3000);
                        return;
                    }
                    swal("Failed", $res.message, "error")
                },
                error: function($xhr, $error, $status) {
                    swal($error, $status, "error");
                    console.log($error + ' -- ' + $status);
                },
                complete: function() {
                    jq_SubmitBtn.removeAttr('disabled');
                    jq_EmailField.removeAttr('disabled');
                }
            })

        });

    })(jQuery);

});