// Default shortcode value for the third shortcode
const defaultShortcode3 = "[bkash_payment_button product_id='19523' label='bKash Payment' style='background-color: black; color: #38BDF8; border-radius: 5px; padding: 10px 20px; border: none; cursor: pointer; height: 50px; font-weight: bold;']";

// Load the saved shortcode or default if not saved
window.addEventListener('load', () => {
    const savedShortcode = localStorage.getItem('bkashShortcode3') || defaultShortcode3;
    document.getElementById('bkash-shortcode-3').value = savedShortcode;
});

// Function to copy shortcode
function copyShortcode(inputId) {
    const copyText = document.getElementById(inputId);
    copyText.select();
    document.execCommand("copy");
    alert("Shortcode copied: " + copyText.value);
}

// Function to save the edited shortcode
function saveShortcode() {
    const editedShortcode = document.getElementById('bkash-shortcode-3').value;
    localStorage.setItem('bkashShortcode3', editedShortcode);
    alert("Shortcode saved!");
}

// Function to reset the shortcode to default
function resetShortcode() {
    document.getElementById('bkash-shortcode-3').value = defaultShortcode3;
    localStorage.removeItem('bkashShortcode3');
    alert("Shortcode reset to default.");
}

// Functions for showing and closing the Help popup
function showHelpPopup() {
    document.getElementById("help-popup-overlay").style.display = "flex";
}

function closeHelpPopup() {
    document.getElementById("help-popup-overlay").style.display = "none";
}
