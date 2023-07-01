window.onload=function() {

  
  const list = [...document.body.querySelectorAll('#form_MyNewsLetter .onglet')];
  if(list.length == 0) { return; }
  
  if(list.length > 1) {
    // On crée une barre de navigation s'il y a plus de 1 chapitre
    var innerHTML = '';
    list.forEach((item, i) => {
      const caption = item.getAttribute('data-title');
      innerHTML += `<button data-page="${i}">${caption}</button>`;
    });
    
    // On crée la barre de navigation
    const pagination_numbers_container = document.createElement('NAV');
    pagination_numbers_container.className = 'art-nav center';
    pagination_numbers_container.innerHTML = innerHTML;
    
    const page0 = list[0].parentElement;
    page0.parentElement.insertBefore(pagination_numbers_container, page0);

    // On gére le click sur la barre de navigation
    pagination_numbers_container.addEventListener('click', (evt) => {
      if(evt.target.hasAttribute('data-page')) {
        evt.preventDefault();
        // On affiche uniquement le chapitre demandé
        [...document.body.querySelectorAll('.onglet.active')].forEach((item) => {
          item.classList.remove('active');
        });
        const i = parseInt(evt.target.dataset.page);
        list[i].classList.add('active');
        // On met en évidence uniquement le bouton du chapitre affiché
        [...pagination_numbers_container.querySelectorAll('.active')].forEach((item) => {
          item.classList.remove('active');
        });
        event.target.classList.add('active');
      }
    });
  }
  
  // On allume sur le premier .onglet
  list[0].classList.add('active');
  const btn = document.body.querySelector('.art-nav button');
  if(btn != null) {
    btn.classList.add('active');
  }

}