// shop filters 
const filterBtn = document.querySelector("#filterBtn")
const sortBtn = document.querySelector("#sortBtn")
const filterList = document.querySelector("#filterList")
const sortList = document.querySelector("#sortList")

function filterShop() {
    filterBtn.addEventListener('click', (el) =>{ 
        filterList.style.display = "block"
        sortList.style.display = "none"
    })
}
filterShop()
function sortShop() {
    sortBtn.addEventListener('click', (el) =>{ 
        filterList.style.display = "none"
        sortList.style.display = "block"
    })
}
sortShop()

// xxxxxx---> FOR FUN <------xxxx
function displayShop() {
    let shopMain = document.querySelector(".shopMain")
    for (let i = 1; i < 61; i++) {
        let product = `    <div>
        <figure><img src="/assets/img/products/chaises${i}.jpg"></figure>
        <h1>Chaise</h1>
        <section class="buyBox">
            <h2>Vamesa<h2>
            <div>
                <p>299€</p>
                <p>buy</p>
            </div>
        </section>
    </div>`
        shopMain.insertAdjacentHTML("beforeend", product)
        i++;

    }
}
displayShop()
