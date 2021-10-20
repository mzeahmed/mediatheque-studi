import Swal from 'sweetalert2';

export default function deleteBookConfirmation () {
  Swal.fire({
    title: 'Sûre ?',
    text: 'Vous allez retirer ce livre de la bibliotheque',
    showCancelButton: true,
    cancelButtonText: 'Annuler',
    confirmButtonColor: '#2196f3',
    cancelButtonColor: '#d33',
    confirmButtonText: 'Oui, retirer !'
  });
}