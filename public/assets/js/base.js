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

