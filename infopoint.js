function plusDivs(n, myDivClass) {
  showDivs(slideIndex += n, myDivClass);
}

function showDivs(n, myDivClass) {
  var i;
  var x = document.getElementsByClassName(myDivClass);
  if (n > x.length) {slideIndex = 1}
  if (n < 1) {slideIndex = x.length} ;
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  x[slideIndex-1].style.display = "block";
}
