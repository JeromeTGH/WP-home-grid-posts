
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

    
    // Chargement des données dans des objets, en vidant les selects au fur et à mesure
    const tblSrc = {};
    const tblDest = {};

    for (let i=source_cat_select.options.length-1; i>=0; i--) {
        tblSrc[source_cat_select.options[i].value] = {
            innerText: source_cat_select.options[i].innerText,
            selected: source_cat_select.options[i].selected
        }
        source_cat_select.remove(i);
    }
    for (let i=dest_cat_select.options.length-1; i>=0; i--) {
        tblDest[dest_cat_select.options[i].value] = {
            innerText: dest_cat_select.options[i].innerText,
            selected: dest_cat_select.options[i].selected
        }
        dest_cat_select.remove(i);
    }


    // Logique de manipulation des tableaux
    switch(option) {
        case '>>':
            const srcEntries = Object.entries(tblSrc);
            for(let i=0; i<srcEntries.length; i++) {
                tblDest[srcEntries[i][0]] = {
                    innerText: srcEntries[i][1].innerText
                }
                delete tblSrc[srcEntries[i][0]];
            }
        case '>':
            const srcEntries2 = Object.entries(tblSrc);
            for(let i=0; i<srcEntries2.length; i++) {
                if(tblSrc[srcEntries2[i][0]].selected !== false) {
                    tblDest[srcEntries2[i][0]] = {
                        innerText: srcEntries2[i][1].innerText
                    }
                    delete tblSrc[srcEntries2[i][0]];
                }
            }
            break;
        case '<<':
            const destEntries = Object.entries(tblDest);
            for(let i=0; i<destEntries.length; i++) {
                tblSrc[destEntries[i][0]] = {
                    innerText: destEntries[i][1].innerText
                }
                delete tblDest[destEntries[i][0]];
            }
            break;
        case '<':
            const destEntries2 = Object.entries(tblDest);
            for(let i=0; i<destEntries2.length; i++) {
                if(tblDest[destEntries2[i][0]].selected !== false) {
                    tblSrc[destEntries2[i][0]] = {
                        innerText: destEntries2[i][1].innerText
                    }
                    delete tblDest[destEntries2[i][0]];
                }
            }
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
        new_src_opt.value = value[0];
        new_src_opt.innerText = value[1].innerText;
        source_cat_select.appendChild(new_src_opt);
    })
    sortedTblDest.forEach((value) => {
        let new_dest_opt = document.createElement('option');
        new_dest_opt.value = value[0];
        new_dest_opt.innerText = value[1].innerText;
        dest_cat_select.appendChild(new_dest_opt);
    })

}