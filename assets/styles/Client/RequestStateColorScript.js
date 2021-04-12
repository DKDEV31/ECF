const requestState = document.querySelector('.request-state')
switch (requestState.innerHTML){
    case 'En Attente':
        requestState.style.color = '#5E98E9'
        break
    case 'Validé' :
        requestState.style.color = '#4AD1C8'
        break
    case 'Refus' :
        requestState.style.color = '#FD82A7'
        break
    default:
        requestState.style.color = '#5E98E9'
}