import Swal from 'sweetalert2'

export const modalExcluded = (callback) => {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não será capaz de reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, apague!'
    }).then(async (result) => {
        if (result.isConfirmed) {
          callback()
          Swal.fire(
            'Excluído!',
            'Seu registro foi excluído.',
            'success'
          )
        }
    });
}