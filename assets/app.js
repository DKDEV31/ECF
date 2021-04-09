/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import './styles/app.scss';
import './styles/Home/home-content.scss';
import {gsap} from 'gsap'

const navMenuToggle = document.querySelector('.dropdown-menu')
const navMenuBlock = document.querySelector('.navToggleMenu')

navMenuToggle.addEventListener('click', ()=>{
    if(navMenuBlock.classList.contains('active')) {
        navMenuToggle.classList.remove('active')
        navMenuToggle.classList.add('desactive')
        navMenuBlock.classList.remove('active')
        navMenuBlock.classList.add('desactive')
    } else {
        navMenuToggle.classList.add('active')
        navMenuBlock.classList.add('active')
        // navMenuBlock.classList.remove('desactive')
        // navMenuToggle.classList.remove('desactive')
    }
})