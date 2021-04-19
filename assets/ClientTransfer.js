import './styles/Client/Client-Transfer.scss'
const transfer = document.querySelectorAll('.box')

transfer.forEach(el => {
    el.addEventListener('click', () => {
        const {id, user} = el.dataset
        location.pathname = `/client/${user}/transfer/add/${id}`
    })
})
