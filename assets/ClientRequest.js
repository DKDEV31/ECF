import './styles/Client/Client-request.scss'

const requestState = document.querySelectorAll('.request-state')

requestState.forEach((el) => {
    switch (el.innerHTML){
        case 'En Attente':
            el.style.color = '#5E98E9'
            break
        case 'Validé' :
            el.style.color = '#4AD1C8'
            break
        case 'Refusée' :
            el.style.color = '#FD82A7'
            break
        default:
            el.style.color = '#5E98E9'
    }
})