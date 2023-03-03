import Swal from 'sweetalert2';

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

export const paginationComponentOptions = {
  rowsPerPageText: 'Filas por página',
  rangeSeparatorText: 'de',
  selectAllRowsItem: true,
  selectAllRowsItemText: 'Todos',
};

export const slugify = text =>
  text
    .toString()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .trim()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]+/g, '')
    .replace(/--+/g, '-')

export const CustomLoader = () => {
    return (
        <h1>Carregando...</h1>
    )
}

export const paymentMethod = {
  "money": "Dinheiro",
  "card": "Cartão",
}

export const statusOrder = {
  "1": "Aberto",
  "2": "Em preparação",
  "3": "Em transporte",
  "4": "Concluído",
  "5": "Cancelado"
}

export const dayOfWeek = [
  null,
  'Domingo',
  'Segunda',
  'Terça',
  'Quarta',
  'Quinta',
  'Sexta',
  'Sábado'
]