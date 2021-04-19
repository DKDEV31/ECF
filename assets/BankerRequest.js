import './styles/Banker/BankerRequest.scss'

const requestState = document.querySelectorAll('.request-state')
const requestAccount = document.querySelectorAll('.requestAccount')
const requestDelete = document.querySelectorAll('.requestDelete')
const requestBenefit = document.querySelectorAll('.requestBenefit')

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
            el.style.color = 'white'
    }
})
requestAccount.forEach(el => {
    el.addEventListener('click', () => {
        let {id, banker} = el.dataset
        id = Number.parseInt(id)
        banker = Number.parseInt(banker)
        location.pathname = `/banker/${banker}/request/create-account/${id}`
    })
})
requestDelete.forEach(el => {
    el.addEventListener('click', () => {
        let {id, banker} = el.dataset
        id = Number.parseInt(id)
        banker = Number.parseInt(banker)
        location.pathname = `/banker/${banker}/request/delete-account/${id}`
    })
})
requestBenefit.forEach(el =>{
    el.addEventListener('click', () => {
        let {id, banker} = el.dataset
        id = Number.parseInt(id)
        banker = Number.parseInt(banker)
        location.pathname = `/banker/${banker}/request/create-benefit/${id}`
    })
})

