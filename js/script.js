
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
