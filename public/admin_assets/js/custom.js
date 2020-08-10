function toast(msg, title, type, timer){
    var opts = {
        title: title,
        html: msg,
        type: type,
        confirmButtonClass: "btn btn-confirm mt-2"
    };
    if(timer !== undefined){
        opts.timer = timer;
    }
    Swal.fire(opts);
}

function initAjaxForm() {
    var submit_btn = $("form.ajaxForm").data('submitBtn'),
        disabled_btn = '<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Loading...';

    if (submit_btn === null || submit_btn === undefined || submit_btn === '') {
        submit_btn = 'Submit';
    }

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf_token"]').attr('content')
        }
    })
    $("form.ajaxForm button[type='submit']").removeAttr('disabled');
    $("form.ajaxForm").parsley();
    $("form.ajaxForm").ajaxForm({
        dataType: "json",
        beforeSubmit: function () {
            page_loader('show');

            $("button[type=submit]").attr("disabled", 'disabled').html(disabled_btn);
            
            if ($("form.ajaxForm").hasClass('popup')) {
                r = confirm("Are you sure?");
                if (!r) {
                    $("button[type=submit]").removeAttr("disabled").html(submit_btn);
                    page_loader('hide');
                    return false;
                }
            }
            
        },
        complete: function () {
            page_loader('hide');
        },
        error: function (data, err, msg) {
            page_loader('hide');
            $("button[type=submit]").removeAttr("disabled").html(submit_btn);
            ajaxErrorHandling(data, msg);
        },
        success: function (data) {
            $("button[type=submit]").removeAttr("disabled").html(submit_btn);
            
            if (data['error'] !== undefined) {
                toast(data['error'], "Error!", 'error');
            } else if (data['success'] !== undefined) {
                toast(data['success'], "Success!", 'success', 1200);
                $('form.ajaxForm').clearForm();
            } else if (data['info'] !== undefined) {
                toast(data['info'], "Info", 'info');
            }

            if (data['errors'] !== undefined) {
                multiple_errors_ajax_handling(data['errors']);
            }

            if (data['redirect'] !== undefined) {
                window.setTimeout(function () {
                    window.location = data['redirect'];
                }, 2000);
            }

            if (data['redirect_new'] !== undefined) {
                window.open(data['redirect_new']);
            }

            if (data['reload'] !== undefined) {
                window.setTimeout(function () {
                    window.location.reload(true);
                }, 1000);
            }
        }
    });
}

function ajaxRequest(_self) {
    var href = $(_self).data('url');
    var nopopup = $(_self).hasClass('nopopup');
    var btn_txt = $(_self).data("btnText");
    var data_msg = $(_self).data("msg");
    if (!nopopup) {
        Swal.fire({
            title: "Are you sure?",
            text: (data_msg && data_msg != '') ? data_msg : "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: (btn_txt && btn_txt != '') ? btn_txt : "Yes, confirm it!"
        }).then(function (t) {
            if (t.value){
                run_ajax(href);
            }
        });
    }else{
        run_ajax(href);
    }
}


function run_ajax(href){
    page_loader('show');
    $.ajax({
        url: href,
        dataType: "json",
        complete: function () {
            page_loader('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            ajaxErrorHandling(jqXHR, errorThrown);
        },
        success: function (data) {
            if (data['error'] !== undefined) {
                toast(data['error'], "Error!", 'error');
            } else if (data['success'] !== undefined) {
                toast(data['success'], "Success!", 'success', 1200);
            } else if (data['info'] !== undefined) {
                toast(data['info'], "Info", 'info');
            }

            if (data['errors'] !== undefined) {
                multiple_errors_ajax_handling(data['errors']);
            }

            if (data['reload'] !== undefined) {
                setTimeout(function () {
                    window.location.reload(true);
                }, 400);
            }

            if (data['redirect'] !== undefined) {
                setTimeout(function () {
                    window.location = data['redirect'];
                }, 400);
            }
        }
    });
}

function getAjaxRequests(url, params, method, callback,loader=true) {
    page_loader('show');

    var params = (!params && params != '') ? {} : params;
    var method = (!method && method != '') ? "POST" : method;

    $.ajax({
        url: url,
        method: method,
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        data: params,
        dataType: "json",
        complete: function () {
            page_loader('hide');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            page_loader('hide');
            ajaxErrorHandling(jqXHR, errorThrown);
        },
        success: function (data) {
            if (data['reload'] !== undefined) {
                setTimeout(function () {
                    window.location.reload(true);
                }, 400);
                return false;
            }
            if (data['redirect'] !== undefined) {
                setTimeout(function () {
                    window.location = data['redirect'];
                }, 400);
                return false;
            }
            if (data['error'] !== undefined) {
                toast(data['error'], "Error!", 'error');
                return false;
            }

            if (data['errors'] !== undefined) {
                multiple_errors_ajax_handling(data['errors']);
            }
            callback(data);
        }
    });
}


function ajaxErrorHandling(data, msg){
    if (data.hasOwnProperty("responseJSON")) {
        var resp = data.responseJSON;
        
        if (resp.message == 'CSRF token mismatch.') {
            toast("Page has been expired and will reload in 2 seconds", "Page Expired!", "error");
            setTimeout(function () {
                window.location.reload();
            }, 2000);
            return;
        }

        if (resp.error) {
            var msg = (resp.error == '') ? 'Something went wrong!' : resp.error;
            toast(msg, "Error!", "error");
            return;
        }

        if (resp.message != 'The given data was invalid.') {
            toast(resp.message, "Error!", "error");
            return;
        }

        multiple_errors_ajax_handling(resp.errors);
    } else {
        toast(msg + "!", "Error!", 'error');
    }
    return;
}

function multiple_errors_ajax_handling(errors){
    $_html = "";
    for (error in errors) {
        $_html += "<p class='m-1 text-danger'><strong>" + errors[error][0] + "</strong></p>";
    }
    toast($_html, "Error!", 'error');
    return false;
}

function logout(e){
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "By this action you will be logged out are you sure you want to continue!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, log me out!"
    }).then(function (t) {
        if (t.value){
            document.getElementById('logout-form').submit();
        }
    })
}

function adminClearNotifications(e){
    e.preventDefault();
    Swal.fire({
        title: "Are you sure?",
        text: "By this action you will be logged out are you sure you want to continue!",
        type: "warning",
        showCancelButton: !0,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, log me out!"
    }).then(function (t) {
        if (t.value){
            document.getElementById('logout-form').submit();
        }
    })
}


$(document).ready(function () {
    initAjaxForm();

    $(".custom_form").parsley();
    
    if ($('[data-toggle="select2"]').length > 0) {
        $('[data-toggle="select2"]').select2()
    }

    if ($('[data-toggle="input-mask"]').length > 0) {
        $('[data-toggle="input-mask"]').each(function (idx, obj) {
            var maskFormat = $(obj).data("maskFormat");
            var reverse = $(obj).data("reverse");
            if (reverse != null) $(obj).mask(maskFormat, {
                'reverse': reverse
            });
            else $(obj).mask(maskFormat);
        });
    }
    
    
});



function page_loader(status) {
    if (status == 'hide') {

        $('.preloader').hide();
        $('#status').hide();
        return;
    }

    $('.preloader').show();
    $('#status').show();
    return;
}

//dateToYMD(new Date(2018-12-01))
function dateToYMD(date) {
    var strArray = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var d = date.getDate();
    var m = strArray[date.getMonth()];
    var y = date.getFullYear();
    return '' + (d <= 9 ? '0' + d : d) + ' ' + m + ', ' + y;
}




function timeSince(date) {
    let minute = 60;
    let hour   = minute * 60;
    let day    = hour   * 24;
    let month  = day    * 30;
    let year   = day    * 365;

    let suffix = ' ago';

    let elapsed = Math.floor((Date.now() - date) / 1000);

    if (elapsed < minute) {
        return 'just now';
    }

    // get an array in the form of [number, string]
    let a = elapsed < hour  && [Math.floor(elapsed / minute), 'minute'] ||
            elapsed < day   && [Math.floor(elapsed / hour), 'hour']     ||
            elapsed < month && [Math.floor(elapsed / day), 'day']       ||
            elapsed < year  && [Math.floor(elapsed / month), 'month']   ||
            [Math.floor(elapsed / year), 'year'];

    // pluralise and append suffix
    return a[0] + ' ' + a[1] + (a[0] === 1 ? '' : 's') + suffix;
}

$("#notification_dropdown").on('shown.bs.dropdown', function () {
    var url = __site_url__ + '/notification/mark_all_read';
    $.get(url, function(data){});
});