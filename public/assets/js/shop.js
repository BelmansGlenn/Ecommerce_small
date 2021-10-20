// products filters
const filterBtn = document.querySelector("#filterBtn")
const sortBtn = document.querySelector("#sortBtn")
const filterList = document.querySelector("#filterList")
const sortList = document.querySelector("#sortList")

function filterShop() {
    filterBtn.addEventListener('click', (el) =>{ 
        filterList.style.display = "block"
        sortList.style.display = "none"
        filterBtn.addEventListener('click', (el) =>{ 
            filterList.style.display = "none"
            filterShop()
        })
    })
}
filterShop()
function sortShop() {
    sortBtn.addEventListener('click', (el) =>{ 
        filterList.style.display = "none"
        sortList.style.display = "block"
        sortBtn.addEventListener('click', (el) =>{ 
            sortList.style.display = "none"
            sortShop()
        })
    })
}
sortShop()

