import axios from 'axios';
import Swal from 'sweetalert2';

function borrow (event) {
  event.preventDefault();

  const url = this.href;
  const reserveBook = document.querySelector('.js-reserve-book')

  axios.get(url)
    .then(function (response) {
      Swal.fire({
        text: 'Votre reservation a bien été envoyé, vous allez resevoir un mail de confirmation',
        confirmButtonColor: '#2196f3'
      });

      reserveBook.remove()
    })
    .catch(function (error) {

    });
}

export default function bookReservationHander () {
  const reserveBook = document.querySelector('.js-reserve-book');
  reserveBook.addEventListener('click', borrow);
}