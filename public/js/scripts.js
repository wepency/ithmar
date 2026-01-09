if('serviceWorker' in navigator){
    console.log('Service working');
}

$(document).ready(function() {

    let startInterval;

    let codeInterval = function (){
        let still = $('body').find('.reverify-counter').data('seconds');

        console.log('still is: '+still)

        if (still>1){
            $('.reverify-counter').data('seconds', still-1);

            $('.send-counter').text(' بعد '+ still-- +' ثانية');
        }else{
            $('.resend-wrap').html('<button class="link valid reverify-counter" id="resend-code" type="button" data-seconds="0">إعادة إرسال كود التفعيل</button>');
            clearInterval(startInterval);
        }
    };

    $('#ithmar-preloader').fadeOut();

    $('.dismiss, .overlay').on('click', function() {
        $('.sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });

    $('.open-menu').on('click', function(e) {
        e.preventDefault();
        const sidebar = $(this).data('target');

        $('#'+sidebar).addClass('active');
        $('.overlay').addClass('active');
        // close opened sub-menus
        $('.collapse.show').toggleClass('show');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    $('.nice-select').niceSelect();

    $('.open-filter').on('click', function (e){
        e.preventDefault();
        const filter = $(this).data('filter');
        const cardOpen = $(this).parents('.card').find('.card-body');
        const filterButton = $('.open-filter');

        $(filterButton).data('filter', filter === 'closed' ? 'open' : 'closed').html(filter === 'closed' ? 'إخفاء الفلاتر' : 'إظهار الفلاتر');
        if (filter === 'closed'){
            cardOpen.slideDown();
        }else{
            cardOpen.slideUp();
        }
    });

    /* Contract Validation */

    /* FrontEnd Bond */
    $('#contract-code-field').on('change', function (e){
        e.preventDefault();

        const contractCode = $(this).val();

        const Name = $('#bond_name'),
              Value = $('#bond_value'),
              ContractCodeHeader = $('#contract-code'),
              ContractCodeField = $('#contract-code-field'),
              ValueInString = $('#bond_value_in_string'),
              BondFor = $('#bond_for');

        $.post('/api/getContractForBond', {code: contractCode}).done(function (data){
            Name.val(data.name);
            Value.val(data.value);
            // ContractCodeHeader.val(contractCode);
            // ContractCodeField.val(contractCode);
            ValueInString.val();
            BondFor.val(data.for);
        }).fail(function () {

        });
    })

    /* Verify Number */
    $('#verify-number').on('click', function (e){
        e.preventDefault();

        $('.code-input').val('')

        const phonenumber = $('#phonenumber').val();

        $(this).attr('disabled', true)

        $.post('/api/send-sms-token/'+phonenumber).done(function (data){
            $('.form-box').hide()
            $("#phonenumber-value").text(phonenumber)
            $("#phone-verification-2").show()
            $('.code-input:first-child').focus()
            startInterval = setInterval(codeInterval, 1000)
        })
    })

    /* Code Inputs */
    $('.code-input').keyup(function (){
        let inputCount = 0,
            code = [];

        $('.code-input').each(function (){
            if ($(this).val() !== ''){
                code.push($(this).val());
                inputCount++;
            }
        });

        if ($(this).is(':last-child') && inputCount >= 6){
            const $this = $(this);

            // $('.code-input').focus().val('');
            $('.code-input').attr('disabled', true);
            $('#verify-code').attr('disabled', true).addClass('button-loading');
            const contract = $('#verify-number').data('contract');

            $.post('/api/confirm-validation', {token: code.join(''), phonenumber: $('#phonenumber').val(), code: $('#confirmation-code').data('form-code')}).done(function (data, statusText, xhr){
                if (xhr.status === 200){
                    window.location.href = '/contract/'+ contract +'/pay-validate';
                }
            }).fail(function(xhr, status, error) {
                $('#code-error').html(xhr.responseJSON);
                $('.code-input').attr('disabled', false);
                $('#verify-code').attr('disabled', false).removeClass('button-loading');
            });
        }
    });

    $('#verify-code').on('click', function (e){
        e.preventDefault();

        let inputCount = 0,
            code = [];

        $('.code-input').each(function (){
            if ($(this).val() !== ''){
                code.push($(this).val());
                inputCount++;
            }
        });

        $('.code-input').attr('disabled', true);
        $(this).attr('disabled', true).addClass('button-loading');
        const contract = $('#verify-number').data('contract');

        $.post('/api/confirm-validation', {token: code.join(''), phonenumber: $('#phonenumber').val(), code: $('#confirmation-code').data('form-code')}).done(function (data, statusText, xhr){
            if (xhr.status === 200){
                window.location.href = '/contract/'+ contract +'/pay-validate';
            }
        }).fail(function(xhr, status, error) {
            $('#code-error').html(xhr.responseJSON);
            $('.code-input').attr('disabled', false);
            $('#verify-code').attr('disabled', false).removeClass('button-loading');
        });
    })

    $('body').on('click', '.link.valid:not(:disabled)',function (e){
        e.preventDefault();
        console.log('Clicked')
    });

    $('body').on('click', '#resend-code', function (e){
        e.preventDefault();
        const phonenumber = $('#phonenumber').val(),
              $this = $(this)

        $this.attr('disabled', true)

        $.post('/api/send-sms-token/'+phonenumber).done(function (data, statusText, xhr){
            if (xhr.status === 200){
                $('.resend-wrap').html('<button class="link deactivated reverify-counter" data-seconds="60" type="button" disabled>ارسال كود أخر <span class="send-counter"> بعد 60 ثانية</span></button>');
                setInterval(codeInterval, 1000)
                // $this.attr('disabled', false).data('seconds', 60).removeClass('valid').addClass('deactivated')
            }
        }).fail(function (){

        })
    })

    $('#edit-phone').on('click', function (){
        $('#phone-verification-1').show()
        $('#phone-verification-2').hide()
        $('#phonenumber').focus();
        $('#verify-number').attr('disabled', false);
        $('#code-error').html('')
    });

    // $('#')
});

function formatPhone(DOM, e){
    $('#phone-error').html('');

    const $myInput = $(DOM);
    const $myInputVal = $(DOM).val();

    if ($myInputVal.substring(0,1) !== '5'){
        $($myInput.addClass('has-error'))
        $('#phone-error').html('يجب ان يكون أول رقم 5 ، برجاء التأكد من إدخال رقم صحيح.');
    }

    if ($myInputVal === ''){
        $($myInput.addClass('has-error'))
        $('#phone-error').html('يجب أن لا يكون هذا الحقل فارغاََ ، و ان تكون القيمة أرقاماََ فقط.');
    }

    if($myInputVal.length > 8){
        $myInput.val($myInputVal.substring(0, 9));

        if ($myInputVal.substring(0,1) === '5'){
            $($myInput.addClass('valid'))
            $('#verify-number').attr('disabled', false)
            $($myInput.removeClass('has-error'))
            $('#phone-error').html('')
        }
    }else {
        $($myInput.removeClass('valid'))
        $('#verify-number').attr('disabled', true)
    }
}

/*  jQuery Nice Select - v1.0
    https://github.com/hernansartorio/jquery-nice-select
    Made by Hernán Sartorio  */
!function(e){e.fn.niceSelect=function(t){function s(t){t.after(e("<div></div>").addClass("nice-select").addClass(t.attr("class")||"").addClass(t.attr("disabled")?"disabled":"").attr("tabindex",t.attr("disabled")?null:"0").html('<span class="current"></span><ul class="list"></ul>'));var s=t.next(),n=t.find("option"),i=t.find("option:selected");s.find(".current").html(i.data("display")||i.text()),n.each(function(t){var n=e(this),i=n.data("display");s.find("ul").append(e("<li></li>").attr("data-value",n.val()).attr("data-display",i||null).addClass("option"+(n.is(":selected")?" selected":"")+(n.is(":disabled")?" disabled":"")).html(n.text()))})}if("string"==typeof t)return"update"==t?this.each(function(){var t=e(this),n=e(this).next(".nice-select"),i=n.hasClass("open");n.length&&(n.remove(),s(t),i&&t.next().trigger("click"))}):"destroy"==t?(this.each(function(){var t=e(this),s=e(this).next(".nice-select");s.length&&(s.remove(),t.css("display",""))}),0==e(".nice-select").length&&e(document).off(".nice_select")):console.log('Method "'+t+'" does not exist.'),this;this.hide(),this.each(function(){var t=e(this);t.next().hasClass("nice-select")||s(t)}),e(document).off(".nice_select"),e(document).on("click.nice_select",".nice-select",function(t){var s=e(this);e(".nice-select").not(s).removeClass("open"),s.toggleClass("open"),s.hasClass("open")?(s.find(".option"),s.find(".focus").removeClass("focus"),s.find(".selected").addClass("focus")):s.focus()}),e(document).on("click.nice_select",function(t){0===e(t.target).closest(".nice-select").length&&e(".nice-select").removeClass("open").find(".option")}),e(document).on("click.nice_select",".nice-select .option:not(.disabled)",function(t){var s=e(this),n=s.closest(".nice-select");n.find(".selected").removeClass("selected"),s.addClass("selected");var i=s.data("display")||s.text();n.find(".current").text(i),n.prev("select").val(s.data("value")).trigger("change")}),e(document).on("keydown.nice_select",".nice-select",function(t){var s=e(this),n=e(s.find(".focus")||s.find(".list .option.selected"));if(32==t.keyCode||13==t.keyCode)return s.hasClass("open")?n.trigger("click"):s.trigger("click"),!1;if(40==t.keyCode){if(s.hasClass("open")){var i=n.nextAll(".option:not(.disabled)").first();i.length>0&&(s.find(".focus").removeClass("focus"),i.addClass("focus"))}else s.trigger("click");return!1}if(38==t.keyCode){if(s.hasClass("open")){var l=n.prevAll(".option:not(.disabled)").first();l.length>0&&(s.find(".focus").removeClass("focus"),l.addClass("focus"))}else s.trigger("click");return!1}if(27==t.keyCode)s.hasClass("open")&&s.trigger("click");else if(9==t.keyCode&&s.hasClass("open"))return!1});var n=document.createElement("a").style;return n.cssText="pointer-events:auto","auto"!==n.pointerEvents&&e("html").addClass("no-csspointerevents"),this}}(jQuery);
