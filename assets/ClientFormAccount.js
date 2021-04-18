const fileInput = document.querySelector('.form-file > input')

fileInput.addEventListener('change', (e)=>{
    let {name, size} = e.target.files[0]
    size = (size/1000000).toFixed(1)
    const labelVal = e.target.previousElementSibling
    labelVal.innerHTML = `${name}-${size}MB`

})
