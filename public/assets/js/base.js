// Return top 
const goBackBtn = document.querySelector("#goTop")
goBackBtn.addEventListener('click', (e) => {
    goTop()
}) 
function goTop() {
    window.scrollTo({top: 0, behavior: 'smooth'});
    goBackBtn.style.animation = "popOff 1s ease-in forwards"
}
window.onscroll = function() {
    checkScroll()
};
function checkScroll() {
  if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
    goBackBtn.style.display = "inline-grid"
    goBackBtn.style.animation = "popOn 1s ease-in forwards"
  } else if (document.body.scrollTop < 350 || document.documentElement.scrollTop < 350) {
    goBackBtn.style.animation = "popOff 1s ease-in forwards"
  }
}


// Basket informations 
/*
const basketProducts = document.querySelector("#basketInfo")
const numProducts = basketProducts.firstElementChild.innerHTML
function checkBasket() {
  if (numProducts < 1) {
    basketProducts.style.display = "none"
  } 
}
checkBasket() */


// MENU 
menuBtn = document.querySelector(".menuOpen")
menuOpen = document.querySelector(".menuOpened")


function menuClick() {
  menuBtn.addEventListener('click', (el) =>{ 
      menuOpen.classList.add('disBlock')
      menuBtn.addEventListener('click', (el) =>{ 
        menuOpen.classList.remove("disBlock");
        menuClick()
      })
  })
}
menuClick()










// CURSOR 
// const point = document.querySelector('.cursor--point');
// document.addEventListener("mousemove", cursorPos, false);
// function cursorPos(event) {
//   pageX = event.pageX;
//   pageY= event.pageY;
//   point.style.transform = 'translate3d(' + (pageX - 1) + 'px ,' + (pageY -1) + 'px, 0)';;
// }

// const cursor = document.querySelector('#cursor');

// let mouse = { x: 300, y: 300 };
// let pos = { x: 0, y: 0 };
// const speed = 0.1; 
// const updatePosition = () => {
//   pos.x += (mouse.x - pos.x) * speed;
//   pos.y += (mouse.y - pos.y) * speed;
//   cursor.style.transform = 'translate3d(' + pos.x + 'px ,' + pos.y + 'px, 0)';
// }
// const updateCoordinates = e => {
//   mouse.x = e.clientX;
//   mouse.y = e.clientY;
// }
// window.addEventListener('mousemove', updateCoordinates);
// function loop() {
//   updatePosition();
//   requestAnimationFrame(loop);
// }
// requestAnimationFrame(loop);
