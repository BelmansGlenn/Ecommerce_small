
var promoAnim = {
    init: function(el, str) {
      var element = document.querySelector(el);
      var text = str ? str : element.innerHTML;
      element.innerHTML = '';
      for (var i = 0; i < text.length; i++) {
        var letter = text[i];
        var span = document.createElement('span');
        var node = document.createTextNode(letter);
        var r = (360/text.length)*(i);
        var x = (Math.PI/text.length).toFixed(0) * (i);
        var y = (Math.PI/text.length).toFixed(0) * (i);
        span.appendChild(node);
        span.style.webkitTransform = 'rotateZ('+r+'deg) translate3d('+x+'px,'+y+'px,0)';
        span.style.transform = 'rotateZ('+r+'deg) translate3d('+x+'px,'+y+'px,0)';
        element.appendChild(span);
      }
    }
  };
  
  promoAnim.init('.promoAnim');


  // Caroussel 

  const clcBtn = document.querySelector(".clcBtn")
  const ecgBtn = document.querySelector(".ecgBtn")

function clcCaroussel() {
    let clcImg = ["home.jpg", "room.jpg", "decoration.jpg", "exterieur.jpg"]
    let clcTxt = ["Salon", "Chambre", "Décoration", "Extérieur"]
    let clcBox = document.querySelector(".jsClc")
    let i = 1
    clcBtn.addEventListener('click', (el) => { 
      clcBox.innerHTML = `
        <figure><img src="/assets/img/${clcImg[i]}"></figure>
        <section>
            <h2>${clcTxt[i]}</h2>
            <a href="{{ path('products') }}">shop now</a>
        </section>
      `
      i++;
      if(i === 4) {
        i = 0
      }
      console.log(clcImg[1]);
      console.log(clcBox);
    })
}
clcCaroussel()

function ecgCaroussel() {
  let ecgImg = ["lightSalon.jpg", "roomecg.jpg", "deskecg.jpg", "salonecg.jpg"]
  let ecgTxt = ["Intérieur", "Chambre", "Bureaux", "Salon"]
  let ecgBox = document.querySelector(".jsEcg")
  let i = 1
  ecgBtn.addEventListener('click', (el) => { 
    ecgBox.innerHTML = `
      <figure><img src="/assets/img/${ecgImg[i]}"></figure>
      <section>
          <h2>${ecgTxt[i]}</h2>
          <a href="{{ path('products') }}">shop now</a>
      </section>
    `
    i++;
    if(i === 4) {
      i = 0
    }
  })
}
ecgCaroussel()