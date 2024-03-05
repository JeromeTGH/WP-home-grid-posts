
// =============================================
// Function : JTGH_WPHGP_handleClickOnCopyButton
// =============================================
const JTGH_WPHGP_handleClickOnCopyButton = () => {
    const shortcode = document.getElementById("JTGH_WPHGP_shortcode").innerText;

    navigator.clipboard.writeText(shortcode).then(() => {
        alert('Shortcode copié !');        
    }).catch(() => {
        alert('Impossible de copier ce shortcode, désolé ...');
    })
}

// ==========================================
// Function : JTGH_WPHGP_handleCategoriesBasc
// ==========================================
const JTGH_WPHGP_handleCategoriesBasc = (option) => {

    // Ciblage des éléments dont on aura besoin
    const source_cat_select = document.getElementById("JTGH_WPHGP_categories_source");
    const dest_cat_select = document.getElementById("JTGH_WPHGP_categories_dest");
    const cat_list_input = document.getElementById("JTGH_WPHGP_categories_choisies");

    
    // Chargement des données dans des tableaux
    const tblSrc = [];
    const tblDest = [];

    for (let i=0; i<source_cat_select.options.length; i++) {
        tblSrc.push({
            value: source_cat_select.options[i].value,
            innerText: source_cat_select.options[i].innerText,
            selected: source_cat_select.options[i].selected
        });
    }
    for (let i=0; i<dest_cat_select.options.length; i++) {
        tblDest.push({
            value: dest_cat_select.options[i].value,
            innerText: dest_cat_select.options[i].innerText,
            selected: dest_cat_select.options[i].selected
        });
    }

    // Vidage des selects
    for (let i=source_cat_select.options.length-1; i>=0; i--) {
        source_cat_select.remove(i);
    }
    for (let i=dest_cat_select.options.length-1; i>=0; i--) {
        dest_cat_select.remove(i);
    }


    // Logique de manipulation des tableaux
    switch(option) {
        case '>>':
            for (let i=0; i<tblSrc.length; i++) {
                tblDest.push({
                    value: tblSrc[i].value,
                    innerText: tblSrc[i].innerText,
                    selected: tblSrc[i].selected
                });
            }
            for (let i=tblSrc.length-1; i>=0; i--) {
                tblSrc.splice(i, 1);
            }
            break;
        case '>':
            for (let i=0; i<tblSrc.length; i++) {
                if(tblSrc[i].selected) {
                    tblDest.push({
                        value: tblSrc[i].value,
                        innerText: tblSrc[i].innerText,
                        selected: tblSrc[i].selected
                    });
                }
            }
            for (let i=tblSrc.length-1; i>=0; i--) {
                if(tblSrc[i].selected) {
                    tblSrc.splice(i, 1);
                }
            }
            break;
        case '<<':
            for (let i=0; i<tblDest.length; i++) {
                tblSrc.push({
                    value: tblDest[i].value,
                    innerText: tblDest[i].innerText,
                    selected: tblDest[i].selected
                });
            }
            for (let i=tblDest.length-1; i>=0; i--) {
                tblDest.splice(i, 1);
            }
            break;
        case '<':
            for (let i=0; i<tblDest.length; i++) {
                if(tblDest[i].selected) {
                    tblSrc.push({
                        value: tblDest[i].value,
                        innerText: tblDest[i].innerText,
                        selected: tblDest[i].selected
                    });
                }
            }
            for (let i=tblDest.length-1; i>=0; i--) {
                if(tblDest[i].selected) {
                    tblDest.splice(i, 1);
                }
            }
            break;
        default:
            break;
    }


    // Tri alphabétique de la liste source (uniquement), basé sur la colonne "innerText"
    const sortedTblSrc = tblSrc.sort((a, b) => {
        if (a.innerText < b.innerText) {return -1;}
        if (a.innerText > b.innerText) {return 1;}
        return 0;
    });


    // Remplissage des selects
    sortedTblSrc.forEach((lg) => {
        let new_src_opt = document.createElement('option');
        new_src_opt.value = lg.value;
        new_src_opt.innerText = lg.innerText;
        source_cat_select.appendChild(new_src_opt);
    })
    tblDest.forEach((lg) => {
        let new_dest_opt = document.createElement('option');
        new_dest_opt.value = lg.value;
        new_dest_opt.innerText = lg.innerText;
        dest_cat_select.appendChild(new_dest_opt);
    })


    // Mémorisation des chiffres envoyés dans le select 'dest'
    const lst_cat = [];
    tblDest.forEach((lg) => {
        if(!isNaN(parseInt(lg.value, 10))) {
            lst_cat.push(parseInt(lg.value,10));
        }
    })
    cat_list_input.value = JSON.stringify(lst_cat, null, 2);

}


// ============================================
// Function : JTGH_WPHGP_handleCategoriesUpDown
// ============================================
const JTGH_WPHGP_handleCategoriesUpDown = (option) => {

    // Ciblage des éléments dont nous aurons besoins ici
    const dest_cat_select = document.getElementById("JTGH_WPHGP_categories_dest");
    const cat_list_input = document.getElementById("JTGH_WPHGP_categories_choisies");

    
    // Chargement des données dans un tableau
    const tblDest = [];
    for (let i=0; i<dest_cat_select.options.length; i++) {
        tblDest.push({
            value: dest_cat_select.options[i].value,
            innerText: dest_cat_select.options[i].innerText,
            selected: dest_cat_select.options[i].selected
        });
    }

    
    // Vidage du select
    for (let i=dest_cat_select.options.length-1; i>=0; i--) {
        dest_cat_select.remove(i);
    }


    // Logique de manipulation du tableau
    switch(option) {
        case '↑':
            for (let i=1; i<tblDest.length; i++) {              // On commence à 1, car on ne peut pas remonter une valeur qui serait déjà tout en haut
                if(tblDest[i].selected && !tblDest[i-1].selected) {
                    const oldPrevious = tblDest[i-1];
                    tblDest[i-1] = tblDest[i];
                    tblDest[i] = oldPrevious;
                }
            }
            break;
        case '↓':
            for (let i=tblDest.length-2; i>=0; i--) {            // On commence à length-2 de la fin, car on ne peut pas descendre une valeur qui serait déjà tout en bas
                if(tblDest[i].selected && !tblDest[i+1].selected) {
                    const oldNext = tblDest[i+1];
                    tblDest[i+1] = tblDest[i];
                    tblDest[i] = oldNext;
                }
            }
            break;
        default:
            break;
    }


    // Re-remplissage du select, avec mémorisation des sélections
    tblDest.forEach((lg) => {
        let new_dest_opt = document.createElement('option');
        new_dest_opt.value = lg.value;
        new_dest_opt.innerText = lg.innerText;
        new_dest_opt.selected = lg.selected;
        dest_cat_select.appendChild(new_dest_opt);
    })


    // Mémorisation des chiffres envoyés dans le select 'dest'
    const lst_cat = [];
    tblDest.forEach((lg) => {
        if(!isNaN(parseInt(lg.value, 10))) {
            lst_cat.push(parseInt(lg.value,10));
        }
    })
    cat_list_input.value = JSON.stringify(lst_cat, null, 2);

}


// ======================================
// Function : JTGH_WPHGP_unselect_all_cat
// ======================================
const JTGH_WPHGP_unselect_all_cat = () => {

    // Ciblage des select
    const source_cat_select = document.getElementById("JTGH_WPHGP_categories_source");
    const dest_cat_select = document.getElementById("JTGH_WPHGP_categories_dest");

    // Déselection de ce qui est éventuellement sélectionné, dans les selects
    for (let i=source_cat_select.options.length-1; i>=0; i--) {
        source_cat_select.options[i].selected = false;
    }
    for (let i=dest_cat_select.options.length-1; i>=0; i--) {
        dest_cat_select.options[i].selected = false;
    }

}

// ======================================
// Function : JTGH_WPHGP_mem_bloc_couleur
// ======================================
const JTGH_WPHGP_mem_bloc_couleur = (e) => {
    // Mémorisation de cette valeur, avant qu'elle ne change avec le keyup
    e.target.defaultValue = e.target.value;
}

// ======================================
// Function : JTGH_WPHGP_maj_bloc_couleur
// ======================================
const JTGH_WPHGP_maj_bloc_couleur = (e) => {

    // Récupération des infos qui nous intéressent
    const cat_id = e.target.alt;
    const previous_val = e.target.defaultValue;
    const next_val = e.target.value;

    // Ciblage des éléments qui nous intéresse ici
    const target_input = document.getElementById("JTGH_WPHGP_hex_code_" + cat_id);
    const target_color = document.getElementById("JTGH_WPHGP_color_bloc_" + cat_id);

    // Regex de vérification des couleurs hexadécimales
    const reg_hexa_color = /^[0-9A-Fa-f]{0,8}$/g;

    // Sortie si couleur non conforme
    if(!reg_hexa_color.test(next_val)) {
        target_input.value = previous_val;
        target_color.style.backgroundColor = "#" + previous_val;
        return;
    }

    // Mise à jour de la couleur, dans le bloc correspondant
    target_color.style.backgroundColor = "#" + next_val;

}

// =================================
// Function : JTGH_WPHGP_confirm_btn
// =================================
const JTGH_WPHGP_confirm_btn = () => {
    return confirm("Êtes-vous sûr de vouloir importer ces données ?");
}
