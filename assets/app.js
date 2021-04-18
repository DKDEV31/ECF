/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';

const navMenuToggle = document.querySelector('.dropdown-menu')
const navMenuBlock = document.querySelector('.navToggleMenu')
const alert = document.querySelector('#alert')

window.addEventListener('load', ()=> {
    if(alert instanceof Element) {
        console.log('ok')
        alert.style.top = '0'
        setTimeout(() => {
            alert.style.top = '-1500px'
        }, 3000)
    }
})

navMenuToggle.addEventListener('click', ()=>{
    navMenuToggle.classList.toggle('active')
    navMenuBlock.classList.toggle('active')
})
