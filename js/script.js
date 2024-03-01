
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

    // Pointage des select
    const source_cat_select = document.getElementById("JTGH_WPHGP_categories_source");
    const dest_cat_select = document.getElementById("JTGH_WPHGP_categories_dest");

    // Chargement des données dans des tableaux, en vidant les selects au fur et à mesure
    const tblSrc = [];
    const tblDest = [];

    for (let i=source_cat_select.options.length-1; i>=0; i--) {
        tblSrc.push({
            value: source_cat_select.options[i].value,
            innerText: source_cat_select.options[i].innerText,
            selected: source_cat_select.options[i].selected
        })
        source_cat_select.remove(i);
    }
    for (let i=dest_cat_select.options.length-1; i>=0; i--) {
        tblDest.push({
            value: dest_cat_select.options[i].value,
            innerText: dest_cat_select.options[i].innerText,
            selected: dest_cat_select.options[i].selected
        })
        dest_cat_select.remove(i);
    }







    // Logique de manipulation des tableaux
    switch(option) {
        case '>>':
            // for(let src_opt in tblSrc) {
            //     tblDest[src_opt] = tblSrc[src_opt];
            //     delete tblSrc[src_opt];
            // }
            break;
        case '>':
            break;
        case '<<':
            // for(let dest_opt in tblDest) {
            //     tblSrc[dest_opt] = tblDest[dest_opt];
            //     delete tblDest[dest_opt];
            // }
            break;
        case '<':
            break;
        default:
            break;
    }



    // Tri alphabétique des tableaux, en fonction de la colonne "innerText"
    const sortedTblSrc = Object.entries(tblSrc).sort((a, b) => {
        if (a[1].innerText < b[1].innerText) {return -1;}
        if (a[1].innerText > b[1].innerText) {return 1;}
        return 0;
    });
    const sortedTblDest = Object.entries(tblDest).sort((a, b) => {
        if (a[1].innerText < b[1].innerText) {return -1;}
        if (a[1].innerText > b[1].innerText) {return 1;}
        return 0;
    });
    

    // Et remplissage des selects avant de quitter
    sortedTblSrc.forEach((value) => {
        let new_src_opt = document.createElement('option');
        new_src_opt.value = value[1].value;
        new_src_opt.innerText = value[1].innerText;
        source_cat_select.appendChild(new_src_opt);
    })
    sortedTblDest.forEach((value) => {
        let new_dest_opt = document.createElement('option');
        new_dest_opt.value = value[1].value;
        new_dest_opt.innerText = value[1].innerText;
        source_cat_select.appendChild(new_dest_opt);
    })

}