const goBackBtn = document.querySelector("#goTop")
goBackBtn.addEventListener('click', (e) => {
    gaoTop()
})

    
function gaoTop() {
    window.scrollTo({top: 0, behavior: 'smooth'});
    goBackBtn.style.animation = "popOff 1s ease-in forwards"
}

// Scroll detector 
window.onscroll = function() {
    checkScroll()
};

function checkScroll() {
  if (document.body.scrollTop > 350 || document.documentElement.scrollTop > 350) {
    goBackBtn.style.display = "inline-grid"
  }
}

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
const point = document.querySelector('.cursor--point');
document.addEventListener("mousemove", cursorPos, false);
function cursorPos(event) {
  pageX = event.pageX;
  pageY= event.pageY;
  point.style.transform = 'translate3d(' + (pageX - 1) + 'px ,' + (pageY -1) + 'px, 0)';;
}


// Take activies input end
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
// CURSOR 
const cursor = document.querySelector('#cursor');
// const point = document.querySelector('.cursor--point');
let mouse = { x: 300, y: 300 };
let pos = { x: 0, y: 0 };
const speed = 0.1; // between 0 and 1
const updatePosition = () => {
  pos.x += (mouse.x - pos.x) * speed;
  pos.y += (mouse.y - pos.y) * speed;
  cursor.style.transform = 'translate3d(' + pos.x + 'px ,' + pos.y + 'px, 0)';
}
const updateCoordinates = e => {
  mouse.x = e.clientX;
  mouse.y = e.clientY;
}
window.addEventListener('mousemove', updateCoordinates);
function loop() {
  updatePosition();
  requestAnimationFrame(loop);
}
requestAnimationFrame(loop);
// xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx