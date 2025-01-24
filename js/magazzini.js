function toggleHiddenByClass(className) {
    const button = document.querySelector(`a.${className}`);
    const elements = document.querySelectorAll(`ul.${className}`);
    elements.forEach(element => {
        element.classList.toggle('hidden');
        if(!element.classList.contains('hidden')){
            button.innerHTML = "Nascondi"
        }else{
            button.innerHTML = "Visualizza contenuto"
        }
    });

}
