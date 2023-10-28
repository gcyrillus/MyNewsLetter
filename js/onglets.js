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
                openTab = evt.target.getAttribute('data-page');
                localStorage.setItem("activeTabIndex", openTab);
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
    
    // memorise l'onglet ouvert ou revient sur le premier par défaut
    let navTabButton = document.querySelectorAll('button[data-page]') ; // nb button   
    let activeTabIndex = window.localStorage.getItem('activeTabIndex'); // memoire
    if(activeTabIndex >= navTabButton.length) {activeTabIndex = 0 };    // init
    
    // action active l'onglet 
    if (activeTabIndex) {        
        document.querySelectorAll('.onglet')[Number(activeTabIndex)].classList.add('active');
        navTabButton[Number(activeTabIndex)].classList.add('active');
        } else {  
        // On allume sur le premier .onglet
        list[0].classList.add('active');
        const btn = document.body.querySelector('.art-nav button');
        if(!activeTabIndex && btn != null) {
            btn.classList.add('active');
        }
    }
    
    
    // effacement abonnement
    const subscribers = [...document.body.querySelectorAll('#suscribers tbody tr a[data-revoque]')];
    subscribers.forEach((reader, i) => {
        reader.addEventListener("click", function () {
            let removeIt = reader.getAttribute('data-revoque');
            deleteSubscriber(removeIt, this);
        });
    });
    
    
    
    function deleteSubscriber(removeIt,el) {        
        var data = new URLSearchParams();
        data.append("p","MyNewsLetter");
        data.append("stopNewsLetter", removeIt);
        var url = "/core/admin/plugin.php?" + data.toString();
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = () => {
            if (xhr.readyState === xhr.DONE) {
                if (xhr.status === 200 && xhr.responseText.includes(ERROR_MESSAGE) !== true ) {
                    el.textContent= L_DELETED ;
                    el.style.color='red';
                    el.parentNode.closest('tr').classList.add("alert", "red");                  
                }
                else {
                    alert(L_ERROR_DEL_SUB);                    
                    el.textContent= L_UNKNOWN ;
                    el.style.color='red';
                    el.parentNode.closest('tr').classList.add("alert", "warning");
                }
            }
        };
        xhr.send();
    }
    
    let addOne = document.querySelector("#courriel");    
    let actionAdd = document.querySelector("#add");
    let getRow = document.querySelector("#suscribers tbody");
    
    // ne pas soumettre le formulaire depuis input text/#courriel
    addOne.addEventListener("keypress", logKey);  
    
    function logKey(e) {
        if(e.code === 'NumpadEnter' ||  e.code === 'Enter') {  
            e.preventDefault();
            let addit = addOne.value;
            addSubscriber(addit, getRow);
            return false;
            
        }
        
    }
    
    
    
    
    actionAdd.addEventListener("click", function () {
        let addit = addOne.value;
        addSubscriber(addit, getRow);
    });
    
    function addSubscriber(addit, getRow) {
        var data = new URLSearchParams();
        data.append("p", "MyNewsLetter");
        data.append("addSubscriber", "1");
        data.append("courriel", addit);
        data.append("valid", "1");
        data.append("newsfrequency", "1");
        var url = "/core/admin/plugin.php?" + data.toString();
        var xhr = new XMLHttpRequest();
        xhr.open("GET", url);
        xhr.onload = () => {
            if (xhr.readyState === xhr.DONE) {
                if (xhr.status === 200 && xhr.responseText.includes('<b>'+addit) === true ) {
                    getRow.insertAdjacentHTML(
                        "beforeend",
                        "<tr class='alert green'><th>" +
                        addit +
                        "</th><td>"+ L_YES +"</td><td>" +
                        TODAY +
                        "</td><td>01-2023</td><td>1</td><td>"+ L_NONE1 +"</td><td><b class='red'>"+ L_NEW +"</b></td></tr> \n"
                    );
                    } else {
                    alert(L_RETRY_VALID_MAIL);
                }
            }
        };
        xhr.send();
    }
    
}                    