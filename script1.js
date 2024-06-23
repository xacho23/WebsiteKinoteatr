document.addEventListener('DOMContentLoaded', function() {
    const startCameraButton = document.getElementById('start-camera');
    let html5QrCode;

    if (startCameraButton) {
        startCameraButton.addEventListener('click', function() {
            if (html5QrCode && html5QrCode.isScanning) {
                html5QrCode.stop().then(ignore => {
                    console.log("Камера выключена.");
                }).catch(err => {
                    console.error(`Ошибка при остановке сканирования: ${err}`);
                });
                return;
            }

            html5QrCode = new Html5Qrcode("reader");

            // Проверка доступности камеры
            Html5Qrcode.getCameras().then(cameras => {
                if (cameras && cameras.length) {
                    // Если камеры найдены, начать сканирование
                    html5QrCode.start(
                        cameras[0].id, // Используем первую найденную камеру
                        {
                            fps: 30,
                            qrbox: 250
                        },
                        qrCodeMessage => {
                            document.getElementById('booking-code').value = qrCodeMessage;
                            fetchBookingStatus(qrCodeMessage);
                        },
                        errorMessage => {
                            console.log(`QR код больше не перед камерой.`);
                        }
                    ).catch(err => {
                        console.error(`Не удается начать сканирование, ошибка: ${err}`);
                    });
                } else {
                    console.error("Камеры не найдены.");
                    alert("Не найдены камеры на вашем устройстве.");
                }
            }).catch(err => {
                console.error(`Ошибка при получении камер: ${err}`);
                alert("Произошла ошибка при получении доступа к камере.");
            });
        });
    }
});

function fetchBookingStatus(bookingCode) {
    fetch(`getBookingStatus.php?code=${bookingCode}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('status').innerText = `Статус билета: ${data.status}`;
            document.getElementById('hall-number').innerText = `Номер зала: ${data.hall_id}`;
            document.getElementById('seat-number').innerText = `Номер места: ${data.seat_number}`;
            document.getElementById('user-email').innerText = `Email: ${data.user_email}`;
            document.getElementById('showtime').innerText = `Дата и время сеанса: ${data.day} ${data.time}`;
            document.getElementById('movie-title').innerText = `Название фильма: ${data.movie_title}`;

            const statusButtonContainer = document.getElementById('status-buttons');
            statusButtonContainer.innerHTML = '';

            if (data.status === 'Бронь') {
                const buyButton = document.createElement('button');
                buyButton.innerText = 'Куплено';
                buyButton.onclick = () => updateTicketStatus(bookingCode, 'Куплено');
                statusButtonContainer.appendChild(buyButton);
            } else if (data.status === 'Куплено') {
                const usedButton = document.createElement('button');
                usedButton.innerText = 'Использовано';
                usedButton.onclick = () => updateTicketStatus(bookingCode, 'Использовано');
                statusButtonContainer.appendChild(usedButton);
            }
        })
        .catch(error => {
            console.error('Ошибка при получении статуса бронирования:', error);
        });
}

function updateTicketStatus(bookingCode, newStatus) {
    fetch(`updateBookingStatus.php?code=${bookingCode}&status=${newStatus}`, { method: 'POST' })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                fetchBookingStatus(bookingCode);
            } else {
                alert('Ошибка при обновлении статуса билета.');
            }
        })
        .catch(error => {
            console.error('Ошибка при обновлении статуса билета:', error);
        });
}
