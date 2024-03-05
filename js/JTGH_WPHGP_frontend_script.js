// ============================================
// Function : JTGH_WPHGP_handleCategoriesUpDown
// ============================================
const JTGH_WPHGP_handleCategoryChange = (cat_id) => {
    const cat_divs = document.querySelectorAll(".JTGH_WPHGP_category_posts_container");
    cat_divs.forEach((cat_div) => {
        cat_div.style.display = 'none';
    })
    
    const active_div = document.getElementById("JTGH_WPHGP_category_number_" + cat_id);
    active_div.style.display = 'grid';
}