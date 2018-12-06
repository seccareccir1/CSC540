/* When the user clicks on the button, 
toggle between hiding and showing the dropdown content */
function iddFunction() {
    document.getElementById("invoiceDD").classList.toggle("show");
}
function cddFunction() {
    document.getElementById("customerDD").classList.toggle("show");
}
function qddFunction() {
    document.getElementById("quoteDD").classList.toggle("show");
}
function oddFunction() {
    document.getElementById("orderDD").classList.toggle("show");
}


// Close the dropdown if the user clicks outside of it
window.onclick = function(e) {
  if (!e.target.matches('.dropbtn')) {
    var invDD = document.getElementById("invoiceDD");
    var custDD = document.getElementById("customerDD");
    var quoteDD = document.getElementById("quoteDD");
    var orderDD = document.getElementById("orderDD");
      if (invDD.classList.contains('show')) {
        invDD.classList.remove('show');
      }
      if (custDD.classList.contains('show')) {
        custDD.classList.remove('show');
      }
      if (quoteDD.classList.contains('show')) {
        quoteDD.classList.remove('show');
      }
      if (orderDD.classList.contains('show')) {
        orderDD.classList.remove('show');
      }
  }
}