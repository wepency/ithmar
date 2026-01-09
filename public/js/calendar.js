let nav = 0;
let clicked = null;
// let events = localStorage.getItem('events') ? JSON.parse(localStorage.getItem('events')) : [];
let events = JSON.parse(window.events);

let calendar = document.getElementById('calendar')
const weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
const currentDate = window.currentDate;

function load(){
	let dt = new Date(currentDate);

	// if (nav !== 0){
	// 	dt.setMonth(new Date().getMonth() + 1)
	// }

	const day = dt.getDate();
	const month = dt.getMonth();
	const year = dt.getFullYear();

	// console.log(year);
	const firstDayOfMonth = new Date(year, month, 1)
	const daysInMonth = new Date(year, month + 1, 0).getDate()

	document.getElementById('monthDisplay').innerText = dt.toLocaleDateString('ar-EG', {
		month: 'long',
		year: 'numeric'
	})

	const dateString = firstDayOfMonth.toLocaleDateString('en-US', {
		weekday: 'long',
		year: 'numeric',
		month: 'numeric',
		day: 'numeric'
	})

	const paddingDays = weekDays.indexOf(dateString.split(', ')[0])
	calendar.innerText = '';

	let i;

	for (i=1; i <= paddingDays + daysInMonth; i++){
		let daySquare = document.createElement('div');
		daySquare.classList.add('day')

		if (i > paddingDays){
		    const day = ('0' + (i - paddingDays)).slice(-2),
                  monthZero = ('0' + (month + 1)).slice(-2)
                  date = year+'-'+monthZero+'-'+day;

			daySquare.innerText = day;
			$(daySquare).data('date', date);

			// const stringKey = String(date);

            let dataType = '';

			if (events[date]){
			    const datEvent = document.createElement('a');
			    datEvent.classList.add('event');
			    datEvent.classList.add(events[date].type);

                dataType = events[date].type;

			    if (events[date].href != ''){
                    $(datEvent).attr('href', events[date].href);
                }

                if (events[date].id != ''){
                    $(datEvent).data('avail', events[date].id);
                }

			    // datEvent.innerText = events[date].type;
                daySquare.appendChild(datEvent);
            }

			daySquare.addEventListener('click', () => {
			    let dates = []

                const currentDate = $(daySquare);
                //         nextDate = currentDate.next(),
                //         prevDate = currentDate.prev()

                if (dataType == '' || dataType == 'available' || dataType == 'closed'){
                    currentDate.toggleClass('checked')
                }else {
                    Swal.fire({
                        text: 'لايمكنك تعديل فتره ( محجوز / في انتظار العربون / طور التاكيد )',
                        icon: 'error',
                        confirmButtonText: 'حسنا!'
                    });
                }

                $('.day.checked').each(function (){
                    dates.push($(this).data('date'));
                })

                $('.dates-field').val(JSON.stringify(dates));

                if($('.day.checked').length > 0){
                    $('#calendar-buttons').css({'display': 'flex'});
                }else{
                    $('#calendar-buttons').hide()
                }

                if($('.day.checked').length == 1 && $('.day.checked').find('.event').hasClass('available')){
                    $('#edit-price').css({'display': 'flex'});

                    $.post('/api/getAvailableData', {avail: $('.day.checked').find('.event').data('avail')}).done(function (data){
                        console.log(data.date);

                        $('#edit_min_stay').val(data.min_stay);
                        $('#edit_price').val(data.price);
                        $('#edit_prices_form').attr('action', data.href);
                        $('#edit_date').text(data.date);
                        $('#edit_min_stay').niceSelect('update');
                    });

                }else if($('.day.checked').length > 1) {
                    $('#edit-price').hide()
                }
			    // if(currentDate.hasClass('checked') && !nextDate.hasClass('checked')){
                //     currentDate.removeClass('checked')
                // }else {
			    //     if (nextDate.hasClass('checked')){
                //         alert('لا يمكن الغاء تحديد فترة من الدخل.')
                //     }
                //
                //     if((prevDate.hasClass('checked') || $('.day.checked').length === 0))
                //         // daySquare.classList.add('checked')
                //         currentDate.addClass('checked')
                //     else
                //         alert('يجب اختيار فترة متصلة و من بدية التاريخ إلى نهايتة.')
                // }

                // $('.from-field').val($('.day.checked').first().data('date'));
                // $('.from-field-text').text($('.day.checked').first().data('date'));
                // $('.to-field').val($('.day.checked').last().data('date'));
                // $('.to-field-text').text($('.day.checked').last().data('date'));
			});
		}else {
			daySquare.classList.add('padding')
		}

		calendar.appendChild(daySquare)
	}
}

function getKeyByValue(object, value) {
    return Object.keys(object).find(key => object[key] == value);
}

// function initButtons(){
// 	document.getElementById('nextButton').addEventListener('click', () => {
// 		console.log('next clicked');
// 		nav++;
// 		load()
// 	})
//
// 	document.getElementById('backButton').addEventListener('click', () => {
// 		console.log('back button');
// 		nav--;
// 		load()
// 	})
// }

// initButtons();
load();
